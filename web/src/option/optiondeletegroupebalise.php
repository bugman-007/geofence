<?php

    /*
    * Supprimer les balises d'un groupe
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

    $nomGroupe = $_GET["nomGroupe"];


    $connectGpw = mysqli_connect($server, $db_user, $db_pass,$database);
    $queryGpw = mysqli_query($connectGpw,"SELECT * FROM gpw WHERE NomGPW = '$nomGroupe' ");
    $assocGpw = mysqli_fetch_assoc($queryGpw);
    $idGpw = $assocGpw['Id_GPW'];
    mysqli_free_result($queryGpw);
    mysqli_close($connectGpw);

    $connection2 = mysqli_connect($server, $db_user, $db_pass, $database);
    $sql2 = "DELETE FROM gpwbalise WHERE Id_GPW = '$idGpw' ";
    mysqli_query($connection2, $sql2);
    mysqli_close($connection2);




?>