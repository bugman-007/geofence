<?php
	include '../function.php';
	include '../dbconnect2.php';
	//include('../ChromePhp.php');
	header( 'content-type: text/html; charset=utf-8' );
	
	$q=$_GET["idTracker"];
	$d=$_GET["debutRapport"];
	$f=$_GET["finRapport"];
	$timezone=$_GET["timezone"];
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];

	$dUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($d)),$timezone);
	$fUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($f)),$timezone);

	$connection=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
	mysqli_set_charset($connection, "utf8");
	include('../dbtpositions.php');

	$sql = "";
	$arrayTpositions = getAllPeriodTpositions($d,$f);
	$i=0;
	$iDureeStop = 0;

	$condition = "";
	$conditionOk = "";
	$conditionFirst = "";
	$firstStopData = "";
	
	$data = array();

	if (sizeof($arrayTpositions) > 1 )
	{
		for ($i = 0; $i < sizeof($arrayTpositions); $i++)
		{
			$sql="SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
							FROM ".$arrayTpositions[$i]." WHERE (Pos_DateTime_position BETWEEN '".$dUTC."' AND '".$fUTC."' ) AND (Pos_Id_tracker = '".$q."' )
							ORDER BY Pos_DateTime_position ; ";
		}
		
		if (mysqli_multi_query($connection, $sql))
		{
			do
			{
				if ($result = mysqli_store_result($connection))
				{
					$data += queryFetchArray($result);
					mysqli_free_result($result);
				}
			}
			while (mysqli_more_results($connection) && mysqli_next_result($connection));
		}
	}
	else
	{
		$sql="SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
							FROM $arrayTpositions[0] WHERE (Pos_DateTime_position BETWEEN '".$dUTC."' AND '".$fUTC."' ) AND (Pos_Id_tracker = '".$q."' )
							ORDER BY Pos_DateTime_position ; ";
		$result = mysqli_query($connection,$sql);

		if( $result !== false )
			$data = queryFetchArray($result);
		
		mysqli_free_result($result);
	}
	
	print_r(json_encode($data));
	
	mysqli_close($connection);
	
	
	
	function queryFetchArray($result)
	{
		$data1 = array();
		$MemoStatutSTOP = 0;
		
		while($row = mysqli_fetch_array($result))
		{
			ini_set('display_errors','off');
			
			if($row['Pos_Statut'] & 0x04)
				$StatutSTOP = 0;
			else
				$StatutSTOP = 1;
			
			if( ($MemoStatutSTOP == 0) && ($StatutSTOP == 1) )
			{
				//echo $row['Pos_Adresse'] . "<br />";
				
				array_push($data1,
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
			else if($StatutSTOP == 0)
			{
				//echo $row['Pos_Adresse'] . "<br />";
				
				array_push($data1,
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
		}
		
		return $data1;
	}

/*
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
*/
?> 