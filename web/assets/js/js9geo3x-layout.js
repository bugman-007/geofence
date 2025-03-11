function eventFire(el, etype){
	if (el.fireEvent) {
		el.fireEvent('on' + etype);
	} else {
		var evObj = document.createEvent('Events');
		evObj.initEvent(etype, true, false);
		el.dispatchEvent(evObj);
	}
}


function verifierCaracteres(event,id,type) {

    var keyCode = event.which ? event.which : event.keyCode;
    var champ = document.getElementById(id);
    var car_auth;
    //type numérique (téléphone) ou alphanumerique (sms)

    if (type === 'n')
    {
        if (champ.value.length > 0) car_auth = "0123456789\x08\x2E\x25\x27";
        else car_auth = "+0123456789\x08\x2E\x25\x27";
    }
    else
    {
        car_auth = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 +-?!:;,()<>\x08\x2E\x25\x27";
    }
    //console.log(keyCode.toString(16).toUpperCase());

    if(car_auth.indexOf(String.fromCharCode(keyCode)) >= 0) 
    {
        return true;
    }
    else 
    {
        return false;
    }
}


/*
function allDayInInterval(debutperiode,finperiode,day){

	var debutperiode = document.getElementById("debutperiode").value;
	var finperiode = document.getElementById("finperiode").value;

	var d = new Date(debutperiode);
	var f = new Date(finperiode);

	var arrayAllDayChosen = new Array();

	if(d.getDay() == day) arrayAllDayChosen.push( new Date(d.setDate(d.getDate())));

	while(d <= f){
		var next_date = d.setDate(d.getDate() + 1);
		var next_days_date = new Date(next_date);

		var day_index = next_days_date.getDay();
		if(day_index == day){
			arrayAllDayChosen.push(next_days_date);
		}

		d = new Date(next_date);

	}
	alert(arrayAllDayChosen);
	return arrayAllDayChosen;
}
*/

function successCallbackGeoloc(position){
		var lat = position.coords.latitude;
		var lng = position.coords.longitude;
		var latlng = new google.maps.LatLng(lat,lng);
		var infowindow = new google.maps.InfoWindow({
			content: "Vous êtes ici"
		});
		var marker = new google.maps.Marker({
			position: latlng,
			infowindow: infowindow,
			title: "test",
			map: map
		});
		//infowindow.open(map, marker);
		google.maps.event.addListener(marker, "click", function () {
			infowindow.open(map, marker);
		});


	if(MarkersArray.length != 0) {
		MarkersArray.push(marker);
		LatLngArray.push(latlng);
		SetZoom();
	}else{
		map.setCenter(latlng);
		map.setZoom(16);
	}


}
function stopWatch(){
	navigator.geolocation.clearWatch(watchId);
}
function errorCallbackGeoloc(error){
	switch(error.code){
		case error.PERMISSION_DENIED:
			alert("L'utilisateur n'a pas autorisé l'accès à sa position");
			break;
		case error.POSITION_UNAVAILABLE:
			alert("L'emplacement de l'utilisateur n'a pas pu être déterminé");
			break;
		case error.TIMEOUT:
			alert("Le service n'a pas répondu à temps");
			break;
	}
};

function sessionAutodisconnect(){
	var timeNow = new Date().getTime();
	timeNow = timeNow * 0.001;
	timeNow = Math.floor(timeNow);

	$.ajax({
		url : '../session_autodisconnect.php',
		type : 'GET',
		success: function(response) {
			if(response) {
				if(response == "DECONNECTER") window.location='../../../logout.php';
				else if (timeNow -response >= 1800){
					//alert("Connexion timeout: Vous allez être redirigé vers la page de connexion");
					window.location='../../../logout.php';
				}
			}
		}
	});
}

setInterval("sessionAutodisconnect()", 60000);


function detectDatabase(){
	$.ajax({
		url : 'layoutdetectdatabase.php',
		type : 'GET',
		//async: false,
		success: function(response) {
			if(response) {
				var nomBDD = response.substring(response.indexOf('NomBDD')+8,response.indexOf('ipBDD'));
				var ipBDD = response.substring(response.indexOf('ipBDD')+7,response.indexOf('idBase'));
				var idBDD = response.substring(response.indexOf('idBase')+8);
				globalIpDatabaseGpw=ipBDD;
				globalnomDatabaseGpw = nomBDD;
				globalIdDatabaseGpw = idBDD;

			}
		}
	});

}

