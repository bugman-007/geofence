/********************************************************************************************************************************************************************************************************/
//****************************************************************************JAVASCRIPT FONCTION GLOBALE**************************************************************************************************//
/********************************************************************************************************************************************************************************************************/
//FONCTION clearOverlays()		
	function clearOverlays() {
		if (MarkersArray) {														//Si nous avons des valeurs dans notre tableau alors...
			for(i in MarkersArray){												//Pour tout les marqueurs inseres dans notre tableau
				map.removeLayer(MarkersArray[i]);									//setmap(null) = efface le marqueur, on efface tout les marqueurs
			}
			MarkersArray.length = 0;											//Null est une valeur, on enleve toutes les valeurs de notre tableau
		}
		// if (LatLngArray) {														//Si nous avons des valeurs dans notre tableau alors...
			// for(i in LatLngArray){												//Pour tout les marqueurs inseres dans notre tableau
				// LatLngArray[i]= null;									//setmap(null) = efface le marqueur, on efface tout les marqueurs
			// }
			// LatLngArray.length = 0;											//Null est une valeur, on enleve toutes les valeurs de notre tableau
		// }
	}
/*	
//FONCTION downloadUrl()	
    function downloadUrl(url, callback){
      var request = window.ActiveXObject ?
      new ActiveXObject('Microsoft.XMLHTTP') :
      new XMLHttpRequest;
		request.onreadystatechange = function() {
			if (request.readyState == 4) {
				request.onreadystatechange = doNothing;
				callback(request.responseText, request.status);
			}
		};
		request.open('GET', url, true);
		request.send(null);
    }

//FONCTION doNothing()	
    function doNothing() {}

//FONCTION deconnexion()	
   function deconnexion() {
       alert("Vous avez bien ete deconnecte")
   }	

   //FONCTION clear()		
	function clear() {
			// infowindow.close(MarkersArray);													//Si nous avons des valeurs dans notre tableau alors...								
			
			map.removeLayer(MarkersArray);			//setmap(null) = efface le marqueur, on efface tout les marqueurs
	}*/
/********************************************************************************************************************************************************************************************************/
//****************************************************************************JAVASCRIPT FONCTION CARTEGOOGLEMAP********************************************************************************************//
/********************************************************************************************************************************************************************************************************/

	
/*
//FONCTION ContenuBulle()		
	function ContenuBulle(line1,line2,line3,line4,line5,line6,line7,line8){
	
		bulleHtml =	"<table cellSpacing=0 cellPadding=0 width=0 border=0 "
		bulleHtml += "style='font-family:Verdana; font-size:11px;'"
		bulleHtml += ">"
		
		if(line7 == ''){
			if(line2 != ''){
				bulleHtml+=  "<tr><td>*</td> <td> "+line2+"</td> </tr>"
			}
			if(line1 != ''){
				bulleHtml+=  "<tr><td>*</td> <td>"+line1+" - "+line3+"</td> </tr>"
			} 		
			if(line5 != ''){
				if(line8==2 || line8==4){
					bulleHtml+=  "<tr><td>*</td><td>"+line5+"</td></tr>"
				}else{
					bulleHtml+=  "<tr><td>*</td><td>"+line5+" - "+line4+" km/h "+"</td></tr>"
				}
			}       
			if(line6 != ''){
				bulleHtml+=  "<tr><td>*</td><td>"+line6+"</td></tr>"
			}
		}else{
			if(line5 != ''){
				if(line8==2 || line8==4){
					bulleHtml+=  "<tr><td>*</td><td>"+line5+"</td></tr>"
				}else{
					bulleHtml+=  "<tr><td>*</td><td>"+line5+" - "+line4+" km/h "+"</td></tr>"
				}
			}       
			if(line7 != ''){
				bulleHtml+=  "<tr><td>*</td> <td>Lien POI:"+"<a href="+line7+ " onclick="+"'window.open(this.href); return false;'" +"> Cliquer ici </a>"+"</td></tr>"
			}
		}
		bulleHtml += "</table>";		
		return bulleHtml;	
}

//FONCTION saveData()
    function saveData() {
		//Initialisation des variables
		var name = escape(document.getElementById("name").value);					//On recupere la valeur de name de insermarker.php
		var address = escape(document.getElementById("address").value);				//On recupere la valeur de address de insermarker.php
		var type = document.getElementById("type").value;							//On recupere la valeur de type de insermarker.php
		var latlng = marker.getPosition();											//On recupere la longitude et latitude du marqueurgrace a la fonction getPosition()
		var url = "insertmarker.php?name=" + name + "&address=" + address +			//Recuperation des donnees dans la BDD grace a url
				"&type=" + type + "&lat=" + latlng.lat() + "&lng=" + latlng.lng();
		
		//Execution de la fonction downloadUrl()
		downloadUrl(url, function(data, responseCode) {							
			if (responseCode == 200 && data.length <= 1) {						//data.length <= 1 ca veut dire: pas de message d'erreur (echo)
				infowindow.close();
				document.getElementById("message").innerHTML = "Position de "+name+" a ete bien ajoute.";
			}else{
				document.getElementById("message").innerHTML = "Pas reussi";
			}
		});
    }
*/
function resetParamPoi(){
	document.getElementById("contenu_alerte_active_desactive").style.display = "none";
	document.getElementById('message_numero_1').style.backgroundColor = "";
	document.getElementById('message_numero_2').style.backgroundColor = "";
	document.getElementById('message_numero_3').style.backgroundColor = "";
	document.getElementById('message_numero_4').style.backgroundColor = "";
	document.getElementById('arrivee_numero_1').checked = false;
	document.getElementById('depart_numero_1').checked = false;
	document.getElementById('arrivee_numero_2').checked = false;
	document.getElementById('depart_numero_2').checked = false;
	document.getElementById('arrivee_numero_3').checked = false;
	document.getElementById('depart_numero_3').checked = false;
	document.getElementById('arrivee_numero_4').checked = false;
	document.getElementById('depart_numero_4').checked = false;
	document.getElementById("message_numero_1").value = "";
	document.getElementById("message_numero_2").value = "";
	document.getElementById("message_numero_3").value = "";
	document.getElementById("message_numero_4").value = "";
	document.getElementById("message_arrivee").value = "";
	document.getElementById("message_depart").value = "";
	document.getElementById('message_arrivee').style.backgroundColor = "";
	document.getElementById('message_depart').style.backgroundColor = "";
	document.getElementById('alerte_active_desactive').style.backgroundColor = "";
	document.getElementById('checkbox_alert_message_desactive').checked = false;
}

