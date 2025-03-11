<?php
	set_time_limit(0);

	include '../function.php';
	include '../dbconnect2.php';
	include('../dbtpositions.php');

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

	include("../../../lib/pChart2.1.4/class/pData.class.php");
	include("../../../lib/pChart2.1.4/class/pDraw.class.php");
	include("../../../lib/pChart2.1.4/class/pImage.class.php");
	include("../../../lib/pChart2.1.4/class/pPie.class.php");
	include("../../../lib/pChart2.1.4/class/pIndicator.class.php");
	$timezone=$_POST["timezone"];
	$idBaliseRapport=$_POST["idBaliseRapport"];
	$debutRapport=$_POST['debutRapport'];
	$finRapport=$_POST['finRapport'];
	$nomDatabaseGpw=$_POST["nomDatabaseGpw"];
	$ipDatabaseGpw=$_POST["ipDatabaseGpw"];
	if( (substr($_SESSION['language'],-2) == "US"))$formatLangDateTime = "Y-m-d h:i:s A"; else $formatLangDateTime = "Y-m-d H:i:s";
	$dUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($debutRapport)),$timezone);
	$fUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($finRapport)),$timezone);

	$Pos_Vitesse = array();
	$Pos_DateTime_position = array();


	$sql = "";
	$arrayTpositions = getAllPeriodTpositions($debutRapport,$finRapport);
	$i = 0;

	$queryFetchArray = function($Pos_Vitesse,$Pos_DateTime_position,$nombrePosition,$idBaliseRapport,$debutRapport,$finRapport) {

		if($nombrePosition >= 20){
			$labelSkipPosition = ($nombrePosition / 10)-1;
		}
		$myData = new pData();
		$myData->addPoints($Pos_Vitesse, _("vitesse")." (km/h)");
		$myData->setAxisName(0,_("vitesse")." (km/h)");

		$myData->addPoints($Pos_DateTime_position,"Date position");
		$myData->setAbscissa("Date position");
		//	$myData->setAbscissaName("Date position");


		$myPicture = new pImage(1700,700,$myData);
		$myPicture->setFontProperties(array("FontName"=>"../../../lib/pChart2.1.4/fonts/MankSans.ttf","FontSize"=>11));
		$myPicture->setGraphArea(50,25,1050,400);
		//	$myPicture->drawFilledRectangle(50,25,1050,400,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
		$myPicture->drawFilledRectangle(50,25,1050,400,array("R"=>0,"G"=>0,"B"=>0,"Surrounding"=>-200,"Alpha"=>10));
		$myPicture->drawScale(array("DrawSubTicks"=>FALSE,'LabelRotation'=>90,'ScaleSpacing'=> 100 ,"R"=>231,"G"=>50,"B"=>36, "Mode"=>SCALE_MODE_START0,"LabelSkip"=>$labelSkipPosition) );

		$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));


		$myPicture->drawSplineChart(array("DisplayPos"=>LABEL_POS_INSIDE,"DisplayValues"=>FALSE,"Rounded"=>TRUE,"Surrounding"=>30,));


		$username = $_SESSION["username"];
		$nomDuFichier = $idBaliseRapport . $username . $debutRapport . $finRapport;
		$nomDuFichier = str_replace(":","",$nomDuFichier);
		$nomDuFichier = str_replace("-","",$nomDuFichier);
		$nomDuFichier = str_replace(" ","",$nomDuFichier);
		$nomDuFichier = str_replace("*","",$nomDuFichier);

        function save_imagepng($img,$fname){
            ob_start();// store output

            imagePNG($img);// output to buffer
            file_put_contents($fname, ob_get_contents(), FILE_BINARY);// write buffer to file
            ob_end_clean();// clear and turn off buffer
        }

