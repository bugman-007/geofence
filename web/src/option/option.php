<?php

	/*
	* Affiche la page option
	*/


	session_start();
	$_SESSION['CREATED'] = time();
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
?>

	<div class="col-lg-5 col col-lg-offset-4">

			<div class="panel panel-default">
				<div class="panel-heading">

					<span class="glyphicon glyphicon-tasks" aria-hidden="true"></span><a style="color: black" data-toggle="collapse" data-parent="#accordion" href="#inforap"> Options</a>
					<?php
					include '../dbgpw.php';
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
						if( $superviseurGpwUser == "2" && $idClientGpwUser != "-1"){
							echo '<div class="pull-right">';
							echo '<div class="btn-group">';
							echo '<button type="button" class="btn btn-primary btn-xs dropdown-toggle" onClick="showManage(\''.$idClientGpwUser.'\')" value="Gérer">'; echo _('option_gerer'); echo'</button>';
							echo '</div></div>';
						}else  if( $superviseurGpwUser == "2" && $idClientGpwUser == "-1") {
							echo '<div class="pull-right">';
							echo '<div class="btn-group">';
							echo '<button type="button" class="btn btn-primary btn-xs dropdown-toggle" onClick="showManage()" value="Gérer">'; echo _('option_gerer'); echo'</button>';
							echo '</div></div>';
						}else if( !empty($_SESSION['superviseurIdBd'])){
							echo '<div class="pull-right">';
							echo '<div class="btn-group">';
							echo '<button type="button" class="btn btn-primary btn-xs dropdown-toggle" onClick="showManage()" value="Gérer">'; echo _('option_gerer'); echo'</button>';
							echo '</div></div>';

						}
//					?>

				</div>
				<div id="inforap" class="panel-collapse collapse in">
					<div class="panel-body">
						<div class="container-fluid" style="overflow:hidden; padding:10px;border-radius:10px 10px 10px 10px;">
							<table class="table table-borderless">
								<tr id="optionGroupeBalise">
									<td><h5><?php echo _('option_changementgroupe'); ?>:</h5></td>
									<td>
										<div class="input-group">
											<input  type="text" class="form-control" id="groupebalise_option"  placeholder="">
											  <span class="input-group-btn">
												<button id="button_groupebalise_option" class="btn btn-default" onclick="okOptionGpw()" type="button"
														data-container="body" >OK</button>
											  </span>
										</div>
									</td>
								</tr>
								<tr id="optionNomBalise">
									<td><h5><?php echo _('option_changementbalise'); ?>:</h5></td>

									<td>
										<div class="input-group">
											<input  type="text" class="form-control" id="nombalise_option"  placeholder="">
											  <span class="input-group-btn">
												<button class="btn btn-default" onclick="okOptionNomBalise()" type="button">OK</button>
											  </span>
										</div>
									</td>
								</tr>
									<?php
										$connectGpwUtilisateur = mysqli_connect($server, $db_user, $db_pass,$database);
										if (!$connectGpwUtilisateur ) {
											die('Impossible de se connecter');
										}
										$queryGpwUtilisateur  = mysqli_query($connectGpwUtilisateur ,"SELECT Superviseur FROM gpwutilisateur WHERE (Login = '".$_SESSION['username']."' ) ");
										$assocGpwUtilisateur  = mysqli_fetch_assoc($queryGpwUtilisateur );
										$superviseurGpwUtilisateur  = $assocGpwUtilisateur ['Superviseur'];

										if( $superviseurGpwUtilisateur == "1"){
											 echo '<tr id="optionNumeroBalise">';
											 echo '<td><h5>'; echo _('option_changementnumero');
											 echo':</h5></td>
													<td>
														<div class="input-group">
															<input type="text" class="form-control" id="numerobalise_option"  placeholder="">
															 <span class="input-group-btn">
																<button class="btn btn-default" onclick="okOptionNumeroBalise()" type="button">OK</button>
															</span>
														</div>
													</td>
													</tr>';
											?>
											<script type="text/javascript">
												function okOptionNumeroBalise(){
													var Id_Tracker = document.getElementById("idBalise").innerHTML;
													var numeroAppelNew = document.getElementById('numerobalise_option').value;
													
													if(numeroAppelNew[0] == '+')
														var InternationalFormat = '1';
													else
														var InternationalFormat = '0';

													if (Id_Tracker==""){
														alert(getTextVeuillezChoisirUneBalise);
													}else if(Id_Tracker.search(/,/) != -1){
														alert(getTextVeuillezChoisirQueUneBalise);
													}else{
														if (confirm(getTextConfirmChangerNumeroBalise+" ?")) {
															$.ajax({
																url: '../option/optionoknumero.php',
																type: 'GET',
																data: "InternationalFormat=" + InternationalFormat + "&numeroTelTrackerNew=" + numeroAppelNew + "&Id_Tracker=" + Id_Tracker +
																"&nomDatabaseGpw=" + globalnomDatabaseGpw + "&ipDatabaseGpw=" + globalIpDatabaseGpw,
																success: function (response) {
																	if (response)
																		showOptionNomBalise();
																}
															});
														}
													}
												}
											</script>											
											<?php
											echo '<tr id="optionCodeTrans">';
											echo '<td><h5>Code transmetteur:</h5></td>';
											echo '<td>
													<div class="input-group">
														<input type="text" class="form-control" id="codetrans_option"  placeholder="">
														<span class="input-group-btn">
															<button class="btn btn-default" onclick="okOptionCodetrans()" type="button">OK</button>
														</span>
													</div>
												</td>
												</tr>';
											?>
											<script type="text/javascript">
												function okOptionCodetrans(){
													var codeTransNew = document.getElementById('codetrans_option').value;
													var Id_Tracker = document.getElementById("idBalise").innerHTML;
													var nomDatabaseGpw = globalnomDatabaseGpw;
													var ipDatabaseGpw = globalIpDatabaseGpw;
													
													if (Id_Tracker==""){
														alert(getTextVeuillezChoisirUneBalise);
													}else if(Id_Tracker.search(/,/) != -1){
														alert(getTextVeuillezChoisirQueUneBalise);
													}else{
														if (confirm("Voulez-vous vraiment modifier le code transmetteur pour la télésurveilance ?"))
														{
															$.ajax({
																url: '../option/optionoknumero.php',
																type: 'GET',
																data: "cdetrstsv=" + codeTransNew + "&Id_Tracker=" + Id_Tracker +
																"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw,
																success: function (response) {
																	if (response) {
																		showOptionNomBalise();
																	}
																}
															});
														}
													}
												}
											</script>											
											<?php
										}
									?>
								<tr id="optionLangue">
									<td><h5><?php echo _('option_changementlangue'); ?>:</h5></td>

									<td>
										<div class="input-group">
											<select id="selectLanguage" class="selectpicker form-control" data-live-search="true"  onChange="changeLanguage(this.value)">
