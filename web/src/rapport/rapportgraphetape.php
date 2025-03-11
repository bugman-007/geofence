<?php
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

	set_time_limit(0);
	ini_set('display_errors','off');
	include("../../../lib/pChart2.1.4/class/pData.class.php");
	include("../../../lib/pChart2.1.4/class/pDraw.class.php");
	include("../../../lib/pChart2.1.4/class/pImage.class.php");
	include("../../../lib/pChart2.1.4/class/pPie.class.php");
	include("../../../lib/pChart2.1.4/class/pIndicator.class.php");
	include '../function.php';
	include '../dbconnect2.php';
	include('../dbtpositions.php');
	$idBaliseRapportEtape=$_POST["idBaliseRapport"];
	$debutRapportEtape=$_POST['debutRapport'];
	$finRapportEtape=$_POST['finRapport'];
	$numeroEtape=$_POST['numeroEtape'];
	$nomDatabaseGpw=$_POST["nomDatabaseGpw"];
	$ipDatabaseGpw=$_POST["ipDatabaseGpw"];
	$timezone=$_POST["timezone"];
	if( (substr($_SESSION['language'],-2) == "US"))$formatLangDateTime = "Y-m-d h:i:s A"; else $formatLangDateTime = "Y-m-d H:i:s";
	$dUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($debutRapportEtape)),$timezone);
	$fUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($finRapportEtape)),$timezone);


	/********************************************************************/
	$i=0;
	$cbalise = array();

	$i=0;		//incrementation lecture sql
	$y=1;		//incrementation ligne tableau etape
	$boubou=0;

	$position = 0;
	$positionEtape = array();

	$dateDebutEtape = array();
	$dateFinEtape = array();
	$condition = "";
	$conditionOk = "";


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
	// $connection2=mysqli_connect($ipDatabaseGpw,$db_user_2, $db_pass_2,$nomDatabaseGpw);
	// $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $idBaliseRapport . "' )";
	// $result2 = mysqli_query($connection2,$sql2);


	$sql = "";
	$arrayTpositions = getAllPeriodTpositions($debutRapportEtape,$finRapportEtape);
	$i = 0;
	$connectDate = mysqli_connect($ipDatabaseGpw,$db_user_2,$db_pass_2,$nomDatabaseGpw);
	mysqli_set_charset($connectDate, "utf8");
	if (sizeof($arrayTpositions) > 1 ) {
		for ($i = 0; $i < sizeof($arrayTpositions); $i++) {
			$sql .= "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
							FROM $arrayTpositions[$i] WHERE (Pos_DateTime_position BETWEEN '" . $dUTC . "' AND '" . $fUTC . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapportEtape . "' )
							ORDER BY Pos_DateTime_position;";
		}
		$cbalise = statutEncodeRapport($sql,$arrayTpositions,$db_user_2,$db_pass_2);
		$i = 0;
		if (mysqli_multi_query($connectDate, $sql)) {
			do {
				if ($resultDate = mysqli_store_result($connectDate)) {

					// if(mysqli_num_rows($result2) > 0 ) {

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
					// 	while ($row = mysqli_fetch_array($resultDate)) {

					// 		ini_set('display_errors', 'off');
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
					// 				&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0].$Hf2[1]),intval($Hf2[2].$Hf2[3]))->format("H:i:s"))	)	 )  ) {

					// 			if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
					// 					($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
					// 					($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
					// 					($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
					// 					($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
					// 					($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
					// 					($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")
					// 			) {
					// 				if ($conditionOk == "") {
					// 					if ($cbalise[$i] != "stop") {
					// 						$dateDebutEtape[$y] = $row['Pos_DateTime_position'];
					// 						$conditionOk = "ok";
					// 						$condition = "ok";
					// 					}

					// 				}
					// 				if ($condition == "ok") {
					// 					if ($cbalise[$i] == "stop") {
					// 						$dateFinEtape[$y] = $row['Pos_DateTime_position'];
					// 						$positionEtape[$y] = $position;
					// 						$y++;
					// 						$position = 0;
					// 						$conditionOk = "";
					// 						$condition = "";
					// 					}
					// 				}
					// 				$position++;
					// 				$i++;
					// 			}
					// 		}
					// 	}
					// }else {
						while ($row = mysqli_fetch_array($resultDate)) {

							ini_set('display_errors', 'off');
							if ($conditionOk == "") {
								if ($cbalise[$i] != "stop") {
									$dateDebutEtape[$y] = $row['Pos_DateTime_position'];
									$conditionOk = "ok";
									$condition = "ok";
								}

							}
							if ($condition == "ok") {
								if ($cbalise[$i] == "stop") {
									$dateFinEtape[$y] = $row['Pos_DateTime_position'];
									$positionEtape[$y] = $position;
									$y++;
									$position = 0;
									$conditionOk = "";
									$condition = "";
								}
							}
							$position++;
							$i++;
						}
					// }
					mysqli_free_result($resultDate);
				}
			} while (mysqli_more_results($connectDate) && mysqli_next_result($connectDate));
		}
	}else{
		$sql = "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
						FROM $arrayTpositions[0] WHERE (Pos_DateTime_position BETWEEN '" . $dUTC . "' AND '" . $fUTC . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapportEtape . "' )
						ORDER BY Pos_DateTime_position";
		$cbalise = statutEncodeRapport($sql,$arrayTpositions,$db_user_2,$db_pass_2);
		$resultDate = mysqli_query($connectDate,$sql);
		$i = 0;
		if( $resultDate !== false ) {
			// if(mysqli_num_rows($result2) > 0 ) {

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
			// 	while ($row = mysqli_fetch_array($resultDate)) {

			// 		ini_set('display_errors', 'off');
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
			// 				&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0].$Hf2[1]),intval($Hf2[2].$Hf2[3]))->format("H:i:s"))	)	 )  ) {

			// 			if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
			// 					($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
			// 					($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
			// 					($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
			// 					($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
			// 					($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
			// 					($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")
			// 			) {
			// 				if ($conditionOk == "") {
			// 					if ($cbalise[$i] != "stop") {
			// 						$dateDebutEtape[$y] = $row['Pos_DateTime_position'];
			// 						$conditionOk = "ok";
			// 						$condition = "ok";
			// 					}

			// 				}
			// 				if ($condition == "ok") {
			// 					if ($cbalise[$i] == "stop") {
			// 						$dateFinEtape[$y] = $row['Pos_DateTime_position'];
			// 						$positionEtape[$y] = $position;
			// 						$y++;
			// 						$position = 0;
			// 						$conditionOk = "";
			// 						$condition = "";
			// 					}
			// 				}
			// 				$position++;
			// 				$i++;
			// 			}
			// 		}
			// 	}
			// }else {
				while ($row = mysqli_fetch_array($resultDate)) {

					ini_set('display_errors', 'off');
					if ($conditionOk == "") {
						if ($cbalise[$i] != "stop") {
							$dateDebutEtape[$y] = $row['Pos_DateTime_position'];
							$conditionOk = "ok";
							$condition = "ok";
						}

					}
					if ($condition == "ok") {
						if ($cbalise[$i] == "stop") {
							$dateFinEtape[$y] = $row['Pos_DateTime_position'];
							$positionEtape[$y] = $position;
							$y++;
							$position = 0;
							$conditionOk = "";
							$condition = "";
						}
					}
					$position++;
					$i++;
				}
			// }
		}
		mysqli_free_result($resultDate);
	}
	mysqli_close($connectDate);
	/********************************************************************/

	/********************************************************************/
	$i=0;
	$y=0;
	$Pos_Vitesse = array();
	$Pos_DateTime_position = array();

	$connectGraph = mysqli_connect($ipDatabaseGpw,$db_user_2,$db_pass_2,$nomDatabaseGpw);
	mysqli_set_charset($connectGraph, "utf8");

	if (sizeof($arrayTpositions) > 1 ) {
		for ($i = 0; $i < sizeof($arrayTpositions); $i++) {
			$sql .= "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
							FROM $arrayTpositions[$i] WHERE (Pos_DateTime_position BETWEEN '" . $dateDebutEtape[$numeroEtape] . "' AND '" . $dateFinEtape[$numeroEtape] . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapportEtape . "' )
							ORDER BY Pos_DateTime_position;";
		}
		if (mysqli_multi_query($connectGraph, $sql)) {
			do {
				if ($resultGraph = mysqli_store_result($connectGraph)) {
					while($row = mysqli_fetch_array($resultGraph)){
						$utc_date = DateTime::createFromFormat(
								'Y-m-d H:i:s',
								$row['Pos_DateTime_position'],
								new DateTimeZone('UTC')
						);
						$local_date = $utc_date;
						$local_date->setTimeZone(new DateTimeZone($timezone));
						$Pos_Vitesse[] = $row["Pos_Vitesse"];
						$Pos_DateTime_position[] = $local_date->format($formatLangDateTime);
					}

					mysqli_free_result($resultGraph);
				}
			} while (mysqli_more_results($connectGraph) && mysqli_next_result($connectGraph));
		}
	}else{
		$sql = "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
						FROM $arrayTpositions[0] WHERE (Pos_DateTime_position BETWEEN '" . $dateDebutEtape[$numeroEtape] . "' AND '" . $dateFinEtape[$numeroEtape] . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapportEtape . "' )
						ORDER BY Pos_DateTime_position";

		$resultGraph = mysqli_query($connectGraph,$sql);
		if( $resultGraph !== false ) {
			while($row = mysqli_fetch_array($resultGraph)){
				$utc_date = DateTime::createFromFormat(
						'Y-m-d H:i:s',
						$row['Pos_DateTime_position'],
						new DateTimeZone('UTC')
				);
				$local_date = $utc_date;
				$local_date->setTimeZone(new DateTimeZone($timezone));
				$Pos_Vitesse[] = $row["Pos_Vitesse"];
				$Pos_DateTime_position[] = $local_date->format($formatLangDateTime);
			}
		}
		mysqli_free_result($resultGraph);
	}
	mysqli_close($connectGraph);

	/****************************************************************************/
	if($positionEtape[$numeroEtape] >= 20){
		$labelSkipPosition = ($positionEtape[$numeroEtape] / 10)-1;
	}
	$myData = new pData();
	$myData->addPoints($Pos_Vitesse, _("vitesse")." (km/h)");
	$myData->setAxisName(0, _("vitesse")." (km/h)");

	$myData->addPoints($Pos_DateTime_position,"Date et Heure");
	$myData->setAbscissa("Date et Heure");
