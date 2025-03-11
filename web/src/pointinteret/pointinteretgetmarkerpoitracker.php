<?php

/*
 * Recuperer le numero de zone dans twarnings2 (pour javascript)
 */

/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 16/02/2015
 * Time: 16:12
 */
//
    include '../dbgpw.php';
    include '../dbconnect2.php';
    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $idTracker = $_GET["idTracker"];
    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;

//    $idPOI = array();
    $connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
    $sql="SELECT Numero_Zone FROM twarnings2  WHERE Id_tracker = '".$idTracker."' AND Type_Geometrie = '4' ";
    $result = mysqli_query($connection,$sql);
    $rowCount = mysqli_num_rows($result);

//    $i=0;
    while($row = mysqli_fetch_array($result)){
        echo "t".$rowCount."g";
        echo "Numero_Zone:" . $row['Numero_Zone'] . "&";
//        $idPOI[$i] = $row['Numero_Zone'];
//        $i++;
    }
    mysqli_free_result($result);
    mysqli_close($connection);

?>