<!--												<select style="font-size: 12px;" id="selectLanguage" onChange="changeLanguage(this.value)">-->
												<option value="fr_FR">Fran&#231;ais</option>
												<option value="en_US">English</option>
											</select>
										</div>
									</td>
								</tr>



<!--								<tr>-->
<!--									<td></td><td></td>-->
<!--								</tr>-->

								<tr  id="optionIconeBalise">
									<td><h5><?php echo _('option_changementicone'); ?>:</h5></td>
									<td>

										<input type="button"  class="btn btn-default"  onClick="listIcone()" value="<?php echo _('option_choisiricone'); ?>">
										<div class="modal fade" id="listIcone" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<br><br><br><br>
											<div class="modal-dialog">
												<div class="modal-content">
													<div  class="modal-body" style="min-height:700px;overflow:auto;">
														<center>
														<h5><p><b><?php echo _('option_cliquericone'); ?>:</b></p></h5>

														<div id="listIcone_modal" style="height:400px;overflow:auto"></div>
														<br/><br/><h5><p><b><?php echo _('option_iconeactuel'); ?>:</b></p></h5>
														<div id="myIcone_modal"><?php echo _('option_iconeactuel'); ?></div>
															<br/><br/><input type="button"  style="width: 85px;"  class="btn btn-default btn-xs" onClick="validIcone()" value="<?php echo _('valider'); ?>">
														</center>

													</div>
												</div>
											</div>
										</div>
									</td>
								</tr>

								<!-- Changement base de données -->
								<tr id="optionChangementBase" style="display:none">
									<td><h5><?php echo _('option_changementbdd'); ?>:</h5></td>
									<td>
										<div class="input-group">
											<select id="select_bdd" class="selectpicker form-control"
													data-live-search="true">
																						<?php
										if( !empty($_SESSION['superviseurIdBd'])) {
											$connection = mysqli_connect($server, $db_user, $db_pass,$database);
											$sql = "SELECT * FROM gpwbd ORDER BY Id_Base";
											$result = mysqli_query($connection,$sql);
//											echo '<select id="select_bdd">';
											while ($row = mysqli_fetch_array($result)) {

												echo '<option value="' . $row['Id_Base'] . '">'.$row['NomBase']." - ".$row['DescriptionBase'].'</option>';
											}
											echo '</select>';

										}
										?>
											<span class="input-group-btn">
												<button class="btn btn-default" onclick="selectChangeBdd()" type="button">OK</button>
											  </span>
										</div>
									</td>
								</tr>
								<tr id="histocon" style="display:none">
									<td><h5><?php echo _('historique_connexion'); ?><a onClick="$('#thisto').toggleClass('show');" style="cursor:pointer;"><i class="fa fa-sort info"></i></a></h5></td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="input-group">
											<div id="thisto" class="" style="display:none;">
												<table class="table table-hover">
													<tr class="active">
														<td><?php echo _('historique_connexion_utilisateur'); ?></td>
														<td><?php echo _('historique_connexion_date'); ?></td>
														<td><?php echo _('historique_connexion_hour'); ?></td>
														<td><?php echo _('historique_connexion_IP'); ?></td>
													</tr>
													<?php
													if( !empty($_SESSION['superviseurIdBd'])) {
														$con = mysqli_connect($server, $db_user, $db_pass,$database);
														if (!$con) die('Impossible de se connecter');
														$res = mysqli_query($con, "SELECT Login, DateConnexion, HeureConnexion, AdresseIP FROM gpwhistoriqueconnexion WHERE Application = 'Geo3xPhpv4' ORDER BY IDLogConnexion DESC limit 10");
														while ($row = mysqli_fetch_array($res)) {

															echo '<tr><td>'.$row['Login'].'</td><td>'.$row['DateConnexion'].'</td><td>'.$row['HeureConnexion'].'</td><td>'.$row['AdresseIP'].'</td></tr>';
														}
														mysqli_close($con);
													}
													?>
												</table>
											</div>
										</div>
									</td>
								</tr>
								<?php
								if( $superviseurGpwUser == "2" || $idClientGpwUser == "-1" || !empty($_SESSION['superviseurIdBd'])) { ?>
								<!--tr  id="optionViePrivee">
									<td><h5><?php echo _('option_gerer')." ";echo _('confidentialite_balise'); ?>:<a href="#" data-toggle="modal" data-target="#info_privacy"> <i class="fa fa-info-circle info"></i></a> </h5>
										<div class="modal fade" id="info_privacy" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<br><br><br><br>
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-body">
														<p><b><?php echo _('option_gerer')." ";echo _('confidentialite_balise'); ?></b>	</p>
														<?php echo _('privacy_info1'); ?>
													</div>
												</div>
											</div>
										</div>
									</td>
									<td>
										<input type="button"  class="btn btn-default"  onClick="addPrivacyConfig()" value="<?php echo _('afficher'); ?>">
									</td>
								</tr-->
								<!-- Gestion confidentialité -->
								<tr id="affichage_privacy" style="display:none">
									<td colspan="2">
										<div id="config_privacy_balise">
											<div class="panel panel-default">
												<div class="panel-body " style="padding: 10px 17px 10px 15px; min-height:50px">
													<form class="form-horizontal" role="form" style="font-size: 14px;">
														<div class="form-group">
															<div class="col-md-10" >

																<label class="control-label">
																	&nbsp;&nbsp;<?php echo _('confidentialite_balise'); ?>:
																</label>
																<div id="nom_balise_privacy" style="display:inline"><?php echo "test" ?></div>


															</div>
														</div>
														<div style="border: 0; height: 1px; background: #aaa; background-image: linear-gradient(to right, lightgray, lightgray, lightgray);"></div>
														<br/>
													</form>
													<form class="form-horizontal" role="form" style="padding-left:2px">
														<div class="form-group" >
															<div class="col-md-2" >
																<div class="panel panel-default">
																	<div class="panel-body " style="padding: 10px 0 10px 15px; ">
																		<label for="debut1" style="width:100px"><?php echo _('debut'); ?> 1&nbsp;</label>
																		<input name="debut1" id="debut1"  style="width:60px"  onchange="onChangeHeurePrivacy(this.id)" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time" <?php echo "value='00:00' "; ?> />

																		<label for="fin1" style="width:100px" ><?php echo _('fin'); ?> 1</label>
																		<input name="fin1" id="fin1"  style="width:60px"  onchange="onChangeHeurePrivacy(this.id)" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time" <?php echo "value='00:00'"; ?> />
																	</div>
																</div>
															</div>
															<div class="col-md-2" >
																<div class="panel panel-default">
																	<div class="panel-body " style="padding: 10px 0 10px 15px;">
																		<label for="debut2" style="width:100px"><?php echo _('debut'); ?> 2&nbsp;</label>
																		<input name="debut2" id="debut2" style="width:60px"  onchange="onChangeHeurePrivacy(this.id)" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time" <?php echo "value='00:00' "; ?> />
																		<label for="finRapport2" style="width:100px" ><?php echo _('fin'); ?> 2</label>
																		<input name="fin2" id="fin2"  style="width:60px"   onchange="onChangeHeurePrivacy(this.id)" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time" <?php echo "value='00:00'"; ?> />
																	</div>
																</div>
															</div>

															<div class="col-md-8" >
																<div class="panel panel-default">
																	<div class="panel-body " style="padding: 10px 0 20px 15px;">
																		<div class="form-group">
																			<div class="col-md-8" style="text-align: left">
																				<b>&nbsp;&nbsp;<?php echo _('jour_valide'); ?>:</b>
																			</div>
																			<br/>
																			<div class="checkbox " style="text-align: center">
																				<label><input type="checkbox" id="privacy_lundi"  ><?php echo _('lundi'); ?> &nbsp;</label>
																				<label><input type="checkbox" id="privacy_mardi"  ><?php echo _('mardi'); ?>&nbsp;</label>
																				<label><input type="checkbox" id="privacy_mercredi" ><?php echo _('mercredi'); ?>&nbsp;</label>
																				<label><input type="checkbox" id="privacy_jeudi"  ><?php echo _('jeudi'); ?>&nbsp;</label>
																				<label><input type="checkbox" id="privacy_vendredi" ><?php echo _('vendredi'); ?>&nbsp;</label><br>
																				<label><input type="checkbox" id="privacy_samedi"  ><?php echo _('samedi'); ?>&nbsp;</label>
																				<label><input type="checkbox" id="privacy_dimanche"><?php echo _('dimanche'); ?></label>

																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div style="text-align: right">
																<input type="button"  class="btn btn-default btn-xs"  onClick="updateConfigPrivacy()" value="<?php echo _('valider'); ?>">&nbsp;&nbsp;
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
									</td>
								</tr>
								<?php
								}
								?>
							</table>
						</div>
					</div>
				</div>
			<script>
				
	document.getElementById("selectLanguage").value = "<?php echo $_SESSION['language']; ?>";
	if(document.getElementById("selectLanguage").value == "en_US"){
		var myTimeFormat = "hh:ii A";
	}else{
		var myTimeFormat = "HH:ii";
	}

	$(function () {
		var curr = new Date().getFullYear();
		var opt = {
			'date': {
				preset: 'date',
				dateOrder: 'd Dmmyy',
				invalid: { daysOfWeek: [0, 6], daysOfMonth: ['5/1', '12/24', '12/25'] }
			},
			'datetime': {
				preset: 'datetime',
				minDate: new Date(2019, 1, 1, 0, 0),
				maxDate: new Date(2050, 2, 1, 0, 0),
				stepMinute: 1,
				dateFormat: 'dd-mm',
				timeFormat: myTimeFormat
			},
			'time': {
				preset: 'time'
			}
		}
	});

	//	$( function() {
	//		if(modeMessageGlobal== "GPRS"){
	//			$('#id_option_mode_fonctionnement').val('GPRS');
	//		}else if(modeMessageGlobal == "SMS"){
	//			$('#id_option_mode_fonctionnement').val('SMS');
	//		}
	//	});
	$(function () {
		$('[data-toggle="popover"]').popover()
	})


	function changeLanguage(value){
		document.body.className = "loading";
		if(window.XMLHttpRequest){
			xmlhttp = new XMLHttpRequest();
		}else{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function(){
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
				document.location.reload(false);
			}
		}
		xmlhttp.open("GET","../language_update.php?geo3x_lang="+value,true);
		xmlhttp.send();
	}


	<?php if( !empty($_SESSION['superviseurIdBd'])) { ?>
	document.getElementById("optionChangementBase").style.display = "";
	document.getElementById("histocon").style.display = "";
	document.getElementById("select_bdd").value = <?php echo $_SESSION['superviseurIdBd']; }?>;


