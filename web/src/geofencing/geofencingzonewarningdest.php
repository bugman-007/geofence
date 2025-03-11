<?php

/*
* Recupere les infos twarnings2_dest pour l'afficher avec javascript
*/


session_start();
$_SESSION['CREATED'] = time();
?>

<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 10/02/2015
 * Time: 14:13
 */

    include '../dbgpw.php';
    include '../dbconnect2.php';
    $idTracker=$_GET["idTracker"];

    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;

    $connection=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
    mysqli_set_charset($connection, "utf8");
    $sql="SELECT dest01,dest02,dest03,dest04 FROM twarnings2_dest  WHERE (Id_tracker = '".$idTracker."' AND TypeMSG = '2')";
    $result = mysqli_query($connection,$sql);

	if($row = mysqli_fetch_array($result)){
        echo "dest01:" . $row['dest01'];
        echo "dest02:" . $row['dest02'];
        echo "dest03:" . $row['dest03'];
        echo "dest04:" . $row['dest04']. "&";
    }
	else
		echo "dest01:dest02:dest03:dest04:&";
	
    mysqli_free_result($result);
    mysqli_close($connection);
?>