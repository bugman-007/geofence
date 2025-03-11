
	<?php

	/*
	 * Pour l'affiche d'un contenu HTML pour afficher la liste de balise en mobile
	 * Afficher par un include dans la page layout.php
	 */

		include '../dbgpw.php';

	session_start();
	$_SESSION['username'];

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



		$idGPW = $_GET["idGPW"];
		

	
		
		/********* Recuperer les Id_Balise selon l'Id_GPW de l'utilisateur ************/

		$i=0;
		$connectGpwBalise = mysqli_connect($server, $db_user, $db_pass,$database);
		if (!$connectGpwBalise) {
			die('Impossible de se connecter: '.mysqli_connect_error());
		}
		mysqli_set_charset($connectGpwBalise, "utf8");
		$queryGpwBalise = mysqli_query($connectGpwBalise,"SELECT Id_Balise,Nom_Balise FROM gpwbalise WHERE id_GPW = ".$idGPW." ORDER BY Nom_Balise");

	// Affichage
		echo "<li > <center style='color:white'><h4>"; echo _('layout_listbalise'); echo ": </h4>";
		echo '<select class="geo3x_input_datetime" id="listResponsive" style="color:black; width: 200px" onchange="getBalise2(this.value,this.options[this.selectedIndex].innerHTML)">';
		echo '  <option disabled selected> -- '; echo _('selectionnerbalise'); echo ' -- </option>';
		while($arrayGpwBalise = mysqli_fetch_array($queryGpwBalise)){
			$arrayIdBalise[$i] = $arrayGpwBalise['Id_Balise'];
			$arrayNomBalise[$i] = $arrayGpwBalise['Nom_Balise'];
			if($arrayIdBalise[$i]) {
				echo '<option value="' . $arrayGpwBalise['Id_Balise'] . '">' . $arrayGpwBalise['Nom_Balise'] . '</option>';
				$i++;
			}
		}
		echo "</select></center></li><br>";
		mysqli_free_result($queryGpwBalise);
		mysqli_close($connectGpwBalise);
	?>