function selectDatabase() {
	var selectDatabase = document.getElementById("selectDatabase");
	var idDatabase = selectDatabase.options[selectDatabase.selectedIndex].value;
	var nameDatabase = selectDatabase.options[selectDatabase.selectedIndex].text;

   document.getElementById("rememberNomBase").innerHTML = nameDatabase;
}
/**********************************************************************************/
/******************************* changeClient & changeClient2************************************/
/**********************************************************************************/
function changeClient(id){
	if(document.getElementById("rememberSuivi").innerHTML=="yes"){
		document.getElementById("rememberSuivi").innerHTML="no";
		document.getElementById("btnSuivi").className = "btn btn-default btn-sm";
		offSuivi();
	}
	//document.body.className = "loading";
	if(id == "all"){

		$.ajax({
			url : 'layoutselectallclient.php',
			type : 'GET',
			success: function(response) {
				if(response) {
					document.getElementById("ListeGroupe").innerHTML = response;
					document.getElementById("ListeBalise").innerHTML = "";
					document.body.className = "";
				}
			}
		});

	}else{
		$.ajax({
			url : 'layoutselectclient.php',
			type : 'GET',
			data: "idClient="+id,
			success: function(response) {
				if(response) {
					document.getElementById("ListeGroupe").innerHTML = response;
					document.getElementById("ListeBalise").innerHTML = "";
					document.body.className = "";
				}
			}
		});
	}
}
function changeClient2(id){
	// document.getElementById("ListeBalise2").innerHTML = "";
	if(document.getElementById("rememberSuivi").innerHTML=="yes"){
		document.getElementById("rememberSuivi").innerHTML="no";
		document.getElementById("btnSuivi").className = "btn btn-default btn-sm";
		offSuivi();
	}
	//document.body.className = "loading";
	if(id == "all"){
		$.ajax({
			url : 'layoutselectallclient2.php',
			type : 'GET',
			success: function(response) {
				if(response) {
					document.getElementById("ListeGroupe2").innerHTML = response;
					document.body.className = "";
				}
			}
		});
	}else{
		$.ajax({
			url : 'layoutselectclient2.php',
			data: "idClient="+id,
			type : 'GET',
			success: function(response) {
				if(response) {
					document.getElementById("ListeGroupe2").innerHTML = response;
					document.body.className = "";
				}
			}
		});
	}
}
/**********************************************************************************/
/******************************* addListeGroupe************************************/
/**********************************************************************************/

/**********************************************************************************/
/******************************* addListeAllBalise************************************/
/**********************************************************************************/
function addListeAllBalise(idClient, id, nomGpw){
	document.getElementById("nomGroupe").innerHTML = nomGpw;
	this.id=id;
	$('a.list-group-item').removeClass('active');
	$(this.id).addClass('active');
	// document.body.className = "loading";
	document.getElementById("rememberSuivi").innerHTML="no";
	document.getElementById("btnSuivi").className = "btn btn-default btn-sm";
	offSuivi();
	baliseIdArray = [];
	baliseNameArray = [];
	if(document.getElementById("TablePosition")){
		document.getElementById("TablePosition").innerHTML = '<table id="idTablePosition" class="sortable table table-bordered table-hover">' +
			'<thead id="head_idTablePosition">'+
			'<tr><th width="50px">N</th><th width="45px"></th><th width="150px">'+getTextNomBalise+'</th><th width="150px">Date position</th>' +
			'<th width="400px">'+getTextAdresse+'</th><th width="50px">'+getTextVitesse+'</th><th width="350px">'+getTextStatut+'</th>' +
			'<th width="45px">GSM</th><th width="45px">'+getTextAlim+'</th>'+
			'<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>'+
			'<tbody id="body_idTablePosition"></tbody></table>';

		document.getElementById("tableposition_modes").style.display = "none";
		modeTablePosition = "normal";
	}
	clearOverlays();
	clearOverlaysPanorama();

	$.ajax({
		url : 'layoutlisteallbalise.php',
		type : 'GET',
		data : "idClient="+idClient+"&nomBase="+globalnomDatabaseGpw,
		success: function(response) {
			if(response) {
				document.getElementById("ListeBalise").innerHTML = response;
				document.body.className = "";
			}
		}
	});
	if(rememberOngletPointInteret == "yes") initCartoPtInteret();
	if(rememberOngletOption == "yes")	showOptionGroupeBalise();
	if(rememberOngletConfiguration == "yes")	paramAvancee(paramPage);
}
function addListeAllBalise2(idClient, nomGpw){
	document.getElementById("nomGroupe").innerHTML = nomGpw;
	// document.body.className = "loading";
	document.getElementById("rememberSuivi").innerHTML="no";
	document.getElementById("btnSuivi").className = "btn btn-default btn-sm";
	offSuivi();
	baliseIdArray = [];
	baliseNameArray = [];
/*	if(document.getElementById("TablePosition")){
		document.getElementById("TablePosition").innerHTML='<table id="idTablePosition" class="table table-bordered table-hover" >'
		+'<tr><th width="35px">N</th><th width="150px">Nom Balise</th><th width="45px"></th><th width="250px">Adresse</th><th width="50px">Vitesse</th><th width="300px">Statut</th><th width="45px">GSM'
		+'</th><th width="45px">Alim</th><th width="120px">Date/heure</th>'
		+'<th width="100px">Latitude</th><th width="100px">Longitude</th></tr></table>';
	}
	clearOverlays();
	clearOverlaysPanorama();*/
	//

	$.ajax({
		url : 'layoutlisteallbalise2.php',
		type : 'GET',
		data : "idClient="+idClient+"&nomBase="+globalnomDatabaseGpw,
		success: function(response) {
			if(response) {
				document.getElementById("ListeBalise2").innerHTML = response;
				document.body.className = "";
			}
		}
	});
	if(rememberOngletPointInteret == "yes") initCartoPtInteret();
	if(rememberOngletOption == "yes")	showOptionGroupeBalise();
	if(rememberOngletConfiguration == "yes")	paramAvancee(paramPage);
}
/**********************************************************************************/
/******************************* addListeBalise************************************/
/**********************************************************************************/
function addListeBalise(idClient, idGPW, id, nomGpw){

	document.getElementById("nomGroupe").innerHTML = nomGpw;
	this.id=id;
	$('a.list-group-item').removeClass('active');
	// alert($('a.list-group-item').length);

	setCookie("idGPW",idGPW);

	$(this.id).addClass('active');
	if(document.getElementById("TablePosition")){
		modeTablePosition = "normal";
		document.getElementById("TablePosition").innerHTML = '<table id="idTablePosition" class="sortable table table-bordered table-hover">' +
				'<thead id="head_idTablePosition">'+
				'<tr><th width="50px">N</th><th width="45px"></th><th width="150px">'+getTextNomBalise+'</th><th width="150px">Date position</th>' +
				'<th width="400px">'+getTextAdresse+'</th><th width="50px">'+getTextVitesse+'</th><th width="350px">'+getTextStatut+'</th>' +
				'<th width="45px">GSM</th><th width="45px">'+getTextAlim+'</th>'+
				'<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>'+
				'<tbody id="body_idTablePosition"></tbody></table>';
		document.getElementById("tableposition_modes").style.display = "none";
		modeTablePosition = "normal";
	}

	clearOverlays();
	clearOverlaysPanorama();
	if(rememberOngletCartoPosition == "yes") {
		document.getElementById("tablePagination").style.display = "";
		document.getElementById("page-selection").style.display = "none";
		document.getElementById("rappel_date").innerHTML = ".................";
	}

	baliseUnSelectAll();
	baliseIdArray = [];
	baliseNameArray = [];
	// document.body.className = "loading";
	document.getElementById("idBalise").innerHTML="";
	document.getElementById("nomBalise").innerHTML="";
	document.getElementById("unchecked").innerHTML=20;

	$.ajax({
		url : 'layoutlistebalise.php',
		type : 'GET',
		data : "idGPW="+idGPW,
		success: function(response) {
			if(response) {
				document.getElementById("ListeBalise").innerHTML = response;
				document.body.className = "";
				if(rememberOngletCartoPosition == "yes") {
					baliseSelectAll();
					boutonDernierePosition();
					if(	$('#ListeBalise li  input.pull-left').length > 1) baliseUnSelectAll();
				}
			}
		}

	});
	if(rememberOngletPointInteret == "yes") initCartoPtInteret();
	if(rememberOngletOption == "yes")	showOptionGroupeBalise();
	if(rememberOngletConfiguration == "yes")	paramAvancee(paramPage);
}

