<?php

/*
* Affichage la page d'onglet geofencing
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
		
<div id="TheContenu" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 " >
	<div class="row">
		<div class="col-lg-3" >
			<div class="panel panel-default"  >
				<div class="panel-heading">
					<i class="fa fa-bar-chart-o fa-fw"></i><a style="color: black" data-toggle="collapse" data-parent="#accordion" href="#parametre"> Geofencing - <?php echo _('geofencing_parametre'); ?> </a>
					<div class="pull-right">
						<div class="btn-group">
							<button type="button" class="btn btn-default btn-xs dropdown-toggle" onClick="afficherFichierLogGeofencing();" value="Fichier log "><?php echo _('geofencing_fichierlog'); ?></button>

						</div>
					</div>
				</div>
				<div id="parametre" class="panel-collapse collapse in" style="font-size: 12px" >
					<form class="form-horizontal" role="form" style="padding-left: 15px;padding-top: 5px;padding-right: 20px;">
						<div class="form-group"  >
							<div class="col-xs-8">
								<b>&nbsp; 1) <?php echo _('geofencing_choisirnumerozone'); ?>:</b>
							</div>
							<div class="col-xs-3">
								<div id="div_selectzone_geofenging">
									<select id="select_geofencing_zone" onChange="selectZone(this.value);" class="form-control input-xs" style="width:75px">
										<option value="all" selected><?php echo _('geofencing_toutes'); ?></option>
										<?php
											$nbreGenfencing = 10;
											for($i = 1 ; $i <= $nbreGenfencing ; $i++)
												echo '<option value="'.$i.'" >'.$i.'</option>';
										?>
<!--										<option value="1" >1</option>-->
<!--										<option value="2" >2</option>-->
<!--										<option value="3" >3</option>-->
<!--										<option value="4" >4</option>-->
<!--										<option value="5" >5</option>-->
									</select>
								</div>

							</div>
						</div>
						<div class="form-group" style="display:none" >
							<div class="col-xs-8">
								&nbsp;<?php echo _('geofencing_adresseapproxizone'); ?>:&nbsp;
								<input type="text"  class="form-control input-xs" id="input_adresse_zone" placeholder="Optionnel">

							</div>
							<div class="col-xs-3"><br/>
								<input type="button" id="input_button_view" value="View" onClick="viewAdresseZone()" class="btn btn-default btn-xs"  style="width:75px" >

							</div>
						</div>
						<div class="col-xs-12">
							&nbsp;<?php echo _('geofencing_adresseapproxizone'); ?>&nbsp;
						</div>
						<div class="form-group"  >
							<div class="col-xs-12"><br/>
								<b>&nbsp;2) <?php echo _('geofencing_cliquersurcartopolygone'); ?>.</b><br/><br/>

							</div>
							<div class="col-xs-12">
								<?php echo _('geofencing_warningpolygonelarge'); ?>

							</div>

						</div>
						<div class="form-group"  >	<br/>
							<div  class="col-xs-8">
								<b id="message_active_desactive">&nbsp;3) <?php echo _('messagedesactive'); ?></b>
							</div>
							<div class="col-xs-2">
								<input type="checkbox" id="checkbox_alert_message_desactive" onclick="onCheckMessageActiveDesactive(this);">
								<a href="#" data-toggle="modal" data-target="#info_message_active_desactive"> <i class="fa fa-info-circle info"></i></a>
								<div class="modal fade" id="info_message_active_desactive" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<br><br><br><br>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-body">
												<p><b><?php echo _('geofencing_modaletitle_activealarme'); ?></b></p>
												<?php echo _('geofencing_modalecontenu_activealarme'); ?>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group"  >
							<div class="col-xs-8">
								<?php echo _('geofencing_messageentree'); ?>:&nbsp;<br/>
										<input  id="message_entree" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'a');" maxlength="29" type="text" placeholder="<?php echo _('geofencing_alerteentree'); ?> " class="form-control input-xs">
								<br/>
								<?php echo _('geofencing_messagesortie'); ?>:&nbsp;<br/>
										<input id="message_sortie" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'a');"  maxlength="29" type="text" placeholder="<?php echo _('geofencing_alertefin'); ?>" class="form-control input-xs">
								<br/>
							</div>
							<div class="col-xs-2" style="padding-top: 6px"><br/><br/>
								<a href="#" data-toggle="modal" data-target="#info_message_alerte"> <i class="fa fa-info-circle info"></i></a>
								<div class="modal fade" id="info_message_alerte" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<br><br><br><br>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-body">
												<p><b><?php echo _('geofencing_modaletitle_alert'); ?></b></p>
												<?php echo _('geofencing_modalecontenu1_alert'); ?>
												<br>
												<?php echo _('geofencing_modalecontenu2_alert'); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-12"><br/></div>

						</div>
					</form>
					<div class="col-xs-12" id="contenu_message_active_desactive" style="display:none">
						<table class="table table-borderless" >
							<tr >
								<td style="padding: 0; margin: 0">
									<table class="table table-bordered"  style="padding: 0; margin: 0">
										<tr style="padding: 0; margin: 0">
											<td style="padding-left: 10px; margin: 0">
												<div class="form-group"  >
													<div class="col-xs-12" >
														<b><?php echo _('transmettresmsnumeros'); ?> :</b>
													</div>
													<div class="col-xs-3 col-xs-offset-6" ><br/>
														<?php echo _('geofencing_entree'); ?>
													</div>
													<div class="col-xs-3"><br/>
														<?php echo _('geofencing_sortie'); ?>
													</div>
													<!--div class="col-xs-6">
														Mail: <input id="mailgeo" onpaste="return false;" type="email"  class="form-control input-xs" style="margin-top:8px" maxlength="30">
													</div>
													<div class="col-xs-3"><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<input type="checkbox" id="apparition_mailgeo" name="" value="" onclick="onCheckNumeroApparitionDisparition(1)">
													</div>
													<div class="col-xs-3"><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<input type="checkbox" id="disparition_mailgeo" name="" value="" onclick="onCheckNumeroApparitionDisparition(1)">
													</div>
													<div class="col-xs-12"><br/></div-->
													<div class="col-xs-6">
														<?php echo _('numero'); ?> 1: 	<input id="message_numero_1" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'n');"  type="text"  class="form-control input-xs" style="margin-top:8px" maxlength="15"><!--input  class="form-control input-xs" id="message_numero_1" type="text" onblur="valider_numero(this)"-->
													</div>
													<div class="col-xs-3"><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<input type="checkbox" id="apparition_numero_1" name="" value="" onclick="onCheckNumeroApparitionDisparition(1)">
													</div>
													<div class="col-xs-3"><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<input type="checkbox" id="disparition_numero_1" name="" value="" onclick="onCheckNumeroApparitionDisparition(1)">
													</div>
													<div class="col-xs-12"><br/></div>
													<div class="col-xs-6">
														<?php echo _('numero'); ?> 2: 	<input id="message_numero_2" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'n');"  type="text"  class="form-control input-xs" style="margin-top:8px" maxlength="15"><!--input class="form-control input-xs" id="message_numero_2" type="text" onblur="valider_numero(this)"-->
													</div>
													<div class="col-xs-3"><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<input type="checkbox" id="apparition_numero_2" name="" value="" onclick="onCheckNumeroApparitionDisparition(2)">
													</div>
													<div class="col-xs-3"><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<input type="checkbox" id="disparition_numero_2" name="" value="" onclick="onCheckNumeroApparitionDisparition(2)">
													</div>
													<div class="col-xs-12"><br/></div>
													<div class="col-xs-6">
														<?php echo _('numero'); ?> 3: 	<input id="message_numero_3" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'n');"  type="text"  class="form-control input-xs" style="margin-top:8px" maxlength="15"><!--input  class="form-control input-xs" id="message_numero_3" type="text" onblur="valider_numero(this)"-->
													</div>
													<div class="col-xs-3"><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<input type="checkbox" id="apparition_numero_3" name="" value="" onclick="onCheckNumeroApparitionDisparition(3)">
													</div>
													<div class="col-xs-3"><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<input type="checkbox" id="disparition_numero_3" name="" value="" onclick="onCheckNumeroApparitionDisparition(3)">
													</div>
													<div class="col-xs-12"><br/></div>
													<div class="col-xs-6">
														<?php echo _('numero'); ?> 4: 	<input id="message_numero_4" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'n');"  type="text"  class="form-control input-xs" style="margin-top:8px" maxlength="15"><!--input class="form-control input-xs" id="message_numero_4" type="text" onblur="valider_numero(this)"-->
													</div>
													<div class="col-xs-3"><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<input type="checkbox" id="apparition_numero_4" name="" value="" onclick="onCheckNumeroApparitionDisparition(4)">
													</div>
													<div class="col-xs-3"><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<input type="checkbox" id="disparition_numero_4" name="" value="" onclick="onCheckNumeroApparitionDisparition(4)">
													</div>
													<div class="col-xs-12"><br/></div>
													<div class="col-xs-12"><br/></div>
												</div>

											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>

			<!--		<div class="panel-body" style="overflow:hidden;border-radius:10px 10px 10px 10px; "> -->
					<table class="table table-borderless">

						<tr>
							<td colspan="2">
								<input type="button" value="<?php echo _('geofencing_supprimerzone'); ?>" class="btn btn-default btn-xs dropdown-toggle" onclick="deleteZone()">
							</td>
							<td>
								<input type="button" value="<?php echo _('geofencing_validerzone'); ?>" class="btn btn-default btn-xs dropdown-toggle" onclick="validZone()">
							</td>
						</tr>
						<tr>

									<td colspan="2">

										<div class="modal fade" id="fichier_log" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<br><br><br><br>
											<div class="modal-dialog">
												<div class="modal-content">
													<div id="fichier_log_modal" class="modal-body">

													</div>
												</div>
											</div>
										</div>
									</td>

						</tr>
					</table>
					</div>
			<!--	</div> -->
			</div>
		</div>
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-bar-chart-o fa-fw"></i><a style="color: black" data-toggle="collapse" data-parent="#accordion" href="#map"> Geofencing - <?php echo _('cartographie'); ?></a>
					<div class="pull-right">
					</div>
				</div>
				<div id="map" class="panel-collapse collapse in">
					<div class="panel-body">
						<div id="basicMap" style="width: 100%;  height:750px; display:none; "></div>
						<div id="map_canvas" style="width: 100%;  min-height: 200px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>