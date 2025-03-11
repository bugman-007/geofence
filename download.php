<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 05/02/2016
 * Time: 10:15
 */

session_start();
include 'web/src/dbgpw.php';

$connectGpwUserConfig = mysqli_connect($server, $db_user, $db_pass,$database);
if (!$connectGpwUserConfig) {
    die('Impossible de se connecter: '.mysqli_connect_error());
}
$queryGpwUserConfig = mysqli_query($connectGpwUserConfig,"SELECT Configuration FROM gpwutilisateurconfiguration WHERE
						(Login = '".$_SESSION['username']."' AND ((Application = 'mGeo3X') || (Application = 'DEFAUT')) )");
$assocGpwUserConfig = mysqli_fetch_assoc($queryGpwUserConfig);
$userConfig = $assocGpwUserConfig['Configuration'];
if($userConfig == "" || $userConfig == null) $userConfig = "WEB_UTILISATEUR";

mysqli_free_result($queryGpwUserConfig);
mysqli_close($connectGpwUserConfig);

set_time_limit(0);
//First, see if the file exists

	if ($userConfig == "WEB_GESTIONNAIRE" || $userConfig == "SUPERVISEUR" || $userConfig == "WEB_UTILISATEUR_NI_AVANCE" || $userConfig == "WEB_UTILISATEUR_AVANCE") {
        $file="web/doc/NOTICE_LOGICIEL_STANCOM+.pdf";
    }else{
        $file="web/doc/NOTICE_LOGICIEL_STANCOM.pdf";
    }


if (!is_file($file)) { die("<b>404 File not found!</b>"); }

//Gather relevent info about file
$len = filesize($file);
$filename = basename($file);
$file_extension = strtolower(substr(strrchr($filename,"."),1));

//Begin writing headers
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");

//Use the switch-generated Content-Type
header('Content-Type: application/octet-stream');

//Force the download
$header="Content-Disposition: attachment; filename=".$filename.";";
header($header);
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".$len);
@readfile($file);