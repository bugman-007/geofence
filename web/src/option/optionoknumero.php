<?php
/*
* Mise a jour du numero
*/
	set_time_limit(0);
	session_start();
	$_SESSION['CREATED'] = time();

	$q=$_GET["Id_Tracker"];
	$database=$_GET["nomDatabaseGpw"];
	$server=$_GET["ipDatabaseGpw"];
	//$timezone=$_GET["timezone"];
	
	if(isset($_GET['cdetrstsv']))
	{
		$cdetrans = $_GET["cdetrstsv"];

		include '../dbconnect2.php';

		$connection=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
		$sql = "UPDATE ttrackers SET SrvCmd = '".$cdetrans."' WHERE Id_tracker  = '".$q."'";
		mysqli_query($connection, $sql);
		mysqli_close($connection);

		echo $cdetrans;
	}
	else if(isset($_GET['numeroTelTrackerNew']) && isset($_GET['InternationalFormat']))
	{
		$numeroAppelNew = $_GET["numeroTelTrackerNew"];
		$InternationalFormat = $_GET["InternationalFormat"];
		if($InternationalFormat == "1") $numeroAppelNew[0] = "+";

		include '../dbconnect2.php';

		$connection=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
		$sql = "UPDATE ttrackers SET Tel_tracker = '$numeroAppelNew' WHERE Id_tracker  = '$q'";
		mysqli_query($connection, $sql);
		mysqli_close($connection);

		echo $numeroAppelNew;
	}

?> 