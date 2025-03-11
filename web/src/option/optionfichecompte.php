<?php

/*
* Afficher la fiche compte
*/


	include '../dbgpw.php';
	include '../dbconnect2.php';


	session_start();

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

	$idClient = $_GET["idClient"];
	$nomBase = $_GET["nomBase"];
	$idBase = $_GET["idBase"];
	$login = "";
	$mdp = "";
	$finValid = "";
	$nom = "";
	$prenom = "";
	$mdpSaisir = "";
	if(!empty($_GET['login'])){
		$login = $_GET['login'] ;
		/*********************************/
		$connectGpwUtilisateur = mysqli_connect($server, $db_user, $db_pass,$database);
		$queryGpwUtilisateur = mysqli_query($connectGpwUtilisateur,"SELECT * FROM gpwutilisateur WHERE (Login = '".$login."' AND Id_Base = '$idBase') "); //AND Id_GPW != 0
		$assocGpwUtilisateur= mysqli_fetch_assoc($queryGpwUtilisateur);
		$mdpSaisir = $assocGpwUtilisateur['MotPasseASaisir'];
		$mdp = $assocGpwUtilisateur['MotPasse'];
		mysqli_free_result($queryGpwUtilisateur);
		mysqli_close($connectGpwUtilisateur);
	}
	if(!empty($_GET['nom'])) $nom = $_GET['nom'] ;
	if(!empty($_GET['prenom'])) $prenom = $_GET['prenom'] ;


?>
	<form class="form-horizontal" role="form" style="font-size: 14px;">
		<div class="form-group">
			<div class="col-md-10" >
				&nbsp; &nbsp;<span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;
				<label class="control-label">
					<?php echo _('option_ficheutilisateur'); ?>:
				</label>
			</div>.
		</div>
		<div style="border: 0; height: 1px; background: #aaa; background-image: linear-gradient(to right, lightgray, lightgray, lightgray);"></div>
		<br/>
	</form>


