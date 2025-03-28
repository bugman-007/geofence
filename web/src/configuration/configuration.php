<?php
/*
* Affiche la page l'onglet configuration

*/
session_start();
$_SESSION['CREATED'] = time();
require_once ("../../../lib/php-gettext-1.0.12/gettext.inc");
include '../ChromePhp.php';
include '../dbgpw.php';
//ChromePhp::log($_SESSION['idclient']);
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

/************* Recuperer l'Id_Base, Id_GPW, NomGPW de l'utilisateur *******************/
$connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
if (!$connectGpwUser) {
	die('Impossible de se connecter');
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


 ?>
<div id="nospam" style="display:none">0</div>
<div id="nospam2" style="display:none">0</div>
<div id="TheContenu" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
	<div class="row">
		<div class="col-lg-7 col col-lg-offset-3">
			<div class="panel panel-default" >
				<div class="panel-heading"> 
					<i class="fa fa-sitemap fa-fw"></i> <a style="color: black" data-toggle="collapse" data-parent="#accordion" href="#configuration">Configuration</a>	<!-- Traduction manquante -->
					<div class="pull-right">
						<div id="buttonOngletConfiguration" class="btn-group">
							<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
								<?php echo _('configuration_parametresavances'); ?>
								<span class="caret"></span>
							</button>
							<ul  class="menu dropdown-menu pull-right" role="menu">
								<li id="ongletModeFonctTempReel" class="effect active"><a href="javascript:paramAvancee(1)"><?php echo _('configuration_modefonctionnementtempsreel'); ?></a></li>
								
								<li id="ongletAlerteEtSms" class="effect"><a href="javascript:paramAvancee(3)"><?php echo _('configuration_alerteetsms'); ?></a></li>
								<?php if (($superviseurGpwUser == "1") || ($superviseurGpwUser == "2" && $idClientGpwUser == "-1")) { ?>
									<li id="ongletPlaningGsm" class="effect"><a href="javascript:paramAvancee(8)">Planning Télésurveillance</a></li>
									<!--li id="ongletRencontreBalise" class="effect"><a href="javascript:paramAvancee(7)">Rencontre Balise</a></li-->
									<li id="ongletDetectDeplaceArret" class="effect"><a href="javascript:paramAvancee(2)"><?php echo _('configuration_detectpdeplacementarret'); ?></a></li>
								<?php } ?>								
								<?php if($superviseurGpwUser == "1") { ?>
									<!--li id="ongletFonctionTechnique" class="effect"><a href="javascript:paramAvancee(4)">Fonction Technique (superviseur)</a></li-->	<!-- Traduction manquante -->
									<!--li id="ongletStrategie" class="effect"><a href="javascript:paramAvancee(5)">Strat&eacute;gie</a></li>
									<!--li id="ongletRadio" class="effect"><a href="javascript:paramAvancee(6)">Radio</a></li-->
									<!--li id="ongletRencontreBalise" class="effect"><a href="javascript:paramAvancee(7)">Rencontre Balise</a></li-->
									<!--li id="ongletPlaningGsm" class="effect"><a href="javascript:paramAvancee(8)">Planning Gsm</a></li-->
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
			
				<div class="panel-body" style="padding: 20px 30px 20px 30px;  min-height:100px ; ">
					<div class="col-lg-12" style="padding: 0px 0px 0px 10px;">
						<div id="config_nom_balise"> <?php echo _('nombalise'); ?>: </div>
						<div id="config_numero_appel"> <?php echo _('numeroappel'); ?>: </div> <br/>
					</div>
					<div class="col-lg-8" >
						<div id="lpb" class="panel panel-default" >
							<div class="panel-body blue" style=" overflow:hidden;border-radius:10px 10px 10px 10px;padding: 0px 0px 0px 10px; ">
								<form id="lpb2" class="form-horizontal" role="form">
									<div class="form-group">
										<div class="col-md-8">
											&nbsp; &nbsp; <label for="lecture_param_balise" class="control-label" ><?php echo _('configuration_lectureparametrebalise'); ?>:

										</div>
										<div class="col-md-3" style="text-align: center" >
											<input type="button" class="btn btn-default btn-xs" style="margin-top: 6px" onClick="lireParamBalise();" value="<?php echo _('configuration_lire'); ?>">
											<label class="control-label"  > <a href="#" data-toggle="modal" data-target="#info_lecture_param_balise"> <i class="fa fa-info-circle info"></i></a> </label>
										</div>
									</div>
								</form>
								<form class="form-horizontal" role="form">
									<div class="form-group">
										<div class="col-md-7">
											&nbsp; &nbsp; <label for="lecture_param_balise" class="control-label" ><?php echo _('dernieresynchro'); ?>:

										</div>
										<div class="col-md-5" style="text-align: center">
											<label class="control-label"  > <div id="config_derniere_synchro">  <a href="#" data-toggle="modal" data-target="#info_derniere_synchro"><i class="fa fa-info-circle info"></i></a> </div> </label>
										</div>
									</div>
								</form>

							</div>
						</div>
					</div>
					<div class="col-lg-4">
					<center>
						<br/><input type="button" class="btn btn-default btn-xs" onClick="afficherFichierLog();" value="<?php echo _('geofencing_fichierlog'); ?>"  data-toggle="modal"> <a href="#" data-toggle="modal" data-target="#info_fichier_log"><i class="fa fa-info-circle info"></i></a>
					</center>
					</div>
					<div class="col-lg-12" style="padding: 20px 0px 20px 0px;">
						<div id="parametre_avancee" >

						</div>
					</div>
				</div>
					
					<div class="modal fade" id="fichier_log" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<br><br><br><br>
						<div class="modal-dialog">
							<div class="modal-content">
								<div id="fichier_log_modal" class="modal-body">
									
								</div>
							</div>
						</div>
					</div>
					
					<div class="modal fade" id="info_lecture_param_balise" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<br><br><br><br>
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body">
									<p><b><?php echo _('configuration_lectureparametrebalise'); ?>: </b>	</p>
										<?php echo _('configuration_modalecontenu1_parambalise'); ?>
											<br>
										<?php echo _('configuration_modalecontenu2_parambalise'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="modal fade" id="info_derniere_synchro" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<br><br><br><br>
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body">
									<p><b><?php echo _('dernieresynchro'); ?> : </b>	</p>
									<?php echo _('configuration_modalecontenu1_dernieresynchro'); ?>
											<br>
									<?php echo _('configuration_modalecontenu2_dernieresynchro'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="modal fade" id="info_fichier_log" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<br><br><br><br>
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body">
									<p><b><?php echo _('geofencing_fichierlog'); ?>: </b>	</p>
									<?php echo _('configuration_modalecontenu1_fichierlog'); ?>
											<br>
									<?php echo _('configuration_modalecontenu2_fichierlog'); ?>
								</div>
							</div>
						</div>
					</div>
			
				
			</div>
		</div>
		
	</div>	

	 
	</div>

<script>
	$('.sortable th').on('click', function(){
		$(this).remove();
		// alert("test");

});	
	$('.menu li.effect').on('click', function(){
		$(this).addClass('active').siblings().removeClass('active');
	});
</script>


<?php
include '../dbgpw.php';
// On prolonge la session

/********** Recuperer la configuration de l'utilisateur *******************/
$connectGpwUserConfig = mysqli_connect($server, $db_user, $db_pass,$database);
if (!$connectGpwUserConfig) {
	die('Impossible de se connecter: '.mysqli_connect_error());
}
$queryGpwUserConfig = mysqli_query($connectGpwUserConfig,"SELECT Configuration FROM gpwutilisateurconfiguration WHERE
						(Login = '".$_SESSION['username']."' AND ((Application = 'mGeo3X') || (Application = 'DEFAUT')) )");
$assocGpwUserConfig = mysqli_fetch_assoc($queryGpwUserConfig);
$userConfig = $assocGpwUserConfig['Configuration'];
if($userConfig == "" || $userConfig == null) $userConfig = "WEB_UTILISATEUR";

mysqli_free_result($queryGpwUserConfig);
mysqli_close($connectGpwUserConfig);

echo "<script>configGpwUser('".$userConfig."');</script>";

/*****************************/