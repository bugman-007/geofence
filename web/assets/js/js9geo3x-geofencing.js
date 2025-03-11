function initGeofencingMap(){
	document.body.className = "loading";
	
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var rememberAddMarker = document.getElementById("rememberAddMarker").innerHTML;
	var rememberAddPeriode = document.getElementById("rememberAddPeriode").innerHTML;
	var rememberAddPosition = document.getElementById("rememberAddPosition").innerHTML;
	//var idTracker = document.getElementById("idBalise").innerHTML;

	
	map = L.map('map_canvas', {
		center: [47.081012, 2.398782], zoom: 6
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
	
	document.body.className = "";

	geocoder = new L.Control.geocoder({ defaultMarkGeocode: false}).on('markgeocode', viewAdresseZone).addTo(map);
}


function listZone(){
	//var xmlhttp = null;
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	//if (window.XMLHttpRequest){
	//	xmlhttp=new XMLHttpRequest();
	//}else{
	//	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	//}
	//xmlhttp.onreadystatechange=function(){
	//	if (xmlhttp.readyState==4 && xmlhttp.status==200){
	//		document.getElementById("div_selectzone_geofenging").innerHTML =  xmlhttp.responseText;
	//		document.getElementById("message_entree").value = "";
	//		document.getElementById("message_sortie").value = "";
	//	}
	//}
	//xmlhttp.open('GET',"geofencingselectzone.php?nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&idTracker="+idTracker,true);
	//xmlhttp.send();

	//$.ajax({
	//	url: 'geofencingselectzone.php',
	//	type: 'GET',
	//	data: "nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&idTracker="+idTracker,
	//	success: function (response) {
	//		if (response) {
	//			document.getElementById("div_selectzone_geofenging").innerHTML =  response;
	//			document.getElementById("message_entree").value = "";
	//			document.getElementById("message_sortie").value = "";
	//		}
	//	}
	//});
	document.getElementById("message_entree").value = "";
	document.getElementById("message_sortie").value = "";
}

function viewAdresseZone(e){

	ClearMarkerAdresse();

	var zone = document.getElementById('select_geofencing_zone').value;
	if(zone == "all"){
		alert($('<div />').html(getTextChoisirDabordZone).text());
		// document.getElementById('checkbox_alert_message_desactive').checked = false;
	}else {
		latlngCartoAddress = e.geocode.center;
		var lat = latlngCartoAddress.lat;
		var lng = latlngCartoAddress.lng;
		
		$.getJSON('https://geocoder.tilehosting.com/r/'+ lng +'/'+ lat +'.js?key=EUON3NGganG4JD5zzQlN', function(data)
		{
			var iconAddress = new Image();
			iconAddress.src = '../../assets/img/blue-pushpin.png';
			var imageMarker = new L.icon({iconUrl:iconAddress.src, iconSize: [40, 40] ,iconAnchor: [10, 38], popupAnchor: [0, -14]});

			markerCartoAddress = new L.marker([lat,lng],
			{
				icon: imageMarker,
				title : data.results[0].display_name
			})
			.bindPopup(data.results[0].display_name)
			.addTo(map);
		});
		
		map.setView(latlngCartoAddress, 16);
	}
}

function selectZone(zone){
	//var xmlhttp = null;
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomBalise=document.getElementById('nomBalise').innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	
	clearPolygone();
	ClearMarkerAdresse();
	ClearPOImarkers();
	clearOverlays();
	LatLngArray = [];
	//clearOverlaysPanorama();
	
	if(idTracker) {

		if(idTracker.search(/,/) != -1) {
			if (zone == "all") {
				map.removeEventListener('click');
				document.getElementById("message_numero_1").value = "";
				document.getElementById("message_numero_2").value = "";
				document.getElementById("message_numero_3").value = "";
				document.getElementById("message_numero_4").value = "";
				showAllZone();
				map.on('click',clickMap);
				function clickMap(event){
					alert($('<div />').html(getTextChoisirZonePrecise).text());
				}

			} else {
				map.removeEventListener('click');

				var regIdTracker = new RegExp("[,]+", "g");
				var tableauIdTracker = idTracker.split(regIdTracker);
				var regNomBalise = new RegExp("[,]+", "g");
				var tableauNomBalise = nomBalise.split(regNomBalise);
				for (var i = 0; i < tableauIdTracker.length; i++) {
					showZone(zone, "notall",tableauIdTracker[i],tableauNomBalise[i]);
				}
				
				map.on('click',clickMap);
				function clickMap(event){
					//if (MarkersArray.length > 0) {
					if (fermerPolygone == "1") {
						if (confirm($('<div />').html( getTextModifierZoneDejaTracee).text())) {
							fermerPolygone = "0";
							clearPolygone();
							clearOverlays();
							//clearOverlaysPanorama();
							
							LatLngArray = [];
							MarkersArray = [];
						}
					} else {
						tracePolygone(event, zone);
					}
				}
				document.getElementById('message_active_desactive').innerHTML = "<b>&nbsp;3) "+getTextMessageDesactive+"</b>";
				document.getElementById('message_active_desactive').style.backgroundColor = '';
				document.getElementById('contenu_message_active_desactive').style.display = "none";
				document.getElementById('message_entree').style.backgroundColor = "";
				document.getElementById('message_sortie').style.backgroundColor = "";
				document.getElementById('checkbox_alert_message_desactive').checked = false;

				checkApparationDisparition("0");
				document.getElementById("message_numero_1").value = "";
				document.getElementById("message_numero_2").value = "";
				document.getElementById("message_numero_3").value = "";
				document.getElementById("message_numero_4").value = "";
			}

		}else {
			$.ajax({
				url: '../geofencing/geofencingzonewarning.php',
				type: 'GET',
				data: "nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&idTracker=" + idTracker + "&zone=" + zone + "&Type_Geometrie=3",
				success: function (response) {
					if (response) {
						var chaine = response;
						var reg = new RegExp("[&]+", "g");
						var tableau = chaine.split(reg);

						var destMethod = tableau[0].substring(tableau[0].indexOf('Dest_Method') + 12, tableau[0].indexOf('Warning_Type'));
						var warningType = tableau[0].substring(tableau[0].indexOf('Warning_Type') + 13, tableau[0].indexOf('Msg_app'));
						var msgApp = tableau[0].substring(tableau[0].indexOf('Msg_app') + 8, tableau[0].indexOf('Msg_disp'));
						var msgDisp = tableau[0].substring(tableau[0].indexOf('Msg_disp') + 9);

						if (warningType == "1") {
							document.getElementById('message_active_desactive').innerHTML = "<b>&nbsp;3) "+geTextMessageActive+"</b>";
							document.getElementById('message_active_desactive').style.backgroundColor = '#00FF00';
							document.getElementById('contenu_message_active_desactive').style.display = "";
							document.getElementById('message_entree').style.backgroundColor = "#00FF00";
							document.getElementById('message_sortie').style.backgroundColor = "#00FF00";
							document.getElementById('checkbox_alert_message_desactive').checked = true;

							checkApparationDisparition(destMethod);

						} else {
							document.getElementById('message_active_desactive').innerHTML = "<b>&nbsp;3) "+getTextMessageDesactive+"</b>";
							document.getElementById('message_active_desactive').style.backgroundColor = '';
							document.getElementById('contenu_message_active_desactive').style.display = "none";
							document.getElementById('message_entree').style.backgroundColor = "";
							document.getElementById('message_sortie').style.backgroundColor = "";
							document.getElementById('checkbox_alert_message_desactive').checked = false;

							checkApparationDisparition(destMethod);
							//	document.getElementById('disparition_numero_1').checked = true;

						}
						
						if (zone == "all") {
							map.removeEventListener('click');
							showAllZone();
							map.on('click',clickMap);
							function clickMap(event){
								alert($('<div />').html(getTextChoisirZonePrecise).text());
							}

						} else {
							map.removeEventListener('click');
							document.getElementById("message_entree").value = msgApp;
							document.getElementById("message_sortie").value = msgDisp;
							showZone(zone, "notall",idTracker,nomBalise);
							map.on('click',clickMap);
							function clickMap(event){
								//if (MarkersArray.length > 0) {
								if (fermerPolygone == "1") {
									if (confirm($('<div />').html( getTextModifierZoneDejaTracee).text())) {
										fermerPolygone = "0";
										clearPolygone();
										clearOverlays();
										//clearOverlaysPanorama();
										
										LatLngArray = [];
										MarkersArray = [];
										
									}
								} else {
									tracePolygone(event, zone);
								}
							}
						}
						showWarning();
					}
				}
			});
		}
	}else if( zone != 'all'){
		alert(getTextVeuillezChoisirUneBalise);
		zone = 'all';
	}
	
	if( zone == 'all')
	{
		document.getElementById("select_geofencing_zone").value = "all";
		
		document.getElementById('message_active_desactive').innerHTML = "<b>&nbsp;3) "+getTextMessageDesactive+"</b>";
		document.getElementById('message_active_desactive').style.backgroundColor = '';
		document.getElementById('checkbox_alert_message_desactive').checked = false;
		document.getElementById('message_entree').style.backgroundColor = "";
		document.getElementById('message_sortie').style.backgroundColor = "";
		document.getElementById("message_entree").value = "";
		document.getElementById("message_sortie").value = "";
		document.getElementById('message_entree').disabled = true;
		document.getElementById('message_sortie').disabled = true;
		document.getElementById('contenu_message_active_desactive').style.display = "none";
	}
	else
	{
		document.getElementById('message_entree').disabled = false;
		document.getElementById('message_sortie').disabled = false;
	}
}

var markerPolygone = new Array();
var coordPolygone = new Array();
var latLngPolygone = new Array();
var infowindowsPolygone = new Array();

function tracePolygone(event,zone){
	latLngPolygone.push(event.latlng);

	//if(test == "1")
		//infowindow.close();
	//if(infowindowsPolygone.length > 1)
		//infowindowsPolygone[infowindowsPolygone.length-1].close();
	if(infowindowsPolygone.length)
	{
		var content = infowindowsPolygone[infowindowsPolygone.length-1];
		content = content.substr(0, content.indexOf('<tr><td><input') );
		if(markerPolygone.length)
			markerPolygone[markerPolygone.length-1].bindPopup(content);
	}

	//var imageMarker= new L.icon({iconUrl:markerGreen.src});

	var html = 	"<table><tr><td>Zone: " + zone + "  Point: "+((markerPolygone.length)+1)+"</td></tr>"+
			"<tr><td><input type='button' value='"+getTextAnnuler+"'  onclick='cancelPolygone()'/>";

	if(markerPolygone.length >= 2)
		html += "<td><input type='button' value='"+getTextFermerPolygone+"' onclick='closePolygone(event)'/></td></tr></table>";

	var marker = new L.marker([event.latlng.lat, event.latlng.lng],
	{
		title: "Point: "+((markerPolygone.length)+1)
	})
	.bindPopup(html)
	.addTo(map);

	marker.openPopup();
	infowindowsPolygone.push(html);

	markerPolygone.push(marker);
	coordPolygone.push(event.latlng);

	test="1";
	if(coordPolygone.length > 1)
	{
		var flightLine = new L.Polyline(coordPolygone, {color: '#FF0000'});
		flightLine.addTo(map);
		line.push(flightLine);
	}

}

function cancelPolygone(){

	map.removeLayer(markerPolygone[markerPolygone.length-1]);
	if(line.length)
	{
		map.removeLayer(line[line.length-1]);
		line.length--;
	}
	
	markerPolygone.length--;
	coordPolygone.length--;
	latLngPolygone.length--;
	infowindowsPolygone.length--;

	if(markerPolygone.length && infowindowsPolygone.length){
		markerPolygone[markerPolygone.length-1].bindPopup(infowindowsPolygone[infowindowsPolygone.length-1]);
		markerPolygone[markerPolygone.length-1].openPopup();
	}
}
function closePolygone(event){
	var zone = document.getElementById('select_geofencing_zone').value;
	//Fermer le polygone
	coordPolygone[coordPolygone.length] = coordPolygone[0];

	var flightLine = new L.Polyline(coordPolygone, {color: '#FF0000'});

	line.push(flightLine);
	flightLine.addTo(map);

	//infowindow.close();
	if(infowindowsPolygone.length)
	{
		var content = infowindowsPolygone[infowindowsPolygone.length-1];
		content = content.substr(0, content.indexOf('<tr><td><input') );
		if(markerPolygone.length)
		{
			markerPolygone[markerPolygone.length-1].bindPopup(content);
			markerPolygone[0].openPopup();
		}
	}
	fermerPolygone = "1";
	map.removeEventListener('click');
	
	map.on('click', clickMap);
	function clickMap(event){
		if(coordPolygone.length > 0){
			if (confirm(getTextResetGeofencing)) {
				fermerPolygone = "0";
				clearPolygone();
				
				selectZone(zone);
			}
		}else{
			selectZone(zone);
		}
	}
}

function addZone(zone){
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var idDatabaseGpw = globalIdDatabaseGpw;

	if(idTracker.search(/,/) != -1) {
		var regIdTracker = new RegExp("[,]+", "g");
		var tableauIdTracker = idTracker.split(regIdTracker);
		var regNomBalise = new RegExp("[,]+", "g");
		//var tableauNomBalise = nomTracker.split(regNomBalise);
		for (var i = 0; i < tableauIdTracker.length; i++) {
			for(var y=0; y<latLngPolygone.length; y++) {
				$.ajax({
					url: '../geofencing/geofencingaddzone.php',
					type: 'GET',
					data: 	"idTracker="+tableauIdTracker[i]+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&idDatabaseGpw="+idDatabaseGpw+"&zone="+zone+
					"&latPolygone=" + latLngPolygone[y].lat + "&lngPolygone=" + latLngPolygone[y].lng,
					async: false
				});
			}
		}
	}else {
		for(var i=0; i<latLngPolygone.length; i++) {
			$.ajax({
				url: '../geofencing/geofencingaddzone.php',
				type: 'GET',
				data: 	"idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&idDatabaseGpw="+idDatabaseGpw+"&zone="+zone+
				"&latPolygone=" + latLngPolygone[i].lat + "&lngPolygone=" + latLngPolygone[i].lng,
				async: false
			});
		}
	}
}

function deleteZone2(){
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var zone = document.getElementById('select_geofencing_zone').value;
	if(idTracker.search(/,/) != -1) {
		var regIdTracker = new RegExp("[,]+", "g");
		var tableauIdTracker = idTracker.split(regIdTracker);
		var regNomBalise = new RegExp("[,]+", "g");
		//var tableauNomBalise = nomTracker.split(regNomBalise);
		for (var i = 0; i < tableauIdTracker.length; i++) {
			$.ajax({
				url: '../geofencing/geofencingdeletezone.php',
				type: 'GET',
				data: "idTracker=" + tableauIdTracker[i] + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&zone=" + zone,
				async: false
			});
		}
	}else {
		//for (var i = 0; i < latLngPolygone.length; i++) {
		$.ajax({
			url: '../geofencing/geofencingdeletezone.php',
			type: 'GET',
			data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&zone=" + zone,
			async: false
		});
		//}
	}

}
function deleteZone(){
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var zone = document.getElementById('select_geofencing_zone').value;
	if(zone != "all") {

		if(confirm($('<div />').html( getTextVouloirSupprimerZone+" "+zone+" ?").text())){
			if(idTracker.search(/,/) != -1) {
				var regIdTracker = new RegExp("[,]+", "g");
				var tableauIdTracker = idTracker.split(regIdTracker);
				var regNomBalise = new RegExp("[,]+", "g");
				for (var i = 0; i < tableauIdTracker.length; i++) {
					deleteWarning(zone, tableauIdTracker[i], nomDatabaseGpw, ipDatabaseGpw);
					$.ajax({
						url: '../geofencing/geofencingdeletezone.php',
						type: 'GET',
						data: "idTracker=" + tableauIdTracker[i] + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&zone=" + zone,
						async: false
					});
				}
			}else{
				deleteWarning(zone, idTracker, nomDatabaseGpw, ipDatabaseGpw);
				//for (var i = 0; i < latLngPolygone.length; i++) {
				$.ajax({
					url: '../geofencing/geofencingdeletezone.php',
					type: 'GET',
					data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&zone=" + zone,
					async: false
				});
				//}
			}


			clearPolygone();
			clearOverlays();
			//clearOverlaysPanorama();
			alert($('<div />').html(getTextEffacerZone+" "+zone).text());
			//initGeofencingMap();

		}
	}else{
		alert($('<div />').html(getTextChoisirDabordZone).text());
	}
}


function deleteWarning(zone, idTracker, nomDatabaseGpw, ipDatabaseGpw){

	$.ajax({
		url : '../geofencing/geofencingdeletewarning.php',
		type : 'GET',
		data : "idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&zone="+zone
	});
}


function decimaltoBinary(dec){
	var decimalRecup = dec >>> 0;
	var puissance = 7;
	var decimalEncode = new Array();


	while(puissance >= 0){
		if(Math.pow(2,puissance) > decimalRecup ){
			decimalEncode.push("0");
		}else if(Math.pow(2,puissance) <= decimalRecup ){
			decimalRecup = decimalRecup - Math.pow(2,puissance);
			decimalEncode.push("1");
		}
		puissance --;
	}
	return(decimalEncode);
}

function binaryToDecimal(bin){
	var puissance = 7;
	var binaryEncode = new Array();
	var i = 0;
	var somme = 0;
	while(puissance >= 0){
		binaryEncode[i] = Math.pow(2,puissance)*bin[i];
		puissance --;
		i++;
	}
	for (var y=0 ; y < binaryEncode.length ; y++) {
		somme += Number(binaryEncode[y]);
	}
	return(somme);
}

function checkApparationDisparition(dec){
	var decimalEncode = decimaltoBinary(dec);

	if(decimalEncode[0] == "0")  document.getElementById('disparition_numero_4').checked = false;
	if(decimalEncode[0] == "1")  document.getElementById('disparition_numero_4').checked = true;
	if(decimalEncode[1] == "0")  document.getElementById('disparition_numero_3').checked = false;
	if(decimalEncode[1] == "1")  document.getElementById('disparition_numero_3').checked = true;
	if(decimalEncode[2] == "0")  document.getElementById('disparition_numero_2').checked = false;
	if(decimalEncode[2] == "1")  document.getElementById('disparition_numero_2').checked = true;
	if(decimalEncode[3] == "0")  document.getElementById('disparition_numero_1').checked = false;
	if(decimalEncode[3] == "1")  document.getElementById('disparition_numero_1').checked = true;

	if(decimalEncode[4] == "0")  document.getElementById('apparition_numero_4').checked = false;
	if(decimalEncode[4] == "1")  document.getElementById('apparition_numero_4').checked = true;
	if(decimalEncode[5] == "0")  document.getElementById('apparition_numero_3').checked = false;
	if(decimalEncode[5] == "1")  document.getElementById('apparition_numero_3').checked = true;
	if(decimalEncode[6] == "0")  document.getElementById('apparition_numero_2').checked = false;
	if(decimalEncode[6] == "1")  document.getElementById('apparition_numero_2').checked = true;
	if(decimalEncode[7] == "0")  document.getElementById('apparition_numero_1').checked = false;
	if(decimalEncode[7] == "1")  document.getElementById('apparition_numero_1').checked = true;

	if( (document.getElementById('apparition_numero_1').checked == true) || (document.getElementById('disparition_numero_1').checked == true) ){
		document.getElementById('message_numero_1').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('message_numero_1').style.backgroundColor = "";
	}
	if( (document.getElementById('apparition_numero_2').checked == true) || (document.getElementById('disparition_numero_2').checked == true) ){
		document.getElementById('message_numero_2').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('message_numero_2').style.backgroundColor = "";
	}
	if( (document.getElementById('apparition_numero_3').checked == true) || (document.getElementById('disparition_numero_3').checked == true) ){
		document.getElementById('message_numero_3').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('message_numero_3').style.backgroundColor = "";
	}
	if( (document.getElementById('apparition_numero_4').checked == true) || (document.getElementById('disparition_numero_4').checked == true) ){
		document.getElementById('message_numero_4').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('message_numero_4').style.backgroundColor = "";
	}

	if(document.getElementById('message_numero_1').value == ""){
		document.getElementById('message_numero_1').style.backgroundColor = "";
		document.getElementById('apparition_numero_1').checked = false;
		document.getElementById('disparition_numero_1').checked = false;
	}
	if(document.getElementById('message_numero_2').value == ""){
		document.getElementById('message_numero_2').style.backgroundColor = "";
		document.getElementById('apparition_numero_2').checked = false;
		document.getElementById('disparition_numero_2').checked = false;
	}
	if(document.getElementById('message_numero_3').value == ""){
		document.getElementById('message_numero_3').style.backgroundColor = "";
		document.getElementById('apparition_numero_3').checked = false;
		document.getElementById('disparition_numero_3').checked = false;
	}
	if(document.getElementById('message_numero_4').value == ""){
		document.getElementById('message_numero_4').style.backgroundColor = "";
		document.getElementById('apparition_numero_4').checked = false;
		document.getElementById('disparition_numero_4').checked = false;
	}
}

function showWarning(){
	//var xmlhttp = null;
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	//if (window.XMLHttpRequest){
	//	xmlhttp=new XMLHttpRequest();
	//}else{
	//	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	//}
	//xmlhttp.onreadystatechange=function(){
	//	if (xmlhttp.readyState==4 && xmlhttp.status==200){
	//		var chaine=xmlhttp.responseText;
	//		var reg=new RegExp("[&]+", "g");
	//		var tableau=chaine.split(reg);
    //
	//		var dest01 = tableau[0].substring(tableau[0].indexOf('dest01')+7,tableau[0].indexOf('dest02'));
    //
	//		var dest02 = tableau[0].substring(tableau[0].indexOf('dest02')+7,tableau[0].indexOf('dest03'));
	//		var dest03 = tableau[0].substring(tableau[0].indexOf('dest03')+7,tableau[0].indexOf('dest04'));
	//		var dest04 = tableau[0].substring(tableau[0].indexOf('dest04')+7);
    //
	//		if(dest01) document.getElementById("message_numero_1").value = dest01;
	//		if(dest02) document.getElementById("message_numero_2").value = dest02;
	//		if(dest03) document.getElementById("message_numero_3").value = dest03;
	//		if(dest04) document.getElementById("message_numero_4").value = dest04;
	//	}
	//}
	//xmlhttp.open('GET',"geofencingzonewarningdest.php?nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&idTracker="+idTracker,true);
	//xmlhttp.send();

	$.ajax({
		url: '../geofencing/geofencingzonewarningdest.php',
		type: 'GET',
		data: "nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&idTracker="+idTracker,
		success: function (response) {
			if (response) {
				var chaine = response;
				var reg = new RegExp("[&]+", "g");
				var tableau=chaine.split(reg);

				var dest01 = tableau[0].substring(tableau[0].indexOf('dest01')+7,tableau[0].indexOf('dest02'));
				var dest02 = tableau[0].substring(tableau[0].indexOf('dest02')+7,tableau[0].indexOf('dest03'));
				var dest03 = tableau[0].substring(tableau[0].indexOf('dest03')+7,tableau[0].indexOf('dest04'));
				var dest04 = tableau[0].substring(tableau[0].indexOf('dest04')+7);
				
				document.getElementById("message_numero_1").value = "";
				document.getElementById("message_numero_2").value = "";
				document.getElementById("message_numero_3").value = "";
				document.getElementById("message_numero_4").value = "";
				
				if(dest01) document.getElementById("message_numero_1").value = dest01;
				if(dest02) document.getElementById("message_numero_2").value = dest02;
				if(dest03) document.getElementById("message_numero_3").value = dest03;
				if(dest04) document.getElementById("message_numero_4").value = dest04;
			}
		}
	});
}

function Lngaddress(lat,lng,m,nt,zz,i){
	$.getJSON('https://geocoder.tilehosting.com/r/'+ lng +'/'+ lat +'.js?key=EUON3NGganG4JD5zzQlN', function(data) {
		i++;
		var html = "<table><tr><td><b>" + nt + "</b></td></tr><tr><td>Zone: " + zz + "  Point: " + i + "</td></tr><tr><td>" + data.results[0].display_name + "</td></tr></table>";
		m.bindPopup(html);
		infowindowsPolygone.push(html);
	});
}

var line = [];
function showZone(zone,info,idTracker, nomTracker){

	document.body.className = "loading";
	if(info != "all") {
		//clearPolygone();
		//clearOverlaysPanorama();
		//clearOverlays();
		//LatLngArray = [];
		fermerPolygone = "0";
	}

	//var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	$.ajax({
		url: '../geofencing/geofencingshowzone.php',
		type: 'GET',
		data: "nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&idTracker="+idTracker+"&zone="+zone,

		success: function (response) {
			if (response) {

				document.body.className = "loading";
				var chaine = response;
				var reg = new RegExp("[&]+", "g");
				var tableau=chaine.split(reg);

				var coordLat;
				var coordLong;
				var latLng;

				var flightPlanCoordinates = [];
				//var infowindow = new google.maps.InfoWindow;

				var nbreLigne = tableau[0].substring(tableau[0].indexOf('t')+1,tableau[0].indexOf('g'));
				if(nbreLigne) {
					var pt1 = markerPolygone.length;
					for (var i = 0; i < nbreLigne; i++) {
						document.body.className = "loading";
						coordLat = tableau[i].substring(tableau[i].indexOf('Pos_Latitude') + 13, tableau[i].indexOf('Pos_Longitude'));
						coordLong = tableau[i].substring(tableau[i].indexOf('Pos_Longitude') + 14);
						latLng = new L.LatLng(coordLat, coordLong);
						
						//var imageMarker= new google.maps.MarkerImage(markerGreen.src);
						
						var marker = new L.marker([coordLat, coordLong],
						{
							title : nomTracker+":\nZone: "+zone+"  Point: " + (i+1)
						});
						Lngaddress(coordLat,coordLong,marker,nomTracker,zone,i);
						marker.addTo(map);

						latLngPolygone.push(latLng);
						markerPolygone.push(marker);
						coordPolygone.push(latLng);
						flightPlanCoordinates[i] = latLng;
					}
					//Fermer le polygone
					flightPlanCoordinates[flightPlanCoordinates.length] = flightPlanCoordinates[0];

					var flightLine = new L.Polyline(flightPlanCoordinates, {color: '#FF0000'});

					line.push(flightLine);
					flightLine.addTo(map);
					fermerPolygone = "1";

					//Afficher l'infobull indiquant la zone
					// var zoneInfoWindow = new google.maps.InfoWindow({
						// //infowindow.setContent("<table><tr><td>Balise:" + nomTracker + "</td></tr><tr><td>Zone:" + zone + "</td></tr><tr><td>Point: " + point + "</td></tr></table>");
						// content: "<table><tr><td><b>" + nomTracker + "</b></td></tr><tr><td>Zone:" + zone + "</td></tr></table>"
					// });
					
					/*var content = "<table><tr><td><b>" + nomTracker + "</b></td></tr><tr><td>Zone:" + zone + "</td></tr></table>"
					/var zoneMarker = new L.marker(flightPlanCoordinates[0],
					{
						title: nomTracker+"\nZone: "+zone
					})
					.bindPopup(content)
					.addTo(map);*/
					markerPolygone[pt1].openPopup();
					
					//MarkersArray.push(zoneMarker);
					document.body.className = "";
					if (rememberOngletGeofencing == "yes")
						SetZoom();
				}else{
					document.body.className = "";
				}
			}else{
				//document.body.className = "";
			}

		}
	});
}

