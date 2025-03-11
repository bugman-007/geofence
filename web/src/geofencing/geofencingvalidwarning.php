<?php

/*
* Ajouter un twarnings2
*/


/**
 * Created by PhpStorm.
 * User: NGUYENChristophe
 * Date: 12/02/2015
 * Time: 15:23
 */

    include '../dbgpw.php';
    include '../dbconnect2.php';
    include '../ChromePhp.php';

    $idTracker=$_GET["idTracker"];
    $zone=$_GET["zone"];
    $messageSortie=$_GET["messageSortie"];
    $messageEntree=$_GET["messageEntree"];
    $destMethod=$_GET["destMethod"];
    $warningType=$_GET["warningType"];
    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $warningLap=$_GET["warningLap"];
	$Type_Geometrie=$_GET["Type_Geometrie"];

session_start();
$_SESSION['CREATED'] = time();

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
    mysqli_set_charset($connectionCVerif, "utf8");
    $sqlVerif = "SELECT * FROM twarnings2 WHERE Id_tracker = '".$idTracker."' AND Numero_Zone = '".$zone."' AND Type_Geometrie = '".$Type_Geometrie."' ";
    //ChromePhp::log($sqlVerif);
    $resultVerif= mysqli_query($connectionCVerif,$sqlVerif);
    $typeExist = mysqli_num_rows($resultVerif);
    mysqli_free_result($resultVerif);
    mysqli_close($connectionCVerif);

   
    $connection1=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
    mysqli_set_charset($connection1, "utf8");
    $sql1="SELECT Id_DestSet FROM twarnings2_dest WHERE (Id_tracker = '".$idTracker."' AND TypeMSG = '2')";
    $result1 = mysqli_query($connection1,$sql1);
    $assoc1 = mysqli_fetch_assoc($result1);
    $Id_DestSet1 = $assoc1['Id_DestSet'];
    mysqli_free_result($result1);
    mysqli_close($connection1);

    $Id_DestSet2 = 0;
    $connection2=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
    mysqli_set_charset($connection2, "utf8");
    $sql2="SELECT Id_DestSet FROM twarnings2_dest WHERE (Id_tracker = '".$idTracker."' AND TypeMSG = '3')";
    //ChromePhp::log($sql2);
    $result2 = mysqli_query($connection2,$sql2);
    $assoc2 = mysqli_fetch_assoc($result2);
    $Id_DestSet2 = $assoc2['Id_DestSet'];
    mysqli_free_result($result2);
    mysqli_close($connection2);
    //ChromePhp::log("Update twarnings2");
    if ($Id_DestSet2 == NULL){
        $Id_DestSet2 = 0;
    }
    if ($Id_DestSet1 == NULL){
        $Id_DestSet1 = 0;
    }
 
    //ChromePhp::log($Id_DestSet1,$Id_DestSet2,$typeExist);
    if($typeExist == "1"){

        $connection=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
        mysqli_set_charset($connection, "utf8");
        $sql="UPDATE twarnings2 SET
			Id_Client = '".$idClient."',
			Warning_Type = '".$warningType."',
			Dest_Method = '".$destMethod."',
			Id_DestSet1 = '".$Id_DestSet1."',
			Id_DestSet2 = '".$Id_DestSet2."',
            Msg_app = '".$messageEntree."',
            Msg_disp = '".$messageSortie."',
			Warning_lap = '".$warningLap."'
			WHERE Id_tracker = '".$idTracker."' AND Numero_Zone = '".$zone."' AND Type_Geometrie = '".$Type_Geometrie."'";
        //ChromePhp::log($sql);
        mysqli_query($connection,$sql);
        mysqli_close($connection);
		echo "succes";
    }else{
        //ChromePhp::log($server, $db_user_2, $db_pass_2, $database);
        $connection=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
        mysqli_set_charset($connection, "utf8");
        $sql="INSERT INTO twarnings2 (Id_tracker,Id_Client,Numero_Zone,Warning_Type,Dest_Method,Id_DestSet1,Id_DestSet2,Msg_app,Msg_disp,Type_Geometrie,Warning_lap) VALUES ('".$idTracker."','".$idClient."','".$zone."','".$warningType."','".$destMethod."','".$Id_DestSet1."','".$Id_DestSet2."','".$messageEntree."','".$messageSortie."','".$Type_Geometrie."','".$warningLap."')";
        //ChromePhp::log($sql);
        mysqli_query($connection,$sql);
        mysqli_close($connection);
 		echo "succes";
   }
?>