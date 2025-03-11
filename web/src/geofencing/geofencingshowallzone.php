<?php
session_start();
$_SESSION['CREATED'] = time();

/*
* Afficher toutes les zones (data ajax pour javascript)
*/

?>

<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 11/02/2015
 * Time: 12:09
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
    $sql="SELECT * FROM twarnings2  WHERE Id_tracker = '".$idTracker."' AND Numero_Zone <= 10 AND Type_Geometrie = 3 ORDER BY Numero_Zone ASC";
    $result = mysqli_query($connection, $sql);

    $rowCount = mysqli_num_rows($result);
    while($row = mysqli_fetch_array($result)){
        echo "t".$rowCount."g";
        echo "Numero_Zone: " . $row['Numero_Zone']. "&";
    }

    mysqli_free_result($result);
    mysqli_close($connection);
    ?>