/********************************************************************************************************************************************************************************************************/
//****************************************************************************JAVASCRIPT FONCTION POINTINTERET***************************************************************************************************//
/********************************************************************************************************************************************************************************************************/
function initCartoPtInteret(){
	document.getElementById("idTablePositionPOI").innerHTML = '<tr><th width="150px">'+getTextNomBalise+'</th><th  width="50">ID Poi</th><th width="150px">'+getTextNomPoi+'</th><th width="350px">'+getTextAdresse+'</th><th width="200px">Description</th>' +
			'<th width="100px" style="display:none" >Latitude</th>' +
			'<th width="100px" style="display:none">Longitude</th><th width="100px">'+getTextRayon+'</th><th style="display:none">ID Balise</th></tr>';
			
	map.setView([47.081012, 2.398782],6);
	
	map.removeEventListener('click');
	map.on('click',function(e) { visualiserClique(e.latlng) });
	
	resetall();
}

function resetall(){
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;

	clearPolygone();
	ClearMarkerAdresse(); 
	ClearPOImarkers();
	clearOverlays();
	LatLngArray = [];
	
	document.getElementById("idTablePositionPOI").innerHTML = '<tr><th width="150px">'+getTextNomBalise+'</th><th  width="50">ID Poi</th><th width="150px">'+getTextNomPoi+'</th><th width="350px">'+getTextAdresse+'</th><th width="200px">Description</th>' +
			'<th width="100px" style="display:none" >Latitude</th>' +
			'<th width="100px" style="display:none">Longitude</th><th width="100px">'+getTextRayon+'</th><th style="display:none">ID Balise</th></tr>';
	
	resetParamPoi();
	
	if(idTracker) {
		if(idTracker.search(/,/) != -1) {
			var regIdTracker = new RegExp("[,]+", "g");
			var tableauIdTracker = idTracker.split(regIdTracker);
			var regNomBalise = new RegExp("[,]+", "g");
			var tableauNomBalise = nomTracker.split(regNomBalise);
			for (var i = 0; i < tableauIdTracker.length; i++) {
				//alert(tableauIdTracker[i]);
				showMarkerPoiTracker(tableauIdTracker[i]);
				showTablePositionPoiTracker(tableauIdTracker[i],tableauNomBalise[i]);
			}
		}else {
			showMarkerPoiTracker(idTracker);
			showTablePositionPoiTracker(idTracker,nomTracker);
			showAlertPOI();
			document.getElementById("tr_2eme_etape").style.display = "";
		}

	}else{
		document.getElementById("tr_2eme_etape").style.display = "";
		document.getElementById("tr_3eme_etape").style.display = "none";
		document.getElementById("tr_message_arrivee").style.display = "none";
		document.getElementById("tr_message_depart").style.display = "none";
		document.getElementById("tr_validation_poi").style.display = "none";
		//getPtInteretMarqueur();
		//afficheTablePositionPOI();
	}

}
/*
function visualiserAdresse(x,y,m) {

	var date=new Date();
	var jour= date.getDate();
	var mois= date.getMonth();
	mois = mois + 1;
	if (mois < 10) { mois = '0' + mois; }
	var year= date.getYear();
	var heure= date.getHours();
	var minute= date.getMinutes();
	if (minute < 10) { minute = '0' + minute; }
	
	nompoi="POI - "+jour+"-"+mois+"-"+(year+1900)+"_ "+heure+"h"+minute;

	//var imageMarker= new L.icon({iconUrl:poi1.src});
					
	//clearOverlays();
	//ClearPOImarkers();
	resetParamPoi();

	//MarkersArray.push(m);
	//visualiserClique();
}
*/
function visualiserClique(latlng)
{
	var idTracker = document.getElementById("idBalise").innerHTML;
	
	if(idTracker){
		
		if (confirm(getTextInsererNewPoi)) {
			
			resetParamPoi();

			ClearMarkerAdresse();
			
			latlngCartoAddress =  latlng;
			var lat = latlng.lat;
			var lng = latlng.lng;
			
			
			$.getJSON('https://geocoder.tilehosting.com/r/'+ lng +'/'+ lat +'.js?key=EUON3NGganG4JD5zzQlN', function(data) {
				
				var date=new Date();
				var jour= date.getDate();
				var mois= date.getMonth();
				mois = mois + 1;
				if (mois < 10) { mois = '0' + mois; }
				var year= date.getYear();
				var heure= date.getHours();
				var minute= date.getMinutes();
				if (minute < 10) { minute = '0' + minute; }
				
				var nompoi="POI - "+jour+"-"+mois+"-"+(year+1900)+"_ "+heure+"h"+minute;
				
				markerCartoAddress = new L.marker([lat,lng]);
				
				markerCartoAddress.bindPopup(
				"<table>" +
				"<tr><td>"+getTextNomPoi+":</td> <td><input type='text' id='name' value='" + nompoi + "'/> </td> </tr>" +
				"<tr><td>"+getTextAdressePostale+":</td> <td><input type='text' id='address'value='" + data.results[0].display_name + "'/></td> </tr>" +
				"<tr><td>"+getTextRayonConsideration+" (m): </td><td><input type='text' id='rayon' placeholder='50'/> </td> </tr>" +
				"<tr><td>Description:</td><td><input type='text' id='description' placeholder='Optionnel'/></td></tr>" +
				"<tr><td>"+getTextHtmlUrl+":</td><td><input type='text' id='lien' placeholder='Optionnel' ></td></tr>" +
				"<tr><td>&nbsp;</td></tr><tr><td colspan='2' style='text-align: right'><input type='button' class='btn btn-default btn-xs dropdown-toggle' value='"+getTextEnregistrerPoi+"' onclick='savePtInteret("+ lat +","+ lng +")'/></td></tr>"
				);
				
				markerCartoAddress.addTo(map);
				markerCartoAddress.openPopup();
			});
					
			map.setView(latlngCartoAddress, 16);

			//latPtInteret = document.getElementById("latbox").value = latlng.lat;
			//lngPtInteret = document.getElementById("lngbox").value = latlng.lng;
		}
	}else{
		alert(getTextVeuillezChoisirUneBalise);
	}
}

