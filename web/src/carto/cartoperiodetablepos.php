<?php

	/*
	 * 	Affiche le tableau pour l'historique de periode
	 * 	Carto:js
	 */

header( 'content-type: text/html; charset=utf-8' );

session_start();
$_SESSION['CREATED'] = time();
error_reporting(0);
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


if( (substr($_SESSION['language'],-2) == "US"))$formatLangDateTime = "Y-m-d h:i:s A"; else $formatLangDateTime = "Y-m-d H:i:s";
?>
<style type="text/css">
.sortable tr:hover {
    cursor: pointer;
}
</style>
<table id="idTablePosition" class="sortable table table-bordered table-hover" >

<?php
	set_time_limit(0);
	include '../function.php';
//	function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
//		$theta = $longitude1 - $longitude2;
//		$miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
//		$miles = acos($miles);
//		$miles = rad2deg($miles);
//		$miles = $miles * 60 * 1.1515;
//		$feet = $miles * 5280;
//		$yards = $feet / 3;
//		$kilometers = $miles * 1.609344;
//		$meters = $kilometers * 1000;
//		return round($kilometers);
//	}

	function getDistanceBetweenPointsNew($lat1, $lng1, $lat2, $lng2) {
		$earth_radius = 6378137;   // Terre = sph?re de 6378km de rayon
		$r = 6371;   // Terre = sph?re de 6378km de rayon
		$rlo1 = deg2rad($lng1);
		$rla1 = deg2rad($lat1);
		$rlo2 = deg2rad($lng2);
		$rla2 = deg2rad($lat2);
		$dlo = ($rlo2 - $rlo1) / 2;
		$dla = ($rla2 - $rla1) / 2;
		$a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo
						));
		$d = 2 * atan2(sqrt($a), sqrt(1 - $a));
		return $r * $d * 1000;
	}



	$distanceFiltree=$_GET["distanceFiltree"];

	$q=$_GET["Id_Tracker"];
	$d=$_GET["debut"];
	$f=$_GET["fin"];
	$nomBalise=$_GET["nomBalise"];
	$filtrage=$_GET["filtrage"];
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
	
	$timezone=$_GET["timezone"];
	$dUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($d)),$timezone);
	$fUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($f)),$timezone);
	$iconesDirectionVitesse = array();
	$cbalise = array();			// Icone position
	
	//PARAMETRE BDD GLOBALE
	$database = $nomDatabaseGpw;  // the name of the database.
	$server = $ipDatabaseGpw;  // server to connect to.
	include '../dbconnect2.php';

	//CONNEXION BDD GLOBALE
	$connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
	mysqli_set_charset($connection, "utf8");
	$numPage = $_GET["numPage"];
	if($numPage == "1") $myPosition = 0;
	else if((intval($numPage)) > 1 ) $myPosition = ((intval($numPage)) - 1) * 1000 ;
	else $myPosition = (intval($numPage)) * 1000 ;
	$limitPosition =  1000;
	$numPageInt = $myPosition;

	include('../dbtpositions.php');
	$sql = "";
	$arrayTpositions = getAllPeriodTpositions($d,$f);
	$i = 0;

	$queryFetchArray = function($result,$timezone,$cbalise,$filtrage,$numPageInt,$nomBalise,$formatLangDateTime,
								$distanceFiltree,$db_user_2, $db_pass_2)
	{
		echo "<tbody id=\"body_idTablePosition\">";
		$q=$_GET["Id_Tracker"];
		
		$i = 0;
		$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
		$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
		$UtilisationConfidentialité = 0;
		$detectStop = "";
		$detectCoordStopLat  = "";
		$detectCoordStopLng  = "";
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
			
		// 	$UtilisationConfidentialité = 1;
		// }
		
		// Passage des resultats de la requete de positions
		while ($row = mysqli_fetch_array($result))
		{
			$utc_date = DateTime::createFromFormat(
					'Y-m-d H:i:s',
					$row['Pos_DateTime_position'],
					new DateTimeZone('UTC')
			);
			$local_date = $utc_date;
			$local_date->setTimeZone(new DateTimeZone($timezone));
			ini_set('display_errors', 'off');

			
			if($UtilisationConfidentialité == 1)
			{
				$dateNewDateTime = new DateTime();
				$PosNonConfidentielle = 0;
		
				if ( ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0].$Hd1[1]),intval($Hd1[2].$Hd1[3]))->format("H:i:s")))
						&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0].$Hf1[1]),intval($Hf1[2].$Hf1[3]))->format("H:i:s"))	)	 )
					||	( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0].$Hd2[1]),intval($Hd2[2].$Hd2[3]))->format("H:i:s")))
						&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0].$Hf2[1]),intval($Hf2[2].$Hf2[3]))->format("H:i:s"))	)	 )  ) 
				{


					if( ($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1) == "Monday") ||
						($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1) == "Tuesday") ||
						($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1) == "Wednesday") ||
						($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1) == "Thursday") ||
						($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1) == "Friday") ||
						($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1) == "Saturday") ||
						($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"),$local_date->format("d"),$local_date->format("Y")),1) == "Sunday") )
					{
						$PosNonConfidentielle = 1;
					}
				}
			}
			else
			{
				$PosNonConfidentielle = 1;
			}		
	/*		
	// GPS Reception valide ?
	if($Pos_Statut & 0x00000020){
		$infoGPS = "1";
	}else{
		$infoGPS = "0";
	}
	// CONTACT
	if($Pos_Statut & 0x00000004){
		$statutSTOP = "1";
	}else {
		$statutSTOP = "0";
	}*/

			// Si position non confidentielle
			if($PosNonConfidentielle == 1)
			{
				$PosNonFiltree = 0;
				
				if($row['Pos_Statut'] & 0x00000020)	// GPS Valide
				{
					if ($filtrage == "yes") {
						if (($cbalise[$i] == "<img src='../../assets/img/ICONES/stop16.ico'>")) {
							if ((((/*$cbalise[$i - 1] != "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>" && */$cbalise[$i - 1] != "<img src='../../assets/img/ICONES/stop16.ico'>") && $cbalise[$i] == "<img src='../../assets/img/ICONES/stop16.ico'>"))) {
								$PosNonFiltree = 1;
								$detectStop = "1";
								$detectCoordStopLat = $row['Pos_Latitude'];
								$detectCoordStopLng = $row['Pos_Longitude'];
							}
						} else {
							if ($cbalise[$i] == lireDirectionVitesse($row['Pos_Direction'], round($row['Pos_Vitesse']))) {
								if ($detectStop == "1") {
									if ((getDistanceBetweenPointsNew($detectCoordStopLat, $detectCoordStopLng, $row['Pos_Latitude'], $row['Pos_Longitude'])) >= $distanceFiltree) {
										$PosNonFiltree = 1;
										$detectStop = 0;
									}
								} else {
									$PosNonFiltree = 1;
								}
							} else {
								$PosNonFiltree = 1;
							}
						}
					} else if ($filtrage == "no") {
						if ($distanceFiltree != ("0" || "")) {
							if (($cbalise[$i] == "<img src='../../assets/img/ICONES/stop16.ico'>" && $cbalise[$i + 1] != "<img src='../../assets/img/ICONES/stop16.ico'>") && 
								($cbalise[$i] == "<img src='../../assets/img/ICONES/stop16.ico'>" && $cbalise[$i + 1] != "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>")) {
								$PosNonFiltree = 1;
								$detectStop = "1";
								$detectCoordStopLat = $row['Pos_Latitude'];
								$detectCoordStopLng = $row['Pos_Longitude'];
							}
							if ($cbalise[$i] == lireDirectionVitesse($row['Pos_Direction'], round($row['Pos_Vitesse']))) {
								if ($detectStop == "1") {
									$PosNonFiltree = 1;
									$detectStop = 0;
								} else {
									if ((getDistanceBetweenPointsNew($detectCoordStopLat, $detectCoordStopLng, $row['Pos_Latitude'], $row['Pos_Longitude'])) >= $distanceFiltree) {
										$PosNonFiltree = 1;
									}
								}
							}
						} else {
							$PosNonFiltree = 1;
						}
					}
				}
				else	// GPS NON Valide
				{
					if ($filtrage == "yes") {
						if (($cbalise[$i] == "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>")) {
							if ((($cbalise[$i - 1] != "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>" /*&& $cbalise[$i - 1] != "<img src='../../assets/img/ICONES/stop16.ico'>")*/ && $cbalise[$i] == "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>"))) {
								$PosNonFiltree = 1;
								$detectStop = "1";
								$detectCoordStopLat = $row['Pos_Latitude'];
								$detectCoordStopLng = $row['Pos_Longitude'];
							}
						} else {
							if ($cbalise[$i] == "<img src='../../assets/img/ICONES/noGPS.ico'>") {
								if ($detectStop == "1") {
									if ((getDistanceBetweenPointsNew($detectCoordStopLat, $detectCoordStopLng, $row['Pos_Latitude'], $row['Pos_Longitude'])) >= $distanceFiltree) {
										$PosNonFiltree = 1;
										$detectStop = 0;
									}
								} else {
									$PosNonFiltree = 1;
								}
							} else {
								$PosNonFiltree = 1;
							}
						}
					} else if ($filtrage == "no") {
						if ($distanceFiltree != ("0" || "")) {
							if (($cbalise[$i] == "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>" && $cbalise[$i + 1] != "<img src='../../assets/img/ICONES/stop16.ico'>") && ($cbalise[$i] == "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>" && $cbalise[$i + 1] != "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>")) {
								$PosNonFiltree = 1;
								$detectStop = "1";
								$detectCoordStopLat = $row['Pos_Latitude'];
								$detectCoordStopLng = $row['Pos_Longitude'];
							}
							if ($cbalise[$i] == "<img src='../../assets/img/ICONES/noGPS.ico'>") {
								if ($detectStop == "1") {
									$PosNonFiltree = 1;
									$detectStop = 0;
								} else {
									if ((getDistanceBetweenPointsNew($detectCoordStopLat, $detectCoordStopLng, $row['Pos_Latitude'], $row['Pos_Longitude'])) >= $distanceFiltree) {
										$PosNonFiltree = 1;
									}
								}
							}
						} else {
							$PosNonFiltree = 1;
						}
					}
				}
				
				// Ajout de la position à la suite dans la table pos
				if($PosNonFiltree == 1)
				{
					//Decodage Status
					$DecodedStatus = DecodeStatus($row['Pos_Statut'], $row['Pos_Odometre'], $row['Pos_Key'], $row['Statut2'], $row['BattInt'], $row['BattExt'], $row['Alim'], $row['TypeServer']);
					
					//Icone brouilleur
					$brouilleur = IconeBrouilleur($row['Pos_Odometre'],$row['Pos_Statut']);
					
					//Icone Defaut Alim/BatExt et BatInt
					$defaultAlim = IconeDefautAlim($row['Pos_Statut']);
					
					//forçage vitesse à 0 quand stop
					if($row['Pos_Statut'] & 0x00000004)
						$vitesse = round($row['Pos_Vitesse']);
					else 
						$vitesse = "0";
					
					// echo de la ligne position dans la table positions
					echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td>";
					if($_SESSION['username'] == "SUPERVISEUR"){
						echo "<td>" . $row['Pos_DateTime_reception'] . "</td>";
					}else{
						echo "<td style='display:none'></td>";
					}
					echo "<td>" . $row['Pos_Adresse'] . "</td><td>" . $vitesse . "</td><td>" . $DecodedStatus . "</td>" . $brouilleur . "<td>" . $defaultAlim . "</td>";
					echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
				}
				
				$numPageInt++;
			}
			$i++;
		}
		
		// mysqli_free_result($result2);
		// mysqli_close($connection2);

		echo "</tbody>";
	};
	
	// Creation du header table position
	echo '<thead id="head_idTablePosition"><tr><th width="40px">N°</th><th width="35px"></th><th width="130px">';
	echo _('nombalise');
	echo '</th><th width="120px">Date position</th>';		// traduction manquante
	if($_SESSION['username'] == "SUPERVISEUR")
	{
		echo '<th width="120px">Date reception</th>';		// traduction manquante
	}
	else
	{
		echo '<th width="120px" style="display:none">Date reception</th>';		// traduction manquante
	}
	echo '<th width="400px">';
	echo _('adresse');
	echo '</th><th width="50px">';
	echo _('vitesse');
	echo '</th><th width="350px">';
	echo _('statut');
	echo '</th><th width="45px">GSM</th><th width="45px">';
	echo _('alim');
	echo '</th>';
	echo '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr></thead>';
	//echo '<tbody id="body_idTablePosition">';
	
	if (sizeof($arrayTpositions) > 0 )
	{
		for ($i = 0; $i < sizeof($arrayTpositions); $i++) {
			// SELECT Pos_DateTime_reception,Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse,Pos_Key,Statut2,BattInt,BattExt,Alim,TypeServer
			$sql .= "SELECT * FROM $arrayTpositions[$i]
			WHERE (Pos_DateTime_position BETWEEN '".$dUTC."' AND '".$fUTC."' ) AND (Pos_Id_tracker = '".$q."' )
			ORDER BY (Pos_DateTime_position) LIMIT  ".$myPosition.",".$limitPosition." ;";
		}
		if (mysqli_multi_query($connection, $sql))
		{
			
			do{
				if ($result = mysqli_store_result($connection) )
				{
					$i=0;
					while ($row = mysqli_fetch_array($result))
					{
						$cbalise[$i] = IconeBalise($row['Pos_Statut'], $row['Pos_Vitesse'], $row['Pos_Direction']);
						$i++;
					}
					mysqli_free_result($result);
				}
			} while (mysqli_more_results($connection) && mysqli_next_result($connection));
			
			if (mysqli_multi_query($connection, $sql))
			{
				do {
					if ($result = mysqli_store_result($connection) )
					{
						$queryFetchArray($result,$timezone,$cbalise,$filtrage,$numPageInt,$nomBalise,$formatLangDateTime,
										$distanceFiltree,$db_user_2, $db_pass_2) ;
						mysqli_free_result($result);
					}
				} while (mysqli_more_results($connection) && mysqli_next_result($connection));
			}
		}
	}
	else
	{
		// SELECT Pos_DateTime_reception,Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse,Pos_Key,Statut2,BattInt,BattExt,Alim,TypeServer
		$sql = "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse,Pos_Key,Statut2,BattInt,BattExt,Alim,TypeServer
				FROM $arrayTpositions[0] WHERE (Pos_DateTime_position BETWEEN '".$dUTC."' AND '".$fUTC."' ) AND (Pos_Id_tracker = '".$q."' )
				ORDER BY Pos_DateTime_position LIMIT ".$myPosition.",".$limitPosition." ";
		$result = mysqli_query($connection,$sql);

		if( $result !== false )
		{
			$i = 0;
			while ($row = mysqli_fetch_array($result)) {
				$cbalise[$i] = IconeBalise($row['Pos_Statut'], $row['Pos_Vitesse'], $row['Pos_Direction']);
				$i++;
			}
			mysqli_free_result($result);

			$result = mysqli_query($connection, $sql);
			$queryFetchArray($result,$timezone,$cbalise,$filtrage,$numPageInt,$nomBalise,$formatLangDateTime,
							$distanceFiltree,$db_user_2, $db_pass_2) ;

			mysqli_free_result($result);
		}
	}

	mysqli_close($connection);
?>
</table>