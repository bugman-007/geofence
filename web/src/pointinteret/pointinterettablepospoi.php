<style type="text/css">
.sortable td:hover {
    cursor: pointer;
}
.sortable th, td{
	text-align: center;
}
</style>
<?php

/*
 * Afficher la table des POI simple
 */

session_start();
include '../dbgpw.php';
$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
/************* Recuperer l'Id_Client de l'utilisateur *******************/
	$connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
	$queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Client,Id_Base FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' AND Id_GPW != 0)");
	$assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
	$idBase = $assocGpwUser['Id_Base'];
	$idClient = abs($assocGpwUser['Id_Client']);
	mysqli_free_result($queryGpwUser);
	mysqli_close($connectGpwUser);
?>
<!--	<table id="idTablePositionPOI" class="sortable table table-bordered">-->
<!--		<tr><th width="150px">Nom du Point Interet </th><th width="250px">Adresse Postale</th><th width="200px">Description</th><th width="100px">Latitude</th><th width="100px">Longitude</th><th width="100px">Rayon</th></tr>-->
<?php
	
	include '../dbconnect2.php';

	$link = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
	mysqli_set_charset($link, "utf8");
	$match = "SELECT * FROM tpoi WHERE IdClient='$idClient' ";
	$result = mysqli_query($link,$match);
	while($row = mysqli_fetch_array($result)){
		echo "<tr onclick=\"afficheInfobullTablePOI(this,'".$row['Id']."');\"><td>".$row['Id']."</td><td  >".$row['Name']."</td><td>".$row['adresse']."</td><td>".$row['description']."</td><td style='display:none'>".$row['latitude']."</td><td style='display:none'>".$row['longitude']."</td><td>".$row['Rayon']."</td></tr>";
	}
	mysqli_free_result($result);
	mysqli_close($link);
?>

<!--	</table>-->