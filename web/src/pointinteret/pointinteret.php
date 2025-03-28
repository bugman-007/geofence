<?php
/*
* Afficher la page point interet
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
<style type="text/css">
	.sortable td:hover {
		cursor: pointer;
	}
	.sortable th, td{
		text-align: center;
	}
	.sortable2 td:hover {
		cursor: pointer;
	}
	.sortable2 th, td{
		text-align: left;
	}
</style>
<div id="TheContenu" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >

	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<span class="glyphicon glyphicon-globe" aria-hidden="true"></span><a style="color: black" data-toggle="collapse" data-parent="#accordion" href="#map"> <?php echo _('cartographie'); ?> - <?php echo _('layout_pointinteret'); ?></a>
				</div>
				<script>
				map = L.map('map_canvas', {
				center: [47.081012, 2.398782],
				zoom: 6
				});
				//some provider https://leaflet-extras.github.io/leaflet-providers/preview/
				var basemaps = {
					Basic : L.tileLayer('https://api.maptiler.com/maps/basic/{z}/{x}/{y}.png?key=EevE8zHrA8OKNsj637Ms',{minZoom: 3,maxZoom: 20, attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">© MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">© OpenStreetMap contributors</a>',crossOrigin: true}),
					//Bright : L.tileLayer('https://maps.tilehosting.com/styles/bright/{z}/{x}/{y}.png?key=EevE8zHrA8OKNsj637Ms', {minZoom: 3, maxZoom: 19, attribution: ' <a href="https://www.maptiler.com/license/maps/" target="_blank">&copy; MapTiler</a> &#124; <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'}),
					Bright : L.tileLayer('https://api.maptiler.com/maps/bright/{z}/{x}/{y}.png?key=EevE8zHrA8OKNsj637Ms',{minZoom: 3,maxZoom: 20, attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">© MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">© OpenStreetMap contributors</a>',crossOrigin: true}),
					Topo : L.tileLayer('https://api.maptiler.com/maps/topo/{z}/{x}/{y}.png?key=EevE8zHrA8OKNsj637Ms',{minZoom: 3,maxZoom: 20, attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">© MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">© OpenStreetMap contributors</a>',crossOrigin: true}),
					//Satellite : L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { minZoom: 3, maxZoom: 19, attribution: ' <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap</a>' }),
					Satellite : L.tileLayer('https://api.maptiler.com/tiles/satellite/{z}/{x}/{y}.jpg?key=EevE8zHrA8OKNsj637Ms',{minZoom: 3,maxZoom: 20,attribution: "<a href=\"https://www.maptiler.com/copyright/\" target=\"_blank\"><copy; MapTiler</a\><a href=\"https://www.openstreetmap.org/copyright\" target=\"_blank\"><copy; OpenStreetMap contributors</a>",crossOrigin: true}),
					SatelliteHD : L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', { minZoom: 3, maxZoom: 20}),
					Hybride : L.tileLayer('http://www.google.cn/maps/vt?lyrs=y@189&gl=cn&x={x}&y={y}&z={z}', { minZoom: 3, maxZoom: 20}) // h:route, m:standard, p:terrain, r: route altérée, s:satellite, t:terrain seulement, y:hybrid 
					//Positron : L.tileLayer('https://maps.tilehosting.com/styles/positron/{z}/{x}/{y}.png?key=EevE8zHrA8OKNsj637Ms', {minZoom: 3, maxZoom: 19, attribution: ' <a href="https://www.maptiler.com/license/maps/" target="_blank">&copy; MapTiler</a> &#124; <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'}),
					//Street : L.tileLayer('https://maps.tilehosting.com/styles/streets/{z}/{x}/{y}.png?key=EevE8zHrA8OKNsj637Ms',{minZoom: 3, maxZoom: 19, attribution: ' <a href="https://www.maptiler.com/license/maps/" target="_blank">&copy; MapTiler</a> &#124; <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'}),
					//Routier : L.tileLayer('http://korona.geog.uni-heidelberg.de/tiles/roads/x={x}&y={y}&z={z}', {minZoom: 3, maxZoom: 18, attribution: ' <a href="https://www.maptiler.com/license/maps/" target="_blank">&copy; MapTiler</a> &#124; <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'}),
					//Satellite : L.tileLayer('https://maps.tilehosting.com/styles/hybrid/{z}/{x}/{y}.jpg?key=EevE8zHrA8OKNsj637Ms', {minZoom: 3, maxZoom: 18, attribution: ' <a href="https://www.maptiler.com/license/maps/" target="_blank">&copy; MapTiler</a> &#124; <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'}),
				};

				L.control.layers(basemaps).addTo(map);
				basemaps.Bright.addTo(map);
				L.control.scale().addTo(map);
				
				geocoder = new L.Control.geocoder({ defaultMarkGeocode: false})
						.on('markgeocode', function(e) { visualiserClique(e.geocode.center) })
						.addTo(map);
				</script>
				<div id="map" class="panel-collapse collapse in">
					<div class="panel-body">
						<div id="basicMap" style="width: 100%;  height:750px; display:none; "></div>
						<div id="map_canvas" style="width: 100%; min-height: 200px;"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3" >
			<div class="panel panel-default">
				<div class="panel-heading">
					<span class="glyphicon glyphicon-globe" aria-hidden="true"></span><a style="color: black" data-toggle="collapse" data-parent="#accordion" href="#table"> Table Position POI </a>
				</div>
				<div id="table" class="panel-collapse collapse in">
					<div class="panel-body">
						<div id="divTable" class="table-responsive" style="height:214px;overflow:auto;">
							<!--							--><?php //include 'pointinterettablepospoi.php'?>
							<table id="idTablePositionPOI" class="sortable table table-bordered  table-hover">
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<span class="glyphicon glyphicon-globe" aria-hidden="true"></span><a style="color: black" data-toggle="collapse" data-parent="#accordion" href="#insert"> Point Interet - <?php echo _('geofencing_parametre'); ?> </a>
				</div>
				<div id="insert" class="panel-collapse collapse in">
					<div class="panel-body" style="min-height:586px; overflow:auto">
						<center>
							<?php
							echo "<input type='text' id='latbox' name='lat' placeholder='Cliquer sur la carte' style='display:none'>";
							echo "<input type='text' id='lngbox' name='lng'placeholder='Cliquer sur la carte' style='display:none'>";
							?>
							<table class="table table-borderless">
								<tr>
									<td colspan="3"><b>&nbsp;1) <?php echo _('ptinteret_insererptinteret'); ?>: </b></td>
								</tr>
								<tr>
									<td colspan="3">
										-  <?php echo _('ptinteret_soitsaisiradresse'); ?>:
									</td>
								</tr>
								<tr style='display:none'>
									<td colspan="3">

											<input type='text' id='adresse' style="width:250px" class="geo3x_input_datetime" placeholder='<?php echo _('veuillezsaisiruneadresse'); ?>'/>
											&nbsp;&nbsp;<input type='button' class="btn btn-default btn-xs dropdown-toggle" onclick='visualiserAdresse()' value='<?php echo _('ptinteret_visualiser'); ?>' class='button'/>

									</td>
								</tr>
								<tr>
									<td colspan="3">
										-  <?php echo _('ptinteret_soitcliquer'); ?>
									</td>
								</tr>
								<tr>
									<td colspan="3">
									</td>
								</tr>

								<tr id="tr_2eme_etape">
									<td colspan="3">
										<b>&nbsp;2) <?php echo _('ptinteret_visualiserunpoi'); ?></b><br/><br/>
									</td>
								</tr>

								<tr id="tr_3eme_etape" style=" display:none ">
									<td colspan="2">
										<div ><b id="alerte_active_desactive">&nbsp;3) <?php echo _('messagedesactive'); ?></b></div>
									</td>
									<td style="text-align:left">
										<input type="checkbox" id="checkbox_alert_message_desactive" onclick="onCheckMessageActiveDesactivePOI(this);">
									</td>
								</tr>
								<tr  id="tr_message_arrivee" style=" display:none ">
									<td colspan="2"><?php echo _('ptinteret_messagearrive'); ?>&nbsp;<br/>
                                                                            <input id="message_arrivee" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'a'); "  type="text" placeholder="Alerte d'arrivee de zone" class="form-control input-xs" style="margin-top:8px" maxlength="30">
										<!--input style="width:250px" class="geo3x_input_text" id="message_arrivee" type="text" placeholder="Alerte d'arrivee de zone"-->
									</td>
								</tr>
								<tr  id="tr_message_depart" style=" display:none ">
									<td colspan="2"><?php echo _('ptinteret_messagedepart'); ?>:&nbsp;<br/>
                                                                            <input id="message_depart" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'a'); "  type="text" placeholder="Alerte de depart de zone" class="form-control input-xs" style="margin-top:8px" maxlength="30">
										<!--input style="width:250px;" class="geo3x_input_text" id="message_depart" type="text" placeholder="Alerte de depart de zone"-->
									</td>
								</tr>
								<tr><td></td></tr>
								<tr>
									<td colspan="3" style="height: 200px">
										<div id="contenu_alerte_active_desactive" style="display:none">
											<table >
												<tr >
													<td><?php echo _('transmettresmsnumeros'); ?>:</td>
													<td>&nbsp;&nbsp;&nbsp;</td>
													<td><?php echo _('ptinteret_arrive'); ?></td>
													<td>&nbsp;&nbsp;&nbsp;</td>
													<td><?php echo _('ptinteret_depart'); ?></td>
												</tr>

												<tr><td>&nbsp;</td></tr>
												<tr>
													<td><?php echo _('numero'); ?> 1: 	<input id="message_numero_1" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'n');"  type="text"  class="form-control input-xs" style="margin-top:8px" maxlength="15"><!--input style="width:150px" class="geo3x_input_text" id="message_numero_1" type="text" onblur="valider_numero(this)"--> </td>
													<td></td>
													<td><center><input type="checkbox" id="arrivee_numero_1" name="" value="" onclick="onCheckNumeroArriveeDepart(1)"></td></center>
													<td></td>
													<td><center><input type="checkbox" id="depart_numero_1" name="" value="" onclick="onCheckNumeroArriveeDepart(1)"></td></center>
												</tr>
												<tr>
													<td><?php echo _('numero'); ?> 2: <input id="message_numero_2" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'n');"  type="text"  class="form-control input-xs" style="margin-top:8px" maxlength="15"><!--input style="width:150px" class="geo3x_input_text" id="message_numero_2" type="text" onblur="valider_numero(this)"--></td>
													<td></td>
													<td><center><input type="checkbox" id="arrivee_numero_2" name="" value="" onclick="onCheckNumeroArriveeDepart(2)"></td></center>
													<td></td>
													<td><center><input type="checkbox" id="depart_numero_2" name="" value="" onclick="onCheckNumeroArriveeDepart(2)"></td></center>
												</tr>
												<tr>
													<td><?php echo _('numero'); ?> 3: <input id="message_numero_3" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'n');"  type="text"  class="form-control input-xs" style="margin-top:8px" maxlength="15"><!--input style="width:150px" class="geo3x_input_text" id="message_numero_3" type="text" onblur="valider_numero(this)"--></td>
													<td></td>
													<td><center><input type="checkbox" id="arrivee_numero_3" name="" value="" onclick="onCheckNumeroArriveeDepart(3)"></td></center>
													<td></td>
													<td><center><input type="checkbox" id="depart_numero_3" name="" value="" onclick="onCheckNumeroArriveeDepart(3)"></td></center>
												</tr>
												<tr>
													<td><?php echo _('numero'); ?> 4: <input id="message_numero_4" onpaste="return false;"  onkeyup="return verifierCaracteres(event,this.id,'n');"  type="text"  class="form-control input-xs" style="margin-top:8px" maxlength="15"><!--input style="width:150px" class="geo3x_input_text" id="message_numero_4" type="text" onblur="valider_numero(this)"--></td>
													<td></td>
													<td><center><input type="checkbox" id="arrivee_numero_4" name="" value="" onclick="onCheckNumeroArriveeDepart(4)"></td></center>
													<td></td>
													<td><center><input type="checkbox" id="depart_numero_4" name="" value=""onclick="onCheckNumeroArriveeDepart(4)"></td></center>
												</tr>
												<tr><td>&nbsp;</td></tr>
											</table>
										</div>
										<table><tr id="tr_validation_poi" style="display:none">
											<td colspan="4">
												<input type="button" class="btn btn-default btn-xs"  value="<?php echo _('valider'); ?>" onclick="validAlertPoi()">
											</td>
										</tr></table>
									</td>
								</tr>
							</table>
						</center>
					</div>
				</div>
			</div>

		</div>


	</div>

	</div>
</div>
<script>
	$('.menu li.effect').on('click', function(){
    $(this).addClass('active').siblings().removeClass('active');
});
</script>