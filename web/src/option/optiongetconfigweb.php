<?php

/*
* Recupérer la configuration web
*/

/**
 * Created by PhpStorm.
 * User: Christophe NGUYEN
 * Date: 18/02/2016
 * Time: 11:34
 */
include '../dbgpw.php';
session_start();

$login = $_GET["login"];
$connectGpwUserAccountConfig = mysqli_connect($server, $db_user, $db_pass,$database);
$queryGpwUserAccountConfig = mysqli_query($connectGpwUserAccountConfig,"SELECT Configuration FROM gpwutilisateurconfiguration WHERE
						(Login = '".$_SESSION['username']."' AND ((Application = 'mGeo3X') || (Application = 'DEFAUT')) )");
$assocGpwUserAccountConfig = mysqli_fetch_assoc($queryGpwUserAccountConfig);
$userConfig = $assocGpwUserAccountConfig['Configuration'];
mysqli_free_result($queryGpwUserAccountConfig);
mysqli_close($connectGpwUserAccountConfig);

$connectGpwUserConfig = mysqli_connect($server, $db_user, $db_pass, $database);
$queryGpwUserConfig = mysqli_query($connectGpwUserConfig, "SELECT Configuration FROM gpwutilisateurconfiguration WHERE
							(Login = '" . $login. "' AND ((Application = 'mGeo3X') || (Application = 'DEFAUT')) )");
$assocGpwUserConfig = mysqli_fetch_assoc($queryGpwUserConfig);
$userConfigCompte = $assocGpwUserConfig['Configuration'];
//if ($userConfigCompte == "" || $userConfigCompte == null) $userConfigCompte = "WEB_UTILISATEUR";
mysqli_free_result($queryGpwUserConfig);
mysqli_close($connectGpwUserConfig);

    echo "Account: $userConfig";
    echo "Target: $userConfigCompte&";