function addListeBalise2(idClient, idGPW, nomGpw){
	document.getElementById("nomGroupe").innerHTML = nomGpw;
	//alert("test");
	//getGPW(idBDD, nomBDD, ipBDD);

	setCookie("idGPW",idGPW);
	baliseUnSelectAll();
	baliseIdArray = [];
	baliseNameArray = [];
    //
	//document.getElementById("tablePagination").style.display = "";
	//document.getElementById("page-selection").style.display = "none";
	//document.getElementById("rappel_date").innerHTML = ".................";
	// document.body.className = "loading";
	document.getElementById("idBalise").innerHTML="";
	document.getElementById("nomBalise").innerHTML="";
	document.getElementById("unchecked").innerHTML=20;

	if( idGPW == "all" ){
		addListeAllBalise2(idClient, "ALL GROUPS");		// ALL GROUPS ?
	}else{
		$.ajax({
			url : 'layoutlistebalise2.php',
			type : 'GET',
			data : "idGPW="+idGPW,
			success: function(response) {
				if(response) {
					document.getElementById("ListeBalise2").innerHTML = response;
					document.body.className = "";
				}
			}
		});
		if(rememberOngletCartoPosition == "yes") addListeGroupeMarkers(idGPW);
	}
	if(rememberOngletPointInteret == "yes") initCartoPtInteret();
	if(rememberOngletOption == "yes")	showOptionGroupeBalise();
	if(rememberOngletConfiguration == "yes")	paramAvancee(paramPage);

}
/**********************************************************************************/
detectDatabase();
function divContenu(num){

	var rememberAddMarker = document.getElementById("rememberAddMarker").innerHTML;
	var rememberAddPeriode = document.getElementById("rememberAddPeriode").innerHTML;
	var rememberAddPosition = document.getElementById("rememberAddPosition").innerHTML;
	var rememberDivHistorique = document.getElementById("rememberDivHistorique").innerHTML;

	var Id_Tracker=	document.getElementById("idBalise").innerHTML;
	if ($(window).width() < 768) {
		if(typeof topmenu !== 'undefined') {
			if (topmenu == "1") {
				$("#menu_navigation").collapse('hide');

				topmenu = "";
			}
		}

	}
	//document.body.className = "loading";
	switch(num){
		case 1:
			$('#liCarto').parent().parent().find('.active').removeClass('active');
			$('#liCarto').addClass('active');
			
			document.body.className = "";
			rememberOngletCartoPosition= "yes";
			rememberOngletRapport = "";
			rememberOngletGeofencing = "";
			rememberOngletPointInteret = "";
			rememberOngletOption = "";
			rememberOngletConfiguration = "";
			rememberOngletEtatBalise = "";

			$(document).ready(function(){
				$("#TheContenu").load("../carto/carto.php", function () {
					document.getElementById("tableposition_modes").style.display = "none";
					modeTablePosition = "normal";
					if ($(window).width() < 768) {
						$('#divHistorique').insertBefore($('#divCartoPos'));
						document.getElementById("divHistorique").style.display = "none";
						document.getElementById("map_canvas").style.height = (($(window).height()) - 100) + "px";
						if(firstLogMobile == "") {
							$("#wrapper").toggleClass("toggled");
							sidemenu = "1";
							document.getElementById("ouvrir").style.right = "-333px";
							document.getElementById("ouvrir").innerHTML = getTextFermer;



							firstLogMobile = "1";
						}
					}
					//document.getElementById("rememberFiltrageArret").innerHTML="yes";
					if(rememberDivHistorique == "Periode"){
						divHistorique(1);
					   $("#OngletHistoPeriode").addClass('effect active').closest('li').siblings().find('#OngletHistoPos').removeClass('active');

					}else if(rememberDivHistorique == "Historique"){
						divHistorique(2);
						$("#OngletHistoPos").addClass('effect active').closest('li').siblings().find('#OngletHistoPeriode').removeClass('active');
					}

					if(rememberAddMarker == "" && rememberAddPeriode == "" && rememberAddPosition == ""){
						initCartoGoogleMap();
					}else if(rememberAddMarker == "yes" && rememberAddPeriode == "" && rememberAddPosition == ""){
						initCartoGoogleMap();
						// executeLastTablePosition();
					}else if(rememberAddMarker == "" && rememberAddPeriode == "yes" && rememberAddPosition == ""){
						initCartoGoogleMap();
						//addPeriodeMarker();
						//addPeriodeTablePosition();
					}else if(rememberAddMarker == "" && rememberAddPeriode == "" && rememberAddPosition == "yes"){
						initCartoGoogleMap();
						//addPositionMarker();
						//addPositionTablePosition();
					}

					
					//offSuivi();
				});
			});
			break;
		case 2:
			document.body.className = "";
			rememberOngletCartoPosition = "";
			rememberOngletRapport = "yes";
			rememberOngletGeofencing = "";
			rememberOngletPointInteret = "";
			rememberOngletOption = "";
			rememberOngletConfiguration = "";
			rememberOngletEtatBalise = "";
			//alert(Id_Tracker);
			//if(Id_Tracker.search(/,/) != -1){
			//	var regIdTracker = new RegExp("[,]+", "g");
			//	var tableauIdTracker=Id_Tracker.split(regIdTracker);
			//	var regNomBalise = new RegExp("[,]+", "g");
			//	//var tableauNomBalise=nomBalise.split(regNomBalise);
			//	tableauIdTracker.splice(0, (tableauIdTracker.length)-1);
			//	alert("test");
			//}
			$(document).ready(function(){
				$("#TheContenu").load("../rapport/rapport.php", function () {
					divModeRapport(1);

					$("#onglet_rapport_instant").addClass('effect active').closest('li').siblings().find('#onglet_rapport_auto').removeClass('active');
					offSuivi();
				});
			});
			break;
		case 3:
			$("#liGeofencing").parent().parent().find('.active').removeClass('active');
			$("#liGeofencing").addClass('active');
			
			document.body.className = "";
			rememberOngletCartoPosition = "";
			rememberOngletRapport = "";
			rememberOngletGeofencing = "yes";
			rememberOngletPointInteret = "";
			rememberOngletOption = "";
			rememberOngletConfiguration = "";
			rememberOngletEtatBalise = "";
			// var texte3=document.getElementById('TheContenu');
			// texte3.innerHTML="Geofencing";
					
			$(document).ready(function(){
				$("#TheContenu").load("../geofencing/geofencing.php", function () {
					initGeofencingMap();
					listZone();
					selectZone("all");
					if (document.getElementById("id_avec_geofencing").checked == false) offSuivi();
				});
			});
			break;
		case 4:
			document.body.className = "";
			rememberOngletCartoPosition = "";
			rememberOngletRapport = "";
			rememberOngletGeofencing = "";
			rememberOngletPointInteret = "yes";
			rememberOngletOption = "";
			rememberOngletConfiguration = "";
			rememberOngletEtatBalise = "";
			$(document).ready(function(){
				$("#TheContenu").load("../pointinteret/pointinteret.php", function () {
					//detectDatabase();
					initCartoPtInteret();
					offSuivi();
				});
			});
			break;
		case 5:
			document.body.className = "";
			rememberOngletCartoPosition = "";
			rememberOngletRapport = "";
			rememberOngletGeofencing = "";
			rememberOngletPointInteret = "";
			rememberOngletConfiguration = "yes";
			rememberOngletOption = "";
			rememberOngletEtatBalise = "";
			$(document).ready(function(){
				$("#TheContenu").load("../configuration/configuration.php", function () {
					infoConf();
					//paramAvancee(1);
					offSuivi();
				});
			});
			break;
		case 6:
			document.body.className = "";
			rememberOngletCartoPosition = "";
			rememberOngletRapport = "";
			rememberOngletGeofencing = "";
			rememberOngletPointInteret = "";
			rememberOngletOption = "yes";
			rememberOngletConfiguration = "";
			rememberOngletEtatBalise = "";
			$(document).ready(function(){
				$("#TheContenu").load("../option/option.php", function () {
					showOptionGroupeBalise();
					showOptionNomBalise();
					offSuivi();
				});
			});
			break;
		case 7:
			document.body.className = "";
			rememberOngletCartoPosition = "";
			rememberOngletRapport = "";
			rememberOngletGeofencing = "";
			rememberOngletPointInteret = "";
			rememberOngletOption = "";
			rememberOngletConfiguration = "";
			rememberOngletEtatBalise = "yes";
			$(document).ready(function(){
				$("#TheContenu").load("../etatbalise/etatbalise.php", function () {
					afficheEtatBalise();
					offSuivi();
				});
			});
			break;
		case 8:
			var texte6=document.getElementById('TheContenu');
			texte6.innerHTML="Deconnexion";
			offSuivi();
			logout();
			break;
	}
	//if(typeof document.getElementById("action_nav") !== 'undefined')
	//	$("#action_nav").collapse('hide');
}


