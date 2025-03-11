<?php

    /*
     *Recuperer le tplanning pour javascript avec AJAX
	* Carto.js
	*/


	session_start();
	$_SESSION['CREATED'] = time();

	include('../dbtpositions.php');
	set_time_limit(0);

	//INITIALISATION VARIABLE

	$q=$_GET["Id_Tracker"];

	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];

	//PARAMETRE BDD GLOBALE
	$database = $nomDatabaseGpw;  // the name of the database.
	$server = $ipDatabaseGpw;  // server to connect to.
	include '../dbconnect2.php';

	//CONNEXION BDD GLOBALE
	$connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
	mysqli_set_charset($connection, "utf8");
	$sql = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $q . "' )";
	$result = mysqli_query($connection,$sql);
	if( $result !== false ) {

		while ($row = mysqli_fetch_array($result)) {
			echo "NbrPlage:" . $row['NbrPlage'];
			echo "Hd1:" . $row['Hd1'];
			echo "Hf1:" . $row['Hf1'];
			echo "Hd2:" . $row['Hd2'];
			echo "Hf2:" . $row['Hf2'];
			echo "Lundi:" . $row['Lundi'];
			echo "Mardi:" . $row['Mardi'];
			echo "Mercredi:" . $row['Mercredi'];
			echo "Jeudi:" . $row['Jeudi'];
			echo "Vendredi:" . $row['Vendredi'];
			echo "Samedi:" . $row['Samedi'];
			echo "Dimanche:" . $row['Dimanche']  . "&";
		}
	}
	mysqli_free_result($result);
	mysqli_close($connection);

	?>