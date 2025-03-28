<?php

	/*
	 * Permet la recuperation de données par AJAX pour detecter la base de donnée de l'utilisateur
	 * Appelé par la fonction javascript du fichier layout.js detectDatabase()
	 */


	include '../dbgpw.php';
	session_start();
	$_SESSION['username'];

    // On recupere l'id base et l'id client de l'utilisateur
	$connectGpwUser = mysqli_connect($server, $db_user, $db_pass, $database);
	if (!$connectGpwUser) {
		die('Impossible de se connecter: '.mysqli_connect_error());
	}
	mysqli_set_charset($connectGpwUser, "utf8");
	$queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Base, Id_Client FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' ) ORDER BY NomGPW"); //AND Id_GPW != 0
	$assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
	$idBase = $assocGpwUser['Id_Base'];
	$idClientGpwUser = $assocGpwUser['Id_Client'];
	mysqli_free_result($queryGpwUser);
	mysqli_close($connectGpwUser);
	
	// On recupere l'id base, nom et la description de la bdd selon l'id base de l'utilisateur
	$connectGpwBD = mysqli_connect($server, $db_user, $db_pass, $database);
	if (!$connectGpwBD) {
		die('Impossible de se connecter: '.mysqli_connect_error());
	}
	if(empty($_SESSION['superviseurIdBd'])) {
		$queryGpwBD = mysqli_query($connectGpwBD,"SELECT Id_Base, NomBase,DescriptionBase FROM gpwbd WHERE Id_Base = ".$idBase);
	}else{
		$queryGpwBD = mysqli_query($connectGpwBD,"SELECT Id_Base, NomBase,DescriptionBase FROM gpwbd WHERE Id_Base = ".$_SESSION['superviseurIdBd']);
	}
	$assocGpwBD = mysqli_fetch_assoc($queryGpwBD);
	$nomBase = $assocGpwBD['NomBase'];
	$descriptionBase = $assocGpwBD['DescriptionBase'];
	$idBase = $assocGpwBD['Id_Base'];

    //Affichage
	echo "NomBDD: ".$nomBase;
	echo "ipBDD: ".$descriptionBase;
	echo "idBase: ".$idBase;

	mysqli_free_result($queryGpwBD);
	mysqli_close($connectGpwBD);
	
	
?>
					