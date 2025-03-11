<?php

	/*
	 * 	Recuperation javascript par ajax des données pour les positions sur historique positions
	* 	Carto:js
	*/
	
	header( 'content-type: text/html; charset=utf-8' );
	
	session_start();
	if( (substr($_SESSION['language'],-2) == "US"))$formatLangDateTime = "Y-m-d h:i:s A"; else $formatLangDateTime = "Y-m-d H:i:s";
	$_SESSION['CREATED'] = time();
	set_time_limit(0);

	//INITIALISATION VARIABLE
	include '../function.php';
	$q=$_GET["Id_Tracker"];
	$p=$_GET["pos"];
	$n=$_GET["n"];
	$select=$_GET["select"];
	$ordre=$_GET["ordre"];
	$timezone=$_GET["timezone"];

	$pUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($p)),$timezone);

	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];

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
		if(intval($yearChosen) < $yearNow){
			$tableTPositions = "tpositions".$yearChosen."12";
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

	//if(intval($n) < 1000)
		$sql="SELECT  * FROM $tableTPositions WHERE (Pos_Id_tracker = '".$q."' AND Pos_DateTime_position ".$select." '".$pUTC."') ORDER BY (Pos_DateTime_position) ".$ordre." LIMIT ".$n." ";
	//else{
	//	$sql = "SELECT *
	//				FROM (SELECT Pos_Id_tracker, Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse from $tableTPositions
	//				UNION  SELECT Pos_Id_tracker, Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse from $tableTPositions2) AS t1
	//				WHERE (t1.Pos_Id_tracker = '".$q."' AND t1.Pos_DateTime_position ".$select." '".$pUTC."')
	//				ORDER BY (t1.Pos_DateTime_position) $ordre LIMIT  ".$n." ";
	//}
	$result = mysqli_query($connection,$sql);
	$rowCount = mysqli_num_rows($result);

	$incrementTable=0;
	while($rowCount == 0){
//		mysqli_free_result($result);
		$tableTPositions = $tableTPositions2;

		$sql="SELECT  * FROM $tableTPositions2 WHERE (Pos_Id_tracker = '".$q."' AND Pos_DateTime_position ".$select." '".$pUTC."') ORDER BY (Pos_DateTime_position) ".$ordre." LIMIT ".$n." ";
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

	// $connection2=mysqli_connect($server,$db_user_2, $db_pass_2,$database);
	// $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $q . "' )";
	// $result2 = mysqli_query($connection2,$sql2);
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

	// 	$dateTimeChosen = new DateTime($pUTC);

	// 	$dd1 = $dateTimeChosen->setTime(intval($Hd1[0] . $Hd1[1]), intval($Hd1[2] . $Hd1[3]))->format('Y-m-d H:i:s');
	// 	$df1 = $dateTimeChosen->setTime(intval($Hf1[0] . $Hf1[1]), intval($Hf1[2] . $Hf1[3]))->format('Y-m-d H:i:s');
	// 	$dd2 = $dateTimeChosen->setTime(intval($Hd2[0] . $Hd2[1]), intval($Hd2[2] . $Hd2[3]))->format('Y-m-d H:i:s');
	// 	$df2 = $dateTimeChosen->setTime(intval($Hf2[0] . $Hf2[1]), intval($Hf2[2] . $Hf2[3]))->format('Y-m-d H:i:s');

	// 	$dd1 = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($dd1)), $timezone);
	// 	$df1 = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($df1)), $timezone);
	// 	$dd2 = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($dd2)), $timezone);
	// 	$df2 = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($df2)), $timezone);

	// 	$i = 1;
	// 	$x=1;
	// 	$y = 0;
	// 	$w = 0;
	// 	$z = 0;

	// 	while ($z < intval($_GET["n"])) {
	// 		$sql = "SELECT * FROM $tableTPositions WHERE ( (Pos_Id_tracker = '" . $q . "') AND ( (Pos_DateTime_position BETWEEN '$dd1' AND '$df1')
	// 				OR (Pos_DateTime_position BETWEEN '$dd2' AND '$df2') )  AND ( Pos_DateTime_position " . $select . " '" . $pUTC . "') ) ORDER BY (Pos_DateTime_position) " . $ordre . " LIMIT " . $n . " ";
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

	// 		if ($w == 368)
	// 			$z =  intval($_GET["n"]);


	// 		if ($result !== false) {

	// 			$rowCount = mysqli_num_rows($result);

	// 			if ($rowCount == 0) {

	// 			}else{


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
	// 							echo "t" . $rowCount . "g";
	// 							echo "Pos_Latitude:" . $row['Pos_Latitude'];
	// 							echo "Pos_DateTime_position:" . $local_date->format($formatLangDateTime);
	// 							echo "Pos_Statut:" . $row['Pos_Statut'];
	// 							echo "Pos_Vitesse:" . $row['Pos_Vitesse'];
	// 							echo "Pos_Direction:" . $row['Pos_Direction'];
	// 							echo "Pos_Odometre:" . $row['Pos_Odometre'];
	// 							//echo "Pos_Adresse:" . $row['Pos_Adresse'];
	// 							echo "Pos_Adresse:" . str_replace("&", "et", $row['Pos_Adresse']);
	// 							echo "Pos_Longitude: " . $row['Pos_Longitude'] . "&";

	// 							$y++;
	// 							$z++;
	// 						}
	// 					}
	// 				}
	// 			}

	// 		}
	// 	}
	// }else{
		$sql="SELECT  * FROM $tableTPositions WHERE (Pos_Id_tracker = '".$q."' AND Pos_DateTime_position ".$select." '".$pUTC."') ORDER BY (Pos_DateTime_position) ".$ordre." LIMIT ".$n." ";
		$result = mysqli_query($connection,$sql);
		while($row = mysqli_fetch_array($result)){

			$utc_date = DateTime::createFromFormat(
					'Y-m-d H:i:s',
					$row['Pos_DateTime_position'],
					new DateTimeZone('UTC')
			);
			$local_date = $utc_date;
			$local_date->setTimeZone(new DateTimeZone($timezone));
			echo "t".$rowCount."g";
			echo "Pos_Latitude:".$row['Pos_Latitude'];
			echo "Pos_DateTime_position:" . $local_date->format($formatLangDateTime);
			echo "Pos_Statut:" . $row['Pos_Statut'];
			echo "Pos_Vitesse:" . $row['Pos_Vitesse'];
			echo "Pos_Direction:" . $row['Pos_Direction'];
			echo "Pos_Odometre:" . $row['Pos_Odometre'];
			//echo "Pos_Adresse:" . $row['Pos_Adresse'];
			echo "Pos_Adresse:" . str_replace("&", "et", $row['Pos_Adresse']);
			echo "Pos_Longitude: ".$row['Pos_Longitude']. "&";

		}
	//}



	mysqli_free_result($result);
	mysqli_close($connection);
?> 