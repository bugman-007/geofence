<?php
	include '../function.php';
	include '../dbconnect2.php';
	include('../dbtpositions.php');
	ini_set('display_errors','off');
	$idBaliseRapportEtape=$_GET["idBaliseRapport"];
	$debutRapportEtape=$_GET['debutRapport'];
	$finRapportEtape=$_GET['finRapport'];
	$numeroEtape=$_GET['numeroEtape'];
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
	$timezone=$_GET["timezone"];
	$dUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($debutRapportEtape)),$timezone);
	$fUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($finRapportEtape)),$timezone);


	$i=0;
	$cbalise = array();
	$sql = "";
	$arrayTpositions = getAllPeriodTpositions($debutRapportEtape,$finRapportEtape);

	if (sizeof($arrayTpositions) > 1 ) {
		for ($i = 0; $i < sizeof($arrayTpositions); $i++) {
			$sql .= "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
			FROM $arrayTpositions[$i] WHERE (Pos_DateTime_position BETWEEN '" . $dUTC . "' AND '" . $fUTC . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapportEtape . "' ) ORDER BY Pos_DateTime_position;";
		}
	}else{
		$sql = "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
						FROM $arrayTpositions[0] WHERE (Pos_DateTime_position BETWEEN '" . $dUTC . "' AND '" . $fUTC . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapportEtape . "' )
						ORDER BY Pos_DateTime_position";
	}

	$cbalise = statutEncodeRapport($sql,$arrayTpositions,$db_user_2,$db_pass_2);
	/********************************************************************/

	$connectDate = mysqli_connect($ipDatabaseGpw,$db_user_2,$db_pass_2,$nomDatabaseGpw);

	$i=0;		//incrementation lecture sql
	$y=1;		//incrementation ligne tableau etape

	$condition = "";
	$dateDebutEtape = array();
	$dateFinEtape = array();
	$km = array();

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
	// $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $idBaliseRapportEtape . "' )";
	// $result2 = mysqli_query($connection2,$sql2);


	if (sizeof($arrayTpositions) > 1 ) {
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
					// 		$utc_date = DateTime::createFromFormat(
					// 				'Y-m-d H:i:s',
					// 				$row['Pos_DateTime_position'],
					// 				new DateTimeZone('UTC')
					// 		);
					// 		$local_date = $utc_date;
					// 		$local_date->setTimeZone(new DateTimeZone($timezone));
					// 		ini_set('display_errors', 'off');
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
					// 				if ($condition == "") {
					// 					if ((($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop")) || (($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop"))
					// 							|| (($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop")) || (($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop"))
					// 					) {

					// 						$latDebut = $row["Pos_Latitude"];
					// 						$lngDebut = $row["Pos_Longitude"];
					// 						$dateDebutEtape[$y] = $row['Pos_DateTime_position'];

					// 						$condition = "ok";

					// 					}
					// 				}
					// 				if ($condition == "ok") {

					// 					if ($cbalise[$i] != "stop" && ($cbalise[$i + 1] == "stop")) {
					// 						$km[$y] = get_distance_m($latDebut, $lngDebut, $row["Pos_Latitude"], $row["Pos_Longitude"]);
					// 						$dateFinEtape[$y] = $row['Pos_DateTime_position'];
					// 						$y++;
					// 						$condition = "";
					// 					}
					// 				}

					// 				$i++;
					// 			}
					// 		}
					// 	}
					// }else {
						while ($row = mysqli_fetch_array($resultDate)) {

							if ($condition == "") {
								if ((($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop")) || (($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop"))
										|| (($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop")) || (($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop"))
								) {

									$latDebut = $row["Pos_Latitude"];
									$lngDebut = $row["Pos_Longitude"];
									$dateDebutEtape[$y] = $row['Pos_DateTime_position'];

									$condition = "ok";

								}
							}
							if ($condition == "ok") {

								if ($cbalise[$i] != "stop" && ($cbalise[$i + 1] == "stop")) {
									$km[$y] = get_distance_m($latDebut, $lngDebut, $row["Pos_Latitude"], $row["Pos_Longitude"]);
									$dateFinEtape[$y] = $row['Pos_DateTime_position'];
									$y++;
									$condition = "";
								}
							}

							$i++;
						}
					//}
					mysqli_free_result($resultDate);
				}
			} while (mysqli_more_results($connectDate) && mysqli_next_result($connectDate));
		}
	}else{
		$resultDate = mysqli_query($connectDate,$sql);
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
		// 		$utc_date = DateTime::createFromFormat(
		// 				'Y-m-d H:i:s',
		// 				$row['Pos_DateTime_position'],
		// 				new DateTimeZone('UTC')
		// 		);
		// 		$local_date = $utc_date;
		// 		$local_date->setTimeZone(new DateTimeZone($timezone));
		// 		ini_set('display_errors', 'off');

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
		// 				if ($condition == "") {
		// 					if ((($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop")) || (($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop"))
		// 							|| (($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop")) || (($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop"))
		// 					) {

		// 						$latDebut = $row["Pos_Latitude"];
		// 						$lngDebut = $row["Pos_Longitude"];
		// 						$dateDebutEtape[$y] = $row['Pos_DateTime_position'];

		// 						$condition = "ok";

		// 					}
		// 				}
		// 				if ($condition == "ok") {

		// 					if ($cbalise[$i] != "stop" && ($cbalise[$i + 1] == "stop")) {
		// 						$km[$y] = get_distance_m($latDebut, $lngDebut, $row["Pos_Latitude"], $row["Pos_Longitude"]);
		// 						$dateFinEtape[$y] = $row['Pos_DateTime_position'];
		// 						$y++;
		// 						$condition = "";
		// 					}
		// 				}

		// 				$i++;
		// 			}
		// 		}
		// 	}
		// }else {
			while ($row = mysqli_fetch_array($resultDate)) {

				if ($condition == "") {
					if ((($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop")) || (($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop"))
							|| (($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop")) || (($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop"))
					) {

						$latDebut = $row["Pos_Latitude"];
						$lngDebut = $row["Pos_Longitude"];
						$dateDebutEtape[$y] = $row['Pos_DateTime_position'];

						$condition = "ok";

					}
				}
				if ($condition == "ok") {

					if ($cbalise[$i] != "stop" && ($cbalise[$i + 1] == "stop")) {
						$km[$y] = get_distance_m($latDebut, $lngDebut, $row["Pos_Latitude"], $row["Pos_Longitude"]);
						$dateFinEtape[$y] = $row['Pos_DateTime_position'];
						$y++;
						$condition = "";
					}
				}

				$i++;
			}
		// }
	}
	mysqli_close($connectDate);

	/********************************************************************/

	 $url= "http://maps.googleapis.com/maps/api/staticmap?size=600x400";

	$connectCarto = mysqli_connect($ipDatabaseGpw,$db_user_2,$db_pass_2,$nomDatabaseGpw);
	mysqli_set_charset($connectCarto, "utf8");

	$latDebut2 = "";
	$lngDebut2 = "";
	$conditionPremierePosition = "";
	$condition2 = "";
	$filtrageKm40 = $km[$numeroEtape] / 40 ;
	$i = 0;
	$km2 = array();
	$sql2 = "";
	$lengthCarto = 0;
		if (sizeof($arrayTpositions) > 1 ) {
			for ($i = 0; $i < sizeof($arrayTpositions); $i++) {
				$sql2 .= "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
					FROM $arrayTpositions[$i] WHERE (Pos_DateTime_position BETWEEN '" . $dateDebutEtape[$numeroEtape] . "' AND '" . $dateFinEtape[$numeroEtape] . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapportEtape . "' ) ORDER BY Pos_DateTime_position;";
			}
			$i = 0;
			if (mysqli_multi_query($connectCarto, $sql)) {
				do {
					if ($resultCarto = mysqli_store_result($connectCarto)) {
						$lengthCarto += mysqli_num_rows($resultCarto);
					}
				} while (mysqli_more_results($connectCarto) && mysqli_next_result($connectCarto));
			}
			if (mysqli_multi_query($connectCarto, $sql)) {
				do {
					if ($resultCarto = mysqli_store_result($connectCarto)) {
						if($lengthCarto > 40){
							while($row = mysqli_fetch_array($resultCarto)){
								if($conditionPremierePosition == "") {
									$url .= "&markers=color:red%7Clabel:A%7C".$row['Pos_Latitude'] . "," . $row['Pos_Longitude'] . "&path=".$row['Pos_Latitude'] . "," . $row['Pos_Longitude']. "|";
									$conditionPremierePosition = "ok";
								}
								if($condition2 == "") {
									$latDebut2 = $row["Pos_Latitude"];
									$lngDebut2 = $row["Pos_Longitude"];
									$condition2 = "ok";
								}


								if($condition2 == "ok") {
									$km2 = get_distance_m($latDebut2, $lngDebut2, $row["Pos_Latitude"], $row["Pos_Longitude"]);

									if ($km2 > $filtrageKm40) {
										//			if($i<40)
										$url .= $row['Pos_Latitude'] . "," . $row['Pos_Longitude'] . "|";
										$condition2 = "";

									}
								}
								if(($lengthCarto-1) == $i) $url .= $row['Pos_Latitude'] . "," . $row['Pos_Longitude']. "&markers=color:red%7Clabel:B%7C".$row['Pos_Latitude'] . "," . $row['Pos_Longitude'];
								$i++;
							}
						}else{
							while($row = mysqli_fetch_array($resultCarto)){
								if($conditionPremierePosition == "") {
									$url .= "&markers=color:red%7Clabel:A%7C".$row['Pos_Latitude'] . "," . $row['Pos_Longitude'] . "&path=".$row['Pos_Latitude'] . "," . $row['Pos_Longitude']. "|";
									$conditionPremierePosition = "ok";
								}
								$url .= $row['Pos_Latitude']. "," . $row['Pos_Longitude'] . "|";

								if(($lengthCarto-1) == $i) $url .= $row['Pos_Latitude'] . "," . $row['Pos_Longitude']. "&markers=color:red%7Clabel:B%7C".$row['Pos_Latitude'] . "," . $row['Pos_Longitude'];
								$i++;
							}
						}

						mysqli_free_result($resultCarto);
					}
				} while (mysqli_more_results($connectCarto) && mysqli_next_result($connectCarto));
			}
		}else{
			$sql2 .= "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
					FROM $arrayTpositions[0] WHERE (Pos_DateTime_position BETWEEN '" . $dateDebutEtape[$numeroEtape] . "' AND '" . $dateFinEtape[$numeroEtape] . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapportEtape . "' ) ORDER BY Pos_DateTime_position;";


			$resultCarto = mysqli_query($connectCarto,$sql2);
			$lengthCarto = mysqli_num_rows($resultCarto);

			if($lengthCarto > 40){
				while($row = mysqli_fetch_array($resultCarto)){
					if($conditionPremierePosition == "") {
						$url .= "&markers=color:red%7Clabel:A%7C".$row['Pos_Latitude'] . "," . $row['Pos_Longitude'] . "&path=".$row['Pos_Latitude'] . "," . $row['Pos_Longitude']. "|";
						$conditionPremierePosition = "ok";
					}
					if($condition2 == "") {
						$latDebut2 = $row["Pos_Latitude"];
						$lngDebut2 = $row["Pos_Longitude"];
						$condition2 = "ok";
					}


					if($condition2 == "ok") {
						$km2 = get_distance_m($latDebut2, $lngDebut2, $row["Pos_Latitude"], $row["Pos_Longitude"]);

						if ($km2 > $filtrageKm40) {
							//			if($i<40)
							$url .= $row['Pos_Latitude'] . "," . $row['Pos_Longitude'] . "|";
							$condition2 = "";

						}
					}
					if(($lengthCarto-1) == $i) $url .= $row['Pos_Latitude'] . "," . $row['Pos_Longitude']. "&markers=color:red%7Clabel:B%7C".$row['Pos_Latitude'] . "," . $row['Pos_Longitude'];
					$i++;
				}
			}else{
				while($row = mysqli_fetch_array($resultCarto)){
					if($conditionPremierePosition == "") {
						$url .= "&markers=color:red%7Clabel:A%7C".$row['Pos_Latitude'] . "," . $row['Pos_Longitude'] . "&path=".$row['Pos_Latitude'] . "," . $row['Pos_Longitude']. "|";
						$conditionPremierePosition = "ok";
					}
					$url .= $row['Pos_Latitude']. "," . $row['Pos_Longitude'] . "|";

					if(($lengthCarto-1) == $i) $url .= $row['Pos_Latitude'] . "," . $row['Pos_Longitude']. "&markers=color:red%7Clabel:B%7C".$row['Pos_Latitude'] . "," . $row['Pos_Longitude'];
					$i++;
				}
			}

		}



	mysqli_close($connectCarto);

	//$trimmedUrl = rtrim($url, "|");
	//$trimmedUrl .= "&sensor=false&client=gme-stancomsas";
	//echo "http://maps.googleapis.com/maps/api/staticmap?".signData($trimmedUrl, 'fOFKm3EqRDQYRgN3Xbey4B4f8ts=');

	/****************************************************************************/
	function get_distance_m($lat1, $lng1, $lat2, $lng2) {
		$earth_radius = 6378137;   // Terre = sph�re de 6378km de rayon
		$rlo1 = deg2rad($lng1);
		$rla1 = deg2rad($lat1);
		$rlo2 = deg2rad($lng2);
		$rla2 = deg2rad($lat2);
		$dlo = ($rlo2 - $rlo1) / 2;
		$dla = ($rla2 - $rla1) / 2;
		$a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo
						));
		$d = 2 * atan2(sqrt($a), sqrt(1 - $a));
		return round(($earth_radius * $d)/1000,3);
	}

	function encodeBase64UrlSafe($value){
		return str_replace(array('+', '/'), array('-', '_'), base64_encode($value));
	}

	function decodeBase64UrlSafe($value){
		return base64_decode(str_replace(array('-', '_'), array('+', '/'), $value));
	}


	function signUrl($myUrlToSign, $privateKey)	{
		$url = parse_url($myUrlToSign);
		$urlPartToSign =  $url['path'] . "?" . $url['query'];
		$decodedKey = decodeBase64UrlSafe($privateKey);
		$signature = hash_hmac("sha1",$urlPartToSign, $decodedKey,  true);
		$encodedSignature = encodeBase64UrlSafe($signature);
		return $url['path'];
	}

	function signData($myUrlToSign, $privateKey)	{

		$url = parse_url($myUrlToSign);
		$urlPartToSign = $url['path'] . "?" . $url['query'];
		$decodedKey = decodeBase64UrlSafe($privateKey);
		$signature = hash_hmac("sha1",$urlPartToSign, $decodedKey,  true);
		$encodedSignature = encodeBase64UrlSafe($signature);
	//		$encodedSignature1 = str_replace("+","-",$encodedSignature);
	//		$encodedSignature2 = str_replace("/","_",$encodedSignature1);
		return $url['query']."&signature=".$encodedSignature;
	}


	/************************************************************************************************
	 *************************************************************************************************	statutEncode
	 ************************************************************************************************/
	function statutEncodeRapport($sql,$arrayTpositions,$db_user_2,$db_pass_2){

		$i=0;
		$cbalise = array();
		$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
		$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
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
		// $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $_GET['idBaliseRapport'] . "' )";
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
						// 		$local_date->setTimeZone(new DateTimeZone($_GET["timezone"]));

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
						//}
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
			// 		$local_date->setTimeZone(new DateTimeZone($_GET["timezone"]));

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