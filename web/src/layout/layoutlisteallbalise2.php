<style type="text/css">
	li { cursor: pointer; cursor: hand; }
</style>
<?php
/*
 * Pour l'affiche d'un contenu HTML pour afficher la liste de ALL GROUP en mobile
 * Appelé par la fonction javascript du fichier layout.js addListeAllBalise2()
 */

	include '../dbgpw.php';
	include '../dbconnect2.php';
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

	$_SESSION['username'];
	$nomBase = $_GET["nomBase"];
	$idClient = $_GET["idClient"];


	$i=0;
	/*************** Affichage des données ttrackers *********************/
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

	echo "<li > <center style='color:white'><h4>"; echo _('layout_listbalise'); echo "</h4>";
	echo '<select id="geo3x_select" class="geo3x_input_datetime" id="listResponsive" style="color:black; width: 200px" onchange="getBalise2(this.value,this.options[this.selectedIndex].innerHTML)">';
	echo '  <option disabled selected>'; echo _('selectionnerbalise'); echo '</option>';
	while($arrayGpwBalise = mysqli_fetch_array($queryGpwBalise)){
		echo '<option value="'.$arrayGpwBalise['Id_tracker'].'">'.$arrayGpwBalise['Nom_tracker'].'</option>';
		$i++;
	}
	echo "</select></center></li><br>";
	mysqli_free_result($queryGpwBalise);
	mysqli_close($connectGpwBalise);
?>