function showAllZone(){
	var xmlhttp = null;
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomBalise=document.getElementById('nomBalise').innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	//clearPolygone();
	

	if(idTracker) {
		if(idTracker.search(/,/) != -1) {
			var regIdTracker = new RegExp("[,]+", "g");
			var tableauIdTracker = idTracker.split(regIdTracker);
			var regNomBalise = new RegExp("[,]+", "g");
			var tableauNomBalise = nomBalise.split(regNomBalise);
			for (var i = 0; i < tableauIdTracker.length; i++) {

				$.ajax({
					url: '../geofencing/geofencingshowallzone.php',
					type: 'GET',
					data: "nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&idTracker="+tableauIdTracker[i],
					async: false,
					success: function (response) {
						if (response) {
							var chaine = response;
							var reg = new RegExp("[&]+", "g");
							var tableau = chaine.split(reg);

							var nbreLigne = tableau[0].substring(tableau[0].indexOf('t') + 1, tableau[0].indexOf('g'));

							for (var y = 0; y < nbreLigne; y++) {
								var zoneSpotted = tableau[y].substring(tableau[y].indexOf('Numero_Zone:') + 13);
								showZone(zoneSpotted, "all", tableauIdTracker[i], tableauNomBalise[i]);
							}

						}
					}
				});

			}
		}else {
			$.ajax({
				url: '../geofencing/geofencingshowallzone.php',
				type: 'GET',
				data: "nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&idTracker="+idTracker,

				success: function (response) {
					if (response) {
						var chaine = response;
						var reg = new RegExp("[&]+", "g");
						var tableau = chaine.split(reg);

						var nbreLigne = tableau[0].substring(tableau[0].indexOf('t') + 1, tableau[0].indexOf('g'));

						for (i = 0; i < nbreLigne; i++) {
							var zoneSpotted = tableau[i].substring(tableau[i].indexOf('Numero_Zone:') + 13);
							showZone(zoneSpotted, "all",idTracker,nomBalise);
						}

					}
				}
			});
		}
	}
}

