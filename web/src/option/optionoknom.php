<?php

/*
* Mise a jour du nom de groupe
*/

/**
 * Created by PhpStorm.
 * User: Emachines1
 * Date: 20/07/2015
 * Time: 15:40
 */

    include '../dbgpw.php';
    include '../dbconnect2.php';

    session_start();

    $nomBaliseNew = $_GET["nomBaliseNew"];
    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $idTracker=$_GET["Id_Tracker"];

	
	// Connexion à la base groupeware pour recuperer l'Id_Base et mettre à jour Nom_Balise en fonction de Id_Base et Id_Tracker
    $connection = mysqli_connect($server, $db_user, $db_pass, $database);
	mysqli_set_charset($connection, "utf8");
	$sql = "SELECT Id_Base FROM gpwbd WHERE NomBase = '$nomDatabaseGpw' LIMIT 0,1";		// R?cup?ration de l'Id_Base
    if($result = mysqli_query($connection, $sql))
	{
		if($row = mysqli_fetch_assoc($result))
		{
			$Id_Base = $row["Id_Base"];
			mysqli_free_result($result);
			
			$sql = "UPDATE gpwbalise SET Nom_Balise = '$nomBaliseNew' WHERE Id_Balise = '$idTracker' AND Id_Base = '$Id_Base' ";	// Mise à jour du nom du groupe dans "gpwbalise"
			mysqli_query($connection, $sql);
		}
	}
	mysqli_close($connection);
	// fin Connexion à la base groupeware
	
	
	// Mise à jour ttrackers
    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;
    $connection2 = mysqli_connect($server, $db_user_2, $db_pass_2, $database);
    mysqli_set_charset($connection2, "utf8");
    $sql2 = "UPDATE ttrackers SET Nom_Tracker = '$nomBaliseNew' WHERE Id_tracker = '$idTracker' ";
    mysqli_query($connection2, $sql2);
    mysqli_close($connection2);



    // echo $database;

?>