<?php

/*
 * Récupérer les info pour afficher le poi (pour javascript)
 */
	session_start();

	$_SESSION['CREATED'] = time();
	include '../dbgpw.php';

	/************* Recuperer l'Id_Client de l'utilisateur *******************/
	$connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
	$queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Client,Id_Base FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' AND Id_GPW != 0)");
	$assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
	$idBase = $assocGpwUser['Id_Base'];
	$idClient = abs($assocGpwUser['Id_Client']);
	mysqli_free_result($queryGpwUser);
	mysqli_close($connectGpwUser);


	include '../dbconnect2.php';
	$table = "tpoi";
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
	$link = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
	$match = "SELECT * from $table WHERE IdClient='$idClient' ";
	$result = mysqli_query($link,$match);
	$i=0;
	$rowCount = mysqli_num_rows($result);
	while($row = mysqli_fetch_array($result)){
		 echo "t".$rowCount."g";
		echo "idPoi:".$row['Id'];
		echo "Latitude:" . $row['latitude'];
		echo "Longitude:" . $row['longitude'] ;
		echo "Name:" . $row['Name'] ;
		echo "Adresse:" . $row['adresse'] ;
		echo "Rayon:" . $row['Rayon'] . "&";

	}
	mysqli_free_result($result);
	mysqli_close($link);

?>