//FONCTION savePtInteret()
function savePtInteret(x,y) {
	document.body.className = "loading";
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var idTracker = document.getElementById("idBalise").innerHTML;
	var name = document.getElementById("name").value;
	name = name.replace("'", "");
	var description = document.getElementById("description").value;
	description = description.replace("'", "");
	var address = document.getElementById("address").value;
	address = address.replace("'", "");
	var rayon = document.getElementById("rayon").value;
	if(rayon == 0){ rayon = 50; }
	//console.log(x); console.log(y);
	//var latlng = marker.getLatLng();
	var lien = document.getElementById("lien").value;

	if(!lien){ lien = "\"\""; }

	if(idTracker)
	{
		if(!address){
			$.getJSON('https://geocoder.tilehosting.com/r/'+ y +'/'+ x +'.js?key=EUON3NGganG4JD5zzQlN', function(data) {
				address = data.results[0].display_name;
				$.ajax({
					url: '../pointinteret/pointinteretinsertmarqueur.php',
					type: 'GET',
					data: "name=" + name + "&address=" + address + "&lat=" + x + "&lng=" + y + "&rayon=" + rayon + "&description=" + description+ "&lien=" + lien +"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,
					success: function (response) {
						//document.body.className = "";
						
						addWarningPoi(response);
						
						//map.setView(new L.LatLng(47.081012,2.398782),6);
						//initCartoPtInteret();
						resetall();
					}
				});
			});
		}else{
			$.ajax({
				url: '../pointinteret/pointinteretinsertmarqueur.php',
				type: 'GET',
				data: "name=" + name + "&address=" + address + "&lat=" + x + "&lng=" + y + "&rayon=" + rayon + "&description=" + description+ "&lien=" + lien +"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,

				success: function (response) {
					//document.body.className = "";

					addWarningPoi(response);
					
					//map.setView(new L.LatLng(47.081012,2.398782),6);
					//initCartoPtInteret();
					resetall();
				}
			});
		}
		//url = "pointinteretinsertmarqueur.php?name=" + name + "&address=" + address + "&lat=" + latlng.lat() + "&lng=" + latlng.lng()+ "&rayon=" + rayon + "&description=" + description+ "&lien=" + lien +"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw;
		//downloadUrl(url, function(data, responseCode) {
		//	if (responseCode == 200 && data.length <= 1) {
		//		infowindow.close();
		//		alert("\" "+name+" \" a \351t\351 bien ajout\351.");
		//		document.body.className = "";
		//		//document.getElementById("message").innerHTML = "Position de \" "+name+" \" a ete bien ajoute.";
		//	}else{
		//		alert("Pas reussi");
		//		document.body.className = "";
		//		// document.getElementById("message").innerHTML = "Pas reussi";
		//	}
		//});

	}
}

var arrayLatLngPOI = new Array();		
var arrayMarkersPOI = [];
var cityCircle;
var arrayCityCircle = [];

function getPtInteretMarqueur(){
		document.body.className = "loading";
		var nomDatabaseGpw = globalnomDatabaseGpw;
		var ipDatabaseGpw = globalIpDatabaseGpw;

		var bounds = new L.latLngBounds();

		var imageMarker = new L.icon({iconUrl:poi1.src});

		$.ajax({
			url: '../pointinteret/pointinteretgetmarqueur.php',
			type: 'GET',
			data: "nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,
			success: function (response) {
				if (response) {
					var reg=new RegExp("[&]+", "g");
					var tableau=response.split(reg);
					var arraylatlong =[];
					var poiLat = [];
					var poiLong = [];
					var poiName = [];
					var poiAdresse = [];
					var poiRayon = [];
					var idPoi = [];

					var nbreLigne = tableau[0].substring(tableau[0].indexOf('t')+1,tableau[0].indexOf('g'));

					// if(	nbreLigne ){
					for (i=0; i< nbreLigne;i++){
						idPoi[i] =  tableau[i].substring(tableau[i].indexOf('idPoi')+6,tableau[i].indexOf('Latitude'));

						poiLat =  tableau[i].substring(tableau[i].indexOf('Latitude')+9,tableau[i].indexOf('Longitude'));
						poiLong = tableau[i].substring(tableau[i].indexOf('Longitude')+10,tableau[i].indexOf('Name'));
						poiName[i] = tableau[i].substring(tableau[i].indexOf('Name')+5,tableau[i].indexOf('Adresse'));
						poiAdresse[i] = tableau[i].substring(tableau[i].indexOf('Adresse')+8,tableau[i].indexOf('Rayon'));
						poiRayon[i] = tableau[i].substring(tableau[i].indexOf('Rayon')+6);
						//Insertion du marqueur selon la latitude et longitude du Tracker
						arraylatlong[i]  = new L.LatLng(poiLat, poiLong);
						var latlngtab = new L.LatLng(poiLat, poiLong);
						// alert(poiName);
						// alert(poiName);
						// var latlng = new google.maps.LatLng(latInteret, lngInteret);
						var marker = new L.FeatureGroup();
						//extend the bounds to include each marker's position
						bounds.extend(latlngtab);
						marker.addLayer(new L.marker([poiLat, poiLong]).bindPopup("<table><tr><td>POI: <b>"+poiName[i]+"</b></td></tr><tr><td>"+poiAdresse[i]+"</td></tr><tr><td></td><td><input type='button' class='btn btn-default btn-xs dropdown-toggle' value='Supprimer POI' onclick=\"supprimerPOI('"+idPoi[i]+"','"+poiName[i]+"')\"/></td></tr></table>"));
						map.addLayer(marker);
						
						// google.maps.event.addListener(marker, 'click', (function(marker, i) {
							// return function() {
								// infowindow.setContent( "<table><tr><td><b>"+poiName[i]+"</b></td></tr><tr><td>"+poiAdresse[i]+"</td></tr><tr><td></td><td><input type='button' class='btn btn-default btn-xs dropdown-toggle' value='Supprimer POI' onclick=\"supprimerPOI('"+idPoi[i]+"','"+poiName[i]+"')\"/></td></tr></table>");
								// infowindow.open(map, marker);

							// }
						// })(marker, i));

						arrayLatLngPOI.push(latlngtab);
						arrayMarkersPOI.push(marker);

					}
					i=0;
					for (var city in poiRayon) {
						// alert(poiRayon[1]);
						// var populationOptions = {
							// strokeColor: '#FF0000',
							// strokeOpacity: 0.8,
							// strokeWeight: 2,
							// fillColor: '#FF0000',
							// fillOpacity: 0.35,
							// map: map,
							// center:  arraylatlong[i],
							// radius: poiRayon[i]*1
						// };
						// Add the circle for this city to the map.
						cityCircle = new L.circle(arraylatlong[i],{radius:poiRayon[i]*1}).addTo(map);
						arrayCityCircle.push(cityCircle);
						i++;
					}
					map.fitBounds(bounds);
					//}
					document.body.className = "";
					//visualiserClique();

				}
			}
		});

}

