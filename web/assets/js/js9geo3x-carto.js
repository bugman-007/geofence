function initializeAutocomplete(id) {
	var element = document.getElementById(id);
	if (element) {
		var autocomplete = new google.maps.places.Autocomplete(element, {types: ['geocode']});
		google.maps.event.addListener(autocomplete, 'place_changed', onPlaceChanged);
	}
}
function onPlaceChanged() {
	var place = this.getPlace();

	// console.log(place);  // Uncomment this line to view the full object returned by Google API.

	for (var i in place.address_components) {
		var component = place.address_components[i];
		for (var j in component.types) {  // Some types are ["country", "political"]
			var type_element = document.getElementById(component.types[j]);
			if (type_element) {
				type_element.value = component.long_name;
			}
		}
	}
}
/**********************************************************************************/
/******************************* initCartoGoogleMap********************************/
/**********************************************************************************/

function initCartoGoogleMap() {
	document.body.className = "loading";

	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var rememberAddMarker = document.getElementById("rememberAddMarker").innerHTML;
	var rememberAddPeriode = document.getElementById("rememberAddPeriode").innerHTML;
	var rememberAddPosition = document.getElementById("rememberAddPosition").innerHTML;
	var Id_Tracker = document.getElementById("idBalise").innerHTML;

	//some provider https://leaflet-extras.github.io/leaflet-providers/preview/
	// map
	map = L.map( 'map_canvas', {
	  center: [47.081012, 2.398782],
	  zoom: 6
	});

	var basemaps1 = {
		Basic : L.tileLayer('https://api.maptiler.com/maps/basic/{z}/{x}/{y}.png?key=EevE8zHrA8OKNsj637Ms',{minZoom: 3,maxZoom: 20, attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">© MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">© OpenStreetMap contributors</a>',crossOrigin: true}),
		//Bright : L.tileLayer('https://maps.tilehosting.com/styles/bright/{z}/{x}/{y}.png?key=EevE8zHrA8OKNsj637Ms', {minZoom: 3, maxZoom: 19, attribution: ' <a href="https://www.maptiler.com/license/maps/" target="_blank">&copy; MapTiler</a> &#124; <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'}),
		Bright : L.tileLayer('https://api.maptiler.com/maps/bright-v2/{z}/{x}/{y}.png?key=EevE8zHrA8OKNsj637Ms',{minZoom: 3,maxZoom: 20, attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">© MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">© OpenStreetMap contributors</a>',crossOrigin: true}),
		Topo : L.tileLayer('https://api.maptiler.com/maps/topo/{z}/{x}/{y}.png?key=EevE8zHrA8OKNsj637Ms',{minZoom: 3,maxZoom: 20, attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">© MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">© OpenStreetMap contributors</a>',crossOrigin: true}),
		//Satellite : L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { minZoom: 3, maxZoom: 19, attribution: ' <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap</a>' }),
		//Satellite : L.tileLayer('https://api.maptiler.com/tiles/satellite/{z}/{x}/{y}.jpg?key=EevE8zHrA8OKNsj637Ms',{minZoom: 3,maxZoom: 20,attribution: "<a href=\"https://www.maptiler.com/copyright/\" target=\"_blank\"><copy; MapTiler</a\><a href=\"https://www.openstreetmap.org/copyright\" target=\"_blank\"><copy; OpenStreetMap contributors</a>",crossOrigin: true}),
		Satellite : L.tileLayer('https://api.maptiler.com/maps/b28da2e1-9e14-4e36-b4e2-543d8cdd79ef/{z}/{x}/{y}.jpg?key=EevE8zHrA8OKNsj637Ms',{minZoom: 3,maxZoom: 20,attribution: "<a href=\"https://www.maptiler.com/copyright/\" target=\"_blank\"><copy; MapTiler</a\><a href=\"https://www.openstreetmap.org/copyright\" target=\"_blank\"><copy; OpenStreetMap contributors</a>",crossOrigin: true}),
		SatelliteHD : L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', { minZoom: 3, maxZoom: 20}),
		Hybride : L.tileLayer('http://www.google.cn/maps/vt?lyrs=y@189&gl=cn&x={x}&y={y}&z={z}', { minZoom: 3, maxZoom: 20}) // h:route, m:standard, p:terrain, r: route altérée, s:satellite, t:terrain seulement, y:hybrid 
		//Positron : L.tileLayer('https://maps.tilehosting.com/styles/positron/{z}/{x}/{y}.png?key=EevE8zHrA8OKNsj637Ms', {minZoom: 3, maxZoom: 19, attribution: ' <a href="https://www.maptiler.com/license/maps/" target="_blank">&copy; MapTiler</a> &#124; <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'}),
		//Street : L.tileLayer('https://maps.tilehosting.com/styles/streets/{z}/{x}/{y}.png?key=EevE8zHrA8OKNsj637Ms',{minZoom: 3, maxZoom: 19, attribution: ' <a href="https://www.maptiler.com/license/maps/" target="_blank">&copy; MapTiler</a> &#124; <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'}),
		//Routier : L.tileLayer('http://korona.geog.uni-heidelberg.de/tiles/roads/x={x}&y={y}&z={z}', {minZoom: 3, maxZoom: 18, attribution: ' <a href="https://www.maptiler.com/license/maps/" target="_blank">&copy; MapTiler</a> &#124; <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'}),
		//Satellite : L.tileLayer('https://maps.tilehosting.com/styles/hybrid/{z}/{x}/{y}.jpg?key=EevE8zHrA8OKNsj637Ms', {minZoom: 3, maxZoom: 18, attribution: ' <a href="https://www.maptiler.com/license/maps/" target="_blank">&copy; MapTiler</a> &#124; <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'}),
		//Google_h: L.tileLayer('http://www.google.cn/maps/vt?lyrs=h@189&gl=cn&x={x}&y={y}&z={z}', { minZoom: 3, maxZoom: 20}),
		//Google_m: L.tileLayer('http://www.google.cn/maps/vt?lyrs=m@189&gl=cn&x={x}&y={y}&z={z}', { minZoom: 3, maxZoom: 20}),
		//Google_p: L.tileLayer('http://www.google.cn/maps/vt?lyrs=p@189&gl=cn&x={x}&y={y}&z={z}', { minZoom: 3, maxZoom: 20}),
		//Google_r: L.tileLayer('http://www.google.cn/maps/vt?lyrs=r@189&gl=cn&x={x}&y={y}&z={z}', { minZoom: 3, maxZoom: 20}),
		//Google_t: L.tileLayer('http://www.google.cn/maps/vt?lyrs=t@189&gl=cn&x={x}&y={y}&z={z}', { minZoom: 3, maxZoom: 20}),
	};
	
	L.control.layers(basemaps1).addTo(map);
	basemaps1.Bright.addTo(map);
	L.control.scale().addTo(map);
	
	// map2
	map2 = L.map('map_canvas2', {
		center: [47.081012, 2.398782],
		zoom: 5
	});
	
	var basemaps2 = {
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

	L.control.layers(basemaps2).addTo(map2);
	basemaps2.Bright.addTo(map2);
	L.control.scale().addTo(map2);
	
	
	if (Id_Tracker != "") {
		
		executeLastMarker();
		executeLastTablePosition();

		document.body.className = "";
	} else {
		
		document.getElementById("tablePagination").style.display = "";
		document.getElementById("page-selection").style.display = "none";
		document.body.className = "";
	}

	// var options = {
		// types: ['(cities)']
	// };
	// var input = document.getElementById('adresse_carto');
	// autocomplete = new google.maps.places.Autocomplete(input, options);

	// 	google.maps.event.addDomListener(window, 'load', function() {
	// 		initializeAutocomplete('adresse_carto');
	// 	});
	echecCageData = 0;
}

function myPosition() {
	if (rememberOngletCartoPosition == "") {
		divContenu(1);
	}
	if (navigator.geolocation)
		navigator.geolocation.getCurrentPosition(successCallbackGeoloc, errorCallbackGeoloc);
	else
		alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");

	if (actionmenu == "1") {
		$("#action_nav").collapse('hide');
		actionmenu = "";
	}

}
/*********************************************************/
/*                     DERNIERE POS                      */
/*                                                       */
/*********************************************************/
/* Fonction OnClick bouton derniere position             */
/*********************************************************/
function boutonDernierePosition() {

	if (document.getElementById("idBalise").innerHTML == "") {
		alert(getTextVeuillezChoisirUneBalise);
	} else {
		
		offSuivi();

		if ((rememberOngletCartoPosition == "") && (rememberOngletGeofencing == "") && (rememberOngletPointInteret == ""))
		{
			divContenu(1);
		}
		else if (rememberOngletCartoPosition == "yes")
		{
			document.getElementById("tableposition_modes").style.display = "";
			
			if (tailleTablePosCarto == "maxi")
				agrandirTablePos();

			document.getElementById("tableposition_li_choixbalises").innerHTML = "";
			document.getElementById("tableposition_choixbalises").style.display = "none";
			resetTraitTrajet();
			executeLastMarker();
			executeLastTablePosition();
		}
		else if( (rememberOngletPointInteret == "yes") || (rememberOngletGeofencing == "yes") )
		{
			executeLastMarker();
		}
		
		
		if (refreshPage) {			// Deselectionne les balises après affichage des points à la selection d'un groupe
			if ($(window).width() >= 768) {
				if ($('#ListeBalise li  input.pull-left').length > 1)
					baliseUnSelectAll();
			}
			refreshPage = false;
		}
		
		//if ($(window).width() < 768) {
		//	document.getElementById("menu-toggle").click();
		//}
	}
}

/*********************************************************/
/*                      MODE SUIVI                       */
/*                                                       */
/*********************************************************/
/* Fonction de rafraichissment automatique en mode suivi */
/*********************************************************/
var suivi = "";
var nbtracker = 1;
function onSuivi() {
	
    x = 1;
    suivi = setTimeout(onSuivi, (nbtracker * 250) + 5000);
	
	if ((rememberOngletCartoPosition == "") && (rememberOngletGeofencing == "") && (rememberOngletPointInteret == ""))
	{
		divContenu(1);
	}
	else if (rememberOngletCartoPosition == "yes")
	{
		document.getElementById("tableposition_modes").style.display = "";
		document.getElementById("tableposition_li_choixbalises").innerHTML = "";
		document.getElementById("tableposition_choixbalises").style.display = "none";
		
		resetTraitTrajet();
		executeLastMarker();
		executeLastTablePosition();
	}
	else if( (rememberOngletPointInteret == "yes") || (rememberOngletGeofencing == "yes") )
	{
		executeLastMarker();
	}
	
}

/********************************/
/* Fonction arretant mode suivi */
/********************************/
function offSuivi() {
    if (suivi) {
        document.getElementById("rememberSuivi").innerHTML = "no";
        document.getElementById("btnSuivi").className = "btn btn-default btn-sm";
        clearTimeout(suivi);
        //clearOverlaysPanorama();
        //clearOverlays();
        //LatLngArray = [];
        //latlngMultipleBalise = [];
        //infoMultipleBalise = [];
        //markerMultipleBalise = [];
		//clearPolygone();
		//ClearMarkerAdresse();
		//ClearPOImarkers();
        suivi = "";
    }
}

/**************************************/
/* Fonction OnClick bouton mode suivi */
/**************************************/
function boutonSuivi() {
    if ($(window).width() < 768) {
        document.getElementById("menu-toggle").click();
    }
}

function modeSuivi() {
    if (document.getElementById("idBalise").innerHTML == "") {
        alert(getTextVeuillezChoisirUneBalise);
    } else {
		
        if (document.getElementById("rememberSuivi").innerHTML == "no") {
            onSuivi();
            document.getElementById("rememberSuivi").innerHTML = "yes";
            document.getElementById("btnSuivi").className = "btn btn-primary btn-sm";
        } else {
            offSuivi();
        }
		
    }
}

/**********************************************************************************/
/*                    CITY CIRCLE (DERNIERE POS & MODE SUIVI)                     */
/*                                                                                */
/**********************************************************************************/
/* Fonction dessinant le cercle autour d'une adresse                              */
/**********************************************************************************/
var markerCartoAddress=null;
var latlngCartoAddress=null;
var AdresseCircle=null;
var geocoder=null;

function ClearMarkerAdresse()
{
	if(markerCartoAddress){
		map.removeLayer(markerCartoAddress);
		markerCartoAddress = null;
		latlngCartoAddress = null;
	}
}

function ClearCircleAdresse()
{
    if (AdresseCircle) {
		map.removeLayer(AdresseCircle);
		AdresseCircle=null;
    }
}

function visualiserAdresseWithTrackers()
{
    //var idTracker = document.getElementById("idBalise").innerHTML;
    // if(idTracker == "" ){
    // }
	
    var km = document.getElementById('km_carto').value;
    if (!km){
        km = 0;
	}
    setCookie("km_carto", km);

	
	if($('.leaflet-control-geocoder.leaflet-bar.leaflet-control').is(':visible'))
	{
		ClearMarkerAdresse();
		ClearCircleAdresse();
		geocoder.remove();
		
		document.getElementById("btn_adresse_carto").className = "btn btn-default btn-xs";
		$('#id_filtrage_adresse_carto').hide();
		
		setCookie("adresse_carto", "");
		document.getElementById('adresse_carto').value = "";
	}
	else
	{
		document.getElementById("btn_adresse_carto").className = "btn btn-primary btn-xs";
		$('#id_filtrage_adresse_carto').show();

		geocoder = new L.Control.geocoder({
			defaultMarkGeocode: false
		});
		
		
		geocoder.on('markgeocode', function(e)
		{
			ClearMarkerAdresse();
			ClearCircleAdresse();
			
			if(modeTablePosition == "kmadress")
			{
				clearOverlaysPanorama();
				clearOverlays();
				LatLngArray = [];
				//MarkersArray = [];
			}

			latlngCartoAddress = e.geocode.center;
			var latCartoAddress = latlngCartoAddress.lat; 
            var lngCartoAddress = latlngCartoAddress.lng; 
			
			$.getJSON('https://geocoder.tilehosting.com/r/'+ lngCartoAddress +'/'+ latCartoAddress +'.js?key=EUON3NGganG4JD5zzQlN', function(data)
			{
				var adresse = data.results[0].display_name;
				setCookie("adresse_carto", adresse);
				document.getElementById('adresse_carto').value = adresse;
				
				var iconAddress = new Image();
				iconAddress.src = '../../assets/img/blue-pushpin.png';
				var imageMarker = new L.icon({iconUrl:iconAddress.src, iconSize: [40, 40] ,iconAnchor: [10, 38], popupAnchor: [0, -14]});
				
				markerCartoAddress = new L.marker([latCartoAddress,lngCartoAddress],
				{
					icon: imageMarker,
					title: adresse
				})
				.bindPopup(adresse)
				.addTo(map);
				
			});
			
			
			var km = document.getElementById('km_carto').value;
			AdresseCircle = new L.circle([latCartoAddress,lngCartoAddress], parseInt(km) * 1000);
			map.addLayer(AdresseCircle);
			
			
			var zoomMax;
			if (parseInt(km) <= 5){
				zoomMax = 13;
			}else if (parseInt(km) <= 10){
				zoomMax = 12;
			}else if (parseInt(km) <= 25){
				zoomMax = 11;
			}else if (parseInt(km) <= 45){
				zoomMax = 10;
			}else if (parseInt(km) <= 100){
				zoomMax = 9;
			}else if (parseInt(km) == 200){
				zoomMax = 8;
			}
			
			if (LatLngArray.length > 1){
				SetZoom();
			} else {
				map.setView(latlngCartoAddress,zoomMax);
			}
			
		})
		.addTo(map);
	}
    // }else {
        // //alert(getTextPasSaisieAdressePostale);
    // }
}

/**********************************************************************************/
/*                       CARTO (DERNIERE POS & MODE SUIVI)                        */
/*                                                                                */
/**********************************************************************************/
/********************************executeLastMarker*********************************/
/**********************************************************************************/
function executeLastMarker()
{
	clearOverlaysPanorama();	// Efface tous les markers references par MarkersArrayPanorama
	clearOverlays();			// Efface tous les markers references par MarkersArray
	LatLngArray = [];
	
	latlngMultipleBalise = [];
	infoMultipleBalise = [];
	markerMultipleBalise = [];

	if(rememberOngletPointInteret == "")
		ClearPOImarkers();
	
	if (rememberOngletGeofencing == "")
	{
		clearPolygone();
		
		if (document.getElementById("id_avec_geofencing").checked == true)
			showAllZone();
	}
	
	// recupération nom balise
    var Id_Tracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;
	
	// ajout de la dernière pos pour chaque balise
    if (Id_Tracker.search(/,/) != -1) {
        var regIdTracker = new RegExp("[,]+", "g");
        var tableauIdTracker = Id_Tracker.split(regIdTracker);
		var tableauIdTrackerlen = tableauIdTracker.length;
		nbtracker = tableauIdTrackerlen;
        var regNomBalise = new RegExp("[,]+", "g");
        var tableauNomBalise = nomBalise.split(regNomBalise);
		
        for (var i = 0; i < tableauIdTrackerlen; i++) {
			
			if(rememberOngletPointInteret == "")
			{
				if(document.getElementById("idpoi").checked == true)
					showMarkerPoiTracker(tableauIdTracker[i]);
			}
			
            addLastMarker(tableauIdTracker[i], tableauNomBalise[i], tableauIdTrackerlen, i);
        }

    } else {
		
		if(rememberOngletPointInteret == "")
		{
			if(document.getElementById("idpoi").checked == true)
				showMarkerPoiTracker(Id_Tracker);
		}
		
        addLastMarker(Id_Tracker, nomBalise, 1, 0);
    }
}


/**********************************************************************************/
/************************************ Geocoding ***********************************/
/**********************************************************************************/
var echecCageData = 0;
function geocoding(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, id, markerhandle)
{
	if( echecCageData == 0 )
	{
		geocodingCage(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, id, markerhandle);
		
	}
	else
	geocodingTile(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, id, markerhandle);
}

function geocodingCage(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, id, markerhandle)
{
	//console.log("Cage",Id_Tracker, coordDateTimeUTC, coordLat, coordLong,echecCageData);
	$.ajax({
		//async: false,
		//global: false,
		url: 'https://api-adresse.data.gouv.fr/reverse/?lon='+ coordLong +'&lat='+ coordLat,
		//dataType: "json",
		success: function (data) {
			//alert(data);
			//console.log(data.type, data.features[0].properties.label);
			if(data.features[0].properties.label != "")
			{
				var coordPosAdresse = data.features[0].properties.label;
				cartoinsertadresse(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, coordPosAdresse, id, markerhandle);
			}
			else
			{
				console.log("opencagedata query limit!");
				echecCageData = 1;
				geocodingTile(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, id, markerhandle);
			}
		},
		error: function() {
			console.log("opencagedata request failed!");
			echecCageData = 1;
			rapportgeocodingTile(Id_Tracker, coordDateTimeUTC, coordLat, coordLong);
		}
	});


}

function geocodingGoogle(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, id, markerhandle)
{
	$.getJSON('https://maps.googleapis.com/maps/api/geocode/json?latlng='+ coordLat +','+ coordLong +'&key=AIzaSyCWOutUA1jir2vHwqwLKyRmRiFIYhDPj8k', function(data)
	{
		
		if( data.status == "OK")
		{
			console.log("Google",data.status, data.results[0].formatted_address);
			var coordPosAdresse = data.results[0].formatted_address;
			cartoinsertadresse(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, coordPosAdresse, id, markerhandle);
		}
		else
		{
			console.log("Erreur de geocoding Google :", data.status, data.error_message,coordLat +','+ coordLong);
			echecCageData = 1;
			geocodingTile(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, id, markerhandle);
		}
	})
	.fail(function(jqXHR, textStatus, errorThrown)
	{
		console.log('getJSON request failed! :' + errorThrown + " " + textStatus);
		echecCageData = 1;
		geocodingTile(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, id, markerhandle);
	});
}

function geocodingTile(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, id, markerhandle)
{
	$.getJSON('https://api.maptiler.com/geocoding/'+ coordLong +','+ coordLat +'.json?key=EevE8zHrA8OKNsj637Ms', function(data)

	{
		//console.log(data);
		if(data.features[0].place_name != "")
		{
			var coordPosAdresse = data.features[0].place_name;
			//console.log(coordPosAdresse)
			cartoinsertadresse(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, coordPosAdresse, id, markerhandle);
		}
		else
		{
			console.log("no address!");
			echecCageData = 1;
		}
	})
	.fail(function(jqXHR, textStatus, errorThrown)
	{
		console.log('getJSON request failed! :' + errorThrown + " " + textStatus);
	});
}

function cartoinsertadresse(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, coordPosAdresse, id, markerhandle)
{
    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;
	
	if(Id_Tracker != 0)
	{
		$.ajax({
			url: '../carto/cartoinsertadresse.php',
			type: 'GET',
			data: "datetime=" + coordDateTimeUTC + "&address=" + coordPosAdresse +
				  "&lat=" + coordLat + "&lng=" + coordLong + "&idTracker=" + Id_Tracker +
				  "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw
		});
	}
	
	if(id)
	{
		var arrayColonnes = id.cells;
		
		if (arrayColonnes.length === 12)
			arrayColonnes[4].innerHTML = coordPosAdresse;
		else if (arrayColonnes.length === 13)
			arrayColonnes[5].innerHTML = coordPosAdresse;
	}
	
	if(markerhandle)
	{
		var infoBul = markerhandle.getPopup().getContent();
		
		if( infoBul.search( "</table></center>" ) >= 0 )
		{
			infoBul = infoBul.substring(0, infoBul.indexOf("</table></center>"));
			infoBul = infoBul + "<tr><td>" + coordPosAdresse + "</td></tr></table></center>";
			
			markerhandle.getPopup().setContent(infoBul);
			markerhandle.getPopup().update();
		}
	}
}



/**********************************************************************************/
/***********************************addLastMarker**********************************/
/**********************************************************************************/
var listNomBaliseSansPos = new Array();
function addLastMarker(Id_Tracker, nomBalise, multipleTracker, iteration) {

    //if(flightPath) flightPath.setMap(null);

    document.getElementById("rememberAddMarker").innerHTML = "yes";
	document.getElementById("rememberAddPeriode").innerHTML = "";
	document.getElementById("rememberAddPosition").innerHTML = "";


    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;
    var tz = jstz.determine();
    var timezone = tz.name();

    if (Id_Tracker != "") {
        $.ajax({
            url: '../carto/cartolastaddmarker.php',
            type: 'GET',
            data: "Id_Tracker=" + Id_Tracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone,
            async: false,
            success: function (response) {
                if (response) {

                    var coordDateTimeUTC = response.substring(response.indexOf('Pos_DateTime_UTC:') + 17, response.indexOf('Pos_DateTime_position'));
                    var coordDateTimePosition = response.substring(response.indexOf('Pos_DateTime_position') + 22, response.indexOf('Pos_Latitude'));
                    var coordLat = response.substring(response.indexOf('Pos_Latitude') + 13, response.indexOf('Pos_Longitude'));
                    var coordLong = response.substring(response.indexOf('Pos_Longitude') + 14, response.indexOf('Pos_Statut'));
                    var coordPosStatut = response.substring(response.indexOf('Pos_Statut') + 11, response.indexOf('Pos_Vitesse'));
                    var coordPosVitesse = Math.round(parseInt(response.substring(response.indexOf('Pos_Vitesse') + 12, response.indexOf('Pos_Direction'))));
                    var coordPosDirection = response.substring(response.indexOf('Pos_Direction') + 14, response.indexOf('Pos_Odometre'));
                    var coordPosOdometre = response.substring(response.indexOf('Pos_Odometre') + 13, response.indexOf('Pos_Adresse'));
                    var coordPosAdresse = response.substring(response.indexOf('Pos_Adresse') + 12, response.indexOf('Pos_Key'));
					var Pos_Key = response.substring(response.indexOf('Pos_Key:') + 8, response.indexOf('Statut2:'));
					var Statut2 = response.substring(response.indexOf('Statut2:') + 8, response.indexOf('BattInt:'));
					var BattInt = response.substring(response.indexOf('BattInt:') + 8, response.indexOf('BattExt:'));
					var BattExt = response.substring(response.indexOf('BattExt:') + 8, response.indexOf('Alim:'));
					var Alim =  response.substring(response.indexOf('Alim:') + 5, response.indexOf('TypeServer:'));
					var TypeServer = response.substring(response.indexOf('TypeServer:') + 11, response.indexOf('Icone:'));
                    var coordIcone = response.substring(response.indexOf('Icone') + 6);
                    
					var AffichePos = 1;
					var numeroBalise = iteration;
					
					if(rememberOngletCartoPosition == "yes"){
						if(latlngCartoAddress != null) {
							var adresse = document.getElementById('adresse_carto').value;
							var checkbox_adresse = document.getElementById('id_filtrage_adresse_carto').checked;

							if (adresse != "" && checkbox_adresse == true) {
								var distancePoiEtEtape = getDistanceFromLatLonInKm(latlngCartoAddress.lat, latlngCartoAddress.lng, coordLat, coordLong);
								var km_carto_rayon = document.getElementById('km_carto').value;
								distancePoiEtEtape = distancePoiEtEtape / 1000;
								if (distancePoiEtEtape > km_carto_rayon) {
									AffichePos = 0;
								}
							}
						}
					}
				
					if(AffichePos == 1){

						var DecodedStatus = DecodeStatus(coordPosStatut, coordPosOdometre, coordPosVitesse, Pos_Key, Statut2, BattInt, BattExt, Alim, TypeServer, 2);
						var iconesDirectionVitesse = IconeBalise(coordPosStatut, coordPosVitesse, coordPosDirection);

						if (coordLat != "" && coordLong != "" && coordLat != 0 && coordLong != 0) {
							latlng = new L.LatLng(coordLat, coordLong);
							
							// Assemblage contenu infobulle
							infowindow = "<center><table><tr><td><img src='"+iconesDirectionVitesse+"'> * <b>" + nomBalise + " </b>" +
									"</td></tr><tr><td>* " + coordDateTimePosition + " - " + substractDateTime(coordDateTimePosition) +
									"</td></tr><tr><td>* " + DecodedStatus + "</td></tr>" +
									"<tr><td>" + coordPosAdresse + "</td></tr></table></center>";
							
							
							
							var iconCar = new Image();
							iconCar.src = "../../assets/img/BibliothequeIcone/" + coordIcone;
							var imageMarker = new L.icon({iconUrl:iconCar.src, iconSize: [40, 40] ,iconAnchor: [10, 38], popupAnchor: [0, -14]});
							
							if (multipleTracker > 1) {		// Multi balises

								if (numeroBalise > markerMultipleBalise.length)		// au cas où des markers n'ont pas été affichés à cause du filtrage adresse ou de position coord 0,0 ou inexistantes
									numeroBalise = markerMultipleBalise.length;
								
								LatLngArray.push(latlng);
								latlngMultipleBalise.push(latlng);
								infoMultipleBalise.push(infowindow);

								markerMultipleBalise[numeroBalise] = new L.marker([latlngMultipleBalise[numeroBalise].lat, latlngMultipleBalise[numeroBalise].lng],
								{
									icon:imageMarker,
									title: nomBalise+":\n"+coordDateTimePosition
								})
								.bindPopup(infoMultipleBalise[numeroBalise])
								.addTo(map);

								
								// zoom et ouverture infobulle
								if (document.getElementById("id_centrer_zoom").checked){
									//if ($(window).width() >= 768) {
									//    markerMultipleBalise[numeroBalise].openPopup();
									//}
									if( (iteration + 1) == multipleTracker)
										SetZoom();
								}

								MarkersArray.push(markerMultipleBalise[numeroBalise]);

							} else {						// Mono balise
							
								LatLngArray.push(latlng);
								latlngMultipleBalise.push(latlng);
								infoMultipleBalise.push(infowindow);
								
								markerMultipleBalise[0] = new L.marker([latlngMultipleBalise[0].lat, latlngMultipleBalise[0].lng],
								{
									icon:imageMarker,
									title: nomBalise+":\n"+coordDateTimePosition
								})
								.bindPopup(infoMultipleBalise[0])
								.addTo(map);
								
								// zoom et ouverture infobulle
								if (rememberOngletCartoPosition == "yes") {
									if (adresse != "") {
										if (document.getElementById("id_centrer_zoom").checked){
											if ($(window).width() >= 768) {
												markerMultipleBalise[0].openPopup();
											}
											SetZoom();
										}
									} else {
										if ($(window).width() >= 768) {
											markerMultipleBalise[0].openPopup();
										}
										if (document.getElementById("id_centrer_zoom").checked) {
											map.setView(latlng, 16);
										}
									}
								} else {		// Sur onglet geofencing
									if (document.getElementById("id_centrer_zoom").checked) {
										if (suivi == "") {
											if ($(window).width() >= 768) {
												markerMultipleBalise[0].openPopup();
											}
										}
										SetZoom();
									}
								}
								
								MarkersArray.push(markerMultipleBalise[0]);
							}
							
							if ((!coordPosAdresse) || (coordPosAdresse == "undefined") || (coordPosAdresse == "") || (coordPosAdresse == null)) {
								geocoding(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, null, markerMultipleBalise[numeroBalise]);
							}

							//Vue Rapprochée
							var iconCar2 = new Image();
							iconCar2.src = iconesDirectionVitesse;
							var imageMarker2 = new L.icon({iconUrl:iconCar2.src, iconAnchor: [9, 21], popupAnchor: [0, -14]});
							
							MarkersArrayPanorama[numeroBalise] = new L.marker([latlngMultipleBalise[numeroBalise].lat, latlngMultipleBalise[numeroBalise].lng],
							{
								icon:imageMarker2,
								title: nomBalise+":\n"+coordDateTimePosition
							})
							.bindPopup(infoMultipleBalise[numeroBalise])
							.addTo(map2);

							//Streetview
							/*iconPanorama = iconesDirectionVitesse;
							latlongPanorama = latlng;
							panoramaOptions = {
								position: latlng,
								pov: {
									heading: parseFloat(coordPosDirection.replace(",", ".")),
									pitch: 10
								}
							}*/

							map2.setView(latlng, 15);

							//if (document.getElementById("rememberStreet").innerHTML == "yes")
							//	streetMap();

						}
						else		// Coordonnees 0,0 ou position non trouvee
						{
							if (document.getElementById("rememberSuivi").innerHTML == "no")
							{ 
								listNomBaliseSansPos.push('\n' + nomBalise);
							}
						}
					}
					
					// Message "Pas de positions trouvee"
                    if (document.getElementById("rememberSuivi").innerHTML == "no")
					{
						if( (iteration + 1) == multipleTracker) {			// Derniere balise affichee ?
							if (listNomBaliseSansPos.length > 0) {
								alert($('<div />').html(getTextPasDePositions + ':\n' + listNomBaliseSansPos).text());
							}
							listNomBaliseSansPos = [];
						}
                    }
					
                    if (document.body.className == "loading")
                        document.body.className = "";
                }
            }
        });
    }
}

/**********************************************************************************/
/*                    TALBLE POS (DERNIERE POS & MODE SUIVI)                      */
/*                                                                                */
/**********************************************************************************/
/****************************executeLastTablePosition******************************/
/**********************************************************************************/
function executeLastTablePosition() {


    var Id_Tracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;
    document.getElementById("body_idTablePosition").innerHTML = "";
	
    switch (modeTablePosition) {
        case "normal":
            document.getElementById("head_idTablePosition").innerHTML = '<tr><th width="40px" style="display:none">N°</th><th width="40px" style="display:none">Km</th><th width="35px"></th><th width="166px">' + getTextNomBalise + '</th><th width="120px">Date position</th>' +		// traduction manquante : Date position
                    '<th width="400px">' + getTextAdresse + '</th><th width="50px">' + getTextVitesse + '</th><th width="350px">' + getTextStatut + '</th>' +
                    '<th width="45px">GSM</th><th width="45px">' + getTextAlim + '</th>' +
                    '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>';
			
 			idlignetabpos = 0;
            if (Id_Tracker.search(/,/) != -1) {
                var regIdTracker = new RegExp("[,]+", "g");
                var tableauIdTracker = Id_Tracker.split(regIdTracker);
                var regNomBalise = new RegExp("[,]+", "g");
                var tableauNomBalise = nomBalise.split(regNomBalise);
                for (var i = 0; i < tableauIdTracker.length; i++) {
                    addLastTablePosition(tableauIdTracker[i], tableauNomBalise[i]);
                }
            } else {
                    addLastTablePosition(Id_Tracker, nomBalise);
            }

            break;
        case "kmaddress":
			document.getElementById("head_idTablePosition").innerHTML = '<tr><th width="40px" style="display:none">N°</th><th width="40px">Km</th><th width="35px"></th><th width="166px">' + getTextNomBalise + '</th><th width="120px">Date position</th>' +		// traduction manquante : Date position
					'<th width="400px">' + getTextAdresse + '</th><th width="50px">' + getTextVitesse + '</th><th width="350px">' + getTextStatut + '</th>' +
					'<th width="45px">GSM</th><th width="45px">' + getTextAlim + '</th>' +
					'<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>';

			idlignetabpos = 0;
			if (Id_Tracker.search(/,/) != -1) {
				var regIdTracker = new RegExp("[,]+", "g");
				var tableauIdTracker = Id_Tracker.split(regIdTracker);
				var regNomBalise = new RegExp("[,]+", "g");
				var tableauNomBalise = nomBalise.split(regNomBalise);
				for (var i = 0; i < tableauIdTracker.length; i++) {
					addLastTablePosition(tableauIdTracker[i], tableauNomBalise[i]);
				}
			} else {
				addLastTablePosition(Id_Tracker, nomBalise);
			}

			// document.getElementById("TablePosition").innerHTML += "</tbody><table>";
			// twPret(twInit);
			if (document.getElementById("rememberSuivi").innerHTML != "yes"){
				var adresse = document.getElementById('adresse_carto').value;
				if (adresse == "") {
					alert("Il n'y a pas d'adresse de renseignée, la distance en km par rapport à l'adresse ne peut être affichée.\n\nPour éviter ce message renseigner une adresse ou bien mettre le mode de la table positions sur \"Normal\"");			// Traduction manquante
				}
			}
            break;
    }

    sortableTablePosition();
}

function sortableTablePosition() {

    $('.sortable').tablesorter({
        dateFormat: "yyyy-mm-dd",
        sortInitialOrder: "asc",
        headers: {
            // set "sorter : false" (no quotes) to disable the column
            0: {sorter: "text"},
            1: {sorter: "text"},
            2: {sorter: "text"}

        }
    });

    $(".sortable").trigger("updateAll");
}

/**********************************************************************************/
/* Fonction du clic dans menu mode de la table position                           */
/**********************************************************************************/
var modeTablePosition = "normal";
function tablePosMode(mode) {

	modeTablePosition = mode;
	
	if (document.getElementById("rememberSuivi").innerHTML != "yes")
	{
		boutonDernierePosition();
	}
}


/**********************************************************************************/
/**************************** addLastTablePosition*********************************/
/**********************************************************************************/
var idlignetabpos = 0;
function addLastTablePosition(Id_Tracker, nomBalise) {

    //var idTablePosition = document.getElementById("idTablePosition");
	
    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;
	
    var tz = jstz.determine();
    var timezone = tz.name();

    if (Id_Tracker == "") {
        alert(getTextVeuillezChoisirUneBalise);
    } else {
        $.ajax({
            url: '../carto/cartolastaddmarker.php',
            type: 'GET',
            data: "Id_Tracker=" + Id_Tracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone + "&typedecodage=1",
			async: false,
            success: function (response) {
                if (response) {
                    var coordDateTimeUTC = response.substring(response.indexOf('Pos_DateTime_UTC:') + 17, response.indexOf('Pos_DateTime_position'));
                    var coordDateTimePosition = response.substring(response.indexOf('Pos_DateTime_position') + 22, response.indexOf('Pos_Latitude'));
                    var coordLat = response.substring(response.indexOf('Pos_Latitude') + 13, response.indexOf('Pos_Longitude'));
                    var coordLong = response.substring(response.indexOf('Pos_Longitude') + 14, response.indexOf('Pos_Statut'));
                    
                    if (coordLat != "" && coordLong != "" && coordLat != 0 && coordLong != 0){
					
						var Pos_Vitesse = response.substring(response.indexOf('Pos_Vitesse:') + 12, response.indexOf('Pos_Direction'));
						var Pos_Direction = response.substring(response.indexOf('Pos_Direction:') + 14, response.indexOf('Pos_Odometre'));
						
						var coordPosAdresse = response.substring(response.indexOf('Pos_Adresse:') + 12, response.indexOf('DecodedStatus'));
						var DecodedStatus = response.substring(response.indexOf('DecodedStatus:') + 14, response.indexOf('IconDirVitesse'));
						var IconDirVitesse = response.substring(response.indexOf('IconDirVitesse:') + 15, response.indexOf('IconeBrouilleur'));
						var IconeBrouilleur = response.substring(response.indexOf('IconeBrouilleur:') + 16, response.indexOf('IconeDefautAlim'));
						var IconeDefautAlim = response.substring(response.indexOf('IconeDefautAlim:') + 16, response.indexOf('Icone:'));
						

						var distancePoiEtEtape = "";
						
						var adresse = document.getElementById('adresse_carto').value;
						var checkbox_adresse = document.getElementById('id_filtrage_adresse_carto').checked;
						
						if(latlngCartoAddress != null) {
							if (adresse != "") {
								distancePoiEtEtape = getDistanceFromLatLonInKm(latlngCartoAddress.lat, latlngCartoAddress.lng, coordLat, coordLong);
								distancePoiEtEtape = distancePoiEtEtape / 1000;
								
								if (checkbox_adresse == true) {
									var km_carto_rayon = document.getElementById('km_carto').value;
									if (distancePoiEtEtape > km_carto_rayon) {
										return;
									}
								}
								
								distancePoiEtEtape = distancePoiEtEtape * 10;			// Arrondi 1 chiffre après la virgule pour l'affichage dans table pos
								distancePoiEtEtape = Math.round(distancePoiEtEtape);
								distancePoiEtEtape = distancePoiEtEtape / 10;
							}
						}
						
						var NewLigneTablePos = "<style type=\"text/css\"> .sortable tr:hover {cursor: pointer;}</style>";
						
						if (modeTablePosition == "kmaddress"){
							NewLigneTablePos += "<tr onclick=\"OpenInfobullTable(this)\" ><td style='display:none'>" + idlignetabpos + "</td><td>" + distancePoiEtEtape + "</td>";
						}else{
							NewLigneTablePos += "<tr onclick=\"OpenInfobullTable(this)\" ><td style='display:none'>" + idlignetabpos + "</td><td style='display:none'></td>";
						}
						
						//NewLigneTablePos += "<td><img src='"+IconDirVitesse+"'></td><td>" + nomBalise + "</td><td>" + coordDateTimePosition + "</td>";
						NewLigneTablePos += "<td>"+IconDirVitesse+"</td><td>" + nomBalise + "</td><td>" + coordDateTimePosition + "</td>";
						NewLigneTablePos += "<td>" + coordPosAdresse + "</td>";
						NewLigneTablePos += "<td>" + Pos_Vitesse + "</td><td>" + DecodedStatus + "</td>" + IconeBrouilleur + "<td>" + IconeDefautAlim + "</td>";
						NewLigneTablePos += "<td style='display:none'>" + coordLat + "</td><td style='display:none'>" + coordLong + "</td><td style='display:none'>" + Pos_Direction + "</td></tr>";
						
						document.getElementById("body_idTablePosition").innerHTML += NewLigneTablePos;
						document.getElementById("tablePagination").style.display = "";
						document.getElementById("page-selection").style.display = "none";
						document.getElementById("rappel_date").innerHTML = ".................";
						$(".sortable th").click(sortableTablePosition);
						
						idlignetabpos++;
						
						if ((!coordPosAdresse) || (coordPosAdresse == "undefined") || (coordPosAdresse == "") || (coordPosAdresse == null))
						{
							var idTablePosition = document.getElementById("idTablePosition");
							
							if (!idTablePosition.rows[idlignetabpos])
								geocoding(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, null, null);
							else
								geocoding(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, idTablePosition.rows[idlignetabpos], null);
						}
					}
                }
            }
        });
    }
}

/**********************************************************************************/
/******************************** OpenInfobullTable *******************************/
/**********************************************************************************/
function OpenInfobullTable(id) 
{
    this.id = id;
	
    var tabIdLigne;
    var arrayColonnes = this.id.cells;
	
	// Mise en surbrillance de la ligne sur laquelle on vient de cliquer
    $('tr').children('td').removeClass('active');
    $(this.id).children('td').addClass('active');

	
	tabIdLigne = arrayColonnes[0].innerHTML;
	
	
	// Fermeture des infobulles
	for(var i=0; i < infoMultipleBalise.length; i++)
	{
		map.closePopup();
	}
	
	// Ouverture de l'infobulle à la selection de la ligne
	if( (tabIdLigne < infoMultipleBalise.length) && (tabIdLigne < markerMultipleBalise.length) )
	{
		markerMultipleBalise[tabIdLigne].openPopup();

// console.log(infoMultipleBalise[tabIdLigne].content);

	}
	
	// Zoom et centrage sur la position selectionnee
	if(tabIdLigne < latlngMultipleBalise.length)
	{
		map.panTo([latlngMultipleBalise[tabIdLigne].lat, latlngMultipleBalise[tabIdLigne].lng]);
		map2.setView(latlngMultipleBalise[tabIdLigne], 15);
	}
}

/**********************************************************************************/
/********************************afficheInfobullTable*******************************/
/**********************************************************************************/
function afficheInfobullTable(id) {
    this.id = id;
    $('tr').children('td').removeClass('active');
    $(this.id).children('td').addClass('active');

    var tabTracker;
    var tabIcone;
    var tabNomBalise;
    var tabDTP;
    var tabAdresse;
    var tabVitesse;
    var tabStat;
    var tabLatitude;
    var tabLongitude;
    var tabDirection;
    var arrayColonnes = this.id.cells;
	
    /*modif 23/03/17 == -> ===*/
    if (arrayColonnes.length === 12) {
        tabTracker = arrayColonnes[0].innerHTML;
        tabIcone = arrayColonnes[1].innerHTML;
        tabNomBalise = arrayColonnes[2].innerHTML;
        tabDTP = arrayColonnes[3].innerHTML;
        tabAdresse = arrayColonnes[4].innerHTML;
        tabVitesse = arrayColonnes[5].innerHTML;
        tabStat = arrayColonnes[6].innerHTML;
		tabLatitude = arrayColonnes[9].innerHTML;
        tabLongitude = arrayColonnes[10].innerHTML;
        tabDirection = arrayColonnes[11].innerHTML;
    } else if (arrayColonnes.length === 13) {
        tabTracker = arrayColonnes[0].innerHTML;
        tabIcone = arrayColonnes[1].innerHTML;
        tabNomBalise = arrayColonnes[2].innerHTML;
        tabDTP = arrayColonnes[3].innerHTML;
        tabAdresse = arrayColonnes[5].innerHTML;
        tabVitesse = arrayColonnes[6].innerHTML;
        tabStat = arrayColonnes[7].innerHTML;
        tabLatitude = arrayColonnes[10].innerHTML;
        tabLongitude = arrayColonnes[11].innerHTML;
        tabDirection = arrayColonnes[12].innerHTML;
    } else if (arrayColonnes.length === 11) {
        tabTracker = arrayColonnes[0].innerHTML;
        tabIcone = arrayColonnes[1].innerHTML;
        tabNomBalise = arrayColonnes[2].innerHTML;
        tabDTP = arrayColonnes[3].innerHTML;
        tabAdresse = arrayColonnes[4].innerHTML;
        tabVitesse = arrayColonnes[5].innerHTML;
        tabStat = arrayColonnes[6].innerHTML;
        tabLatitude = arrayColonnes[9].innerHTML;
        tabLongitude = arrayColonnes[10].innerHTML;
        tabDirection = arrayColonnes[11].innerHTML;		// S'il y a 11 colonnes on prend la 12 eme ? Sérieusement ???
    }
	
	// Suppression de la sous-chaine (non brouille)
	var textnonbrouille = "(" + getTextNonBrouille + ")";		// traduction manquante
	if( tabStat.search(textnonbrouille) >= 0 )
	{
		var tabStat0 = tabStat.substring(0, tabStat.indexOf(textnonbrouille));
		var tabStat1 = tabStat.substring(tabStat.indexOf(textnonbrouille) + textnonbrouille.length + 1);
		tabStat = tabStat0 + tabStat1;
	}
	
    var markerhandle = afficheMarker(tabTracker, tabIcone, tabDTP, tabStat, tabLatitude, tabLongitude, tabDirection, tabVitesse, tabAdresse, tabNomBalise);
	
    if ((!tabAdresse) || (tabAdresse == "undefined") || (tabAdresse == "" ) || (tabAdresse == null))
	{
		geocoding(0, 0, tabLatitude, tabLongitude, id, markerhandle);
	}
}

/**********************************************************************************/
/********************************afficheMarker*************************************/
/**********************************************************************************/
function afficheMarker(pos, icone, dtp, stat, lat, lng, direction, vitesse, adresse, nameBalise) {
	
    var realIcone = icone.substring(icone.indexOf('<img src=\"') + 10, icone.indexOf('\">'));
    var afficheVitesse;
    var imageMarker;
	
    clearOverlaysPanorama();
	
	// Contenu infobulle
    if (realIcone.substr(24, 6) == "stop16" || realIcone.substr(24, 6) == "noGPS_") {
		afficheVitesse = "";
    } else {
        afficheVitesse = "<b>" + vitesse + "</b> km/h - ";
    }
	
    if (pos != "") {
        pos = " - POS:" + pos;
    }
	
	var html = "<center><table><tr><td colspan='2'><img src='"+realIcone+"'> * <b>" + nameBalise + "</b>" + pos +
			"</td></tr><tr><td>* " + dtp + " - " + substractDateTime(dtp) +
			"</td></tr><tr><td>* " + afficheVitesse + stat + "</td></tr>";

    if (adresse) {
        html += "<tr><td>" + adresse + "</td></tr></table></center>";
    } else {
        html += "</table></center>";
    }
	
	
	// Marker 
    var iconCar = new Image();
    iconCar.src = realIcone
    imageMarker = new L.icon({iconUrl:iconCar.src, iconAnchor: [9, 21], popupAnchor: [0, -14]});

    //Marker Carto Position
    latlng = new L.LatLng(lat, lng);

	marker = new L.marker([latlng.lat, latlng.lng],
	{
		icon:imageMarker,
		title: nameBalise+":\n"+dtp
	})
	.bindPopup(html)
	.addTo(map)
	.openPopup();

    MarkersArray.push(marker);
	
    //Marker VueRapproche
    latlongPanorama = latlng;

	marker2 =  new L.marker([latlongPanorama.lat, latlongPanorama.lng],
	{
		//icon:iconPanorama,
		icon:imageMarker,
		title: nameBalise+":\n"+dtp
	})
	.bindPopup(html)
	.addTo(map2);
	
    MarkersArrayPanorama.push(marker2);

    //Centrer les maps
    map.panTo([latlng.lat, latlng.lng]);
    map2.panTo([latlng.lat, latlng.lng]);

    // if (document.getElementById("rememberAddMarker").innerHTML == "yes") {
	
	return marker;
}



/**********************************************************************************/
/*                              HISTORIQUE PERIODE                                */
/*                                                                                */
/**********************************************************************************/
/*************************** addPeriodeTablePosition*******************************/
/**********************************************************************************/
function boutonPeriodePosition() {
    document.getElementById("tableposition_modes").style.display = "none";
    modeTablePosition = "normal";

    if ($(window).width() < 768) {

        document.getElementById("divHistorique").style.display = "none";
        if (topmenu == "1") {
            $(".navbar-collapse").collapse('hide');
            topmenu = "";
        }
        if (sidemenu == "1") {
            $("#wrapper").toggleClass("toggled");
            document.getElementById("ouvrir").style.right = "-82px";
            document.getElementById("ouvrir").innerHTML = getTextOuvrir;
            sidemenu = "";
        }
    }
    document.getElementById("rememberDivHistorique").innerHTML = "Periode";

	clearPolygone();
	ClearMarkerAdresse();
	ClearPOImarkers();
    clearOverlays();
    clearOverlaysPanorama();
    offSuivi();
    resetTraitTrajet();
    resetDrawLegend();

    LatLngArray = [];

    MarkersArray = [];
    latlngMultipleBalise = [];
    infoMultipleBalise = [];
    markerMultipleBalise = [];
    document.getElementById("tableposition_li_choixbalises").innerHTML = "";
    document.getElementById("tableposition_choixbalises").style.display = "none";
    var Id_Tracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;
    document.getElementById("tableposition_modes").style.display = "none";
    modeTablePosition = "normal";
    if (Id_Tracker.search(/,/) != -1) {

		document.getElementById("body_idTablePosition").innerHTML = "";
		document.getElementById("head_idTablePosition").innerHTML = '<tr><th width="40px" style="display:yes">N°</th><th width="35px"></th><th width="166px">' + getTextNomBalise + '</th><th width="120px">Date position</th>' +		// traduction manquante : Date position
				'<th width="400px">' + getTextAdresse + '</th><th width="50px">' + getTextVitesse + '</th><th width="350px">' + getTextStatut + '</th>' +
				'<th width="45px">GSM</th><th width="45px">' + getTextAlim + '</th>' +
				'<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>';
		idlignetabpos=0;
				
        var regIdTracker = new RegExp("[,]+", "g");
        var tableauIdTracker = Id_Tracker.split(regIdTracker);
        var regNomBalise = new RegExp("[,]+", "g");
        var tableauNomBalise = nomBalise.split(regNomBalise);
        for (var i = 0; i < tableauIdTracker.length; i++) {
            addPeriodeMarker(tableauIdTracker[i], tableauNomBalise[i], Id_Tracker, i);
        }

        //executeLastTablePosition();
    } else {
        addPeriodeMarker(Id_Tracker, nomBalise, Id_Tracker, 0);
        addPeriodeTablePosition();
        addPeriodePagination();
    }
}

/**********************************************************************************/
/*******************************addPeriodeMarker***********************************/
/**********************************************************************************/
function addPeriodeMarker(Id_Tracker, nomBalise, multipleTracker, numeroBalise) {

    document.body.className = "loading";
    //if(numeroBalise > 0 && flightPath.length <= 0) numeroBalise = numeroBalise -1 ;

    document.getElementById("rememberAddMarker").innerHTML = "";
    document.getElementById("rememberAddPeriode").innerHTML = "yes";
    document.getElementById("rememberAddPosition").innerHTML = "";

    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    if (!Id_Tracker) {
        var Id_Tracker = document.getElementById("idBalise").innerHTML;
        var nomBalise = document.getElementById('nomBalise').innerHTML;
    }


    var debutperiode = document.getElementById("debutperiode").value;
    var finperiode = document.getElementById("finperiode").value;

    var tz = jstz.determine();
    var timezone = tz.name();

    if (multipleTracker.search(/,/) != -1)
        asynchro = false;
    else
        asynchro = true;


    
    if (Id_Tracker == "") {
        // alert('Veuillez d\'abord choisir une balise');
        return;
        //}else if(Id_Tracker.search(/,/) != -1){
        // alert("Choisir qu'une balise");
        //return
    } else {
        if (document.getElementById('id_historique_poi').checked) {
            showMarkerPoiTracker(Id_Tracker);
        }
		if (document.getElementById("id_avec_geofencing").checked == true)
			showAllZone();
		
		$.ajax({
			url: '../carto/cartoperiodeaddmarker.php',
			type: 'GET',
			data: "debut=" + debutperiode + "&fin=" + finperiode + "&Id_Tracker=" + Id_Tracker + "&nomDatabaseGpw=" + nomDatabaseGpw +
					"&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone,
			async: asynchro,
			success: function (response) {
				if (response) {
					//alert(response);
					var reg = new RegExp("[&]+", "g");
					var tableau = response.split(reg);

					var latlngtab = [];
					var flightPlanCoordinates = [];
					var html = [];
					var iconesDirectionVitesse = [];
							
					var coordLat, coordLong, coordDateTimePosition, coordPosStatut, coordPosAdresse;
					var DecodedStatus, IconDirVitesse, coordPosDirection;
					var coordPosOdometre, coordPosVitesse;
					
					var zoomOk = "0";

					var iconCar = new Image();
					
					var lastLng = 0, lastLat = 0, detectStop = 0;
					var infoGPS, lastInfoGPS = 0;
					var affichepos;
					
					var indicepos = 0;
					
					var filtreStop = document.getElementById('id_historique_filtrage').checked;
					var distFiltrage = document.getElementById("id_historique_distance_filtree").value;
					
					var Pos_Key;
					var Statut2;
					var BattInt;
					var BattExt;
					var Alim;
					var TypeServer;
					

					if (tableau.length > 1) {		// Toujours une case en trop dans le tableau, faudrait regarder pourquoi
					
						var tableaulength = tableau.length - 1;
					
						for (var i = 0; i < tableaulength; i++) {

							coordLat = tableau[i].substring(tableau[i].indexOf('P_Lat:') + 6, tableau[i].indexOf('P_DTime:'));
							coordLong = tableau[i].substring(tableau[i].indexOf('P_Lon:') + 6);
							coordDateTimePosition = tableau[i].substring(tableau[i].indexOf('P_DTime:') + 8, tableau[i].indexOf('P_Stat:'));
							coordPosStatut = tableau[i].substring(tableau[i].indexOf('P_Stat:') + 7, tableau[i].indexOf('P_Vit:'));
							coordPosVitesse = Math.round(parseInt(tableau[i].substring(tableau[i].indexOf('P_Vit:') + 6, tableau[i].indexOf('P_Dir:'))));
							coordPosDirection = tableau[i].substring(tableau[i].indexOf('P_Dir:') + 6, tableau[i].indexOf('P_Odo:'));
							coordPosOdometre = tableau[i].substring(tableau[i].indexOf('P_Odo:') + 6, tableau[i].indexOf('P_Adr:'));
							coordPosAdresse = tableau[i].substring(tableau[i].indexOf('P_Adr:') + 6, tableau[i].indexOf('P_Key:'));
							Pos_Key = tableau[i].substring(tableau[i].indexOf('P_Key:') + 6, tableau[i].indexOf('Stat2:'));
							Statut2 = tableau[i].substring(tableau[i].indexOf('Stat2:') + 6, tableau[i].indexOf('BtInt:'));
							BattInt = tableau[i].substring(tableau[i].indexOf('BtInt:') + 6, tableau[i].indexOf('BtExt:'));
							BattExt = tableau[i].substring(tableau[i].indexOf('BtExt:') + 6, tableau[i].indexOf('Alim:'));
							Alim =  tableau[i].substring(tableau[i].indexOf('Alim:') + 5, tableau[i].indexOf('TypSrv:'));
							TypeServer = tableau[i].substring(tableau[i].indexOf('TypSrv:') + 7, tableau[i].indexOf('P_Lon:'));

							
							
							DecodedStatus = DecodeStatus(coordPosStatut, coordPosOdometre, coordPosVitesse, Pos_Key, Statut2, BattInt, BattExt, Alim, TypeServer, 2);
							IconDirVitesse = IconeBalise(coordPosStatut, coordPosVitesse, coordPosDirection);
							
							if (coordLat != "" && coordLong != "" && coordLat != 0 && coordLong != 0) {
								
								if(coordPosStatut & 0x00000020)
									infoGPS = 1;		// GPS Valide
								else
									infoGPS = 0;
								
								// Filtrage positions
								affichepos = 0;
								if(coordPosStatut & 0x00000004)		// En TRAJET
								{
									if( (distFiltrage > 0) && !detectStop && infoGPS && lastInfoGPS)
									{
										if(getDistanceFromLatLonInKm(lastLat, lastLng, coordLat, coordLong) > distFiltrage)
										{
											affichepos=1;
										}
									}
									else
									{
										detectStop=0;
										affichepos=1;
									}
								}
								else									// En STOP
								{
									if(filtreStop && detectStop)
									{
										if(getDistanceFromLatLonInKm(lastLat, lastLng, coordLat, coordLong) > 1000)
										{
											affichepos=1;
										}
									}
									else
									{
										detectStop=1;
										affichepos=1;
									}
								}
								
								if(i == tableaulength-1)		// forçage de l'affichage de la dernière pos de la période
									affichepos=1;
								
								// Affichage position
								if(affichepos)
								{
									// Pour fonctionnement du filtrage
									lastLat = coordLat;
									lastLng = coordLong;
									lastInfoGPS = infoGPS;
									
									// Contenu infobulle
									html[indicepos] = "<center><table><tr><td><img src='"+IconDirVitesse+"'> * <b>" + nomBalise + "</b> - POS:" + indicepos +
											"</td></tr><tr><td>* " + coordDateTimePosition + " - " + substractDateTime(coordDateTimePosition) +
											"</td></tr><tr><td>* " + DecodedStatus + "</td></tr>";
											
									if (coordPosAdresse) {
										html[indicepos] += "<tr><td>" + coordPosAdresse + "</td></tr></table></center>";
									} else {
										html[indicepos] += "</table></center>";
									}
										

									
									// Icone
									iconesDirectionVitesse[indicepos] = new L.icon({iconUrl: IconDirVitesse, iconAnchor: [9, 21], popupAnchor: [0, -14]}); 
									
									// Creation marker
									latlngtab[indicepos] = new L.LatLng(coordLat, coordLong);
									
									marker = new L.marker([latlngtab[indicepos].lat, latlngtab[indicepos].lng],
									{
										icon: iconesDirectionVitesse[indicepos],
										title: nomBalise+":\n"+coordDateTimePosition
									})
									.bindPopup(html[indicepos]);
									
									MarkersArray.push(marker);
									
									// Trait trajet
									flightPlanCoordinates.push(latlngtab[indicepos]);
									LatLngArray.push(latlngtab[indicepos]);

									zoomOk = "1";

									rapprochLatLng = latlngtab[indicepos];
									rapprochIcon = iconesDirectionVitesse[indicepos];
									rapprochInfoWindow = html[indicepos];
									rapprochDirection = coordPosDirection;

									indicepos++;
								}
							}
						}
						
						if(indicepos)
							indicepos--;
						
						// Affichage markers si Icone est coché
						if (document.getElementById('id_historique_icon').checked) {
							if (MarkersArray) {
								for (i in MarkersArray) {
									map.addLayer(MarkersArray[i]);
								}
							}
						}
						
						// Modification de l'icone du dernier marker pour mettre l'icone personnalisée
						iconCar.src = "../../assets/img/BibliothequeIcone/" + getIcone(Id_Tracker);
						var imageMarker = new L.icon({iconUrl:iconCar.src, iconSize: [40, 40] ,iconAnchor: [10, 38], popupAnchor: [0, -14]});
						
						MarkersArray[indicepos].setIcon(imageMarker);
						MarkersArray[indicepos].addTo(map);			// Affichage du marker de la dernière pos même si Icone est décoché
						//if ($(window).width() >= 768) {
						if (document.getElementById('id_historique_infobulle').checked) {
							MarkersArray[indicepos].openPopup();
						}
						//}
						
						if (multipleTracker.search(/,/) != -1) {

							flightColors[markerMultipleBalise.length] = get10colors(numeroBalise);
							drawLegend(flightColors[markerMultipleBalise.length], nomBalise, markerMultipleBalise.length);
							flightPath[markerMultipleBalise.length] = new L.Polyline(flightPlanCoordinates,
							{
								color:flightColors[markerMultipleBalise.length]
							});

							if (document.getElementById('id_historique_trait_trajet').checked) {
								document.getElementById('legend_traitrajet').style.display = "";
								flightPath[markerMultipleBalise.length].addTo(map);
								//clearOverlays();

							} else {
								document.getElementById('legend_traitrajet').style.display = "none";
								map.removeLayer(flightPath[markerMultipleBalise.length]);
							}

							latlngMultipleBalise.push(rapprochLatLng);
							//infoMultipleBalise.push(infowindow);
							infoMultipleBalise.push(html[indicepos]);
							
							markerMultipleBalise[markerMultipleBalise.length] = new L.marker([latlngMultipleBalise[markerMultipleBalise.length].lat,latlngMultipleBalise[markerMultipleBalise.length].lng],
							{
								icon:imageMarker
							})
							.bindPopup(infoMultipleBalise[markerMultipleBalise.length])
							.addTo(map);
							
							//}
							//if(suivi == "") {
							//SetZoom();
							//}
							//var infowindowLastPeriode = infoMultipleBalise[markerMultipleBalise.length - 1];
							//var markerLastPeriode = markerMultipleBalise[markerMultipleBalise.length - 1];
							
							// (function () {
								// google.maps.event.addListener(markerLastPeriode, 'click', function () {
									// infowindowLastPeriode.open(map, markerLastPeriode);
								// });
							// })();

							MarkersArray.push(markerMultipleBalise[markerMultipleBalise.length - 1]);
							
							//if ($(window).width() >= 768) {
							/*if (document.getElementById('id_historique_infobulle').checked) {
								//infoMultipleBalise[markerMultipleBalise.length - 1].open(map, markerMultipleBalise[markerMultipleBalise.length - 1]);
							}
							*/
							
							// 
							document.getElementById("tableposition_choixbalises").style.display = "";
							document.getElementById("tableposition_li_choixbalises").innerHTML += "<li>" +
									"<a  href=\"javascript:addPeriodeTablePosition('0','" + Id_Tracker + "','" + nomBalise + "')\">" + nomBalise + "</a></li>";

							// document.getElementById("head_idTablePosition").innerHTML = '<tr><th width="50px">N</th><th width="45px"></th><th width="150px">' + getTextNomBalise + '</th><th width="150px">Date position</th>' +
									// '<th width="400px">' + getTextAdresse + '</th><th width="50px">' + getTextVitesse + '</th><th width="350px">' + getTextStatut + '</th>' +
									// '<th width="45px">GSM</th><th width="45px">' + getTextAlim + '</th>' +
									// '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>';
							document.getElementById("head_idTablePosition").innerHTML = '<tr><th width="45px"></th><th width="150px">' + getTextNomBalise + '</th><th width="150px">Date position</th>' +
									'<th width="400px">' + getTextAdresse + '</th><th width="50px">' + getTextVitesse + '</th><th width="350px">' + getTextStatut + '</th>' +
									'<th width="45px">GSM</th><th width="45px">' + getTextAlim + '</th>' +
									'<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>';

							addLastTablePosition(Id_Tracker, nomBalise);

						} else {									// Mono balise
							//document.body.className = "loading";
							
							//SetZoom();
							
							// Trait trajet
							flightColors[0] = get10colors(0);
							drawLegend(flightColors[0], nomBalise, 0);
							flightPath[0] = new L.Polyline(flightPlanCoordinates,
							{
								color: flightColors[0]
							});

							if (document.getElementById('id_historique_trait_trajet').checked) {
								document.getElementById('legend_traitrajet').style.display = "";
								flightPath[0].addTo(map);
								//clearOverlays();
							} else {
								document.getElementById('legend_traitrajet').style.display = "none";
								map.removeLayer(flightPath[0]);
							}

						}
						
						//iconPanorama = rapprochIcon;
						latlongPanorama = rapprochLatLng;
						/*panoramaOptions = {
							position: rapprochLatLng,
							pov: {
								heading: parseFloat(rapprochDirection.replace(",", ".")),
								pitch: 10
							}
						}*/
						marker2 = new L.marker([rapprochLatLng.lat, rapprochLatLng.lng],
						{
							icon: rapprochIcon,
							title: nomBalise
						})
						.bindPopup(rapprochInfoWindow)
						.addTo(map2);
						
						map2.setView(rapprochLatLng, 15);
						MarkersArrayPanorama.push(marker2);
						//if (document.getElementById("rememberStreet").innerHTML == "yes") {
						//	streetMap();
						//}

						if (zoomOk == "1") {
							SetZoom();
						}
						if ($(window).width() < 768)
							document.body.className = "";

					} else {
						//if (multipleTracker.search(/,/) != -1) {
						//} else {

						alert($('<div />').html(getTextPasDePositions + ': ' + nomBalise).text());
						//alert(getTextPasDePositions+ ': ' + nomBalise);
						//alert('Pas de positions pour balise ' + nomBalise + ' sur l\'intervalle de temps [' + debutperiode + '; ' + finperiode + ']');
						/*document.getElementById("TablePosition").innerHTML = '<table id="idTablePosition" class="sortable table table-bordered table-hover">' +
								'<thead id="head_idTablePosition">' +
								'<tr><th width="50px">N</th><th width="45px"></th><th width="150px">' + getTextNomBalise + '</th><th width="150px">Date position</th>' +
								'<th width="400px">' + getTextAdresse + '</th><th width="50px">' + getTextVitesse + '</th><th width="350px">' + getTextStatut + '</th>' +
								'<th width="45px">GSM</th><th width="45px">' + getTextAlim + '</th>' +
								'<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>' +
								'<tbody id="body_idTablePosition"></tbody></table>';
						*/
						
						document.getElementById("body_idTablePosition").innerHTML = "";
						
						document.getElementById("tableposition_modes").style.display = "none";
						modeTablePosition = "normal";
						document.getElementById("rappel_date").innerHTML = ".................";
						document.body.className = "";
						//}
					}
					
					if (multipleTracker.search(/,/) != -1)
						document.body.className = "";
					//if(document.body.className == "loading") document.body.className = "";
					//if(i == 10000) alert("La capacit顭aximale d'affichage de positions est de 10000 \n" +
					//"La derni鳥 position affichꥠest : "+ coordDateTimePosition[coordDateTimePosition.length-1]);
				}
			}
		});
    }
}

function get10colors(id) {
    var colors = ["#FF0000", "#0000FF", "#00FF00", "#000000", "#4B0082", "#FFFF00", "#A9A9A9", "#FF4500", "#1E90FF", "#483D8B",
        "#FF0000", "#0000FF", "#00FF00", "#000000", "#4B0082", "#FFFF00", "#A9A9A9", "#FF4500", "#1E90FF", "#483D8B",
        "#FF0000", "#0000FF", "#00FF00", "#000000", "#4B0082", "#FFFF00", "#A9A9A9", "#FF4500", "#1E90FF", "#483D8B"];

	while(id > 29){
		id = id-30;
    }

	return colors[id];
}

function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
    var R = 6371; // Radius of the earth in km
    var dLat = deg2rad(lat2 - lat1);  // deg2rad below
    var dLon = deg2rad(lon2 - lon1);
    var a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2)
            ;
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c; // Distance in km
    return d * 1000;
}

function addPeriodeTablePosition(numPage, idTracker, nomBaliseChoix) {
    //offSuivi();

    var Id_Tracker = document.getElementById("idBalise").innerHTML;
    //var filtrage = document.getElementById("rememberFiltrageArret").innerHTML;
    var filtrage;
    if (document.getElementById('id_historique_filtrage').checked) {
        filtrage = "yes";
    } else if ((document.getElementById('id_historique_filtrage').checked) == false) {
        filtrage = "no";
    }
    var nomBalise = document.getElementById('nomBalise').innerHTML;
    var distanceFiltree = document.getElementById("id_historique_distance_filtree").value;

    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;
    var tz = jstz.determine();
    var timezone = tz.name();
    var debutperiode = document.getElementById("debutperiode").value;
    var finperiode = document.getElementById("finperiode").value;
    var fd = new Date(debutperiode); // from date
    var td = new Date(finperiode); // to date

    if (idTracker)
        Id_Tracker = idTracker;
    if (nomBaliseChoix)
        nomBalise = nomBaliseChoix;


    if (Id_Tracker == "") {
        alert(getTextVeuillezChoisirUneBalise);
        document.body.className = "";
        return;
        //}else if(Id_Tracker.search(/,/) != -1) {
        //	alert(getTextVeuillezChoisirQueUneBalise);
        //	return;
    } else if (fd.getTime() > td.getTime()) {
        alert(getTextFinSuperieurDebut);
        document.body.className = "";
        return;
    } else {
		document.getElementById("body_idTablePosition").innerHTML = "";
		document.getElementById("head_idTablePosition").innerHTML = '<tr><th width="40px" style="display:yes">N°</th><th width="35px"></th><th width="166px">' + getTextNomBalise + '</th><th width="120px">Date position</th>' +		// traduction manquante : Date position
				'<th width="400px">' + getTextAdresse + '</th><th width="50px">' + getTextVitesse + '</th><th width="350px">' + getTextStatut + '</th>' +
				'<th width="45px">GSM</th><th width="45px">' + getTextAlim + '</th>' +
				'<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>';
        
        document.getElementById("tableposition_modes").style.display = "none";
        modeTablePosition = "normal";

        document.body.className = "loading";
        $.ajax({
            url: '../carto/cartoperiodetablepos.php',
            type: 'GET',
            data: "debut=" + debutperiode + "&fin=" + finperiode + "&Id_Tracker=" + Id_Tracker + "&nomBalise=" + nomBalise + "&filtrage=" + filtrage + "&nomDatabaseGpw=" + nomDatabaseGpw +
                    "&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone + "&distanceFiltree=" + distanceFiltree + "&numPage=" + numPage,
            success: function (response) {
                if (response) {
                    //var reg = new RegExp("[&]+", "g");
                    //var tableau = response.split(reg);
                    //var nbreLigne = tableau[0].substring(tableau[0].indexOf('#') + 1, tableau[0].indexOf('%'));

                    //if(nbreLigne) {

                    if (document.body.className == "")
                        document.body.className = "loading";
                    if (response.length > 173)
                        document.getElementById("TablePosition").innerHTML = response;
                    //if (numPage)document.body.className = "";

                    var container = document.getElementById('idTablePosition');
                    var items = container.getElementsByTagName('td');
                    if (items.length > 0)
						document.getElementById("rappel_date").innerHTML = "(" + items[0].innerHTML + ") <b>" + items[3].innerHTML + "</b> &nbsp; - &nbsp; <b>" + items[items.length - 10].innerHTML + "</b> (" + items[items.length - 13].innerHTML + ")";
                    else {
                        document.getElementById("tablePagination").style.display = "";
                        document.getElementById("page-selection").style.display = "none";
                    }
                    //}

                    $(".sortable th").click(sortableTablePosition);
                    document.body.className = "";
                } else {
                    document.body.className = "";

                }
            }
        });

    }
}

function addPeriodePagination() {
    var Id_Tracker = document.getElementById("idBalise").innerHTML;
    //var filtrage = document.getElementById("rememberFiltrageArret").innerHTML;
    var filtrage;
    if (document.getElementById('id_historique_filtrage').checked) {
        filtrage = "yes";
    } else if ((document.getElementById('id_historique_filtrage').checked) == false) {
        filtrage = "no";
    }
    var nomBalise = document.getElementById('nomBalise').innerHTML;
    var distanceFiltree = document.getElementById("id_historique_distance_filtree").value;

    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;
    var tz = jstz.determine();
    var timezone = tz.name();
    var debutperiode = document.getElementById("debutperiode").value;
    var finperiode = document.getElementById("finperiode").value;



    if (Id_Tracker == "") {
        return;
    } else if (Id_Tracker.search(/,/) != -1) {
        return
    } else {
        $.ajax({
            url: '../carto/cartoperiodepagination.php',
            type: 'GET',
            data: "debut=" + debutperiode + "&fin=" + finperiode + "&Id_Tracker=" + Id_Tracker + "&nomBalise=" + nomBalise + "&filtrage=" + filtrage + "&nomDatabaseGpw=" + nomDatabaseGpw +
                    "&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone + "&distanceFiltree=" + distanceFiltree,
            success: function (response) {
                if (response) {

                    var nombreDePages = response.substring(response.indexOf('NombreDePages') + 14);
                    if (nombreDePages > "1") {
                        document.getElementById("page-selection").style.display = "";
                        $('#page-selection').bootpag({
                            total: nombreDePages,
                            page: 1,
                            maxVisible: 5,
                            leaps: true,
                            firstLastUse: true,
                            first: '←',
                            last: '→',
                            wrapClass: 'pagination',
                            activeClass: 'active',
                            disabledClass: 'disabled',
                            nextClass: 'next',
                            prevClass: 'prev',
                            lastClass: 'last',
                            firstClass: 'first',
                            margin: "0px"
                        }).on("page", function (event, num) {
                            addPeriodeTablePosition(num);
                        });
                        document.getElementById("tablePagination").style.display = "none";
                    } else {
                        document.getElementById("tablePagination").style.display = "";
                        document.getElementById("page-selection").style.display = "none";
                    }

                } else {
                    document.getElementById("tablePagination").style.display = "";
                    document.getElementById("page-selection").style.display = "none";
                }
            }
        });

    }
}

/**********************************************************************************/
/*                           Nb Position Historique                               */
/*                                                                                */
/**********************************************************************************/
/************************** addPositionTablePosition*******************************/
/**********************************************************************************/
function addPositionTablePosition(numPage) {
    document.getElementById("tableposition_modes").style.display = "none";
    modeTablePosition = "normal";
    if ($(window).width() < 768) {
        document.getElementById("divHistorique").style.display = "none";
        if (topmenu == "1") {
            $(".navbar-collapse").collapse('hide');
            topmenu = "";
        }
        if (sidemenu == "1") {
            $("#wrapper").toggleClass("toggled");
            document.getElementById("ouvrir").style.right = "-82px";
            document.getElementById("ouvrir").innerHTML = getTextOuvrir;
            sidemenu = "";
        }
    }
    offSuivi();
    var Id_Tracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;


    var filtrage;
    if (document.getElementById('id_historique_filtrage').checked) {
        filtrage = "yes";
    } else if ((document.getElementById('id_historique_filtrage').checked) == false) {
        filtrage = "no";
    }
    var distanceFiltree = document.getElementById("id_historique_distance_filtree").value;

    var n = document.getElementById("n").value;
    var datetime = document.getElementById("datetime").value;

    var e = document.getElementById("selectposition");
    var selectValue = e.options[e.selectedIndex].value;

    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    var tz = jstz.determine();
    var timezone = tz.name();

    var selectRetranscrit;
    var ordre;

    if (selectValue == "avant") {
        selectRetranscrit = "<=";
        ordre = "DESC";
    } else if (selectValue == "apres") {
        selectRetranscrit = ">=";
        ordre = "ASC";
    }
	
    if (Id_Tracker == "") {
        alert(getTextVeuillezChoisirUneBalise);
        return;
    } else if (Id_Tracker.search(/,/) != -1) {
        alert(getTextVeuillezChoisirQueUneBalise);
        return
    }
	
    if ((n <= 0) || (n > 9999)) {
        alert(getTextVeuillezNombreEntre);
        return;
    } else {
        document.body.className = "loading";
		
        $.ajax({
            url: '../carto/cartopositiontablepos.php',
            type: 'GET',
            data: "pos=" + datetime + "&Id_Tracker=" + Id_Tracker + "&n=" + n + "&select=" + selectRetranscrit + "&ordre=" + ordre + "&nomBalise=" + nomBalise +
                    "&filtrage=" + filtrage + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone + "&distanceFiltree=" + distanceFiltree + "&numPage=" + numPage,
            success: function (response) {
                if (response) {
                    //alert(response);
                    if (document.body.className == "")
                        document.body.className = "loading";
                    document.getElementById("TablePosition").innerHTML = response;
                    //if(numPage)document.body.className = "";

                    var container = document.getElementById('idTablePosition');
                    var items = container.getElementsByTagName('td');
                    if (n != "1") {
                        document.getElementById("rappel_date").innerHTML = "(" + items[0].innerHTML + ") <b>" + items[3].innerHTML + "</b> &nbsp; - &nbsp; <b>" + items[items.length - 9].innerHTML + "</b> (" + items[items.length - 12].innerHTML + ")";
                        //document.getElementById("rappel_date").innerHTML = "<b>"+items[3].innerHTML + "</b> &nbsp; à &nbsp; <b>" +items[items.length-9].innerHTML + "</b>";
                        //if (selectValue == "avant") document.getElementById("rappel_date").innerHTML = "<b>" + items[items.length - 4].innerHTML + "</b> &nbsp; à &nbsp; <b>" + items[8].innerHTML + "</b>";
                        //if (selectValue == "apres") document.getElementById("rappel_date").innerHTML = "<b>" + items[8].innerHTML + "</b> &nbsp; à &nbsp; <b>" + items[items.length - 4].innerHTML + "</b>";

                    } else {
                        document.getElementById("rappel_date").innerHTML = ".................";
                    }
                    $(".sortable th").click(sortableTablePosition);
                    document.body.className = "";
                }
            }
        });
        //document.body.className = "";
    }
}

function addPositionPagination() {

    var Id_Tracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;

    //var filtrage = document.getElementById("rememberFiltrageArret").innerHTML;
    var filtrage;
    if (document.getElementById('id_historique_filtrage').checked) {
        filtrage = "yes";
    } else if ((document.getElementById('id_historique_filtrage').checked) == false) {
        filtrage = "no";
    }
    var distanceFiltree = document.getElementById("id_historique_distance_filtree").value;

    var n = document.getElementById("n").value;
    var datetime = document.getElementById("datetime").value;

    var e = document.getElementById("selectposition");
    var selectValue = e.options[e.selectedIndex].value;

    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    var tz = jstz.determine();
    var timezone = tz.name();

    var selectRetranscrit;
    var ordre;

    if (selectValue == "avant") {
        selectRetranscrit = "<=";
        ordre = "DESC";
    } else if (selectValue == "apres") {
        selectRetranscrit = ">=";
        ordre = "ASC";
    }
	
    if (Id_Tracker == "") {
        return;
    } else if (Id_Tracker.search(/,/) != -1) {
        return
    }
	
    if ((n <= 0) || (n > 9999)) {
        return;
    } else {
        $.ajax({
            url: '../carto/cartopositionpagination.php',
            type: 'GET',
            data: "pos=" + datetime + "&Id_Tracker=" + Id_Tracker + "&n=" + n + "&select=" + selectRetranscrit + "&ordre=" + ordre + "&nomBalise=" + nomBalise +
                    "&filtrage=" + filtrage + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone + "&distanceFiltree=" + distanceFiltree,
            success: function (response) {
                if (response) {
                    var nombreDePages = response.substring(response.indexOf('NombreDePages') + 14);
                    //alert(n);
                    //alert(nombreDePages);
                    if (nombreDePages > "1") {
                        document.getElementById("page-selection").style.display = "";
                        $('#page-selection').bootpag({
                            total: nombreDePages,
                            page: 1,
                            maxVisible: 5,
                            leaps: true,
                            firstLastUse: true,
                            first: '←',
                            last: '→',
                            wrapClass: 'pagination',
                            activeClass: 'active',
                            disabledClass: 'disabled',
                            nextClass: 'next',
                            prevClass: 'prev',
                            lastClass: 'last',
                            firstClass: 'first',
                            margin: "0px"
                        }).on("page", function (event, num) {
                            addPositionTablePosition(num);
                        });
                        document.getElementById("tablePagination").style.display = "none";
                    } else {
                        document.getElementById("tablePagination").style.display = "";
                        document.getElementById("page-selection").style.display = "none";
                    }
                }
            }
        });

    }
}


//function unlock(){
//	locked = false;
//}

/*
function loadingAddress() {

    if (confirm("Cela peut prendre du temps selon le nombre de positions.\nVoulez-vous continuer cette action (Charger les adresses) ?")) {
        var idTablePosition = document.getElementById("idTablePosition");
        if (!idTablePosition.rows[1])
            return;
        for (var i = 1, row; row = idTablePosition.rows[i]; i++) {
            var latitude = idTablePosition.rows[i].cells[9].innerHTML;
            var longitude = idTablePosition.rows[i].cells[10].innerHTML;
            var address = idTablePosition.rows[i].cells[3].innerHTML;
            if ((!address) || (address == "undefined") || (address == "") || (address == null)) {
				
				//$.getJSON('https://geocoder.tilehosting.com/r/'+ longitude +'/'+ latitude +'.js?key=EUON3NGganG4JD5zzQlN', function(data) {
				//	idTablePosition.rows[i].cells[3].innerHTML = data.results[0].display_name;
				
				$.getJSON('https://api.opencagedata.com/geocode/v1/json?q='+ latitude +'+'+ longitude +'&language=en&pretty=1&key=83f0f8644cd747bf94fb2018390f572c', function(data) {
					idTablePosition.rows[i].cells[3].innerHTML = data.results[0].formatted;
					
					//address = data.results[0].display_name;
					//$.ajax({
					//	url: '../carto/cartoinsertadresse.php',
					//	type: 'GET',
					//	data: "address=" + address +
					//		  "&lat=" + latitude + "&lng=" + longitude +
					//		  "&nomDatabaseGpw=" + globalnomDatabaseGpw + "&ipDatabaseGpw=" + globalIpDatabaseGpw
					//});
				});
            }
        }
    }
}
*/

function deg2rad(deg) {
    return deg * (Math.PI / 180)
}

/**********************************************************************************/
/* addPositionMarker : historique nombre de position avant/après date et heure    */
/**********************************************************************************/
function addPositionMarker() {
    resetTraitTrajet();
    resetDrawLegend();
    document.getElementById("rememberDivHistorique").innerHTML = "Historique";
    document.getElementById("tableposition_li_choixbalises").innerHTML = "";
    document.getElementById("tableposition_choixbalises").style.display = "none";
    //if(flightPath) flightPath.setMap(null);

    document.getElementById("rememberAddMarker").innerHTML = "";
    document.getElementById("rememberAddPeriode").innerHTML = "";
    document.getElementById("rememberAddPosition").innerHTML = "yes";

    var Id_Tracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;

    var n = document.getElementById("n").value;
    var datetime = document.getElementById("datetime").value;

    var e = document.getElementById("selectposition");
    var selectValue = e.options[e.selectedIndex].value;

    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    var tz = jstz.determine();
    var timezone = tz.name();

    var selectRetranscrit;
    var ordre;

    // infowindow = new google.maps.InfoWindow;

    if (selectValue == "avant") {
        selectRetranscrit = "<=";
        ordre = "DESC";
    } else if (selectValue == "apres") {
        selectRetranscrit = ">=";
        ordre = "ASC";
    }
	
    if (Id_Tracker == "") {
        // alert('Veuillez d\'abord choisir une balise');
        return;
    }
	
    if ((n <= 0) || (n > 9999)) {
        //alert('Veuillez saisir un nombre de positions entre 1 et 9999');
        return;
    } else if (Id_Tracker.search(/,/) != -1) {
        // alert("Choisir qu'une balise");
        return
    } else {
		clearPolygone();
		ClearMarkerAdresse();
		ClearPOImarkers();
        clearOverlaysPanorama();
        clearOverlays();
        LatLngArray = [];
		
        if (document.getElementById('id_historique_poi').checked) {
            showMarkerPoiTracker(Id_Tracker);
        }
		if (document.getElementById("id_avec_geofencing").checked == true)
			showAllZone();

        $.ajax({
            url: '../carto/cartopositionaddmarker.php',
            type: 'GET',
            data: "pos=" + datetime + "&Id_Tracker=" + Id_Tracker + "&n=" + n + "&select=" + selectRetranscrit + "&ordre=" + ordre + "&nomDatabaseGpw=" + nomDatabaseGpw +
                    "&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone,
            success: function (response) {
                if (response) {
                    //alert(response);
                    var reg = new RegExp("[&]+", "g");
                    var tableau = response.split(reg);

                    var latlngtab = [], nomVersionBalise = [];
                    var statut, iconesDirectionVitesse = [], contact = [], brouilleur = [], niveauGSM = [], vibration = [],
                            alimEtBatterie = [], niveauBat = [], GPS = [], infoGPS = [], vitesse = [], alarm1 = [], alarm2 = [], volt = [];

                    var coordLat = [], coordLong = [], coordDateTimePosition = [], coordPosStatut = [], coordPosVitesse = [],
                            coordPosDirection = [], coordPosOdometre = [], coordPosAdresse = [];
					var html = [];
                    var flightPlanCoordinates = [];
                    var zoomOk = "0";

                    var detectCoordStop, detectCoordStopLng, detectCoordStopLat, detectStop;
                    var nbreLigne = tableau[0].substring(tableau[0].indexOf('t') + 1, tableau[0].indexOf('g'));
                    //alert(response);

                    //alert(tableau.length);
                    if (nbreLigne) {
                        for (var i = 0; i < tableau.length - 1; i++) {

                            coordLat[i] = tableau[i].substring(tableau[i].indexOf('Pos_Latitude') + 13, tableau[i].indexOf('Pos_DateTime_position'));
                            coordLong[i] = tableau[i].substring(tableau[i].indexOf('Pos_Longitude') + 14);
                            coordDateTimePosition[i] = tableau[i].substring(tableau[i].indexOf('Pos_DateTime_position') + 22, tableau[i].indexOf('Pos_Statut'));
                            coordPosStatut[i] = tableau[i].substring(tableau[i].indexOf('Pos_Statut') + 11, tableau[i].indexOf('Pos_Vitesse'));
                            coordPosVitesse[i] = Math.round(parseInt(tableau[i].substring(tableau[i].indexOf('Pos_Vitesse') + 12, tableau[i].indexOf('Pos_Direction'))));
                            coordPosDirection[i] = tableau[i].substring(tableau[i].indexOf('Pos_Direction') + 14, tableau[i].indexOf('Pos_Odometre'));
                            coordPosOdometre[i] = tableau[i].substring(tableau[i].indexOf('Pos_Odometre') + 13, tableau[i].indexOf('Pos_Adresse'));
                            coordPosAdresse[i] = tableau[i].substring(tableau[i].indexOf('Pos_Adresse') + 12, tableau[i].indexOf('Pos_Longitude'));
                            //alert(coordDateTimePosition[i] );
                            //alert(coordLat[i] );
                            latlngtab[i] = new L.LatLng(coordLat[i], coordLong[i]);

                            statut = lireStatut(coordPosStatut[i]);

                            //flightPlanCoordinates[i] = new google.maps.LatLng(coordLat[i], coordLong[i]);

                            nomVersionBalise[i] = versionBalise(coordPosOdometre[i]);
                            if ((nomVersionBalise[i].substr(0, 5) == "SC200") || (nomVersionBalise[i].substr(0, 5) == "SC300"))
                                brouilleur[i] = "";
                            else if (statut[3] == "1")
                                brouilleur[i] = "(" + getTextBrouille + ")";
                            else
                                brouilleur[i] = "";


                            if (statut[31] == "1"){
                                //alarm1[i] = " - " + getTextAlarm + " 1 active";
								if(nomVersionBalise[i].substr(0,3) == "600")
									alarm1[i]	= " - " +getTextAlarm+ " Arrachement active";
								else
									alarm1[i]	= " - " +getTextAlarm+ " 1 active";
                            }else
                                alarm1[i] = "";

                            if (statut[30] == "1")
                                alarm2[i] = " - " + getTextAlarm + " 2 active";
                            else
                                alarm2[i] = "";

                            if (statut[29] == "1") {
                                iconesDirectionVitesse[i] = lireDirectionVitesse(coordPosDirection[i], coordPosVitesse[i]);
                                contact[i] = "";
                                vitesse[i] = "<b>" + coordPosVitesse[i] + "</b> km/h - ";
                            } else {
                                iconesDirectionVitesse[i] = stop16.src;
                                contact[i] = "STOP - ";
                                vitesse[i] = "";
                            }

                            if (statut[26] == "1") {
							
                                //GPS[i] = " - Nb_Sat " + coordPosOdometre[i][4] + " - Pdop " + (coordPosOdometre[i][5] + coordPosOdometre[i][6]/10) + " m"; //coordPosOdometre[i][4] + "/" + coordPosOdometre[i][5] + "." + coordPosOdometre[i][6];
								//GPS[i] = coordPosOdometre[i][4] + "/" + coordPosOdometre[i][5] + "." + coordPosOdometre[i][6];
                                GPS[i] = "<b>" + coordPosOdometre[i][4] + "/" + coordPosOdometre[i][5] + "." + coordPosOdometre[i][6] + "</b>";
                                infoGPS[i] = "1";
                            } else {
                                GPS[i] = "No";
                                if (iconesDirectionVitesse[i] == stop16.src)
                                    iconesDirectionVitesse[i] = noGPS_Stop.src;
                                else {
                                    iconesDirectionVitesse[i] = noGPS.src;
                                }

                            }

                            if (statut[25] == "1")
                                vibration[i] = "VIB";
                            else
                                vibration[i] = getTextPas + " VIB";



                            niveauBat[i] = lireBatterie(statut[1], statut[2], statut[9], statut[8]);
							volt[i] = (parseInt(niveauBat[i],10) * 0.253) + 5.5;
							volt[i] = volt[i]*10;
							volt[i] = Math.round(volt[i]);
							volt[i] = volt[i]/10;

					
							if (statut[14] == "1")
								alimEtBatterie[i] = getTextAlimExt + " <b>" + volt[i] + "V</b>";
                            else if ((statut[14] == "0") && (statut[13] == "1"))
                                alimEtBatterie[i] = "BatExt <b>" + niveauBat[i] + "%</b>";
                            else if ((statut[14] == "0") && (statut[13] == "0") && (statut[12] == "1"))
                                alimEtBatterie[i] = "BatInt <b>" + niveauBat[i] + "%</b>";
                            else
                                alimEtBatterie[i] = getTextAlimBasse + " <b>" + niveauBat[i] + "%</b>";

                            niveauGSM[i] = lireReseau(statut[7], statut[6]);

							// Contenu infobulle
							html[i] = "<center><table><tr><td><img src='"+iconesDirectionVitesse[i]+"'> * <b>" + nomBalise + "</b> - POS:" + i +
									"</td></tr><tr><td>* " + coordDateTimePosition[i] + " - " + substractDateTime(coordDateTimePosition[i]) +
									"</td></tr><tr><td>* " + vitesse[i] + "<b>" + contact[i] + "" + vibration[i] + "</b> -  GSM <b>" + brouilleur[i] + " " + niveauGSM[i] + "/3</b> - GPS " + GPS[i] + " - " + alimEtBatterie[i] + "" + alarm1[i] + "" + alarm2[i] + "</td></tr>" +
									"<tr><td>" + coordPosAdresse[i] + "</td></tr></table></center>";							
                        }

                        for (var i = 0; i < tableau.length - 1; i++) {
                            if (infoGPS[i] == "1") {

                                if (document.getElementById('id_historique_filtrage').checked) {
                                    if (iconesDirectionVitesse[i] == stop16.src) {
                                        //if ((iconesDirectionVitesse[i] == stop16.src && iconesDirectionVitesse[i + 1] != stop16.src) && (iconesDirectionVitesse[i] == stop16.src && iconesDirectionVitesse[i + 1] != noGPS_Stop.src)) {
                                        if (((/*iconesDirectionVitesse[i - 1] != noGPS_Stop.src &&*/ iconesDirectionVitesse[i - 1] != stop16.src) && iconesDirectionVitesse[i] == stop16.src)) {
											iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
											marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
											{
												title: nomBalise+":\n"+coordDateTimePosition[i],
												icon:iconesDirectionVitesse[i]
											})
											.bindPopup(html[i]);
											map.addLayer(marker);
                                            detectStop = latlngtab[i];
                                            detectCoordStop = latlngtab[i];
                                            detectCoordStopLng = coordLong[i];
                                            detectCoordStopLat = coordLat[i];
                                        }

                                    } else {
                                        if (iconesDirectionVitesse[i] == lireDirectionVitesse(coordPosDirection[i], coordPosVitesse[i])) {
                                            if (detectStop) {
                                                if ((getDistanceFromLatLonInKm(detectCoordStopLat, detectCoordStopLng, coordLat[i], coordLong[i])) >= document.getElementById("id_historique_distance_filtree").value) {
													iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
													marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
													{
														title: nomBalise+":\n"+coordDateTimePosition[i],
														icon:iconesDirectionVitesse[i]
													})
													.bindPopup(html[i]);
													map.addLayer(marker);
                                                    detectStop = "";
                                                }
                                            } else {
												iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
												marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
												{
													title: nomBalise+":\n"+coordDateTimePosition[i],
													icon:iconesDirectionVitesse[i]
												})
												.bindPopup(html[i]);
												map.addLayer(marker);
                                            }
                                        } else {
											iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
											marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
											{
												title: nomBalise+":\n"+coordDateTimePosition[i],
												icon:iconesDirectionVitesse[i]
											})
											.bindPopup(html[i]);
											map.addLayer(marker);
                                        }
                                    }
                                } else if ((document.getElementById('id_historique_filtrage').checked) == false) {
                                    if (!document.getElementById("id_historique_distance_filtree").value || document.getElementById("id_historique_distance_filtree").value > "0") {
                                        if ((iconesDirectionVitesse[i] == stop16.src && iconesDirectionVitesse[i + 1] != stop16.src) && (iconesDirectionVitesse[i] == stop16.src && iconesDirectionVitesse[i + 1] != noGPS_Stop.src)) {
											iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
											marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
											{
												title: nomBalise+":\n"+coordDateTimePosition[i],
												icon:iconesDirectionVitesse[i]
											})
											.bindPopup(html[i]);
											map.addLayer(marker);
                                            detectStop = latlngtab[i];
                                            detectCoordStop = latlngtab[i];
                                            detectCoordStopLng = coordLong[i];
                                            detectCoordStopLat = coordLat[i];
                                        }
                                        if (iconesDirectionVitesse[i] == lireDirectionVitesse(coordPosDirection[i], coordPosVitesse[i])) {
                                            if (detectStop) {
												iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
												marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
												{
													title: nomBalise+":\n"+coordDateTimePosition[i],
													icon:iconesDirectionVitesse[i]
												})
												.bindPopup(html[i]);
												map.addLayer(marker);
                                                detectStop = "";
                                            } else {
                                                if ((getDistanceFromLatLonInKm(detectCoordStopLat, detectCoordStopLng, coordLat[i], coordLong[i])) >= document.getElementById("id_historique_distance_filtree").value) {
													iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
													marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
													{
														title: nomBalise+":\n"+coordDateTimePosition[i],
														icon:iconesDirectionVitesse[i]
													})
													.bindPopup(html[i]);
													map.addLayer(marker);
                                                }
                                            }
                                        }
                                    } else {
										iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
										marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
										{
											title: nomBalise+":\n"+coordDateTimePosition[i],
											icon:iconesDirectionVitesse[i]
										})
										.bindPopup(html[i]);
										map.addLayer(marker);
                                    }

                                }
                            } else {
                                if (document.getElementById('id_historique_filtrage').checked) {
                                    if (iconesDirectionVitesse[i] == noGPS_Stop.src) {
                                        //if ((iconesDirectionVitesse[i] == noGPS_Stop.src && iconesDirectionVitesse[i + 1] != stop16.src) && (iconesDirectionVitesse[i] == noGPS_Stop.src && iconesDirectionVitesse[i + 1] != noGPS_Stop.src)) {
                                        if (((iconesDirectionVitesse[i - 1] != noGPS_Stop.src /*&& iconesDirectionVitesse[i - 1] != stop16.src */) && iconesDirectionVitesse[i] == noGPS_Stop.src)) {
											iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
											marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
											{
												title: nomBalise+":\n"+coordDateTimePosition[i],
												icon:iconesDirectionVitesse[i]
											})
											.bindPopup(html[i]);
											map.addLayer(marker);
                                            detectCoordStop = latlngtab[i];
                                            detectStop = latlngtab[i];
                                            detectCoordStopLng = coordLong[i];
                                            detectCoordStopLat = coordLat[i];
                                        }

                                    } else {
                                        if (iconesDirectionVitesse[i] == lireDirectionVitesse(coordPosDirection[i], coordPosVitesse[i])) {
                                            if (detectStop) {
                                                if ((getDistanceFromLatLonInKm(detectCoordStopLat, detectCoordStopLng, coordLat[i], coordLong[i])) >= document.getElementById("id_historique_distance_filtree").value) {
													iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
													marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
													{
														title: nomBalise+":\n"+coordDateTimePosition[i],
														icon:iconesDirectionVitesse[i]
													})
													.bindPopup(html[i]);
													map.addLayer(marker);
                                                    detectStop = "";
                                                }
                                            } else {
												iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
												marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
												{
													title: nomBalise+":\n"+coordDateTimePosition[i],
													icon:iconesDirectionVitesse[i]
												})
												.bindPopup(html[i]);
												map.addLayer(marker);
                                            }
                                        } else {
											iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
											marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
											{
												title: nomBalise+":\n"+coordDateTimePosition[i],
												icon:iconesDirectionVitesse[i]
											})
											.bindPopup(html[i]);
											map.addLayer(marker);
                                        }
                                    }
                                } else if ((document.getElementById('id_historique_filtrage').checked) == false) {
                                    if (!document.getElementById("id_historique_distance_filtree").value || document.getElementById("id_historique_distance_filtree").value > "0") {
                                        if ((iconesDirectionVitesse[i] == stop16.src && iconesDirectionVitesse[i + 1] != stop16.src) && (iconesDirectionVitesse[i] == stop16.src && iconesDirectionVitesse[i + 1] != noGPS_Stop.src)) {
											iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
											marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
											{
												title: nomBalise+":\n"+coordDateTimePosition[i],
												icon:iconesDirectionVitesse[i]
											})
											.bindPopup(html[i]);
											map.addLayer(marker);
                                            detectCoordStopLng = coordLong[i];
                                            detectCoordStopLat = coordLat[i];
                                            detectStop = latlngtab[i];
                                            detectCoordStop = latlngtab[i];
                                        }
                                        if (iconesDirectionVitesse[i] == lireDirectionVitesse(coordPosDirection[i], coordPosVitesse[i])) {
                                            if (detectStop) {
												iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
												marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
												{
													title: nomBalise+":\n"+coordDateTimePosition[i],
													icon:iconesDirectionVitesse[i]
												})
												.bindPopup(html[i]);
												map.addLayer(marker);
                                                detectStop = "";
                                            } else {
                                                if ((getDistanceFromLatLonInKm(detectCoordStopLat, detectCoordStopLng, coordLat[i], coordLong[i])) >= document.getElementById("id_historique_distance_filtree").value) {
													iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
													marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
													{
														title: nomBalise+":\n"+coordDateTimePosition[i],
														icon:iconesDirectionVitesse[i]
													})
													.bindPopup(html[i]);
													map.addLayer(marker);
                                                }
                                            }
                                        }
                                    } else {
										iconesDirectionVitesse[i] = new L.icon({iconUrl:iconesDirectionVitesse[i], iconAnchor: [9, 21], popupAnchor: [0, -14]});
										marker = new L.Marker(new L.LatLng(latlngtab[i].lat, latlngtab[i].lng),
										{
											title: nomBalise+":\n"+coordDateTimePosition[i],
											icon:iconesDirectionVitesse[i]
										})
										.bindPopup(html[i]);
										map.addLayer(marker);
                                    }
                                }


                            }

                            if (coordLat[i] != "" && coordLong[i] != "" && coordLat[i] != 0 && coordLong[i] != 0) {
								//console.log(marker.getLatLng().lat);
                                flightPlanCoordinates[i] = new L.LatLng(marker.getLatLng().lat, marker.getLatLng().lng);
                                LatLngArray.push(new L.LatLng(marker.getLatLng().lat, marker.getLatLng().lng));
                                MarkersArray.push(marker);

                                zoomOk = "1";

                                rapprochLatLng = new L.LatLng(marker.getLatLng().lat, marker.getLatLng().lng);
                                rapprochIcon = iconesDirectionVitesse[i];
                                rapprochInfoWindow = html[i];
                                rapprochDirection = coordPosDirection[i];

                            } else {
                                //alert($('<div />').html( getTextPasDePositions+ ': ' + nomBalise).text());
                                //alert(getTextPasDePositions+ ': ' + nomBalise);
                                //alert("Il n\'y a pas plus de " + i + " positions pour la balise " + nomBalise);

                                // return;
                            }
                            // google.maps.event.addListener(marker, 'click', (function (marker, i) {
                                // return function () {

                                    // infowindow.setContent("<table><tr><td>* <b>" + nomBalise + "</b> - POS:" + i + "</td></tr><tr><td>* " + coordDateTimePosition[i] + " - " + substractDateTime(coordDateTimePosition[i]) +
                                            // "</td></tr><tr><td>* " + contact[i] + " " + vibration[i] + " -  GSM " + brouilleur[i] + " " + niveauGSM[i] +
                                            // "/3 - GPS " + coordPosOdometre[i][4] + "/" + coordPosOdometre[i][5] + "." + coordPosOdometre[i][6] + " - " + alimEtBatterie[i] + "" + alarm1[i] + "" + alarm2[i] + "" + vitesse[i] + "</td></tr></table>");
                                    // infowindow.open(map, marker);
                                // }
                            // })(marker, i));


                            //flightPlanCoordinates[i] = latlngtab[i];

                        }
                        flightColors[0] = get10colors2(Id_Tracker);
                        drawLegend(flightColors[0], nomBalise, 0);
						flightPath[0] = new L.Polyline(flightPlanCoordinates, {color:flightColors[0]});

                        if (document.getElementById('id_historique_trait_trajet').checked) {
                            document.getElementById('legend_traitrajet').style.display = "";
                            flightPath[0].addTo(map);
                            //clearOverlays();

                        } else {
                            document.getElementById('legend_traitrajet').style.display = "none";
                            map.removeLayer(flightPath[0]);

                        }

                        iconPanorama = rapprochIcon;
                        latlongPanorama = rapprochLatLng;
                        // panoramaOptions = {
                            // position: rapprochLatLng,
                            // pov: {
                                // heading: parseFloat(rapprochDirection.replace(",", ".")),
                                // pitch: 10
                            // }
                        // }
						marker2 = new L.marker([rapprochLatLng.lat, rapprochLatLng.lng],
						{
							icon:rapprochIcon,
							title: nomBalise
						})
						.bindPopup(rapprochInfoWindow);
						map2.addLayer(marker2);
                        map2.setView(new L.LatLng(rapprochLatLng.lat, rapprochLatLng.lng),15);
                        MarkersArrayPanorama.push(marker2);
                        //if (document.getElementById("rememberStreet").innerHTML == "yes") {
                        //    streetMap();
                        //}

                        //if (zoomOk == "1") {
                        SetZoom();
                        //} else {
                        //	document.getElementById("rappel_date").innerHTML = ".................";
                        //	document.getElementById("TablePosition").innerHTML = '<table id="idTablePosition" class="sortable table table-bordered table-hover">' +
                        //			'<tr><th width="50px">N</th><th width="45px"></th><th width="150px">'+getTextNomBalise+'</th><th width="150px">Date position</th>' +
                        //			'<th width="300px">'+getTextAdresse+'</th><th width="50px">'+getTextVitesse+'</th><th width="350px">'+getTextStatut+'</th>' +
                        //			'<th width="45px">GSM</th><th width="45px">'+getTextAlim+'</th>'+
                        //			'<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr></table>';
                        //	//alert(getTextPasDePositions+ ': ' + nomBalise);
                        //	alert($('<div />').html( getTextPasDePositions+ ': ' + nomBalise).text());
                        //}


                        if (parseInt(n) != parseInt((tableau.length - 1)))
                            alert($('<div />').html((tableau.length - 1) + "/" + n + " positions").text());
                        //document.body.className = "";
                    } else {
                        //alert(getTextPasDePositions+ ': ' + nomBalise);
                        alert($('<div />').html(getTextPasDePositions + ': ' + nomBalise).text());
                        document.getElementById("rappel_date").innerHTML = ".................";
                        document.getElementById("TablePosition").innerHTML = '<table id="idTablePosition" class="sortable table table-bordered table-hover">' +
                                '<thead id="head_idTablePosition">' +
                                '<tr><th width="50px">N</th><th width="45px"></th><th width="150px">' + getTextNomBalise + '</th><th width="150px">Date position</th>' +
                                '<th width="400px">' + getTextAdresse + '</th><th width="50px">' + getTextVitesse + '</th><th width="350px">' + getTextStatut + '</th>' +
                                '<th width="45px">GSM</th><th width="45px">' + getTextAlim + '</th>' +
                                '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>' +
                                '<tbody id="body_idTablePosition"></tbody></table>';
                        document.getElementById("tableposition_modes").style.display = "none";
                        modeTablePosition = "normal";
                        document.body.className = "";
                    }
                    //document.body.className = "";
                    if (document.body.className == "loading")
                        document.body.className = "";
                }
            }
        });

    }
}

