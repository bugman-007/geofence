<style type="text/css">
	li { cursor: pointer; cursor: hand; }
</style>	<?php
	/*
	 * Pour l'affiche d'un contenu HTML pour afficher la liste de balise
	 * Appelé par la fonction javascript du fichier layout.js addListeBalise()
	 */
		include '../dbgpw.php';
		include '../dbconnect2.php';
                //include '../ChromePhp.php';
		$idGPW = $_GET["idGPW"];
		// ChromePhp::log($idGPW); 
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

		/********* Recuperer les Id_Balise selon l'Id_GPW de l'utilisateur ************/
		$arrayIdBalise = array();
		$arrayNomBalise = array();
		$i=0;
		$connectGpwBalise = mysqli_connect($server, $db_user, $db_pass,$database);
		if (!$connectGpwBalise) {
			die('Impossible de se connecter: '.mysqli_connect_error());
		}
		mysqli_set_charset($connectGpwBalise, "utf8");
		$queryGpwBalise = mysqli_query($connectGpwBalise,"SELECT Id_Balise,Nom_Balise FROM gpwbalise WHERE id_GPW = ".$idGPW." ORDER BY Nom_Balise");
   
		/* Affichage */
		echo '<ul  class="list-group" style="color: black; max-height:200px; width:275px ;text-align: left;"> ';
	
		echo '<li style="padding-left: 10px;font-size:12px" id="cocher_liste_balise" href="#" class="list-group-item" onclick="baliseSelectAll(this)" >'; echo _('layout_toutcocher'); echo "</li>";
		echo '<li style="padding-left: 10px;font-size:12px" id="decocher_liste_balise" href="#" class="list-group-item" onclick="baliseUnSelectAll(this)">'; echo _('layout_toutdecocher'); echo "</li>";
		echo '<li></li>';
		while($arrayGpwBalise = mysqli_fetch_array($queryGpwBalise)){
			$arrayIdBalise[$i] = $arrayGpwBalise['Id_Balise'];
			$arrayNomBalise[$i] = $arrayGpwBalise['Nom_Balise'];
			if($arrayIdBalise[$i]) {
				echo '<li   style="padding-left: 10px; font-size:12px;" id="id_liste_balise"  class="list-group-item" onclick="getOneBalise(\'' . $arrayIdBalise[$i] . '\',\'' . $arrayNomBalise[$i] . '\',event)">' .
					'<input type="checkbox" class="pull-left" id="' . $arrayIdBalise[$i] . '" name="' . $arrayNomBalise[$i] . '" style="margin-top: 2px" onclick="getOneBaliseCheckbox(\'' . $arrayIdBalise[$i] . '\',\'' . $arrayNomBalise[$i] . '\')">&nbsp;' . $arrayNomBalise[$i] . '&nbsp;<i style=" font-weight:normal;" >(ID = '. $arrayIdBalise[$i].')</i></li>';
			}
			$i++;
		}
                // ChromePhp::log($arrayGpwBalise); 
                //    ChromePhp::log($arrayIdBalise);    
                //ChromePhp::log($arrayNomBalise);   
		mysqli_free_result($queryGpwBalise);
		mysqli_close($connectGpwBalise);
		
	
	?>
