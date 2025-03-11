<?php
    /**
     * Created by PhpStorm.
     * User: Christophe NGUYEN
     * Date: 04/05/2016
     * Time: 09:40
     */

    /*
    * Enregistre le nom de l'icon dans la bdd
     * Carto.js : getIcone()
    */

include '../dbconnect2.php';

    $q=$_GET["Id_Tracker"];
//    $nomBalise=$_GET["nomBalise"];
    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];

    $connection=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
    $sql="SELECT Icone FROM tpositions0  WHERE Pos_Id_tracker = '".$q."' ORDER BY Pos_DateTime_position DESC LIMIT 1";
    $result = mysqli_query($connection,$sql);
    $assoc = mysqli_fetch_assoc($result);

    $icone = $assoc['Icone'];

    if($icone == null || $icone == ""){

        if(file_exists ("../../assets/img/BibliothequeIcone/".$nomDatabaseGpw."_".$q.".png"))     $icone = $nomDatabaseGpw."_".$q.".png";
        else if(file_exists ("../../assets/img/BibliothequeIcone/".$nomDatabaseGpw."_".$q.".ico"))     $icone = $nomDatabaseGpw."_".$q.".ico";
        else     $icone = "default.png";

        echo $icone;

        $connection2=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
        $sql2="UPDATE tpositions0  SET Icone = '".$icone."' WHERE Pos_Id_tracker = '".$q."'";
        mysqli_query($connection2,$sql2);
        mysqli_close($connection2);

    }
    else echo $icone;
