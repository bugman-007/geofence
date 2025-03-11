<?php

/*
* Recupere les infos twarnings pour l'afficher avec javascript
*/



session_start();
$_SESSION['CREATED'] = time();
?>

<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 10/02/2015
 * Time: 12:20
 */
    include '../dbgpw.php';
    include '../dbconnect2.php';
    $idTracker=$_GET["idTracker"];
    $zone=$_GET["zone"];
    $Type_Geometrie=$_GET["Type_Geometrie"];

    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;

    $connection=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
    mysqli_set_charset($connection, "utf8");
    $sql="SELECT * FROM twarnings2  WHERE Id_tracker = '".$idTracker."' AND Numero_Zone = '".$zone."' AND Type_Geometrie = '".$Type_Geometrie."' ";
    $result = mysqli_query($connection,$sql);


    while($row = mysqli_fetch_array($result)){
        echo "Dest_Method:".$row['Dest_Method'];
        echo "Warning_Type:".$row['Warning_Type'];
        echo "Msg_app:". $row['Msg_app'];
        echo "Msg_disp:".$row['Msg_disp']. "&";
    }
    mysqli_free_result($result);
    mysqli_close($connection);
?>