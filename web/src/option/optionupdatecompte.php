<?php


/*
* Mise à jour du compte
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
    $idClient = $_GET["idClient"];
    $nomBase = $_GET["nomBase"];
    $compteLogin = $_GET["compteLogin"];
    $compteMdp = $_GET["compteMdp"];
    $compteNom = $_GET["compteNom"];
    $comptePrenom = $_GET["comptePrenom"];

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

    if($compteType == "0") $compteDuree = "0";
    $connection = mysqli_connect($server, $db_user, $db_pass, $database);
 
    $sql = "UPDATE gpwutilisateur SET Nom = '$compteNom', Nom = '$compteNom', Prenom = '$comptePrenom', MotPasse = '$compteMdp', MotPasseASaisir = '$checkedSaisieMdp',
                Type = '$compteType', Duree = '$compteDuree' , Datefin = NULL WHERE Login = '$compteLogin' ";
    //ChromePhp::log($sql);
    mysqli_query($connection, $sql);
    mysqli_close($connection);

    if($_GET["compteConfig"] != "aucun") {
        $compteConfig = $_GET["compteConfig"];

        $connection2 = mysqli_connect($server, $db_user, $db_pass, $database);
        $sql2 = "UPDATE gpwutilisateurconfiguration SET Configuration = '$compteConfig' WHERE Login = '$compteLogin' AND Application = 'mGeo3X'";
         
        mysqli_query($connection2, $sql2);
        mysqli_close($connection2);

        $chaine = substr($compteConfig, 4);
        if ($compteConfig == "WEB_UTILISATEUR_ALARMES") $chaine = "UTILISATEUR_ALM";
        if ($compteConfig == "WEB_UTILISATEUR_NI_ALARMES") $chaine = "UTILISATEUR_NI_ALM";
        $connection3 = mysqli_connect($server, $db_user, $db_pass, $database);
        $sql3 = "UPDATE gpwutilisateurconfiguration SET Configuration = '$chaine' WHERE Login = '$compteLogin' AND Application = 'Geo3XC'";

        mysqli_query($connection3, $sql3);
        mysqli_close($connection3);
    }

    echo "Vous avez bien modifié le compte : ".$compteLogin;


?>