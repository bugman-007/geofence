<?php
session_start();

/*Remise à 0 du compteurs de temps pour la deconnexion automatique*/
$_SESSION['CREATED'] = time();

/*Bibliotheque pour l'internationalisation et mise en place*/
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
<div id="TheContenu" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
	<div class="row" >
		<div id="divCartoPos" class="col-lg-9" >
			<div class="panel panel-default">
				<div class="panel-heading"> 
					<span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span><a class="hidden-xs"  style="color: black" data-toggle="collapse" data-parent="#accordion" href="#map"> Cartographie - Carto Position</a>
					<div class="pull-right">
						<input type='text' id='adresse_carto' style="width:150px; display: none" class="form-control input-xs" placeholder='<?php echo _('ptinteret_adressepostale'); ?>' />
						<button type='submit' id='btn_adresse_carto' style="display: inline" class="btn btn-default btn-xs" onclick="visualiserAdresseWithTrackers()"><?php echo _('ptinteret_adressepostale'); ?></button>
						
						<!--select id="km_carto"  class="geo3x_input_datetime" onchange="visualiserAdresseWithTrackers()" style="display: inline"-->
						<select id="km_carto"  class="geo3x_input_datetime" style="display: inline">
							<option value="5" selected>5km</option>
							<option value="10" >10km</option>
							<option value="15" >15km</option>
							<option value="20" >20km</option>
							<option value="25" >25km</option>
							<option value="30" >30km</option>
							<option value="35" >35km</option>
							<option value="40" >40km</option>
							<option value="45" >45km</option>
							<option value="50" >50km</option>
							<option value="100" >100km</option>
							<option value="200" >200km</option>
						</select>
						<label style=" font-weight: normal; font-size:14px; display:none;">
							<input class="pull-right" type="checkbox" id="id_filtrage_adresse_carto" style="display:none;"> &nbsp; <?php echo _('configuration_filtrage'); ?> &nbsp;
						</label>
						<div class="btn-group" id="legend_traitrajet" style="display:none">
							<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
								<?php echo _('legende'); ?> <span class="caret"></span>
							</button>
							<ul class=" menu dropdown-menu pull-right" role="menu">
								<li>
									<div id="header_canvas">
										<canvas id="canvas"  height="40"></canvas>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div id="map" class="panel-collapse collapse in">
					<div class="panel-body">				
						<!--div id="basicMap" style="width: 100%;  height:750px; display:none; "></div-->
						<div id="map_canvas"></div>
					</div>
				</div>
			</div>
		</div>        
		<div id="divAgrandir" class="col-lg-3" >
			<div class="hidden-xs">
				<div class="panel panel-default">
					<div class="panel-heading"> 
						<span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> <a style="color: black" data-toggle="collapse" data-parent="#accordion" href="#table"> Tables Positions</a>
						<div class="pull-right">
							<div id="tableposition_choixbalises" style="display:none" class="btn-group">
								<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
									<?php echo _('choixbalise'); ?>
									<span class="caret"></span>
								</button>
								<ul id="tableposition_li_choixbalises" class=" menu dropdown-menu pull-right" role="menu">


								</ul>
							</div>
							<div id="tableposition_modes" style="display:none" class="btn-group">
								<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
									Modes
									<span class="caret"></span>
								</button>
								<ul id="tableposition_li_modes" class=" menu dropdown-menu pull-right" role="menu">
									<li><a href="javascript:tablePosMode('normal')">Normal</a></li>
									<li><a href="javascript:tablePosMode('kmaddress')">Km par adresse</a></li>
									<!--li><a href="javascript:testsort()">tfsfs</a></li-->
								</ul>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs " id="boutonZoom" onclick="javascript:agrandirTablePos();">
									<!--span class="input-group-addon"><span class="glyphicon glyphicon-star"></span></span-->
									<i class="glyphicon glyphicon-zoom-in"></i>
									<!--span class="caret"></span-->
								</button>
								<!--li><a  href="javascript:loadingAddress()">--><?php //echo _('chargerlesadresses'); ?><!--</a></li>-->

							</div>
						</div>
					</div>
					<div id="table" class="panel-collapse collapse in">
						<div class="panel-body" >
							<div id="divTable" class="table-responsive" >
								<div id="TablePosition" style="height:254px;overflow:auto;">
									<table id="idTablePosition" class="sortable table table-bordered table-hover" >
										<thead id="head_idTablePosition">
											<tr><th width="50px">N</th><th width="45px"></th><th width="150px"><?php echo _('nombalise'); ?></th><th width="150px">Date position</th>
											<th width="400px"><?php echo _('adresse'); ?></th><th width="50px"><?php echo _('vitesse'); ?></th><th width="350px"><?php echo _('statut'); ?></th>
											<th width="45px">GSM</th><th width="45px"><?php echo _('alim'); ?></th>
											<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>
										</thead>
										<tbody id="body_idTablePosition">

										</tbody>