function validZone(){
	document.body.className = "loading";
	var zone = document.getElementById('select_geofencing_zone').value;
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nameTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var regIdTracker = new RegExp("[,]+", "g");
	var tableauIdTracker = idTracker.split(regIdTracker);

	if(zone == "all"){
		//alert($('<div />').html(getTextChoisirZonePrecise).text());
		alert($('<div />').html(getTextChoisirDabordZone).text());
	}else{
		if (latLngPolygone.length >= 3) {
			if (fermerPolygone == "1") {
				if (idTracker.search(/,/) != -1)
					alert($('<div />').html(getTextWarningGeofMultiple).text());

				if (confirm($('<div />').html( getTextVouloirCreerModifierZone+" "+zone+" ?").text())) {
					validTmessagesGeofencing();
					document.getElementById('select_geofencing_zone').value == "all";
				}
				
			} else {
				alert(getTextVeuillezFermerPolygone);
			}
		} else {
			alert($('<div />').html(getTextRedefinirZone).text());
		}
	}
	
	document.body.className = "";
}

var fermerPolygone = "0";
function validWarningDest(nameTracker,idTracker, nomDatabaseGpw, ipDatabaseGpw){
	var numero1 = document.getElementById('message_numero_1').value;
	var numero2 = document.getElementById('message_numero_2').value;
	var numero3 = document.getElementById('message_numero_3').value;
	var numero4 = document.getElementById('message_numero_4').value;

	$.ajax({
		url : '../geofencing/geofencingvalidwarningdest.php',
		type : 'GET',
		data : "idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&numero1="+numero1+"&numero2="+numero2+"&numero3="+numero3+"&numero4="+numero4,

		success: function(response) {
			//if(response){
			//	alert("Le(s) numero(s) "+response+" ont \351t\351 modifi\351");
			//}
		}
	});
}

