<?php

	/*
	* Affiche l'onglet alerte et sms
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
<div id="alerteEtSms" >
	<div id="alerte" class="panel-collapse collapse in">
		<div class="container-fluid" style="overflow:hidden; padding:10px;border-radius:10px 10px 10px 10px; ">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-8">
						<div class="panel panel-default">
							<div class="panel-body blue" style="overflow:hidden; padding:10px;border-radius:10px 10px 10px 10px; ">
<!--							<b> &nbsp;	--><?php //echo _('configuration_alert'); ?><!--:&nbsp;</b>-->
								<form class="form-horizontal" role="form" style="font-size: 14px;">
									<div class="form-group">
										<div class="col-md-5" >
											&nbsp; &nbsp;<i class="fa fa-sitemap fa-fw"></i>&nbsp;
											<label for="select_mode_fonctionnement" class="control-label">
												<?php echo _('configuration_alert'); ?>s:
											</label>
										</div>
										<div class="col-md-2">
											<label for="select_mode_fonctionnement" class="control-label" style="font-weight: normal">
												Type: <a href="#" data-toggle="modal" data-target="#info_alerte_sms_type"><i class="fa fa-info-circle info"></i></a>
											</label>
										</div>
										<div class="col-md-4">
											<select id="select_type_alert" onChange="typeAlerte(this.value);" class="form-control input-xs" style="margin-top: 6px" >
<!--												<option value="nothing" ></option>-->
												<!--															<option value="alarmedeplacement" >Alarme D&eacute;placement</option>-->
												<!--															<option value="alarmeparking" >Alarme Parking</option>-->
												<!--															<option value="alarmealimentation" >Alarme Alimentation</option>-->
											</select>
										</div>
									</div>
									<div style="border: 0; height: 1px; background: #aaa; background-image: linear-gradient(to right, lightgray, lightgray, lightgray);"></div>
									<br/>
								</form>
								<div class="col-md-12">
									<form class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-4">
												<label for="alerte_filtrage" class="control-label" style="font-weight: normal">
													&nbsp;		<?php echo _('configuration_filtrage'); ?> (mn):
													<a href="#" data-toggle="modal" data-target="#info_alerte_sms_filtrage"><i class="fa fa-info-circle info"></i></a>
												</label>
											</div>
											<div class="col-md-2">
												<input id="alerte_filtrage" type="number" class="form-control input-xs" style="margin-top:8px" oninput="minLengthCheck(this)" min="0">
											</div>
											<div class="col-md-4">
												<label class="control-label" style="font-weight: normal" >
													&nbsp;	<?php echo _('configuration_tempsreel'); ?> (mn):
												</label>
											</div>
											<div class="col-md-2">
												<input id="input_alert_temps_reel" type="number" class="form-control input-xs" style="margin-top:8px"  min="0" disabled>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-4">
												<label class="control-label" style="font-weight: normal">
													&nbsp;		<?php echo _('configuration_normalement'); ?>:
												</label>
											</div>
											<div class="col-md-2">
												<select id="alertsms_normalement" class="form-control input-xs" style="margin-top:6px;" disabled>
													<option value="--choisir--" disabled selected>--</option>
													<option value="ouvert" ><?php echo _('configuration_ouvert'); ?></option>
													<option value="ferme" ><?php echo _('configuration_ferme'); ?></option>
												</select>
											</div>
											<div class="col-md-4">
												<label class="control-label" style="font-weight: normal">
													&nbsp;	<?php echo _('configuration_seuildebat'); ?> :
												</label>
											</div>
											<div class="col-md-2">
												<select id="select_seuil_bat" class="form-control input-xs" style="margin-top:6px" disabled>

												</select>
											</div>

										</div>
										<div id="abat" class="form-group" style="display:none">
											<div class="col-md-3"><label class="control-label" style="font-weight: normal">&nbsp; Activer Strat BF ?</label></div>
											<div class="col-md-1">
												<input id="asbf" type="checkbox" class="form-control input-xs" style="margin-top:8px"/>
											</div>
											
											<div class="col-md-2"><label class="control-label" style="font-weight: normal">&nbsp; &nbsp; Seuil 2</label></div>
											<div class="col-md-2">
												<select id="seuil2" class="form-control input-xs" style="margin-top:6px;">
													<option value="25">25%</option>
													<option value="30">30%</option>
													<option value="35">35%</option>
												</select>
											</div>
											
											<div class="col-md-2"><label class="control-label" style="font-weight: normal">&nbsp; &nbsp; Seuil 3</label></div>
											<div class="col-md-2">
												<select id="seuil3" class="form-control input-xs" style="margin-top:6px;">
													<option value="45">45%</option>
													<option value="50">50%</option>
													<option value="55">55%</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-5">
												<label class="control-label" style="font-weight: normal">
													&nbsp;		<?php echo _('configuration_messageapparition'); ?>:
												</label>
											</div>
											<div class="col-md-7">
												<input id="message_apparition" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'a'); "  type="text"  class="form-control input-xs" style="margin-top:8px" maxlength="30">
                                                                                               
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-5">
												<label class="control-label" style="font-weight: normal">
													&nbsp; <?php echo _('configuration_messagedisparition'); ?>:
												</label>
											</div>
											<div class="col-md-7">
												<input id="message_disparition" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'a');"  type="text"  class="form-control input-xs" style="margin-top:8px" maxlength="30">
											</div>
										</div>
									</form>

								</div>
								<table class="table table-borderless">

									<tr>
										<td>
											<table class="table table-borderless">
												<tr>
													<td>
														<div style="display: inline;" id="alert_active_desactive"><?php echo _('configuration_alertedesactivee'); ?>&nbsp;
														</div>
													</td>
													<td>&nbsp; <input type="checkbox" id="checkbox_alert_active_desactive" onclick="onCheckAlert(this);">
														<a href="#" data-toggle="modal" data-target="#info_alerte_sms_fichier_log"><i class="fa fa-info-circle info"></i></a>
													</td>
												</tr>
											</table>
										</td>

<!--													<td>-->
<!--													</td>-->
										<td colspan="2" >
											<div id="alert_active_parking"  style=" padding: 0; margin: 0; visibility:hidden">
<!--														<div id="alert_active_parking" style="visibility:hidden">-->


												<div class="panel-body" style="padding: 0; margin: 0">
													<table class="table table-bordered" style="padding: 0; margin: 0">

														<tr  style="padding: 0; margin: 0">
															<td  id="parking_sur_deplacement"  style="width:150px; padding: 0px 0px 0px 10px; margin: 0"><?php echo _('configuration_surdeplacement'); ?></td>
															<td style="padding: 0; margin: 0" > </td>
															<td style="padding: 0; margin: 0" ><center><input type="checkbox" id="checkbox_parking_sur_deplacement" name="" value="" onclick=" onCheckAlertePark(1)"></td></center>
														</tr>
														<tr  style="padding: 0; margin: 0" >
															<td style="padding: 0px 0px 0px 10px; margin: 0" id="parking_sur_vibration" ><?php echo _('configuration_survibration'); ?></td>
															<td style="padding: 0; margin: 0"><select name="detection_alerte_vibration" id="detection_alerte_vibration">
																	<option value="0" >0s</option><option value="30" >30s</option><option value="45" >45s</option>
																	<option value="60">1mn</option><option value="90" >1mn30s</option><option value="120" >2mn</option><option value="150" >2mn30s</option><option value="180" >3mn</option>
																	<option value="240" >4mn</option><option value="300" >5mn</option><option value="600" >10mn</option></option><option value="900" >15mn</option>
																</select>
															</td>
															<td style="padding: 0; margin: 0"><center><input type="checkbox" id="checkbox_parking_sur_vibration" name="" value="" onclick=" onCheckAlertePark(2)"></td></center>
														</tr>
														<tr  style="padding: 0; margin: 0">
															<td style="padding: 0px 0px 0px 10px; margin: 0" id="parking_sur_vitesse"><?php echo _('configuration_survitesse'); ?> (km/h)</td>
															<td style="padding: 0; margin: 0"><select name="detection_alerte_vitesse" id="detection_alerte_vitesse">
																	<option value="0" >0</option>
																	<option value="2" >2</option><option value="4" >4</option>
																	<option value="5">5</option><option value="7" >7</option><option value="9" >9</option><option value="11" >11</option><option value="15" >15</option>
																	<option value="18" >18</option><option value="26" >26</option><option value="40" >40</option>
																	<option value="60" >60</option><option value="65" >65</option><option value="70" >70</option>
																	<option value="80" >80</option><option value="90" >26</option><option value="90" >90</option>
																	<option value="95" >95</option><option value="100" >100</option><option value="120" >120</option>
																</select>
															</td>
															<td style="padding: 0; margin: 0"><center><input type="checkbox" id="checkbox_parking_sur_vitesse" name="" value="" onclick="onCheckAlertePark(3)"></td></center>
														</tr>
													</table>
												</div>


											</div>
										</td>

									</tr>

<!--									<tr >-->
<!--										<td >--><?php //echo _('configuration_messageapparition'); ?><!--:&nbsp;-->
<!--											</td><td colspan="2"><input style="width:280px;" id="message_apparition" type="text" maxlength="29">-->
<!--										</td>-->
<!--									</tr>-->
<!--									<tr>-->
<!--										<td >--><?php //echo _('configuration_messagedisparition'); ?><!--:&nbsp;<br/>-->
<!--										</td><td colspan="2"><input style="width:280px;" id="message_disparition" type="text" maxlength="29">-->
<!--										</td>-->
<!--									</tr>-->
									<tr>
										<td id="alert_active" colspan="3" style="visibility:hidden">



												<div class="panel-body "  style="padding: 0; margin: 0">
													<table class="table table-bordered"  style="padding: 0; margin: 0">

														<tr >
															<td colspan="3"><b><?php echo _('transmettresmsnumeros'); ?>:&nbsp;</b></td>
														</tr>
														<tr >
															<td></td>
															<td style="text-align: center"><?php echo _('apparition'); ?></td>
															<td style="text-align: center"><?php echo _('disparition'); ?></td>
														</tr>
														<tr>
															<td id="numero_1"><?php echo _('numero'); ?> 1</td>
															<td><center><input type="checkbox" id="apparition_numero_1" name="" value="" onclick="onCheckNumeroApparitionDisparition2(1)"></td></center>
															<td><center><input type="checkbox" id="disparition_numero_1" name="" value="" onclick="onCheckNumeroApparitionDisparition2(1)"></td></center>
														</tr>
														<tr>
															<td id="numero_2"><?php echo _('numero'); ?> 2</td>
															<td><center><input type="checkbox" id="apparition_numero_2" name="" value="" onclick="onCheckNumeroApparitionDisparition2(2)" ></td></center>
															<td><center><input type="checkbox" id="disparition_numero_2" name="" value="" onclick="onCheckNumeroApparitionDisparition2(2)"></td></center>
														</tr>
														<tr>
															<td id="numero_3"><?php echo _('numero'); ?> 3</td>
															<td><center><input type="checkbox" id="apparition_numero_3" name="" value="" onclick="onCheckNumeroApparitionDisparition2(3)" ></td></center>
															<td><center><input type="checkbox" id="disparition_numero_3" name="" value="" onclick="onCheckNumeroApparitionDisparition2(3)"></td></center>
														</tr>
														<tr>
															<td id="numero_4"><?php echo _('numero'); ?> 4</td>
															<td><center><input type="checkbox" id="apparition_numero_4" name="" value="" onclick="onCheckNumeroApparitionDisparition2(4)" ></td></center>
															<td><center><input type="checkbox" id="disparition_numero_4" name="" value="" onclick="onCheckNumeroApparitionDisparition2(4)" ></td></center>
														</tr>
													</table>
												</div>



										</td>
									</tr>
									<tr>
										<td colspan="2" style="padding: 10px 0px 0px 10px">
											<input type="button" onClick="validAlert()" class="btn btn-default btn-xs" value="<?php echo _('valider'); ?>"> <a href="#" data-toggle="modal" data-target="#info_alerte_sms_message"><i class="fa fa-info-circle info"></i></a>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="modal fade" id="info_alerte_sms_type" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<br><br><br><br>
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">
										<p><b>	<?php echo _('configuration_alarmedeplacement'); ?>: </b>	</p>
										<?php echo _('configuration_modalecontenu1_alerttype'); ?>
												<br><br>
										<p><b><?php echo _('configuration_alarmeparking'); ?>: </b>	</p>
										<?php echo _('configuration_modalecontenu2_alerttype'); ?> <br>
										<?php echo _('configuration_modalecontenu3_alerttype'); ?>
												<br> <br>
										<p><b><?php echo _('configuration_alarmealimentation'); ?>: </b>	</p>
										<?php echo _('configuration_modalecontenu4_alerttype'); ?>
												<br>
									</div>
								</div>
							</div>
						</div>
						<div class="modal fade" id="info_alerte_sms_fichier_log" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<br><br><br><br>
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">
										<p><b><?php echo _('configuration_modalectitle_fichierlog'); ?> </b>	</p>
										<?php echo _('configuration_modaleccontenu_fichierlog'); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="modal fade" id="info_alerte_sms_filtrage" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<br><br><br><br>
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">
										<p><b><?php echo _('configuration_filtrage'); ?> </b>	</p>
										<?php echo _('configuration_modaleccontenu1_filtrage'); ?>
											<br>
										<?php echo _('configuration_modaleccontenu2_filtrage'); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="modal fade" id="info_alerte_sms_message" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<br><br><br><br>
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">
										<p><b>	<?php echo _('configuration_modalectitle_messagealerte'); ?> </b>	</p>
										<?php echo _('configuration_modaleccontenu_messagealerte'); ?>
										</div>
								</div>
							</div>
						</div>
					</div>
						<div class="col-lg-4">
							<div class="panel panel-default">
								<div class="panel-body blue" style="overflow:hidden; padding:10px;border-radius:10px 10px 10px 10px; ">
<!--									<b>	--><?php //echo _('configuration_telephone'); ?><!--:&nbsp;</b>  <a href="#" data-toggle="modal" data-target="#info_alerte_sms_telephone"><i class="fa fa-info-circle info"></i></a>-->
									<form class="form-horizontal" role="form" style="font-size: 14px;">
										<div class="form-group">
											<div class="col-md-8" >
												&nbsp; &nbsp;<i class="fa fa-sitemap fa-fw"></i>&nbsp;
												<label for="select_mode_fonctionnement" class="control-label"><?php echo _('configuration_telephone'); ?>:
													<a href="#" data-toggle="modal" data-target="#info_alerte_sms_telephone"><i class="fa fa-info-circle info"></i></a>
												</label>
											</div>

										</div>
										<div style="border: 0; height: 1px; background: #aaa; background-image: linear-gradient(to right, lightgray, lightgray, lightgray);"></div>
										<br/>
										<div class="form-group">
											<div class="col-md-3">
												<label class="control-label" style="font-weight: normal">
													&nbsp;		N&deg;1:&nbsp;
												</label>
											</div>
											<div class="col-md-8">
                                                                                                <input id="message_numero_1" onpaste="return true;" onkeyup="return verifierCaracteres(event,this.id,'n');"  type="text"  class="form-control input-xs" style="margin-top:8px" maxlength="15">
												<!--input id="message_numero_1" type="text" onblur="valider_numero(this)" class="form-control input-xs" style="margin-top:8px"-->
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-3">
												<label class="control-label" style="font-weight: normal">
													&nbsp;		N&deg;2:&nbsp;
												</label>
											</div>
											<div class="col-md-8">
                                                                                            <input id="message_numero_2" onpaste="return true;"  onkeyup="return verifierCaracteres(event,this.id,'n');"  type="text"  class="form-control input-xs" style="margin-top:8px" maxlength="15">
												<!--input id="message_numero_2" type="text" onblur="valider_numero(this)" class="form-control input-xs" style="margin-top:8px"-->
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-3">
												<label class="control-label" style="font-weight: normal">
													&nbsp;		N&deg;3:&nbsp;
												</label>
											</div>
											<div class="col-md-8">
                                                <input id="message_numero_3" onpaste="return true;"  onkeyup="return verifierCaracteres(event,this.id,'n');"  type="text"  class="form-control input-xs" style="margin-top:8px" maxlength="15">
												<!--input id="message_numero_3" type="text" onblur="valider_numero(this)" class="form-control input-xs" style="margin-top:8px"-->
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-3">
												<label class="control-label" style="font-weight: normal">
													&nbsp;		N&deg;4:&nbsp;
												</label>
											</div>
											<div class="col-md-8">
                                                <input id="message_numero_4" onpaste="return true;"  onkeyup="return verifierCaracteres(event,this.id,'n');"  type="text"  class="form-control input-xs" style="margin-top:8px" maxlength="15">
												<!--input id="message_numero_4" type="text" onblur="valider_numero(this)" class="form-control input-xs" style="margin-top:8px"-->
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-8">
											</div>
											<div class="col-md-3">
												<input type="button" onClick="validTelephone()"  class="btn btn-default btn-xs"  value="<?php echo _('valider'); ?>">
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-1">
											</div>
											<div class="col-md-11">
												<?php echo _('configuration_infostelephone'); ?>
											</div>
										</div>
									</form>
										<!--table class="table table-borderless">
											<tr>
												<td>N&deg;1:&nbsp;>
													<input id="message_numero_1" type="text" onblur="valider_numero(this)">
												</td>
											</tr>
											<tr><td>N&deg;2:&nbsp;
													<input id="message_numero_2" type="text" onblur="valider_numero(this)">
												</td>
											</tr>
											<tr><td >N&deg;3:&nbsp;
													<input id="message_numero_3" type="text" onblur="valider_numero(this)">
											</td></tr>
											<tr><td>N&deg;4:&nbsp;
												<input id="message_numero_4" type="text" onblur="valider_numero(this)">
												</td>
											</tr>
											<tr>	<td >
												</td></tr>
										</table--> <?php //echo _('configuration_infostelephone'); ?>
									<div class="modal fade" id="info_alerte_sms_telephone" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<br><br><br><br>
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-body">
													<p><b><?php echo _('configuration_modalectitle_telephonesms'); ?> </b>	</p>
													<?php echo _('configuration_modaleccontenu1_telephonesms'); ?>
													<br>
													<?php echo _('configuration_modaleccontenu2_telephonesms'); ?>
													<br>
													<?php echo _('configuration_modaleccontenu3_telephonesms'); ?>
													<br>
													<?php echo _('remarques'); ?>
													<br>
													<?php echo _('configuration_modaleccontenu4_telephonesms'); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

<!--
						<div class="col-lg-4">
							<div class="panel panel-default">
								<div class="panel-body blue" style="overflow:hidden; padding:10px;border-radius:10px 10px 10px 10px; ">
									<form class="form-horizontal" role="form" style="font-size: 14px;">
										<div class="form-group">
											<div class="col-md-8" >
												&nbsp; &nbsp;<i class="fa fa-sitemap fa-fw"></i>&nbsp;
												<label for="select_mode_fonctionnement" class="control-label">Mail:
													<a href="#" data-toggle="modal" data-target="#info_alerte_sms_mail"><i class="fa fa-info-circle info"></i></a>
												</label>
											</div>

										</div>
										<div style="border: 0; height: 1px; background: #aaa; background-image: linear-gradient(to right, lightgray, lightgray, lightgray);"></div>
										<br/>
										<div class="form-group">
											<div class="col-md-3">
												<label class="control-label" style="font-weight: normal">
													&nbsp;  Email:&nbsp;
												</label>
											</div>
											<div class="col-md-8">
                                                <input id="mail_as" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'n');"  type="text"  class="form-control input-xs" style="margin-top:8px">
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-8">
											</div>
											<div class="col-md-3">
												<input type="button" onClick="validMail()"  class="btn btn-default btn-xs"  value="<?php echo _('valider'); ?>">
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-1">
											</div>
											<div class="col-md-11">
												<?php //echo _('configuration_infostelephone'); ?>
												Cet email est utilis&eacute; par l'ensemble des alarmes de la balise
											</div>
										</div>
									</form>
									<div class="modal fade" id="info_alerte_sms_mail" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<br><br><br><br>
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-body">
													<p><b>Mail SMS</b>	</p>
													Permet de renseigner l'email vers lequel la balise enverra ses diff&eacute;rentes alarmes.	
													<br>
													Toute modification effectu&eacute;e dans le champ ci-dessous entrainera une modification du destinataire pour l'ensemble des alarmes.	
													<br>
													L'utilisation ou non de l'email renseign&eacute; se fait au niveau de chaque alarme (case coch&eacute;e/d&eacute;coch&eacute;e au niveau de r&eacute;glage de chaque alarme).	
													<br>
													Remarque
													<br>
													L'email doit &ecirc;tre renseign&eacute; pour pouvoir utiliser les alarmes; une attention toute particuli&egrave;re doit &ecirc;tre port&eacute;e au fait que l'email renseign&eacute; doit &ecirc;tre valide.
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						-->
				</div>
			</div>
		</div>
	</div>

</div>

