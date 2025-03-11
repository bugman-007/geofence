<?php

/*
* Effacer un warning poi
*/

/**
 * Created by PhpStorm.
 * User: Emachines1
 * Date: 20/02/2015
 * Time: 09:47
 */

    include '../dbgpw.php';
    include '../dbconnect2.php';

    /************* Récupérer les infos de la new POI *****************/
    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $idPoi = $_GET['idPoi'];

    /************* DELETE Warning *******************/
    $connectWarning =  mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
    $querydDeleteWarning = mysqli_query($connectWarning,"DELETE FROM twarnings2 WHERE Numero_Zone = '".$idPoi."' AND Type_Geometrie = '4' ");
    mysqli_close($connectWarning);
?>