/**********************************************************************************/
/********************************versionBalise*************************************/
/**********************************************************************************/
function versionBalise(odometre) {
    var version1 = odometre.substr(0, 4);
    var nomVersion;
	
    switch (version1) {
		case "3006":
			nomVersion = "GEOCUBE V2";		// Geofence CUBE V2
			break;
		case "3370":
			nomVersion = "GEONEO 3G";		// Geofence NEO 3G
            break;
		case "8079":
		case "8045":
			nomVersion = "GEONEO 4G";		// Geofence NEO 3G
            break;
		case "2205":
			nomVersion = "GEONANO";			// Geofence CJ 
            break;
		case "7003":
			nomVersion = "GEONEO";			// Geofence NEO
            break;
		case "7201":
			nomVersion = "GEOSOLO";			// Geofence SOLO
            break;
		case "8000":
			nomVersion = "GEOSOLAIRE";		// Geofence SOLAR
            break;
		case "2201":
			nomVersion = "GEONANO2";		// Geofence SOLAR
			break;
		case "2801":
			nomVersion = "GEONEO-S";		// Geofence SOLAR
			break;
		case "2601":
			nomVersion = "GEOCOW";		// Geofence SOLAR
			break;
		/*case "2001":
			nomVersion = "TWIG ONE";		// Geofence SOLAR
			break;*/
        default:
			var version = odometre.substr(0, 2);
			var firmware = odometre[2] + "." + odometre[3];
			switch (version) {
				default:
					nomVersion = getTextInconnue;
					break;
				case "11":
					nomVersion = "SC200G v" + firmware;
					break;
				case "17":
					nomVersion = "SCx00J v" + firmware;
					break;
				case "18":
					nomVersion = "SC500J v" + firmware;
					break;
				case "19":
					nomVersion = "SC500JS v" + firmware;
					break;
				case "20":
					nomVersion = "GEOTRACK";				// Geofence
					break;
				case "23":
					nomVersion = "SC200M v" + firmware;
					break;
				case "24":
					nomVersion = "SC300x v" + firmware;
					break;
				case "31":
					nomVersion = "SC300M v" + firmware;
					break;
				case "32":
					nomVersion = "SC300MB v" + firmware;
					break;
				case "33":
					nomVersion = "SC300ME v" + firmware;
					break;
				case "41":
					nomVersion = "SC300G v" + firmware;
					break;
				case "42":
					nomVersion = "SC300GB v" + firmware;
					break;
				case "52":
					nomVersion = "SC300E v" + firmware;
					break;
				case "43":
					nomVersion = "SC400MB v" + firmware;
					break;
				//case "44":
				//    nomVersion = "SC300P v" + firmware;
				//    break;
				case "45":
					nomVersion = "SC400M/E v" + firmware;
					break;
				case "46":
					nomVersion = "SC400n v" + firmware;		// SC400µ + SC400n + SC500n
					break;
				case "47":
					nomVersion = "GEOFLEET v" + firmware;	// Geofence
					break;
				case "48":
					nomVersion = "GEOTRACK v" + firmware;	// Geofence
					break;
				case "49":
					nomVersion = "SC400PM v" + firmware;
					break;
				case "50":
					nomVersion = "SC500MB v" + firmware;
					break;
				case "51":
					nomVersion = "SC500MB v" + firmware;
					break;
				//case "52":
				//	nomVersion = "SC400BLE v"+firmware;
				//	break;
				case "53":
					nomVersion = "GEOCUBE v" + firmware;	// Geofence
					break;
				case "54":
					nomVersion = "SC500H v" + firmware;
					break;
				case "55":
					nomVersion = "600St v" + firmware;
					break;
				case "56":
					nomVersion = "SC HYBRID+ v" + firmware;
					break;
				case "57":
					nomVersion = "600Av v" + firmware;
					break;
			}
	}
    return nomVersion;

}
/************************************************************************************/
/*********************************substractDateTime**********************************/
/*																					*/
/* 			Calcule l'age de la position affiché dans l'info bulle					*/
/*																					*/
/************************************************************************************/
function substractDateTime(choixDate) {

    var currentdate = new Date();
    var currentMonth = currentdate.getMonth() + 1;
    var datetimeCurrent = currentdate.getFullYear() + "-" + ((currentMonth < 10) ? "0" : "") + currentMonth + "-" + ((currentdate.getDate() < 10) ? "0" : "") + currentdate.getDate() + " "
            + ((currentdate.getHours() < 10) ? "0" : "") + currentdate.getHours() + ":" + ((currentdate.getMinutes() < 10) ? "0" : "") + currentdate.getMinutes() + ":" + ((currentdate.getSeconds() < 10) ? "0" : "") + currentdate.getSeconds();

    var diffMs = (new Date(datetimeCurrent.replace(/-/g, '/')) - new Date(choixDate.replace(/-/g, '/')));
    var diff = Math.abs(new Date(datetimeCurrent.replace(/-/g, '/')) - new Date(choixDate.replace(/-/g, '/')));
	
	diff = diff / 1000;				// conversion millisecondes -> secondes
    var resultSubstract;
	resultSubstract = "(" + getTextDepuis + " ";

    if (Math.floor(diff / 2678400) != 0) {				// Temps >= à 1 mois ?
        resultSubstract += Math.floor(diff / 2678400) + "mois ";
		diff = diff % 2678400;
        resultSubstract += Math.floor(diff / 86400) + getTextJ + " ";
		diff = diff % 86400;
        resultSubstract += Math.floor(diff / 3600) + "h";
		diff = diff % 3600;
		resultSubstract += Math.floor(diff / 60) + "mn" + (diff % 60) + "s)";
    } else if (Math.floor(diff / 86400) != 0) {			// Temps >= à 1 jour ?
        resultSubstract += Math.floor(diff / 86400) + getTextJ + " ";
		diff = diff % 86400;
        resultSubstract += Math.floor(diff / 3600) + "h";
		diff = diff % 3600;
		resultSubstract += Math.floor(diff / 60) + "mn" + (diff % 60) + "s)";
    } else if (Math.floor(diff / 3600) != 0) {			// Temps >= à 1 heure ?
        resultSubstract += Math.floor(diff / 3600) + "h";
		diff = diff % 3600;
		resultSubstract += Math.floor(diff / 60) + "mn" + (diff % 60) + "s)";
    } else if (Math.floor(diff / 60) != 0) {			// Temps >= à 1 minute ?
        resultSubstract += Math.floor(diff / 60) + "mn" + (diff % 60) + "s)";
    } else {											// Alors c'est des secondes.
        resultSubstract += (diff) + "s)";
    }

    return resultSubstract;
}