//	$myData->setAbscissaName("Date et Heure");


	$myPicture = new pImage(1700,700,$myData);
	$myPicture->setFontProperties(array("FontName"=>"../../../lib/pChart2.1.4/fonts/MankSans.ttf","FontSize"=>11));
	$myPicture->setGraphArea(50,25,1050,400);
//	$myPicture->drawFilledRectangle(50,25,1050,400,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
	$myPicture->drawFilledRectangle(50,25,1050,400,array("R"=>0,"G"=>0,"B"=>0,"Surrounding"=>-200,"Alpha"=>10));
	$myPicture->drawScale(array("DrawSubTicks"=>FALSE,'LabelRotation'=>90,'ScaleSpacing'=> 100 ,"R"=>231,"G"=>50,"B"=>36, "Mode"=>SCALE_MODE_START0,"LabelSkip"=>$labelSkipPosition) );

	$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

	/* Draw the chart */
	$myPicture->drawSplineChart(array("DisplayPos"=>LABEL_POS_INSIDE,"DisplayValues"=>FALSE,"Rounded"=>TRUE,"Surrounding"=>30,));
	// $verify = 1;
	// $incrementVerify = 1;
	// while($verify != 1){
		// if (file_exists(("rapportgraphetape".$incrementVerify.".png")) {
			// $incrementVerify++;
		// }else {
			// $myPicture->Render("rapportgraphetape".$incrementVerify.".png");
			// $verify = 0;
		// }
	// }
	$username = $_SESSION["username"];
	$nomDuFichier = $numeroEtape."_".$idBaliseRapportEtape . $username . $debutRapportEtape . $finRapportEtape;
	$nomDuFichier = str_replace(":","",$nomDuFichier);
	$nomDuFichier = str_replace("-","",$nomDuFichier);
	$nomDuFichier = str_replace(" ","",$nomDuFichier);
	$nomDuFichier = str_replace("*","",$nomDuFichier);
	$myPicture->Render("../../assets/img/graph/rapportgraphetape$nomDuFichier.png");

