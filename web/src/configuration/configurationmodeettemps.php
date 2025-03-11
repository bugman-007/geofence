<?php


/*
* Affiche l'onglet modeettemps
*
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
<div id="modeFonctTempReel">
	<div id="mdf" class="panel-collapse collapse in">
		<div class="container-fluid" style="overflow:hidden; padding:10px;border-radius:10px 10px 10px 10px;">
			<div id="body_mode_fctmnt_temps" class="panel-body blue" style="border: 0px">
				<div class="modal fade" id="info_mode_fctmnt_list" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<br><br><br><br>
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<?php echo _('configuration_modalecontenu1_modedefctmnt'); ?>
							</div>
						</div>
					</div>
				</div>

				<div class="panel panel-default" style="margin: 0px; " >
					<div class="panel-body blue" style="margin: 0px; overflow:hidden; padding:10px;border-radius:10px 10px 10px 10px">
						<form class="form-horizontal" role="form" style="font-size: 14px;">
							<div class="form-group">
								<div class="col-md-6" >
									&nbsp; &nbsp;<i class="fa fa-sitemap fa-fw"></i>&nbsp;
									<label for="select_mode_fonctionnement" class="control-label"><?php echo _('modefonctionnement'); ?>:
										<a href="#" data-toggle="modal" data-target="#info_mode_fctmnt_list"><i class="fa fa-info-circle info"></i></a>
									</label>
								</div>
								<div class="col-md-3">
									<select id="select_mode_fonctionnement"  class="form-control  input-xs" style="margin-top: 6px" onChange="choisirModeFonctionnement(this.value)">
										<option value="normal" selected><?php echo _('configuration_modenormal'); ?></option>
										<!--option value="historique"><?php//echo _('configuration_modehistorique'); ?></option-->
										<!--option value="periscope"><?php //echo _('configuration_modeperiscope'); ?></option-->
										<!-- <option value="silencieux">--><?php //echo _('configuration_modesilencieux'); ?><!--</option>-->
									</select>
								</div>
							</div>
							<div style="border: 0; height: 1px; background: #aaa; background-image: linear-gradient(to right, lightgray, lightgray, lightgray);"></div>
							<br/>
						</form>
						<div class="col-md-6">
							<label class="control-label" style="margin-left:6em;"><?php echo _('configuration_freqacquiposition'); ?></label>
							<form class="form-horizontal" role="form">
								<div id="div_freq_acq_trajet" class="form-group">
										<label for="select_freq_acquisition_trajet" class="col-md-4 control-label" style="font-weight: normal"><?php echo _('entrajet'); ?>:</label>
									<div class="col-md-7">
										<select id="select_freq_acquisition_trajet" class="form-control  input-xs" style="margin-top: 6px">
											<option value="5" >5s</option><option value="10" >10s</option><option value="15" >15s</option><option value="20" >20s</option><option value="25" >25s</option>
											<option value="30" >30s</option><option value="35" >35s</option><option value="40" >40s</option><option value="45" >45s</option>
											<option value="60" selected>1mn</option><option value="90" >1mn30s</option><option value="120" >2mn</option><option value="150" >2mn30s</option><option value="180" >3mn</option>
											<option value="300" >5mn</option><option value="600" >10mn</option><option value="900" >15mn</option><option value="1200" >20mn</option><option value="1800" >30mn</option>
											<option value="3600" >1h</option><option value="5400" >1h30</option><option value="7200" >2h</option><option value="10800" >3h</option><option value="14400" >4h</option>
											<option value="18000" >5h</option><option value="21600" >6h</option><option value="25200" >7h</option><option value="28800" >8h</option>
										</select>
									</div>
								</div>
								<div id="div_freq_acq_timing" class="form-group">
									<label for="select_freq_acquisition_timing" class="col-md-4 control-label" style="font-weight: normal" ><?php echo _('configuration_entiming'); ?>:</label>
									<div class="col-md-7">
										<select id="select_freq_acquisition_timing" class="form-control  input-xs" style="margin-top: 6px" disabled>
											<option value="8" >8mn</option><option value="30" >30mn</option><option value="60" >1h</option><option value="120" >2h</option><option value="180" >3h</option><option value="240" >4h</option><option value="360" >6h</option>
											<option value="480" >8h</option><option value="720" >12h</option><option value="1440" >24h</option>
										</select>
									</div>
								</div>
								<div id="div_freq_acq_arret" class="form-group">
									<label for="select_freq_acquisition_arret" class="col-md-4 control-label" style="font-weight: normal"><?php echo _('configuration_alarret'); ?>:</label>
									<div class="col-md-7">
										<select id="select_freq_acquisition_arret" class="form-control  input-xs" style="margin-top: 6px">
											<option value="300" >5mn</option><option value="600" >10mn</option><option value="900" >15mn</option><option value="1200" >20mn</option><option value="1800" >30mn</option>
											<option value="3600" >1h</option><option value="5400" >1h30</option><option value="7200" >2h</option><option value="10800" >3h</option><option value="14400" >4h</option>
											<option value="18000" >5h</option><option value="21600" >6h</option><option value="25200" >7h</option><option value="28800" >8h</option><option value="43200" >12h</option><option value="64800" >18h</option>
										</select>
									</div>
								</div>
							</form>
						</div>

						<div id="div_freq_rap" class="col-md-6">
							<label class="control-label" style="margin-left:6em;"><?php echo _('configuration_freqrapatriposition'); ?></label>
							<form class="form-horizontal" role="form">
								<div id="div_freq_rap_trajet" class="form-group">
									<label for="select_freq_rapatriement_trajet" class="col-md-4 control-label" style="font-weight: normal"><?php echo _('entrajet'); ?>:</label>
									<div class="col-md-7">
										<select id="select_freq_rapatriement_trajet" class="form-control input-xs" style="margin-top: 6px">
											<option value="0" >Imm&eacute;diat</option>
											<option value="1" selected>1mn</option><option value="2" >2mn</option><option value="3" >3mn</option><option value="4" >4mn</option><option value="5" >5mn</option>
											<option value="10" >10mn</option><option value="15" >15mn</option><option value="20" >20mn</option><option value="30" >30mn</option>
											<option value="60" >1h</option><option value="90" >1h30</option><option value="120" >2h</option><option value="180" >3h</option><option value="240" >4h</option>
											<option value="300" >5h</option><option value="360" >6h</option><option value="420" >7h</option><option value="480" >8h</option>
										</select>
									</div>
								</div>
							</form>
							<form class="form-horizontal" role="form">
								<div id="div_freq_rap_timing" class="form-group">
									<label for="select_freq_rapatriement_timing" class="col-md-4 control-label" style="font-weight: normal"><?php echo _('configuration_entiming'); ?>:</label>
									<div class="col-md-7">
										<select id="select_freq_rapatriement_timing" class="form-control  input-xs" style="margin-top: 6px" disabled>
											<option value="8" >8mn</option><option value="30" >30mn</option><option value="60" >1h</option><option value="120" >2h</option><option value="180" >3h</option><option value="240" >4h</option><option value="360" >6h</option>
											<option value="480" >8h</option><option value="720" >12h</option><option value="1440" >24h</option>
										</select>
									</div>
								</div>
							</form>
							<form class="form-horizontal" role="form">
								<div id="div_freq_rap_arret" class="form-group">
									<label for="select_freq_rapatriement_arret" class="col-md-4 control-label" style="font-weight: normal"><?php echo _('configuration_alarret'); ?>:</label>
									<div class="col-md-7">
										<select id="select_freq_rapatriement_arret" class="form-control  input-xs" style="margin-top: 6px">
											<option value="5" >5mn</option><option value="10" >10mn</option><option value="15" >15mn</option><option value="20" >20mn</option><option value="30" >30mn</option>
											<option value="60" >1h</option><option value="90" >1h30</option><option value="120" >2h</option><option value="180" >3h</option><option value="240" >4h</option>
											<option value="300" >5h</option><option value="360" >6h</option><option value="420" >7h</option><option value="480" >8h</option><option value="720" >12h</option><option value="1440" >24h</option>
										</select>
									</div>
								</div>
							</form>
						</div>
						<div class="col-md-12">
							<form class="form-horizontal" role="form">
								<div class="form-group">
									<label id="labelgsm" for="connexion_gsm" class="col-md-2 control-label">GSM:</label>
									<script>
										function showcthis(){
											if(document.getElementById("connexion_gsm").value == "actifarret" || document.getElementById("connexion_gsm").value == "actiftrajet" || versionBaliseGlobal == "56"){
												$("#r").show(); $("#rt").show();
											}else{
												$("#r").hide(); $("#rt").hide();
											}
											if(document.getElementById("connexion_gsm").value == "planning"){alert('Avant de valider, assurez-vous que le planning est rempli et validé');}
										}
									</script>
									<div class="col-md-3">
										<select id="connexion_gsm" class="form-control input-xs" style="margin-top: 6px" onchange="showcthis()">
											<option value="permanent" selected>Permanent</option>
											<!--option value="eco">Actif en mouvement</option-->
										</select>

									</div>
									
									<label id="r" for="retard" class="col-md-3 control-label" style="display:none;">Retard &agrave; l'activation:</label>
									<div id="rt" class="col-md-2" style="display:none">
										<select id="retard" class="form-control input-xs" style="margin-top: 6px">
											<option value="0">0mn</option><option value="1">1mn</option><option value="2">2mn</option><option value="5">5mn</option><option value="10">10mn</option><option value="30">30mn</option><option value="60">1h</option><option value="120">2h</option>
										</select>

									</div>
								</div>
								<div class="col-md-offset-11" class="form-control input-xs" style="margin-top: 6px">
									<input type="button" class="btn btn-default btn-xs" onClick="validModeDeFonctionnement()" value="<?php echo _('valider'); ?>">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<br/>
			<!-- Mode Vitesse -->
			<div id="modevitesse" class="panel-body blue" style="display:none;border: 0px">
				<div class="modal fade" id="modalvitesse" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<br><br><br><br>
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<p><b><?php echo _('modevitesse'); ?></b></p>
								<?php echo _('configuration_modalecontenu_modevitesse'); ?>
								<!--Le mode vitesse permet d'optimiser les consommations d'énergie en diminuant l'envoi des positions.-->
							</div>
						</div>
					</div>
				</div>

				<div class="panel panel-default" style="margin: 0px; " >
					<div class="panel-body blue" style="margin: 0px; overflow:hidden; padding:10px;border-radius:10px 10px 10px 10px">
						<form class="form-horizontal" role="form" style="font-size: 14px;">
							<div class="form-group">
								<div class="col-md-6" >
									&nbsp; &nbsp;<i class="fa fa-sitemap fa-fw"></i>&nbsp;
									<label id="mv" for="select_mode_fonctionnement" class="control-label"><?php echo _('modevitesse'); ?>:
										<a href="#" data-toggle="modal" data-target="#modalvitesse"><i class="fa fa-info-circle info"></i></a>
									</label>
								</div>
								<div id="divchkv" class="col-md-3">
									<label for="chkv" class="control-label" style="font-weight: normal"><?php echo _('modevitesse_activer'); ?> 
									<input id="chkv" type="checkbox" style="margin-top: 6px">
									</label>
								</div>
							</div>
							<div style="border: 0; height: 1px; background: #aaa; background-image: linear-gradient(to right, lightgray, lightgray, lightgray);"></div>
							<br/>
						</form>
						
						<div class="col-md-2">
							<label id="lmvv" class="control-label" style="margin-left:3.2em;"><?php echo _('vitesse'); ?></label>
							<form class="form-horizontal" role="form">
								<div class="form-group">
									<div class="col-md-7">
										<select id="mvv" class="form-control input-xs" style="margin-top: 6px; margin-left:3.2em;">
											<option value="80" >80 Km/h</option><option value="90" >90 Km/h</option><option value="100" >100 Km/h</option><option value="120" >120 Km/h</option><option value="130" >130 Km/h</option>
										</select>
									</div>
								</div>
							</form>
						</div>
						
						<div class="col-md-5">
							<label id="lmvfp" class="control-label" style="margin-left:3.2em;"><?php echo _('configuration_freqacquiposition'); ?></label>
							<form class="form-horizontal" role="form">
								<div class="form-group">
									<label for="mvfp" class="col-md-4 control-label" style="font-weight: normal"><?php echo _('entrajet'); ?>:</label>
									<div class="col-md-7">
										<select id="mvfp" class="form-control  input-xs" style="margin-top: 6px">
											<option value="5" >5s</option><option value="10" >10s</option><option value="15" >15s</option><option value="20" >20s</option><option value="25" >25s</option>
											<option value="30" >30s</option><option value="35" >35s</option><option value="40" >40s</option><option value="45" >45s</option>
											<option value="60" selected>1mn</option><option value="90" >1mn30s</option><option value="120" >2mn</option><option value="150" >2mn30s</option><option value="180" >3mn</option>
											<option value="300" >5mn</option><option value="600" >10mn</option><option value="900" >15mn</option><option value="1200" >20mn</option><option value="1800" >30mn</option>
											<option value="3600" >1h</option><option value="5400" >1h30</option><option value="7200" >2h</option><option value="10800" >3h</option><option value="14400" >4h</option>
											<option value="18000" >5h</option><option value="21600" >6h</option><option value="25200" >7h</option><option value="28800" >8h</option>
										</select>
									</div>
								</div>
							</form>
						</div>

						<div id="divmvfrp" class="col-md-5">
							<label class="control-label" style="margin-left:3.2em;"><?php echo _('configuration_freqrapatriposition'); ?></label>
							<form class="form-horizontal" role="form">
								<div class="form-group">
									<label for="mvfrp" class="col-md-4 control-label" style="font-weight: normal"><?php echo _('entrajet'); ?>:</label>
									<div class="col-md-7">
										<select id="mvfrp" class="form-control input-xs" style="margin-top: 6px">
											<option value="0" >Imm&eacute;diat</option>
											<option value="1" selected>1mn</option><option value="2" >2mn</option><option value="3" >3mn</option><option value="4" >4mn</option><option value="5" >5mn</option>
											<option value="10" >10mn</option><option value="15" >15mn</option><option value="20" >20mn</option><option value="30" >30mn</option>
											<option value="60" >1h</option><option value="90" >1h30</option><option value="120" >2h</option><option value="180" >3h</option><option value="240" >4h</option>
											<option value="300" >5h</option><option value="360" >6h</option><option value="420" >7h</option><option value="480" >8h</option>
										</select>
									</div>
								</div>
							</form>
						</div>
						
						<div class="col-md-12">
							<form class="form-horizontal" role="form">
								<div class="col-md-offset-11" class="form-control input-xs" style="margin-top: 6px">
									<input type="button" class="btn btn-default btn-xs" onClick="validvitesse()" value="<?php echo _('valider'); ?>">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<br/>
			<!-- Fin Mode Vitesse -->
			<div id="modetr" class="panel panel-default" >
				<div class="panel-body blue" style="overflow:hidden; padding:10px;border-radius:10px 10px 10px 10px; ">
					<div class="modal fade" id="info_temps_reel" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<br><br><br><br>
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body">

									<p><b><?php echo _('configuration_tempsreel'); ?> </b>	</p>
										<?php echo _('configuration_modalecontenu1_tempsreel'); ?>
										<br>
									<?php echo _('configuration_modalecontenu2_tempsreel'); ?>
										<br>
									<?php echo _('configuration_modalecontenu3_tempsreel'); ?>
										<br>
										<?php echo _('remarques'); ?>:
										<br>
									<?php echo _('configuration_modalecontenu4_tempsreel'); ?>
								</div>
							</div>
						</div>
					</div>
					<form class="form-horizontal" role="form" style="font-size: 14px;">
						<div class="form-group">
							<div class="col-md-12" >
								&nbsp;	&nbsp;<i class="fa fa-sitemap fa-fw"></i>&nbsp;
								<label for="select_mode_fonctionnement" class="control-label">
									<?php echo _('configuration_tempsreelposition2sec'); ?>:&nbsp;</b>  <a href="#" data-toggle="modal" data-target="#info_temps_reel"><i class="fa fa-info-circle info"></i></a>
								</label>
							</div>
						</div>
						<div style="border: 0; height: 1px; background: #aaa; background-image: linear-gradient(to right, lightgray, lightgray, lightgray);"></div>
						<br/>
					</form>
					<div class="col-md-12">
						<form class="form-horizontal" role="form">
							<div class="form-group">
								<label for="select_temps_reel_immediat" class="col-md-5 control-label" style="font-weight: normal" >
									<?php echo _('configuration_immediatpendant'); ?>:
									&nbsp;<a href="#" data-toggle="modal" data-target="#info_temps_reel_immediat"><i class="fa fa-info-circle info"></i></a>
								</label>
								<div class="col-md-3">
									<select id="select_temps_reel_immediat" class="form-control input-xs" style="margin-top: 6px">
										<option value="0" selected>0mn</option>
										<option value="1" >1mn</option><option value="2" >2mn</option><option value="3" >3mn</option>
										<option value="4" >4mn</option><option value="5" >5mn</option><option value="10" >10mn</option><option value="15" >15mn</option><option value="20" >20mn</option><option value="30" >30mn</option>
										<option value="60" >1h</option><option value="90" >1h30</option>
									</select>
								</div>
								<div class="col-md-5 col-md-offset-1" class="form-control input-xs" style="margin-top: 6px">
									<!--<input type="button" class="btn btn-default btn-xs" onClick="validModeDeFonctionnement()" value="--><?php //echo _('valider'); ?><!--">-->
								</div>
							</div>
						</form>
					</div>
					<div class="col-md-12">
						<form class="form-horizontal" role="form">
							<div class="form-group">
								<label for="select_temps_reel_appel" class="col-md-5 control-label"style="font-weight: normal" >
									<?php echo _('configuration_surappelpendant'); ?>
									&nbsp;<a href="#" data-toggle="modal" data-target="#info_temps_reel_appel"><i class="fa fa-info-circle info"></i></a>
								</label>
								<div class="col-md-3">
									<select id="select_temps_reel_appel" class="form-control input-xs" style="margin-top: 6px">
										<option value="0" >0mn</option>
										<option value="1" selected>1mn</option><option value="2" >2mn</option><option value="3" >3mn</option><option value="3" >3mn</option><option value="5" >5mn</option>
										<option value="10" >10mn</option><option value="15" >15mn</option><option value="20" >20mn</option><option value="30" >30mn</option>
										<option value="60" >1h</option><option value="90" >1h30</option>
									</select>
								</div>
								<div class="col-md-2 col-md-offset-1" class="form-control input-xs" style="margin-top: 6px">
									<input type="button" class="btn btn-default btn-xs" onClick="validTempsReel()" value="<?php echo _('valider'); ?>">
								</div>
							</div>
						</form>
					</div>
					<div class="col-md-12">
						<form class="form-horizontal" role="form">
							<div class="form-group">
								<label for="select_temps_reel_demarrage" class="col-md-5 control-label"style="font-weight: normal" >
									<?php echo _('configuration_audemarragependant'); ?>
									&nbsp;<a href="#" data-toggle="modal" data-target="#info_temps_reel_demarrage"><i class="fa fa-info-circle info"></i></a>
								</label>

								<div class="col-md-3">
									<select id="select_temps_reel_demarrage" class="form-control input-xs" style="margin-top: 6px">
										<option value="0" >0 sec</option><option value="30" >30 sec</option>
										<option value="60" selected>1mn</option><option value="90" >1mn30s</option><option value="120" >2mn</option><option value="180" >3mn</option>
									</select>
								</div>
								<div class="col-md-2 col-md-offset-1" class="form-control input-xs" style="margin-top: 6px">

								</div>
							</div>
						</form>
					</div>

					<div class="col-md-12">
						<br/>
						<form class="form-horizontal" role="form">
							<div class="form-group">
								<label id="temps_reel_active_desactive" for="checkbox_temps_reel_active_desactive" class="col-md-6 control-label"style="font-weight: normal" >
									<?php echo _('configuration_surdeplacementdesactive'); ?>
									&nbsp;<a href="#" data-toggle="modal" data-target="#info_temps_reel_deplacement"><i class="fa fa-info-circle info"></i></a>
								</label>

								<div class="col-md-1"  class="form-control input-xs" style="margin-top: 6px">
									&nbsp; <input type="checkbox" id="checkbox_temps_reel_active_desactive" onClick="onCheckTempsReel(this);">
								</div>
								<div class="col-md-5" class="form-control input-xs" style="margin-top: 6px">
									<input type="button" class="btn btn-default btn-xs" onClick="validTempsReelActivation()" value="<?php echo _('valider'); ?>">
								</div>
							</div>
						</form>
					</div>
					<div class="modal fade" id="info_temps_reel_immediat" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<br><br><br><br>
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body">

									<p><b>	<?php echo _('configuration_tempsreel'); echo " ";  echo _('configuration_immediatpendant'); ?>  </b></p>
									<?php echo _('configuration_modalecontenu1_tempsreelimmediat'); ?>
										<br>
									<?php echo _('configuration_modalecontenu2_tempsreelimmediat'); ?>
										<br>
									<?php echo _('remarques'); ?>:
										<br>
									<?php echo _('configuration_modalecontenu3_tempsreelimmediat'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="modal fade" id="info_temps_reel_appel" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<br><br><br><br>
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body">

									<p><b>	<?php echo _('configuration_modalectitle_tempsreelsurappel');  ?>  </b>	</p>
									<?php echo _('configuration_modalecontenu1_tempsreelsurappel');  ?>
										<br>
											<?php echo _('remarques'); ?>:
										<br>
									<?php echo _('configuration_modalecontenu2_tempsreelsurappel');  ?>
								</div>
							</div>
						</div>
					</div>
					<div class="modal fade" id="info_temps_reel_demarrage" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<br><br><br><br>
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body">

									<p><b>	<?php echo _('configuration_modalectitle_tempsreelaudemarrage'); ?>: </b>	</p>
									<?php echo _('configuration_modalecontenu1_tempsreelaudemarrage'); ?>
										<br>
									<?php echo _('remarques'); ?>:
										<br>
									<?php echo _('configuration_modalecontenu2_tempsreelaudemarrage'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="modal fade" id="info_temps_reel_deplacement" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<br><br><br><br>
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body">

									<p><b>	<?php echo _('configuration_modalectitle_tempsreelsurdeplacement'); ?>: </b>	</p>
									<?php echo _('configuration_modalecontenu1_tempsreelsurdeplacement'); ?>
										<br>
									<?php echo _('configuration_modalecontenu2_tempsreelsurdeplacement'); ?>
										<br>
									<?php echo _('remarques'); ?>:
										<br>
									<?php echo _('configuration_modalecontenu3_tempsreelsurdeplacement'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>