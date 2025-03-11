<?php

/*
* Valider le changement d'icone pour une balise
*/

    /**
     * Created by PhpStorm.
     * User: NGUYEN Christophe
     * Date: 29/05/2015
     * Time: 10:10
     */
    include '../dbconnect2.php';

    $idTracker=$_GET["idTracker"];
    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $selectIcone=$_GET["selectIcone"];

    $res = substr ($selectIcone, 27);


    $connection2=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
    $sql2="UPDATE tpositions0  SET Icone = '".$res."' WHERE Pos_Id_tracker = '".$idTracker."'";
    mysqli_query($connection2,$sql2);
    mysqli_close($connection2);
?>
