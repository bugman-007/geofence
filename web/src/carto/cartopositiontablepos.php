<?php

/*
   * 		Affiche le tableau pour l'historique de positions
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

//		function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
//			$theta = $longitude1 - $longitude2;
//			$miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
//			$miles = acos($miles);
//			$miles = rad2deg($miles);
//			$miles = $miles * 60 * 1.1515;
//			$feet = $miles * 5280;
//			$yards = $feet / 3;
//			$kilometers = $miles * 1.609344;
//			$meters = $kilometers * 1000;
//			return round($kilometers);
//		}

	function getDistanceBetweenPointsNew($lat1, $lng1, $lat2, $lng2) {
		$earth_radius = 6378137;   // Terre = sph�re de 6378km de rayon
		$r = 6371;   // Terre = sph�re de 6378km de rayon
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
		$p=$_GET["pos"];
		$n=$_GET["n"];
		$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
		$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
		$filtrage=$_GET["filtrage"];
		$select=$_GET["select"];
		$ordre=$_GET["ordre"];
		$nomBalise=$_GET["nomBalise"];
		$timezone=$_GET["timezone"];

		$pUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($p)),$timezone);

		$niveauReseau = array();
		$cbalise = array();
		$statutSTOP = array();
		$defaultAlim = array();
		$brouilleur = array();
		$statutBrouilleur = array();
		$statutVIB = array();
		$GPS = array();
		$infoGPS = array();
		$alimEtBatterie = array();
		$v = array();
		$volt = array();
		$niveauBat = array();
		$alarm1 = array();
		$alarm2 = array();
		$i = 0;
		$i2= 0;
		$headerTable = 0;
		$detectStop;
		$detectCoordStopLat;
		$detectCoordStopLng;

		//PARAMETRE BDD GLOBALE
		$database = $nomDatabaseGpw;  // the name of the database.
		$server = $ipDatabaseGpw;  // server to connect to.
		include '../dbconnect2.php';

		//CONNEXION BDD GLOBALE
		$connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
		if (!$connection) die('Not connected : ' . mysqli_connect_error());
		mysqli_set_charset($connection, "utf8");

		$numPage = $_GET["numPage"];
		if($numPage == "1") $myPosition = 0;
		else if((intval($numPage)) > 1 ) $myPosition = ((intval($numPage)) - 1) * 1000 ;
		else $myPosition = (intval($numPage)) * 1000 ;
		$n = $n - $myPosition;
		$numPageInt = $myPosition;
		$limitPosition = 1000;
		if(intval($n) >= $limitPosition) $limitPosition =  1000;
		else $limitPosition = $n;

		$tableTPositions = "tpositions";
		$tableTPositions2 = "tpositions";

		$dateNow = strtotime(date('Y-m-d H:i:s'));
		$yearNow = intval(date('Y'));
		$monthNow = intval(date('m'));

		$dateChosen = strtotime($p);

		$secsDifference = $dateNow - $dateChosen;
		$monthDifference = round($secsDifference / (60*60*24*7*4)) ;

		if($monthDifference >= 3){
			$yearChosen = $p[0]."".$p[1]."".$p[2]."".$p[3];
			$monthChosen = $p[5]."".$p[6];
			if(intval($yearChosen) <= 2014){
				$tableTPositions = "tpositions201412";
			}else{
				if( (intval($monthChosen) <= 3)){
					$tableTPositions = "tpositions".($yearChosen)."03";
					if($ordre == "ASC"){
						$tableTPositions2 = "tpositions".($yearChosen)."06";
						if($monthDifference == 3) $tableTPositions2 = "tpositions";
					}
					if($ordre == "DESC") $tableTPositions2 = "tpositions".(intval($yearChosen)-1)."12";
				}
				if( (intval($monthChosen) > 3) && (intval($monthChosen) <= 6)){
					$tableTPositions = "tpositions".($yearChosen)."06";
					if($ordre == "ASC"){
						$tableTPositions2 = "tpositions".($yearChosen)."09";
						if($monthDifference == 3) $tableTPositions2 = "tpositions";
					}
					if($ordre == "DESC") $tableTPositions2 = "tpositions".($yearChosen)."03";
				}
				if( (intval($monthChosen) > 6) && (intval($monthChosen) <= 9)){
					$tableTPositions = "tpositions".($yearChosen)."09";
					if($ordre == "ASC"){
						$tableTPositions2 = "tpositions".($yearChosen)."12";
						if($monthDifference == 3) $tableTPositions2 = "tpositions";
					}
					if($ordre == "DESC") $tableTPositions2 = "tpositions".($yearChosen)."06";
				}
				if( (intval($monthChosen) > 9) && (intval($monthChosen) <= 12)){
					$tableTPositions = "tpositions".($yearChosen)."12";
					if($ordre == "ASC"){
						$tableTPositions2 = "tpositions".(intval($yearChosen)+1)."03";
						if($monthDifference == 3) $tableTPositions2 = "tpositions";
					}
					if($ordre == "DESC") $tableTPositions2 = "tpositions".($yearChosen)."09";
				}
			}
		}else{
			$tableTPositions = "tpositions";
			if($ordre == "ASC") $tableTPositions2 = "tpositions";
			if($ordre == "DESC"){
				$modulo = $monthNow % 3;
				$monthSub = ($monthNow) - $modulo;
				if($monthSub == $monthNow) $monthSub = $monthSub - 3;

				$monthNow = sprintf("%02d", $monthSub);

				$tableTPositions2 = "tpositions" . ($yearNow) . "" . $monthNow;
			}
		}

		$sql = "SELECT  * FROM $tableTPositions WHERE (Pos_Id_tracker = '" . $q . "' AND Pos_DateTime_position " . $select . " '" . $pUTC . "') ORDER BY (Pos_DateTime_position) " . $ordre . " LIMIT  $myPosition,$limitPosition  ";

	$incrementTable=0;
	$result = mysqli_query($connection,$sql);
	$rowCount = mysqli_num_rows($result);

	while($rowCount == 0){
		$tableTPositions = $tableTPositions2;
		$sql="SELECT  * FROM $tableTPositions2 WHERE (Pos_Id_tracker = '".$q."' AND Pos_DateTime_position ".$select." '".$pUTC."') ORDER BY (Pos_DateTime_position) ".$ordre." LIMIT  $myPosition,$limitPosition  ";
		$result = mysqli_query($connection,$sql);
		$rowCount = mysqli_num_rows($result);

		if($ordre == "DESC") {
			$modulo = $monthNow % 3;
			$monthSub = ($monthNow) - $modulo;
			if ($monthSub == $monthNow) $monthSub = $monthSub - 3;
			if ($monthSub == 0) {
				$monthSub = 12;
				$yearNow--;
			}
		}else{
			$modulo = $monthNow % 3;
			$monthSub = ($monthNow) + $modulo;
			if ($monthSub == $monthNow) $monthSub = $monthSub + 3;
			if ($monthSub == 15) {
				$monthSub = 3;
				$yearNow++;
			}
		}
		$monthNow = sprintf("%02d", $monthSub);

		$tableTPositions2 = "tpositions" . ($yearNow) . "" . $monthNow;

		if ($incrementTable == 368) $rowCount = 1;

		$incrementTable++;


	}

	//
//		if( $result !== false ) {
			$NbrPlage = "";
			$Hd1 = "";
			$Hf1 = "";
			$Hd2 = "";
			$Hf2 = "";
			$Lundi = "";
			$Mardi = "";
			$Mercredi = "";
			$Jeudi = "";
			$Vendredi = "";
			$Samedi = "";
			$Dimanche = "";

			// $connection2 = mysqli_connect($server, $db_user_2, $db_pass_2, $database);
			// $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $q . "' )";
			// $result2 = mysqli_query($connection2, $sql2);

			// if (mysqli_num_rows($result2) > 0) {
			// 	while ($row2 = mysqli_fetch_array($result2)) {
			// 		$NbrPlage = $row2['NbrPlage'];
			// 		$Hd1 = $row2['Hd1'];
			// 		$Hf1 = $row2['Hf1'];
			// 		$Hd2 = $row2['Hd2'];
			// 		$Hf2 = $row2['Hf2'];
			// 		$Lundi = $row2['Lundi'];
			// 		$Mardi = $row2['Mardi'];
			// 		$Mercredi = $row2['Mercredi'];
			// 		$Jeudi = $row2['Jeudi'];
			// 		$Vendredi = $row2['Vendredi'];
			// 		$Samedi = $row2['Samedi'];
			// 		$Dimanche = $row2['Dimanche'];
			// 	}
			// 	$x = 1;
			// 	$z = 0;
			// 	$w = 0;


			// 	$dateTimeChosen = new DateTime($pUTC);

			// 	$dd1 = $dateTimeChosen->setTime(intval($Hd1[0] . $Hd1[1]), intval($Hd1[2] . $Hd1[3]))->format('Y-m-d H:i:s');
			// 	$df1 = $dateTimeChosen->setTime(intval($Hf1[0] . $Hf1[1]), intval($Hf1[2] . $Hf1[3]))->format('Y-m-d H:i:s');
			// 	$dd2 = $dateTimeChosen->setTime(intval($Hd2[0] . $Hd2[1]), intval($Hd2[2] . $Hd2[3]))->format('Y-m-d H:i:s');
			// 	$df2 = $dateTimeChosen->setTime(intval($Hf2[0] . $Hf2[1]), intval($Hf2[2] . $Hf2[3]))->format('Y-m-d H:i:s');

			// 	$dd1 = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($dd1)), $timezone);
			// 	$df1 = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($df1)), $timezone);
			// 	$dd2 = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($dd2)), $timezone);
			// 	$df2 = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($df2)), $timezone);


			// 	while ($z < intval($_GET["n"])) {
			// 		//$sql = "SELECT  * FROM tpositions201412 WHERE (Pos_Id_tracker = '" . $q . "' AND Pos_DateTime_position " . $select . " '" . $pUTC . "') ORDER BY (Pos_DateTime_position) " . $ordre . " LIMIT  $myPosition,$limitPosition  ";

			// 		$sql = "SELECT * FROM $tableTPositions WHERE ( (Pos_Id_tracker = '" . $q . "') AND ( (Pos_DateTime_position BETWEEN '$dd1' AND '$df1')
			// 				OR (Pos_DateTime_position BETWEEN '$dd2' AND '$df2') )  AND ( Pos_DateTime_position " . $select . " '" . $pUTC . "') ) ORDER BY (Pos_DateTime_position) " . $ordre . "   LIMIT  $myPosition,$limitPosition  ";
			// 		$result = mysqli_query($connection, $sql);

			// 		if($select == "<=") {
			// 			$dd1 = date('Y-m-d H:i:s', strtotime($dd1 . '-' . $x . 'day'));
			// 			$df1 = date('Y-m-d H:i:s', strtotime($df1 . '-' . $x . ' day'));
			// 			$dd2 = date('Y-m-d H:i:s', strtotime($dd2 . '-' . $x . ' day'));
			// 			$df2 = date('Y-m-d H:i:s', strtotime($df2 . '-' . $x . ' day'));

			// 		}else if($select == ">=") {
			// 			$dd1 = date('Y-m-d H:i:s', strtotime($dd1 . '+' . $x . 'day'));
			// 			$df1 = date('Y-m-d H:i:s', strtotime($df1 . '+' . $x . ' day'));
			// 			$dd2 = date('Y-m-d H:i:s', strtotime($dd2 . '+' . $x . ' day'));
			// 			$df2 = date('Y-m-d H:i:s', strtotime($df2 . '+' . $x . ' day'));
			// 		}


			// 		$w++;

			// 		if ($w == 368){
			// 			$z =  intval($_GET["n"]);
			// 			echo '	<thead id="head_idTablePosition"><tr><th width="50px">N</th><th width="45px"></th><th width="150px">';
			// 			echo _('nombalise');
			// 			echo '</th><th width="150px">Date position</th><th width="400px">';
			// 			echo _('adresse');
			// 			echo '</th><th width="50px">';
			// 			echo _('vitesse');
			// 			echo '</th><th width="350px">';
			// 			echo _('statut');
			// 			echo '</th><th width="45px">GSM</th><th width="45px">';
			// 			echo _('alim');
			// 			echo '</th>';
			// 			echo '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr></thead><tbody id="body_idTablePosition">';

			// 		}
			// 		if ($result !== false) {
			// 			$tg = mysqli_query($connection, $sql);
			// 			$lengths = mysqli_num_rows($result);

			// 			if ($lengths == 0) {

			// 				$sqlVerify = "SELECT COUNT(*) AS nb FROM tpositions WHERE  (Pos_Id_tracker = '" . $q . "') AND ( Pos_DateTime_position " . $select . " '" . $dd1 . "')  ORDER BY (Pos_DateTime_position) " . $ordre . "";
			// 				$resultVerify = mysqli_query($connection, $sqlVerify);
			// 				$rowVerify = mysqli_fetch_array($resultVerify,MYSQLI_ASSOC);
			// 				if($rowVerify['nb'] <= 0) {
			// 					$z = intval($_GET["n"]);
			// 					if ($w <= 1 ) {
			// 						echo '	<thead id="head_idTablePosition"><tr><th width="50px">N</th><th width="45px"></th><th width="150px">';
			// 						echo _('nombalise');
			// 						echo '</th><th width="150px">Date position</th><th width="400px">';
			// 						echo _('adresse');
			// 						echo '</th><th width="50px">';
			// 						echo _('vitesse');
			// 						echo '</th><th width="350px">';
			// 						echo _('statut');
			// 						echo '</th><th width="45px">GSM</th><th width="45px">';
			// 						echo _('alim');
			// 						echo '</th>';
			// 						echo '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr></thead><tbody id="body_idTablePosition">';
			// 					}
			// 				}
			// 				mysqli_free_result($resultVerify);
			// 			} else {
			// 				while ($row = mysqli_fetch_array($result)) {
			// 					$utc_date = DateTime::createFromFormat(
			// 							'Y-m-d H:i:s',
			// 							$row['Pos_DateTime_position'],
			// 							new DateTimeZone('UTC')
			// 					);
			// 					$local_date = $utc_date;
			// 					$local_date->setTimeZone(new DateTimeZone($timezone));
			// 					if ($z < intval($_GET["n"])) {
			// 						if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
			// 								($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
			// 								($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
			// 								($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
			// 								($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
			// 								($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
			// 								($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")
			// 						) {
										
			// 							//********************************************************** TRAITEMENT STATUT **********************************************************
			// 							$statutEncode = statutEncode($row['Pos_Statut']);

			// 							//Brouilleur
			// 							$nomVersionBalise = versionBalise($row['Pos_Odometre']);
			// 							if ((substr($nomVersionBalise, 0, 5) == "SC200") || (substr($nomVersionBalise, 0, 5) == "SC300")) {
			// 								if ($headerTable == 0) {
			// 									//					echo '<tr><th width="35px">N</th><th width="45px"></th><th width="400px">Adresse</th><th width="50px">Vitesse</th><th width="350px">Statut</th><th width="45px">Alim</th><th width="120px">Date/heure</th>';
			// 									//					echo '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>';
			// 									echo '	<thead id="head_idTablePosition"><tr><th width="50px">N</th><th width="45px"></th><th width="150px">';
			// 									echo _('nombalise');
			// 									echo '</th><th width="150px">Date position</th><th width="400px">';
			// 									echo _('adresse');
			// 									echo '</th><th width="50px">';
			// 									echo _('vitesse');
			// 									echo '</th><th width="350px">';
			// 									echo _('statut');
			// 									echo '</th><th width="45px">GSM</th><th width="45px">';
			// 									echo _('alim');
			// 									echo '</th>';
			// 									echo '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr></thead><tbody id="body_idTablePosition">';
			// 								}
			// 								$headerTable = 1;
			// 								//				$statutBrouilleur[$i] = "";
			// 								//				$brouilleur[$i] = "";
			// 								if ($statutEncode[3] == "1") {
			// 									$brouilleur[$i] = "<td><img src='../../assets/img/ICONES/brouillage.ico'></td>";
			// 									$statutBrouilleur[$i] = "(brouille)";
			// 								} else {
			// 									$brouilleur[$i] = "<td><img src='../../assets/img/ICONES/nonBrouillage.ico'></td>";
			// 									$statutBrouilleur[$i] = "";
			// 								}
			// 							} else {
			// 								if ($headerTable == 0) {
			// 									echo '	<thead id="head_idTablePosition"><tr><th width="50px">N</th><th width="45px"></th><th width="150px">';
			// 									echo _('nombalise');
			// 									echo '</th><th width="150px">Date position</th><th width="400px">';
			// 									echo _('adresse');
			// 									echo '</th><th width="50px">';
			// 									echo _('vitesse');
			// 									echo '</th><th width="350px">';
			// 									echo _('statut');
			// 									echo '</th><th width="45px">GSM</th><th width="45px">';
			// 									echo _('alim');
			// 									echo '</th>';
			// 									echo '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr></thead><tbody id="body_idTablePosition">';
			// 								}
			// 								$headerTable = 1;
			// 								if ($statutEncode[3] == "1") {
			// 									$brouilleur[$i] = "<td><img src='../../assets/img/ICONES/brouillage.ico'></td>";
			// 									$statutBrouilleur[$i] = "(" . _('brouille') . ")";
			// 								} else {
			// 									$brouilleur[$i] = "<td><img src='../../assets/img/ICONES/nonBrouillage.ico'></td>";
			// 									$statutBrouilleur[$i] = "";
			// 								}
			// 							}

			// 							//ALARM 1
			// 							if ($statutEncode[31] == "1") {
			// 								$alarm1[$i] = " - " . _('configuration_alarme') . " 1 active";
			// 							} else {
			// 								$alarm1[$i] = "";
			// 							}
			// 							//ALARM 2
			// 							if ($statutEncode[30] == "1") {
			// 								$alarm2[$i] = " - " . _('configuration_alarme') . " 2 active";
			// 							} else {
			// 								$alarm2[$i] = "";
			// 							}
			// 							// COULEUR - CONTACT
			// 							if ($statutEncode[29] == "1") {
			// 								$cbalise[$i] = lireDirectionVitesse($row['Pos_Direction'], round($row['Pos_Vitesse']));
			// 								$statutSTOP = "";
			// 							} else {
			// 								$cbalise[$i] = "<img src='../../assets/img/ICONES/stop16.ico'>";
			// 								$statutSTOP[$i] = "STOP - ";
			// 							}

			// 							//DEFAULT ALIM
			// 							if (($statutEncode[28]) == "1" && ($statutEncode[27] == "0")) {
			// 								$defaultAlim[$i] = "<img src='../../assets/img/ICONES/alarmeAlim3232.ico' width='17' height='17'>";
			// 							} else if (($statutEncode[28] == "0") && ($statutEncode[27] == "1")) {
			// 								$defaultAlim[$i] = "<img src='../../assets/img/ICONES/alarmeBatterie.ico'>";
			// 							} else if (($statutEncode[28] == "1") && ($statutEncode[27] == "1")) {
			// 								$defaultAlim[$i] = "<img src='../../assets/img/ICONES/alarmeMulti.ico'>";
			// 							} else {
			// 								$defaultAlim[$i] = "";
			// 							}

			// 							//VIBRATION
			// 							if ($statutEncode[25] == "1") {
			// 								$statutVIB[$i] = "VIB";
			// 							} else {
			// 								$statutVIB[$i] = _('pas') . " VIB";
			// 							}
			// 							//GPS
			// 							if ($statutEncode[26] == "1") {
			// 								$GPS[$i] = " " . $row['Pos_Odometre'][4] . "/" . $row['Pos_Odometre'][5] . "." . $row['Pos_Odometre'][6];
			// 								$infoGPS[$i] = "1";
			// 							} else {
			// 								if ($cbalise[$i] == lireDirectionVitesse($row['Pos_Direction'], round($row['Pos_Vitesse']))) {
			// 									$cbalise[$i] = "<img src='../../assets/img/ICONES/noGPS.ico'>";
			// 								}
			// 								if (($cbalise[$i] == "<img src='../../assets/img/ICONES/stop16.ico'>")) {
			// 									$cbalise[$i] = "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>";
			// 								}
			// 								$GPS[$i] = " No";

			// 							}
			// 							//ALIM
			// 							$niveauBat[$i] = lireBatterie($statutEncode[1], $statutEncode[2], $statutEncode[9], $statutEncode[8]);
										
			// 							$v[$i] = explode("%",$niveauBat[$i]);
			// 							$volt[$i] = ($v[$i][0] * 0.253) + 5.5;
			// 							$volt[$i] = $volt[$i] * 10;		// Pour arrondir 1 chiffre après la virgule
			// 							$volt[$i] = round($volt[$i]);	// Pour arrondir 1 chiffre après la virgule
			// 							$volt[$i] = $volt[$i] / 10;		// Pour arrondir 1 chiffre après la virgule
										
			// 							if($statutEncode[14] == "1"){
			// 								$alimEtBatterie[$i] = _('alimext')." ".$volt[$i]."V" ;
			// 							} else if (($statutEncode[14] == "0") && ($statutEncode[13] == "1")) {
			// 								$alimEtBatterie[$i] = "BatExt " . $niveauBat[$i];
			// 							} else if (($statutEncode[14] == "0") && ($statutEncode[13] == "0") && ($statutEncode[12] == "1")) {
			// 								$alimEtBatterie[$i] = "BatInt " . $niveauBat[$i];
			// 							} else {
			// 								$alimEtBatterie[$i] = _('alimbasse') . " " . $niveauBat[$i];
			// 							}
			// 							//RESEAU GSM
			// 							$niveauReseau[$i] = ($statutEncode[7] * 1) + ($statutEncode[6] * 2);
			// 							//********************************************************** Fin TRAITEMENT STATUT **********************************************************
			// 							$i++;
			// 						}
			// 					}
			// 				}


			// 				while ($row = mysqli_fetch_array($tg)) {
			// 					$utc_date = DateTime::createFromFormat(
			// 							'Y-m-d H:i:s',
			// 							$row['Pos_DateTime_position'],
			// 							new DateTimeZone('UTC')
			// 					);
			// 					$local_date = $utc_date;
			// 					$local_date->setTimeZone(new DateTimeZone($timezone));
			// 					ini_set('display_errors', 'off');
			// 					//			if($y < $n) {
			// 					if ($z < intval($_GET["n"])) {
			// 						if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
			// 								($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
			// 								($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
			// 								($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
			// 								($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
			// 								($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
			// 								($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")
			// 						) {

			// 							if ($infoGPS[$i2] == "1") {
			// 								if ($filtrage == "yes") {
			// 									if (($cbalise[$i2] == "<img src='../../assets/img/ICONES/stop16.ico'>")) {
			// 										if (((/*$cbalise[$i2 - 1] != "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>" &&*/ $cbalise[$i2 - 1] != "<img src='../../assets/img/ICONES/stop16.ico'>") && $cbalise[$i2] == "<img src='../../assets/img/ICONES/stop16.ico'>")) {
			// 											echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>0</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 											echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 											echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 											$detectStop = "1";
			// 											$detectCoordStopLat = $row['Pos_Latitude'];
			// 											$detectCoordStopLng = $row['Pos_Longitude'];
			// 											$y++;
			// 										}
			// 									} else {
			// 										if ($cbalise[$i2] == lireDirectionVitesse($row['Pos_Direction'], round($row['Pos_Vitesse']))) {
			// 											if ($detectStop == "1") {
			// 												if ((getDistanceBetweenPointsNew($detectCoordStopLat, $detectCoordStopLng, $row['Pos_Latitude'], $row['Pos_Longitude'])) >= intval($distanceFiltree)) {
			// 													echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 													echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 													echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 													$detectStop = 0;
			// 													$y++;
			// 												}
			// 											} else {
			// 												echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 												echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 												echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 												$y++;
			// 											}
			// 										} else {
			// 											echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 											echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 											echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 											$y++;
			// 										}
			// 									}
			// 								} else if ($filtrage == "no") {
			// 									if (intval($distanceFiltree) != (0 || "")) {
			// 										if (($cbalise[$i2] == "<img src='../../assets/img/ICONES/stop16.ico'>" && $cbalise[$i2 + 1] != "<img src='../../assets/img/ICONES/stop16.ico'>") && ($cbalise[$i2] == "<img src='../../assets/img/ICONES/stop16.ico'>" && $cbalise[$i2 + 1] != "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>")) {
			// 											echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>0</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 											echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 											echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 											$detectStop = "1";
			// 											$detectCoordStopLat = $row['Pos_Latitude'];
			// 											$detectCoordStopLng = $row['Pos_Longitude'];
			// 											$y++;
			// 										}
			// 										if ($cbalise[$i2] == lireDirectionVitesse($row['Pos_Direction'], round($row['Pos_Vitesse']))) {
			// 											if ($detectStop == "1") {
			// 												echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 												echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 												echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 												$detectStop = 0;
			// 												$y++;
			// 											} else {
			// 												if ((getDistanceBetweenPointsNew($detectCoordStopLat, $detectCoordStopLng, $row['Pos_Latitude'], $row['Pos_Longitude'])) >= intval($distanceFiltree)) {
			// 													echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 													echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 													echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 													$y++;

			// 												}
			// 											}
			// 										}
			// 									} else {
			// 										echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 										echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 										echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 										$y++;
			// 									}
			// 								}
			// 							} else {
			// 								if ($filtrage == "yes") {
			// 									if (($cbalise[$i2] == "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>")) {
			// 										if ((($cbalise[$i2 - 1] != "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>" /*&& $cbalise[$i2 - 1] != "<img src='../../assets/img/ICONES/stop16.ico'>"*/) && $cbalise[$i2] == "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>")) {
			// 											echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>0</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 											echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 											echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 											$detectStop = "1";
			// 											$detectCoordStopLat = $row['Pos_Latitude'];
			// 											$detectCoordStopLng = $row['Pos_Longitude'];
			// 											$y++;
			// 										}
			// 									} else {
			// 										if ($cbalise[$i2] == "<img src='../../assets/img/ICONES/noGPS.ico'>") {
			// 											if ($detectStop == "1") {
			// 												if ((getDistanceBetweenPointsNew($detectCoordStopLat, $detectCoordStopLng, $row['Pos_Latitude'], $row['Pos_Longitude'])) >= intval($distanceFiltree)) {
			// 													echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 													echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 													echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 													$detectStop = 0;
			// 													$y++;
			// 												}
			// 											} else {
			// 												echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 												echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 												echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 												$y++;
			// 											}
			// 										} else {
			// 											echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 											echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 											echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 											$y++;
			// 										}
			// 									}
			// 								} else if ($filtrage == "no") {
			// 									if (intval($distanceFiltree) != (0 || "")) {
			// 										if (($cbalise[$i2] == "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>" && $cbalise[$i2 + 1] != "<img src='../../assets/img/ICONES/stop16.ico'>") && ($cbalise[$i2] == "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>" && $cbalise[$i2 + 1] != "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>")) {
			// 											echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>0</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 											echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 											echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 											$detectStop = "1";
			// 											$detectCoordStopLat = $row['Pos_Latitude'];
			// 											$detectCoordStopLng = $row['Pos_Longitude'];
			// 											$y++;
			// 										}
			// 										if ($cbalise[$i2] == "<img src='../../assets/img/ICONES/noGPS.ico'>") {
			// 											if ($detectStop == "1") {
			// 												echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 												echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 												echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 												$detectStop = 0;
			// 												$y++;
			// 											} else {
			// 												if ((getDistanceBetweenPointsNew($detectCoordStopLat, $detectCoordStopLng, $row['Pos_Latitude'], $row['Pos_Longitude'])) >= intval($distanceFiltree)) {
			// 													echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 													echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 													echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 													$y++;
			// 												}
			// 											}
			// 										}
			// 									} else {
			// 										echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i2] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i2] . " " . $statutVIB[$i2] . " - GSM " . $statutBrouilleur[$i2] . " ";
			// 										echo $niveauReseau[$i2] . "/3 - GPS" . $GPS[$i2] . " - " . $alimEtBatterie[$i2] . "" . $alarm1[$i2] . "" . $alarm2[$i2] . "</td>" . $brouilleur[$i2] . "<td>" . $defaultAlim[$i2] . "</td>";
			// 										echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
			// 										$y++;
			// 									}

			// 								}
			// 							}
			// 							$numPageInt++;
			// 							$i2++;
			// 							$z++;
			// 						}
			// 					}

			// 				}
			// 			}

			// 		} else {
			// 			echo '	<thead id="head_idTablePosition"><tr><th width="50px">N</th><th width="45px"></th><th width="150px">';
			// 			echo _('nombalise');
			// 			echo '</th><th width="150px">Date position</th><th width="400px">';
			// 			echo _('adresse');
			// 			echo '</th><th width="50px">';
			// 			echo _('vitesse');
			// 			echo '</th><th width="350px">';
			// 			echo _('statut');
			// 			echo '</th><th width="45px">GSM</th><th width="45px">';
			// 			echo _('alim');
			// 			echo '</th>';
			// 			echo '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr></thead><tbody id="body_idTablePosition">';
			// 		}


			// 	}

			// 	mysqli_free_result($tg);
			// 	mysqli_free_result($result);
			// 	mysqli_close($connection);
			// } else {
				$sql = "SELECT  * FROM $tableTPositions WHERE (Pos_Id_tracker = '" . $q . "' AND Pos_DateTime_position " . $select . " '" . $pUTC . "') ORDER BY (Pos_DateTime_position) " . $ordre . " LIMIT  $myPosition,$limitPosition  ";

				$result = mysqli_query($connection, $sql);

				if ($result !== false) {
					$tg = mysqli_query($connection, $sql);
					$lengths = mysqli_num_rows($result);


					if ($lengths == 0) {
						echo '	<thead id="head_idTablePosition"><tr><th width="50px">N</th><th width="45px"></th><th width="150px">';
						echo _('nombalise');
						echo '</th><th width="150px">Date position</th><th width="400px">';
						echo _('adresse');
						echo '</th><th width="50px">';
						echo _('vitesse');
						echo '</th><th width="350px">';
						echo _('statut');
						echo '</th><th width="45px">GSM</th><th width="45px">';
						echo _('alim');
						echo '</th>';
						echo '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr></thead><tbody id="body_idTablePosition">';
					}
					while ($row = mysqli_fetch_array($result)) {
//********************************************************** TRAITEMENT STATUT **********************************************************
						$statutEncode = statutEncode($row['Pos_Statut']);

						//Brouilleur
						$nomVersionBalise = versionBalise($row['Pos_Odometre']);
						if ((substr($nomVersionBalise, 0, 5) == "SC200") || (substr($nomVersionBalise, 0, 5) == "SC300")) {
							if ($headerTable == 0) {
								//					echo '<tr><th width="35px">N</th><th width="45px"></th><th width="400px">Adresse</th><th width="50px">Vitesse</th><th width="350px">Statut</th><th width="45px">Alim</th><th width="120px">Date/heure</th>';
								//					echo '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>';
								echo '	<thead id="head_idTablePosition"><tr><th width="50px">N</th><th width="45px"></th><th width="150px">';
								echo _('nombalise');
								echo '</th><th width="150px">Date position</th><th width="400px">';
								echo _('adresse');
								echo '</th><th width="50px">';
								echo _('vitesse');
								echo '</th><th width="350px">';
								echo _('statut');
								echo '</th><th width="45px">GSM</th><th width="45px">';
								echo _('alim');
								echo '</th>';
								echo '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr></thead><tbody id="body_idTablePosition">';
							}
							$headerTable = 1;
							//				$statutBrouilleur[$i] = "";
							//				$brouilleur[$i] = "";
							if ($statutEncode[3] == "1") {
								$brouilleur[$i] = "<td><img src='../../assets/img/ICONES/brouillage.ico'></td>";
								$statutBrouilleur[$i] = "(brouille)";
							} else {
								$brouilleur[$i] = "<td><img src='../../assets/img/ICONES/nonBrouillage.ico'></td>";
								$statutBrouilleur[$i] = "(non brouille)";
							}
						} else {
							if ($headerTable == 0) {
								echo '	<thead id="head_idTablePosition"><tr><th width="50px">N</th><th width="45px"></th><th width="150px">';
								echo _('nombalise');
								echo '</th><th width="150px">Date position</th><th width="400px">';
								echo _('adresse');
								echo '</th><th width="50px">';
								echo _('vitesse');
								echo '</th><th width="350px">';
								echo _('statut');
								echo '</th><th width="45px">GSM</th><th width="45px">';
								echo _('alim');
								echo '</th>';
								echo '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr></thead><tbody id="body_idTablePosition">';
							}
							$headerTable = 1;
							if ($statutEncode[3] == "1") {
								$brouilleur[$i] = "<td><img src='../../assets/img/ICONES/brouillage.ico'></td>";
								$statutBrouilleur[$i] = "(" . _('brouille') . ")";
							} else {
								$brouilleur[$i] = "<td><img src='../../assets/img/ICONES/nonBrouillage.ico'></td>";
								$statutBrouilleur[$i] = "(" . _('nonbrouille') . ")";
							}
						}

						//ALARM 1
						if ($statutEncode[31] == "1") {
							$alarm1[$i] = " - " . _('configuration_alarme') . " 1 active";
						} else {
							$alarm1[$i] = "";
						}
						//ALARM 2
						if ($statutEncode[30] == "1") {
							$alarm2[$i] = " - " . _('configuration_alarme') . " 2 active";
						} else {
							$alarm2[$i] = "";
						}
						// COULEUR - CONTACT
						if ($statutEncode[29] == "1") {
							$cbalise[$i] = lireDirectionVitesse($row['Pos_Direction'], round($row['Pos_Vitesse']));
							$statutSTOP = "";
						} else {
							$cbalise[$i] = "<img src='../../assets/img/ICONES/stop16.ico'>";
							$statutSTOP[$i] = "STOP - ";
						}

						//DEFAULT ALIM
						if (($statutEncode[28]) == "1" && ($statutEncode[27] == "0")) {
							$defaultAlim[$i] = "<img src='../../assets/img/ICONES/alarmeAlim3232.ico' width='17' height='17'>";
						} else if (($statutEncode[28] == "0") && ($statutEncode[27] == "1")) {
							$defaultAlim[$i] = "<img src='../../assets/img/ICONES/alarmeBatterie.ico'>";
						} else if (($statutEncode[28] == "1") && ($statutEncode[27] == "1")) {
							$defaultAlim[$i] = "<img src='../../assets/img/ICONES/alarmeMulti.ico'>";
						} else {
							$defaultAlim[$i] = "";
						}

						//VIBRATION
						if ($statutEncode[25] == "1") {
							$statutVIB[$i] = "VIB";
						} else {
							$statutVIB[$i] = _('pas') . " VIB";
						}
						//GPS
						if ($statutEncode[26] == "1") {
							$GPS[$i] = " " . $row['Pos_Odometre'][4] . "/" . $row['Pos_Odometre'][5] . "." . $row['Pos_Odometre'][6];
							$infoGPS[$i] = "1";
						} else {
							if ($cbalise[$i] == lireDirectionVitesse($row['Pos_Direction'], round($row['Pos_Vitesse']))) {
								$cbalise[$i] = "<img src='../../assets/img/ICONES/noGPS.ico'>";
							}
							if (($cbalise[$i] == "<img src='../../assets/img/ICONES/stop16.ico'>")) {
								$cbalise[$i] = "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>";
							}
							$GPS[$i] = " No";

						}
						//ALIM
						$niveauBat[$i] = lireBatterie($statutEncode[1], $statutEncode[2], $statutEncode[9], $statutEncode[8]);
						
						$v[$i] = explode("%",$niveauBat[$i]);
						$volt[$i] = ($v[$i][0] * 0.253) + 5.5;
						$volt[$i] = $volt[$i] * 10;		// Pour arrondir 1 chiffre après la virgule
						$volt[$i] = round($volt[$i]);	// Pour arrondir 1 chiffre après la virgule
						$volt[$i] = $volt[$i] / 10;		// Pour arrondir 1 chiffre après la virgule
						
						if($statutEncode[14] == "1"){
							$alimEtBatterie[$i] = _('alimext')." ".$volt[$i]."V" ;
						} else if (($statutEncode[14] == "0") && ($statutEncode[13] == "1")) {
							$alimEtBatterie[$i] = "BatExt " . $niveauBat[$i];
						} else if (($statutEncode[14] == "0") && ($statutEncode[13] == "0") && ($statutEncode[12] == "1")) {
							$alimEtBatterie[$i] = "BatInt " . $niveauBat[$i];
						} else {
							$alimEtBatterie[$i] = _('alimbasse') . " " . $niveauBat[$i];
						}
						//RESEAU GSM
						$niveauReseau[$i] = ($statutEncode[7] * 1) + ($statutEncode[6] * 2);

						$i++;
					}
					$i = 0;
//********************************************************** Fin TRAITEMENT STATUT **********************************************************
					$y = 0;
					while ($row = mysqli_fetch_array($tg)) {
						$utc_date = DateTime::createFromFormat(
								'Y-m-d H:i:s',
								$row['Pos_DateTime_position'],
								new DateTimeZone('UTC')
						);
						$local_date = $utc_date;
						$local_date->setTimeZone(new DateTimeZone($timezone));
						ini_set('display_errors', 'off');
						//			if($y < $n) {
						if ($infoGPS[$i] == "1") {
							if ($filtrage == "yes") {
								if (($cbalise[$i] == "<img src='../../assets/img/ICONES/stop16.ico'>")) {
									if ((($cbalise[$i - 1] != "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>" && $cbalise[$i - 1] != "<img src='../../assets/img/ICONES/stop16.ico'>") && $cbalise[$i] == "<img src='../../assets/img/ICONES/stop16.ico'>")) {
										echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>0</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
										echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
										echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
										$detectStop = "1";
										$detectCoordStopLat = $row['Pos_Latitude'];
										$detectCoordStopLng = $row['Pos_Longitude'];
										$y++;
									}
								} else {
									if ($cbalise[$i] == lireDirectionVitesse($row['Pos_Direction'], round($row['Pos_Vitesse']))) {
										if ($detectStop == "1") {
											if ((getDistanceBetweenPointsNew($detectCoordStopLat, $detectCoordStopLng, $row['Pos_Latitude'], $row['Pos_Longitude'])) >= intval($distanceFiltree)) {
												echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
												echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
												echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
												$detectStop = 0;
												$y++;
											}
										} else {
											echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
											echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
											echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
											$y++;
										}
									} else {
										echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
										echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
										echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
										$y++;
									}
								}
							} else if ($filtrage == "no") {
								if (intval($distanceFiltree) != (0 || "")) {
									if (($cbalise[$i] == "<img src='../../assets/img/ICONES/stop16.ico'>" && $cbalise[$i + 1] != "<img src='../../assets/img/ICONES/stop16.ico'>") && ($cbalise[$i] == "<img src='../../assets/img/ICONES/stop16.ico'>" && $cbalise[$i + 1] != "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>")) {
										echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>0</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
										echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
										echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
										$detectStop = "1";
										$detectCoordStopLat = $row['Pos_Latitude'];
										$detectCoordStopLng = $row['Pos_Longitude'];
										$y++;
									}
									if ($cbalise[$i] == lireDirectionVitesse($row['Pos_Direction'], round($row['Pos_Vitesse']))) {
										if ($detectStop == "1") {
											echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
											echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
											echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
											$detectStop = 0;
											$y++;
										} else {
											if ((getDistanceBetweenPointsNew($detectCoordStopLat, $detectCoordStopLng, $row['Pos_Latitude'], $row['Pos_Longitude'])) >= intval($distanceFiltree)) {
												echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
												echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
												echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
												$y++;

											}
										}
									}
								} else {
									echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
									echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
									echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
									$y++;
								}
							}
						} else {
							if ($filtrage == "yes") {
								if (($cbalise[$i] == "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>")) {
									if ((($cbalise[$i - 1] != "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>" && $cbalise[$i - 1] != "<img src='../../assets/img/ICONES/stop16.ico'>") && $cbalise[$i] == "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>")) {
										echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>0</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
										echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
										echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
										$detectStop = "1";
										$detectCoordStopLat = $row['Pos_Latitude'];
										$detectCoordStopLng = $row['Pos_Longitude'];
										$y++;
									}
								} else {
									if ($cbalise[$i] == "<img src='../../assets/img/ICONES/noGPS.ico'>") {
										if ($detectStop == "1") {
											if ((getDistanceBetweenPointsNew($detectCoordStopLat, $detectCoordStopLng, $row['Pos_Latitude'], $row['Pos_Longitude'])) >= intval($distanceFiltree)) {
												echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
												echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
												echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
												$detectStop = 0;
												$y++;
											}
										} else {
											echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
											echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
											echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
											$y++;
										}
									} else {
										echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
										echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
										echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
										$y++;
									}
								}
							} else if ($filtrage == "no") {
								if (intval($distanceFiltree) != (0 || "")) {
									if (($cbalise[$i] == "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>" && $cbalise[$i + 1] != "<img src='../../assets/img/ICONES/stop16.ico'>") && ($cbalise[$i] == "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>" && $cbalise[$i + 1] != "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>")) {
										echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>0</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
										echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
										echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
										$detectStop = "1";
										$detectCoordStopLat = $row['Pos_Latitude'];
										$detectCoordStopLng = $row['Pos_Longitude'];
										$y++;
									}
									if ($cbalise[$i] == "<img src='../../assets/img/ICONES/noGPS.ico'>") {
										if ($detectStop == "1") {
											echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
											echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
											echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
											$detectStop = 0;
											$y++;
										} else {
											if ((getDistanceBetweenPointsNew($detectCoordStopLat, $detectCoordStopLng, $row['Pos_Latitude'], $row['Pos_Longitude'])) >= intval($distanceFiltree)) {
												echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
												echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
												echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
												$y++;
											}
										}
									}
								} else {
									echo "<tr onclick='afficheInfobullTable(this)'><td>" . $numPageInt . "</td><td>" . $cbalise[$i] . "</td><td>" . $nomBalise . "</td><td>" . $local_date->format($formatLangDateTime) . "</td><td>" . $row['Pos_Adresse'] . "</td><td>" . round($row['Pos_Vitesse']) . "</td><td>" . $statutSTOP[$i] . " " . $statutVIB[$i] . " - GSM " . $statutBrouilleur[$i] . " ";
									echo $niveauReseau[$i] . "/3 - GPS" . $GPS[$i] . " - " . $alimEtBatterie[$i] . "" . $alarm1[$i] . "" . $alarm2[$i] . "</td>" . $brouilleur[$i] . "<td>" . $defaultAlim[$i] . "</td>";
									echo "<td style='display:none'>" . $row['Pos_Latitude'] . "</td><td style='display:none'>" . $row['Pos_Longitude'] . "</td><td style='display:none'>" . $row['Pos_Direction'] . "</td></tr>";
									$y++;
								}

							}
						}
						$numPageInt++;
						$i++;
						//			}
						//					}

					}
					mysqli_free_result($tg);
					mysqli_free_result($result);
					mysqli_close($connection);
				} else {
					echo '<thead id="head_idTablePosition"><tr><th width="50px">N</th><th width="45px"></th><th width="150px">';
					echo _('nombalise');
					echo '</th><th width="150px">Date position</th><th width="400px">';
					echo _('adresse');
					echo '</th><th width="50px">';
					echo _('vitesse');
					echo '</th><th width="350px">';
					echo _('statut');
					echo '</th><th width="45px">GSM</th><th width="45px">';
					echo _('alim');
					echo '</th>';
					echo '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr></thead><tbody id="body_idTablePosition">';
				}
//			}
//		}else{
//			echo '<tr><th width="50px">N</th><th width="45px"></th><th width="150px">';
//			echo _('nombalise');
//			echo '</th><th width="150px">Date position</th><th width="400px">';
//			echo _('adresse');
//			echo '</th><th width="50px">';
//			echo _('vitesse');
//			echo '</th><th width="350px">';
//			echo _('statut');
//			echo '</th><th width="45px">GSM</th><th width="45px">';
//			echo _('alim');
//			echo '</th>';
//			echo '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>';

		//}

		?>
</tbody>
	</table>