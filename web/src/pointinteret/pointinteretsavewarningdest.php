<?php
/*
   * Ajouter les infos du POI dans twarnings2_dest
   */
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 18/02/2015
 * Time: 17:06
 */

    include '../dbgpw.php';
    include '../dbconnect2.php';

    session_start();
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

    $numero1=$_GET["numero1"];
    $numero2=$_GET["numero2"];
    $numero3=$_GET["numero3"];
    $numero4=$_GET["numero4"];
    $dest01;
    $dest02;
    $dest03;
    $dest04;
    if($numero1[0] == " ") $numero1[0] = "+";
    if($numero2[0] == " ") $numero2[0] = "+";
    if($numero3[0] == " ") $numero3[0] = "+";
    if($numero4[0] == " ") $numero4[0] = "+";

    $connection2=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
    $sql2="SELECT dest01,dest02,dest03,dest04 FROM twarnings2_dest  WHERE (Id_tracker = '".$idTracker."' AND TypeMSG = '2')";
    $result2 = mysqli_query($connection2,$sql2);
    $typeExist = mysqli_num_rows($result2);
    while($row2 = mysqli_fetch_array($result2)){
        $dest01 = $row2['dest01'];
        $dest02 = $row2['dest02'];
        $dest03 = $row2['dest03'];
        $dest04 = $row2['dest04'];
    }
    mysqli_free_result($result2);
    mysqli_close($connection2);

    if($dest01 == $numero1) ; else echo "N°1 ";
    if($dest02 == $numero2) ; else echo "N°2 ";
    if($dest03 == $numero3) ; else echo "N°3 ";
    if($dest04 == $numero4) ; else echo "N°4 ";


    if($typeExist == "1"){
        $connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
        $sql="UPDATE twarnings2_dest  SET dest01 = '".$numero1."', dest02 = '".$numero2."',  dest03 = '".$numero3."',  dest04 = '".$numero4."' WHERE (Id_tracker = '".$idTracker."' AND TypeMSG = '2') ";
        mysqli_query($connection,$sql);
        mysqli_close($connection);
    }else{
        $connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
        $sql="INSERT INTO twarnings2_dest (Id_tracker,Id_Client,TypeMSG,dest01,dest02,dest03,dest04) VALUES ('".$idTracker."','".$idClient."','2','".$numero1."','".$numero2."','".$numero3."','".$numero4."')";
        mysqli_query($connection,$sql);
        mysqli_close($connection);
    }

?>