<!--	<div class="col-md-12">-->
	<form class="form-horizontal" role="form" style="padding-left:20px">
		<div class="form-group"  >
			<div class="col-md-12">
				<b>&nbsp; 1) <?php echo _('option_saisirinfos'); ?>:</b>
			</div>
		</div>
		<div class="form-group" style="margin-bottom: 0px" >
			<div class="col-md-4" >
				<label  class="control-label" style="font-weight: normal">
					&nbsp;		Login:
				</label>
			</div>
			<div class="col-md-4" style="margin-top:8px">
				<?php
				if(!empty($_GET['login'])){
					echo '<input class="form-control input-xs" id="compte_login"  type="text" value="'.$login.'" disabled onkeyup="this.value=this.value.toUpperCase()">';
				}else{
					echo '<input  class="form-control input-xs" id="compte_login"  type="text" value="'.$login.'" onkeyup="this.value=this.value.toUpperCase()">';
				}
				?>
			</div>
		</div>
		<div class="form-group"  style="margin-bottom: 0px">
			<div class="col-md-4">
				<label  class="control-label" style="font-weight: normal">
					&nbsp;		<?php echo _('option_nom');?>:
				</label>
			</div>
			<div class="col-md-4" style="margin-top:8px;">
				<?php
				if( !empty($_SESSION['superviseurIdBd'])){
					echo '<input  class="form-control input-xs" id="compte_nom" type="text" value="'.$nom.'" >';
				}else{
					echo '<input  class="form-control input-xs" id="compte_nom" type="text" value="'.$nom.'" disabled>';
				}
				?>
			</div>
		</div>
		<div class="form-group" style="margin-bottom: 0px" >
			<div class="col-md-4">
				<label  class="control-label" style="font-weight: normal">
					&nbsp;		<?php echo _('option_prenom');?>:
				</label>
			</div>
			<div class="col-md-4" style="margin-top:8px;">
				<?php
				if( !empty($_SESSION['superviseurIdBd'])){
					echo '<input  class="form-control input-xs" id="compte_prenom" type="text" value="'.$prenom.'" >';
				}else{
					echo '<input  class="form-control input-xs" id="compte_prenom" type="text" value="'.$prenom.'" disabled>';
				}
				?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-4" >
				<label  class="control-label" style="font-weight: normal">
					&nbsp;		<?php echo _('index_motdepasse');?>:
				</label>
			</div>
			<div class="col-md-4" style="margin-top:8px;">
				<input class="form-control input-xs" id="compte_mdp" type="password" value="<?php echo $mdp ?>" onkeyup="this.value=this.value.toUpperCase();uncheckedSaisieMdp();">
			</div>
			<div class="col-md-4" style="margin-top:8px;text-align: center">
				<input  class="btn btn-default btn-xs" id="mdp_visibilite" type="button" value="Visible" onClick="visibilitePassword()">
			</div>
		</div>
		<div class="form-group" >

				<?php
				if(!empty($_GET['login'])) {
					if ($mdpSaisir == "0"){
						echo '	<div class="col-md-6" style="text-align:center"><input type="CHECKBOX" name="saisieMdp" id="saisieMdp" onClick="deleteInputPwd()" >&nbsp;';
						echo _('option_saisirmdp');
					}
					else if ($mdpSaisir == "1"){
						echo '	<div class="col-md-6" style="text-align:center"><input type="CHECKBOX" name="saisieMdp" id="saisieMdp" onClick="deleteInputPwd()" checked>&nbsp;';
						echo _('option_saisirmdp');
					}
					echo "</div>";
					$connectGpwUser = mysqli_connect($server, $db_user, $db_pass, $database);
					$queryGpwUser = mysqli_query($connectGpwUser, "SELECT Id_GPW, NomGPW, Superviseur FROM gpwuser_gpw WHERE (Login = '" . $login. "' AND Id_GPW != '0') ORDER BY NomGPW"); //AND Id_GPW != 0
					$assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
					$superviseurGpwUser = $assocGpwUser['Superviseur'];
					mysqli_free_result($queryGpwUser);
					mysqli_close($connectGpwUser);

					$arrayIdGpwUser = array();
					$arrayNomGpwUser = array();
					$iGpwUser = 0;
					$connectGpwUser2 = mysqli_connect($server, $db_user, $db_pass, $database);
					$queryGpwUser2 = mysqli_query($connectGpwUser2, "SELECT Id_GPW, NomGPW, Superviseur FROM gpwuser_gpw WHERE (Login = '" . $login. "' AND Id_GPW != '0') ORDER BY NomGPW"); //AND Id_GPW != 0
					while($fetchGpwUser2 = mysqli_fetch_array($queryGpwUser2)){
						$arrayNomGpwUser[$iGpwUser] = $fetchGpwUser2['NomGPW'];
						$arrayIdGpwUser[$iGpwUser] = $fetchGpwUser2['Id_GPW'];
						$iGpwUser++;
					}
					mysqli_free_result($queryGpwUser2);
					mysqli_close($connectGpwUser2);

					if( !empty($_SESSION['superviseurIdBd'])) {
						if ($superviseurGpwUser == "2")
							echo ' <div class="col-md-6" style="text-align:center"><input type="CHECKBOX" name="compte_admin" id="compte_admin" onClick="" checked>&nbsp;  Admin';
						else
							echo ' <div class="col-md-6" style="text-align:center"><input type="CHECKBOX"  name="compte_admin" id="compte_admin" onClick="" >&nbsp;  Admin';
					}else{
						if ($superviseurGpwUser == "2")
							echo ' <div class="col-md-6" style="visibility:hidden"><input type="CHECKBOX" name="compte_admin" id="compte_admin" onClick="" checked>&nbsp;  Admin';
						else
							echo '<div class="col-md-6"  style="visibility:hidden"><input type="CHECKBOX" name="compte_admin" id="compte_admin" onClick="" >&nbsp;  Admin';
					}
					echo "</div>";
				}else{
					echo '	<div class="col-md-6" style="text-align:center"><input type="CHECKBOX" name="saisieMdp" id="saisieMdp" onClick="deleteInputPwd()" >&nbsp;'; echo _('option_saisirmdp');
					echo "</div>";
					if( !empty($_SESSION['superviseurIdBd'])) {
						echo '<div class="col-md-6" style="text-align:center" ><input type="CHECKBOX" name="compte_admin" id="compte_admin" onClick="" >&nbsp;  Admin';
					}else{
						echo '<div class="col-md-6" style="visibility:hidden;text-align:center"><input type="CHECKBOX" name="compte_admin" id="compte_admin" onClick="" >&nbsp;  Admin';
					}
					echo "</div>";
				}
				?>
		</div>

	</form>
<!--	</div>-->
<!--	<br/>-->
	<form class="form-horizontal" role="form" style="padding-left:20px">
		<div class="form-group" >
			<div class="col-md-8">
				<b>&nbsp; 2) <?php echo _('option_choisirdureecompte'); ?>:</b>
			</div>
			<div class="col-md-2">
				<select id="compte_duree"  class="form-control input-xs" >
					<?php
//					echo '<option value="aucun" disabled selected> -- </option>';
					for($i = 0 ; $i < 24 ; $i++ ) {
						$i2 = $i+1;
						echo "<option value='$i2' >$i2</option>";
					}
					?>
				</select>

			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2 col-md-offset-1" >
				<input type="radio" name="compte_type" id="type_heure" onClick="openDuree()" checked>&nbsp;<?php echo _('heure'); ?> &nbsp;&nbsp;
			</div>
			<div class="col-md-2" >
				<input type="radio" name="compte_type" id="type_jour" onClick="openDuree()" >&nbsp;<?php echo _('jour'); ?> &nbsp;&nbsp;
			</div>
			<div class="col-md-2" >
				<input type="radio" name="compte_type" id="type_semaine" onClick="openDuree()" >&nbsp;<?php echo _('semaine'); ?> &nbsp;&nbsp;
			</div>
			<div class="col-md-2" >
				<input type="radio" name="compte_type" id="type_mois" onClick="openDuree()" >&nbsp;<?php echo _('mois'); ?> &nbsp;&nbsp;
			</div>
			<div class="col-md-2" >
				<input type="radio" name="compte_type" id="type_illimite" onClick="closeDuree()" >&nbsp;<?php echo _('option_illimite'); ?> <br/>
			</div>
		</div>
		<div class="form-group" >
			<div class="col-md-12">
				<div class="col-md-6">
					<b>&nbsp;3) <?php echo _('option_envoyercomptemail'); ?>:</b>
				</div>
				<div class="col-md-5">
					<input class="form-control input-xs" id="compte_mail" type="text">
				</div>
			</div>
		</div>
	</form>

	<form class="form-horizontal" role="form" style="padding-left:20px">
		<div class="form-group" >
			<div class="col-md-12">
				<b>&nbsp; 4) <?php echo _('option_selectmodifiergroupebaliseassoc'); ?> :</b>
			</div>
		</div>
	</form>

