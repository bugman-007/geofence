<?php

	/*
	* Affiche planing gsm
	*
	*/
session_start();
$_SESSION['CREATED'] = time();
require_once ("../../../lib/php-gettext-1.0.12/gettext.inc");
include '../ChromePhp.php';
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

//Chromephp::log();
?>
<div id="radio" class="panel panel-default">
	<div id="" class="panel-collapse collapse in">
		<div class="panel-body blue" >
			<div class="container-fluid" style="overflow:hidden; padding:3px;border-radius:10px 10px 10px 10px;">
				<div class="panel-body">
					<div class="modal fade" id="info_planing" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<br><br><br><br>
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">
										<h5><b>Planning Télésurveillance</b></h5>
											<br>Permet de règler les heures de télésurveillance	
									</div>
								</div>
							</div>
					</div>
					<form class="form-horizontal" role="form" style="font-size: 14px;">
						<div class="form-group">
							<div class="col-md-12" >
								&nbsp; &nbsp;<i class="fa fa-sitemap fa-fw"></i>&nbsp;
									<label class="control-label">Planning Télésurveillance
										<?php //echo _('configuration_parametredetectdeplacementarret'); ?>:
										<a href="#" data-toggle="modal" data-target="#info_planing"><i class="fa fa-info-circle info"></i></a>
									</label>
							</div>
						</div>
						<div style="border: 0; height: 1px; background: #aaa; background-image: linear-gradient(to right, lightgray, lightgray, lightgray);"></div>
						<br/>
					</form>
					<form class="form-horizontal" role="form">
						<script></script>
						<center>
							<table>
								<thead>
									<tr>
										<th colspan width=16%>Activer</th>
										<th colspan width=20%>Jours</th>
										<th colspan="2" width=32%>Créneau 1</th>
										<th colspan="2" width=32%>Créneau 2</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<input id="act1" type="checkbox" class="control-form" style="margin-top:7px;">
										</td>
										<td>
											<select id="jour1" class="control-form" style="margin-top:7px;">
											<option selected value="1">Lundi</option><option value="2">Mardi</option><option value="3">Mercredi</option><option value="4">Jeudi</option><option value="5">vendredi</option><option value="6">Samedi</option><option value="7">Dimanche</option><option value="8">Tous les jours</option>
											</select>
										</td>
										<td>
											<input id="deb11" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="fin11" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="deb12" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="fin12" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
									</tr>
									<tr>
										<td>
											<input id="act2" type="checkbox" class="control-form" style="margin-top:7px;">
										</td>
										<td>
											<select id="jour2" class="control-form" style="margin-top:7px;">
											<option value="1">Lundi</option><option selected value="2">Mardi</option><option value="3">Mercredi</option><option value="4">Jeudi</option><option value="5">vendredi</option><option value="6">Samedi</option><option value="7">Dimanche</option><option value="8">Tous les jours</option>
											</select>
										</td>
										<td>
											<input id="deb21" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="fin21" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="deb22" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="fin22" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
									</tr>
									<tr>
										<td>
											<input id="act3" type="checkbox" class="control-form" style="margin-top:7px;">
										</td>
										<td>
											<select id="jour3" class="control-form" style="margin-top:7px;">
											<option value="1">Lundi</option><option value="2">Mardi</option><option selected value="3">Mercredi</option><option value="4">Jeudi</option><option value="5">vendredi</option><option value="6">Samedi</option><option value="7">Dimanche</option><option value="8">Tous les jours</option>
											</select>
										</td>
										<td>
											<input id="deb31" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="fin31" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="deb32" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="fin32" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
									</tr>
									<tr>
										<td>
											<input id="act4" type="checkbox" class="control-form" style="margin-top:7px;">
										</td>
										<td>
											<select id="jour4" class="control-form" style="margin-top:7px;">
											<option value="1">Lundi</option><option value="2">Mardi</option><option value="3">Mercredi</option><option selected value="4">Jeudi</option><option value="5">vendredi</option><option value="6">Samedi</option><option value="7">Dimanche</option><option value="8">Tous les jours</option>
											</select>
										</td>
										<td>
											<input id="deb41" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="fin41" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="deb42" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="fin42" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
									</tr>
									<tr>
										<td>
											<input id="act5" type="checkbox" class="control-form" style="margin-top:7px;">
										</td>
										<td>
											<select id="jour5" class="control-form" style="margin-top:7px;">
											<option value="1">Lundi</option><option value="2">Mardi</option><option value="3">Mercredi</option><option value="4">Jeudi</option><option selected value="5">vendredi</option><option value="6">Samedi</option><option value="7">Dimanche</option><option value="8">Tous les jours</option>
											</select>
										</td>
										<td>
											<input id="deb51" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="fin51" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="deb52" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="fin52" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
									</tr>
									<tr>
										<td>
											<input id="act6" type="checkbox" class="control-form" style="margin-top:7px;">
										</td>
										<td>
											<select id="jour6" class="control-form" style="margin-top:7px;">
											<option value="1">Lundi</option><option value="2">Mardi</option><option value="3">Mercredi</option><option value="4">Jeudi</option><option value="5">vendredi</option><option selected value="6">Samedi</option><option value="7">Dimanche</option><option value="8">Tous les jours</option>
											</select>
										</td>
										<td>
											<input id="deb61" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="fin61" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="deb62" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="fin62" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
									</tr>
									<tr>
										<td>
											<input id="act7" type="checkbox" class="control-form" style="margin-top:7px;">
										</td>
										<td>
											<select id="jour7" class="control-form" style="margin-top:7px;">
											<option value="1">Lundi</option><option value="2">Mardi</option><option value="3">Mercredi</option><option value="4">Jeudi</option><option value="5">vendredi</option><option value="6">Samedi</option><option selected value="7">Dimanche</option><option value="8">Tous les jours</option>
											</select>
										</td>
										<td>
											<input id="deb71" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="fin71" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="deb72" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
										<td>
											<input id="fin72" type="time" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time form_datetime input-xs" value="00:00" style="margin-top:7px;">
										</td>
									</tr>
								</tbody>
							</table>
						</center>

						<div class="col-md-offset-11">
							<input type="button" class="btn btn-default btn-xs" onClick="validplanning()"  value="<?php echo _('valider'); ?>">
						</div>
					</form>	
				</div>
			</div>
		</div>	
	</div>	
</div>	