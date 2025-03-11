<?php

/*
 * Pour l'affiche d'un contenu HTML pour la liste des clients pour mobile
 * Afficher par un include dans la page layout.php
 */
	
	/*Remise à 0 du compteurs de temps pour la deconnexion automatique*/
	$_SESSION['CREATED'] = time();

	/*Bibliotheque pour l'internationalisation et mise en place*/
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

	/************* Recuperer l'Id_Base, Id_GPW, NomGPW de l'utilisateur *******************/
	$connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
	if (!$connectGpwUser) {
		die('Impossible de se connecter');
	}
	mysqli_set_charset($connectGpwUser, "utf8");
	$queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Base,Id_GPW, NomGPW, Superviseur, Id_Client FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' ) ORDER BY NomGPW"); //AND Id_GPW != 0
	$assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
	$idBase = $assocGpwUser['Id_Base'];
	$idGpwUser = $assocGpwUser['Id_GPW'];
	$superviseurGpwUser = $assocGpwUser['Superviseur'];
	$idClientGpwUser = $assocGpwUser['Id_Client'];
	$arrayIdGpwUser = array();
	$arrayNomGpwUser = array();
	$iGpwUser = 0;
	while($fetchGpwUser = mysqli_fetch_array($queryGpwUser)){
		$arrayNomGpwUser[$iGpwUser] = $fetchGpwUser['NomGPW'];
		$arrayIdGpwUser[$iGpwUser] = $fetchGpwUser['Id_GPW'];
		$iGpwUser++;
	}
	mysqli_free_result($queryGpwUser);
	mysqli_close($connectGpwUser);
	
	
	$TextAllClient = _('layout_allclient');
	
	/*  If superviseur = 2 et idClient -1 */
	if( $superviseurGpwUser == "2" && $idClientGpwUser == "-1"){
		echo "<li > <center style='color:white '><h4>"; echo _('layout_client'); echo " : </h4>";

		/***********************Recuperer le nom et la description de la base ****************************/
		$connectGpwBD = mysqli_connect($server, $db_user, $db_pass,$database);
		if (!$connectGpwBD) {
			die('Impossible de se connecter');
		}

		$queryGpwBD = mysqli_query($connectGpwBD,"SELECT NomBase,DescriptionBase FROM gpwbd WHERE Id_Base = ".$idBase);

		$assocGpwBD = mysqli_fetch_assoc($queryGpwBD);
		$nomBase = $assocGpwBD['NomBase'];
		$descriptionBase = $assocGpwBD['DescriptionBase'];
		mysqli_free_result($queryGpwBD);
		mysqli_close($connectGpwBD);

		/******************recupere les infos tclients********************/
		$connectClient = mysqli_connect($descriptionBase, $db_user_2, $db_pass_2,$nomBase);
		if (!$connectClient) {
			die('Impossible de se connecter');
		}
		mysqli_set_charset($connectClient, "utf8");
		//affichage
		$queryClient = mysqli_query($connectClient,"SELECT * FROM tclients ORDER BY Nom_Client");
		echo '<select class="geo3x_select" style="color:black" onchange="changeClient(this.value);">';
		echo '<option value="all">'.$TextAllClient.'</option>';
		while($rowClient = mysqli_fetch_array($queryClient)){
			echo '<option value="'.$rowClient['Id_Client'].'">'.$rowClient['Nom_Client'].'</option>';
		}
		echo '</select>';
		echo "</center></li><br>";
		mysqli_free_result($queryClient);
		mysqli_close($connectClient);
	}
	/* Si ce n'est pas un superviseur 2 mais un superviseur 1 */
	else if( !empty($_SESSION['superviseurIdBd']))
	{
		/***********************Recuperer le nom et la description de la base ****************************/
		$connectGpwBD = mysqli_connect($server, $db_user, $db_pass,$database);
		if (!$connectGpwBD) {
			die('Impossible de se connecter');
		}
		$queryGpwBD = mysqli_query($connectGpwBD,"SELECT NomBase,DescriptionBase FROM gpwbd WHERE Id_Base = ".$_SESSION['superviseurIdBd']);
		$assocGpwBD = mysqli_fetch_assoc($queryGpwBD);
		$nomBase = $assocGpwBD['NomBase'];
		$descriptionBase = $assocGpwBD['DescriptionBase'];
		mysqli_free_result($queryGpwBD);
		mysqli_close($connectGpwBD);

		//Affichage
		$connectClient = mysqli_connect($descriptionBase, $db_user_2, $db_pass_2,$nomBase);
		if (!$connectClient) {
			die('Impossible de se connecter');
		}
		$queryClient = mysqli_query($connectClient,"SELECT * FROM tclients ORDER BY Nom_Client");
		echo "<li > <center style='color:white '><h4>"; echo _('layout_client'); echo " : </h4>";
		echo '<select class="geo3x_input_datetime" style="color:black" onchange="changeClient2(this.value);">';
		echo '<option value="all">'.$TextAllClient.'</option>';
		while($rowClient = mysqli_fetch_array($queryClient)){
			echo '<option value="'.$rowClient['Id_Client'].'">'.$rowClient['Nom_Client'].'</option>';
		}
		echo '</select>';
		echo "</center></li><br>";
		mysqli_free_result($queryClient);
		mysqli_close($connectClient);

	}
?>
