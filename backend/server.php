<?php
//Bloque de conexion a Mysql
$servername = "localhost";
$username = "innopbbl_admon";
$password = "aarm1989";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$api = $_GET["api"];

// update bus position (driver)
if($api == "updatePosition"){

	try {
		
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
	
		$sql = "UPDATE innopbbl_bus_tracker.coordinates SET"
		. " lat = $request->lat, "
		. " lng = $request->lng "
		. " WHERE busId=$request->idBus;";
		
		if ($conn->query($sql) === TRUE) {
			echo "Record updated successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	
	} catch (Exception $e) {
		echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
	} 

}


// get bus positions (user)
if($api == "getPositionByRoute"){

	try {
		
		$postdata = file_get_contents("php://input");
		$request = substr($postdata, 1, -1);  // remove the firts and last character.
	
		$sql = "SELECT * FROM innopbbl_bus_tracker.coordinates WHERE busId IN ($request);";

		$result_json = "[";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {

			while($row = $result->fetch_assoc()) {

				$result_json = $result_json."{\"busId\":".$row["busId"].",\"routeId\":".$row["routeId"].",\"lat\":".$row["lat"].",\"lng\":".$row["lng"]."},";
			}

			$result_json = substr($result_json, 0, -1)."]";
			echo $result_json;
		} else {
			echo "[{\"message\":\"query rusult empty.\"}]";
		}
	
	} catch (Exception $e) {
		echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
	} 

}


$conn->close();


function output($data)
{

	$fichero = 'output.txt';
	// Abre el fichero para obtener el contenido existente
	$actual = file_get_contents($fichero);
	// A単ade una nueva persona al fichero
	$actual .= $data;
	// Escribe el contenido al fichero
	file_put_contents($fichero, $actual);
}
?>