function statutEncodeRapport($sql,$arrayTpositions,$db_user_2,$db_pass_2){

	$i=0;
	$cbalise = array();
	$nomDatabaseGpw=$_POST["nomDatabaseGpw"];
	$ipDatabaseGpw=$_POST["ipDatabaseGpw"];
	$connectStatutEncode = mysqli_connect($ipDatabaseGpw,$db_user_2,$db_pass_2,$nomDatabaseGpw);

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

	// $connection2=mysqli_connect($ipDatabaseGpw,$db_user_2, $db_pass_2,$nomDatabaseGpw);
	// $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $_POST['idBaliseRapport'] . "' )";
	// $result2 = mysqli_query($connection2,$sql2);

	$queryFetchArray = function($cbalise,$rowStatutEncode,$i) {

		$statutRecup = $rowStatutEncode['Pos_Statut'];
		$statutEncode = array();
		$puissance = 31;
		while ($puissance > 0) {
			if (pow(2, $puissance) > $statutRecup) {
				array_push($statutEncode, "0");
			} else {
				$statutRecup = $statutRecup - pow(2, $puissance);
				array_push($statutEncode, "1");
			}
			$puissance--;
		}
		if ($statutEncode[29] == "1") {
			if ($rowStatutEncode['Pos_Vitesse'] == 0) {
				$cbalise[$i] = "rouge";
			}
			if ($rowStatutEncode['Pos_Vitesse'] <= 10) {
				$cbalise[$i] = "jaune";
			}
			if ($rowStatutEncode['Pos_Vitesse'] > 10) {
				$cbalise[$i] = "vert";
			}
		} else {
			$cbalise[$i] = "stop";
		}

		return $cbalise[$i];
	};

	if (sizeof($arrayTpositions) > 1 ) {
		$i = 0;
		if (mysqli_multi_query($connectStatutEncode, $sql)) {
			do {
				if ($resultStatutEncode = mysqli_store_result($connectStatutEncode)) {

					// if(mysqli_num_rows($result2) > 0 ) {

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
					// 	while($rowStatutEncode = mysqli_fetch_array($resultStatutEncode)) {

					// 		$utc_date = DateTime::createFromFormat(
					// 				'Y-m-d H:i:s',
					// 				$rowStatutEncode['Pos_DateTime_position'],
					// 				new DateTimeZone('UTC')
					// 		);
					// 		$local_date = $utc_date;
					// 		$local_date->setTimeZone(new DateTimeZone($_POST["timezone"]));
					// 		$dateNewDateTime = new DateTime();
					// 		if ( ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0].$Hd1[1]),intval($Hd1[2].$Hd1[3]))->format("H:i:s")))
					// 				&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0].$Hf1[1]),intval($Hf1[2].$Hf1[3]))->format("H:i:s"))	)	 )
					// 			||	( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0].$Hd2[1]),intval($Hd2[2].$Hd2[3]))->format("H:i:s")))
					// 				&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0].$Hf2[1]),intval($Hf2[2].$Hf2[3]))->format("H:i:s"))	)	 )  ) {

					// 			if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
					// 					($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
					// 					($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
					// 					($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
					// 					($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
					// 					($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
					// 					($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")) {

					// 				$cbalise[$i] = $queryFetchArray($cbalise,$rowStatutEncode,$i);
					// 				$i++;
					// 			}
					// 		}
					// 	}
					// }else{
						while($rowStatutEncode = mysqli_fetch_array($resultStatutEncode)){
							$cbalise[$i] = $queryFetchArray($cbalise,$rowStatutEncode,$i);
							$i++;
						}
					// }
					mysqli_free_result($resultStatutEncode);
				}
			} while (mysqli_more_results($connectStatutEncode) && mysqli_next_result($connectStatutEncode));
		}
	}else{
		$resultStatutEncode = mysqli_query($connectStatutEncode,$sql);

		// if(mysqli_num_rows($result2) > 0 ) {

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
		// 	while($rowStatutEncode = mysqli_fetch_array($resultStatutEncode)) {

		// 		$utc_date = DateTime::createFromFormat(
		// 				'Y-m-d H:i:s',
		// 				$rowStatutEncode['Pos_DateTime_position'],
		// 				new DateTimeZone('UTC')
		// 		);
		// 		$local_date = $utc_date;
		// 		$local_date->setTimeZone(new DateTimeZone($_POST["timezone"]));
		// 		$dateNewDateTime = new DateTime();
		// 		if ( ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0].$Hd1[1]),intval($Hd1[2].$Hd1[3]))->format("H:i:s")))
		// 				&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0].$Hf1[1]),intval($Hf1[2].$Hf1[3]))->format("H:i:s"))	)	 )
		// 			||	( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0].$Hd2[1]),intval($Hd2[2].$Hd2[3]))->format("H:i:s")))
		// 				&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0].$Hf2[1]),intval($Hf2[2].$Hf2[3]))->format("H:i:s"))	)	 )  ) {

		// 			if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
		// 					($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
		// 					($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
		// 					($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
		// 					($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
		// 					($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
		// 					($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")) {

		// 				$cbalise[$i] = $queryFetchArray($cbalise,$rowStatutEncode,$i);
		// 				$i++;
		// 			}
		// 		}
		// 	}
		// }else{
			while($rowStatutEncode = mysqli_fetch_array($resultStatutEncode)){
				$cbalise[$i] = $queryFetchArray($cbalise,$rowStatutEncode,$i);
				$i++;
			}
		// }
	}
	mysqli_close($connectStatutEncode);
	return $cbalise;
}
?>