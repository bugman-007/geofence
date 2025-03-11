<?php

	/*
	 * 	Recuperation javascript par ajax des données pour les positions sur historique periode
	 * 	Carto:js
	 */
	
	header( 'content-type: text/html; charset=utf-8' );

	session_start();
	$_SESSION['CREATED'] = time();
	
	require_once ("../../../lib/php-gettext-1.0.12/gettext.inc");
	$locale = "fr_FR";
	if (isset($_SESSION["language"])) {
		$locale = $_SESSION['language'];
	}else{
		$_SESSION['language'] = "fr_FR";
		$locale = "fr_FR";
	}
	T_setlocale(LC_MESSAGES, $locale);
	$encoding = "UTF-8";
	$domain = "messages";
	bindtextdomain($domain, '../../../locale');
	bind_textdomain_codeset($domain, $encoding);
	textdomain($domain);
	
	if( (substr($_SESSION['language'],-2) == "US"))
		$formatLangDateTime = "Y-m-d h:i:s A";
	else
		$formatLangDateTime = "Y-m-d H:i:s";

	include('../dbtpositions.php');
	include '../function.php';
	set_time_limit(0);
	
	//INITIALISATION VARIABLE
	$q=$_GET["Id_Tracker"];
	$d=$_GET["debut"];
	$f=$_GET["fin"];
	$timezone=$_GET["timezone"];	
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];	
	
	$dUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($d)),$timezone);
	$fUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($f)),$timezone);

	//PARAMETRE BDD GLOBALE
	$database = $nomDatabaseGpw;  // the name of the database.
	$server = $ipDatabaseGpw;  // server to connect to.
	include '../dbconnect2.php';

	//CONNEXION BDD GLOBALE
	$connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
	if (!$connection) {
	  die('Not connected : ' . mysqli_connect_error());
	}
	mysqli_set_charset($connection, "utf8");
	$sql = "";
	$arrayTpositions = getAllPeriodTpositions($d,$f);
	$i = 0;

	$queryFetchArray = function($result,$timezone,$formatLangDateTime,$db_user_2, $db_pass_2) {
		$q=$_GET["Id_Tracker"];

		$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
		$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
		$NbrPlage="";
		$Hd1="";
		$Hf1="";
		$Hd2="";
		$Hf2="";
		$Lundi="";
		$Mardi="";
		$Mercredi="";
		$Jeudi="";
		$Vendredi="";
		$Samedi="";
		$Dimanche="";
		//PARAMETRE BDD GLOBALE
		$database = $nomDatabaseGpw;  // the name of the database.
		$server = $ipDatabaseGpw;  // server to connect to.
		// $connection2=mysqli_connect($server,$db_user_2, $db_pass_2,$database);
		// $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $q . "' )";
		// $result2 = mysqli_query($connection2,$sql2);

		// if(mysqli_num_rows($result2) > 0 ) {

		// 	while ($row2 = mysqli_fetch_array($result2)) {
		// 		$NbrPlage= $row2['NbrPlage'];
		// 		$Hd1= $row2['Hd1'];
		// 		$Hf1=$row2['Hf1'];
		// 		$Hd2= $row2['Hd2'];
		// 		$Hf2= $row2['Hf2'];
		// 		$Lundi=$row2['Lundi'];
		// 		$Mardi=$row2['Mardi'];
		// 		$Mercredi=$row2['Mercredi'];
		// 		$Jeudi=$row2['Jeudi'];
		// 		$Vendredi= $row2['Vendredi'];
		// 		$Samedi= $row2['Samedi'];
		// 		$Dimanche= $row2['Dimanche'];
		// 	}
		// 	while ($row = mysqli_fetch_array($result)) {
		// 		$utc_date = DateTime::createFromFormat(
		// 				'Y-m-d H:i:s',
		// 				$row['Pos_DateTime_position'],
		// 				new DateTimeZone('UTC')
		// 		);
		// 		$local_date = $utc_date;
		// 		$local_date->setTimeZone(new DateTimeZone($timezone));

		// 		$dateNewDateTime = new DateTime();
		// 		if ( ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0].$Hd1[1]),intval($Hd1[2].$Hd1[3]))->format("H:i:s")))
		// 				&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0].$Hf1[1]),intval($Hf1[2].$Hf1[3]))->format("H:i:s"))	)	 )
		// 			||	( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0].$Hd2[1]),intval($Hd2[2].$Hd2[3]))->format("H:i:s")))
		// 				&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0].$Hf2[1]),intval($Hf2[2].$Hf2[3]))->format("H:i:s"))	)	 )  )
		// 		{

		// 			if( ($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1) == "Monday") ||
		// 				($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1) == "Tuesday") ||
		// 				($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1) == "Wednesday") ||
		// 				($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1) == "Thursday") ||
		// 				($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1) == "Friday") ||
		// 				($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1) == "Saturday") ||
		// 				($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1) == "Sunday") ) 
		// 			{
		// 				echo "P_Lat:" . $row['Pos_Latitude'] . " ";
		// 				//echo "P_DTime:" . jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1);
		// 				echo "P_DTime:" . $local_date->format($formatLangDateTime);
		// 				echo "P_Stat:" . $row['Pos_Statut'];
		// 				echo "P_Vit:" . round( $row['Pos_Vitesse'] );
		// 				echo "P_Dir:" . round( $row['Pos_Direction'] );
		// 				echo "P_Odo:" . round( $row['Pos_Odometre'] );
		// 				//echo "P_Adr:" . $row['Pos_Adresse'];
		// 				echo "P_Adr:" . str_replace("&", "et", $row['Pos_Adresse']);
		// 				echo "P_Key:" . $row['Pos_Key'];
		// 				echo "Stat2:" . round( $row['Statut2'] );
		// 				echo "BtInt:" . round( $row['BattInt'] );
		// 				echo "BtExt:" . round( $row['BattExt'] );
		// 				echo "Alim:" . round( $row['Alim'] );
		// 				echo "TypSrv:" . $row['TypeServer'];
		// 				echo "P_Lon: " . $row['Pos_Longitude'] . "&";
		// 			}
		// 		}
		// 	}
		// }else{
			while ($row = mysqli_fetch_array($result)) {
				$utc_date = DateTime::createFromFormat(
						'Y-m-d H:i:s',
						$row['Pos_DateTime_position'],
						new DateTimeZone('UTC')
				);
				$local_date = $utc_date;
				$local_date->setTimeZone(new DateTimeZone($timezone));


				echo "P_Lat:" . $row['Pos_Latitude'] . " ";
				echo "P_DTime:" . $local_date->format($formatLangDateTime);
				echo "P_Stat:" . $row['Pos_Statut'];
				echo "P_Vit:" . round( $row['Pos_Vitesse'] );
				echo "P_Dir:" . round( $row['Pos_Direction'] );
				echo "P_Odo:" . round( $row['Pos_Odometre'] );
				//echo "P_Adr:" . $row['Pos_Adresse'];
				echo "P_Adr:" . str_replace("&", "et", $row['Pos_Adresse']);
				echo "P_Key:" . $row['Pos_Key'];
				echo "Stat2:" . round( $row['Statut2'] );
				echo "BtInt:" . round( $row['BattInt'] );
				echo "BtExt:" . round( $row['BattExt'] );
				echo "Alim:" . round( $row['Alim'] );
				echo "TypSrv:" . $row['TypeServer'];
				echo "P_Lon: " . $row['Pos_Longitude'] . "&";

			}
		//}
		// mysqli_free_result($result2);
		// mysqli_close($connection2);


	};

	if (sizeof($arrayTpositions) > 1 ) {
		for ($i = 0; $i < sizeof($arrayTpositions); $i++) {
			$sql .= "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Key, Pos_Adresse, Statut2, BattInt, BattExt, Alim, TypeServer
						FROM $arrayTpositions[$i] WHERE (Pos_DateTime_position BETWEEN '" . $dUTC . "' AND '" . $fUTC . "' ) AND (Pos_Id_tracker = '" . $q . "' )
						ORDER BY Pos_DateTime_position;";
		}
		if (mysqli_multi_query($connection, $sql)) {
			do {
				if ($result = mysqli_store_result($connection)) {
					$queryFetchArray($result,$timezone,$formatLangDateTime,$db_user_2, $db_pass_2);
					mysqli_free_result($result);
				}
			} while (mysqli_more_results($connection) && mysqli_next_result($connection));
		}
	}else{
		$sql = "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Key, Pos_Adresse, Statut2, BattInt, BattExt, Alim, TypeServer
					FROM $arrayTpositions[0] WHERE (Pos_DateTime_position BETWEEN '" . $dUTC . "' AND '" . $fUTC . "' ) AND (Pos_Id_tracker = '" . $q . "' )
					ORDER BY Pos_DateTime_position";

		$result = mysqli_query($connection,$sql);
		if( $result !== false ) {

			$queryFetchArray($result,$timezone,$formatLangDateTime,$db_user_2, $db_pass_2);
		}
		mysqli_free_result($result);
	}
	mysqli_close($connection);

?> 