<?php
    /*
    * Synchronisation de la mémoire (fichier log)
    *
    */
	session_start();
	include '../dbgpw.php';
	include '../dbconnect2.php';

	$_SESSION['CREATED'] = time();

	$idTracker=$_GET["idTracker"];
	$numeroAppel=$_GET["numeroAppel"];
	if($numeroAppel[1] == "3" && $numeroAppel[2] == "3") $numeroAppel[0] = "+";
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
	$datetime=$_GET["datetime"];
	$modeMessage=$_GET["modeMessage"];
	
	/************* Recuperer idClient *******************/
	$connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
	$queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Client FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' )");
	$assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
	$idClientGpwUser = $assocGpwUser['Id_Client'];
	mysqli_close($connectGpwUser);
	
	/***********Definition Insert *****************/
	if($modeMessage == "GPRS"){
		$typeMsg = "1";
		$dest = $idTracker;
		$corps = "?M!";
	}else if($modeMessage == "SMS"){
		$typeMsg = "2";
		$dest = $numeroAppel;
		$corps = "#M&";
	}
	$sujet = html_entity_decode("Synchronisation de la m&eacute;moire");

	
	/************Insert into tmessages *************/
	$connection=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
	mysqli_set_charset($connection, "utf8");
	$sql="INSERT INTO  tmessages (TypeMSG, Dest, Sujet, Corps, Date, Client) VALUES('".$typeMsg."','".$dest."','".$sujet."','".$corps."','".$datetime."','".$idClientGpwUser."') ";
	
	$result = mysqli_query($connection,$sql);

	mysqli_close($connection);
	
?>