<!--										<tr><th onclick="deleteColonne(this)" width="50px">N</th><th width="150px">Nom Balise</th><th width="45px"></th><th width="250px">Adresse</th><th width="50px">Vitesse</th><th width="300px">Statut</th><th width="45px"></th><th width="45px">GSM</th><th width="45px">Alim</th><th width="120px">Date/heure</th>-->
<!--										<th width="100px">Latitude</th><th width="100px">Longitude</th></tr>-->

									</table>

								</div>
								<center><div id="rappel_date" >	................. </div>
								<ul id="page-selection" style="float: bottom; margin: 0px"></ul>
								<ul id="tablePagination" class="pagination pagination-sm" style="float: bottom; margin: 0px">
									<li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
									<li class="disabled"><a href="#">...</a></li>
									<li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
								</ul>

								</center>
							</div>        
						</div>
					</div>
				</div>
			</div>
			<div id="divHistorique"  class="panel panel-default" style="padding: 0px">
				<div class="panel-heading" >
					<span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> <a style="color: black" data-toggle="collapse" data-parent="#accordion" href="#historique"><?php echo _('historique'); ?></a>
					<div class="pull-right">
						<div class="btn-group">
							<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
								Modes
								<span class="caret"></span>
							</button>
							<ul class="menu dropdown-menu pull-right" role="menu">
								<li id="OngletHistoPeriode" class="effect"><a href="javascript:divHistorique(1)"><?php echo _('periode'); ?></a>
								</li>
								<li id="OngletHistoPos" class="effect"><a href="javascript:divHistorique(2)">Positions</a>
								</li>
								</li>
							</ul>
						</div>
						<div class="btn-group hidden-sm hidden-md hidden-lg">
							<button type="button" class="btn btn-default btn-xs " onclick="javascript:closeDivMobileHistorique()">
								<i class="glyphicon glyphicon-remove"></i>
							</button>
						</div>
					</div>
				</div>
				<div id="historique" class="panel-collapse collapse in" >
				</div>
			</div>
			<div class="hidden-xs">
				<div id="divVueRapproche" class="panel panel-default hidden-sm">
					<div class="panel-heading">
						<span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> <a style="color: black" data-toggle="collapse" data-parent="#accordion" href="#view"> <?php echo _('vuerapprochee'); ?></a>
						<div class="pull-right">
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" style="display:none;">
									Styles
									<span class="caret"></span>
								</button>
								<ul class="menu dropdown-menu pull-right" role="menu">
									<li class="effect"><a href="javascript:changeMapType('ROADMAP');">Normal</a>
									</li>
									<li class="effect"><a href="javascript:changeMapType('TERRAIN');">Terrain</a>
									<li class="effect active"><a href="javascript:changeMapType('SATELLITE');">Satellite</a>
									</li>
									<li class="effect"><a href="javascript:changeMapType('HYBRID');">Satellite (<?php echo _('routesmajeur'); ?>)</a>
									</li>

									<li class="divider"></li>
									<li class="effect" ><a href="javascript:streetMap();">StreetView</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div id="view" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="container-fluid" style="height: 300px; overflow:hidden; padding:0px; padding-right:0px; padding-left:0px; border-radius:10px 10px 10px 10px;">
<!--							<div class="container-fluid" style="height: 300px; overflow:auto;">-->
									<div id="map_canvas2" style=" width: 100%; height: 100%;"></div>
<!--							</div>          -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>

	/*Execute cette fonction uen fois que l'adresse postale est inscrit dans la carto position*/
	$('#adresse_carto').donetyping(function(){
		var adresse = document.getElementById('adresse_carto').value;
		if(adresse == "") {
			setCookie("adresse_carto","");
			if (cityCircle) {
				for (i in arrayCityCircle) {
					arrayCityCircle[i].setMap(null);
				}
			}
			if(markerCartoAddress){
				markerCartoAddress.setMap(null);
				LatLngArray.splice(LatLngArray.indexOf(latlngCartoAddress),1);
			}
		}else{
			visualiserAdresseWithTrackers()
		}
	});

//
//	$('.sortable th').on('click', function(){
//		$(this).remove();
//    });

	//Class "active" a la selection d'un element d'une liste de la classe "menu"
	$('.menu li.effect').on('click', function(){
		$(this).addClass('active').siblings().removeClass('active');
	});

	//On utilise les cookies à refreshment de la page
	if(refreshPage) {
		if (getCookie("idGPW")) {
			if ($(window).width() < 768) {
				document.getElementById('select_liste_groupe_2').value = getCookie("idGPW");
				document.getElementById('select_liste_groupe_2').onchange();
			} else {
				$("#" + getCookie("idGPW")).trigger('click');
			}
			if(getCookie("adresse_carto")){
				document.getElementById('adresse_carto').value = getCookie("adresse_carto");

				if(getCookie("km_carto"))
					document.getElementById('km_carto').value = getCookie("km_carto");

				visualiserAdresseWithTrackers();

			}
		}
	}

	//Si on a qu'une balise sur la liste , on l'affiche directement après avoir selectionner le groupe de balise
	if($('a.list-group-item').length == 1) $('a.list-group-item').click();
</script>


