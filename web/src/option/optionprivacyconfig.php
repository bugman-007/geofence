<?php

/*
* Ajouter uen confidentialité
*/

/**
 * Created by PhpStorm.
 * User: Christophe NGUYEN
 * Date: 26/04/2016
 * Time: 10:52
 */
    require_once ("../../../lib/php-gettext-1.0.12/gettext.inc");
    $locale = "fr_FR";
    if (isset($_SESSION["language"])) {
        $locale = $_SESSION['language'];
    }else{
        $_SESSION['language'] = "fr_FR";
        $locale = "fr_FR";
    }
    T_setlocale(LC_MESSAGES, $locale);
    $encoding = "UTF-8";
    $domain = "messages";
    bindtextdomain($domain, '../../../locale');
    bind_textdomain_codeset($domain, $encoding);
    textdomain($domain);

    include '../dbconnect2.php';

    //$idGPW=$_GET["idGPW"];
    $ipBase=$_GET["ipBase"];
    $nomBase=$_GET["nomBase"];
    $idTracker=$_GET["idTracker"];
    $nomBalise=$_GET["nomBalise"];

    $database = $nomBase;
    $server = $ipBase;

    $NbrPlage="";
    $Hd1="";
    $Hf1="";
    $Hd2="";
    $Hf2="";
    $Lundi="";
    $Mardi="";
    $Mercredi="";
    $Jeudi="";
    $Vendredi="";
    $Samedi="";
    $Dimanche="";

    $connection2=mysqli_connect($server,$db_user_2, $db_pass_2,$database);
    $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $idTracker . "' )";
    $result2 = mysqli_query($connection2,$sql2);


    if(mysqli_num_rows($result2) > 0 ) {
        while ($row2 = mysqli_fetch_array($result2)) {
            echo "NbrPlage". $row2['NbrPlage'];
            echo "Hd1". $row2['Hd1'];
            echo "Hf1".$row2['Hf1'];
            echo "Hd2". $row2['Hd2'];
            echo "Hf2". $row2['Hf2'];
            echo "Lundi".$row2['Lundi'];
            echo "Mardi".$row2['Mardi'];
            echo "Mercredi".$row2['Mercredi'];
            echo "Jeudi".$row2['Jeudi'];
            echo "Vendredi". $row2['Vendredi'];
            echo "Samedi". $row2['Samedi'];
            echo "Dimanche".$row2['Dimanche'];
        }
    }else{
        $connection=mysqli_connect($server,$db_user_2, $db_pass_2,$database);
        $sql = "INSERT INTO tplanning(Id_tracker,NbrPLage, Hd1, Hf1, Hd2, Hf2, Lundi, Mardi, Mercredi, Jeudi, Vendredi, Samedi, Dimanche) VALUES('$idTracker','0','0000','1200','1200','2359','1','1','1','1','1','1','1')";
        $result = mysqli_query($connection,$sql);
        mysqli_query($connection);

        echo "NbrPlage". "0";
        echo "Hd1". "0000";
        echo "Hf1". "1200";
        echo "Hd2". "1200";
        echo "Hf2". "2359";
        echo "Lundi"."1";
        echo "Mardi"."1";
        echo "Mercredi"."1";
        echo "Jeudi"."1";
        echo "Vendredi". "1";
        echo "Samedi". "1";
        echo "Dimanche"."1";

    }

    mysqli_free_result($result2);
    mysqli_close($connection2);
?>
