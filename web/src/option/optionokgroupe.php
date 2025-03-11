<?php

/*
* Mise a jour d'un groupe
*/
/**
 * Created by PhpStorm.
 * User: Emachines1
 * Date: 20/07/2015
 * Time: 15:40
 */

    include '../dbgpw.php';
    include '../dbconnect2.php';
    include '../ChromePhp.php';
    session_start();
    
    $nomGroupe = $_GET["nomGroupe"];
    $nomGroupeNew = $_GET["nomGroupeNew"];
  

    $connection = mysqli_connect($server, $db_user, $db_pass, $database);
    mysqli_set_charset($connection, "utf8");
    $sql = "SELECT * FROM gpwuser_gpw WHERE NomGPW = '$nomGroupeNew'";
    //ChromePhp::log($idClient);
    $result = mysqli_query($connection, $sql);
    $nbrec = mysqli_num_rows($result);

    if ($nbrec > 0){
        
        $outArr=array("status"=>"no");
       // ChromePhp::log("nb enr:",$nbrec);
    }else{
        $sql = "UPDATE gpwuser_gpw SET NomGPW = '$nomGroupeNew' WHERE NomGPW = '$nomGroupe'";
        mysqli_query($connection, $sql);

        $sql = "UPDATE gpw SET NomGPW = '$nomGroupeNew' WHERE NomGPW = '$nomGroupe' ";
        mysqli_query($connection, $sql);
        // ChromePhp:log("Select a retourné %d lignes.\n", mysqli_num_rows($result2));
        //if (!$result){
        //  ChromePhp:log(mysqli_error($result));
        //  }
        $outArr=array("status"=>"ok");
       
    }
    mysqli_free_result($result);
    mysqli_close($connection);

   


    /*$connection3 = mysqli_connect($server, $db_user, $db_pass, $database);
    mysqli_set_charset($connection3, "utf8");
    $sql3 = "UPDATE gpwuser_gpw SET NomGPW = '$nomGroupeNew' WHERE NomGPW = '$nomGroupe'";
    ChromePhp::log($sql3);
    $result = mysqli_query($connection3, $sql3);
    //ChromePhp:log($result);
    if (!$result){
        ChromePhp:log(mysqli_error($result));
    }
    mysqli_close($connection3);
    if ($result){
        ChromePhp:log(mysqli_error($result));
    }*/
    //echo "test";
    
$jsonResponse=json_encode($outArr);
echo $jsonResponse;

?>