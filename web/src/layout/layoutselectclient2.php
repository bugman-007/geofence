<?php
/*
 * Pour l'affiche d'un contenu HTML pour afficher la liste de groupes à la selection d'un client précis en mobile
 * Appelé par la fonction javascript du fichier layout.js changeClient2()
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


	$idClient = $_GET['idClient'];


	/*****************Recuperer les infos  GPW de l'utilisateur ****************/
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
	// $idClientGpwUser = $assocGpwUser['Id_Client'];
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
	$connectGpw= mysqli_connect($server, $db_user, $db_pass,$database);
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
	/******************Recupérer l'id et le nom du  GPW ************************/
	$connectGpwClient= mysqli_connect($server, $db_user, $db_pass,$database);
	if (!$connectGpwClient) {
		die('Impossible de se connecter: '.mysqli_connect_error());
	}
	if( !empty($_SESSION['superviseurIdBd'])){
		$queryGpwClient = mysqli_query($connectGpwClient,"SELECT Id_GPW, NomGPW FROM gpw WHERE Id_Base = ".$_SESSION['superviseurIdBd']." AND Id_Client = ".$idClient." ORDER BY NomGPW ");
	}else{
		$queryGpwClient = mysqli_query($connectGpwClient,"SELECT Id_GPW, NomGPW FROM gpw WHERE Id_Base = ".$idBase." AND Id_Client = ".$idClient." ORDER BY NomGPW ");
	}
	$iGpwClient=0;
	while($fetchGpwClient = mysqli_fetch_array($queryGpwClient)){
		$arrayNomGpwClient[$iGpwClient] = $fetchGpwClient['NomGPW'];
		$arrayIdGpwClient[$iGpwClient] = $fetchGpwClient['Id_GPW'];
		$iGpwClient++;
	}
	mysqli_free_result($queryGpwClient);
	mysqli_close($connectGpwClient);


	$i=0;

    //Affichage de la liste des balises selon le clients
    echo "<li > <center style='color:white'><h4>"; echo _('layout_groupebalise'); echo ": </h4>";
	echo '<select style="color:black; width: 200px" class="geo3x_input_datetime" onchange="addListeBalise2(\''.$idClient.'\',this.value,this.options[this.selectedIndex].innerHTML);">';
	echo '  <option disabled selected> -- '; echo _('selectionnergroupe'); echo ' -- </option>';
	$TextAllGroups = _('layout_allgroups');
	echo '<option value="all">'.$TextAllGroups.'</option>';
	if(!empty($arrayNomGpwClient)){
		if( !empty($_SESSION['superviseurIdBd'])){
			foreach ($arrayNomGpwClient as $val) {
				echo '<option value="'.$arrayIdGpwClient[$i].'">'.$val.'</option>';
				$i++;
			}
		}else{
			if( $superviseurGpwUser == "2"){
				if($idClient == "-1"){
					foreach ($arrayNomGpwClient as $val) {
						echo '<option value="' . $arrayIdGpwClient[$i] . '">' . $val . '</option>';
						// echo '<a style="padding-left: 10px;font-size:12px" id="id_liste_groupe" href="#" class="list-group-item" onclick="addListeBalise(\''.$val.'\',\''.$arrayIdGpwClient[$i].'\',\''.$idBase.'\',\''.$nomBase.'\',\''.$descriptionBase.'\',this)" > '.$val.'</a>';
						$i++;
					}
				}else{
					foreach ($arrayNomGpwClient as $val) {
						echo '<option value="' . $arrayIdGpwClient[$i] . '">' . $val . '</option>';
						// echo '<a style="padding-left: 10px; font-size:12px" id="id_liste_groupe" href="#" class="list-group-item" onclick="addListeBalise(\''.$idClient.'\',\''.$arrayIdGpw[$i].'\',\''.$idBase.'\',\''.$nomBase.'\',\''.$descriptionBase.'\',this)" > '.$val.'</a>';
						$i++;
					}
				}
			}else{
				//Client
				foreach ($arrayNomGpwUser as $val) {
					echo '<option value="' . $arrayIdGpwUser[$i] . '">' . $val . '</option>';
					// echo '<a style="padding-left: 10px; font-size:12px" id="id_liste_groupe" href="#" class="list-group-item" onclick="addListeBalise(\''.$idClient.'\',\''.$arrayIdGpwUser[$i].'\',\''.$idBase.'\',\''.$nomBase.'\',\''.$descriptionBase.'\',this)" > '.$val.'</a>';
					$i++;
				}
			}
		}
	}
	echo '<select></center></li><br>';


?>