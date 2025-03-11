<?php
	/************* R�cup�rer les infos de la new POI *****************/
	include '../function.php';
	include '../dbconnect2.php';
	$address = addslashes($_GET['address']);
	$lat = $_GET['lat'];
	$lng = $_GET['lng'];
	$dateTime = $_GET["datetime"];
	$idTracker = $_GET['idTracker'];
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];

	/************* Update POI *******************/
	$connectTpoi =  mysqli_connect($ipDatabaseGpw,$db_user_2,$db_pass_2,$nomDatabaseGpw);
	mysqli_set_charset($connectTpoi, "utf8");
	$tableTPositions = "tpositions";
	/*$dateNow = strtotime(date('Y-m-d H:i:s'));
	$dateChosen = strtotime($dateTime);
	$secs = $dateNow - $dateChosen;// == <seconds between the two times>
	$monthDifference = round($secs / (60*60*24*7*4)) ;
	$dayDifference = round($secs / (60*60*24)) ;
	$year = $dateTime[0]."".$dateTime[1]."".$dateTime[2]."".$dateTime[3];
	$month = $dateTime[5]."".$dateTime[6];*/

	/*if($dayDifference >= 90){

		if(intval($year) <= 2014){
			$tableTPositions = "tpositions201412";
		}else{
			if( (intval($month) <= 3))	$tableTPositions = "tpositions".($year)."03";
			if( (intval($month) > 3) && (intval($month) <= 6))	$tableTPositions = "tpositions".($year)."06";
			if( (intval($month) > 6) && (intval($month) <= 9))	$tableTPositions = "tpositions".($year)."09";
			if( (intval($month) > 9) && (intval($month) <= 12)) $tableTPositions = "tpositions".($year)."12";
		}


	}else{*/
		$tableTPositions = "tpositions";
	//}
	
	$queryInsertTpoi = mysqli_query($connectTpoi,"UPDATE $tableTPositions SET Pos_Adresse = '$address' WHERE Pos_Latitude = '$lat' AND Pos_Longitude = '$lng' AND Pos_Id_tracker = '$idTracker' AND Pos_DateTime_position = '$dateTime' ");
	echo $dateTime;
	
	mysqli_close($connectTpoi);
?>