function getGPW(idBase, nomBase, ipBase){
	//if(document.getElementById("rememberSuivi").innerHTML=="yes"){
	//	document.getElementById("rememberSuivi").innerHTML="no";
	//	document.getElementById("btnSuivi").className = "btn btn-default btn-sm";
	//	offSuivi();
	//}



}

function getGroupe(idGroupe,nomGroupe){
	document.getElementById("idGroupe").innerHTML=idGroupe;
	document.getElementById("nomGroupe").innerHTML=nomGroupe;
}

function getOneBalise(id,name,event){

	if (event.ctrlKey) {
		baliseUnSelectAll();
	}

	if(document.getElementById(id).checked == false) {
		document.getElementById(id).checked = true;
		getBalise(id,name);
	}else{
		document.getElementById(id).checked = false;
		getBalise(id,name);
	}
}
function getOneBaliseCheckbox(id,name){

	if(document.getElementById(id).checked == false) {
		document.getElementById(id).checked = true;
		//getBalise(id,name);
	}else{
		document.getElementById(id).checked = false;
		//getBalise(id,name);
	}
}
function getBalise(idTracker,nomBalise){
	//resetTraitTrajet();
	//clearOverlaysPanorama();
	//clearOverlays();
	//LatLngArray = [];
	//latlngMultipleBalise = [];
	//infoMultipleBalise = [];
	//markerMultipleBalise = [];

	//$('li#cocher_liste_balise').removeClass('active');
	//$('li#decocher_liste_balise').removeClass('active');
	if(document.getElementById("rememberSuivi").innerHTML=="yes"){
		document.getElementById("rememberSuivi").innerHTML="no";
		document.getElementById("btnSuivi").className = "btn btn-default btn-sm";
		offSuivi();
	}
	var index;
	// var checkboxe = $('#ListeBalise li label input.checkbox1').length;
	// var checked = $('#ListeBalise li label input.checkbox1:checked').length;
	var checkboxe = $('#ListeBalise li  input.pull-left').length;
	var checked = $('#ListeBalise  li  input.pull-left:checked').length;
	var unchecked = checkboxe - checked;

	if(checked > document.getElementById("checked").innerHTML ) {
		baliseIdArray.push(idTracker);
		baliseNameArray.push(nomBalise);
	}else{
		//removeA(baliseIdArray,idTracker);
		//removeA(baliseNameArray,nomBalise);

		baliseIdArray.splice(baliseIdArray.indexOf(idTracker),1);
		baliseNameArray.splice(baliseNameArray.indexOf(nomBalise),1);
		//alert(baliseIdArray);
	}
	document.getElementById("idBalise").innerHTML=baliseIdArray;
	document.getElementById("nomBalise").innerHTML=baliseNameArray;

	document.getElementById("checkboxe").innerHTML = checkboxe;
	document.getElementById("checked").innerHTML = checked;
	document.getElementById("unchecked").innerHTML = unchecked;

	if(checked == 0) baliseUnSelectAll();

	getNomVersion();
	if(rememberOngletRapport == "yes"){
		showCarburant();
		if(rememberModeRapport == "auto"){/*listTypeRapport();*/ showListMail();rapportAutoChangeTracker();}
		else enleverBoutonOuvrir1();
	}
	if(rememberOngletGeofencing == "yes"){listZone();selectZone('all');}
	if(rememberOngletPointInteret == "yes") initCartoPtInteret();
	if(rememberOngletConfiguration == "yes"){
		if(idTracker != "") {
			document.body.className = "loading";
			infoConf();
			// if(paramPage == "1") detecterModeFonctionnementEtTempsReel();
			// if(paramPage == "2") detecterDeplacementEtArret();
			// if(paramPage == "3") detecterAlertEtSmS();
		}
		paramAvancee(paramPage);
	}

	if(rememberOngletOption == "yes") {
		if(document.getElementById("affichage_privacy"))document.getElementById("affichage_privacy").style.display = "none";
		showOptionNomBalise();
	}
	if(rememberOngletEtatBalise == "yes") afficheEtatBalise();
}


