<?php

/*
 * Recupérer les info twarnings2
 */


/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 17/02/2015
 * Time: 16:18
 */

include '../dbgpw.php';
include '../dbconnect2.php';
$idTracker=$_GET["idTracker"];
$idPoi=$_GET["idPoi"];

$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
$ipDatabaseGpw=$_GET["ipDatabaseGpw"];
$database = $nomDatabaseGpw;
$server = $ipDatabaseGpw;

$connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
$sql="SELECT * FROM twarnings2  WHERE Id_tracker = '".$idTracker."' AND Numero_Zone = '".$idPoi."' AND Type_Geometrie = '4' ";
$result = mysqli_query($connection,$sql);

while($row = mysqli_fetch_array($result)){
    echo "Dest_Method:".$row['Dest_Method'];
    echo "Warning_Type:".$row['Warning_Type'];
    echo "Msg_app:". $row['Msg_app'];
    echo "Msg_disp:".$row['Msg_disp'];
}
mysqli_free_result($result);
mysqli_close($connection);

?>