</script>
<!---->
	<?php
	include '../dbgpw.php';
	// On prolonge la session

	// On teste si la variable de session existe et contient une valeur
	if(empty($_SESSION['username']))
	{
	  // Si inexistante ou nulle, on redirige vers le formulaire de login
	  header('location:index.php');
	  exit();
	}
	/************* Recuperer la configuration de l'utilisateur *******************/
	$connectGpwUserConfig = mysqli_connect($server, $db_user, $db_pass,$database);
	if (!$connectGpwUserConfig) {
		die('Impossible de se connecter');
	}
	$queryGpwUserConfig = mysqli_query($connectGpwUserConfig,"SELECT Configuration FROM gpwutilisateurconfiguration WHERE
							(Login = '".$_SESSION['username']."' AND ((Application = 'mGeo3X') || (Application = 'DEFAUT')) )");
	$assocGpwUserConfig = mysqli_fetch_assoc($queryGpwUserConfig);
	$userConfig = $assocGpwUserConfig['Configuration'];
	if($userConfig == "" || $userConfig == null) $userConfig = "WEB_UTILISATEUR";

	//$userConfig = "WEB_UTILISATEUR";

	mysqli_free_result($queryGpwUserConfig);
	mysqli_close($connectGpwUserConfig);
	echo "<script>configGpwUser('".$userConfig."');</script>";

	/*****************************/

	//?>