function getNomVersion(){
	var Id_Tracker =document.getElementById("idBalise").innerHTML;
	var nomBalise=document.getElementById('nomBalise').innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var tz = jstz.determine();
    var timezone = tz.name();
	if (Id_Tracker==""){
		versionBaliseGlobal = 0;
		return;
	}else if(Id_Tracker.search(/,/) != -1){
		return
	}else{
		$.ajax({
			url : '../carto/cartolastaddmarker.php',
			type : 'GET',
			data : "Id_Tracker="+Id_Tracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&timezone="+timezone,
			async: false,
			success: function(response) {
				if(response) {
					
					var coordPosOdometre = response.substring(response.indexOf('Pos_Odometre')+13,response.indexOf('Pos_Adresse'));
					versionBaliseGlobal = coordPosOdometre[0]+coordPosOdometre[1];
					firmwareBaliseGlobal = coordPosOdometre[2]+coordPosOdometre[3];
				
					if((versionBaliseGlobal == "33")&&(firmwareBaliseGlobal == "70"))
						versionBaliseGlobal = "3370";
					else if((versionBaliseGlobal == "22")&&(firmwareBaliseGlobal == "05"))
						versionBaliseGlobal = "2205";
					else if((versionBaliseGlobal == "70")&&(firmwareBaliseGlobal == "03"))
						versionBaliseGlobal = "7003";
					else if((versionBaliseGlobal == "72")&&(firmwareBaliseGlobal == "01"))
						versionBaliseGlobal = "7201";
					else if((versionBaliseGlobal == "80")&&(firmwareBaliseGlobal == "00"))
						versionBaliseGlobal = "8000";
					else if((versionBaliseGlobal == "30")&&(firmwareBaliseGlobal == "06"))
						versionBaliseGlobal = "3006";
					else if((versionBaliseGlobal == "22")&&(firmwareBaliseGlobal == "01"))
						versionBaliseGlobal = "2201";
					else if((versionBaliseGlobal == "28")&&(firmwareBaliseGlobal == "01"))
						versionBaliseGlobal = "2801";
					else if((versionBaliseGlobal == "26")&&(firmwareBaliseGlobal == "01"))
						versionBaliseGlobal = "2601";
					else if((versionBaliseGlobal == "80")&&(firmwareBaliseGlobal == "79"))
						versionBaliseGlobal = "8079";
					else if((versionBaliseGlobal == "80")&&(firmwareBaliseGlobal == "45"))
						versionBaliseGlobal = "8045";

					
					
				}
			}
			
		});
	}
}
function getBalise2(idTracker,nomBalise){

	baliseUnSelectAll();

	baliseIdArray.push(idTracker);
	baliseNameArray.push(nomBalise);

	document.getElementById("idBalise").innerHTML=baliseIdArray;
	document.getElementById("nomBalise").innerHTML=baliseNameArray;

	getNomVersion();
	if(rememberOngletCartoPosition  == "yes") boutonDernierePosition();

	if(rememberOngletEtatBalise  == "yes") afficheEtatBalise();
	else boutonDernierePosition();
	if(rememberOngletGeofencing == "yes"){
		listZone();
		selectZone('all');
	}
	if(rememberOngletPointInteret == "yes") initCartoPtInteret();

}

