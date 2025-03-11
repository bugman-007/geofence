<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 03/03/2015
 * Time: 12:35
 */


include '../dbgpw.php';
include '../dbconnect2.php';

ini_set('display_errors','off');
    $idTracker=$_GET["idTracker"];
    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];

    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;
    $connection=mysql_connect($server, $db_user_2, $db_pass_2);
    if (!$connection) {
        die('Not connected : ' . mysql_error());
    }
    $db_selected = mysql_select_db($database, $connection);
    if (!$db_selected) {
        die ('Can\'t use db : ' . mysql_error());
    }
    $sql="DELETE FROM trapport WHERE Id_tracker = '".$idTracker."' AND Freq_envoi = '-1'";
    $result = mysql_query($sql);
    if (!$result) {
        die(mysql_error());
    }
    mysql_close($connection);
?>