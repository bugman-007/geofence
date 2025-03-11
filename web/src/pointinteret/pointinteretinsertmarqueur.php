<?php

	/*
	 * Ajouter un poi dans la base
	 */

	include '../dbgpw.php';
	include '../dbconnect2.php';
	include '../ChromePhp.php';
	/*************** Recuperer la session avec le login de l'utilisateur ************/
	session_start();
	$_SESSION['username'];
	
	/************* R�cup�rer les infos de la new POI *****************/
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
	$name = $_GET['name'];
	$address = $_GET['address'];
	$lat = $_GET['lat'];
	$lng = $_GET['lng'];
	// $type = $_GET['type'];
	$rayon = $_GET['rayon'];
	$description = $_GET['description'];
	$lien = $_GET['lien'];
	
	/************* Recuperer l'Id_Client de l'utilisateur *******************/
	$connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
	$queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Client,Id_Base FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' AND Id_GPW != 0)");
	$assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
	$idBase = $assocGpwUser['Id_Base'];
	$idClient = abs($assocGpwUser['Id_Client']);
	mysqli_free_result($queryGpwUser);
	mysqli_close($connectGpwUser);

	/************* Insertion POI *******************/
	// $lat = str_replace(".",",",$lat);
	// $lng = str_replace(".",",",$lng);
	$connectTpoi =  mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
	mysqli_set_charset($connectTpoi, "utf8");
	$queryInsertTpoi = mysqli_query($connectTpoi,"INSERT INTO tpoi (IdClient,Name,description,latitude,longitude,adresse,lien,Id_Base,Rayon) VALUES ('$idClient', '$name' , '$description', '$lat', '$lng' , '$address' , '$lien', '$idBase', '$rayon')");
	// if (!$queryInsertTpoi)
	// {
	// 	ChromePhp::log(mysqli_error($connectTpoi))
	// }
	mysqli_close($connectTpoi);

	$link = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
	mysqli_set_charset($link, "utf8");
	$match = "SELECT * from tpoi WHERE IdClient='".$idClient."' AND Name='".$name."' AND adresse='".$address."' AND Id_Base='".$idBase."'";
	$result = mysqli_query($link,$match);

	while($row = mysqli_fetch_array($result)){
		echo $row['Id'];

	}
	mysqli_free_result($result);
	mysqli_close($link);
?>