function include(arr, obj) {
	for(var i=0; i<arr.length; i++) {
		if (arr[i] == obj) return true;
	}
}
function baliseSelectAll(id){
	//this.id = id;
	////alert(id);
	baliseUnSelectAll();
	// $('li#decocher_liste_balise').removeClass('active');
	// $('li#cocher_liste_balise').addClass('active');
	if(document.getElementById("rememberSuivi").innerHTML=="yes"){
		document.getElementById("rememberSuivi").innerHTML="no";
		document.getElementById("btnSuivi").className = "btn btn-default btn-sm";
		offSuivi();
	}
	// document.body.className = "loading";
	  $('#ListeBalise li#id_liste_balise input:checkbox').each(function() {
		  		//alert(this.id);
				//if(!include(baliseIdArray,this.id)) {
				//	this.checked = true;
				//	getBalise(this.id,this.name);
				//}
			//alert(this.id);
		  this.click();

		  //$( this ).addClass( "active" );
		});
	document.body.className = "";
		//  document.getElementById("idBalise").innerHTML = baliseIdArray;
		//  document.getElementById("nomBalise").innerHTML = baliseNameArray;
        //
        //
		//var checkboxe = $('#ListeBalise a  input.pull-right').length;
		//var checked = $('#ListeBalise a  input.pull-right:checked').length;
		//var unchecked = checkboxe - checked;
		//document.getElementById("checkboxe").innerHTML = checkboxe;
		//document.getElementById("checked").innerHTML = checked;
		//document.getElementById("unchecked").innerHTML = unchecked;


	if(rememberOngletGeofencing == "yes"){
		listZone();
		selectZone('all');
	}
	if(rememberOngletPointInteret == "yes") initCartoPtInteret();
}

function baliseUnSelectAll(id){
	// if(id) {
		// this.id = id;
		// $('li#cocher_liste_balise').removeClass('active');
		// $(this.id).addClass('active');
	// }
	
	document.getElementById("rememberAddMarker").innerHTML ="";
	if(document.getElementById("rememberSuivi").innerHTML=="yes"){
		document.getElementById("rememberSuivi").innerHTML="no";
		document.getElementById("btnSuivi").className = "btn btn-default btn-sm";
		offSuivi();
	}
	
	$('#ListeBalise li  input.pull-left').each(function() {
	// $('#ListeBalise li label input.checkbox1').each(function() {
		this.checked = false;
	});
	
	// var checkboxe = $('#ListeBalise li label input.checkbox1').length;
	// var checked = $('#ListeBalise li label input.checkbox1:checked').length;
	var checkboxe = $('#ListeBalise li  input.pull-left').length;
	var checked = $('#ListeBalise li  input.pull-left:checked').length;
	var unchecked = checkboxe - checked;
	baliseIdArray = [];
	baliseNameArray = [];
	document.getElementById("idBalise").innerHTML="";
	document.getElementById("nomBalise").innerHTML="";

	document.getElementById("checkboxe").innerHTML = checkboxe;
	document.getElementById("checked").innerHTML = checked;
	document.getElementById("unchecked").innerHTML = unchecked;
	// clearOverlays();
	// clearOverlaysPanorama();
	// executeLastMarker();executeLastTablePosition();
	if(rememberOngletGeofencing == "yes"){
		listZone();
		selectZone('all');
	}
}

function removeA(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }

    return arr;
}