function validWarning(zone, idTracker, nomDatabaseGpw, ipDatabaseGpw){
	var xmlhttp = null;

	var messageEntree = document.getElementById("message_entree").value;
	var messageSortie = document.getElementById("message_sortie").value;

	var warningType;

	var destMethodArray = new Array();
	var destMethod;

	if(document.getElementById('disparition_numero_1').checked == true) destMethodArray[3] = "1";
	if(document.getElementById('disparition_numero_1').checked == false)destMethodArray[3] = "0";
	if(document.getElementById('disparition_numero_2').checked == true) destMethodArray[2] = "1";
	if(document.getElementById('disparition_numero_2').checked == false)destMethodArray[2] = "0";
	if(document.getElementById('disparition_numero_3').checked == true) destMethodArray[1] = "1";
	if(document.getElementById('disparition_numero_3').checked == false)destMethodArray[1] = "0";
	if(document.getElementById('disparition_numero_4').checked == true)	destMethodArray[0] = "1";
	if(document.getElementById('disparition_numero_4').checked == false)destMethodArray[0] = "0";

	if(document.getElementById('apparition_numero_1').checked == true)	destMethodArray[7] = "1";
	if(document.getElementById('apparition_numero_1').checked == false) destMethodArray[7] = "0";
	if(document.getElementById('apparition_numero_2').checked == true)	destMethodArray[6] = "1";
	if(document.getElementById('apparition_numero_2').checked == false) destMethodArray[6] = "0";
	if(document.getElementById('apparition_numero_3').checked == true)	destMethodArray[5] = "1";
	if(document.getElementById('apparition_numero_3').checked == false) destMethodArray[5] = "0";
	if(document.getElementById('apparition_numero_4').checked == true)	destMethodArray[4] = "1";
	if(document.getElementById('apparition_numero_4').checked == false) destMethodArray[4] = "0";

	destMethod = binaryToDecimal(destMethodArray);

	if(document.getElementById('checkbox_alert_message_desactive').checked == true) warningType= "1";
	if(document.getElementById('checkbox_alert_message_desactive').checked == false) warningType= "0";

	if(destMethod == "0"){
		warningType= "0";
		alert("Vous n'avez choisi aucun numéro de téléphone pour recevoir des messages. L'alarme ne sera pas activée.");
	}
	var date = new Date();
	$.ajax({
		url : '../geofencing/geofencingvalidwarning.php',
		type : 'GET',
		data : "idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&zone="+zone+
		"&messageEntree="+messageEntree+"&messageSortie="+messageSortie+"&destMethod="+destMethod+"&warningType="+warningType+"&Type_Geometrie=3"+
		"&warningLap="+date.getTimezoneOffset(),



		success: function(response) {

		}
	});
}