//function afficheWarning(idPoi){
//	var idTracker = document.getElementById("idBalise").innerHTML;
//	var nomDatabaseGpw = document.getElementById('nomDatabaseGpw').innerHTML;
//	var ipDatabaseGpw = globalIpDatabaseGpw;
//}
//	if(idTracker) {
//		$.ajax({
//			url: 'pointinteretwarning.php',
//			type: 'GET',
//			data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&idDatabaseGpw=" + idDatabaseGpw + "&idPoi=" + idPoi,
//
//			success: function (response) {
//				if (response) {
//					var destMethod = response.substring(response.indexOf('Dest_Method') + 12, response.indexOf('Warning_Type'));
//					var warningType = response.substring(response.indexOf('Warning_Type') + 13, response.indexOf('Msg_app'));
//					var msgApp = response.substring(response.indexOf('Msg_app') + 8, response.indexOf('Msg_disp'));
//					var msgDisp = response.substring(response.indexOf('Msg_disp') + 9);
//
//					document.getElementById('message_arrivee').value = msgApp;
//					document.getElementById('message_depart').value = msgDisp;
//
//					if (warningType == "1") {
//						document.getElementById('alerte_active_desactive').innerHTML = "<b>&nbsp;3) Message activ&eacute;</b>";
//						document.getElementById('alerte_active_desactive').style.backgroundColor = '#00FF00';
//						document.getElementById('contenu_alerte_active_desactive').style.display = "";
//
//						document.getElementById('message_arrivee').style.backgroundColor = "#00FF00";
//						document.getElementById('message_depart').style.backgroundColor = "#00FF00";
//						document.getElementById('checkbox_alert_message_desactive').checked = true;
//
//						checkArriveeDepart(destMethod);
//
//					} else {
//						document.getElementById('alerte_active_desactive').innerHTML = "<b>&nbsp;3) Message d&eacute;sactiv&eacute;</b>";
//						document.getElementById('alerte_active_desactive').style.backgroundColor = '';
//						document.getElementById('contenu_alerte_active_desactive').style.display = "none";
//						document.getElementById('message_arrivee').style.backgroundColor = "";
//						document.getElementById('message_depart').style.backgroundColor = "";
//						document.getElementById('checkbox_alert_message_desactive').checked = false;
//
//						checkArriveeDepart(destMethod);
//					}
//
//
//				}
//			}
//		});
//}

var rememberIdPoi;
var rememberNamePoi;
var rememberIdTrackerPoi;
var rememberNameTrackerPoi;
function afficheInfobullTablePOI(id,idPoi,userConfig){
	this.id=id;
	$('tr').children('td').removeClass('active');
	$(this.id).children('td').addClass('active');

	var tabNamePOI;
	var tabDescription;
	var tabAddress;
	var arrayColonnes = this.id.cells;

	tabNamePOI=arrayColonnes[1].innerHTML;
	tabDescription=arrayColonnes[3].innerHTML;
	tabAddress=arrayColonnes[2].innerHTML;
	tabLatitude=arrayColonnes[4].innerHTML;
	tabLongitude=arrayColonnes[5].innerHTML;
	tabRayon=arrayColonnes[6].innerHTML;

	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var idDatabaseGpw = globalIdDatabaseGpw;
	document.body.className = "loading";
	if(idTracker) {
		if(userConfig == "WEB_UTILISATEUR" || userConfig == "WEB_UTILISATEUR_NI"){
			afficheMarkerPOI(idPoi,tabNamePOI,tabDescription,tabAddress,tabLatitude,tabLongitude,tabRayon);
		}else{
			afficheMarkerPOI(idPoi,tabNamePOI,tabDescription,tabAddress,tabLatitude,tabLongitude,tabRayon);
			$.ajax({
				url: '../pointinteret/pointinteretwarning.php',
				type: 'GET',
				data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&idDatabaseGpw=" + idDatabaseGpw + "&idPoi=" + idPoi,

				success: function (response) {
					if (response) {
						var destMethod = response.substring(response.indexOf('Dest_Method') + 12, response.indexOf('Warning_Type'));
						var warningType = response.substring(response.indexOf('Warning_Type') + 13, response.indexOf('Msg_app'));
						var msgApp = response.substring(response.indexOf('Msg_app') + 8, response.indexOf('Msg_disp'));
						var msgDisp = response.substring(response.indexOf('Msg_disp') + 9);

						document.getElementById('message_arrivee').value = msgApp;
						document.getElementById('message_depart').value = msgDisp;

						if (warningType == "1") {
							document.getElementById('alerte_active_desactive').innerHTML = "<b>&nbsp;3) "+geTextMessageActive+"</b>";
							document.getElementById('alerte_active_desactive').style.backgroundColor = '#00FF00';
							document.getElementById('contenu_alerte_active_desactive').style.display = "";

							document.getElementById('message_arrivee').style.backgroundColor = "#00FF00";
							document.getElementById('message_depart').style.backgroundColor = "#00FF00";
							document.getElementById('checkbox_alert_message_desactive').checked = true;

							checkArriveeDepart(destMethod);

						} else {
							document.getElementById('alerte_active_desactive').innerHTML = "<b>&nbsp;3) "+getTextMessageDesactive+"</b>";
							document.getElementById('alerte_active_desactive').style.backgroundColor = '';
							document.getElementById('contenu_alerte_active_desactive').style.display = "none";
							document.getElementById('message_arrivee').style.backgroundColor = "";
							document.getElementById('message_depart').style.backgroundColor = "";
							document.getElementById('checkbox_alert_message_desactive').checked = false;

							checkArriveeDepart(destMethod);
						}

						rememberIdPoi = idPoi;
						rememberNamePoi = tabNamePOI;

						document.body.className = "";
					}
				}
			});
		}
	}
}

