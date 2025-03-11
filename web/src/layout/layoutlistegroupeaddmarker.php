	<?php

	/*
	 * Permet la recuperation de données par AJAX pour afficher les markers balises du groupes selectionnés
	 * Appelé par la fonction javascript du fichier layout.js addListeGroupeMarkers()
	 */


	set_time_limit(0);
		session_start();

		$idGpw = $_GET["idGpw"];
		include '../dbgpw.php';
		$connectGpwBalise = mysqli_connect($server, $db_user, $db_pass,$database);
		mysqli_set_charset($connectGpwBalise, "utf8");
		$queryGpwBalise = mysqli_query($connectGpwBalise,"SELECT Id_Balise, Nom_Balise FROM gpwbalise WHERE Id_GPW = '$idGpw'");
		$rowCount = mysqli_num_rows($queryGpwBalise);
		while($fetchGpwBalise = mysqli_fetch_array($queryGpwBalise)){
			echo "t".$rowCount."g";
			echo "Id_Balise:".$fetchGpwBalise['Id_Balise'];
			echo "Nom_Balise:".$fetchGpwBalise['Nom_Balise']. "&";
		}
		mysqli_free_result($queryGpwBalise);
		mysqli_close($connectGpwBalise);

	?> 


