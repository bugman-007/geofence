<?php
/*
* Ajouter un compte
*/

    /**
     * Created by PhpStorm.
     * User: NGUYEN Christophe
     * Date: 05/06/2015
     * Time: 10:06
     */

    include '../dbgpw.php';
    include '../dbconnect2.php';
    include '../ChromePhp.php';
    session_start();
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

    $idClient = $_GET["idClient"];
    $nomBase = $_GET["nomBase"];
    $compteLogin = $_GET["compteLogin"];
    $compteMdp = $_GET["compteMdp"];
    $compteNom = $_GET["compteNom"];
    $comptePrenom = $_GET["comptePrenom"];
    $compteConfig = $_GET["compteConfig"];
    $compteDuree = $_GET["compteDuree"];
    $compteMail = $_GET["compteMail"];
    $checkedSaisieMdp = $_GET["checkedSaisieMdp"];
    $checkedAdmin = $_GET["checkedAdmin"];
    $compteType = $_GET["compteType"];

    $connectGpwBD = mysqli_connect($server, $db_user, $db_pass,$database);
    $queryGpwBD = mysqli_query($connectGpwBD,"SELECT Id_Base FROM gpwbd WHERE NomBase = '$nomBase' ");
    $assocGpwBD = mysqli_fetch_assoc($queryGpwBD);
    $idBase = $assocGpwBD['Id_Base'];
    mysqli_free_result($queryGpwBD);
    mysqli_close($connectGpwBD);
   
    $connectGpwUtilisateur = mysqli_connect($server, $db_user, $db_pass,$database);
    mysqli_set_charset($connectGpwUtilisateur, "utf8");
    $queryGpwUtilisateur = mysqli_query($connectGpwUtilisateur,"SELECT * FROM gpwutilisateur WHERE Login = '$compteLogin' ");
    $lengths = mysqli_num_rows($queryGpwUtilisateur);
    mysqli_free_result($queryGpwUtilisateur);
    mysqli_close($connectGpwUtilisateur);

    if($compteType == "0") $compteDuree = "0";

    if($lengths == 0) {
//        $connection = mysqli_connect($server, $db_user, $db_pass, $database);
//
//
//        mysqli_begin_transaction($connection);
//        mysqli_autocommit($connection, FALSE);
//        $sql = "INSERT INTO gpwutilisateur VALUES('','$compteLogin','$compteNom','$comptePrenom','$compteMdp','','$checkedSaisieMdp','$compteType','$compteDuree','','$idClient','$idBase')";
//        $sql2 = "INSERT INTO gpwutilisateurconfiguration VALUES('','$compteConfig','$compteLogin','mGeo3X')";
//
//        $chaine = substr($compteConfig, 4);
//        if($compteConfig == "WEB_UTILISATEUR_ALARMES") $chaine = "UTILISATEUR_ALM";
//        if($compteConfig == "WEB_UTILISATEUR_NI_ALARMES") $chaine = "UTILISATEUR_NI_ALM";
//        $sql3 = "INSERT INTO gpwutilisateurconfiguration VALUES('','$chaine','$compteLogin','Geo3XC')";
//
//        $sql4 = "INSERT INTO gpwuser_gpw VALUES('','$compteLogin','0','','$idClient','$idBase','0')";
//
//        $errors = array();
//        if (!mysqli_query($connection, $sql))
//            $errors[] = mysqli_error() ;
//        if (!mysqli_query($connection, $sql2))
//            $errors[] = mysqli_error() ;
//        if (!mysqli_query($connection, $sql3))
//            $errors[] = mysqli_error() ;
//        if (!mysqli_query($connection, $sql4))
//            $errors[] = mysqli_error() ;
//
//        if(count($errors) === 0) {
//            mysqli_commit($connection);
//            echo _('option_alert_nouveaucomptecreer'); echo " ".$compteLogin;
//        } else {
//            mysqli_rollback($connection);
//            echo $errors;
//        }
//
//        mysqli_close($connection);
        $connection = mysqli_connect($server, $db_user, $db_pass, $database);
        mysqli_set_charset($connection, "utf8");
        $sql = "INSERT INTO gpwutilisateur (Login,Nom, Prenom, MotPasse, MotPasseASaisir,Type,Duree,Id_Client,Id_Base) VALUES('$compteLogin','$compteNom','$comptePrenom','$compteMdp','$checkedSaisieMdp','$compteType','$compteDuree','$idClient','$idBase')";
        //ChromePhp::log($sql);
        mysqli_query($connection, $sql);
        mysqli_close($connection);

        $connection2 = mysqli_connect($server, $db_user, $db_pass, $database);
        $sql2 = "INSERT INTO gpwutilisateurconfiguration (Configuration,Login,Application) VALUES('$compteConfig','$compteLogin','mGeo3X')";
        mysqli_query($connection2, $sql2);
        mysqli_close($connection2);
       
        $chaine = substr($compteConfig, 4);
        if($compteConfig == "WEB_UTILISATEUR_ALARMES") $chaine = "UTILISATEUR_ALM";
        if($compteConfig == "WEB_UTILISATEUR_NI_ALARMES") $chaine = "UTILISATEUR_NI_ALM";
        $connection3 = mysqli_connect($server, $db_user, $db_pass, $database);
        $sql3 = "INSERT INTO gpwutilisateurconfiguration (Configuration,Login,Application) VALUES('$chaine','$compteLogin','Geo3XC')";
        mysqli_query($connection3, $sql3);
        mysqli_close($connection3);

        $connection4 = mysqli_connect($server, $db_user, $db_pass, $database);
        $sql4 = "INSERT INTO gpwuser_gpw (Login,Id_GPW,Id_Client,Id_Base,Superviseur) VALUES('$compteLogin','0','$idClient','$idBase','$checkedAdmin')";
        mysqli_query($connection4, $sql4);
        mysqli_close($connection4);
        echo _('option_alert_nouveaucomptecreer'); echo " ".$compteLogin;
    }else{
//        echo _('option_alert_nomcompteexistedeja');
    }

?>