// function analyserBalise(){
	// if(document.getElementById('gpw').innerHTML==""){
		// document.getElementById('analyseur').innerHTML = "<?php echo $_SESSION['username']; ?>Veuillez selectionner un <b>Groupe de Balises </b>";
	// }else{
		// document.getElementById('analyseur').innerHTML=document.getElementById('gpw').innerHTML;
		// if(document.getElementById('idBalise').innerHTML==""){
			// document.getElementById('analyseur').innerHTML  = "Selection: Groupe: <b>\""+document.getElementById('gpw').innerHTML+"\"</b> ; Veuillez selectionner une <b>balise</b>";
		// }else{
			// document.getElementById('analyseur').innerHTML  = "Selection: Groupe: <b>\""+document.getElementById('gpw').innerHTML+"\"</b> ; Balise: <b>\""+document.getElementById('nomBalise').innerHTMLetElementById('nomBalise').innerHTML+"\"</b> ; Mode Suivi: <b>"+document.getElementById('rememberSuivi').innerHTML+"</b> ; Filtrage Arret: <b>"+document.getElementById('rememberFiltrageArret').innerHTML+"</b>";
		// }
	// }
// }


/*
function refreshHour(){
	date=new Date();
	jour= date.getDate();
	mois= date.getMonth();
	mois = mois + 1;
	if (mois < 10) { mois = '0' + mois; }
	year= date.getYear();
	heure= date.getHours();
	minute= date.getMinutes();
	if (minute < 10) { minute = '0' + minute; }
	seconde= date.getSeconds();
	nompoi="POI - "+jour+"-"+mois+"-"+(year+1900)+"_ "+heure+"h"+minute;
	setTimeout(refreshHour, 30000);
}
*/
/*
function refreshDernierePosition(){
	x = 1;
	executeLastTablePosition();
    setTimeout(refreshDernierePosition, x*100);
}
*/

function addEvent(obj, event, fct) {
    if (obj.attachEvent) //Est-ce IE ?
        obj.attachEvent("on" + event, fct); //Ne pas oublier le "on"
    else
        obj.addEventListener(event, fct, true);
}

addEvent(window , "load", divContenu(1));

var configPtInteret = "";

function configGpwUser(config){

	var liConfiguration = document.getElementById('liConfiguration');
	var liGeofencing = document.getElementById('liGeofencing');
	var liEtatBalise = document.getElementById('liEtatBalise');
	var liOptions = document.getElementById('liOptions');
	var liPtInteret = document.getElementById('liPtInteret');
	var optionGroupeBalise= document.getElementById('optionGroupeBalise');
	var optionNomBalise= document.getElementById('optionNomBalise');
	var optionNumeroBalise= document.getElementById('optionNumeroBalise');
	var optionIconeBalise= document.getElementById('optionIconeBalise');
	var optionOption= document.getElementById('optionOption');
	var modeFonctTempReel= document.getElementById('modeFonctTempReel');
	var detectDeplaceArret= document.getElementById('detectDeplaceArret');
	var alerteEtSms= document.getElementById('alerteEtSms');
	var ongletModeFonctTempReel= document.getElementById('ongletModeFonctTempReel');
	var ongletDetectDeplaceArret= document.getElementById('ongletDetectDeplaceArret');
	var ongletAlerteEtSms= document.getElementById('ongletAlerteEtSms');
	var ongletPlaningGsm= document.getElementById('ongletPlaningGsm');
	var buttonOngletConfiguration= document.getElementById('buttonOngletConfiguration');
	var avecGeofencing = document.getElementById('id_label_avec_geofencing');

	if (config == "WEB_UTILISATEUR" || config == "WEB_UTILISATEUR_NI" || config == "" || config == null) {

		if(rememberOngletConfiguration == "yes"){
			detectDeplaceArret.style.display = "none";
			modeFonctTempReel.style.display = "none";
			alerteEtSms.style.display = "none";
			ongletPlaningGsm.style.display = "none";
		}
		if(rememberOngletOption == "yes"){
			if(config == "WEB_UTILISATEUR") {
				optionGroupeBalise.style.display = "none";
				optionNomBalise.style.display = "none";
				optionIconeBalise.style.display = "none";
			}
			//optionNumeroBalise.style.display = "none";
			//optionIconeBalise.style.display = "none";
			//optionOption.style.display = "none";
		}
		if(rememberOngletPointInteret == "yes"){
			document.getElementById("tr_3eme_etape").style.display = "none";
			document.getElementById("tr_message_arrivee").style.display = "none";
			document.getElementById("tr_message_depart").style.display = "none";
			document.getElementById("tr_validation_poi").style.display = "none";
			document.getElementById("contenu_alerte_active_desactive").style.display = "none";
		}
		liGeofencing.style.display = "none";

		//avecGeofencing.style.display = "none";
		liOptions.style.display = "none";
		//liEtatBalise.style.display = "none";
		liPtInteret.style.display = "none";
		liConfiguration.style.display = "none";
		configPtInteret = "user";


	}

	if (config == "WEB_UTILISATEUR_AVANCE") {
		if(rememberOngletOption == "yes") {
			optionGroupeBalise.style.display = "none";
			optionNomBalise.style.display = "none";
			optionIconeBalise.style.display = "none";
		}
		if(rememberOngletConfiguration == "yes"){
			//buttonOngletConfiguration.style.display = "none";
			ongletPlaningGsm.style.display = "none";
			paramAvancee(1);

		}
	}


	if (config == "WEB_UTILISATEUR_ALARMES") {
		if(rememberOngletConfiguration == "yes"){
			buttonOngletConfiguration.style.display = "none";
			ongletPlaningGsm.style.display = "none";
			paramAvancee(3);

		}
		if(rememberOngletOption == "yes"){
			optionGroupeBalise.style.display = "none";
			optionNomBalise.style.display = "none";
			optionIconeBalise.style.display = "none";
		}
	}
	if (config == "WEB_UTILISATEUR_NI_ALARMES") {
		if(rememberOngletConfiguration == "yes"){
			buttonOngletConfiguration.style.display = "none";
			ongletPlaningGsm.style.display = "none";
			paramAvancee(3);

		}
	}

	if (config == "WEB_GESTIONNAIRE" || config == "SUPERVISEUR" || config == "WEB_UTILISATEUR_NI_AVANCE" ) {
		if(rememberOngletConfiguration == "yes"){
			if (config == "WEB_GESTIONNAIRE"){
				ongletPlaningGsm.style.display = "none";
			} 

			//buttonOngletConfiguration.style.display = "none";
			paramAvancee(1);

		}
	}

}



