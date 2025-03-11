<?php

/*
* Mise à jour de la confidentialité
*/


    /**
     * Created by PhpStorm.
     * User: Christophe NGUYEN
     * Date: 26/04/2016
     * Time: 10:52
     */


    include '../dbconnect2.php';

    //$idGPW=$_GET["idGPW"];
    $ipBase=$_GET["ipBase"];
    $nomBase=$_GET["nomBase"];
    $idTracker=$_GET["idTracker"];
    $nomBalise=$_GET["nomBalise"];

    $database = $nomBase;
    $server = $ipBase;

    $Hd1=$_GET["Hd1"];
    $Hf1=$_GET["Hf1"];
    $Hd2=$_GET["Hd2"];
    $Hf2=$_GET["Hf2"];
    $Lundi=$_GET["Lundi"];
    $Mardi=$_GET["Mardi"];
    $Mercredi=$_GET["Mercredi"];
    $Jeudi=$_GET["Jeudi"];
    $Vendredi=$_GET["Vendredi"];
    $Samedi=$_GET["Samedi"];
    $Dimanche=$_GET["Dimanche"];


    $connection=mysqli_connect($server,$db_user_2, $db_pass_2,$database);

    $sql="UPDATE tplanning  SET Hd1 = '$Hd1', Hf1 = '$Hf1', Hd2 = '$Hd2',Hf2 = '$Hf2',
    Lundi = '$Lundi',Mardi = '$Mardi',Mercredi = '$Mercredi',Jeudi = '$Jeudi',Vendredi = '$Vendredi',Samedi = '$Samedi',Dimanche = '$Dimanche'
    WHERE (Id_tracker = '".$idTracker."')";

     mysqli_query($connection,$sql);



    echo "Ok";

    mysqli_close($connection);
?>
