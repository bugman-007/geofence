<?php

/*
* Ajouter un groupe
*/

    /**
     * Created by PhpStorm.
     * User: NGUYEN Christophe
     * Date: 05/06/2015
     * Time: 10:06
     */
    include '../ChromePhp.php';
    include '../dbgpw.php';
    include '../dbconnect2.php';

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
    $nomGroupe = $_GET["nomGroupe"];

    $connectGpwBD = mysqli_connect($server, $db_user, $db_pass,$database);
    $queryGpwBD = mysqli_query($connectGpwBD,"SELECT Id_Base FROM gpwbd WHERE NomBase = '$nomBase' ");
    $assocGpwBD = mysqli_fetch_assoc($queryGpwBD);
    $idBase = $assocGpwBD['Id_Base'];
    mysqli_free_result($queryGpwBD);
    mysqli_close($connectGpwBD);
    $connectGpw = mysqli_connect($server, $db_user, $db_pass,$database);
    $queryGpw = mysqli_query($connectGpw,"SELECT * FROM gpw WHERE NomGPW = '$nomGroupe' ");
    $lengths = mysqli_num_rows($queryGpw);
    mysqli_free_result($queryGpw);
    mysqli_close($connectGpw);
    if($lengths == 0) {
        $connection = mysqli_connect($server, $db_user, $db_pass, $database);
        mysqli_set_charset($connection, "utf8");
        $sql = "INSERT INTO gpw (NomGPW, Id_Client, Id_Base) VALUES ('$nomGroupe','$idClient','$idBase')";
        $result = mysqli_query($connection, $sql);
 
        
        mysqli_close($connection);
        echo "1:"._('option_alert_nouveaugroupecreer')." ".$nomGroupe;
    }else{
        echo "0:"._('option_alert_nomgroupeexistedeja');
    }

?>