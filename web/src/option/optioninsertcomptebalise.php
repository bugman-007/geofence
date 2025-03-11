<?php

/*
* Ajouter les balises à un compte
*/

    /**
     * Created by PhpStorm.
     * User: NGUYEN Christophe
     * Date: 05/06/2015
     * Time: 10:06
     */

    include '../dbgpw.php';
    include '../dbconnect2.php';
    //include('../ChromePhp.php');
    

    session_start();
    $idClient = $_GET["idClient"];
    $nomBase = $_GET["nomBase"];
    $compteLogin = $_GET["compteLogin"];
    $idBalise = $_GET["idBalise"];
    $nomBalise = $_GET["nomBalise"];
    $checkedAdmin = $_GET["checkedAdmin"];

    $connectGpwBD = mysqli_connect($server, $db_user, $db_pass,$database);
    $queryGpwBD = mysqli_query($connectGpwBD,"SELECT Id_Base FROM gpwbd WHERE NomBase = '".$nomBase."' ");
    $assocGpwBD = mysqli_fetch_assoc($queryGpwBD);
    $idBase = $assocGpwBD['Id_Base'];
    mysqli_free_result($queryGpwBD);
    mysqli_close($connectGpwBD);


    $connection = mysqli_connect($server, $db_user, $db_pass, $database);
    mysqli_set_charset($connection, "utf8");
    $sql = "INSERT INTO gpwuser_gpw (Login, Id_GPW, NomGPW, Id_Client, Id_Base, Superviseur) VALUES('".$compteLogin."','".$idBalise."','".$nomBalise."','".$idClient."','".$idBase."','".$checkedAdmin."')";
    mysqli_query($connection, $sql);
    $sql = "UPDATE gpwuser_gpw SET Superviseur = '".$checkedAdmin."' WHERE Login = '".$compteLogin."' AND Id_GPW = '0'";
    //Chromephp::log($sql);
    mysqli_query($connection, $sql);
    mysqli_close($connection);



?>