function afficheInfobullTablePOI2(id,idPoi,userConfig){
	this.id=id;
	$('tr').children('td').removeClass('active');
	$(this.id).children('td').addClass('active');

	var tabNamePOI;
	var tabDescription;
	var tabAddress;
	var arrayColonnes = this.id.cells;

	var tabNameTrackerPOI=arrayColonnes[0].innerHTML;
	tabNamePOI=arrayColonnes[2].innerHTML;
	tabDescription=arrayColonnes[4].innerHTML;
	tabAddress=arrayColonnes[3].innerHTML;
	var tabLatitude=arrayColonnes[5].innerHTML;
	var tabLongitude=arrayColonnes[6].innerHTML;
	var tabRayon=arrayColonnes[7].innerHTML;
	var tabIdTracker=arrayColonnes[8].innerHTML;

	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var idDatabaseGpw = globalIdDatabaseGpw;
	document.body.className = "loading";


	if(idTracker) {
		if(userConfig == "WEB_UTILISATEUR" || userConfig == "WEB_UTILISATEUR_NI"){
			afficheMarkerPOI(idPoi,tabNamePOI,tabDescription,tabAddress,tabLatitude,tabLongitude,tabRayon);
		}else{
			afficheMarkerPOI(idPoi,tabNamePOI,tabDescription,tabAddress,tabLatitude,tabLongitude,tabRayon);
			$.ajax({
				url: '../pointinteret/pointinteretwarning.php',
				type: 'GET',
				data: "idTracker=" + tabIdTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&idDatabaseGpw=" + idDatabaseGpw + "&idPoi=" + idPoi,

				success: function (response) {
					if (response) {
						var destMethod = response.substring(response.indexOf('Dest_Method') + 12, response.indexOf('Warning_Type'));
						var warningType = response.substring(response.indexOf('Warning_Type') + 13, response.indexOf('Msg_app'));
						var msgApp = response.substring(response.indexOf('Msg_app') + 8, response.indexOf('Msg_disp'));
						var msgDisp = response.substring(response.indexOf('Msg_disp') + 9);

						document.getElementById('message_arrivee').value = msgApp;
						document.getElementById('message_depart').value = msgDisp;

						if (warningType == "1") {
							document.getElementById('alerte_active_desactive').innerHTML = "<b>&nbsp;3) "+geTextMessageActive+"</b>";
							document.getElementById('alerte_active_desactive').style.backgroundColor = '#00FF00';
							document.getElementById('contenu_alerte_active_desactive').style.display = "";

							document.getElementById('message_arrivee').style.backgroundColor = "#00FF00";
							document.getElementById('message_depart').style.backgroundColor = "#00FF00";
							document.getElementById('checkbox_alert_message_desactive').checked = true;

							checkArriveeDepart(destMethod);

						} else {
							document.getElementById('alerte_active_desactive').innerHTML = "<b>&nbsp;3) "+getTextMessageDesactive+"</b>";
							document.getElementById('alerte_active_desactive').style.backgroundColor = '';
							document.getElementById('contenu_alerte_active_desactive').style.display = "none";
							document.getElementById('message_arrivee').style.backgroundColor = "";
							document.getElementById('message_depart').style.backgroundColor = "";
							document.getElementById('checkbox_alert_message_desactive').checked = false;

							checkArriveeDepart(destMethod);
						}

						rememberIdPoi = idPoi;
						rememberNamePoi = tabNamePOI;
						rememberIdTrackerPoi = tabIdTracker;
						rememberNameTrackerPoi = tabNameTrackerPOI;

						document.body.className = "";
					}
				}
			});
		}
	}

}
function afficheMarkerPOI(idPoi,tabNamePOI, tabDescription,tabAddress,lat,lng,rayon){

	if(configPtInteret == "user") {
		document.getElementById("tr_3eme_etape").style.display = "none";
		document.getElementById("tr_message_arrivee").style.display = "none";
		document.getElementById("tr_message_depart").style.display = "none";
		document.getElementById("tr_validation_poi").style.display = "none";
	}else{
		document.getElementById("tr_3eme_etape").style.display = "";
		document.getElementById("tr_message_arrivee").style.display = "";
		document.getElementById("tr_message_depart").style.display = "";
		document.getElementById("tr_validation_poi").style.display = "";
	}
	latlng = new L.LatLng(lat, lng);
	showAlertPOI();
	var imageMarker= new L.icon({iconUrl:poi1.src});
	var marker = new L.FeatureGroup();
	var html = "<table><tr><td><b>POI: "+tabNamePOI+"</b></td></tr><tr><td>"+tabAddress+"</td></tr>"+
				"<tr><td></td><td><input type='button' class='btn btn-default btn-xs dropdown-toggle' value='Supprimer POI' onclick=\"supprimerPOI('"+idPoi+"', '"+tabNamePOI+"')\"/></td></tr></table>";

	marker.addLayer(new L.marker(latlng).bindPopup(html));
	map.addLayer(marker);
	
	arrayLatLngPOI.push(latlng);
	arrayMarkersPOI.push(marker);

	// var populationOptions = {
		// strokeColor: '#FF0000',
		// strokeOpacity: 0.8,
		// strokeWeight: 2,
		// fillColor: '#FF0000',
		// fillOpacity: 0.35,
		// map: map,
		// center: latlng,
		// radius: rayon * 1
	// };
	
	cityCircle = new L.circle(latlng,{radius:rayon*1}).addTo(map);
	
	arrayCityCircle.push(cityCircle);

	map.panTo(latlng);
	//document.body.className = "";
}


function afficheTablePositionPOI(){
	var xmlhttp = null;

	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	document.getElementById("idTablePositionPOI").innerHTML = '<tr><th width="150px">'+getTextNomBalise+'</th><th  width="50">ID Poi</th><th width="150px">'+getTextNomPoi+'</th><th width="350px">'+getTextAdresse+'</th><th width="200px">Description</th>' +
			'<th width="100px" style="display:none" >Latitude</th>' +
			'<th width="100px" style="display:none">Longitude</th><th width="100px">'+getTextRayon+'</th><th style="display:none">ID Balise</th></tr>';
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nameTracker = document.getElementById("nomBalise").innerHTML;
	//if(idTracker.search(/,/) != -1) {
	//	var regIdTracker = new RegExp("[,]+", "g");
	//	var tableauIdTracker = idTracker.split(regIdTracker);
	$.ajax({
		url: '../pointinteret/pointinterettablepospoi.php',
		type: 'GET',
		data: "nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,
		async: false,
		success: function (response) {
			if (response) {
				document.getElementById("idTablePositionPOI").innerHTML += response;
				document.body.className = "";
			}
		}
	});
	//if(window.XMLHttpRequest){
	//	xmlhttp=new XMLHttpRequest();
	//}else{
	//	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	//}
	//xmlhttp.onreadystatechange=function(){
	//	if (xmlhttp.readyState==4 && xmlhttp.status==200){
	//		document.getElementById("idTablePositionPOI").innerHTML+=xmlhttp.responseText;
	//	}
	//}
	//xmlhttp.open("GET","pointinterettablepospoi.php?nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,false);
	//xmlhttp.send();
}

