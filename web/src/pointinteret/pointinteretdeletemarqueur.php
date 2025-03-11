<?php

/*
* Effacer un poi
*/


	include '../dbgpw.php';
	include '../dbconnect2.php';

	/************* Récupérer les infos de la new POI *****************/
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
	$idPoi = $_GET['idPoi'];

	/************* DELETE POI *******************/
	$connectTpoi =  mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
	$querydDeleteTpoi = mysqli_query($connectTpoi,"DELETE FROM tpoi WHERE Id = '".$idPoi."' ");
	mysqli_close($connectTpoi);
?>
