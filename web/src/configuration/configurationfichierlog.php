<?php
session_start();
$_SESSION['CREATED'] = time();
?><?php
/*
* Affiche fichier log
*
*/

	include '../dbconnect2.php';
	
	$idTracker=$_GET["idTracker"];
	$numeroAppel=$_GET["numeroAppel"];
	if($numeroAppel[1] == "3" && $numeroAppel[2] == "3") $numeroAppel[0] = "+";
	if($numeroAppel[1] == "4" && $numeroAppel[2] == "4") $numeroAppel[0] = "+";
	if($numeroAppel[1] == "4" && $numeroAppel[2] == "6") $numeroAppel[0] = "+";
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];

	/************ Select tmessages *************/
	$connection=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
	if (!$connection) {
	  die('Not connected : ' . mysqli_connect_error());
	}
	mysqli_set_charset($connection, "utf8");
	$sql="SELECT * FROM tmessages WHERE (Dest = '$idTracker' || Dest = '$numeroAppel') ORDER BY Date DESC";
	
	$result = mysqli_query($connection,$sql);
	$rowCount = mysqli_num_rows($result);
	
	while($row = mysqli_fetch_array($result)){
		echo "t".$rowCount."g";
		echo "Sujet:" . $row['Sujet'];
		echo "Date:" . $row['Date'];
		echo "DateEnvoi:" . $row['DateEnvoi'] ."&";	
	}  
	
	mysqli_close($connection);
	
?>