<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 25/02/2015
 * Time: 10:30
 */

session_start();
$_SESSION['CREATED'] = time();

    include '../dbgpw.php';
    include '../dbconnect2.php';
    $idTracker=$_GET["idTracker"];
    $typeRapport=$_GET["typeRapport"];

    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;

    $connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
    mysqli_set_charset($connection, "utf8");
    $sql="SELECT * FROM trapport WHERE (Id_tracker = '".$idTracker."' AND Freq_envoi = '".$typeRapport."')";
    $result = mysqli_query($connection,$sql);

    while($row = mysqli_fetch_array($result)){
        echo "Format_rapport:" . $row['Format_rapport'];
        echo "RapportEtape:" . $row['RapportEtape'];
        echo "HeureJourD:" . $row['HeureJourD'];
        echo "HeureJourF:" . $row['HeureJourF'];
        echo "DateTimeD_UTC:" . $row['DateTimeD_UTC'];
        echo "DateTimeF_UTC:" . $row['DateTimeF_UTC'];
        echo "DateTime_envoiUTC:" . $row['DateTime_envoiUTC'];
        echo "JourEnvoi:" . $row['JourEnvoi'];
        echo "Sujet:" . $row['Sujet'];
        echo "Message:" . $row['Message'];
        echo "Dest_Method:" . $row['Dest_Method'];
    }
    mysqli_close($connection);

?>