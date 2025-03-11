<?php
session_start();
$_SESSION['CREATED'] = time();
?>

<?php
/*
* On recupere les données data 1 et data0 de la balise pour l'etat balise
*/
	function identifierData($data, $data1){
		print("Save GPS trajet:".hexdec(bin2hex(strrev(substr($data, 43,4)))));
		print("Save GPS arret:".hexdec(bin2hex(strrev(substr($data, 47,4)))));

		printf("&");
	}
	//INITIALISATION VARIABLE
	$q=$_GET["idTracker"];
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];

	include '../dbconnect2.php';

	$connection=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
	mysqli_set_charset($connection, "utf8");
	$sql="SELECT * FROM ttrackers WHERE Id_tracker = '".$q."' ";

	$result = mysqli_query($connection,$sql);

	while($row = mysqli_fetch_array($result)){

		 identifierData($row['Datas0'], $row['Datas1']);


	}  

	mysqli_free_result($result);
	mysqli_close($connection);
?>
