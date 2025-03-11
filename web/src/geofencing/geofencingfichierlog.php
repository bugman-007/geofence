<?php

/*
* Recupération des données tmessages pour afficher le fichier log
*/


session_start();
$_SESSION['CREATED'] = time();
?>

<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 12/02/2015
 * Time: 12:55
 */

    include '../dbconnect2.php';

    $idTracker=$_GET["idTracker"];

    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];

    $numeroAppel=$_GET["numeroAppel"];
    if($numeroAppel != "") {
        if ($numeroAppel[1] == "3" && $numeroAppel[2] == "3") $numeroAppel[0] = "+";
    }
    $connection=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
    mysqli_set_charset($connection, "utf8");
    $sql="SELECT * FROM tmessages WHERE (Dest = '$idTracker' OR Dest = '$numeroAppel') ORDER BY Date DESC";

    $result = mysqli_query($connection,$sql);
    $rowCount = mysqli_num_rows($result);

    while($row = mysqli_fetch_array($result)){
        echo "t".$rowCount."g";
        echo "Sujet:" . $row['Sujet'];
        echo "Date:" . $row['Date'];
        echo "DateEnvoi:" . $row['DateEnvoi'] ."&";
    }

    mysqli_free_result($result);
    mysqli_close($connection);

?>