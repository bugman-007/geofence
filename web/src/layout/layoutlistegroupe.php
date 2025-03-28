<?php
	/*
	 * Pour l'affiche d'un contenu HTML pour la liste des groupes
	 * Afficher par un include dans la page layout.php
	 */

		include '../dbgpw.php';
        // include '../ChromePhp.php';
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
		mysqli_set_charset($connecGpwBalise, "utf8");
		$queryGpwBalise = mysqli_query($connectGpwBalise,"SELECT Id_Balise,Id_groupe FROM gpwbalise WHERE id_GPW = ".$idGpwUser);
		while($fetchGpwBalise = mysqli_fetch_array($queryGpwBalise)){
			$arrayIdGroupe[$iGpwBalise] = $fetchGpwBalise['Id_groupe'];
			$iGpwBalise++;
		}
		mysqli_free_result($queryGpwBalise);
		mysqli_close($connectGpwBalise);
		
		/***************** Recuperer tout les gpw selon l'idbase *************************/
		$connectGpw= mysqli_connect($server, $db_user, $db_pass, $database);
		mysqli_set_charset($connectGpw, "utf8");
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
                
        // ChromePhp::log($arrayNomGpwClient);
		// ChromePhp::log($arrayIdGpwClient);
		mysqli_free_result($queryGpwClient);
		mysqli_close($connectGpwClient);
		// /******** Afficher la liste groupe selon la base de donnee de l'utilisateur*******/
		$i=0;
		
		$TextAllGroups = _('layout_allgroups');
		if( !empty($_SESSION['superviseurIdBd'])){
			echo '<a style="padding-left: 10px; font-size: 12px " id="id_liste_groupe" href="#" class="list-group-item  " onclick="addListeAllBalise(\''.$idClientGpwUser.'\',this,\''.$TextAllGroups.'\')" >'.$TextAllGroups.'</a>';
			foreach ($arrayNomGpw as $val) {

				echo '<a style="padding-left: 10px; font-size:12px" id="'.$arrayIdGpw[$i].'"  name="'.$val.'" href="#" class="list-group-item" onclick="addListeBalise(\''.$idClientGpwUser.'\',\''.$arrayIdGpw[$i].'\',this,\''.$val.'\')" >'.$val.'</a>';
				$i++;
			}
		}else{
			//Chef Client
			if( $superviseurGpwUser == "2"){
				if($idClientGpwUser == "-1"){
					echo '<a style="padding-left: 10px; font-size:12px" id="id_liste_groupe" href="#" class="list-group-item" onclick="addListeAllBalise(\''.$idClientGpwUser.'\',this,\''.$TextAllGroups.'\')" >'.$TextAllGroups.'</a>';
					// foreach ($arrayNomGpwClient as $val) {
					foreach ($arrayNomGpw as $val) {
						echo '<a style="padding-left: 10px; font-size:12px" id="'.$arrayIdGpw[$i].'" name="'.$val.'" href="#" class="list-group-item" onclick="addListeBalise(\''.$idClientGpwUser.'\',\''.$arrayIdGpw[$i].'\',this,\''.$val.'\')" > '.$val.'</a>';
						$i++;
					}
				}else{
					echo '<a style="padding-left: 10px; font-size:12px" id="id_liste_groupe" href="#" class="list-group-item" onclick="addListeAllBalise(\''.$idClientGpwUser.'\',this,\''.$TextAllGroups.'\')" >'.$TextAllGroups.'</a>';
					foreach ($arrayNomGpwClient as $val) {
					// foreach ($arrayNomGpwUser as $val) {
						echo '<a style="padding-left: 10px; font-size:12px" id="'.$arrayIdGpwClient[$i].'" name="'.$val.'" href="#" class="list-group-item" onclick="addListeBalise(\''.$idClientGpwUser.'\',\''.$arrayIdGpwClient[$i].'\',this,\''.$val.'\')" > '.$val.'</a>';
						$i++;
					}
				}
			}else{
			//Client
				foreach ($arrayNomGpwUser as $val) {
					echo '<a style="padding-left: 10px; font-size:12px" id="'.$arrayIdGpwUser[$i].'" name="'.$val.'" href="#" class="list-group-item" onclick="addListeBalise(\''.$idClientGpwUser.'\',\''.$arrayIdGpwUser[$i].'\',this,\''.$val.'\')" > '.$val.'</a>';
					$i++;
				}
			}
		}

		
	?> 


