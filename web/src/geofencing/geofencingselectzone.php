<?php

/*
* Afficher la liste des zones
*/

session_start();
$_SESSION['CREATED'] = time();


?>

<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Christophe
 * Date: 09/02/2015
 * Time: 15:04
 */
    include '../dbgpw.php';
    include '../dbconnect2.php';
    $idTracker=$_GET["idTracker"];
    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;

    $connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
    mysqli_set_charset($connection, "utf8");
    $sql="SELECT * FROM twarnings2  WHERE Id_tracker = '".$idTracker."' AND Type_Geometrie = '3' ORDER BY Numero_Zone ASC";
    $result = mysqli_query($connection,$sql);

    echo '<select id="select_geofencing_zone" onChange="selectZone(this.value);">';
    echo '<option value="all">Toutes</option>';
    while($row = mysqli_fetch_array($result)){
        echo '<option value="'.$row['Numero_Zone'].'">'.$row['Numero_Zone'].'</option>';
    }
    echo '</select>';
    echo '<a href="#" data-toggle="modal" data-target="#info_geofencing"> ?</a>';

    mysqli_free_result($result);
    mysqli_close($connection);
?>