<?php
	include '../function.php';
	include '../dbconnect2.php';

	header( 'content-type: text/html; charset=utf-8' );
	
	$q=$_GET["idTracker"];
	$d=$_GET["debutRapport"];
	$f=$_GET["finRapport"];
	$timezone=$_GET["timezone"];
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];

	$dUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($d)),$timezone);
	$fUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($f)),$timezone);;



	$connection=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
	mysqli_set_charset($connection, "utf8");
	include('../dbtpositions.php');

	$sql = "";
	$arrayTpositions = getAllPeriodTpositions($d,$f);
	$i=0;
	$iDureeStop = 0;
	$lengthEtape = 0;

	$condition = "";
	$conditionOk = "";
	$conditionFirst = "";
	$firstStopData = "";
	
	$data = array();

	
	if (sizeof($arrayTpositions) > 1 ) {
		if (mysqli_multi_query($connection, $sql)) {
			do {
				if ($result = mysqli_store_result($connection)) {
					$lengthEtape += mysqli_num_rows($result);
				}
			} while (mysqli_more_results($connection) && mysqli_next_result($connection));
		}
		for ($i = 0; $i < sizeof($arrayTpositions); $i++) {
			$sql="SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
							FROM ".$arrayTpositions[$i]." WHERE (Pos_DateTime_position BETWEEN '".$dUTC."' AND '".$fUTC."' ) AND (Pos_Id_tracker = '".$q."' )
							ORDER BY Pos_DateTime_position ; ";
		}
		$cbalise = statutEncodeCouleur($dUTC,$fUTC,$q,$sql,$arrayTpositions,$db_user_2,$db_pass_2);
		if (mysqli_multi_query($connection, $sql)) {
			$i=0;
			do {
				if ($result = mysqli_store_result($connection)) {
					while($row = mysqli_fetch_array($result))
					{
						//ini_set('display_errors','off');
						$printpos = 0;
						
						if($conditionFirst == ""){
							if ( $cbalise[$i] == "stop")
							{
								$printpos = 1;
								$conditionFirst = "ok";
							}
						}
						if ($iDureeStop == 0) {
							if ($cbalise[$i - 1] != "stop" && $cbalise[$i] == "stop" )
							{
								$printpos = 1;
								$iDureeStop++;
							}
						}
						//		if ($iDureeStop != 0) {
						if ($conditionOk == "") {
							if ($cbalise[$i] != "stop" && ($cbalise[$i + 1] != "stop"))
							{
								$printpos = 1;
								
								$condition = "ok";
								$conditionOk = "ok";
							}
						}
						if ($condition == "ok") {
							if ($cbalise[$i] != "stop"/* && ($cbalise[$i + 1] == "stop")*/) 
							{
								$printpos = 1;
								
								$condition = "o";
								$conditionOk = "";
								$iDureeStop = "0";
							}
						}
						if($i == $lengthEtape-1)
						{
							$printpos = 1;
							
							$condition = "o";
							$conditionOk = "";
							$iDureeStop = "0";
						}
						//		}
						
						if($printpos == 1)
						{
							array_push($data,
								array(
									"DateTime_Position" => $row['Pos_DateTime_position'],
									"Latitude" => $row['Pos_Latitude'],
									"Longitude" => $row['Pos_Longitude'],
									"Statut" => $row['Pos_Statut'],
									"Vitesse" => $row['Pos_Vitesse'],
									"Direction" => $row['Pos_Direction'],
									"Odometre" => $row['Pos_Odometre'],
									"Adresse" => $row['Pos_Adresse']
								)
							); 
						}
						
						$i++;
					}
					mysqli_free_result($result);
				}
			} while (mysqli_more_results($connection) && mysqli_next_result($connection));
		}
	}
	else
	{
		$sql="SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
							FROM $arrayTpositions[0] WHERE (Pos_DateTime_position BETWEEN '".$dUTC."' AND '".$fUTC."' ) AND (Pos_Id_tracker = '".$q."' )
							ORDER BY Pos_DateTime_position ; ";
							
		$result = mysqli_query($connection,$sql);
		
		if( $result !== false )
		{
			$lengthEtape = mysqli_num_rows($result);
			$MemoStatutSTOP = 0;
			$printpos = 1;
			$i=0;
			
			while($row = mysqli_fetch_array($result))
			{
				//ini_set('display_errors','off');
				
				if($row['Pos_Statut'] & 0x04)
					$StatutSTOP = 0;
				else
					$StatutSTOP = 1;
				
				if (($MemoStatutSTOP == 0) && ($StatutSTOP == 1))
					$printpos = 1;
				else if (($MemoStatutSTOP == 1) && ($StatutSTOP == 0))
					$printpos = 1;
				
				if($i == $lengthEtape-1)		// Traitement derniere position du rapport
					$printpos = 1;
				
				if($printpos == 1)
				{
					//echo $row['Pos_Adresse'] . "<br />";
					
					array_push($data,
						array(
							"DateTime_Position" => $row['Pos_DateTime_position'],
							"Latitude" => $row['Pos_Latitude'],
							"Longitude" => $row['Pos_Longitude'],
							"Statut" => $row['Pos_Statut'],
							"Vitesse" => $row['Pos_Vitesse'],
							"Direction" => $row['Pos_Direction'],
							"Odometre" => $row['Pos_Odometre'],
							"Adresse" => $row['Pos_Adresse']
						)
					);
				}
				
				$MemoStatutSTOP = $StatutSTOP;
				$printpos = 0;
				$i++;
			}
		}
		mysqli_free_result($result);
	}
	mysqli_close($connection);





	function statutEncodeCouleur($debutRapport,$finRapport,$idBaliseRapport,$sql,$arrayTpositions,$db_user_2,$db_pass_2){
		//ini_set('display_errors','off');

		$i=0;
		$cbalise = array();
		$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
		$ipDatabaseGpw=$_GET["ipDatabaseGpw"];

		$connectStatutEncode = mysqli_connect($ipDatabaseGpw,$db_user_2,$db_pass_2,$nomDatabaseGpw);
		if (sizeof($arrayTpositions) > 1 ) {
			if (mysqli_multi_query($connectStatutEncode, $sql)) {
				do {
					if ($resultStatutEncode = mysqli_store_result($connectStatutEncode)) {
						while($row = mysqli_fetch_array($resultStatutEncode))
						{
							if($row['Pos_Statut'] & 0x04)
								$cbalise[$i] = "";
							else
								$cbalise[$i] = "stop";
							
							$i++;
						}
						mysqli_free_result($resultStatutEncode);
					}
				} while (mysqli_more_results($connectStatutEncode) && mysqli_next_result($connectStatutEncode));
			}
		}


		mysqli_close($connectStatutEncode);
		
		return $cbalise;
	}
	print_r(json_encode($data));
	?>