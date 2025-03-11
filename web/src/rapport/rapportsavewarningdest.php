<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN CHRISTOPHE
 * Date: 03/03/2015
 * Time: 14:47
 */

session_start();
$_SESSION['CREATED'] = time();

    include '../dbgpw.php';
    include '../dbconnect2.php';

    /************* Recuperer l'Id_Client de l'utilisateur *******************/
    $connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
    $queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Client FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' AND Id_GPW != 0)");
    $assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
    $idClient = abs($assocGpwUser['Id_Client']);
    mysqli_free_result($queryGpwUser);
    mysqli_close($connectGpwUser);


    $idTracker=$_GET["idTracker"];


    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;

    $mail1=$_GET["mail1"];
    $mail2=$_GET["mail2"];
    $mail3=$_GET["mail3"];
    $mail4=$_GET["mail4"];

    $dest01;
    $dest02;
    $dest03;
    $dest04;

    $connection2=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
    $sql2="SELECT dest01,dest02,dest03,dest04 FROM twarnings2_dest  WHERE (Id_tracker = '".$idTracker."' AND TypeMSG = '3')";
    $result2 = mysqli_query($connection2,$sql2);
    $typeExist = mysqli_num_rows($result2);
    while($row2 = mysqli_fetch_array($result2)){
        $dest01 = $row2['dest01'];
        $dest02 = $row2['dest02'];
        $dest03 = $row2['dest03'];
        $dest04 = $row2['dest04'];
    }
    mysqli_close($connection2);

    if($dest01 == $mail1) ; else echo "N°1 ";
    if($dest02 == $mail2) ; else echo "N°2 ";
    if($dest03 == $mail3) ; else echo "N°3 ";
    if($dest04 == $mail4) ; else echo "N°4 ";

//    $connection=mysql_connect($server, $db_user_2, $db_pass_2);
//    $db_selected = mysql_select_db($database, $connection);
//    $sql="UPDATE twarnings2_dest  SET dest01 = '".$mail1."', dest02 = '".$mail2."',  dest03 = '".$mail3."',  dest04 = '".$mail4."' WHERE (Id_tracker = '".$idTracker."' AND TypeMSG = '3') ";
//    $result=mysql_query($sql);
//    mysql_close($connection);

    if($typeExist == "1"){
        $connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
        $sql="UPDATE twarnings2_dest  SET dest01 = '".$mail1."', dest02 = '".$mail2."',  dest03 = '".$mail3."',  dest04 = '".$mail4."' WHERE (Id_tracker = '".$idTracker."' AND TypeMSG = '3') ";
        mysqli_query($connection,$sql);
        mysqli_close($connection);
    }else{
        $connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
        $sql="INSERT INTO twarnings2_dest (Id_tracker,Id_Client,TypeMSG,dest01,dest02,dest03,dest04) VALUES ('".$idTracker."','".$idClient."','3','".$mail1."','".$mail2."','".$mail3."','".$mail4."')";
        mysqli_query($connection,$sql);
        mysqli_close($connection);
    }

?>