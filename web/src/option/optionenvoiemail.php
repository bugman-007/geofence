<?php

    /*
  * Envoyer un compte mail
  */


/**
 * Created by PhpStorm.
 * User: Emachines1
 * Date: 01/02/2016
 * Time: 14:55
 */

//error_reporting(0);

    $compteLogin = $_GET["compteLogin"];
    $compteMdp = $_GET["compteMdp"];
    $compteDuree = $_GET["compteDuree"];
    $compteMail = $_GET["compteMail"];
    $checkedSaisieMdp = $_GET["checkedSaisieMdp"];
    $compteType = $_GET["compteType"];



    $validiteCompte="";
    if ( $compteType == "1" )$validiteCompte= $compteDuree." heures";
    if ( $compteType == "2" )$validiteCompte= $compteDuree." jours";
    if ( $compteType == "3" )$validiteCompte= $compteDuree." semaines";
    if ( $compteType == "4" )$validiteCompte= $compteDuree." mois";
    if ( $compteType == "0" )$validiteCompte= "Illimite";


    if($checkedSaisieMdp == "1") $compteMdp = "A créer au premier lancement";
    $sujet = 'Création de compte Geo3X';
    $message = "Bonjour,<br />
    Nous tenons à vous adresser le compte utilisateur pour le site www.geo3x.fr et www.geo3x.com<br />
    <br />
    Utilisateur: $compteLogin<br />
    Mot de passe:  $compteMdp
    <br /><br />
    Validité du compte: $validiteCompte à compter de votre première utilisation www.geo3x.fr et www.geo3x.com
    <br /><br />
    Cordialement";

    $destinataire = $compteMail;
    $headers = "From: \"geo3x@geo3x.fr\"<geo3x@geo3x.fr>\n";
    $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"";
    if(mail($destinataire,$sujet,$message,$headers))
    {
        echo "L'email a bien été envoyé.";
    }
    else
    {
        echo "Envoie du mail via le serveur";
//        echo "Une erreur s'est produite lors de l'envois de l'email";
        session_start();
        include '../dbgpw.php';

        $connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
        $queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Base,Id_GPW, NomGPW, Superviseur, Id_Client FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' ) ORDER BY NomGPW"); //AND Id_GPW != 0
        $assocGpwUser = mysqli_fetch_assoc($queryGpwUser);

        $idClientGpwUser = $assocGpwUser['Id_Client'];

        mysqli_free_result($queryGpwUser);
        mysqli_close($connectGpwUser);

        include '../dbconnect2.php';

        $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
        $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
        $datetime=$_GET["datetime"];

        $connection=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
        mysqli_set_charset($connection, "utf8");
        $sql="INSERT INTO  tmessages  (TypeMSG, Dest, Sujet, Corps, Date, DateEnvoi, Client) VALUES ('3','".$destinataire."','".$sujet."','".$message."','".$datetime."','".$datetime."','".$idClientGpwUser."') ";

        $result = mysqli_query($connection,$sql);

        mysqli_close($connection);
    }

