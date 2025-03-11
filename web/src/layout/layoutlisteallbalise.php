
<style type="text/css">
	li { cursor: pointer; cursor: hand; }
</style>
<?php

/*
 * Pour l'affiche d'un contenu HTML pour afficher la liste de ALL GROUP
 * Appelé par la fonction javascript du fichier layout.js addListeAllBalise()
 */

	include '../dbgpw.php';
	include '../dbconnect2.php';
	session_start();
	$_SESSION['username'];
	$nomBase = $_GET["nomBase"];
	$idClient = $_GET["idClient"];

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

	/*************** Affichage des données ttrackers *********************/
	$arrayIdBalise = array();
	$i=0;
	$connectGpwBalise = mysqli_connect($server, $db_user_2, $db_pass_2,$nomBase);
	if (!$connectGpwBalise) {
		die('Impossible de se connecter: '.mysqli_connect_error());
	}
	mysqli_set_charset($connectGpwBalise, "utf8");
	if( !empty($_SESSION['superviseurIdBd'])) {

		if(!empty($_GET["idClient"])) {
			if ($idClient != "-1")
				$queryGpwBalise = mysqli_query($connectGpwBalise, "SELECT * FROM ttrackers WHERE Client = '$idClient' ORDER BY Nom_tracker");
			else
				$queryGpwBalise = mysqli_query($connectGpwBalise, "SELECT * FROM ttrackers ORDER BY Nom_tracker");
		}else
			$queryGpwBalise = mysqli_query($connectGpwBalise, "SELECT * FROM ttrackers ORDER BY Nom_tracker");

	} else
		$queryGpwBalise = mysqli_query($connectGpwBalise,"SELECT * FROM ttrackers WHERE Client = '$idClient' ORDER BY Nom_tracker");

	echo '<ul class="list-group" style="color: black; height:200px; width:275px  ;text-align: left;"> ';
	echo '<li style="padding-left: 10px;font-size:12px" id="cocher_liste_balise" href="#" class="list-group-item" onclick="baliseSelectAll()" >'; echo _('layout_toutcocher'); echo "</li>";
	echo '<li style="padding-left: 10px;font-size:12px" id="decocher_liste_balise" href="#" class="list-group-item" onclick="baliseUnSelectAll()">'; echo _('layout_toutdecocher'); echo "</li>";
	while($arrayGpwBalise = mysqli_fetch_array($queryGpwBalise)){
		$arrayIdBalise[$i] = $arrayGpwBalise['Id_tracker'];
		echo '<li style="padding-left: 10px; font-size:12px" id="id_liste_balise" href="#" class="list-group-item" onclick="getOneBalise(\''.$arrayGpwBalise['Id_tracker'].'\',\''.$arrayGpwBalise['Nom_tracker'].'\',event)">'.
			'<input type="checkbox" class="pull-left" id="'.$arrayGpwBalise['Id_tracker'].'" name="'.$arrayGpwBalise['Nom_tracker'].'"  style="margin-top: 2px" onclick="getOneBaliseCheckbox(\''.$arrayGpwBalise['Id_tracker'].'\',\''.$arrayGpwBalise['Nom_tracker'].'\')">&nbsp;'.$arrayGpwBalise['Nom_tracker'].'&nbsp;<i style=" font-weight:normal;">(ID = '. $arrayGpwBalise['Id_tracker'].')</i></li>';
		$i++;
	}
	mysqli_free_result($queryGpwBalise);
	mysqli_close($connectGpwBalise);
?>
