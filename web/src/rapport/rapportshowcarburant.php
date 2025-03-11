<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 25/02/2015
 * Time: 10:30
 */

session_start();
$_SESSION['CREATED'] = time();

include '../dbgpw.php';
include '../dbconnect2.php';
$idTracker=$_GET["idTracker"];
$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
$database = $nomDatabaseGpw;
$server = $ipDatabaseGpw;

$connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
$sql="SELECT TypeCarburant,LitrePar100Km  FROM trapport  WHERE (Id_tracker = '$idTracker' AND Format_rapport = '0' AND Freq_envoi = '-1' AND Fuseau_DecalMin = '-60')";
$result = mysqli_query($connection,$sql);

while($row = mysqli_fetch_array($result)){
    echo "TypeCarburant:" . $row['TypeCarburant'];
    echo "LitrePar100Km:" . $row['LitrePar100Km'];
//    echo "CO2ParL:" . $row['CO2ParL'];
}
mysqli_free_result($result);
mysqli_close($connection);

?>