function supprimerPOI(idPoi,name){
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var answer = confirm(getTextSupprimerPOI+"?");
	var idTracker = document.getElementById("idBalise").innerHTML;
	if (answer){
		$.ajax({
			url : '../pointinteret/pointinteretdeletemarqueur.php',
			type : 'GET',
			data : "idPoi=" + idPoi + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw,

			success: function () {

				supprimerWarningPoi(idPoi);

				//map.setView(new L.LatLng(47.081012,2.398782),6);
				//initCartoPtInteret();
				resetall();

			}

		})

		//infowindow.close();


	}else{
		//infowindow.close();
	}

}
function supprimerWarningPoi(idPoi){
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	$.ajax({
		url : '../pointinteret/pointinteretdeletewarning.php',
		type : 'GET',
		data : "idPoi=" + idPoi + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw,
		async : false
	})
}

function addWarningPoi(idPoi){
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var idTracker = document.getElementById("idBalise").innerHTML;
	if(idTracker.search(/,/) != -1) {
		var regIdTracker = new RegExp("[,]+", "g");
		var tableauIdTracker = idTracker.split(regIdTracker);
		for (var i = 0; i < tableauIdTracker.length; i++) {
			saveWarning(idPoi, tableauIdTracker[i], nomDatabaseGpw, ipDatabaseGpw);
		}
	}else{
		saveWarning(idPoi, idTracker, nomDatabaseGpw, ipDatabaseGpw);
	}
}

function showMarkerPoiTracker(idTracker){
	//if(rememberOngletCartoPosition == "") {
	if(rememberOngletPointInteret == "") {
		ClearPOImarkers();
	}
	//var idTracker = document.getElementById("idBalise").innerHTML;
	var nameTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var imageMarker = new L.icon({iconUrl:poi1.src});
	var bounds = new L.latLngBounds();

	//if(idTracker.search(/,/) != -1) {
	//	alert("Veuillez séléctionner qu'une balise");
	//	document.body.className = "";
	//	baliseUnSelectAll();
	//	getPtInteretMarqueur();
	//}else {
		$.ajax({
			url: '../pointinteret/pointinteretgetmarkerpoitracker.php',
			type: 'GET',
			data: "nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&idTracker=" + idTracker,

			success: function (response) {
				if (response) {
					document.body.className = "loading";
					var chaine = response;
					var reg = new RegExp("[&]+", "g");
					var tableau = chaine.split(reg);

					var numeroZonePoi = new Array();

					var nbreLigne = tableau[0].substring(tableau[0].indexOf('t') + 1, tableau[0].indexOf('g'));


					for (var i = 0; i < nbreLigne; i++) {
						numeroZonePoi[i] = tableau[i].substring(tableau[i].indexOf('Numero_Zone') + 12);
						$.ajax({
							url: '../pointinteret/pointinteretshowmarkerpoitracker.php',
							type: 'GET',
							data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroZonePoi=" + numeroZonePoi[i],

							success: function (response) {
								if (response) {
									document.body.className = "loading";
									var arraylatlong = [];
									var idPoi = [];
									var poiLat = [];
									var poiLong = [];
									var poiName = [];
									var poiAdresse = [];
									var poiRayon = [];
									var nbreLigne2 = response.substring(response.indexOf('t') + 1, response.indexOf('g'));

									for (i = 0; i < nbreLigne2; i++) {
										idPoi[i] = response.substring(response.indexOf('idPoi') + 6, response.indexOf('Latitude'));
										poiLat = response.substring(response.indexOf('Latitude') + 9, response.indexOf('Longitude'));
										poiLong = response.substring(response.indexOf('Longitude') + 10, response.indexOf('Name'));
										poiName[i] = response.substring(response.indexOf('Name') + 5, response.indexOf('Adresse'));
										poiAdresse[i] = response.substring(response.indexOf('Adresse') + 8, response.indexOf('Rayon'));
										poiRayon[i] = response.substring(response.indexOf('Rayon') + 6);

										//Insertion du marqueur selon la latitude et longitude du Tracker
										var latlngtab = new L.LatLng(poiLat, poiLong);
										arraylatlong[i] = latlngtab;
										//arraylatlong[i] = new L.LatLng(poiLat, poiLong);

										
										//bounds.extend(latlngtab);
										var html = "<table><tr><td>POI: <b>" + poiName[i] +
													"</b></td></tr><tr><td>" + poiAdresse[i] +
													"</td></tr><tr><td></td>";
													
										if(rememberOngletPointInteret == "yes") {
											 html += "<td><input type='button' class='btn btn-default btn-xs dropdown-toggle' value='Supprimer POI' onclick=\"supprimerPOI('" + idPoi[i] + "', '" + poiName[i] + "')\"/></td></tr></table>";
											
										}else{
											 html += "<td></td></tr></table>";
										}
										
										var marker = new L.FeatureGroup();
										marker.addLayer(new L.marker([poiLat, poiLong]).bindPopup(html));
										map.addLayer(marker);
										
										//rememberIdPoi = idPoi[i];
										//afficheWarning(idPoi[i]);

										arrayLatLngPOI.push(latlngtab);
										arrayMarkersPOI.push(marker);
										//arrayLatLngPOI.push(new L.LatLng(poiLat, poiLong));
									}

									i = 0;
									for (var city in poiRayon) {
										
										cityCircle = new L.circle(arraylatlong[i], {radius: poiRayon[i] * 1}).addTo(map);
										arrayCityCircle.push(cityCircle);

										i++;
									}
									
									if(rememberOngletPointInteret == "yes")
										SetZoom();
									else
										document.body.className = "";
								}
							}
						});
					}
				}else{
					document.body.className = "";
					//alert("Il n'y a pas de POI pour la balise "+nameTracker);
					if(rememberOngletPointInteret == "yes")
					{
						document.getElementById("tr_2eme_etape").style.display = "";
						document.getElementById("tr_3eme_etape").style.display = "none";
						document.getElementById("tr_message_arrivee").style.display = "none";
						document.getElementById("tr_message_depart").style.display = "none";
						document.getElementById("tr_validation_poi").style.display = "none";
					}
				}
			}
		});
	//}

}
function isInArray(value, array) {
	return array.indexOf(value) > -1;
}
var arrayNumeroPOi = new Array();

