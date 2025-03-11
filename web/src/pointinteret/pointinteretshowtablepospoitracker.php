<?php

/*
 * Afficher la table des POI selon la balise
 */

/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 17/02/2015
 * Time: 11:22
 */
session_start();
include '../dbgpw.php';
include '../dbconnect2.php';
$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
$ipDatabaseGpw=$_GET["ipDatabaseGpw"];

/************* Recuperer la configuration de l'utilisateur *******************/
$connectGpwUserConfig = mysqli_connect($server, $db_user, $db_pass,$database);
if (!$connectGpwUserConfig) {
    die('Impossible de se connecter: '.mysqli_connect_error());
}
$queryGpwUserConfig = mysqli_query($connectGpwUserConfig,"SELECT Configuration FROM gpwutilisateurconfiguration WHERE (Login = '".$_SESSION['username']."' AND ((Application = 'mGeo3X') || (Application = 'DEFAUT')) )");
$assocGpwUserConfig = mysqli_fetch_assoc($queryGpwUserConfig);
$userConfig = $assocGpwUserConfig['Configuration'];
if($userConfig == "" || $userConfig == null) $userConfig = "WEB_UTILISATEUR";

mysqli_free_result($queryGpwUserConfig);
mysqli_close($connectGpwUserConfig);

$database = $nomDatabaseGpw;
$server = $ipDatabaseGpw;

    $idTracker=$_GET["idTracker"];
    $nomTracker=$_GET["nomTracker"];
    $numeroZonePoi=$_GET["numeroZonePoi"];



    $link = mysqli_connect($server, $db_user_2, $db_pass_2,$database);
    mysqli_set_charset($link, "utf8");
    $match = "SELECT * from tpoi WHERE Id='".$numeroZonePoi."' ORDER BY Id";
    $result = mysqli_query($link,$match);

    while($row = mysqli_fetch_array($result)){
        echo "<tr onclick=\"afficheInfobullTablePOI2(this,'".$row['Id']."','".$userConfig."');\"><td>".$nomTracker."</td>
        <td>".$row['Id']."</td><td  >".$row['Name']."</td><td>".$row['adresse']."</td><td>".$row['description']."</td>
        <td style='display:none'>".$row['latitude']."</td><td style='display:none'>".$row['longitude']."</td><td>".$row['Rayon']."</td><td style='display:none'>".$idTracker."</td></tr>";
    }
    mysqli_free_result($result);
    mysqli_close($link);
?>