/**********************************************************************************/
/***********************************lireStatut*************************************/
/**********************************************************************************/
function lireStatut(statut) {
    var statutRecup = statut >>> 0;
    var puissance = 31;
    var statutEncode = new Array();
	
    while (puissance >= 0) {

        if (Math.pow(2, puissance) > statutRecup) {
            statutEncode.push("0");
        } else if (Math.pow(2, puissance) <= statutRecup) {
            statutRecup = statutRecup - Math.pow(2, puissance);
            statutEncode.push("1");
        }

        puissance--;
    }
	
    return statutEncode;
}

/**********************************************************************************/
/**********************************lireBatterie************************************/
/**********************************************************************************/
function lireBatterie(b0, b1, b2, b3) {
    var niveauBatterie;
    var pourcentageBatterie;
    niveauBatterie = (8 * parseInt(b3)) + (4 * parseInt(b2)) + (2 * parseInt(b1)) + parseInt(b0);
    pourcentageBatterie = (100 * niveauBatterie) / 15;

    return Math.round(pourcentageBatterie);
}

/**********************************************************************************/
/***********************************lireReseau*************************************/
/**********************************************************************************/
function lireReseau(lvl1, lvl2) {
    var niveauReseau;
    niveauReseau = (lvl1 * 1) + (lvl2 * 2);

    return niveauReseau;
}

