<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 17/02/2015
 * Time: 16:18
 */

/*
* On recupere les type geometrie et les numeros de zone de la balise pour l'etat balise
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
$sql="SELECT * FROM twarnings2 WHERE Id_tracker = '".$idTracker."' AND Warning_Type = '1' ORDER BY `Type_Geometrie`, `Numero_Zone`";

$result = mysqli_query($connection,$sql);
$rowCount = mysqli_num_rows($result);

while($row = mysqli_fetch_array($result)){
    echo "t".$rowCount."g";
    echo "Type_Geometrie:".$row['Type_Geometrie'];
    echo "Numero_Zone:".$row['Numero_Zone']."&";
}
mysqli_free_result($result);
mysqli_close($connection);

?>