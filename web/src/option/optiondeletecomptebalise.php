<?php

/*
* Supprimer les balises d'un compte
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
    $login = $_GET["login"];


    $connection3 = mysqli_connect($server, $db_user, $db_pass, $database);
    $sql3 = "DELETE FROM gpwuser_gpw WHERE Login = '$login' AND Id_GPW != '0' ";
    mysqli_query($connection3, $sql3);
    mysqli_close($connection3);



?>