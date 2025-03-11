<?php

/*
   * Ajouter les infos du POI dans twarnings2
   */

/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 18/02/2015
 * Time: 15:03
 */

    include '../dbgpw.php';
    include '../dbconnect2.php';
    include '../ChromePhp.php';
    $idTracker=$_GET["idTracker"];
    $idPoi=$_GET["idPoi"];
    $messageArrivee=$_GET["messageArrivee"];
    $messageDepart=$_GET["messageDepart"];
    $destMethod=$_GET["destMethod"];
    $warningType=$_GET["warningType"];
    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $warningLap=$_GET["warningLap"];

    session_start();
    $_SESSION['CREATED'] = time();

    include '../function.php';
    $messageArrivee =wd_remove_accents($messageArrivee);
    $messageDepart = wd_remove_accents($messageDepart);

    /************* Recuperer l'Id_Client de l'utilisateur *******************/
    $connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
    $queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Client FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' AND Id_GPW != 0)");
    $assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
    $idClient = abs($assocGpwUser['Id_Client']);
    mysqli_free_result($queryGpwUser);
    mysqli_close($connectGpwUser);

    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;

    $connectionCVerif=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
    $sqlVerif = "SELECT * FROM twarnings2 WHERE Id_tracker = '".$idTracker."' AND Numero_Zone = '".$idPoi."' AND Type_Geometrie = '4' ";
    $resultVerif= mysqli_query($connectionCVerif,$sqlVerif);
    $typeExist = mysqli_num_rows($resultVerif);
    mysqli_free_result($resultVerif);
    mysqli_close($connectionCVerif);

    $connection1=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
    $sql1="SELECT Id_DestSet FROM twarnings2_dest WHERE (Id_tracker = '".$idTracker."' AND TypeMSG = '2')";
    $result1 = mysqli_query($connection1,$sql1);
    $assoc1 = mysqli_fetch_assoc($result1);
    $Id_DestSet1 = $assoc1['Id_DestSet'];
    mysqli_free_result($result1);
    mysqli_close($connection1);

    $connection2=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
    $sql2="SELECT Id_DestSet FROM twarnings2_dest WHERE (Id_tracker = '".$idTracker."' AND TypeMSG = '3')";
    $result2 = mysqli_query($connection2,$sql2);
    $assoc2 = mysqli_fetch_assoc($result2);
    $Id_DestSet2 = $assoc2['Id_DestSet'];
    mysqli_free_result($result2);
    mysqli_close($connection2);
//ChromePhp::log($typeExist);

    if($typeExist == "1"){
        $connection=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
        mysqli_set_charset($connection, "utf8");
        $sql="UPDATE twarnings2  SET Msg_app = '".$messageArrivee."', Msg_disp = '".$messageDepart."',
         Dest_Method = '".$destMethod."',  Warning_Type = '".$warningType."', Id_DestSet1 = '".$Id_DestSet1."',
          Id_DestSet2 = '".$Id_DestSet2."' , Warning_lap = '".$warningLap."' WHERE Id_tracker = '".$idTracker."' AND Numero_Zone = '".$idPoi."' AND Type_Geometrie = '4' ";
        mysqli_query($connection,$sql);
        mysqli_close($connection);
    }else{
        $connection=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
        mysqli_set_charset($connection, "utf8");
        $sql="INSERT INTO twarnings2 (Id_tracker,Id_Client,Numero_Zone,Warning_Type,Dest_Method,Id_DestSet1,Id_DestSet2,Msg_app,Msg_disp,Type_Geometrie,Warning_lap) VALUES 
		('".$idTracker."','".$idClient."','".$idPoi."','".$warningType."','".$destMethod."','".$Id_DestSet1."','".$Id_DestSet2."','".$messageArrivee."','".$messageDepart."','4','".$warningLap."')";
        mysqli_query($connection,$sql);
        mysqli_close($connection);
   }
?>