/**********************************************************************************/
/******************************lireDirectionVitesse********************************/
/**********************************************************************************/
function lireDirectionVitesse(direction, vitesse) {
    if (vitesse == 0) {
        if ((direction >= 0) && (direction <= 22.5)) {
            positionFleche.src = fleche0DegRouge.src;
        }
        if ((direction >= 337.5) && (direction <= 360)) {
            positionFleche.src = fleche0DegRouge.src;
        }
        if ((direction > 22.5) && (direction < 67.5)) {
            positionFleche.src = fleche45DegRouge.src;
        }
        if ((direction >= 67.5) && (direction <= 112.5)) {
            positionFleche.src = fleche90DegRouge.src;
        }
        if ((direction > 112.5) && (direction < 157.5)) {
            positionFleche.src = fleche135DegRouge.src;
        }
        if ((direction >= 157.5) && (direction <= 202.5)) {
            positionFleche.src = fleche180DegRouge.src;
        }
        if ((direction > 202.5) && (direction < 247.5)) {
            positionFleche.src = fleche225DegRouge.src;
        }
        if ((direction >= 247.5) && (direction <= 292.5)) {
            positionFleche.src = fleche270DegRouge.src;
        }
        if ((direction > 292.5) && (direction < 337.5)) {
            positionFleche.src = fleche315DegRouge.src;
        }
    } else if (vitesse <= 10) {
        if ((direction >= 0) && (direction <= 22.5)) {
            positionFleche.src = fleche0DegJaune.src;
        }
        if ((direction >= 337.5) && (direction <= 360)) {
            positionFleche.src = fleche0DegJaune.src;
        }
        if ((direction > 22.5) && (direction < 67.5)) {
            positionFleche.src = fleche45DegJaune.src;
        }
        if ((direction >= 67.5) && (direction <= 112.5)) {
            positionFleche.src = fleche90DegJaune.src;
        }
        if ((direction > 112.5) && (direction < 157.5)) {
            positionFleche.src = fleche135DegJaune.src;
        }
        if ((direction >= 157.5) && (direction <= 202.5)) {
            positionFleche.src = fleche180DegJaune.src;
        }
        if ((direction > 202.5) && (direction < 247.5)) {
            positionFleche.src = fleche225DegJaune.src;
        }
        if ((direction >= 247.5) && (direction <= 292.5)) {
            positionFleche.src = fleche270DegJaune.src;
        }
        if ((direction > 292.5) && (direction < 337.5)) {
            positionFleche.src = fleche315DegJaune.src;
        }
    } else if (vitesse > 10) {
        if ((direction >= 0) && (direction <= 22.5)) {
            positionFleche.src = fleche0Deg.src;
        }
        if ((direction >= 337.5) && (direction <= 360)) {
            positionFleche.src = fleche0Deg.src;
        }
        if ((direction > 22.5) && (direction < 67.5)) {
            positionFleche.src = fleche45Deg.src;
        }
        if ((direction >= 67.5) && (direction <= 112.5)) {
            positionFleche.src = fleche90Deg.src;
        }
        if ((direction > 112.5) && (direction < 157.5)) {
            positionFleche.src = fleche135Deg.src;
        }
        if ((direction >= 157.5) && (direction <= 202.5)) {
            positionFleche.src = fleche180Deg.src;
        }
        if ((direction > 202.5) && (direction < 247.5)) {
            positionFleche.src = fleche225Deg.src;
        }
        if ((direction >= 247.5) && (direction <= 292.5)) {
            positionFleche.src = fleche270Deg.src;
        }
        if ((direction > 292.5) && (direction < 337.5)) {
            positionFleche.src = fleche315Deg.src;
        }
    }
    return positionFleche.src;
}

