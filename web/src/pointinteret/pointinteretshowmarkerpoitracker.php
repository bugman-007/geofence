<?php

/*
 * Recupérer les infos du POI
 */

/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 16/02/2015
 * Time: 17:13
 */
    include '../dbgpw.php';
    include '../dbconnect2.php';
    $idTracker=$_GET["idTracker"];
    $numeroZonePoi=$_GET["numeroZonePoi"];

    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;

    $link = mysqli_connect($server, $db_user_2, $db_pass_2,$database);
    mysqli_set_charset($link, "utf8");
    $match = "SELECT * from tpoi WHERE Id='".$numeroZonePoi."' ";
    $result = mysqli_query($link,$match);

    $rowCount = mysqli_num_rows($result);
    while($row = mysqli_fetch_array($result)){
        echo "t".$rowCount."g";
        echo "idPoi:".$row['Id'];
        echo "Latitude:" . $row['latitude'];
        echo "Longitude:" . $row['longitude'] ;
        echo "Name:" . $row['Name'] ;
        echo "Adresse:" . $row['adresse'] ;
        echo "Rayon:" . $row['Rayon'];
    }
    mysqli_free_result($result);
    mysqli_close($link);

?>