//        save_imagepng($myPicture,$nomDuFichier);
        header( "Content-type: image/png" );
        ini_set('display_errors', 'off');
        $myPicture->render("../../assets/img/graph/rapportgraph$nomDuFichier");
	};

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

	$connectGraph = mysqli_connect($ipDatabaseGpw,$db_user_2,$db_pass_2,$nomDatabaseGpw);
	mysqli_set_charset($connectGraph, "utf8");
	if (sizeof($arrayTpositions) > 1 ) {
		for ($i = 0; $i < sizeof($arrayTpositions); $i++) {
			$sql .= "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
							FROM $arrayTpositions[$i] WHERE (Pos_DateTime_position BETWEEN '" . $dUTC . "' AND '" . $fUTC . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapport . "' )
							ORDER BY Pos_DateTime_position;";
		}
		if (mysqli_multi_query($connectGraph, $sql)) {
			do {
				if ($resultGraph = mysqli_store_result($connectGraph)) {
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
					// 	$nombrePosition = 0;
					// 	while($row = mysqli_fetch_array($resultGraph)) {

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
					// 				$Pos_Vitesse[] = $row["Pos_Vitesse"];
					// 				$Pos_DateTime_position[] = $local_date->format($formatLangDateTime);
					// 				$nombrePosition++;

					// 			}

					// 		}
					// 	}
					// 	$queryFetchArray($Pos_Vitesse,$Pos_DateTime_position,$nombrePosition,$idBaliseRapport,$debutRapport,$finRapport);
					// }else{
						while($row = mysqli_fetch_array($resultGraph)) {

							$utc_date = DateTime::createFromFormat(
									'Y-m-d H:i:s',
									$row['Pos_DateTime_position'],
									new DateTimeZone('UTC')
							);
							$local_date = $utc_date;
							$local_date->setTimeZone(new DateTimeZone($timezone));
							$Pos_Vitesse[] = $row["Pos_Vitesse"];
							$Pos_DateTime_position[] = $local_date->format($formatLangDateTime);
							$nombrePosition++;

						}
						$queryFetchArray($Pos_Vitesse,$Pos_DateTime_position,$nombrePosition,$idBaliseRapport,$debutRapport,$finRapport);
					// }

					mysqli_free_result($resultGraph);
				}
			} while (mysqli_more_results($connectGraph) && mysqli_next_result($connectGraph));
		}
	}else{
		$sql = "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
						FROM $arrayTpositions[0] WHERE (Pos_DateTime_position BETWEEN '" . $dUTC . "' AND '" . $fUTC . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapport . "' )
						ORDER BY Pos_DateTime_position";

		$resultGraph = mysqli_query($connectGraph,$sql);
		if( $resultGraph !== false ) {
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
			// 	$nombrePosition = 0;
			// 	while($row = mysqli_fetch_array($resultGraph)) {

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
			// 				$Pos_Vitesse[] = $row["Pos_Vitesse"];
			// 				$Pos_DateTime_position[] = $local_date->format($formatLangDateTime);
			// 				$nombrePosition++;

			// 			}

			// 		}
			// 	}
			// 	$queryFetchArray($Pos_Vitesse,$Pos_DateTime_position,$nombrePosition,$idBaliseRapport,$debutRapport,$finRapport);
			// }else{
				while($row = mysqli_fetch_array($resultGraph)) {

					$utc_date = DateTime::createFromFormat(
							'Y-m-d H:i:s',
							$row['Pos_DateTime_position'],
							new DateTimeZone('UTC')
					);
					$local_date = $utc_date;
					$local_date->setTimeZone(new DateTimeZone($timezone));
					$Pos_Vitesse[] = $row["Pos_Vitesse"];
					$Pos_DateTime_position[] = $local_date->format($formatLangDateTime);
					$nombrePosition++;

				}
				$queryFetchArray($Pos_Vitesse,$Pos_DateTime_position,$nombrePosition,$idBaliseRapport,$debutRapport,$finRapport);
			// }

		}
		mysqli_free_result($resultGraph);
	}
	mysqli_close($connectGraph);






?>