function validTmessagesGeofencing(){

	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var nameTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var messageEntree = document.getElementById("message_entree").value;
	var messageSortie = document.getElementById("message_sortie").value;

	var zone = document.getElementById('select_geofencing_zone').value;

	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();


	var sujet;
	var corps;
	var ok = 0;
	
	if(document.getElementById('checkbox_alert_message_desactive').checked == true){

		if(messageEntree == "" && messageSortie == ""){
			alert(getTextSaisirMessageEntreeSortie);
		}else {
			sujet = "Geofencing polygone Zone "+zone+" - Activer Message, Entree: " + messageEntree + ", Sortie: " + messageSortie;
			corps = "Activer Message, Entree: " + messageEntree + ", Sortie: " + messageSortie + ", Zone " + zone;
			ok = 1;
		}
	}else if(document.getElementById('checkbox_alert_message_desactive').checked == false){
		sujet = "Geofencing polygone Zone "+zone+" - Desactiver Message";
		corps = "Desactiver Message, Zone "+zone;
		ok = 2;
	}
	
	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else {

		if(ok != 0) {
			if(idTracker.search(/,/) != -1) {
				var regIdTracker = new RegExp("[,]+", "g");
				var tableauIdTracker = idTracker.split(regIdTracker);
				var regNomBalise = new RegExp("[,]+", "g");
				var tableauNomBalise = nomTracker.split(regNomBalise);

				deleteZone2();

				addZone(zone);
				for (var i = 0; i < tableauIdTracker.length; i++) {
					validWarningDest(nameTracker, tableauIdTracker[i], nomDatabaseGpw, ipDatabaseGpw);
					validWarning(zone, tableauIdTracker[i], nomDatabaseGpw, ipDatabaseGpw);
					$.ajax({
						url: '../geofencing/geofencingvalidtmessages.php',
						type: 'GET',
						data: "datetime=" + notreDate + "&idTracker=" + tableauIdTracker[i] +
						"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&sujet=" + sujet + "&corps=" + corps,
						async: true,
						success: function (response) {

						}
					});
				}
				alert($('<div />').html(getTextBaliseBienConfig+" (zone " + zone + ") : " + nameTracker).text());
			}else{
				deleteZone2();
				
				addZone(zone);
				if(ok == 1) 
					validWarningDest(nameTracker, idTracker, nomDatabaseGpw, ipDatabaseGpw);
				validWarning(zone, idTracker, nomDatabaseGpw, ipDatabaseGpw);
				
				$.ajax({
					url: '../geofencing/geofencingvalidtmessages.php',
					type: 'GET',
					data: "datetime=" + notreDate + "&idTracker=" + idTracker +
					"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&sujet=" + sujet + "&corps=" + corps,
					async: true,
					success: function (response) {
						alert($('<div />').html(getTextBaliseBienConfig+" (zone " + zone + ") : " + nameTracker).text());
					}
				});
			}
		}
	}
}