var tailleTablePosCarto = "mini";
function agrandirTablePos() {
    var divCartoPos = document.getElementById('divCartoPos');
    var divVueRapproche = document.getElementById('divVueRapproche');
    var divHistorique = document.getElementById('divHistorique');
    var divAgrandir = document.getElementById("divAgrandir");
    var divTable = document.getElementById("divTable");
    var TablePosition = document.getElementById("TablePosition");
    var currentClass = divAgrandir.className;
    if (currentClass == "col-lg-12") { // Check the current class name
        divAgrandir.className = "col-lg-3";   // Set other class name
        divCartoPos.style.display = "block";
        divVueRapproche.style.display = "block";
        divHistorique.style.display = "block";
        TablePosition.style.height = "214px";
        document.getElementById("boutonZoom").innerHTML = '<i class="glyphicon glyphicon-zoom-in"></i>';
        tailleTablePosCarto = "mini";
    } else {
        divAgrandir.className = "col-lg-12";  // Otherwise, use `second_name`
        divCartoPos.style.display = "none";
        divVueRapproche.style.display = "none";
        divHistorique.style.display = "none";

        TablePosition.style.height = $(window).height() - 225 + "px";
        document.getElementById("boutonZoom").innerHTML = '<i class="glyphicon glyphicon-zoom-out"></i>';
        tailleTablePosCarto = "maxi";
    }

}


