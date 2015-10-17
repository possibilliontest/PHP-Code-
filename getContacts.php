<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json');

$conn = mysqli_connect("localhost", "ChekhraApp", "CHKWeb123", 'chekhra_test');

// Check connection

if (mysqli_connect_errno())
	{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

$result = array();

if (isset($_GET[insert]))
	{
	for ($i = 0; $i < 200; $i++)
		{
		$ins1 = "insert INTO contactname (NAME) VALUES ('name$i')";
		mysqli_query($conn, $ins1);
		$id = mysqli_insert_id($conn);
		$phone = rand(1111111111, 9999999999);
		$ins2 = "INSERT INTO contactphone (contact_id, phone) VALUES ($id,'$phone')";
		mysqli_query($conn, $ins2);
		}

	exit(0);
	}

if (!isset($_GET[start]))
	{
	$result[code] = 400;
	$result[status] = "failure";
	$result[error] = "Unable to fetch start value";
	echo json_encode($result);
	exit(0);
	}

if (!isset($_GET[pag]))
	{
	$result[code] = 400;
	$result[status] = "failure";
	$result[error] = "Unable to fetch pagination value";
	echo json_encode($result);
	exit(0);
	}

$start = intval($_GET[start]);
$pagination = intval($_GET[pag]);
$query = "SELECT * FROM contactphone,contactname WHERE contactname.id=contactphone.contact_id AND contactname.id>$start ORDER BY contactname.id LIMIT $pagination";
$res = mysqli_query($conn, $query);
$data = array();

while ($row = mysqli_fetch_assoc($res))
	{
	$rowData = array();
	$rowData[id] = $row[id];
	$rowData[phone] = $row[phone];
	$rowData[name] = $row[name];
	array_push($data, $rowData);
	}

// echo "<pre>".print_r($data)."</pre>";
if(mysqli_affected_rows($conn)<1){
	$result[code] = 400;
	$result[status] = "failure";
	$result[error] = "No Records found";
	echo json_encode($result);
	exit(0);
}
$result[code] = 401;
$result[status] = "success";
$result[data] = $data;
$result[count] = mysqli_affected_rows($conn);
echo json_encode($result);
?>