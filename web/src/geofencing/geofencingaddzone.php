<?php

/*
* Ajouter une zone geofencing
*/


session_start();
$_SESSION['CREATED'] = time();


?>

<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 16/02/2015
 * Time: 10:06
 */

    include '../dbgpw.php';
    include '../dbconnect2.php';
    /*************** Recuperer la session avec le login de l'utilisateur ************/
//    $_SESSION['username'];
//    /************* Recuperer l'Id_Base 'utilisateur *******************/
//    $selectGpwUser = mysql_select_db($database, $connectGpwUser);
//    $queryGpwUser = mysql_query("SELECT Id_Base FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' ");
//    $assocGpwUser = mysql_fetch_assoc($queryGpwUser);
//    $idBase = $assocGpwUser['Id_Base'];
//    mysql_close($connectGpwUser);

    /************* AddZone *******************/
    $idTracker=$_GET["idTracker"];
    $zone = $_GET["zone"];
    $latPolygone=$_GET["latPolygone"];
    $lngPolygone=$_GET["lngPolygone"];
    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $idDatabaseGpw=$_GET["idDatabaseGpw"];
    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;

    $connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
    mysqli_set_charset($connection, "utf8");
    $sql="INSERT INTO tgeofencings(Id_tracker, Id_Base, Type_Geometrie, Numero_Zone, Pos_Latitude, Pos_Longitude) VALUES('".$idTracker."', '".$idDatabaseGpw."', '3', '".$zone."', '".$latPolygone."', '".$lngPolygone."')";
    mysqli_query($connection,$sql) or die('Erreur SQL !'.$sql.'<br />'.mysqli_error_query());
    mysqli_close($connection);

?>