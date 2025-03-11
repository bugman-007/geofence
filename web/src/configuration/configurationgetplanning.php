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
//include '../ChromePhp.php';

	 
    //$idGPW=$_GET["idGPW"];
    $ipBase=$_GET["ipBase"];
    $nomBase=$_GET["nomBase"];
    $idTracker=$_GET["idTracker"];
    
    $database = $nomBase;
    $server = $ipBase;
//ChromePhp::log($ipBase,$idTracker);
    //ChromePhp::log($server,$db_user_2, $db_pass_2,$database);

    $connection=mysqli_connect($server,$db_user_2, $db_pass_2,$database);
    $sql = "SELECT * FROM tplanning_tsl WHERE (Id_tracker = '" . $idTracker . "' )";
   	$result = mysqli_query($connection,$sql);
	$i=0;
	$arr[0] = "";
	
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$arr[$i]=$row;
		$i++;
    }  
    echo json_encode($arr);
	
	mysqli_free_result($result);
	mysqli_close($connection);
?>
