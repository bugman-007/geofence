<?php


    /*
    * Supprimer un groupe
    */

    /**
     * Created by PhpStorm.
     * User: NGUYEN Christophe
     * Date: 05/06/2015
     * Time: 10:06
     */

    include '../dbgpw.php';
    include '../dbconnect2.php';

    session_start();
    $idClient = $_GET["idClient"];
    $nomBase = $_GET["nomBase"];
    $nomGPW = $_GET["nomGPW"];
    $idGPW = $_GET["idGPW"];


    $connection = mysqli_connect($server, $db_user, $db_pass, $database);
    $sql = "DELETE FROM gpw WHERE Id_GPW = '$idGPW' ";
    mysqli_query($connection, $sql);
    mysqli_close($connection);

    $connection2 = mysqli_connect($server, $db_user, $db_pass, $database);
    $sql2 = "DELETE FROM gpwbalise WHERE Id_GPW = '$idGPW' ";
    mysqli_query($connection2, $sql2);
    mysqli_close($connection2);

    $connection3 = mysqli_connect($server, $db_user, $db_pass, $database);
    $sql3 = "DELETE FROM gpwuser_gpw WHERE Id_GPW = '$idGPW' ";
    mysqli_query($connection3, $sql3);
    mysqli_close($connection3);



?>