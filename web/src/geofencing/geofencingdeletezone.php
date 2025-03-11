<?php

/*
* Effacer une zone geofencing
*/

session_start();
$_SESSION['CREATED'] = time();
?>

<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 16/02/2015
 * Time: 10:35
 */

    include '../dbgpw.php';
    include '../dbconnect2.php';
    $idTracker=$_GET["idTracker"];
    $zone = $_GET["zone"];
    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;

    $connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
    $sql="DELETE FROM tgeofencings WHERE (Id_tracker = '".$idTracker."' AND Numero_Zone = '".$zone."')";
    mysqli_query($connection,$sql) or die('Erreur SQL !'.$sql.'<br />'.mysqli_error_query());

    mysqli_close($connection);
?>