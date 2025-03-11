<?php

/*
* Ajouter les balises dans un groupe
*/

    /**
     * Created by PhpStorm.
     * User: NGUYEN Christophe
     * Date: 05/06/2015
     * Time: 10:06
     */

    include '../dbgpw.php';

    session_start();
	
	// Bibliotheque pour l'internationalisation
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
    $idBalise = $_GET["idBalise"];
    $nomBalise = $_GET["nomBalise"];

	// Récup de l'Id_Base
    $connectGpwBD = mysqli_connect($server, $db_user, $db_pass,$database);
    mysqli_set_charset($connectGpwBD, "utf8");
    $queryGpwBD = mysqli_query($connectGpwBD,"SELECT Id_Base FROM gpwbd WHERE NomBase = '".$nomBase."'");
    $assocGpwBD = mysqli_fetch_assoc($queryGpwBD);
    $idBase = $assocGpwBD['Id_Base'];
    mysqli_free_result($queryGpwBD);
    mysqli_close($connectGpwBD);

	// Récup de l'Id_GPW
    $connectGpw = mysqli_connect($server, $db_user, $db_pass, $database);
    mysqli_set_charset($connectGpw, "utf8");
    $queryGpw = mysqli_query($connectGpw,"SELECT Id_GPW FROM gpw WHERE NomGPW = '".$nomGroupe."'");
    if($arrayGpw = mysqli_fetch_array($queryGpw)){
		$idGpw = $arrayGpw['Id_GPW'];
	}else{
		$idGpw = 0;
		echo "ERREUR: Echec de recuperation du groupe\n\n";
	}
    mysqli_free_result($queryGpw);
    mysqli_close($connectGpw);



//    $arrayIdBalise = array();
//    $arrayNomBalise = array();
//    $connectGpwBalise = mysqli_connect($server, $db_user, $db_pass,$database);
//    $queryGpwBalise = mysqli_query($connectGpwBalise,"SELECT Id_Balise,Nom_Balise FROM gpwbalise WHERE id_GPW = ".$idGpw." ORDER BY Nom_Balise");
//    while($arrayGpwBalise = mysqli_fetch_array($queryGpwBalise)){
//        $arrayIdBalise[$i] = $arrayGpwBalise['Id_Balise'];
//        $arrayNomBalise[$i] = $arrayGpwBalise['Nom_Balise'];
//        $i++;
//    }
//    mysqli_free_result($queryGpwBalise);
//    mysqli_close($connectGpwBalise);
//

	// Ajout de la balise au groupe.
	if($idGpw > 0)
	{
		$connection=mysqli_connect($server, $db_user, $db_pass, $database);
        mysqli_set_charset($connection, "utf8");
		// if (!in_array($idBalise, $arrayIdBalise)){
			$sql="INSERT INTO gpwbalise (Id_GPW, Id_groupe, Nom_Groupe, Id_Balise, Nom_Balise, Id_Client, Id_Base) VALUES ('".$idGpw."','0','0','".$idBalise."','".$nomBalise."','".$idClient."','".$idBase."')";
			mysqli_query($connection,$sql);
		// }
		mysqli_close($connection);
	}
	else
	{
		echo "Echec, la balise: ".$nomBalise." (ID = ".$idBalise.") n'a pu etre ajouter au groupe: ".$nomGroupe;
	}

?>