function onCheckMessageActiveDesactive(obj){

	var div = document.getElementById( 'message_active_desactive' );

	var zone = document.getElementById('select_geofencing_zone').value;
	if(zone == "all"){
		document.getElementById('checkbox_alert_message_desactive').checked = false;
		alert($('<div />').html(getTextChoisirDabordZone).text());
		//alert("Il faut choisir d'abord une zone pr\351cise.");
	}else {
		if (obj.checked) {
			document.getElementById('message_active_desactive').innerHTML = "<b>&nbsp;3) "+geTextMessageActive+"</b>";
			div.style.backgroundColor = '#00FF00';
			document.getElementById('contenu_message_active_desactive').style.display = "";
			document.getElementById('message_entree').style.backgroundColor = "#00FF00";
			document.getElementById('message_sortie').style.backgroundColor = "#00FF00";
		} else {
			document.getElementById('message_active_desactive').innerHTML = "<b>&nbsp;3) "+getTextMessageDesactive+"</b>";
			div.style.backgroundColor = '';
			document.getElementById('contenu_message_active_desactive').style.display = "none";
			document.getElementById('message_entree').style.backgroundColor = "";
			document.getElementById('message_sortie').style.backgroundColor = "";
		}
	}
}

function onCheckNumeroApparitionDisparition(numero){
	switch(numero){
		case 1:
			if(document.getElementById('message_numero_1').value) {
				if (document.getElementById('apparition_numero_1').checked || document.getElementById('disparition_numero_1').checked) {
					document.getElementById('message_numero_1').style.backgroundColor = "#00FF00";
				} else {
					document.getElementById('message_numero_1').style.backgroundColor = "";
				}
			}else{
				alert($('<div />').html( getTextAlertTel1PasEnregistrer).text());
				document.getElementById('apparition_numero_1').checked = false;
				document.getElementById('disparition_numero_1').checked = false;
			}
			break;
		case 2:
			if(document.getElementById('message_numero_2').value) {
				if (document.getElementById('apparition_numero_2').checked || document.getElementById('disparition_numero_2').checked) {
					document.getElementById('message_numero_2').style.backgroundColor = "#00FF00";
				} else {
					document.getElementById('message_numero_2').style.backgroundColor = "";
				}
			}else{
				alert($('<div />').html( getTextAlertTel2PasEnregistrer).text());
				document.getElementById('apparition_numero_2').checked = false;
				document.getElementById('disparition_numero_2').checked = false;
			}
			break;
		case 3:
			if(document.getElementById('message_numero_3').value) {
				if (document.getElementById('apparition_numero_3').checked || document.getElementById('disparition_numero_3').checked) {
					document.getElementById('message_numero_3').style.backgroundColor = "#00FF00";
				} else {
					document.getElementById('message_numero_3').style.backgroundColor = "";
				}
			}else{
				alert($('<div />').html( getTextAlertTel3PasEnregistrer).text());
				document.getElementById('apparition_numero_3').checked = false;
				document.getElementById('disparition_numero_3').checked = false;
			}
			break;
		case 4:
			if(document.getElementById('message_numero_4').value) {
				if (document.getElementById('apparition_numero_4').checked || document.getElementById('disparition_numero_4').checked) {
					document.getElementById('message_numero_4').style.backgroundColor = "#00FF00";
				} else {
					document.getElementById('message_numero_4').style.backgroundColor = "";
				}
			}else{
				alert($('<div />').html( getTextAlertTel4PasEnregistrer).text());
				document.getElementById('apparition_numero_4').checked = false;
				document.getElementById('disparition_numero_4').checked = false;
			}
			break;
	}
}

