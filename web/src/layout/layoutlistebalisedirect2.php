
<?php

/*
 * Pour l'affiche d'un contenu HTML pour afficher la liste de balise direct au lancement de l'application en mobile
 *Afficher par un include dans la page layout.php
 */

	include '../dbgpw.php';
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
	echo "<li > <center style='color:white'><h4>"; echo _('layout_groupebalise'); echo ": </h4>";
	/************* Recuperer l'Id_Base, Id_GPW, NomGPW de l'utilisateur *******************/
	$connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
	mysqli_set_charset($connectGpwUser, "utf8");
	if (!$connectGpwUser) {
		die('Impossible de se connecter: '.mysqli_connect_error());
	}
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

	/***************** Recuperer tout les gpw selon l'idbase *************************/
	$connectGpw= mysqli_connect($server, $db_user, $db_pass, $database);
	if (!$connectGpw) {
		die('Impossible de se connecter: '.mysqli_connect_error());
	}
	if(empty($_SESSION['superviseurIdBd'])) {
		$queryGpw= mysqli_query($connectGpw,"SELECT Id_GPW, NomGPW FROM gpw WHERE Id_Base = ".$idBase." ORDER BY NomGPW ");
	}else{
		$queryGpw= mysqli_query($connectGpw,"SELECT Id_GPW, NomGPW FROM gpw WHERE Id_Base = ".$_SESSION['superviseurIdBd']." ORDER BY NomGPW ");
	}
	$iGpw=0;
	while($fetchGpw = mysqli_fetch_array($queryGpw)){
		$arrayNomGpw[$iGpw] = $fetchGpw['NomGPW'];
		$arrayIdGpw[$iGpw] = $fetchGpw['Id_GPW'];
		$iGpw++;
	}
	mysqli_free_result($queryGpw);
	mysqli_close($connectGpw);
	/***************** Recuperer tout les gpw selon l'idClient *************************/
	$connectGpwClient= mysqli_connect($server, $db_user, $db_pass,$database);
	if (!$connectGpwClient) {
		die('Impossible de se connecter: '.mysqli_connect_error());
	}

	$queryGpwClient= mysqli_query($connectGpwClient,"SELECT Id_GPW, NomGPW FROM gpw WHERE Id_Base = ".$idBase." AND Id_Client = ".$idClientGpwUser." ORDER BY NomGPW ");
	$iGpwClient=0;
	while($fetchGpwClient = mysqli_fetch_array($queryGpwClient)){
		$arrayNomGpwClient[$iGpwClient] = $fetchGpwClient['NomGPW'];
		$arrayIdGpwClient[$iGpwClient] = $fetchGpwClient['Id_GPW'];
		$iGpwClient++;
	}
	mysqli_free_result($queryGpwClient);
	mysqli_close($connectGpwClient);

	/***************** On recuère tous les idgpw selon le type d'utilisateur *************************/
	if( !empty($_SESSION['superviseurIdBd'])){
		$idGPW = join(',',$arrayIdGpw);
	}else{
		if( $superviseurGpwUser == "2"){
			if($idClientGpwUser == "-1"){
				$idGPW = join(',',$arrayIdGpw);
			}else{
				$idGPW = join(',',$arrayIdGpwClient);
			}
		}else{
			$idGPW = join(',',$arrayIdGpwUser);
		}
	}


/***************** On recupère les id et nom des balises *************************/
	$connectGpwBalise = mysqli_connect($server, $db_user, $db_pass,$database);
	if (!$connectGpwBalise) {
		die('Impossible de se connecter: '.mysqli_connect_error());
	}
	mysqli_set_charset($connectGpwBalise, "utf8");
	$queryGpwBalise = mysqli_query($connectGpwBalise,"SELECT Id_Balise,Nom_Balise FROM gpwbalise WHERE id_GPW IN ($idGPW) ORDER BY Nom_Balise");

/***************** Affichage *************************/
	echo '<select class="geo3x_input_datetime" id="listResponsive" style="color:black; width: 200px" onchange="getBalise2(this.value,this.options[this.selectedIndex].innerHTML)">';
	echo '  <option disabled selected> -- '; echo _('selectionnerbalise'); echo ' -- </option>';
	while($arrayGpwBalise = mysqli_fetch_array($queryGpwBalise)) {
		if (!(in_array($arrayGpwBalise['Id_Balise'], $arrayIdBalise))) {
			$arrayIdBalise[$i] = $arrayGpwBalise['Id_Balise'];
			$arrayNomBalise[$i] = $arrayGpwBalise['Nom_Balise'];

			if($arrayIdBalise[$i]) {

				echo '<option value="' . $arrayGpwBalise['Id_Balise'] . '">' . $arrayGpwBalise['Nom_Balise'] . '</option>';

			}
			$i++;
		}

	}
	echo "</select></center></li><br>";
	mysqli_free_result($queryGpwBalise);
	mysqli_close($connectGpwBalise);
?>
