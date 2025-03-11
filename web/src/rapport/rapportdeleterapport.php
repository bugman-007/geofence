<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 03/03/2015
 * Time: 12:35
 */


include '../dbgpw.php';
include '../dbconnect2.php';


$idTracker=$_GET["idTracker"];
$type=$_GET["type"];
$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
$ipDatabaseGpw=$_GET["ipDatabaseGpw"];

$database = $nomDatabaseGpw;
$server = $ipDatabaseGpw;
    $connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
    $sql="DELETE FROM trapport WHERE Id_tracker = '".$idTracker."' AND Freq_envoi =  '".$type."'";
    $result = mysqli_query($connection,$sql);
    if (!$result) {
        die(mysqli_error());
    }
    mysqli_close($connection);
?>