<?php

	//Identifiant MySQL
	include '../dbgpw.php';
	include '../dbconnect2.php';

	session_set_cookie_params(0);
	session_start();
	error_reporting(0);

	/*Deconnexion automatique de la session*/
	$inactive = 1800;
	ini_set('session.gc_maxlifetime', $inactive);
	if (isset($_SESSION['CREATED']) && (time() - $_SESSION['CREATED'] > $inactive)) {
		session_unset();
		session_destroy();
	}

	/*Vérification si l'utilisateur est superviseur et si la BDD existe*/
	if(isset($_SESSION['superviseur'])) {
		if (isset($_SESSION['superviseurIdBd'])){
			$_SESSION["superviseur"] = "";
		}else{
			session_destroy();
			header('location:../../../index.php');
			exit();
		}
	}

	/*Vérification du login*/
	if(empty($_SESSION['username'])) {
		header('location:../../../index.php');
		exit();
	}else{
		/*Vérification du telechargement de la notice*/
		if (isset($_SESSION['notice'])){
			if($_SESSION['notice'] == "1") {
				header('location:../../../download.php');
				$_SESSION['notice'] = "0";
			}
		}
	}

	/*Remise à 0 du compteurs de temps pour la deconnexion automatique*/
	$_SESSION['CREATED'] = time();


	/* Recuperer la configuration de l'utilisateur */
	$connectGpwUserConfig = mysqli_connect($server, $db_user, $db_pass,$database);
	if (!$connectGpwUserConfig)
		die('Impossible de se connecter: '.mysqli_connect_error());
	$queryGpwUserConfig = mysqli_query($connectGpwUserConfig,"SELECT Configuration FROM gpwutilisateurconfiguration WHERE
						(Login = '".$_SESSION['username']."' AND ((Application = 'mGeo3X') || (Application = 'DEFAUT')) )");
	$assocGpwUserConfig = mysqli_fetch_assoc($queryGpwUserConfig);
	$userConfig = $assocGpwUserConfig['Configuration'];
	if($userConfig == "" || $userConfig == null) $userConfig = "WEB_UTILISATEUR";
	mysqli_free_result($queryGpwUserConfig);
	mysqli_close($connectGpwUserConfig);


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
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="cache-control" content="no-store" />
		<meta http-equiv="Expires" content="0">
		<meta http-equiv="pragma" content="no-cache" />
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no" />

		<link rel="icon" type="image/png" href="../../assets/img/favicon.ico" />	<!-- Geofence -->

		<title>Geofence - G&eacute;olocalisation</title>						<!-- Geofence -->

		<!-- CSS OPENSTREETMAP -->
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" >
		<!--link href="../../assets/leaflet-1.0.3/dist/leaflet.css" rel="stylesheet" type="text/css" /-->
		<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.0.4/dist/MarkerCluster.Default.css" />
		<!--link href="../../assets/leaflet.markercluster-1.0.4/dist/MarkerCluster.Default.css" rel="stylesheet" type="text/css" /-->
		<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@1.5.4/dist/Control.Geocoder.css" />
		<link rel="stylesheet" href="https://coryasilva.github.io/Leaflet.ExtraMarkers/css/leaflet.extra-markers.min.css" />
		<!--link href="../../assets/Leaflet.ExtraMarkers/dist/css/leaflet.extra-markers.min.css" rel="stylesheet" type="text/css" /-->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.4.9/leaflet.draw.css">
		<link href="../../assets/Leaflet.draw/src/leaflet.draw.css" rel="stylesheet" type="text/css" />
		

		<!--CSS Bootstrap 3 -->
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
		<link href="../../assets/css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css" />
		<link href="../../assets/css/bootstrap/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
		<link href="../../assets/css/bootstrap/bootstrap-toggle.min.css" rel="stylesheet" type="text/css" />
		<link href="../../assets/css/bootstrap/bootstrap-theme.css" rel="stylesheet" type="text/css" />
		<!-- CSS Geo3x -->
		<link href="../../assets/css/geo3x/geo3x.css" rel="stylesheet" type="text/css" />
		<link href="../../assets/css/font-awesome/font-awesome.css" rel="stylesheet"/>

		<!-- CSS Mobiscroll -->
		<link href="../../assets/css/mobiscroll/mobiscroll.scroller.css" rel="stylesheet" type="text/css" />
		<link href="../../assets/css/mobiscroll/mobiscroll.scroller.jqm.css" rel="stylesheet" type="text/css" />
		<link href="../../assets/css/mobiscroll/mobiscroll.scroller.android.css" rel="stylesheet" type="text/css" />
		<link href="../../assets/css/mobiscroll/mobiscroll.scroller.android-ics.css" rel="stylesheet" type="text/css" />
		<link href="../../assets/css/mobiscroll/mobiscroll.scroller.ios.css" rel="stylesheet" type="text/css" />
		<link href="../../assets/css/mobiscroll/mobiscroll.scroller.sense-ui.css" rel="stylesheet" type="text/css" />
		<link href="../../assets/css/mobiscroll/mobiscroll.scroller.wp.css" rel="stylesheet" type="text/css" />
		<link href="../../assets/css/mobiscroll/mobiscroll.animation.css" rel="stylesheet" type="text/css" />
	</head>

	<body>

	<!--VARIABLE GlOBALE/DIV HTML-->
	<div id="checkboxe"  style="display:none"></div>
	<div id="checked"  style="display:none">0</div>
	<div id="unchecked"  style="display:none">20</div>
	<div id="rememberNomBase"  style="display:none"></div>
	<div id="rememberFiltrageArret" style="display:none" >yes</div>
	<div id="rememberSuivi" style="display:none" >no</div>
	<div id="rememberStreet" style="display:none" ></div>
	<div id="rememberTrafic" style="display:none" ></div>
	<div id="rememberTrafic" style="display:none" ></div>
	<div id="rememberContenuCP" style="display:none" ></div>
	<div id="rememberDivHistorique" style="display:none" >Periode</div>
	<div id="rememberDateTimePosition" style="display:none" ></div>
	<div id="rememberAddPeriode" style="display:none" ></div>
	<div id="rememberAddPosition" style="display:none" ></div>
	<div id="rememberAddMarker" style="display:none" ></div>
	<div id="idGroupe" style="display:none" ></div>
	<div id="idBalise" style="display:none; color:white"></div>
	<div id="nomGroupe" style="display:none"></div>
	<div id="nomBalise" style="display:none"></div>

	<nav class="navbar navbar-inverse navbar-fixed-top col-lg-12" style="background-image:radial-gradient(white, white, silver);" role="navigation">	<!-- Geofence -->
		<div class="container-fluid">
			<div class="navbar-header">
				<!-- Bouton de navigation du menu pour les tablettes/mobiles -->
				<button id="liste_top_menu"  type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu_navigation">
					<span style="color:black" >Navigation</span>		<!-- Geofence -->
				</button>
				<!-- Bouton d'action pour les tablettes/mobiles	-->
				<button id="action_menu"  type="button" class="navbar-toggle hidden-sm hidden-md hidden-lg" data-toggle="collapse" data-target="#action_nav">
					<span style="color:black" >Actions</span>			<!-- Geofence -->
				</button>


				<img src="../../assets/img/logo_main.png" alt="..." width="100" height="40" style="position:absolute;left: 15px; top: 0.4em;">				<!-- Geofence -->
			
			</div>
			<!-- Action pour les tablettes/mobiles	-->
			<div class="hidden-sm hidden-md hidden-lg">
					<ul  class="collapse navbar-collapse nav navbar-nav" id="action_nav" >
						<br/>
							<li style="text-align: center" >
									<button type="button"  class="btn btn-default btn-sm" onclick="myPosition()"><?php echo _('votreposition'); ?></button>
									<button type="button"  class="btn btn-default btn-sm" onclick="mobileHistorique()"><?php echo _('historique'); ?></button>
							</li>
						<br/>
					</ul>
			</div>
			<!-- Onglets de navigations-->
			<div class="navbar-collapse collapse" id="menu_navigation">
				<ul  class="nav navbar-nav navbar-right" >
					<li class="active"  id="liCarto" ><a href="javascript:divContenu(1)" style="color: #5d5d5d;" ><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> <?php echo _('layout_cartoposition'); ?></a></li>	<!-- Geofence -->
					<li class="hidden-xs" id="liRapport"><a id="aliRapport" href="javascript:divContenu(2)" style="color: #5d5d5d;"><i class="fa fa-edit fa-fw"></i> <?php echo _('layout_rapport'); ?></a></li>							<!-- Geofence -->
					<li class="hidden-xs" id="liGeofencing" ><a href="javascript:divContenu(3)" style="color: #5d5d5d;"><i class="fa fa-bar-chart-o fa-fw"></i> Geofencing</a></li>															<!-- Geofence -->
					<li class="hidden-xs" id="liPtInteret"><a href="javascript:divContenu(4)" style="color: #5d5d5d;"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span> <?php echo _('layout_pointinteret'); ?></a></li>	<!-- Geofence -->
					<li class="hidden-xs " id="liConfiguration"><a href="javascript:divContenu(5)" style="color: #5d5d5d;"><i class="fa fa-sitemap fa-fw"></i> Configuration</a></li>														<!-- Geofence -->
					<li class="hidden-xs " id="liOptions"><a href="javascript:divContenu(6)" style="color: #5d5d5d;"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></span>  Options</a></li>									<!-- Geofence -->
					<li id="liEtatBalise"><a href="javascript:divContenu(7)" style="color: #5d5d5d;"><i class="fa fa-wrench fa-fw"></i> <?php echo _('layout_etatbalise'); ?></a></li>														<!-- Geofence -->
					<li id="liDeconnexion"><a href="#" onclick="javascript:deconnexion()" style="color: #5d5d5d;"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> <?php echo _('layout_deconnexion'); ?></a></li>			<!-- Geofence -->
				</ul>
			</div>

		</div>

	</nav>

	<div id="wrapper">

        <!-- Sidebar : Menu sur le côté gauche de l'application-->
        <div id="sidebar-wrapper">
			<b>
				<div class="hidden-sm hidden-md hidden-lg">
					<?php include 'layoutchoixclient2.php' ?>
					<div id="ListeGroupe2"><?php include 'layoutlistegroupe2.php'?>	</div>
					<div id="ListeBalise2"><?php include 'layoutlistebalisedirect2.php'?></div>
				</div>
				<div id="theMenu"  class="hidden-xs">
					<?php include 'layoutchoixclient.php'?>
					<br>
					<li  ><center  style='color:white;'> <h4><?php echo _('layout_groupebalise'); ?></h4>
						<div id="ListeGroupe"  style=" width: 200px;  height: 200px; background-color: #e8e8e8;overflow-y: scroll;">
							<ul class="list-group" style="color: black; max-height:200px;text-align: left;">
								<?php include 'layoutlistegroupe.php'?>
							</ul>
						</div></center> </li>
					<br>
					<li ><center  style='color:white'><h4><?php echo _('layout_listbalise'); ?></h4><div id="ListeBalise"  style=" width: 200px; height: 200px; background-color: #e8e8e8; overflow: scroll; " class="list-group">
						<ul class="list-group" style="color: black; min-height: 20px; max-height:200px;text-align: left;">

						</ul>
					</div></center></li>
				</div>
				<li >
					<center  style='color:white'><h4><?php echo _('layout_modesuivi'); ?></h4>
						<button type="button" class="btn btn-default btn-sm" onclick="javascript:boutonDernierePosition()">
							<b><?php echo _('layout_derniereposition'); ?></b>
						</button>
						<button id="btnSuivi" type="button" class="btn btn-default btn-sm" onclick="javascript:modeSuivi();boutonSuivi()">
							<b><?php echo _('layout_modesuivi'); ?></b>
						</button>
						<br><br>
						<button id="getmyposition" type="button" class="btn btn-default btn-sm" onclick="getuserposition()">
							<b>Ma Position</b>
						</button>

						<div class="checkbox">
							<!--
							<label>
								<input class="pull-right" type="checkbox" id="idInfotrafic" name="nameInfotrafic" onclick="javascript:infoTrafic()">
								<?php echo _('layout_infotrafic'); ?>
							</label>
							<br>
							-->
							<label>
								<input class="pull-right" type="checkbox" id="idpoi" name="namepoi">
								POI
							</label>
							<br>
							<!--
							<label id="id_label_avec_geofencing" class="hidden-xs" style="display:none;">
								<input style="display:none;" class=" pull-right" type="checkbox" id="id_avec_geofencing" name="name_avec_geofencing" onclick="">
								<?php //echo _('layout_geofencing'); ?>
							</label>
							-->
							<label id="id_label_avec_geofencing" class="hidden-xs">
								<input class=" pull-right" type="checkbox" id="id_avec_geofencing" name="name_avec_geofencing" onclick="">
								<?php echo _('layout_geofencing'); ?>
							</label>
							<br>
							<label id="id_label_centrer_zoom" >
								<input class=" pull-right" type="checkbox" id="id_centrer_zoom" name="name_centrer_zoom" onclick="" checked>
								<?php echo _('centrerzoom'); ?>
							</label>
							<br>
						</div>
					</center>
				</li>
				<br><br><br><br><br>
			</b>
		</div>


        <!-- Page Content: Ici on y include nos pages d'onglets -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                       <div id="TheContenu"  style="height: 100%"> </div>
							<div id="volet">
								<a  id="ouvrir" href="#" class="ouvrir" style="right: -82px"><?php echo _('ouvrir') ?></a>
							</div>
                    </div>
                </div>
            </div>
        </div>


    </div>

	<!-- Gerer -->
	<?php include 'layoutgererutilisateur.php'; ?>


	<div class="modal"></div>

	<script type="text/javascript" src="../../assets/js/jstz.min.js"></script>
	<!-- JS GOOGLEMAP -->
	<!--script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?client=gme-stancomsas&libraries=geometry,places"></script-->
	
	<!-- JS OPENSTREETMAP -->
	<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
	<!--script type="text/javascript" src="../../assets/Leaflet-1.0.3/dist/leaflet.js" ></script-->
	<script src="https://unpkg.com/leaflet.markercluster@1.0.4/dist/leaflet.markercluster.js"></script>
	<!--script type="text/javascript" src="../../assets/Leaflet.markercluster-1.0.6/src/markercluster.js" ></script-->
	<script src="https://unpkg.com/leaflet-control-geocoder@1.5.4/dist/Control.Geocoder.js"></script>
	
	<script src="https://coryasilva.github.io/Leaflet.ExtraMarkers/js/leaflet.extra-markers.min.js"></script>
	<!--script type="text/javascript" src="../../assets/Leaflet.ExtraMarkers/dist/js/leaflet.extra-markers.min.js" ></script-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.4.9/leaflet.draw.js"></script>
	<!--script type="text/javascript" src="../../assets/Leaflet.draw/src/leaflet.draw.js" ></script-->

	<!-- JS JQUERY -->
	<script type="text/javascript" src="../../assets/js/jquery/jquery-1.10.2.js"></script>

	<script type="text/javascript" src="../../assets/js/jquery/jquery.bootpag.min.js"></script>
	<script type="text/javascript" src="../../assets/js/jquery/jquery.bootpag.js"></script>

	<script type="text/javascript" src="../../assets/js/jquery/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="../../assets/js/jquery/jquery.tablesorter.widgets.js"></script>
	<script type="text/javascript" src="../../assets/js/jquery/jquery.tablesorter.min.js"></script>
	
	<!-- JS GEO3X -->

	<script type="text/javascript" src="../../assets/js/js9geo3x-layout.js"></script>
	<script type="text/javascript" src="../../assets/js/js9geo3x-variable.js"></script>
	<script type="text/javascript" src="../../assets/js/js9geo3x-carto.js"></script>
	<script type="text/javascript" src="../../assets/js/js9geo3x-ptinteret.js"></script>
	<script type="text/javascript" src="../../assets/js/js9geo3x-rapport.js"></script>
	<script type="text/javascript" src="../../assets/js/js9geo3x-configuration.js"></script>
	<script type="text/javascript" src="../../assets/js/js9geo3x-option.js"></script>
	<script type="text/javascript" src="../../assets/js/js9geo3x-etatbalise.js"></script>
	<script type="text/javascript" src="../../assets/js/js9geo3x-geofencing.js"></script>

	<!--script type="text/javascript" src="../../assets/js/js7geo3x-layout.js"></script-->
	<!--script type="text/javascript" src="../../assets/js/js7geo3x-variable.js"></script-->
	<!--script type="text/javascript" src="../../assets/js/js7geo3x-carto.js"></script-->
	<!--script type="text/javascript" src="../../assets/js/js7geo3x-ptinteret.js"></script-->
	<!--script type="text/javascript" src="../../assets/js/js7geo3x-rapport.js"></script-->
	<!--script type="text/javascript" src="../../assets/js/js8geo3x-configuration.js"></script-->
	<!--script type="text/javascript" src="../../assets/js/js7geo3x-option.js"></script-->
	<!--script type="text/javascript" src="../../assets/js/js7geo3x-etatbalise.js"></script-->
	<!--script type="text/javascript" src="../../assets/js/js7geo3x-geofencing.js"></script-->

	<!-- JS BOOTSTRAP -->
    <script type="text/javascript" src="../../assets/js/bootstrap/bootstrap.js"></script>
	<script type="text/javascript" src="../../assets/js/bootstrap/bootstrap-datetimepicker.js"></script>
	<script type="text/javascript" src="../../assets/js/bootstrap/bootstrap-toggle.min.js"></script>
	<script type="text/javascript" src="../../assets/js/bootstrap/bootstrap.min.js"></script>
	<script type="text/javascript" src="../../assets/js/bootstrap/dropdown.js"></script>
	<script type="text/javascript" src="../../assets/js/bootstrap/tooltip.js"></script>
	<script type="text/javascript" src="../../assets/js/bootstrap/popover.js"></script>
	<script type="text/javascript" src="../../assets/js/bootstrap/confirmation.js"></script>

	<!-- JS Mobiscroll -->
	<script src="../../assets/js/mobiscroll/mobiscroll.core.js"></script>
    <script src="../../assets/js/mobiscroll/mobiscroll.scroller.js" type="text/javascript"></script>
    <script src="../../assets/js/mobiscroll/mobiscroll.datetime.js" type="text/javascript"></script>
    <script src="../../assets/js/mobiscroll/mobiscroll.select.js" type="text/javascript"></script>
    <script src="../../assets/js/mobiscroll/mobiscroll.scroller.jqm.js" type="text/javascript"></script>
    <script src="../../assets/js/mobiscroll/mobiscroll.scroller.ios.js" type="text/javascript"></script>
    <script src="../../assets/js/mobiscroll/mobiscroll.scroller.android.js" type="text/javascript"></script>
    <script src="../../assets/js/mobiscroll/mobiscroll.scroller.android-ics.js" type="text/javascript"></script>
    <script src="../../assets/js/mobiscroll/mobiscroll.scroller.wp.js" type="text/javascript"></script>
    <script src="../../assets/js/mobiscroll/mobiscroll.i18n.fr.js" type="text/javascript"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src="../../assets/js/jquery/jquery.ui.touch-punch.js"></script>

	<script language="javascript">
		var username = "<?php echo $_SESSION['username']; ?>";



		function deconnexion(){
			deleteAllCookies();
			if(confirm("<?php echo _('confirmdeconnexion'); ?>"))
				window.location='../../../logout.php';
		}


		$(document).ready(function(){

            //Manipulaion du volet de sidebar
            $( "#volet" )
            .draggable({
                axis: 'y',
                containment: "window"
            })
            .click(function(){
                openSidebar();
            })
            .on( "dragstop", function( event, ui ) {
                openSidebar();
            } );

		    //"Dropdown" des toggles
            $('.dropdown-toggle').dropdown();

		    /******************** Permet la dynamique des barres de navigation ************************/
			document.getElementById("volet").style.top = ($(window).height()-300)+"px";
			$("#liste_top_menu").click(function(){
				if(topmenu == "")topmenu = "1";
				else topmenu = "";
				if(sidemenu == "1"){
					$("#wrapper").toggleClass("toggled");
					document.getElementById("ouvrir").style.right = "-82px";
					document.getElementById("ouvrir").innerHTML = getTextOuvrir;
					sidemenu = "";
				}
				if(actionmenu == "1"){
					$("#action_nav").collapse('hide');
					actionmenu = "";
				}
			});
			$("#action_menu").click(function(){
				if(actionmenu == "")actionmenu = "1";
				else actionmenu = "";
				if(sidemenu == "1"){
					$("#wrapper").toggleClass("toggled");
					document.getElementById("ouvrir").style.right = "-82px";
					document.getElementById("ouvrir").innerHTML = getTextOuvrir;
					sidemenu = "";
				}
				if(topmenu == "1"){
					$(".navbar-collapse").collapse('hide');
					topmenu = "";
				}
			});


		});
        //Class "active" pour l'onglet selectionné
        $('.nav > li').click(function(){
            if(this.id != "liDeconnexion") {
                $(this).parent().parent().find('.active').removeClass('active');
                $(this).addClass('active');
            }
        });

        //Fermer la liste des menu de navigation (mobile)
		$('.nav a').on('click', function(){
			if ($(window).width() < 1084)
			$("#liste_top_menu").click();
		});

        /**************Script et function pour le deplacement d'une "row" d'une table vers une autre table *********/
		var addedrows = new Array();
		$(document).ready(function() {
			$( "#csourcetable tr" ).on( "click", function( event ) {
				var theid = $( this ).attr('id').replace("csour","");
				$( this ).css( "background-color", "#cacaca" );
				$('#destinationtable tr:last').after('<tr id="cdest' + theid + '" onclick="addRowSource(this)"><td style="display:none">'
						+ $(this).find("td").eq(0).html() + '</td><td>'
						+ $(this).find("td").eq(1).html() + '</td></tr>');

				var tr = $( "#csour" + theid );
				tr.css("background-color","#cacaca");
				tr.remove();
			});
			$( "#cdestinationtable tr" ).on( "click", function( event ) {
				var theid = $( this ).attr('id').replace("cdest","");
				$( this ).css( "background-color", "#cacaca" );
				$('#csourcetable tr:last').after('<tr id="csour' + theid + '" onclick="addRowDest(this)"><td style="display:none">'
						+ $(this).find("td").eq(0).html() + '</td><td>'
						+ $(this).find("td").eq(1).html() + '</td></tr>');
				var tr = $( "#cdest" + theid );
				tr.css("background-color","#cacaca");
				tr.remove();
			});
		});

		function addRowSource(id){
			var theid = $( id ).attr('id').replace("cdest","");
			$( id ).css( "background-color", "#cacaca" );
			$('#csourcetable tr:last').after('<tr id="csour' + theid + '" onclick="addRowDest(this)"><td style="display:none">'
					+ $(id).find("td").eq(0).html() + '</td><td>'
					+ $(id).find("td").eq(1).html() + '</td></tr>');
			var tr = $( "#cdest" + theid );
			tr.css("background-color","#cacaca");
			tr.remove();
		}

		function addRowDest(id){
			var theid = $( id ).attr('id').replace("csour","");

			$( id ).css( "background-color", "#cacaca" );
			$('#cdestinationtable tr:last').after('<tr id="cdest' + theid + '" onclick="addRowSource(this)"><td style="display:none">'
					+ $(id).find("td").eq(0).html() + '</td><td>'
					+ $(id).find("td").eq(1).html() + '</td></tr>');

			var tr = $( "#csour" + theid );
			tr.css("background-color","#cacaca");

			tr.remove();
		}

		function addRowSourceGrp(id){
			var theid = $( id ).attr('id').replace("dest","");
			$( id ).css( "background-color", "#cacaca" );
			$('#sourcetable tr:last').after('<tr id="sour' + theid + '" onclick="addRowDestGrp(this)"><td>'
					+ $(id).find("td").eq(0).html() + '</td><td>'
					+ $(id).find("td").eq(1).html() + '</td></tr>');
			var tr = $( "#dest" + theid );
			tr.css("background-color","#cacaca");
			tr.remove();
		}

		function addRowDestGrp(id){
			var theid = $( id ).attr('id').replace("sour","");

			$( id ).css( "background-color", "#cacaca" );
			$('#destinationtable tr:last').after('<tr id="dest' + theid + '" onclick="addRowSourceGrp(this)"><td>'
					+ $(id).find("td").eq(0).html() + '</td><td>'
					+ $(id).find("td").eq(1).html() + '</td></tr>');

			var tr = $( "#sour" + theid );
			tr.css("background-color","#cacaca");

			tr.remove();
		}


        /********** Créer la fonction donetyping (execute lorsque on termine d'écrire) ********/
		(function($){
			$.fn.extend({
				donetyping: function(callback,timeout){
					timeout = timeout || 1e3; // 1 second default timeout
					var timeoutReference,
							doneTyping = function(el){
								if (!timeoutReference) return;
								timeoutReference = null;
								callback.call(el);
							};
					return this.each(function(i,el){
						var $el = $(el);
						// Chrome Fix (Use keyup over keypress to detect backspace)
						// thank you @palerdot
						$el.is(':input') && $el.on('keyup keypress paste',function(e){
							// This catches the backspace button in chrome, but also prevents
							// the event from triggering too preemptively. Without this line,
							// using tab/shift+tab will make the focused element fire the callback.
							if (e.type=='keyup' && e.keyCode!=8) return;

							// Check if timeout has been set. If it has, "reset" the clock and
							// start over again.
							if (timeoutReference) clearTimeout(timeoutReference);
							timeoutReference = setTimeout(function(){
								// if we made it here, our timeout has elapsed. Fire the
								// callback
								doneTyping(el);
							}, timeout);
						}).on('blur',function(){
							// If we can, fire the event since we're leaving the field
							doneTyping(el);
						});
					});
				}
			});
		})(jQuery);

		/*****************************************Traduction PHP -> JS ********************************************/
		getTextRapport=  "<?php echo _('layout_rapport'); ?>";
		getTextJ = "<?php echo _('j'); ?>";

		getTextAllGoups =  "<?php echo _('layout_allgroups'); ?>";
		getTextVeuillezChoisirUnGroupe =  "<?php echo _('veuillezchoisirungroupe'); ?>";
		getTextVeuillezChoisirUneIcone =  "<?php echo _('veuillezchoisiruneicone'); ?>";
		getTextVeuillezChoisirUnClientPrecis =  "<?php echo _('veuillezchoisirunclientprecis'); ?>";
		getTextVeuillezChoisirUneBalise =  "<?php echo _('veuillezchoisirunebalise'); ?>";
		getTextVeuillezChoisirQueUneBalise =  "<?php echo _('veuillezchoisirqueunebalise'); ?>";
		getTextVeuillezSaisirAdresse =  "<?php echo _('veuillezsaisiruneadresse'); ?>";
		getTextVeuillezSaisirNumTel =  "<?php echo _('veuillezsaisirunnumtel'); ?>";
		getTextPasDebut =  "<?php echo _('pas_debut'); ?>";
		getTextPasFin =  "<?php echo _('pas_fin'); ?>";
		getTextInconnue =  "<?php echo _('inconnue'); ?>";
		getTextAucune =  "<?php echo _('aucune'); ?>";
		getTextNon =  "<?php echo _('non'); ?>";
		getTextPas =  "<?php echo _('pas'); ?>";
		getTextTrouve =  "<?php echo _('trouve'); ?>";
		getTextAlimExt =  "<?php echo _('alimext'); ?>";
		getTextAlimBasse =  "<?php echo _('alimbasse'); ?>";
		getTextAlim =  "<?php echo _('alim'); ?>";
		getTextStatut =  "<?php echo _('statut'); ?>";
		getTextDepuis =  "<?php echo _('depuis'); ?>";
		getTextBrouille =  "<?php echo _('brouille'); ?>";
		getTextNonBrouille =  "<?php echo _('nonbrouille'); ?>";
		getTextDerniereSynchro =  "<?php echo _('dernieresynchro'); ?>";
		getTextDernierePosition =  "<?php echo _('layout_derniereposition'); ?>";
		getTextNumeroAppel =  "<?php echo _('numeroappel'); ?>";
		getTextModeFonctionnement =  "<?php echo _('modefonctionnement'); ?>";
		getTextAcquisitionPos =  "<?php echo _('acquisitionpos'); ?>";
		getTextRapatriementPos =  "<?php echo _('rapatriementpos'); ?>";
		getTextEnTrajet =  "<?php echo _('entrajet'); ?>";
		getTextEnVeille =  "<?php echo _('enveille'); ?>";
		getTextTempsSurAppel =  "<?php echo _('tempsreelsurappel'); ?>";
		getTextAlarmesActivees =  "<?php echo _('alarmesactivees'); ?>";
		getTextHistorique =  "<?php echo _('historique'); ?>";
		getTextSilencieux =  "<?php echo _('silencieux'); ?>";
		getTextNomBalise =  "<?php echo _('nombalise'); ?>";
		getTextAdresse =  "<?php echo _('adresse'); ?>";
		getTextVitesse =  "<?php echo _('vitesse'); ?>";
		getTextFinSuperieurDebut =  "<?php echo _('finsuperieurdebut'); ?>";
		getTextVeuillezNombreEntre =  "<?php echo _('veuillezsaisirnombreentre'); ?>";
		getTextPasDePositions =  "<?php echo _('pasdepositions'); ?>";
		getTextAgrandir=  "<?php echo _('agrandir'); ?>";
		getTextReduire =  "<?php echo _('reduire'); ?>";

		getTextOuvrir =  "<?php echo _('ouvrir'); ?>";
		getTextFermer =  "<?php echo _('fermer'); ?>";
		getTextEtape =  "<?php echo _('rapport_etape'); ?>";
		getTextPaDeTrajetIntervalle =  "<?php echo _('rapport_pasdetrajetintervalle'); ?>";
		getTextContenuEtape =  "<?php echo _('rapport_contenuetape'); ?>";
		getTextGrapheVitesse =  "<?php echo _('rapport_graphevitesse'); ?>";
		getTextAvecCarto =  "<?php echo _('rapport_aveccarto'); ?>";
		getTextGenererEtapeSurIntervalle=  "<?php echo _('rapport_genereretapesurintervalle'); ?>";
		getTextGenererEtape =  "<?php echo _('rapport_genereretape'); ?>";
		getTextVeuillezSelectTypeCarbu =  "<?php echo _('rapport_veuillezselecttypecarburant'); ?>";
		getTextSaisirValeurLitreCarburant100km =  "<?php echo _('rapport_saisirvaleurlitrecarburant100km'); ?>";
		getTextConfirmDeleteCarburant =  "<?php echo _('rapport_confirmsupprimercarburant'); ?>";
		getTextDeleteCarburant =  "<?php echo _('rapport_supprimerinfoscarburant'); ?>";

		getTextChoisirTypeSelect=  "<?php echo _('rapport_choisirtypeselect'); ?>";
		getTextUneFois=  "<?php echo _('rapport_unefois'); ?>";
		getTextJournalier=  "<?php echo _('rapport_journalier'); ?>";
		getTextJournalierPlus=  "<?php echo _('rapport_journalierplus'); ?>";
		getTextHebdomadaire=  "<?php echo _('rapport_hebdomadaire'); ?>";
		getTextHebdomadairePlus=  "<?php echo _('rapport_hebdomadaireplus'); ?>";
		getTextMensuel=  "<?php echo _('rapport_mensuel'); ?>";
		getTextMensuelPlus=  "<?php echo _('rapport_mensuelplus'); ?>";
		getTextAlertPasEtape = "<?php echo _('rapport_alert_pasdetape'); ?>";
		getTextChangerHeureDate = "<?php echo _('rapport_alert_changerheuredate'); ?>";
		getTextChangerHeure = "<?php echo _('rapport_alert_changerheure'); ?>";
		getTextPasRapportAuto = "<?php echo _('rapport_alert_pasrapportauto'); ?>";
		getTextAlertSupprimer = "<?php echo _('rapport_alert_supprimer'); ?>";
		getTextPasEncoreEnregistrer = "<?php echo _('rapport_pasencoreenregistrer'); ?>";
		getTextDernierJourDuMois = "<?php echo _('rapport_dernierjourdumois'); ?>";
		getTextAlertExcelMultiple = "<?php echo _('rapport_alert_excel_multiple'); ?>";
		getTextAlertExcelPdf = "<?php echo _('rapport_alert_excel_pdf'); ?>";
		getTextConfirmSupprimer = "<?php echo _('rapport_confirm_supprimer'); ?>";
		getTextConfirmEnregistrer = "<?php echo _('rapport_confirm_enregistrer'); ?>";
		getTextConfirmWarningAllBalises = "<?php echo _('rapport_confirm_warningallbalises'); ?>";
		getTextconfirmValiderConfigPoi = "<?php echo _('ptinteret_confirmvaliderconfigpoi'); ?>";
		getTextPourLaBalise = "<?php echo _('ptinteret_pourlabalise'); ?>";
		getTextVeuillezChoisirUnpoi = "<?php echo _('ptinteret_veuillezchoisirunpoi'); ?>";
		getTextNomPoi=  "<?php echo _('ptinteret_nompoi'); ?>";
		getTextRayon=  "<?php echo _('ptinteret_rayon'); ?>";
		getTextPasSaisieAdressePostale=  "<?php echo _('ptinteret_passaisiadressepostale'); ?>";
		getTextAlertTel1PasEnregistrer = "<?php echo _('alert_tel1pasenregistrer'); ?>";
		getTextAlertTel2PasEnregistrer = "<?php echo _('alert_tel2pasenregistrer'); ?>";
		getTextAlertTel3PasEnregistrer = "<?php echo _('alert_tel3pasenregistrer'); ?>";
		getTextAlertTel4PasEnregistrer = "<?php echo _('alert_tel4pasenregistrer'); ?>";
		getTextAlertTelAucunValid = "<?php echo _('configuration_aucuntelvalide'); ?>";
		getTextAlertChoisirAlert = "<?php echo _('configuration_choisiralerte'); ?>";

		geTextMessageActive=  "<?php echo _('messageactive'); ?>";
		getTextMessageDesactive =  "<?php echo _('messagedesactive'); ?>";

		getTextAnnuler =  "<?php echo _('annuler'); ?>";
		getTextFermerPolygone =  "<?php echo _('geofencing_fermerpolygone'); ?>";
		getTextChoisirZonePrecise =  "<?php echo _('geofencing_alert_choisirzoneprecise'); ?>";
		getTextModifierZoneDejaTracee =  "<?php echo _('geofencing_modifierzonedejatracee'); ?>";
		getTextEffacerZone =  "<?php echo _('geofencing_alert_effacerzone'); ?>";
		getTextChoisirDabordZone =  "<?php echo _('geofencing_choissirdabordzone'); ?>";
		getTextRedefinirZone =  "<?php echo _('geofencing_alert_redefinirlazone'); ?>";
		getTextVeuillezFermerPolygone =  "<?php echo _('geofencing_veuillezfermerpolygone'); ?>";
		getTextSaisirMessageEntreeSortie =  "<?php echo _('geofencing_saisirmessageentreesortie'); ?>";
		getTextBaliseBienConfig=  "<?php echo _('geofencing_alert_balisebienconfig'); ?>";
		getTextEndroitApproxi=  "<?php echo _('geofencing_alert_endroitapproxi'); ?>";
		getTextResetGeofencing=  "<?php echo _('geofencing_resetgeofencing'); ?>";
		getTextVouloirSupprimerZone=  "<?php echo _('geofencing_voulezvoussupprimerzone'); ?>";
		getTextVouloirCreerModifierZone=  "<?php echo _('geofencing_voulezvouscreermodifierzone'); ?>";
		getTextWarningGeofMultiple=  "<?php echo _('geofencing_warningmultiplebalise'); ?>";

		getTextAdressePostale=  "<?php echo _('ptinteret_adressepostale'); ?>";
		getTextRayonConsideration=  "<?php echo _('ptinteret_rayonconsideration'); ?>";
		getTextHtmlUrl=  "<?php echo _('ptinteret_htmlurl'); ?>";
		getTextEnregistrerPoi=  "<?php echo _('ptinteret_enregistrerpoi'); ?>";
		getTextInsererNewPoi=  "<?php echo _('ptinteret_inserernouveaupoi'); ?>";
		getTextModifierConfigurationPOI=  "<?php echo _('ptinteret_modifierconfiguration'); ?>";
		getTextSupprimerPOI=  "<?php echo _('ptinteret_supprimerpoi'); ?>";
        getTextSaisirMessageArriveeDepart=  "<?php echo _('ptinteret_saisirmessagearriveedepart'); ?>";

		getTextAlarm =  "<?php echo _('configuration_alarme'); ?>";
		getTextAlarmDeplacement =  "<?php echo _('configuration_alarmedeplacement'); ?>";
		getTextAlarmAlimentation =  "<?php echo _('configuration_alarmealimentation'); ?>";
		getTextAlarmParking =  "<?php echo _('configuration_alarmeparking'); ?>";
		getTextAlarmBatterie =  "<?php echo _('configuration_alarmebatterie'); ?>";
		getTextModeNormal =  "<?php echo _('configuration_modenormal'); ?>";
		getTextModeHistorique =  "<?php echo _('configuration_modehistorique'); ?>";
		getTextModePeriscope =  "<?php echo _('configuration_modeperiscope'); ?>";
		getTextModeSilencieux =  "<?php echo _('configuration_modesilencieux'); ?>";
		getTextModeConfirmNumTel =  "<?php echo _('configuration_confirmnumerotelephone'); ?>";
		getTextModeAttentionParamRapatrie =  "<?php echo _('configuration_attentionparamrapatriement'); ?>";
		getTextModeBienEnregistrerParam =  "<?php echo _('configuration_bienenregistreparam'); ?>";
		getTextModeBienAlertActive =  "<?php echo _('configuration_alerteactivee'); ?>";
		getTextModeBienAlertDesactive =  "<?php echo _('configuration_alertedesactivee'); ?>"
		getTextModeConfirmModeDeFonctionnement=  "<?php echo _('configuration_confirmmodifiermodedefonctionnement'); ?>";
		getTextModeConfirmTempsReel =  "<?php echo _('configuration_confirmmodifiertempsreel'); ?>";
		getTextModeConfirmEtatTempsReel =  "<?php echo _('configuration_confirmetattempsreel'); ?>";
		getTextModeConfirmDetectionBalise =  "<?php echo _('configuration_confirmemodifierdetectionbalise'); ?>";
		getTextModeConfirmAlerteBalise =  "<?php echo _('configuration_confirmemodifieralertebalise'); ?>";
		getTextModeBienSurDeplacementDesactive =  "<?php echo _('configuration_surdeplacementdesactive'); ?>";
		getTextModeBienSurDeplacementActive =  "<?php echo _('configuration_surdeplacementactive'); ?>";
		getTextFaible =  "<?php echo _('faible'); ?>";
		getTextExterne =  "<?php echo _('externe'); ?>";
		getTextTempsReelSurDeplacementEstActive =  "<?php echo _('tempsreelsurdeplacemeentestactive'); ?>";

		getTextAucuneBaliseAjoutee=  "<?php echo _('option_alert_aucunebaliseajoutee'); ?>";
		getTextVeuillezAjouterAuMoinsUneBalise =  "<?php echo _('option_alert_veuillezajouteraumoinsunebalise'); ?>";
		getTextVeuillezSaisirNomGroupe =  "<?php echo _('option_alert_veuillezsaisirnomgroupe'); ?>";
		getTextVeuillezAjouterAuMoinsUnGroupe =  "<?php echo _('option_alert_veuillezajouteraumoinsungroupe'); ?>";
		getTextVeuillezAjouterAuMoinsUneDuree =  "<?php echo _('option_alert_veuillezajouteraumoinsuneduree'); ?>";
		getTextVeuillezSelectionnerUneDuree =  "<?php echo _('option_alert_veuillezselectionneruneduree'); ?>";
		getTextVeuillezSaisirUneConfig =  "<?php echo _('option_alert_veuillezsaisiruneconfig'); ?>";
		getTextVeuillezSaisirUnMdp =  "<?php echo _('option_alert_veuillezsaisirunmdp'); ?>";
		getTextVeuillezSaisirUnLogin=  "<?php echo _('option_alert_veuillezsaisirunlogin'); ?>";
		getTextVeuillezSelectionnerUnCompte=  "<?php echo _('option_alert_veuillezselectionnercompte'); ?>";
		getTextAlertModifier=  "<?php echo _('option_alert_modifier'); ?>";
		getTextConfirmChangerNomBalise=  "<?php echo _('option_confirm_changernombalise'); ?>";
		getTextConfirmChangerNumeroBalise=  "<?php echo _('option_confirm_changernumerobalise'); ?>";
		getTextConfirmChangerNomGroupe=  "<?php echo _('option_confirm_changernomgroupe'); ?>";
		getTextConfirmWarningMultipleBalises=  "<?php echo _('option_confirm_warningmultiplebalises'); ?>";
		getTextConfirmEnregistrerIcone=  "<?php echo _('option_confirm_enregistrericone'); ?>";
		getTextConfirmSupprimerGroupe=  "<?php echo _('option_confirm_supprimergroupe'); ?>";
		getTextConfirmSupprimerCompte=  "<?php echo _('option_confirm_supprimercompte'); ?>";
		getTextAlertNomExist =  "<?php echo _('option_alert_nomcompteexistedeja'); ?>";
		getTextConfidenceOk=  "<?php echo _('confidentialite_ok'); ?>";

		getTextConfirmNoLogs=  "<?php echo _('alert_pasdelog'); ?>";
		getTextHistoriqueParam=  "<?php echo _('historiqueparametrage'); ?>";
		getTextDateEnregistrement=  "<?php echo _('dateenregistrement'); ?>";
		getTextDateEnvoi=  "<?php echo _('dateenvoi'); ?>";



	</script>
<?php
	echo "<script>configGpwUser('".$userConfig."');</script>";
?>

</body>      
</html>