<center>
<?php
echo '<div style="display: inline-block; height:200px; overflow:scroll;">';
echo '<table id="csourcetable" class="sortable table table-bordered table-hover" style="width: 200px">';
echo '<thead><tr><th style="width: 75px; display: none">'; echo _('idbalise'); echo'</th><th style="width: 150px">'; echo _('option_groupeexistant'); echo'</th></tr></thead>';
	/*********************************/
	$connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
	if (!$connectGpwUser) {
		die('Impossible de se connecter: '.mysqli_connect_error());
	}
	$queryGpwUser = mysqli_query($connectGpwUser,"SELECT Superviseur FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' ) ORDER BY NomGPW"); //AND Id_GPW != 0
	$assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
	$superviseurGpwUser = $assocGpwUser['Superviseur'];
	mysqli_free_result($queryGpwUser);
	mysqli_close($connectGpwUser);
	/******************************************/
	$connectGpwBD = mysqli_connect($server, $db_user, $db_pass,$database);
	$queryGpwBD = mysqli_query($connectGpwBD,"SELECT Id_Base FROM gpwbd WHERE NomBase = '$nomBase'");
	$assocGpwBD = mysqli_fetch_assoc($queryGpwBD);
	$idBase = $assocGpwBD['Id_Base'];
	mysqli_free_result($queryGpwBD);
	mysqli_close($connectGpwBD);
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
	if(!empty($arrayNomGpwClient)){
		if( !empty($_SESSION['superviseurIdBd'])){
			foreach ($arrayNomGpwClient as $val) {
				if(!empty($_GET['login'])) {
					if (!in_array($val, $arrayNomGpwUser)) {
						echo '<tr id="csour' . $arrayIdGpwClient[$i] . '" onclick="addRowDest(this)" ><td style="display:none">' . $arrayIdGpwClient[$i] . '</td><td>' . $val . '</td></tr>';
					}
				}else{
					echo '<tr id="csour' . $arrayIdGpwClient[$i] . '" onclick="addRowDest(this)" ><td style="display:none">' . $arrayIdGpwClient[$i] . '</td><td>' . $val . '</td></tr>';
				}
				$i++;
			}
		}else{
			if( $superviseurGpwUser == "2"){
				if($idClient == "-1"){
					foreach ($arrayNomGpwClient as $val) {
						if(!empty($_GET['login'])) {
							if (!in_array($val, $arrayNomGpwUser)) {
								echo '<tr id="csour' . $arrayIdGpwClient[$i] . '" onclick="addRowDest(this)" ><td style="display:none">' . $arrayIdGpwClient[$i] . '</td><td>' . $val . '</td></tr>';
							}
						}else{
							echo '<tr id="csour' . $arrayIdGpwClient[$i] . '" onclick="addRowDest(this)" ><td style="display:none">' . $arrayIdGpwClient[$i] . '</td><td>' . $val . '</td></tr>';
						}
						$i++;
					}
				}else{
					foreach ($arrayNomGpwClient as $val) {
						if(!empty($_GET['login'])) {
							if (!in_array($val, $arrayNomGpwUser)) {
								echo '<tr id="csour' . $arrayIdGpwClient[$i] . '" onclick="addRowDest(this)" ><td style="display:none">' . $arrayIdGpwClient[$i] . '</td><td>' . $val . '</td></tr>';
							}
						}else{
							echo '<tr id="csour' . $arrayIdGpwClient[$i] . '" onclick="addRowDest(this)" ><td style="display:none">' . $arrayIdGpwClient[$i] . '</td><td>' . $val . '</td></tr>';
						}
						$i++;
					}
				}
			}
//			else{
//				//Client
//				foreach ($arrayNomGpwClient as $val) {
//					if(!empty($_GET['login'])) {
//						if (!in_array($val, $arrayNomGpwUser)) {
//							echo '<tr id="csour' .  $arrayIdGpwUser[$i] . '" onclick="addRowDest(this)" ><td style="display:none">' . $arrayIdGpwUser[$i] . '</td><td>' . $val . '</td></tr>';
//						}
//					}else{
//						echo '<tr id="csour' .  $arrayIdGpwUser[$i] . '" onclick="addRowDest(this)" ><td style="display:none">' . $arrayIdGpwUser[$i] . '</td><td>' . $val . '</td></tr>';
//					}
//					$i++;
//				}
//			}
		}
	}
