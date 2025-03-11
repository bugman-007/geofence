<?php

	/*
	* Recupere les données en javascript par AJAX pour la dernier position
	*/

	include '../function.php';
	include '../dbconnect2.php';

	header( 'content-type: text/html; charset=utf-8' );
	
	//Bibliotheque pour l'internationalisation
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
	//fin Bibliotheque pour l'internationalisation
	
	session_start();
	$_SESSION['CREATED'] = time();

	if( (substr($_SESSION['language'],-2) == "US"))
		$formatLangDateTime = "Y-m-d h:i:s A"; else $formatLangDateTime = "Y-m-d H:i:s";

	$q=$_GET["Id_Tracker"];
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
	$timezone=$_GET["timezone"];
	$database = $nomDatabaseGpw;
	$server = $ipDatabaseGpw;

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


	$connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
	$sql="SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse,Icone,Pos_Key,Statut2,BattInt,BattExt,Alim,TypeServer
			FROM tpositions0 WHERE Pos_Id_tracker = '".$q."' ORDER BY Pos_DateTime_position DESC LIMIT 1";
	mysqli_set_charset($connection, "utf8");
	$result = mysqli_query($connection,$sql);

	// $connection2=mysqli_connect($server,$db_user_2, $db_pass_2,$database);
	// $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $q . "' )";
	// $result2 = mysqli_query($connection2,$sql2);

	// //On recupère la confidentialité dans tplanings
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


	// 		$icone = $row['Icone'];
	// 		if($icone == null || $icone == ""){
	// 			if(file_exists ("../../assets/img/BibliothequeIcone/".$nomDatabaseGpw."_".$q.".png"))     $icone = $nomDatabaseGpw."_".$q.".png";
	// 			else if(file_exists ("../../assets/img/BibliothequeIcone/".$nomDatabaseGpw."_".$q.".ico"))     $icone = $nomDatabaseGpw."_".$q.".png";
	// 			else     $icone = "default.png";

	// 			$connectionUpdate=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
	// 			$sqlUpdate="UPDATE tpositions0  SET Icone = '".$icone."' WHERE Pos_Id_tracker = '".$q."'";
	// 			mysqli_query($connectionUpdate,$sqlUpdate);
	// 			mysqli_close($connectionUpdate);

	// 		}


	// 			$dateNewDateTime = new DateTime();
	// 			if ( ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0].$Hd1[1]),intval($Hd1[2].$Hd1[3]))->format("H:i:s")))
	// 							&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0].$Hf1[1]),intval($Hf1[2].$Hf1[3]))->format("H:i:s"))	)	 )
	// 					||	( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0].$Hd2[1]),intval($Hd2[2].$Hd2[3]))->format("H:i:s")))
	// 							&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0].$Hf2[1]),intval($Hf2[2].$Hf2[3]))->format("H:i:s"))	)	 )  ) {


	// 				if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
	// 						($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
	// 						($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
	// 						($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
	// 						($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
	// 						($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
	// 						($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")
	// 				) {
	// 					// $url = "https://maps.googleapis.com/maps/api/geocode/xml?latlng=" . $row['Pos_Latitude'] . "," . $row['Pos_Longitude'] . "&sensor=false&client=gme-stancomsas";
	// 					echo "Pos_DateTime_UTC:" . $row['Pos_DateTime_position'];
	// 					echo "Pos_DateTime_position:" . $local_date->format($formatLangDateTime);
	// 					echo "Pos_Latitude:" . $row['Pos_Latitude'];
	// 					echo "Pos_Longitude:" . $row['Pos_Longitude'];
	// 					echo "Pos_Statut:" . $row['Pos_Statut'];
	// 					echo "Pos_Vitesse:" . round( $row['Pos_Vitesse'] );
	// 					echo "Pos_Direction:" . round( $row['Pos_Direction'] );
	// 					echo "Pos_Odometre:" . round( $row['Pos_Odometre'] );
	// 					echo "Pos_Adresse:" . $row['Pos_Adresse'];
	// 					if(isset($_GET['typedecodage']))
	// 					{
	// 						$TypeDecodage = $_GET["typedecodage"];
							
	// 						if($TypeDecodage == 1)
	// 						{
	// 							$DecodedStatus = DecodeStatus($row['Pos_Statut'], $row['Pos_Odometre'], $row['Pos_Key'], $row['Statut2'], $row['BattInt'], $row['BattExt'], $row['Alim'], $row['TypeServer']);
	// 							echo "DecodedStatus:" . $DecodedStatus;
								
	// 								$IconeDirVitesse = IconeBalise($row['Pos_Statut'], $row['Pos_Vitesse'], $row['Pos_Direction']);
	// 								echo "IconDirVitesse:" . $IconeDirVitesse;
									
	// 									$brouilleur = IconeBrouilleur($row['Pos_Odometre'],$row['Pos_Statut']);
	// 									echo "IconeBrouilleur:" . $brouilleur;
	// 									$defautAlim = IconeDefautAlim($row['Pos_Statut']);
	// 									echo "IconeDefautAlim:" . $defautAlim;
	// 						}
	// 					}
	// 					else
	// 					{
	// 						echo "Pos_Key:" . $row['Pos_Key'];
	// 						echo "Statut2:" . round( $row['Statut2'] );
	// 						echo "BattInt:" . round( $row['BattInt'] );
	// 						echo "BattExt:" . round( $row['BattExt'] );
	// 						echo "Alim:" . round( $row['Alim'] );
	// 						echo "TypeServer:" . $row['TypeServer'];
	// 					}
	// 					echo "Icone:" . $icone;
	// 					// echo "url:https://maps.googleapis.com/maps/api/geocode/xml?";
	// 					// echo "data:latlng=" . $row['Pos_Latitude'] . "," . $row['Pos_Longitude'] . "&sensor=false";
	// 					// echo "dataSigned:" . signData($url, 'fOFKm3EqRDQYRgN3Xbey4B4f8ts=');
	// 				}

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

       

			$icone = $row['Icone'];
			if($icone == null || $icone == ""){
				if(file_exists ("../../assets/img/BibliothequeIcone/".$nomDatabaseGpw."_".$q.".png"))     $icone = $nomDatabaseGpw."_".$q.".png";
				else if(file_exists ("../../assets/img/BibliothequeIcone/".$nomDatabaseGpw."_".$q.".ico"))     $icone = $nomDatabaseGpw."_".$q.".png";
				else     $icone = "default.png";

				$connectionUpdate=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
				$sqlUpdate="UPDATE tpositions0  SET Icone = '".$icone."' WHERE Pos_Id_tracker = '".$q."'";
				mysqli_query($connectionUpdate,$sqlUpdate);
				mysqli_close($connectionUpdate);

			}
                        
			// $url = "https://maps.googleapis.com/maps/api/geocode/xml?latlng=" . $row['Pos_Latitude'] . "," . $row['Pos_Longitude'] . "&sensor=false&client=gme-stancomsas";
			echo "Pos_DateTime_UTC:" . $row['Pos_DateTime_position'];
			echo "Pos_DateTime_position:" .$local_date->format($formatLangDateTime);
			echo "Pos_Latitude:" . $row['Pos_Latitude'];
			echo "Pos_Longitude:" . $row['Pos_Longitude'];
			echo "Pos_Statut:" . $row['Pos_Statut'];
			echo "Pos_Vitesse:" . round( $row['Pos_Vitesse'] );
			echo "Pos_Direction:" . round( $row['Pos_Direction'] );
			echo "Pos_Odometre:" . round( $row['Pos_Odometre'] );
			echo "Pos_Adresse:" . $row['Pos_Adresse'];
			if(isset($_GET['typedecodage']))
			{
				$TypeDecodage = $_GET["typedecodage"];
				
				if($TypeDecodage == 1)
				{
					$DecodedStatus = DecodeStatus($row['Pos_Statut'], $row['Pos_Odometre'], $row['Pos_Key'], $row['Statut2'], $row['BattInt'], $row['BattExt'], $row['Alim'], $row['TypeServer']);
					echo "DecodedStatus:" . $DecodedStatus;
					
						$IconeDirVitesse = IconeBalise($row['Pos_Statut'], $row['Pos_Vitesse'], $row['Pos_Direction']);
						echo "IconDirVitesse:" . $IconeDirVitesse;
						
							$brouilleur = IconeBrouilleur($row['Pos_Odometre'],$row['Pos_Statut']);
							echo "IconeBrouilleur:" . $brouilleur;
							$defautAlim = IconeDefautAlim($row['Pos_Statut']);
							echo "IconeDefautAlim:" . $defautAlim;
				}
			}
			else
			{
				echo "Pos_Key:" . $row['Pos_Key'];
				echo "Statut2:" . round( $row['Statut2'] );
				echo "BattInt:" . round( $row['BattInt'] );
				echo "BattExt:" . round( $row['BattExt'] );
				echo "Alim:" . round( $row['Alim'] );
				echo "TypeServer:" . $row['TypeServer'];
			}
			echo "Icone:" . $icone;
			// echo "url:https://maps.googleapis.com/maps/api/geocode/xml?";
			// echo "data:latlng=" . $row['Pos_Latitude'] . "," . $row['Pos_Longitude'] . "&sensor=false";
			// echo "dataSigned:" . signData($url, 'fOFKm3EqRDQYRgN3Xbey4B4f8ts=');
		}
	//}

	mysqli_free_result($result);
	mysqli_close($connection);


	//fonctions permettant le decodage de la cle googlemap avec PHP
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
		return $url['query']."&signature=".$encodedSignature;
	}
?> 