function showTablePositionPoiTracker(idTracker,nomTracker){
	arrayNumeroPOi = [];
	//var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;


	//	afficheTablePositionPOI();
	//}else{
		$.ajax({
			url: '../pointinteret/pointinteretgetmarkerpoitracker.php',
			type: 'GET',
			data: "nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&idTracker=" + idTracker,
			success: function (response) {
				if (response) {
					var chaine = response;
					var reg = new RegExp("[&]+", "g");
					var tableau = chaine.split(reg);

					var numeroZonePoi = new Array();

					var nbreLigne = tableau[0].substring(tableau[0].indexOf('t') + 1, tableau[0].indexOf('g'));


					var i = 0;
					for (i = 0; i < nbreLigne; i++) {
						numeroZonePoi[i] = tableau[i].substring(tableau[i].indexOf('Numero_Zone') + 12);
						//alert(arrayNumeroPOi);
						if(isInArray(numeroZonePoi[i]+idTracker, arrayNumeroPOi) ==  false){

							arrayNumeroPOi.push(numeroZonePoi[i]+idTracker);
							$.ajax({
								url: '../pointinteret/pointinteretshowtablepospoitracker.php',
								type: 'GET',
								data: "idTracker=" + idTracker + "&nomTracker=" + nomTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroZonePoi=" + numeroZonePoi[i],
								//async: false,
								success: function (response) {
									if (response) {

										document.getElementById("idTablePositionPOI").innerHTML += response;
										document.body.className = "";

									}
								}
							});
						}
					}


				}else{
					document.getElementById("idTablePositionPOI").innerHTML = '<tr><th width="150px">'+getTextNomBalise+'</th><th  width="50">ID Poi</th><th width="150px">'+getTextNomPoi+'</th><th width="350px">'+getTextAdresse+'</th><th width="200px">Description</th>' +
						'<th width="100px" style="display:none" >Latitude</th>' +
						'<th width="100px" style="display:none">Longitude</th><th width="100px">'+getTextRayon+'</th><th style="display:none">ID Balise</th></tr>';
				}
			}
		});
	//}
}

function showAlertPOI(){

	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	$.ajax({
		url: '../pointinteret/pointinteretshowalertpoi.php',
		type: 'GET',
		data: "nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&idTracker="+idTracker,
		success: function (response) {
			if (response) {
				var chaine=response;
				var reg=new RegExp("[&]+", "g");
				var tableau=chaine.split(reg);

				var dest01 = tableau[0].substring(tableau[0].indexOf('dest01')+7,tableau[0].indexOf('dest02'));

				var dest02 = tableau[0].substring(tableau[0].indexOf('dest02')+7,tableau[0].indexOf('dest03'));
				var dest03 = tableau[0].substring(tableau[0].indexOf('dest03')+7,tableau[0].indexOf('dest04'));
				var dest04 = tableau[0].substring(tableau[0].indexOf('dest04')+7);

				if(dest01) document.getElementById("message_numero_1").value = dest01;
				if(dest02) document.getElementById("message_numero_2").value = dest02;
				if(dest03) document.getElementById("message_numero_3").value = dest03;
				if(dest04) document.getElementById("message_numero_4").value = dest04;
			}
		}
	});
}


function validAlertPoi(){

	var idTracker = document.getElementById("idBalise").innerHTML;
	var nameTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	//alert(rememberIdTrackerPoi);
	if(rememberNamePoi){
		if (confirm(getTextModifierConfigurationPOI+" : "+rememberNamePoi+" : "+rememberNameTrackerPoi)) {
			var messageArrivee = document.getElementById("message_arrivee").value;
			var messageDepart = document.getElementById("message_depart").value;
			if(messageArrivee == "" && messageDepart == "") {
				alert(getTextSaisirMessageArriveeDepart);
			}else {
				saveWarningDest(rememberIdTrackerPoi, nomDatabaseGpw, ipDatabaseGpw);
				saveWarning(rememberIdPoi, rememberIdTrackerPoi, nomDatabaseGpw, ipDatabaseGpw);
				alert($('<div />').html( getTextconfirmValiderConfigPoi+" "+rememberNamePoi+""+getTextPourLaBalise+" "+nameTracker).text());
			}
		}
	}else{
		alert(getTextVeuillezChoisirUnpoi);
	}
}

function saveWarningDest(idTracker, nomDatabaseGpw, ipDatabaseGpw){
	var numero1 = document.getElementById('message_numero_1').value;
	var numero2 = document.getElementById('message_numero_2').value;
	var numero3 = document.getElementById('message_numero_3').value;
	var numero4 = document.getElementById('message_numero_4').value;

	$.ajax({
		url : '../pointinteret/pointinteretsavewarningdest.php',
		type : 'GET',
		data : "idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&numero1="+numero1+"&numero2="+numero2+"&numero3="+numero3+"&numero4="+numero4,

		success: function(response) {
			if(response){
			}
		}
	});
}


function saveWarning(idPoi, idTracker, nomDatabaseGpw, ipDatabaseGpw){

	var messageArrivee = document.getElementById("message_arrivee").value;
	var messageDepart = document.getElementById("message_depart").value;

	var warningType;

	var destMethodArray = new Array();
	var destMethod;

	if(document.getElementById('depart_numero_1').checked == true) destMethodArray[3] = "1";
	if(document.getElementById('depart_numero_1').checked == false)destMethodArray[3] = "0";
	if(document.getElementById('depart_numero_2').checked == true) destMethodArray[2] = "1";
	if(document.getElementById('depart_numero_2').checked == false)destMethodArray[2] = "0";
	if(document.getElementById('depart_numero_3').checked == true) destMethodArray[1] = "1";
	if(document.getElementById('depart_numero_3').checked == false)destMethodArray[1] = "0";
	if(document.getElementById('depart_numero_4').checked == true)	destMethodArray[0] = "1";
	if(document.getElementById('depart_numero_4').checked == false)destMethodArray[0] = "0";

	if(document.getElementById('arrivee_numero_1').checked == true)	destMethodArray[7] = "1";
	if(document.getElementById('arrivee_numero_1').checked == false) destMethodArray[7] = "0";
	if(document.getElementById('arrivee_numero_2').checked == true)	destMethodArray[6] = "1";
	if(document.getElementById('arrivee_numero_2').checked == false) destMethodArray[6] = "0";
	if(document.getElementById('arrivee_numero_3').checked == true)	destMethodArray[5] = "1";
	if(document.getElementById('arrivee_numero_3').checked == false) destMethodArray[5] = "0";
	if(document.getElementById('arrivee_numero_4').checked == true)	destMethodArray[4] = "1";
	if(document.getElementById('arrivee_numero_4').checked == false) destMethodArray[4] = "0";

	destMethod = binaryToDecimal(destMethodArray);

	if(document.getElementById('checkbox_alert_message_desactive').checked == true) warningType= "1";
	if(document.getElementById('checkbox_alert_message_desactive').checked == false) warningType= "0";

	if(destMethod == "0")  warningType= "0";

	var date = new Date();

	$.ajax({
		url: '../pointinteret/pointinteretsavewarning.php',
		type: 'GET',
		data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&idPoi=" + idPoi +
		"&messageArrivee=" + messageArrivee + "&messageDepart=" + messageDepart + "&destMethod=" + destMethod + "&warningType=" + warningType
		+ "&warningLap=" + date.getTimezoneOffset(),
		async: false,
		success: function (response) {

		}
	});

}