function valider_numero(elementId) {
	var nombre = elementId.value;
	var chiffres = new String(nombre);
// Enlever tous les charactères sauf les chiffres
	chiffres = chiffres.replace(/[^0-9]/g, '');

// Nombre de chiffres
	compteur = chiffres.length;
	if(nombre == "") return;
	if (nombre[0] == "+" && nombre[1] == "3" && nombre[2] == "3") {
		if (compteur == 11) {
			return
		} else {
			alert($('<div />').html( getTextVeuillezSaisirNumTel).text());

			elementId.value = "";
		}
	}

	if (compteur!=10) {
		alert($('<div />').html( getTextVeuillezSaisirNumTel).text());
		elementId.value = "";
		return;

	}



}
function valider_mail(elementId)
{
	var mailteste = elementId.value;
	var reg = new RegExp('^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$', 'i');

	if(mailteste != "") {
		if (reg.test(mailteste)) {

		}
		else {
			alert(getTextVeuillezSaisirAdresse);
			elementId.value = "";
		}
	}
}


function fn_do(elementId) {
	var numb = elementId.value;
	//var numb = 123;
	var zzz = (parseFloat(numb) || 0).toFixed(2);
	elementId.value = zzz;
}




function lancer(fct) {
   addEvent(window, "load", fct);
}

$(function() {
    $("ul.dropdown-menu").on("click", "[data-stopPropagation]", function(e) {
        e.stopPropagation();
    });
});


function addListeGroupeMarkers(idGpw){


	if(idGpw) {
		$.ajax({
			url: 'layoutlistegroupeaddmarker.php',
			type: 'GET',
			data: "idGpw=" + idGpw,
			success: function (response) {
				if (response) {
					var reg = new RegExp("[&]+", "g");
					var tableau = response.split(reg);
					var nomBalise = [], idBalise = [];
					var nbreLigne = tableau[0].substring(tableau[0].indexOf('t') + 1, tableau[0].indexOf('g'));
					if (nbreLigne) {
						for (var i = 0; i < nbreLigne; i++) {
							idBalise[i] = tableau[i].substring(tableau[i].indexOf('Id_Balise') + 10, tableau[i].indexOf('Nom_Balise'));
							nomBalise[i] = tableau[i].substring(tableau[i].indexOf('Nom_Balise') + 11);


						}
						document.getElementById("idBalise").innerHTML = idBalise;
						document.getElementById("nomBalise").innerHTML = nomBalise;
						boutonDernierePosition();
					}
				}
			}
		});
	}
}

function formatDateAMPM(date) {
	var d = new Date(date);
	var hh = d.getHours();
	var m = d.getMinutes();
	var s = d.getSeconds();
	var dd = "AM";
	var h = hh;
	if (h >= 12) {
		h = hh-12;
		dd = "PM";
	}
	if (h == 0) {
		h = 12;
	}
	m = m<10?"0"+m:m;

	s = s<10?"0"+s:s;

	 h = h<10?"0"+h:h;

	var pattern = new RegExp("0?"+hh+":"+m+":"+s);

	var replacement = h+":"+m;

	replacement += ":"+s;
	replacement += " "+dd;

	return date.replace(pattern,replacement);
}

function setCookie(cname,cvalue)
{
	var d = new Date();
	d.setTime(d.getTime()+ 30 * 24 * 3600 * 1000); // plus 30 days
	var expires = "expires="+d.toGMTString();
	document.cookie = cname+"="+cvalue+"; "+expires;
}

function getCookie(cname)
{
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++)
	{
		var c = ca[i].trim();
		if (c.indexOf(name)==0) return c.substring(name.length,c.length);
	}
	return "";
}

function checkCookie()
{
	var user=getCookie("username");
	if (user!="")
	{
		window.location.href ="http://stackoverflow.com/questions/20766590/how-to-save-user-text-input-html-input-as-cookie"
	}
	else
	{
		if (user!="" && user!=null)
		{
			setCookie("username",user,30);
		}
	}
}

function deleteAllCookies() {
	var cookies = document.cookie.split(";");

	for (var i = 0; i < cookies.length; i++) {
		var cookie = cookies[i];
		var eqPos = cookie.indexOf("=");
		var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
		document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
	}
}

function openSidebar() {
	if(sidemenu == "") {
		$("#wrapper").toggleClass("toggled");
		document.getElementById("ouvrir").style.right = "-333px";
		document.getElementById("ouvrir").innerHTML = getTextFermer;
		sidemenu = "1";
	}else if(sidemenu == "1"){
		$("#wrapper").toggleClass("toggled");
		document.getElementById("ouvrir").style.right = "-82px";
		document.getElementById("ouvrir").innerHTML = getTextOuvrir;
		sidemenu = "";
	}
	if(topmenu == "1"){
		$(".navbar-collapse").collapse('hide');
		topmenu = "";
	}
	if(actionmenu == "1"){
		$("#action_nav").collapse('hide');
		actionmenu = "";
	}
}