function afficherFichierLogGeofencing(){

	var xmlhttp = null;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var idTracker =document.getElementById("idBalise").innerHTML;
	var nomBalise=document.getElementById('nomBalise').innerHTML;
	var tz = jstz.determine();
	var timezone = tz.name();
	if(idTracker != "") {
		if(idTracker.search(/,/) != -1) {
			alert(getTextVeuillezChoisirQueUneBalise);
			$('#fichier_log').modal('hide');
		}else {

			$.ajax({
				url: '../etatbalise/etatbalisettracker.php',
				type: 'GET',
				data: "Id_Tracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone,
				async: true,
				success: function (response) {
					if (response) {
						var chaine = response;
						var reg = new RegExp("[&]+", "g");
						var tableau = chaine.split(reg);

						var numeroAppel = tableau[1].substring(12);
						$.ajax({
							url: '../geofencing/geofencingfichierlog.php',
							type: 'GET',
							data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel,
							async: true,
							success: function (response2) {
								if (response2) {
									var chaine = response2;
									var reg = new RegExp("[&]+", "g");
									var tableau = chaine.split(reg);
									var nbreLigne = tableau[0].substring(tableau[0].indexOf('t') + 1, tableau[0].indexOf('g'))
									var contenuFichierLog = "<p><b>"+getTextHistoriqueParam+" " + nomBalise + " </b></p> ";
									if (nbreLigne) {
										for (var i = 0; i < nbreLigne; i++) {
											var sujet = tableau[i].substring(tableau[i].indexOf('Sujet') + 6, tableau[i].indexOf('Date'));
											var date = tableau[i].substring(tableau[i].indexOf('Date') + 5, tableau[i].indexOf('DateEnvoi'));
											var dateEnvoi = tableau[i].substring(tableau[i].indexOf('DateEnvoi') + 10);
											contenuFichierLog += getTextDateEnregistrement+": " + date + " "+getTextDateEnvoi+": " + dateEnvoi + "<br>" + sujet + "<br><br>";
										}
										document.getElementById('fichier_log_modal').innerHTML = contenuFichierLog;
										$('#fichier_log').modal('show');
									} else {
										alert($('<div />').html( getTextConfirmNoLogs).text());
									}

								}
							}
						});

					}
				}
			});
		}
	}else{
		alert(getTextVeuillezChoisirUneBalise);
		$('#fichier_log').modal('hide');
	}
}

function clearPolygone()
{
	var i;
	
	for(i=markerPolygone.length-1; i>=0; i--)
	{
		map.removeLayer(markerPolygone[i]);
		markerPolygone.pop();
	}
	
	for(i = latLngPolygone.length-1; i>=0; i--)
		latLngPolygone.pop();
	
	for(i = infowindowsPolygone.length-1; i>=0; i--)
		infowindowsPolygone.pop();
		
	for(i=line.length-1; i>=0; i--)
	{
		map.removeLayer(line[i]);
		line.pop();
	}
	
	for(i = coordPolygone.length-1; i>=0; i--)
		coordPolygone.pop();
}