function streetAndMap() {

    var defaut = document.getElementById('streetview');
    var autre = document.getElementById('map_canvas2');
    defaut.style.display = (defaut.style.display == 'none' ? '' : 'none');

    autre.style.display = (autre.style.display == 'none' ? '' : 'none');
    google.maps.event.trigger(autre, 'resize');
}



function centerMap() {
	map = L.map( 'map_canvas', {
		center: [47.081012, 2.398782],
		zoom: 6
	});

	L.tileLayer( 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
		subdomains: ['a', 'b', 'c']
	}).addTo( map );
			
}
function height750(_divId_) {
    if (document.getElementById(_divId_) != null) {
        document.getElementById(_divId_).style.height = "750px";
        initCartoGoogleMap();

    }
}
function height500(_divId_) {
    if (document.getElementById(_divId_) != null) {
        document.getElementById(_divId_).style.height = "500px";
        initCartoGoogleMap();

    }
}
function height250(_divId_) {
    if (document.getElementById(_divId_) != null) {
        document.getElementById(_divId_).style.height = "250px";
        initCartoGoogleMap();

    }
}
function piheight750(_divId_) {
    if (document.getElementById(_divId_) != null) {
        document.getElementById(_divId_).style.height = "750px";
        initCartoPtInteret();

    }
}
// function autoResizemap(){
// if($(window).width()<= 750){
// height500('map_canvas');
// }else if($(window).width()<= 400){
// height250('map_canvas');
// }
// }
// addEvent(window , "load", autoResizemap);

