<?php

/*
* Effacer un warning geofencing
*/

session_start();
$_SESSION['CREATED'] = time();
?>

<?php
/**
 * Created by PhpStorm.
 * User: NGUYENChristophe
 * Date: 12/02/2015
 * Time: 15:23
 */

    include '../dbgpw.php';
    include '../dbconnect2.php';
    $idTracker=$_GET["idTracker"];
    $zone=$_GET["zone"];
    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;

    $connection=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
    $sql="DELETE FROM twarnings2 WHERE (Id_tracker = '".$idTracker."' AND Numero_Zone = '".$zone."' AND Type_Geometrie = '3')";
    mysqli_query($connection,$sql);
    mysqli_close($connection);
?>