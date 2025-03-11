<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 27/02/2015
 * Time: 16:18
 */

    include '../dbgpw.php';
    include '../dbconnect2.php';

    $idTracker=$_GET["idTracker"];
    $nomTracker=$_GET["nomTracker"];
    $formatRapport=$_GET["formatRapport"];
    $rapportEtape=$_GET["rapportEtape"];
    $type=$_GET["type"];
    $heureJourD=$_GET["heureJourD"];
    $heureJourF=$_GET["heureJourF"];
    $dateTimeDebut=$_GET["dateTimeDebut"];
    $dateTimeFin=$_GET["dateTimeFin"];
    $dateTimeEnvoi=$_GET["dateTimeEnvoi"];
    $jourEnvoi=$_GET["jourEnvoi"];
    $fuseauDecalage=$_GET["fuseauDecalage"];
    $sujet=$_GET["sujet"];
    $message=$_GET["message"];
    $destMethod=$_GET["destMethod"];

    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];


    $dateTimeDebut = date("Y-m-d H:i:s", strtotime($dateTimeDebut));
    $dateTimeFin = date("Y-m-d H:i:s", strtotime($dateTimeFin));
    $dateTimeEnvoi = date("Y-m-d H:i:s", strtotime($dateTimeEnvoi));

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
    $sqlVerif = "SELECT Freq_envoi FROM trapport WHERE Id_tracker = '".$idTracker."' AND Freq_envoi = '".$type."' ";
    $resultVerif= mysqli_query($connectionCVerif,$sqlVerif);
    $typeExist = mysqli_num_rows($resultVerif);
    mysqli_free_result($resultVerif);
    mysqli_close($connectionCVerif);

    if($typeExist == "1"){
        $connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
        mysqli_set_charset($connection, "utf8");
        $sql="UPDATE trapport  SET Format_rapport = '".$formatRapport."', RapportEtape = '".$rapportEtape."', HeureJourD = '".$heureJourD."', HeureJourF = '".$heureJourF."',
                DateTimeD_UTC = '".$dateTimeDebut."',  DateTimeF_UTC = '".$dateTimeFin."',
                DateTime_envoiUTC = '".$dateTimeEnvoi."',  JourEnvoi = '".$jourEnvoi."',
                Fuseau_DecalMin = '".$fuseauDecalage."',  Sujet = '".$sujet."',
                Message = '".$message."',  Dest_Method = '".$destMethod."'
                WHERE Id_tracker = '".$idTracker."' AND Freq_envoi = '".$type."' ";
        $result = mysqli_query($connection,$sql);

        mysqli_close($connection);

        echo _('rapport_alert_modifier');   echo " ".$nomTracker;
    }else{
        $connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
        mysqli_set_charset($connection, "utf8");
        $sql="INSERT INTO trapport(Id_tracker, Id_Client, Format_rapport, RapportEtape, Freq_envoi, HeureJourD,
              HeureJourF, DateTimeD_UTC,  DateTimeF_UTC, DateTime_envoiUTC, JourEnvoi, Fuseau_DecalMin, Sujet, Message,
              Dest_Method, Id_DestSet2) VALUES('$idTracker','$idClient','$formatRapport','$rapportEtape','".$type."', '$heureJourD','$heureJourF',
              '".$dateTimeDebut."', '".$dateTimeFin."', '$dateTimeEnvoi', '$jourEnvoi', '$fuseauDecalage', '$sujet', '$message',
              '$destMethod','5')";
        $result = mysqli_query($connection,$sql);

        mysqli_close($connection);
        echo _('rapport_alert_creer');   echo " ".$nomTracker;

    }
?>