// $(window).ready(function(){
// var wi = $(window).width();
// $("p.testp").test('Initial screen width is currently: ' + wi + 'px.');

// $(window).resize(function() {
// var wi = $(window).width();

// if(wi <= 480){
// $("p.testp").text('Screen width is less than or equal to 480px. Width is currently: ' +wi + 'px.');

function test() {
    alert($(window).width());
    alert($(document).width());
    alert($(screen).width());
}

function piheight500(_divId_) {
    if (document.getElementById(_divId_) != null) {
        document.getElementById(_divId_).style.height = "500px";
        initCartoPtInteret();
    }

}
function piheight250(_divId_) {
    if (document.getElementById(_divId_) != null) {
        document.getElementById(_divId_).style.height = "250px";
        initCartoPtInteret();
    }

}

function changeMapType(mapTypeId) {
    if (statusStreet == 0) {
        markerStreet.infowindow.close();
        statusStreet = 1;
    }
    if (document.getElementById("rememberStreet").innerHTML == "yes") {
        panorama.setVisible(false);
        map2.setMapTypeId(google.maps.MapTypeId[mapTypeId]);
        document.getElementById("rememberStreet").innerHTML = "";
        document.getElementById("LiStreetView").className = "";
    }
    map2.setMapTypeId(google.maps.MapTypeId[mapTypeId]);
}

function streetMap() {
	return;
    /*clearOverlaysPanorama();
    document.getElementById("rememberStreet").innerHTML = "yes";

    //panorama = new google.maps.StreetViewPanorama(document.getElementById('map_canvas2'), panoramaOptions);
	markerStreet = new L.MarkerClusterGroup();
	markerStreet.addLayer(new L.marker([latlongPanorama.lat, latlongPanorama.lng],{icon:iconPanorama}).bindPopup(name));
	map2.addLayer(markerStreet);

    var streetViewMaxDistance = 100;
    var streetViewService = new google.maps.StreetViewService();

    streetViewService.getPanoramaByLocation(latlongPanorama, streetViewMaxDistance, function (streetViewPanoramaData, status) {
        if (status === google.maps.StreetViewStatus.OK) {
            MarkersArrayPanorama.push(markerStreet);
        } else {
            markerStreet.infowindow = new google.maps.InfoWindow({
                content: "Streetview indisponible"
            });
            markerStreet.infowindow.close();
            markerStreet.infowindow.open(map2, markerStreet);
            MarkersArrayPanorama.push(markerStreet);
            panorama.setVisible(false);
            statusStreet = 0;
        }
        map2.setStreetView(panorama);
    });*/
}


function modeFiltrage() {
    if (document.getElementById("rememberFiltrageArret").innerHTML == "no") {
        document.getElementById("rememberFiltrageArret").innerHTML = "yes";
    } else {
        document.getElementById("rememberFiltrageArret").innerHTML = "no";
    }
}

//var trafficLayer = new google.maps.TrafficLayer();
function infoTrafic() {
    if (document.getElementById("rememberTrafic").innerHTML == "") {
        document.getElementById("rememberTrafic").innerHTML = "yes";
		//map.removeLayer(trafficLayer);
    } else {
        document.getElementById("rememberTrafic").innerHTML = "";
		//map.removeLayer(trafficLayer);
    }
}

function avecGeofencing() {
    if (document.getElementById("id_avec_geofencing").checked == true) {
        divContenu(3);
        $("#liGeofencing").parent().parent().find('.active').removeClass('active');
        $("#liGeofencing").addClass('active');
    }
}

function traitTrajet() {

    if (flightPath.length > 0) {
        for (var i = 0; i < flightPath.length; i++) {
            if (document.getElementById('id_historique_trait_trajet').checked) {
				map.addLayer(flightPath[i]);
                document.getElementById('legend_traitrajet').style.display = "";
            } else {
				map.removeLayer(flightPath[i]);
                document.getElementById('legend_traitrajet').style.display = "none";
            }
        }
    }
}

function iconHistorique() {
    if (MarkersArray) {
        for (i in MarkersArray) {
            if (document.getElementById('id_historique_icon').checked) {
				map.addLayer(MarkersArray[i]);
            } else {
				map.removeLayer(MarkersArray[i]);
            }
        }
    }
}

function resetTraitTrajet() {
    document.getElementById('legend_traitrajet').style.display = "none";
    if (flightPath.length > 0) {
        for (var i = 0; i < flightPath.length; i++) {
			map.removeLayer(flightPath[i]);
        }
        flightPath = [];
    }
}

function divHistorique(num) {
    switch (num) {
        case 1:
			document.getElementById("rememberDivHistorique").innerHTML = "Periode";
            $(document).ready(function () {
                $("#historique").load("../carto/cartoperiodehistorique.php", function () {
                    //document.getElementById("rememberDivHistorique").innerHTML = "Periode";
                    $('#OngletHistoPos').parent().parent().find('.active').removeClass('active');
                    $("#OngletHistoPeriode").addClass('active');
                });
            });
            break;
        case 2:
			document.getElementById("rememberDivHistorique").innerHTML = "Historique";
            $(document).ready(function () {
                $("#historique").load("../carto/cartopositionhistorique.php", function () {
                    //document.getElementById("rememberDivHistorique").innerHTML = "Historique";
                    $('#OngletHistoPeriode').parent().parent().find('.active').removeClass('active');
                    $("#OngletHistoPos").addClass('active');
                });
            });
            break;
    }
}

/*********************************************************/
/* fonction qui zoome sur tous les points de LatLngArray */
/*********************************************************/
var timeoutZoom = "";

function SetZoom() {
	
	if(timeoutZoom == "")
	{
		timeoutZoom = setTimeout(doZoom, 750);
	}
}

function doZoom()
{
	clearTimeout(timeoutZoom);
	timeoutZoom = "";
	
	boundbox = new L.latLngBounds();
	
	for (var i = 0; i < LatLngArray.length; i++) {
		boundbox.extend(LatLngArray[i]);
	}
	
	if(latlngCartoAddress != null) {
		boundbox.extend(latlngCartoAddress);
	}
	
	if(rememberOngletGeofencing == "yes")
	{
		for (i = 0; i < latLngPolygone.length; i++) {
			boundbox.extend(latLngPolygone[i]);
		}
	}
	
	if(rememberOngletPointInteret == "yes")
	{
		for (i = 0; i < arrayLatLngPOI.length; i++) {
			boundbox.extend(arrayLatLngPOI[i]);
		}
	}
	
	map.panTo(boundbox.getCenter());
	map.fitBounds(boundbox, {maxZoom: 16} );
}

/*********************************************************/
/* fonction qui efface les markers de map2               */
/*********************************************************/
function clearOverlaysPanorama() {
    if (MarkersArrayPanorama) {
        for (i in MarkersArrayPanorama) {
            map.removeLayer(MarkersArrayPanorama[i]);
			map2.removeLayer(MarkersArrayPanorama[i]);
        }
        MarkersArrayPanorama.length = 0;
    }
}


function getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}


function get10colors2(idtrack) {
    var colors = ["#FF0000", "#0000FF", "#00FF00", "#000000", "#4B0082", "#FFFF00", "#A9A9A9", "#FF4500", "#1E90FF", "#483D8B",
        "#FF0000", "#0000FF", "#00FF00", "#000000", "#4B0082", "#FFFF00", "#A9A9A9", "#FF4500", "#1E90FF", "#483D8B",
        "#FF0000", "#0000FF", "#00FF00", "#000000", "#4B0082", "#FFFF00", "#A9A9A9", "#FF4500", "#1E90FF", "#483D8B"];
    var color;

    var Id_Tracker = document.getElementById("idBalise").innerHTML;
    var regIdTracker = new RegExp("[,]+", "g");
    var tableauIdTracker = Id_Tracker.split(regIdTracker);
    //alert(tableauIdTracker);
    //alert(idtrack);
    for (var i = 0; i < tableauIdTracker.length; i++) {
        if (tableauIdTracker[i] == idtrack)
            color = colors[i];
    }

    return color;
}


// function deleteColonne(id){
// alert(id);
// $(id).remove();
// }

var lengthPreviousLegend = 0;
function drawLegend(color, nomBalise, numeroBalise) {


    var headerCanvas = document.getElementById("header_canvas");
    var ctx = document.getElementById("canvas").getContext("2d");
    var canvas = document.getElementsByTagName('canvas')[0];

    var widthHeader = 0;
    widthHeader = 120 * (numeroBalise + 1);
    //document.getElementById("canvas").width = widthHeader;
    headerCanvas.style.overflowX = "hidden";
    if (widthHeader >= 300) {
        widthHeader = 300;
        headerCanvas.style.overflowX = "scroll";
    }
    $("#header_canvas").css({
        'width': widthHeader
    });


    var x = 10;
    var y = 10;

    ctx.fillStyle = color;
    if (lengthPreviousLegend == 0)
        ctx.fillRect(x, y + 4, 25, 15);
    else
        ctx.fillRect(lengthPreviousLegend + x, y + 4, 25, 15);
    ctx.font = "8pt Arial";
    ctx.fillStyle = "rgb(0,0,0)";
    if (lengthPreviousLegend == 0)
        ctx.fillText(nomBalise, x + 25, y + 4 + 12);
    else
        ctx.fillText(nomBalise, lengthPreviousLegend + x + 25, y + 4 + 12);

    lengthPreviousLegend += ctx.measureText(nomBalise).width + 30;


}

function resetDrawLegend() {
    lengthPreviousLegend = 0;
    var canvas = document.getElementsByTagName('canvas')[0];
    var ctx = document.getElementById("canvas").getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

function historiqueXCartoPosition() {
    //$('#divCartoPos').append($('#divHistorique'));
    $('#divHistorique').insertBefore($('#divCartoPos'));
}

function mobileHistorique() {

    if (rememberOngletCartoPosition == "") {
        divContenu(1);
        $('#liCarto').parent().parent().find('.active').removeClass('active');
        $('#liCarto').addClass('active')
        divContenu(1);
    }
    if (document.getElementById("divHistorique").style.display == "") {
        document.getElementById("divHistorique").style.display = "none";
    } else if (document.getElementById("divHistorique").style.display == "none") {
        document.getElementById("divHistorique").style.display = "";
    }


    $("#action_nav").collapse('hide');
    actionmenu = "";

    //document.getElementById("divCartoPos").style.display = "none";
}

function closeDivMobileHistorique() {
    document.getElementById("divHistorique").style.display = "none";
}

function resetSelectPeriode() {
    document.getElementById("selectPeriode").value = "aucun";

    if (rememberOngletCartoPosition == "yes") {
		clearPolygone();
		ClearMarkerAdresse();
        ClearPOImarkers();
		clearOverlays();
        clearOverlaysPanorama();
        //offSuivi();
        resetTraitTrajet();
        resetDrawLegend();
        document.getElementById("TablePosition").innerHTML = '<table id="idTablePosition" class="sortable table table-bordered table-hover">' +
                '<thead id="head_idTablePosition">' +
                '<tr><th width="50px">N</th><th width="45px"></th><th width="150px">' + getTextNomBalise + '</th><th width="150px">Date position</th>' +
                '<th width="400px">' + getTextAdresse + '</th><th width="50px">' + getTextVitesse + '</th><th width="350px">' + getTextStatut + '</th>' +
                '<th width="45px">GSM</th><th width="45px">' + getTextAlim + '</th>' +
                '<th width="100px" style="display:none">Latitude</th><th width="100px" style="display:none">Longitude</th><th width="56px" style="display:none">Direction</th></tr>' +
                '<tbody id="body_idTablePosition"></tbody></table>';
        document.getElementById("tableposition_modes").style.display = "none";
        modeTablePosition = "normal";

        document.getElementById("rappel_date").innerHTML = ".................";
    }
}

function getIcone(Id_Tracker) {

    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;
    var icone;
	
    $.ajax({
        url: '../carto/cartogeticon.php',
        type: 'GET',
        data: "Id_Tracker=" + Id_Tracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw,
        async: false,
        success: function (response) {
            icone = response;
        }
    });

    return icone;
}

/*
function ImageExist(url)
{
    var img = new Image();
    img.src = url;
    return img.height != 0;
}
function imageExists(image_url) {

    var http = new XMLHttpRequest();

    http.open('HEAD', image_url, false);
    http.send();

    return http.status != 404;

}
*/


//**Franck**/
//**position de l'utilisateur**/
function getuserposition() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        alert("Nous ne pouvons donner suite à votre demande.");
    }
}

