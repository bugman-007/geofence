<?php
/*
* On recupere les synch time et telephone de la balise pour l'etat balise
*/

	set_time_limit(0);
	session_start();
	$_SESSION['CREATED'] = time();

	$q=$_GET["Id_Tracker"];
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
	$timezone=$_GET["timezone"];	

	$database = $nomDatabaseGpw; 
	$server = $ipDatabaseGpw; 
	include '../dbconnect2.php';


	$connection=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
	mysqli_set_charset($connection, "utf8");
	$sql="SELECT Tel_tracker,SynchTime1,SrvCmd FROM ttrackers WHERE Id_tracker = '".$q."' ";
	$result = mysqli_query($connection,$sql);

	while($row = mysqli_fetch_array($result)){
		$utc_date = DateTime::createFromFormat(
			'Y-m-d H:i:s', 
			$row['SynchTime1'], 
			new DateTimeZone('UTC')
		);
		
		
		if($row['SynchTime1'])
		{
//			$local_date = $utc_date;
//			$local_date->setTimeZone(new DateTimeZone($timezone));
//			echo "SynchTime1:" . $local_date->format('Y-m-d H:i:s');
			echo "SynchTime1:" . $row['SynchTime1']. "&";
		}
		else
			echo "SynchTime1:&";
		
		
		if($row['Tel_tracker'])
			echo "Tel_tracker:" . $row['Tel_tracker']. "&";
		else
			echo "Tel_tracker:&";
		
		if($row['SrvCmd'])
			echo "cdetsv:" . $row['SrvCmd']. "&";
		else
			echo "cdetsv:&";
		
	}  

	mysqli_free_result($result);
	mysqli_close($connection);
?> 