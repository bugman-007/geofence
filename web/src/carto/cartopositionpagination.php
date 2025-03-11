
<?php

    /*
   * 	Affiche le tableau pour l'historique de position selon la pagination courante
   * 	Carto:js
   */

	set_time_limit(0);
	include '../function.php';

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

	//PARAMETRE BDD GLOBALE
	$database = $nomDatabaseGpw;  // the name of the database.
	$server = $ipDatabaseGpw;  // server to connect to.
	include '../dbconnect2.php';

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
//	if(intval($n) < 1000)
		$sql="SELECT COUNT(*) AS total  FROM $tableTPositions  WHERE (Pos_Id_tracker = '".$q."' AND Pos_DateTime_position ".$select." '".$pUTC."') ORDER BY  (Pos_DateTime_position) ASC LIMIT 0,".$n." ";
//	else{
//		$sql = "SELECT COUNT(*) AS total
//				FROM (SELECT Pos_Id_tracker, Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse from $tableTPositions
//				UNION  SELECT Pos_Id_tracker, Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse from $tableTPositions2) AS t1
//				WHERE (t1.Pos_Id_tracker = '".$q."' AND t1.Pos_DateTime_position ".$select." '".$pUTC."')
//				ORDER BY (t1.Pos_DateTime_position) $ordre LIMIT  0,".$n." ";
//	}
	$retour_total=mysqli_query($connection,$sql); //Nous récupérons le contenu de la requête dans $retour_total
	$donnees_total=mysqli_fetch_assoc($retour_total); //On range retour sous la forme d'un tableau.

	$total=$donnees_total['total']; //On récupère le total pour le placer dans la variable $total.
	$messagesParPage=1000;

	$nombreDePages=ceil($n/$messagesParPage);

	echo "NombreDePages:".$nombreDePages;

	mysqli_free_result($retour_total);
	mysqli_close($connection);
?>