function showPosition(position) {
    L.Marker.mergeOptions({
		icon: L.ExtraMarkers.icon({
		icon: 'fa fa-male',
		markerColor: 'pink',
		})
	});
	markerp = new L.marker([position.coords.latitude, position.coords.longitude]);
	$.getJSON('https://geocoder.tilehosting.com/r/'+ position.coords.longitude +'/'+ position.coords.latitude +'.js?key=EUON3NGganG4JD5zzQlN', function(data) {
		markerp.bindPopup(data.results[0].display_name);
	});
	
	map.setView(new L.LatLng(position.coords.latitude, position.coords.longitude),16);
	map.addLayer(markerp);
}

function DecodeStatus(Pos_Statut, Pos_Odometre, coordPosVitesse, Pos_Key, Statut2, BattInt, BattExt, Alim, TypeServer, TypeDecodage)
{
	var GPS = new Array();
	var alimEtBatterie = new Array();
	var vitesse;
	var nomVersionBalise;
	var TypeBalise, FirmVer;
	var statutBrouilleur;
	var niveauReseau;
	var alarm1;
	var alarm2;
	var statutSTOP;
	var statutVIB;
	var volt;
	
	if(TypeDecodage == 2){
		// vitesse = " - <b>" + Math.round(coordPosVitesse) + "</b> km/h";
		vitesse = "<b>" + Math.round(coordPosVitesse) + "</b> km/h - ";
	}
	else if(TypeDecodage == 3){
		vitesse = " - Vitesse : <b>" + Math.round(coordPosVitesse) + "</b> km/h";
	}
	
	
	nomVersionBalise = versionBalise(Pos_Odometre);
	TypeBalise = Pos_Odometre.substr(0,4);
	
	if((TypeBalise!="3006")&&(TypeBalise!="3370")&&(TypeBalise!="8045")&&(TypeBalise!="8079")&&(TypeBalise!="2205")&&(TypeBalise!="7003")&&(TypeBalise!="7201")&&(TypeBalise!="8000")&&(TypeBalise!="2201")&&(TypeBalise!="2801")&&(TypeBalise!="2601"))
	{
		TypeBalise = Pos_Odometre.substr(0,2);
		FirmVer = Pos_Odometre.substr(2,2);
	}
	
	//Brouilleur
	if( (nomVersionBalise.substr(0,5) == "SC200") || (nomVersionBalise.substr(0,5) == "SC300") || ( $.inArray(TypeBalise, ['20','55','56','57','3006','3370','8079','3600','7003','7201','8000','2201','2801','2601']) >= 0) ){		// TELTO, HYBRID, NEO, QBIT, SOLO & SOLAR
		statutBrouilleur = "";
	}else{
		if(Pos_Statut & 0x10000000){
			statutBrouilleur = "(" + getTextBrouille + ") ";
		}else{
			if(TypeDecodage == 2){
				statutBrouilleur = "";			// TypeDecodage = 2
			}else{
				statutBrouilleur = "(" + getTextNonBrouille + ") ";
			}
		}
	}
	
	//NIVEAU RESEAU GSM
	var niveauReseau = 0;
	if(Pos_Statut & 0x02000000){
		niveauReseau = 2;
	}
	if(Pos_Statut & 0x01000000){
		niveauReseau = niveauReseau + 1;
	}
	
	//ALARM 2
	if(Pos_Statut & 0x00000002){
		alarm1	= "<br>";
		if( (TypeBalise == "8079") || (TypeBalise == "3370") || (TypeBalise == "7003") || (TypeBalise == "7201") ){			// SC NEO, SC SOLO
			alarm2	= "<img src='../../assets/img/ICONES/alarmeMulti.ico'> <b>" + getTextAlarm + " Couvercle</b>";			// traduction manquante
		}else if(TypeBalise == "47") {
			alarm2	= "<img src='../../assets/img/ICONES/alarme2.ico'> <b>Sous surv.</b>";
		}else{
			alarm2	= "<img src='../../assets/img/ICONES/alarme2.ico'> <b>" + getTextAlarm + " 2 active</b>";
		}
	}else {
		alarm1	= "";
		alarm2	= "";
	}

	//ALARM 1
	if(Pos_Statut & 0x00000001){
		if( (TypeBalise == "56") || (TypeBalise == "57") || (TypeBalise == "53") || (TypeBalise == "3006") || (TypeBalise == "8079")|| (TypeBalise == "3370") || (TypeBalise == "7003") || (TypeBalise == "7201") ){	// 600Av, SC CUBE, SC NEO, SC SOLO
			alarm1	= "<br><img src='../../assets/img/ICONES/alarmeMulti.ico'> <b>" + getTextAlarm + " Arrachement</b>";		// traduction manquante
		}else if(TypeBalise == "47") {
			alarm1	= "<br><img src='../../assets/img/ICONES/alarmeMulti.ico'> <b>" + getTextAlarm + " Porte</b>";
		}else{
			alarm1	= "<br><img src='../../assets/img/ICONES/alarme1.ico'> <b>" + getTextAlarm + " 1 active</b>";
		}
		if(alarm2	!= "")
			alarm1	+= " - ";
	}
	
	
	// CONTACT
	if(Pos_Statut & 0x00000004){
		statutSTOP = "";
		
	}else {
		statutSTOP = "STOP - ";
		vitesse = "";
	}

	//VIBRATION
	if(Pos_Statut & 0x00000040){
		statutVIB = "VIB";
	}else{
		statutVIB = getTextPas + " VIB";
	}
	
	// GPS Reception valide ?
	if(Pos_Statut & 0x00000020)
	{
		GPS[0] = "<b>" + Pos_Odometre[4] + "/" + Pos_Odometre[5] + "." + Pos_Odometre[6] + "</b>";
		GPS[1] = "<b>" + Pos_Odometre[4] + "/" + Pos_Odometre[5] + "." + Pos_Odometre[6] + "</b>";
		GPS[2] = "Nb.Sat <b>"  +  Pos_Odometre[4]  +  "</b> - PDOP <b>"  +  Pos_Odometre[5] + "." + Pos_Odometre[6] +  "</b> - Precision <b>"  +  (Pos_Odometre[5] + Pos_Odometre[6]/10)  +  "m</b>";	// traduction manquante
	}
	else if((TypeServer == 1) && (Pos_Key == 1) && (Statut2 & 0x01))	// Position GSM ?
	{
		GPS[0] = "<b>PositionGSM</b>";				// traduction manquante
		GPS[1] = "<b>PositionGSM</b>";				// traduction manquante
		GPS[2] = "<b>invalide, position GSM</b>";	// traduction manquante
	}
	else
	{
		GPS[0] = "<b>No</b>";
		GPS[1] = "<b>No</b>";
		GPS[2] = "<b>invalide</b>";
	}
	
	//ALIM
	if((TypeBalise == "3006") || (TypeBalise == "8079")|| (TypeBalise == "3370") || (TypeBalise == "7003") || (TypeBalise == "8000") || (TypeBalise == "22") || (TypeBalise == "24") || (TypeBalise == "26"))		// NEO 3G & NEO
	{
		if(Alim > 0 ){
			alimEtBatterie[0] = " - En charge: <b>" + Math.round(BattInt) + "%</b>";			// traduction manquante
			alimEtBatterie[1] = " - En charge: <b>" + Math.round(BattInt) + "%</b>";			// traduction manquante
			alimEtBatterie[2] = "En charge: <b>" + Math.round(BattInt) + "%</b>";				// traduction manquante
		}else{
			alimEtBatterie[0] = " - B.Int: <b>" + Math.round(BattInt) + "%</b>";				// traduction manquante
			alimEtBatterie[1] = " - B.Int: <b>" + Math.round(BattInt) + "%</b>";				// traduction manquante
			alimEtBatterie[2] = "Batterie: <b>" + Math.round(BattInt) + "%</b>";				// traduction manquante
		}
	}
	else if(TypeBalise == "7201")								// SOLO
	{	
			alimEtBatterie[0] = "";													// traduction manquante
			alimEtBatterie[1] = "";													// traduction manquante
			alimEtBatterie[2] = "";													// traduction manquante
	}
	else if(TypeBalise == "20")								// SC ECO
	{
		if(Alim > 0 ){
			volt = (Alim * 0.23) + 5;
			volt = Math.round(volt*10)/10;	// Pour arrondir 1 chiffre apres la virgule
		}else{
			volt = 0;
		}
		
		alimEtBatterie[0] = "<br>" + getTextAlimExt + ": <b>" + volt + "V</b> - B.Int: <b>" + Math.round(BattInt) + "%</b>";	// traduction manquante
		alimEtBatterie[1] = "<br>" + getTextAlimExt + ": <b>" + volt + "V</b> - B.Int: <b>" + Math.round(BattInt) + "%</b>";	// traduction manquante
		alimEtBatterie[2] = "Alimentation: <b>" + volt + "V</b>  Batterie Interne: <b>" + Math.round(BattInt) + "%</b>";		// traduction manquante
		//alimEtBatterie[0] = "<br>" + getTextAlimExt + ": <b>" + volt + "V</b>";	// traduction manquante
		//alimEtBatterie[1] = "<br>" + getTextAlimExt + ": <b>" + volt + "V</b>";	// traduction manquante
		//alimEtBatterie[2] = "Alimentation: <b>" + volt + "V</b>";		// traduction manquante
	
	}
	else if( (TypeServer == 1) && (Pos_Key == 1) && ( (TypeBalise == "55") || (TypeBalise == "56") || (TypeBalise == "57") || (TypeBalise == "17") || (TypeBalise == "18") || (TypeBalise == "19") || (TypeBalise == "48") || (TypeBalise == "53")&&(FirmVer != "01") ))		// (600St, HYBRID+, 600Av, SCx00J, SC500J, SC500JS, SC400LC, CUBE v02+ )
	{
		if(TypeBalise == "53")					// SC CUBE
		{
			alimEtBatterie[0] = " - Bat.: <b>" + Math.round(BattInt) + "%</b>";			// traduction manquante
			alimEtBatterie[1] = " - Bat.: <b>" + Math.round(BattInt) + "%</b>";			// traduction manquante
			alimEtBatterie[2] = "Batterie: <b>" + Math.round(BattInt) + "%</b>";		// traduction manquante
		}
		else if(TypeBalise == "19")				// SC500JS
		{
			volt = (Alim * 56)/1000;
			volt = volt * 10;			// Pour arrondir 1 chiffre apres la virgule
			volt = Math.round(volt);	// Pour arrondir 1 chiffre apres la virgule
			volt = volt / 10;			// Pour arrondir 1 chiffre apres la virgule
			alimEtBatterie[0] = "<br>Solaire: <b>" + volt + "V</b> - Bat.1: <b>" + Math.round(BattExt) + "%</b> - Bat.2: <b>" + Math.round(BattInt) + "%</b>";				// traduction manquante
			alimEtBatterie[1] = "<br>Solaire: <b>" + volt + "V</b> - Bat.1: <b>" + Math.round(BattExt) + "%</b> - Bat.2: <b>" + Math.round(BattInt) + "%</b>";				// traduction manquante
			alimEtBatterie[2] = "Solaire : <b>" + volt + "V</b>  Batterie 1 : <b>" + Math.round(BattExt) + "%</b>  Batterie 2 : <b>" + Math.round(BattInt) + "%</b>";		// traduction manquante
		}
		else									// 600St, 600Av, SCx00J, SC500J, SC400LC
		{
			if(Alim > 0 ){
				volt = (Alim * 0.253) + 5.5;
				volt = volt * 10;			// Pour arrondir 1 chiffre apres la virgule
				volt = Math.round(volt);	// Pour arrondir 1 chiffre apres la virgule
				volt = volt / 10;			// Pour arrondir 1 chiffre apres la virgule
			}else{
				volt = 0;
			}
			
			if(TypeBalise == "48"){
				alimEtBatterie[0] = "<br>" + getTextAlimExt + ": <b>" + volt + "V</b> - B.Int: <b>" + Math.round(BattInt) + "%</b>";	// traduction manquante
				alimEtBatterie[1] = "<br>" + getTextAlimExt + ": <b>" + volt + "V</b> - B.Int: <b>" + Math.round(BattInt) + "%</b>";	// traduction manquante
				alimEtBatterie[2] = "Alimentation: <b>" + volt + "V</b>  Batterie Interne: <b>" + Math.round(BattInt) + "%</b>";		// traduction manquante
			}else{
				alimEtBatterie[0] = "<br>" + getTextAlimExt + ": <b>" + volt + "V</b> - B.Ext: <b>" + Math.round(BattExt) + "%</b> - B.Int: <b>" + Math.round(BattInt) + "%</b>";			// traduction manquante
				alimEtBatterie[1] = "<br>" + getTextAlimExt + ": <b>" + volt + "V</b> - B.Ext: <b>" + Math.round(BattExt) + "%</b> - B.Int: <b>" + Math.round(BattInt) + "%</b>";			// traduction manquante
				alimEtBatterie[2] = "Alimentation: <b>" + volt + "V</b>  Batterie Externe: <b>" + Math.round(BattExt) + "%</b>  Batterie Interne: <b>" + Math.round(BattInt) + "%</b>";		// traduction manquante
			}
		}
	}
	else
	{
		var niveauBat = DecodeBatLvl(Pos_Statut);
		if(Pos_Statut & 0x00020000){
			alimEtBatterie[0] = " - " + getTextAlimExt;
			alimEtBatterie[1] = " - " + getTextAlimExt;
			alimEtBatterie[2] = getTextAlimExt;
		}else if(Pos_Statut & 0x00040000){
			alimEtBatterie[0] = " - B.Ext <b>" + niveauBat + "</b>";				// traduction manquante
			alimEtBatterie[1] = " - B.Ext <b>" + niveauBat + "</b>";				// traduction manquante
			alimEtBatterie[2] = "Batterie Externe : <b>" + niveauBat + "</b>";		// traduction manquante
		}else if(Pos_Statut & 0x00080000){
			alimEtBatterie[0] = " - B.Int <b>" + niveauBat + "</b>";				// traduction manquante
			alimEtBatterie[1] = " - B.Int <b>" + niveauBat + "</b>";				// traduction manquante
			alimEtBatterie[2] = "Batterie Interne : <b>" + niveauBat + "</b>";		// traduction manquante
		}else{
			alimEtBatterie[0] = " - " + getTextAlimBasse + " <b>" + niveauBat + "</b>";
			alimEtBatterie[1] = " - " + getTextAlimBasse + " <b>" + niveauBat + "</b>";
			alimEtBatterie[2] = getTextAlimBasse + " : <b>" + niveauBat + "</b>";
		}
	}
	
	
	// Selection du formatage
	if(TypeDecodage == 1) {
		var DecodedStatus = "<b>" + statutSTOP + statutVIB + "</b> - GSM <b>" + statutBrouilleur + niveauReseau + "/3</b> - GPS " + GPS[0] + alimEtBatterie[0] + "" + alarm1 + "" + alarm2;
	} else if(TypeDecodage == 2) {
		// DecodedStatus = "<b>" + statutSTOP + statutVIB + "</b> - GSM <b>" + statutBrouilleur + niveauReseau + "/3</b> - GPS " + GPS[1] + " - " + alimEtBatterie[1] + "" + alarm1 + "" + alarm2 + "" + vitesse;
		var DecodedStatus = vitesse + "<b>" + statutSTOP + statutVIB + "</b> - GSM <b>" + statutBrouilleur + niveauReseau + "/3</b> - GPS " + GPS[1] + alimEtBatterie[1] + "" + alarm1 + "" + alarm2;
	} else {
		var DecodedStatus = "<b>" + statutSTOP + statutVIB + "</b> -  GSM <b>" + statutBrouilleur + niveauReseau + "/3</b><br>GPS " + GPS[2] + vitesse + "<br>" + alimEtBatterie[2] + alarm1 + alarm2;
	}
	
	return DecodedStatus;
}

function DecodeBatLvl(Pos_Statut){
	var pourcentageBatterie = 0;
	
	// Obtention des 4 bits du niveau batterie
	if(Pos_Statut & 0x00800000)		pourcentageBatterie = 8;						// b3 (poids fort)
	if(Pos_Statut & 0x00400000)		pourcentageBatterie = pourcentageBatterie + 4;	// b2
	if(Pos_Statut & 0x20000000)		pourcentageBatterie = pourcentageBatterie + 2;	// b1
	if(Pos_Statut & 0x40000000)		pourcentageBatterie = pourcentageBatterie + 1;	// b0 (poids faible)
	
	// Calcul du pourcentage ࡰartir des 4 bits de niveau batterie
	pourcentageBatterie = (100*pourcentageBatterie)/15 ;

	return Math.round(pourcentageBatterie) + "%";
}

function IconeBalise(Pos_Statut, Pos_Vitesse, Pos_Direction){
	
	var cbalise;
	
	// GPS Reception valide ?
	if(Pos_Statut & 0x00000020){
		// Etat deplacement ?
		if(Pos_Statut & 0x00000004){
			cbalise = lireDirectionVitesse(Pos_Direction, Pos_Vitesse);
		}else {
			cbalise = stop16.src;
		}
	}else{
		// Etat deplacement ?
		if(Pos_Statut & 0x00000004){
			cbalise = noGPS.src;
		}else{
			cbalise = noGPS_Stop.src;
		}
	}
	
	return cbalise;
}
