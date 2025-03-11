
<?php
/*
 * Pour l'affiche d'un contenu HTML pour afficher la liste de groupes à la selection de ALL CLIENT
 * Appelé par la fonction javascript du fichier layout.js changeClient()
 */
	include '../dbgpw.php';
	session_start();

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

	// /*************** Recuperer la session avec le login de l'utilisateur ************/
	$_SESSION['username'];
	/************* Recuperer l'Id_Base, Id_GPW, NomGPW de l'utilisateur *******************/
	$connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
	if (!$connectGpwUser) {
		die('Impossible de se connecter: '.mysqli_connect_error());
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

	/********* Recuperer les Id_groupe selon l'Id_GPW de l'utilisateur ************/
	$arrayIdGroupe = array();
	$iGpwBalise=0;
	$connectGpwBalise = mysqli_connect($server, $db_user, $db_pass,$database);
	if (!$connectGpwBalise) {
		die('Impossible de se connecter: '.mysqli_connect_error());
	}
	mysqli_set_charset($connectGpwBalise, "utf8");
	$queryGpwBalise = mysqli_query($connectGpwBalise,"SELECT Id_Balise,Id_groupe FROM gpwbalise WHERE id_GPW = ".$idGpwUser);
	while($fetchGpwBalise = mysqli_fetch_array($queryGpwBalise)){
		$arrayIdGroupe[$iGpwBalise] = $fetchGpwBalise['Id_groupe'];
		$iGpwBalise++;
	}
	mysqli_free_result($queryGpwBalise);
	mysqli_close($connectGpwBalise);

	/***************** Recuperer tout les gpw selon l'idbase *************************/
	$connectGpw= mysqli_connect($server, $db_user, $db_pass, $database);
	if (!$connectGpw) {
		die('Impossible de se connecter: '.mysqli_connect_error());
	}
	mysqli_set_charset($connectGpw, "utf8");
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
	$connectGpwClient= mysqli_connect($server, $db_user, $db_pass, $database);
	if (!$connectGpwClient) {
		die('Impossible de se connecter: '.mysqli_connect_error());
	}
	mysqli_set_charset($connectGpwClient, "utf8");
	$queryGpwClient= mysqli_query($connectGpwClient,"SELECT Id_GPW, NomGPW FROM gpw WHERE Id_Base = ".$idBase." AND Id_Client = ".$idClientGpwUser." ORDER BY NomGPW ");
	$iGpwClient=0;
	while($fetchGpwClient = mysqli_fetch_array($queryGpwClient)){
		$arrayNomGpwClient[$iGpwClient] = $fetchGpwClient['NomGPW'];
		$iGpwClient++;
	}
	mysqli_free_result($queryGpwClient);
	mysqli_close($connectGpwClient);
	echo '<ul id="testigroupe" class="list-group" style="color: black; height:200px;text-align: left;"> ';
	$TextAllGroups = _('layout_allgroups');
	echo '<a style="padding-left: 10px;font-size:12px" href="#" class="list-group-item" onclick="addListeAllBalise(\''.$idClientGpwUser.'\',this,\''.$TextAllGroups.'\')" >'.$TextAllGroups.'</a>';
	// /******** Afficher la liste groupe selon la base de donn�e de l'utilisateur*******/
	$i=0;

	if( !empty($_SESSION['superviseurIdBd'])){
		foreach ($arrayNomGpw as $val) {
			echo '<a style="padding-left: 10px;font-size:12px" href="#" class="list-group-item" onclick="addListeBalise(\''.$val.'\',\''.$arrayIdGpw[$i].'\',this,\''.$val.'\')" >  '.$val.'</a>';
			$i++;
		}
	}else{
	if( $superviseurGpwUser == "2"){
			if($idClientGpwUser == "-1"){
				foreach ($arrayNomGpw as $val) {
					echo '<a style="padding-left: 10px; font-size:12px" id="id_liste_groupe" href="#" class="list-group-item" onclick="addListeBalise(\''.$idClientGpwUser.'\',\''.$arrayIdGpw[$i].'\',this,\''.$val.'\')" > '.$val.'</a>';
					$i++;
				}
			}else{
			foreach ($arrayNomGpwClient as $val) {
					echo '<a style="padding-left: 10px; font-size:12px" id="id_liste_groupe" href="#" class="list-group-item" onclick="addListeBalise(\''.$idClientGpwUser.'\',\''.$arrayIdGpw[$i].'\',this,\''.$val.'\')" > '.$val.'</a>';
					$i++;
				}
			}
		}else{
		//Client
			foreach ($arrayNomGpwUser as $val) {
				echo '<a style="padding-left: 10px; font-size:12px" id="id_liste_groupe" href="#" class="list-group-item" onclick="addListeBalise(\''.$idClientGpwUser.'\',\''.$arrayIdGpwUser[$i].'\',\''.$idBase.'\',\''.$nomBase.'\',\''.$descriptionBase.'\',this,\''.$val.'\')" > '.$val.'</a>';
				$i++;
			}
		}
	}


?>

	<script>
$('#testigroupe > a').click(function(){
 // $('#liRapport').addClass('active') ;
$(this).parent().parent().find('.active').removeClass('active');
 $(this).addClass('active');
})


</script>