function checkArriveeDepart(dec){

	var decimalEncode = decimaltoBinary(dec);

	if(decimalEncode[0] == "0")  document.getElementById('depart_numero_4').checked = false;
	if(decimalEncode[0] == "1")  document.getElementById('depart_numero_4').checked = true;
	if(decimalEncode[1] == "0")  document.getElementById('depart_numero_3').checked = false;
	if(decimalEncode[1] == "1")  document.getElementById('depart_numero_3').checked = true;
	if(decimalEncode[2] == "0")  document.getElementById('depart_numero_2').checked = false;
	if(decimalEncode[2] == "1")  document.getElementById('depart_numero_2').checked = true;
	if(decimalEncode[3] == "0")  document.getElementById('depart_numero_1').checked = false;
	if(decimalEncode[3] == "1")  document.getElementById('depart_numero_1').checked = true;

	if(decimalEncode[4] == "0")  document.getElementById('arrivee_numero_4').checked = false;
	if(decimalEncode[4] == "1")  document.getElementById('arrivee_numero_4').checked = true;
	if(decimalEncode[5] == "0")  document.getElementById('arrivee_numero_3').checked = false;
	if(decimalEncode[5] == "1")  document.getElementById('arrivee_numero_3').checked = true;
	if(decimalEncode[6] == "0")  document.getElementById('arrivee_numero_2').checked = false;
	if(decimalEncode[6] == "1")  document.getElementById('arrivee_numero_2').checked = true;
	if(decimalEncode[7] == "0")  document.getElementById('arrivee_numero_1').checked = false;
	if(decimalEncode[7] == "1")  document.getElementById('arrivee_numero_1').checked = true;

	if( (document.getElementById('arrivee_numero_1').checked == true) || (document.getElementById('depart_numero_1').checked == true) ){
		document.getElementById('message_numero_1').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('message_numero_1').style.backgroundColor = "";
	}
	if( (document.getElementById('arrivee_numero_2').checked == true) || (document.getElementById('depart_numero_2').checked == true) ){
		document.getElementById('message_numero_2').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('message_numero_2').style.backgroundColor = "";
	}
	if( (document.getElementById('arrivee_numero_3').checked == true) || (document.getElementById('depart_numero_3').checked == true) ){
		document.getElementById('message_numero_3').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('message_numero_3').style.backgroundColor = "";
	}
	if( (document.getElementById('arrivee_numero_4').checked == true) || (document.getElementById('depart_numero_4').checked == true) ){
		document.getElementById('message_numero_4').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('message_numero_4').style.backgroundColor = "";
	}

}

function onCheckNumeroArriveeDepart(numero){
	switch(numero){
		case 1:
			if(document.getElementById('message_numero_1').value) {
				if (document.getElementById('arrivee_numero_1').checked || document.getElementById('depart_numero_1').checked) {
					document.getElementById('message_numero_1').style.backgroundColor = "#00FF00";
				} else {
					document.getElementById('message_numero_1').style.backgroundColor = "";
				}
			}else{
				alert($('<div />').html( getTextAlertTel1PasEnregistrer).text());
				document.getElementById('arrivee_numero_1').checked = false;
				document.getElementById('depart_numero_1').checked = false;
			}
			break;
		case 2:
			if(document.getElementById('message_numero_2').value) {
				if (document.getElementById('arrivee_numero_2').checked || document.getElementById('depart_numero_2').checked) {
					document.getElementById('message_numero_2').style.backgroundColor = "#00FF00";
				} else {
					document.getElementById('message_numero_2').style.backgroundColor = "";
				}
			}else{
				alert($('<div />').html( getTextAlertTel2PasEnregistrer).text());
				document.getElementById('arrivee_numero_2').checked = false;
				document.getElementById('depart_numero_2').checked = false;
			}
			break;
		case 3:
			if(document.getElementById('message_numero_3').value) {
				if (document.getElementById('arrivee_numero_3').checked || document.getElementById('depart_numero_3').checked) {
					document.getElementById('message_numero_3').style.backgroundColor = "#00FF00";
				} else {
					document.getElementById('message_numero_3').style.backgroundColor = "";
				}
			}else{
				alert($('<div />').html( getTextAlertTel3PasEnregistrer).text());
				document.getElementById('arrivee_numero_3').checked = false;
				document.getElementById('depart_numero_3').checked = false;
			}
			break;
		case 4:
			if(document.getElementById('message_numero_4').value) {
				if (document.getElementById('arrivee_numero_4').checked || document.getElementById('depart_numero_4').checked) {
					document.getElementById('message_numero_4').style.backgroundColor = "#00FF00";
				} else {
					document.getElementById('message_numero_4').style.backgroundColor = "";
				}
			}else{
				alert($('<div />').html( getTextAlertTel4PasEnregistrer).text());
				document.getElementById('arrivee_numero_4').checked = false;
				document.getElementById('depart_numero_4').checked = false;
			}
			break;
	}
}


function onCheckMessageActiveDesactivePOI(obj){

	var div = document.getElementById( 'alerte_active_desactive' );


		if (obj.checked) {
			document.getElementById('alerte_active_desactive').innerHTML = "<b>&nbsp;3) "+geTextMessageActive+"</b>";
			div.style.backgroundColor = '#00FF00';
			document.getElementById('contenu_alerte_active_desactive').style.display = "";
			document.getElementById('message_arrivee').style.backgroundColor = "#00FF00";
			document.getElementById('message_depart').style.backgroundColor = "#00FF00";

		} else {
			document.getElementById('alerte_active_desactive').innerHTML = "<b>&nbsp;3) "+getTextMessageDesactive+"</b>";
			div.style.backgroundColor = '';
			document.getElementById('contenu_alerte_active_desactive').style.display = "none";
			document.getElementById('message_arrivee').style.backgroundColor = "";
			document.getElementById('message_depart').style.backgroundColor = "";
		}

}

function ClearPOImarkers()
{
	var i;
	
	for(i = arrayMarkersPOI.length-1; i>=0; i--)
	{
		map.removeLayer(arrayMarkersPOI[i]);
		arrayMarkersPOI.pop();
	}
	
	for(i = arrayLatLngPOI.length-1; i>=0; i--)
		arrayLatLngPOI.pop();
	
	for(i = arrayCityCircle.length-1; i>=0; i--)
	{
		map.removeLayer(arrayCityCircle[i]);
		arrayCityCircle.pop();
	}
}