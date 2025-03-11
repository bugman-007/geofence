<?php

/*
* Ajouter un tmessages
*/

/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 09/03/2015
 * Time: 14:13
 */
    session_start();
    include '../dbgpw.php';
    include '../dbconnect2.php';

    $_SESSION['CREATED'] = time();

    $idTracker=$_GET["idTracker"];

    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $datetime=$_GET["datetime"];
    $modeMessage="4";

    /************* Recuperer idClient *******************/
    $connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
    $queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Client FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' )");
    $assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
    $idClientGpwUser = $assocGpwUser['Id_Client'];
    mysqli_close($connectGpwUser);
    /***********Definition Insert *****************/

    $typeMsg = "4";
    $dest = $idTracker;

    $sujet = $_GET["sujet"];
    $corps = $_GET["corps"];
    include '../function.php';
    $sujet =wd_remove_accents($sujet);
    $corps = wd_remove_accents($corps);

    echo $sujet;
    /************Insert into tmessages *************/
    $connection=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
    mysqli_set_charset($connection, "utf8");
    $sql="INSERT INTO  tmessages (TypeMSG, Dest, Sujet, Corps, Date, DateEnvoi, Client) VALUES ('".$typeMsg."','".$dest."','".$sujet."','".$corps."','".$datetime."','".$datetime."','".$idClientGpwUser."') ";

    $result = mysqli_query($connection,$sql);

    mysqli_close($connection);

?>