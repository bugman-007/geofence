<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 27/02/2015
 * Time: 16:18
 */

    session_start();
    $_SESSION['CREATED'] = time();
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

    include '../dbgpw.php';
    include '../dbconnect2.php';
    ini_set('display_errors','off');
    $idTracker=$_GET["idTracker"];
    $nomTracker=$_GET["nomBalise"];

    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];

    $selectCarburant=$_GET["selectCarburant"];
    $carburant100km=$_GET["carburant100km"];
    $carburant=$_GET["carburant"];

    if($selectCarburant == "1") {
        $emisionCO2 = 2380;
    }
    if($selectCarburant == "2"){
        $emisionCO2 = 2650 ;
    }
    if($selectCarburant == "3"){
        $emisionCO2 = 1780;
    }
    if($selectCarburant == "4"){
        $emisionCO2 = 2740;
    }

    session_start();

    $_SESSION['CREATED'] = time();

    /************* Recuperer l'Id_Client de l'utilisateur *******************/
    $connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
    $queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Client FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' AND Id_GPW != 0)");
    $assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
    $idClient = abs($assocGpwUser['Id_Client']);
    mysqli_free_result($queryGpwUser);
    mysqli_close($connectGpwUser);


    /****************************/
    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;

    $connectionCVerif=mysqli_connect($server, $db_user_2, $db_pass_2, $database);
    $sqlVerif = "SELECT Format_rapport FROM trapport WHERE (Id_tracker = '$idTracker' AND Format_rapport = '0' AND Freq_envoi = '-1' AND Fuseau_DecalMin = '-60')";
    $resultVerif= mysqli_query($connectionCVerif,$sqlVerif);
    $typeExist = mysqli_num_rows($resultVerif);
    mysqli_free_result($resultVerif);
    mysqli_close($connectionCVerif);

    if($typeExist == "1"){
        $connection=mysql_connect($server, $db_user_2, $db_pass_2);
        if (!$connection) {
            die('Not connected : ' . mysql_error());
        }
        $db_selected = mysql_select_db($database, $connection);
        if (!$db_selected) {
            die ('Can\'t use db : ' . mysql_error());
        }
        $sql="UPDATE trapport  SET
                TypeCarburant = '".$selectCarburant."',  LitrePar100Km = '".$carburant100km."',
                CO2ParL = '".$emisionCO2."'
                WHERE (Id_tracker = '".$idTracker."' AND Format_rapport = '0' AND Freq_envoi = '-1' AND Fuseau_DecalMin = '-60')";
        $result = mysql_query($sql);
        if (!$result) {
            die(mysql_error());
        }
        mysql_close($connection);

        echo _('rapport_confirmmodifcarburant')." ".$nomTracker;
    }else{
        $connection=mysql_connect($server, $db_user_2, $db_pass_2);
        if (!$connection) {
            die('Not connected : ' . mysql_error());
        }
        $db_selected = mysql_select_db($database, $connection);
        if (!$db_selected) {
            die ('Can\'t use db : ' . mysql_error());
        }
        $sql="INSERT INTO trapport(Id_tracker, Id_Client, Format_rapport, RapportEtape, Freq_envoi, HeureJourD,
              HeureJourF, DateTimeD_UTC,  DateTimeF_UTC, DateTime_envoiUTC, JourEnvoi, Fuseau_DecalMin, Sujet, Message,
              Dest_Method, Id_DestSet2,TypeCarburant,LitrePar100Km,CO2ParL) VALUES('$idTracker','$idClient','0','','-1', '','',
              '', '', '', '', '-60', '', '','0','0','$selectCarburant','$carburant100km','$emisionCO2')";
        $result = mysql_query($sql);
        if (!$result) {
            die(mysql_error());
        }
        mysql_close($connection);
        echo _('rapport_confirmcreercarburant')." ".$nomTracker;

    }
?>

