<?php

	/*
	* Affiche l'onglet deplacement et arret
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

$date = date("Y-m-d");
$heure = date("H")+2;
$heures = date($heure.":i:s");
$dateTime = $date." 00:00:00";
$localDateTime = new DateTime(null, new DateTimezone('Europe/Berlin'));
		// echo $localDateTime->format('Y-m-d H:i:s');
?><div id="detectDeplaceArret" class="panel panel-default">
		<!--		<div class="panel-heading">
					<a style="color: black" data-toggle="collapse" data-parent="#accordion" href="#detect">D&eacute;tection d&eacute;placement / arr&ecirc;t</a>
					
				</div> -->
				<div id="detect" class="panel-collapse collapse in">
				<div class="panel-body blue" >
					<div class="container-fluid" style="overflow:hidden; padding:3px;border-radius:10px 10px 10px 10px;">
						<div class="panel-body">
<!--							<b>	--><?php //echo _('configuration_parametredetectdeplacementarret'); ?><!--:&nbsp;</b>  <a href="#" data-toggle="modal" data-target="#info_temps_reel">?</a>-->
<!--							<br>-->
<!--							<br>-->
							<div class="modal fade" id="info_temps_reel" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<br><br><br><br>
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-body">

											<p><b><?php echo _('configuration_modalectitle_deplacementarret'); ?> </b>	</p>
											<?php echo _('configuration_modalecontenu1_deplacementarret'); ?>
												<br>
											<?php echo _('configuration_modalecontenu2_deplacementarret'); ?>
												<br>
												-<?php echo _('configuration_modalecontenu3_deplacementarret'); ?>
												<br>														
												-<?php echo _('configuration_modalecontenu4_deplacementarret'); ?>
												<br>
											<?php echo _('remarques'); ?>:
												<br>
											<?php echo _('configuration_modalecontenu5_deplacementarret'); ?>
										</div>
									</div>
								</div>
							</div>
							<form class="form-horizontal" role="form" style="font-size: 14px;">
								<div class="form-group">
									<div class="col-md-12" >
										&nbsp; &nbsp;<i class="fa fa-sitemap fa-fw"></i>&nbsp;
										<label class="control-label"><?php echo _('configuration_parametredetectdeplacementarret'); ?>:
											<a href="#" data-toggle="modal" data-target="#info_temps_reel"><i class="fa fa-info-circle info"></i></a>
										</label>
									</div>
								</div>
								<div style="border: 0; height: 1px; background: #aaa; background-image: linear-gradient(to right, lightgray, lightgray, lightgray);"></div>
								<br/>
							</form>
							<div class="col-md-12">
								<form class="form-horizontal" role="form">
									<div id="divseuilvitesse" class="form-group">
										<label for="detection_deplacement_seuil_vitesse" class="col-md-6 control-label" style="font-weight: normal">&nbsp; &nbsp;<?php echo _('configuration_deplacementseuilvitesse'); ?>:</label>
										<div class="col-md-3">
											<select name="detection_deplacement_seuil_vitesse" id="detection_deplacement_seuil_vitesse" class="form-control  input-xs" style="margin-top: 6px">
												<option value="5" >5km/h</option><option value="7" >7km/h</option><option value="9" >9km/h</option><option value="11" >11km/h</option><option value="15" >15km/h</option>
												<option value="18" >18km/h</option><option value="40" >40km/h</option><option value="90" >90km/h</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="detection_deplacement_temps_vib" class="col-md-6 control-label" style="font-weight: normal" >&nbsp; &nbsp;<?php echo _('configuration_deplacementtempsvib'); ?>:</label>
										<div class="col-md-3">
											<select name="detection_deplacement_temps_vib" id="detection_deplacement_temps_vib" class="form-control  input-xs" style="margin-top: 6px">
												<option value="15" >15s</option>
												<option value="30" >30s</option><option value="45" >45s</option>
												<option value="60">1mn</option><option value="90" >1mn30s</option><option value="120" >2mn</option><option value="150" >2mn30s</option><option value="180" >3mn</option>
												<option value="240" >4mn</option><option value="300" >5mn</option><option value="600" >10mn</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="detection_arret_temps_absence_vib" class="col-md-6 control-label" style="font-weight: normal">&nbsp; &nbsp;<?php echo _('configuration_deplacementtempsabscencevib'); ?>:</label>
										<div class="col-md-3">
											<select name="detection_arret_temps_absence_vib" id="detection_arret_temps_absence_vib" class="form-control  input-xs" style="margin-top: 6px">
												<option value="30" >30s</option><option value="45" >45s</option>
												<option value="60">1mn</option><option value="90" >1mn30s</option><option value="120" >2mn</option><option value="150" >2mn30s</option><option value="180" >3mn</option>
												<option value="240" >4mn</option><option value="300" >5mn</option><option value="600" >10mn</option>
											</select>
										</div>
									</div>
									<!--franck-->
									<script>
										if( $.inArray(versionBaliseGlobal, ['47','48','51','53','55','56','57']) >= 0){
											$('#divsensibilite').show(); 
										}else{
											$('#divsensibilite').hide(); 
										}
										if( $.inArray(versionBaliseGlobal, ['20','3006','3370','8079','3600','7003','7201','8000']) >= 0){
											$('#lpb').hide();
											$('#divseuilvitesse').hide();
										}else{
											$('#lpb').show();
											$('#divseuilvitesse').show();
										}
									</script>
									<div id="divsensibilite" class="form-group" style="display:none;">
										<label for="sensibilite" class="col-md-6 control-label" style="font-weight: normal">&nbsp; &nbsp;Sensibilit&eacute;:</label>
										<div class="input-group col-md-3">
											<span class="input-group-addon">+</span>
											<input id="sensibilite" class="form-control input-xs" type="range" value="3" min="2" max="15" step="1"></input>
											<span class="input-group-addon">-</span>
										</div>
									</div>
									<!--franck-->
									<div class="col-md-offset-11">
										<input type="button" class="btn btn-default btn-xs" onClick="validDeplacementArret()" value="<?php echo _('valider'); ?>">
									</div>
								</form>
							</div>
<!--							<table class="table table-borderless">-->
<!--											-->
<!--								<tr>-->
<!--									<td>--><?php //echo _('configuration_deplacementseuilvitesse'); ?><!--: &nbsp;</td>-->
<!--									<td>-->
<!--										<select name="detection_deplacement_seuil_vitesse" id="detection_deplacement_seuil_vitesse" class="geo3x_select_config">-->
<!--											<option value="5" >5km/h</option><option value="7" >7km/h</option><option value="9" >9km/h</option><option value="11" >11km/h</option><option value="15" >15km/h</option>-->
<!--											<option value="18" >18km/h</option><option value="40" >40km/h</option><option value="90" >90km/h</option>-->
<!--										</select> -->
<!--									</td>-->
<!--									-->
<!--								</tr>-->
<!--								<tr><td>--><?php //echo _('configuration_deplacementtempsvib'); ?><!--:&nbsp;</td>-->
<!--									<td>-->
<!--										<select name="detection_deplacement_temps_vib" id="detection_deplacement_temps_vib" class="geo3x_select_config">-->
<!--										<option value="30" >30s</option><option value="45" >45s</option>-->
<!--										<option value="60">1mn</option><option value="90" >1mn30s</option><option value="120" >2mn</option><option value="150" >2mn30s</option><option value="180" >3mn</option>-->
<!--										<option value="240" >4mn</option><option value="300" >5mn</option><option value="600" >10mn</option>-->
<!--									</select> -->
<!--									</td>-->
<!--								</tr>-->
<!--								<tr><td >--><?php //echo _('configuration_deplacementtempsabscencevib'); ?><!--:&nbsp;</td>-->
<!--									<td><select name="detection_arret_temps_absence_vib" id="detection_arret_temps_absence_vib" class="geo3x_select_config">-->
<!--										<option value="30" >30s</option><option value="45" >45s</option>-->
<!--										<option value="60">1mn</option><option value="90" >1mn30s</option><option value="120" >2mn</option><option value="150" >2mn30s</option><option value="180" >3mn</option>-->
<!--										<option value="240" >4mn</option><option value="300" >5mn</option><option value="600" >10mn</option>-->
<!--									</select> -->
<!--								</td></tr>-->
<!--								<tr>	<td><input type="button" class="btn btn-default btn-xs" onClick="validDeplacementArret()" value="--><?php //echo _('valider'); ?><!--"></td>-->
<!--								</tr>-->
<!--							</table>-->
						</div>	
					</div>
				</div>
			</div>	
		</div>