?>

	</table>
</div>


<div style="display: inline-block; height:200px; overflow:scroll;">

	<table id="cdestinationtable" class="sortable table table-bordered table-hover" style="width: 200px">
		<thead>
		<tr>
			<th style="width: 75px; display: none"><?php echo _('idbalise'); ?></th>
			<th style="width: 150px" ><?php echo _('option_groupeassoccompte'); ?></th>
		</tr>
		</thead>
		<?php
		$i=0;
		$y=0;
		if(!empty($_GET['login'])){
			foreach ($arrayNomGpwUser as $val) {
//				if( $val != "") {
					echo '<tr id="cdest' . $arrayIdGpwUser[$i] . '" onclick="addRowSource(this)" ><td style="display:none">' . $arrayIdGpwUser[$i] . '</td><td>' . $val . '</td></tr>';
					$y++;
//				}
				$i++;
			}
		}
		?>
	</table>

</div>
</center>

<br/>
	<form class="form-horizontal" role="form" style="padding-left:20px">
		<div class="form-group" >
			<div class="col-md-12">
				<b>&nbsp; 5) <?php echo _('option_choixconfigweb'); ?>:</b>
			</div>

			<div class="col-md-12">
<!--				<select id="compte_config" class="form-control input-xs">-->
<!--					--><?php
//					/************* Recuperer la configuration de l'utilisateur *******************/
					$connectGpwUserAccountConfig = mysqli_connect($server, $db_user, $db_pass,$database);
					$queryGpwUserAccountConfig = mysqli_query($connectGpwUserAccountConfig,"SELECT Configuration FROM gpwutilisateurconfiguration WHERE
						(Login = '".$_SESSION['username']."' AND ((Application = 'mGeo3X') || (Application = 'DEFAUT')) )");
					$assocGpwUserAccountConfig = mysqli_fetch_assoc($queryGpwUserAccountConfig);
					$userConfig = $assocGpwUserAccountConfig['Configuration'];
//
//					if(!empty($_GET['login'])) {
//						$connectGpwUserConfig = mysqli_connect($server, $db_user, $db_pass, $database);
//						$queryGpwUserConfig = mysqli_query($connectGpwUserConfig, "SELECT Configuration FROM gpwutilisateurconfiguration WHERE
//							(Login = '" . $login. "' AND ((Application = 'mGeo3X') || (Application = 'DEFAUT')) )");
//						$assocGpwUserConfig = mysqli_fetch_assoc($queryGpwUserConfig);
//						$userConfigCompte = $assocGpwUserConfig['Configuration'];
//						if ($userConfigCompte == "" || $userConfigCompte == null) $userConfigCompte = "WEB_UTILISATEUR";
//						mysqli_free_result($queryGpwUserConfig);
//						mysqli_close($connectGpwUserConfig);
//
//
//						if( !empty($_SESSION['superviseurIdBd'])) {
//							if ($userConfigCompte == "WEB_UTILISATEUR_NI_AVANCE") echo "<option value='WEB_UTILISATEUR_NI_AVANCE' selected>WEB_UTILISATEUR_NI_AVANCE</option>";
//							else  echo "<option value='WEB_UTILISATEUR_NI_AVANCE' >WEB_UTILISATEUR_NI_AVANCE</option>";
//							if ($userConfigCompte == "WEB_UTILISATEUR_AVANCE") echo "<option value='WEB_UTILISATEUR_AVANCE' selected>WEB_UTILISATEUR_AVANCE</option>";
//							else echo "<option value='WEB_UTILISATEUR_AVANCE' >WEB_UTILISATEUR_AVANCE</option>";
//
//
//							if ($userConfigCompte == "WEB_UTILISATEUR_NI_ALARMES") echo "<option value='WEB_UTILISATEUR_NI_ALARMES' selected>WEB_UTILISATEUR_NI_ALARMES</option>";
//							else echo "<option value='WEB_UTILISATEUR_NI_ALARMES' >WEB_UTILISATEUR_NI_ALARMES</option>";
//							if ($userConfigCompte == "WEB_UTILISATEUR_ALARMES") echo "<option value='WEB_UTILISATEUR_ALARMES' selected>WEB_UTILISATEUR_ALARMES</option>";
//							else echo "<option value='WEB_UTILISATEUR_ALARMES' >WEB_UTILISATEUR_ALARMES</option>";
//
//							if ($userConfigCompte == "WEB_UTILISATEUR_NI") echo "<option value='WEB_UTILISATEUR_NI' selected>WEB_UTILISATEUR_NI</option>";
//							else echo "<option value='WEB_UTILISATEUR_NI' >WEB_UTILISATEUR_NI</option>";
//							if ($userConfigCompte == "WEB_UTILISATEUR") echo "<option value='WEB_UTILISATEUR' selected>WEB_UTILISATEUR</option>";
//							else echo "<option value='WEB_UTILISATEUR' >WEB_UTILISATEUR</option>";
//						}
//						if($userConfig == "WEB_GESTIONNAIRE") {
//							if ($userConfigCompte == "WEB_UTILISATEUR_NI_AVANCE") echo "<option value='WEB_UTILISATEUR_NI_AVANCE' selected>WEB_UTILISATEUR_NI_AVANCE</option>";
//							else  echo "<option value='WEB_UTILISATEUR_NI_AVANCE' >WEB_UTILISATEUR_NI_AVANCE</option>";
//							if ($userConfigCompte == "WEB_UTILISATEUR_AVANCE") echo "<option value='WEB_UTILISATEUR_AVANCE' selected>WEB_UTILISATEUR_AVANCE</option>";
//							else echo "<option value='WEB_UTILISATEUR_AVANCE' >WEB_UTILISATEUR_AVANCE</option>";
//
//
//							if ($userConfigCompte == "WEB_UTILISATEUR_NI_ALARMES") echo "<option value='WEB_UTILISATEUR_NI_ALARMES' selected>WEB_UTILISATEUR_NI_ALARMES</option>";
//							else echo "<option value='WEB_UTILISATEUR_NI_ALARMES' >WEB_UTILISATEUR_NI_ALARMES</option>";
//							if ($userConfigCompte == "WEB_UTILISATEUR_ALARMES") echo "<option value='WEB_UTILISATEUR_ALARMES' selected>WEB_UTILISATEUR_ALARMES</option>";
//							else echo "<option value='WEB_UTILISATEUR_ALARMES' >WEB_UTILISATEUR_ALARMES</option>";
//
//							if ($userConfigCompte == "WEB_UTILISATEUR_NI") echo "<option value='WEB_UTILISATEUR_NI' selected>WEB_UTILISATEUR_NI</option>";
//							else echo "<option value='WEB_UTILISATEUR_NI' >WEB_UTILISATEUR_NI</option>";
//							if ($userConfigCompte == "WEB_UTILISATEUR") echo "<option value='WEB_UTILISATEUR' selected>WEB_UTILISATEUR</option>";
//							else echo "<option value='WEB_UTILISATEUR' >WEB_UTILISATEUR</option>";
//						}
//
//						if($userConfig == "WEB_UTILISATEUR_NI_AVANCE"){
////							echo "<option value='WEB_GESTIONNAIRE' >WEB_GESTIONNAIRE</option>";
//							if($userConfigCompte == "WEB_UTILISATEUR_NI_AVANCE") echo "<option value='WEB_UTILISATEUR_NI_AVANCE' selected>WEB_UTILISATEUR_NI_AVANCE</option>";
//							else  echo "<option value='WEB_UTILISATEUR_NI_AVANCE' >WEB_UTILISATEUR_NI_AVANCE</option>";
//							if($userConfigCompte == "WEB_UTILISATEUR_AVANCE") echo "<option value='WEB_UTILISATEUR_AVANCE' selected>WEB_UTILISATEUR_AVANCE</option>";
//							else echo "<option value='WEB_UTILISATEUR_AVANCE' >WEB_UTILISATEUR_AVANCE</option>";
//
//
//							if($userConfigCompte == "WEB_UTILISATEUR_NI_ALARMES") echo "<option value='WEB_UTILISATEUR_NI_ALARMES' selected>WEB_UTILISATEUR_NI_ALARMES</option>";
//							else echo "<option value='WEB_UTILISATEUR_NI_ALARMES' >WEB_UTILISATEUR_NI_ALARMES</option>";
//							if($userConfigCompte == "WEB_UTILISATEUR_ALARMES") echo "<option value='WEB_UTILISATEUR_ALARMES' selected>WEB_UTILISATEUR_ALARMES</option>";
//							else echo "<option value='WEB_UTILISATEUR_ALARMES' >WEB_UTILISATEUR_ALARMES</option>";
//
//							if($userConfigCompte == "WEB_UTILISATEUR_NI") echo "<option value='WEB_UTILISATEUR_NI' selected>WEB_UTILISATEUR_NI</option>";
//							else echo "<option value='WEB_UTILISATEUR_NI' >WEB_UTILISATEUR_NI</option>";
//							if($userConfigCompte == "WEB_UTILISATEUR") echo "<option value='WEB_UTILISATEUR' selected>WEB_UTILISATEUR</option>";
//							else echo "<option value='WEB_UTILISATEUR' >WEB_UTILISATEUR</option>";
//
//						}
//						if($userConfig == "WEB_UTILISATEUR_AVANCE"){
////							echo "<option value='WEB_GESTIONNAIRE' >WEB_GESTIONNAIRE</option>";
//							if($userConfigCompte == "WEB_UTILISATEUR_AVANCE") echo "<option value='WEB_UTILISATEUR_AVANCE' selected>WEB_UTILISATEUR_AVANCE</option>";
//							else echo "<option value='WEB_UTILISATEUR_AVANCE' >WEB_UTILISATEUR_AVANCE</option>";
//
//							if($userConfigCompte == "WEB_UTILISATEUR_ALARMES") echo "<option value='WEB_UTILISATEUR_ALARMES' selected>WEB_UTILISATEUR_ALARMES</option>";
//							else echo "<option value='WEB_UTILISATEUR_ALARMES' >WEB_UTILISATEUR_ALARMES</option>";
//
//							if($userConfigCompte == "WEB_UTILISATEUR") echo "<option value='WEB_UTILISATEUR' selected>WEB_UTILISATEUR</option>";
//							else echo "<option value='WEB_UTILISATEUR' >WEB_UTILISATEUR</option>";
//
//						}
//
//						if($userConfig == "WEB_UTILISATEUR_NI_ALARMES"){
////							echo "<option value='WEB_GESTIONNAIRE' >WEB_GESTIONNAIRE</option>";
//							if($userConfigCompte == "WEB_UTILISATEUR_NI_ALARMES") echo "<option value='WEB_UTILISATEUR_NI_ALARMES' selected>WEB_UTILISATEUR_NI_ALARMES</option>";
//							else echo "<option value='WEB_UTILISATEUR_NI_ALARMES' >WEB_UTILISATEUR_NI_ALARMES</option>";
//							if($userConfigCompte == "WEB_UTILISATEUR_ALARMES") echo "<option value='WEB_UTILISATEUR_ALARMES' selected>WEB_UTILISATEUR_ALARMES</option>";
//							else echo "<option value='WEB_UTILISATEUR_ALARMES' >WEB_UTILISATEUR_ALARMES</option>";
//							if($userConfigCompte == "WEB_UTILISATEUR_NI") echo "<option value='WEB_UTILISATEUR_NI' selected>WEB_UTILISATEUR_NI</option>";
//							else echo "<option value='WEB_UTILISATEUR_NI' >WEB_UTILISATEUR_NI</option>";
//							if($userConfigCompte == "WEB_UTILISATEUR") echo "<option value='WEB_UTILISATEUR' selected>WEB_UTILISATEUR</option>";
//							else echo "<option value='WEB_UTILISATEUR' >WEB_UTILISATEUR</option>";
//						}
//						if($userConfig == "WEB_UTILISATEUR_ALARMES"){
////							echo "<option value='WEB_GESTIONNAIRE' >WEB_GESTIONNAIRE</option>";
//							if($userConfigCompte == "WEB_UTILISATEUR_ALARMES") echo "<option value='WEB_UTILISATEUR_ALARMES' selected>WEB_UTILISATEUR_ALARMES</option>";
//							else echo "<option value='WEB_UTILISATEUR_ALARMES' >WEB_UTILISATEUR_ALARMES</option>";
//							if($userConfigCompte == "WEB_UTILISATEUR") echo "<option value='WEB_UTILISATEUR' selected>WEB_UTILISATEUR</option>";
//							else echo "<option value='WEB_UTILISATEUR' >WEB_UTILISATEUR</option>";
//						}
//
//
//
//						if($userConfig == "WEB_UTILISATEUR_NI"){
//							if($userConfigCompte == "WEB_UTILISATEUR_NI") echo "<option value='WEB_UTILISATEUR_NI' selected>WEB_UTILISATEUR_NI</option>";
//							else echo "<option value='WEB_UTILISATEUR_NI' >WEB_UTILISATEUR_NI</option>";
//							if($userConfigCompte == "WEB_UTILISATEUR") echo "<option value='WEB_UTILISATEUR' selected>WEB_UTILISATEUR</option>";
//							else echo "<option value='WEB_UTILISATEUR' >WEB_UTILISATEUR</option>";
//						}
//
//						if($userConfig == "WEB_UTILISATEUR"){
//							if($userConfigCompte == "WEB_UTILISATEUR") echo "<option value='WEB_UTILISATEUR' selected>WEB_UTILISATEUR</option>";
//							else echo "<option value='WEB_UTILISATEUR' >WEB_UTILISATEUR</option>";
//						}
//					}else{
//						if( !empty($_SESSION['superviseurIdBd'])) {
//							echo "<option value='WEB_UTILISATEUR_NI_AVANCE' >WEB_UTILISATEUR_NI_AVANCE</option>";
//							echo "<option value='WEB_UTILISATEUR_AVANCE' >WEB_UTILISATEUR_AVANCE</option>";
//
//							echo "<option value='WEB_UTILISATEUR_NI_ALARMES' >WEB_UTILISATEUR_NI_ALARMES</option>";
//						 	echo "<option value='WEB_UTILISATEUR_ALARMES' >WEB_UTILISATEUR_ALARMES</option>";
//
//
//							echo "<option value='WEB_UTILISATEUR_NI' >WEB_UTILISATEUR_NI</option>";
//							echo "<option value='WEB_UTILISATEUR' selected>WEB_UTILISATEUR</option>";
//						}
//						if($userConfig == "WEB_GESTIONNAIRE") {
//							echo "<option value='WEB_UTILISATEUR_NI_AVANCE' >WEB_UTILISATEUR_NI_AVANCE</option>";
//							echo "<option value='WEB_UTILISATEUR_AVANCE' >WEB_UTILISATEUR_AVANCE</option>";
//
//							echo "<option value='WEB_UTILISATEUR_NI_ALARMES' >WEB_UTILISATEUR_NI_ALARMES</option>";
//							echo "<option value='WEB_UTILISATEUR_ALARMES' >WEB_UTILISATEUR_ALARMES</option>";
//
//
//							echo "<option value='WEB_UTILISATEUR_NI' >WEB_UTILISATEUR_NI</option>";
//							echo "<option value='WEB_UTILISATEUR' selected >WEB_UTILISATEUR</option>";
//						}
//
//						if($userConfig == "WEB_UTILISATEUR_NI_AVANCE"){
//							echo "<option value='WEB_UTILISATEUR_NI_AVANCE' >WEB_UTILISATEUR_NI_AVANCE</option>";
//							echo "<option value='WEB_UTILISATEUR_AVANCE' >WEB_UTILISATEUR_AVANCE</option>";
//
//							echo "<option value='WEB_UTILISATEUR_NI_ALARMES' >WEB_UTILISATEUR_NI_ALARMES</option>";
//							echo "<option value='WEB_UTILISATEUR_ALARMES' >WEB_UTILISATEUR_ALARMES</option>";
//
//
//							echo "<option value='WEB_UTILISATEUR_NI' >WEB_UTILISATEUR_NI</option>";
//							echo "<option value='WEB_UTILISATEUR' selected>WEB_UTILISATEUR</option>";
//
//						}
//						if($userConfig == "WEB_UTILISATEUR_AVANCE"){
//							echo "<option value='WEB_UTILISATEUR_AVANCE' >WEB_UTILISATEUR_AVANCE</option>";
//							echo "<option value='WEB_UTILISATEUR_ALARMES' >WEB_UTILISATEUR_ALARMES</option>";
//							echo "<option value='WEB_UTILISATEUR' selected>WEB_UTILISATEUR</option>";
//
//						}
//						if($userConfig == "WEB_UTILISATEUR_NI_ALARMES"){
//							echo "<option value='WEB_UTILISATEUR_NI_ALARMES' >WEB_UTILISATEUR_NI_ALARMES</option>";
//							echo "<option value='WEB_UTILISATEUR_ALARMES' >WEB_UTILISATEUR_ALARMES</option>";
//
//							echo "<option value='WEB_UTILISATEUR_NI' >WEB_UTILISATEUR_NI</option>";
//							echo "<option value='WEB_UTILISATEUR' selected>WEB_UTILISATEUR</option>";
//						}
//						if($userConfig == "WEB_UTILISATEUR_ALARMES"){
//							echo "<option value='WEB_UTILISATEUR_ALARMES' >WEB_UTILISATEUR_ALARMES</option>";
//
//							echo "<option value='WEB_UTILISATEUR' selected>WEB_UTILISATEUR</option>";
//						}
//
//						if($userConfig == "WEB_UTILISATEUR_NI"){
//							echo "<option value='WEB_UTILISATEUR_NI' >WEB_UTILISATEUR_NI</option>";
//							echo "<option value='WEB_UTILISATEUR' >WEB_UTILISATEUR</option>";
//						}
//
//						if($userConfig == "WEB_UTILISATEUR"){
//							echo "<option value='WEB_UTILISATEUR' selected>WEB_UTILISATEUR</option>";
//						}
//
//					}
//					?>
<!--				</select>-->
<!--				<div class="col-md-4" >-->
<!--					<input type="checkbox" name="option_droit_option" id="option_droit_option" onClick="closeDuree()" >&nbsp;--><?php //echo _('option_droit_option'); ?><!-- <br/>-->
<!--				</div>-->
				<?php
				if( !empty($_SESSION['superviseurIdBd'])) { ?>
						<div class="col-md-3 col-md-offset-1" id="config_rien">&nbsp;<input type="radio" name="config_choix" id="rien"  >&nbsp;Visualisateur</div>	<!-- traduction manquante -->
						<div class="col-md-3" id="config_alarme"><input type="radio" name="config_choix" id="alarmes" >&nbsp;Utilisateur</div>	<!-- traduction manquante -->
						<div class="col-md-3" id="config_tout"><input type="radio" name="config_choix" id="tout"  >&nbsp;Administrateur</div>	<!-- traduction manquante -->
					</div>

				</div>
				<div class="form-group" >
					<div class="col-md-offset-2" >
						<input type="checkbox" name="option_droit_option" id="option_droit_option" >&nbsp;<?php  echo _('option_droit_option'); ?>
					</div>
				</div>
			<?php	}
				if($userConfig == "WEB_GESTIONNAIRE") { ?>
						<div class="col-md-3 col-md-offset-1" id="config_rien">&nbsp;<input type="radio" name="config_choix" id="rien"  >&nbsp;Visualisateur</div>	<!-- traduction manquante -->
						<div class="col-md-3" id="config_alarme"><input type="radio" name="config_choix" id="alarmes" >&nbsp;Utilisateur</div>	<!-- traduction manquante -->
						<div class="col-md-3" id="config_tout"><input type="radio" name="config_choix" id="tout"  >&nbsp;Administrateur</div>	<!-- traduction manquante -->
					</div>

				</div>
				<div class="form-group" >
					<div class="col-md-offset-2" >
						<input type="checkbox" name="option_droit_option" id="option_droit_option" >&nbsp;<?php  echo _('option_droit_option'); ?>
					</div>
				</div>
			<?php	}

				if($userConfig == "WEB_UTILISATEUR_NI_AVANCE") { ?>
						<div class="col-md-3 col-md-offset-1" id="config_rien">&nbsp;<input type="radio" name="config_choix" id="rien"  >&nbsp;Visualisateur</div>	<!-- traduction manquante -->
						<div class="col-md-3" id="config_alarme"><input type="radio" name="config_choix" id="alarmes" >&nbsp;Utilisateur</div>	<!-- traduction manquante -->
						<div class="col-md-3" id="config_tout"><input type="radio" name="config_choix" id="tout"  >&nbsp;Administrateur</div>	<!-- traduction manquante -->
					</div>

				</div>
				<div class="form-group" >
					<div class="col-md-offset-2" >
						<input type="checkbox" name="option_droit_option" id="option_droit_option"  >&nbsp;<?php  echo _('option_droit_option'); ?>
					</div>
				</div>
			<?php	}
				if($userConfig == "WEB_UTILISATEUR_AVANCE") { ?>
						<div class="col-md-3 col-md-offset-1" id="config_rien">&nbsp;<input type="radio" name="config_choix" id="rien"  >&nbsp;Visualisateur</div>	<!-- traduction manquante -->
						<div class="col-md-3" id="config_alarme"><input type="radio" name="config_choix" id="alarmes" >&nbsp;Utilisateur</div>	<!-- traduction manquante -->
						<div class="col-md-3" id="config_tout"><input type="radio" name="config_choix" id="tout"  >&nbsp;Administrateur</div>	<!-- traduction manquante -->
					</div>

				</div>

			<?php	}
				if($userConfig == "WEB_UTILISATEUR_NI_ALARMES") { ?>
						<div class="col-md-3 col-md-offset-1" id="config_rien">&nbsp;<input type="radio" name="config_choix" id="rien"  >&nbsp;Visualisateur</div>	<!-- traduction manquante -->
						<div class="col-md-3" id="config_alarme"><input type="radio" name="config_choix" id="alarmes" >&nbsp;Utilisateur</div>	<!-- traduction manquante -->
					</div>
				</div>
				<div class="form-group" >
					<div class="col-md-offset-2" >
						<input type="checkbox" name="option_droit_option" id="option_droit_option" >&nbsp;<?php  echo _('option_droit_option'); ?>
					</div>
				</div>
			<?php	}
				if($userConfig == "WEB_UTILISATEUR_ALARMES") { ?>
						<div class="col-md-3 col-md-offset-1" id="config_rien">&nbsp;<input type="radio" name="config_choix" id="rien"  >&nbsp;Visualisateur</div>	<!-- traduction manquante -->
						<div class="col-md-3" id="config_alarme"><input type="radio" name="config_choix" id="alarmes" >&nbsp;Utilisateur</div>	<!-- traduction manquante -->
					</div>
				</div>
			<?php	}

				if($userConfig == "WEB_UTILISATEUR_NI") { ?>
						<div class="col-md-3 col-md-offset-1" id="config_rien">&nbsp;<input type="radio" name="config_choix" id="rien"  >&nbsp;Visualisateur</div>	<!-- traduction manquante -->
					</div>

				</div>
				<div class="form-group" >
					<div class="col-md-offset-2" >
						<input type="checkbox" name="option_droit_option" id="option_droit_option"  >&nbsp;<?php  echo _('option_droit_option'); ?>
					</div>
				</div>
			<?php	}
				if($userConfig == "WEB_UTILISATEUR") { ?>
						<div class="col-md-3 col-md-offset-1" id="config_rien">&nbsp;<input type="radio" name="config_choix" id="rien"  >&nbsp;Visualisateur</div>	<!-- traduction manquante -->
					</div>

				</div>

			<?php	}
				?>

	</form>


<div style="border: 0; height: 1px; background: #aaa; background-image: linear-gradient(to right, lightgray, lightgray, lightgray);"></div>

	<form class="form-horizontal" role="form" style="font-size: 14px;">
		<div class="form-group">
			<div class="col-md-8" >
			</div>
			<div class="col-md-3"  style="margin-top:8px;">

	<?php
		if(!empty($_GET['login'])) {
			echo '<input type="button" class="btn btn-default btn-xs" onClick="annulerCompte()" value="'; echo _('annuler'); echo'"></center>';
			echo '&nbsp;&nbsp;<input type="button" class="btn btn-default btn-xs" onClick="modifyAccount(\''.$idClient.'\')" value="'; echo _('valider'); echo'">';


		}else{
			echo '<input type="button" class="btn btn-default btn-xs" onClick="annulerCompte()" value="'; echo _('annuler'); echo'"></center>';
			echo '&nbsp;&nbsp;<input type="button" class="btn btn-default btn-xs" onClick="createAccount(\''.$idClient.'\')" value="'; echo _('valider'); echo'">';

		}
	?>
			</div>
		</div>

	</form>
