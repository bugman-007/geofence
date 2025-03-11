<?php

/*
* Afficher la zone (data ajax pour javascript)
*/


session_start();
$_SESSION['CREATED'] = time();
?>

<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 11/02/2015
 * Time: 09:54
 */

    include '../dbgpw.php';
    include '../dbconnect2.php';
    $idTracker=$_GET["idTracker"];
    $zone=$_GET["zone"];

    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;

    $connection=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
    mysqli_set_charset($connection, "utf8");
    $sql="SELECT * FROM tgeofencings  WHERE Id_tracker = '".$idTracker."' AND Numero_Zone = '".$zone."' ORDER BY Id";
    $result = mysqli_query($connection,$sql);

    $rowCount = mysqli_num_rows($result);
    while($row = mysqli_fetch_array($result)){
        echo "t".$rowCount."g";

        echo "Pos_Latitude:".$row['Pos_Latitude'];
        echo "Pos_Longitude:".$row['Pos_Longitude']. "&";
    }

    mysqli_free_result($result);
    mysqli_close($connection);
?>