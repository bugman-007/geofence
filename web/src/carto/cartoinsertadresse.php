<?php

/*
* Enregistre l'adresse dans la bdd
 *
*/


	/************* R�cup�rer les infos de la new POI *****************/
	include '../function.php';
	include '../dbconnect2.php';
	$address = addslashes($_GET['address']);
	$lat = $_GET['lat'];
	$lng = $_GET['lng'];
	$dateTime = date("Y-m-d H:i:s", strtotime($_GET["datetime"]));
	$idTracker = $_GET['idTracker'];
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];

	/************* Update POI *******************/
	$connectTpoi =  mysqli_connect($ipDatabaseGpw,$db_user_2,$db_pass_2,$nomDatabaseGpw);
	if (!$connectTpoi) {
		die('Not connected : ' . mysqli_connect_error());
	}
	mysqli_set_charset($connectTpoi, "utf8");
	
	$queryInsertTpoi = mysqli_query($connectTpoi,"UPDATE tpositions0 SET Pos_Adresse = '$address' WHERE Pos_Latitude = '$lat' AND Pos_Longitude = '$lng' AND Pos_Id_tracker = '$idTracker' AND Pos_DateTime_position = '$dateTime' ");
	$queryInsertTpoi = mysqli_query($connectTpoi,"UPDATE tpositions SET Pos_Adresse = '$address' WHERE Pos_Latitude = '$lat' AND Pos_Longitude = '$lng' AND Pos_Id_tracker = '$idTracker' AND Pos_DateTime_position = '$dateTime' ");
	//$queryInsertTpoi = mysqli_query($connectTpoi,"UPDATE tpositions0 SET Pos_Adresse = '$address' WHERE Pos_Latitude = '$lat' AND Pos_Longitude = '$lng' AND Pos_DateTime_position = '$dateTime' ");
	//$queryInsertTpoi = mysqli_query($connectTpoi,"UPDATE tpositions SET Pos_Adresse = '$address' WHERE Pos_Latitude = '$lat' AND Pos_Longitude = '$lng' AND Pos_DateTime_position = '$dateTime' ");
	echo $dateTime;
	
	mysqli_close($connectTpoi);
?>
