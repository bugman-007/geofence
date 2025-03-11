function dataEtatBalise(Id_Tracker,nomBalise,multipleTracker){
	
	//var xmlhttp = null;

	if(!Id_Tracker){
		var Id_Tracker =document.getElementById("idBalise").innerHTML;
	}
	
	if (Id_Tracker==""){
		return;
	}else if(Id_Tracker.search(/,/) != -1){
		return;
	}else{
		// debut ajax qui récupère les config ALARMES SERVEUR
		$.ajax({
			url: '../etatbalise/etatbalisewarning.php',
			type: 'GET',
			data: "idTracker=" + Id_Tracker + "&nomDatabaseGpw=" + globalnomDatabaseGpw + "&ipDatabaseGpw=" + globalIpDatabaseGpw + "&idDatabaseGpw=" + globalIdDatabaseGpw,

			success: function (response2) {
				var stringAlarmActiveServeur = "";
				var Separateur = "";
				
				if (response2) {
					var reg = new RegExp("[&]+", "g");
					var tableau = response2.split(reg);
					var typeGeometrie = [], numeroZone = [];

					var nbreLigne = tableau[0].substring(tableau[0].indexOf('t') + 1, tableau[0].indexOf('g'));
					if (nbreLigne) {
						for (var i = 0; i < nbreLigne; i++) {
							typeGeometrie[i] = tableau[i].substring(tableau[i].indexOf('Type_Geometrie') + 15, tableau[i].indexOf('Numero_Zone'));
							numeroZone[i] = tableau[i].substring(tableau[i].indexOf('Numero_Zone') + 12);

							if (typeGeometrie[i] == "3")		// Type Geofencing
							{
								stringAlarmActiveServeur += Separateur+"Geof. polygonal zone "+ numeroZone[i];	 // traduction manquante
								//Separateur = " - ";
								Separateur = "<br>";
							}
							else if (typeGeometrie[i] == "4")	// Type POI
							{
								stringAlarmActiveServeur += Separateur+"Alerte POI "+ numeroZone[i];	 // traduction manquante
								Separateur = "<br>";
							}
							else if (typeGeometrie[i] == "5")
							{
								stringAlarmActiveServeur += Separateur+getTextAlarmBatterie;
								Separateur = "<br>";
							}
							else if (typeGeometrie[i] == "6")
							{
								stringAlarmActiveServeur += Separateur+getTextAlarmAlimentation;
								Separateur = "<br>";
							}
							else if (typeGeometrie[i] == "7")
							{
								stringAlarmActiveServeur += Separateur+getTextAlarmDeplacement;
								Separateur = "<br>";
							}
							else if (typeGeometrie[i] == "8")
							{
								if(( versionBaliseGlobal == "53" )||( versionBaliseGlobal == "3006" )||( versionBaliseGlobal == "3370" )||( versionBaliseGlobal == "8045" )||( versionBaliseGlobal == "8079" )||( versionBaliseGlobal == "7003" )||( versionBaliseGlobal == "7201" ))	// CUBE, NEO & SOLO
									stringAlarmActiveServeur += Separateur+"Alarme Arrachement";	 // traduction manquante
								else
									stringAlarmActiveServeur += Separateur+getTextAlarm+" 1";
								Separateur = "<br>";
							}
							else if (typeGeometrie[i] == "9")
							{
								stringAlarmActiveServeur += Separateur+getTextAlarm+" 2";
								Separateur = "<br>";
							}
						}
					}
				}
				
				if(stringAlarmActiveServeur == "")
					stringAlarmActiveServeur = getTextAucune;
				
				
				if( $.inArray(versionBaliseGlobal, ['20','3006','3370','8045','8079','3600','7003','7201','8000']) >= 0)		// TELTO , NEO, QBIT, SOLO & SOLAR
				{
					var stringconfig =	"<div class='panel panel-default'><div class='panel-body'><table>"+
						"<tr><td> * "+getTextAlarmesActivees+":<br><b>"+stringAlarmActiveServeur+"</b></td></tr>"+
						"</div></div>";
					
					document.getElementById('data_etat_balise').innerHTML = stringconfig;
					document.body.className = "";
				}
				else
				{
					var idTracker = document.getElementById("idBalise").innerHTML;
					
					$.ajax({
						url: '../configuration/configurationanalysedata.php',
						type: 'GET',
						data: "idTracker="+idTracker+"&nomDatabaseGpw="+globalnomDatabaseGpw+"&ipDatabaseGpw="+globalIpDatabaseGpw,
						async: true,
						success: function (response) {
							if (response) {
								var test = response;
								var reg=new RegExp("[&]+", "g");
								var tableau=test.split(reg);
								var saveGpsTrajet = tableau[0].substring(tableau[0].indexOf('<br> Save GPS trajet')+30,tableau[0].indexOf('<br> Save GPS arret'));
								var saveGpsArret = tableau[0].substring(tableau[0].indexOf('<br> Save GPS arret')+30,tableau[0].indexOf('<br> Mode Sleep'));
								var callTimeRealtime = tableau[0].substring(tableau[0].indexOf('<br> CALL TIME REALTIME')+32,tableau[0].indexOf('<br> TPS REBOOT GSM'));
								//var telAlarm = tableau[0].substring(tableau[0].indexOf('<br> Tel  Alarm')+27,tableau[0].indexOf('<br> Time RST'));
								var sendGprs = tableau[0].substring(tableau[0].indexOf('<br> SEND GPRS')+27,tableau[0].indexOf('<br> SEND GPRS VEILLE'));
								var sendGprsVeille = tableau[0].substring(tableau[0].indexOf('<br> SEND GPRS VEILLE')+32,tableau[0].indexOf('<br> DELAY 1'));

								var cfgAlaApc = tableau[0].substring(tableau[0].indexOf('<br> CFG ALA APC')+28,tableau[0].indexOf('<br> SMS ALA APC'));
								//var smsAlaApc = tableau[0].substring(tableau[0].indexOf('<br> SMS ALA APC')+28,tableau[0].indexOf('<br> CFG ALA BAT'));

								var cfgAlaBat = tableau[0].substring(tableau[0].indexOf('<br> CFG ALA BAT')+28,tableau[0].indexOf('<br> SMS ALA BAT'));
								//var smsAlaBat = tableau[0].substring(tableau[0].indexOf('<br> SMS ALA BAT')+28,tableau[0].indexOf('<br> CFG ALA ALIM'));

								var cfgAlaAlim = tableau[0].substring(tableau[0].indexOf('<br> CFG ALA ALIM')+28,tableau[0].indexOf('<br> SMS ALA ALIM'));
								//var smsAlaAlim = tableau[0].substring(tableau[0].indexOf('<br> SMS ALA ALIM')+28,tableau[0].indexOf('<br> SEUIL BATTERY'));

								var cfgAl1 = tableau[0].substring(tableau[0].indexOf('<br> CFG AL1')+25,tableau[0].indexOf('<br> OLD AL1'));
								//var oldAl1 = tableau[0].substring(tableau[0].indexOf('<br> OLD AL1')+25,tableau[0].indexOf('<br> SMS AL1'));
								//var smsAl1 = tableau[0].substring(tableau[0].indexOf('<br> SMS AL1')+25,tableau[0].indexOf('<br> AL1 TIME BETWEEN'));

								var cfgAl2 = tableau[0].substring(tableau[0].indexOf('<br> CFG AL2')+25,tableau[0].indexOf('<br> OLD AL2'));
								//var oldAl2 = tableau[0].substring(tableau[0].indexOf('<br> OLD AL2')+25,tableau[0].indexOf('<br> SMS AL2'));
								//var smsAl2 = tableau[0].substring(tableau[0].indexOf('<br> SMS AL2')+25,tableau[0].indexOf('<br> AL2 TIME BETWEEN'));

								var cfgModePark = tableau[0].substring(tableau[0].indexOf('<br> CFG MODE_PARK')+29,tableau[0].indexOf('<br> LAPS MODE_PARK'));

								var cfgGeo1 = tableau[0].substring(tableau[0].indexOf('<br> CFG GEO 1')+26,tableau[0].indexOf('<br> SMS GEO 1'));
								//var smsGeo1 = tableau[0].substring(tableau[0].indexOf('<br> SMS GEO 1')+26,tableau[0].indexOf('<br> CFG GEO 2'));

								var cfgGeo2 = tableau[0].substring(tableau[0].indexOf('<br> CFG GEO 2')+26,tableau[0].indexOf('<br> SMS GEO 2'));
								//var smsGeo2 = tableau[0].substring(tableau[0].indexOf('<br> SMS GEO 2')+26,tableau[0].indexOf('<br> CFG GEO 3'));

								var cfgGeo3 = tableau[0].substring(tableau[0].indexOf('<br> CFG GEO 3')+26,tableau[0].indexOf('<br> SMS GEO 3'));
								//var smsGeo3 = tableau[0].substring(tableau[0].indexOf('<br> SMS GEO 3')+26,tableau[0].indexOf('<br> CFG GEO 4'));

								var cfgGeo4 = tableau[0].substring(tableau[0].indexOf('<br> CFG GEO 4')+26,tableau[0].indexOf('<br> SMS GEO 4'));
								//var smsGeo4 = tableau[0].substring(tableau[0].indexOf('<br> SMS GEO 4')+26,tableau[0].indexOf('<br> CFG GEO 5'));

								var cfgGeo5 = tableau[0].substring(tableau[0].indexOf('<br> CFG GEO 5')+26,tableau[0].indexOf('<br> SMS GEO 5'));
								//var smsGeo5 = tableau[0].substring(tableau[0].indexOf('<br> SMS GEO 5')+26,tableau[0].indexOf('<br> MODE_RING'));

								var realtimeApc = tableau[0].substring(tableau[0].indexOf('<br> RealtimeAPC')+28);

								var modeDeFonctionnement = "NORMAL";
								var modeGprs = tableau[0].substring(tableau[0].indexOf('<br> Mode GPRS')+26,tableau[0].indexOf('<br> Mode GPS'));
								var modeGps = tableau[0].substring(tableau[0].indexOf('<br> Mode GPS')+25,tableau[0].indexOf('<br> Timeout GPS'));
								var modeGsm = tableau[0].substring(tableau[0].indexOf('<br> MODE GSM')+25,tableau[0].indexOf('<br> GSM - APC'));
								
								var APN = tableau[0].substring(tableau[0].indexOf('<br> APN')+22,tableau[0].indexOf('<br> LOGIN'));
								var LoginAPN = tableau[0].substring(tableau[0].indexOf('<br> LOGIN')+23,tableau[0].indexOf('<br> login'));
								var PasswordAPN = tableau[0].substring(tableau[0].indexOf('<br> login')+23,tableau[0].indexOf('<br> DNS'));
								
								var nMode;
								if(modeGps == "16"){
									if(modeGprs == "16"){
										if(modeGsm == "108"){
											modeDeFonctionnement = "PERISCOPE";
										}
									}
								}else if(modeGps == "5" || modeGps == "7"){
									if(modeGprs == "16"){
										if(modeGsm == "108"){
											modeDeFonctionnement = getTextHistorique;
										}
									}else{
										if(modeGprs == "8" || modeGprs == "10"){
											modeDeFonctionnement = "NORMAL";
											if(modeGsm == "0"){
												modeDeFonctionnement += ", GSM permanent";
											}
											if(modeGsm == "109"){
												modeDeFonctionnement += ", GSM ECO";
											}
											if( versionBaliseGlobal != "43" && versionBaliseGlobal != "45"){
												if(modeGsm == "108"){
													modeDeFonctionnement += ", GSM ECO +";
												}
											}
										}
									}
								}

								if(modeGsm == "16"){
									if( versionBaliseGlobal != "43" && versionBaliseGlobal != "45"){
										modeDeFonctionnement = getTextSilencieux;
									}
								}
								if(modeGps != "16" || modeGps != "5" || modeGprs != "16" || modeGprs != "8" || modeGprs != "10" || modeGsm == "0" || modeGsm == "16" || modeGsm == "108" || modeGsm == "109" ){
									// modeDeFonctionnement = "NORMAL";
								}
								
								// Detection du temps reel
								var stringRealTimeApc="";
								if(realtimeApc == "0"){
									stringRealTimeApc = "";
								}else if(realtimeApc == "1"){
									stringRealTimeApc = "<tr><td></td></tr><tr><td>"+getTextTempsReelSurDeplacementEstActive+"</td></tr>";
								}
								
								//Detection des ALARMES EMBARQUEE DANS BALISE
								var stringAlarmActive = "";
								Separateur = "";
								
								if( cfgAlaApc == "1")		{ stringAlarmActive = getTextAlarmDeplacement;									Separateur = "<br>";}
								if( cfgModePark == "1")		{ stringAlarmActive += Separateur+getTextAlarmParking;							Separateur = "<br>";}
								if( cfgAlaBat == "1")		{ stringAlarmActive += Separateur+getTextAlarmBatterie+" "+getTextFaible;		Separateur = "<br>";}
								if( cfgAlaAlim == "1")		{ stringAlarmActive += Separateur+getTextAlarmAlimentation+" "+getTextExterne;	Separateur = "<br>";}
								if( cfgAl1 == "1")			{ stringAlarmActive += Separateur+getTextAlarm+" 1";							Separateur = "<br>";}
								if( cfgAl2 == "1")			{ stringAlarmActive += Separateur+getTextAlarm+" 2";							Separateur = "<br>";}
								if( cfgGeo1 & 0x01)			{ stringAlarmActive += Separateur+"Geofencing zone 1";							Separateur = "<br>";}	 // traduction manquante
								if( cfgGeo2 & 0x01)			{ stringAlarmActive += Separateur+"Geofencing zone 2";							Separateur = "<br>";}	 // traduction manquante
								if( cfgGeo3 & 0x01)			{ stringAlarmActive += Separateur+"Geofencing zone 3";							Separateur = "<br>";}	 // traduction manquante
								if( cfgGeo4 & 0x01)			{ stringAlarmActive += Separateur+"Geofencing zone 4";							Separateur = "<br>";}	 // traduction manquante
								if( cfgGeo5 & 0x01)			{ stringAlarmActive += Separateur+"Geofencing zone 5";							Separateur = "<br>";}	 // traduction manquante
								if(stringAlarmActive == "")	{ stringAlarmActive = getTextAucune;}
								
								// AFFICHAGE DU RESUME DE LA CONFIGURATION
								var stringconfig =	"<div class='panel panel-default'><div class='panel-body'><table>"+
									"<tr><td> * "+getTextModeFonctionnement+": <b>"+modeDeFonctionnement+"</b></td></tr>"+
									"<tr><td> - "+getTextAcquisitionPos+": "+getTextEnTrajet+": <b>"+saveGpsTrajet+"</b> sec / "+getTextEnVeille+": <b>"+saveGpsArret+"</b> sec </td></tr>"+
									"<tr><td> - "+getTextRapatriementPos+": "+getTextEnTrajet+": <b>"+sendGprs+"</b> mn / "+getTextEnVeille+": <b>"+sendGprsVeille+"</b> mn </td></tr>"+stringRealTimeApc+
									"<tr><td> "+getTextTempsSurAppel+"<b>"+callTimeRealtime+"</b> mn </td></tr>"+
									"<tr><td>&nbsp;</td></tr>"+
								//	"<tr><td> * "+getTextAlarmesActivees+": <b>"+stringAlarmActive+"</b></td></tr>"+
									"<tr><td> * Alarmes balise activées:<br><b>"+stringAlarmActive+"</b></td></tr>"+	 		// traduction manquante
									"<tr><td>&nbsp;</td></tr>"+
									"<tr><td> * Alarmes serveur activées:<br><b>"+stringAlarmActiveServeur+"</b></td></tr>"+	// traduction manquante
									"<tr><td>&nbsp;</td></tr>"+
									"<tr><td>APN: <b>"+APN+"</b> / <b>"+LoginAPN+"</b> / <b>"+PasswordAPN+"</b></td></tr>"+
									"</div></div>";
								

								document.getElementById('data_etat_balise').innerHTML = stringconfig;
								document.body.className = "";
							}
						}
					});
				}
			}
		});
		// fin ajax qui récupère les config ALARMES SERVEUR
	}
}

/**********************************************************************************/
/***********************************infoTtracker************************************/
/**********************************************************************************/
function infoTtracker(Id_Tracker,nomBalise,multipleTracker){
	var xmlhttp = null;

	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	if(!Id_Tracker){
		var Id_Tracker =document.getElementById("idBalise").innerHTML;
		var nomBalise=document.getElementById('nomBalise').innerHTML;
	}
	var tz = jstz.determine();
    var timezone = tz.name();
	if (Id_Tracker==""){
		return;
	}else if(Id_Tracker.search(/,/) != -1){
		return;
	}else{
		$.ajax({
			url: '../etatbalise/etatbalisettracker.php',
			type: 'GET',
			data: "Id_Tracker="+Id_Tracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&timezone="+timezone,
			async: true,
			success: function (response) {
				if (response) {
					var chaine=response;
					var reg=new RegExp("[&]+", "g");
					var tableau=chaine.split(reg);


					var derniereSynchro =  tableau[0].substring(11);
					var numeroAppel = tableau[1].substring(12);
					
					// Mise en forme information derniereSynchro
					if( $.inArray(versionBaliseGlobal, ['20','3006','3370','8045','8079','3600','7003','7201','8000']) >= 0) {		// TELTO , NEO, QBIT, SOLO & SOLAR
						derniereSynchro = "";
					}else{
						if((derniereSynchro == "") || (derniereSynchro == " "))
							derniereSynchro = getTextNon + " " + getTextTrouve;		// traduction manquante
						derniereSynchro = getTextDerniereSynchro+": <b>"+derniereSynchro+"</b>";
					}
					
					// Mise en forme information numeroAppel
					if(numeroAppel == "") numeroAppel = getTextNon + " " + getTextTrouve;
					
					numeroAppel = getTextNumeroAppel+": <b>"+ numeroAppel+"</b>";
					
					
					// Affichage informations
					contenuSynchro =	"<div class='panel panel-default'><div class='panel-body'><table><tr><td>"+numeroAppel+"</td></tr>"+
					"<tr><td>"+derniereSynchro+"</td></tr></div></div>";
					document.getElementById('derniere_synchro_etat_balise').innerHTML = contenuSynchro;
				}
			}
		});
	}
}

/**********************************************************************************/
/***********************************infoTposition************************************/
/**********************************************************************************/
function infoTposition(Id_Tracker,nomBalise,multipleTracker){

	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	if(!Id_Tracker){
		var Id_Tracker =document.getElementById("idBalise").innerHTML;
		var nomBalise=document.getElementById('nomBalise').innerHTML;
	}
	
	var tz = jstz.determine(); 
    var timezone = tz.name();
	var nomVersionBalise;

	if (Id_Tracker==""){	
	
		document.getElementById('titre_etat_balise').innerHTML = getTextVeuillezChoisirUneBalise;
		document.getElementById('derniere_synchro_etat_balise').innerHTML = "<div class='panel panel-default'><div class='panel-body'></div> <br><br></div>";
		document.getElementById('derniere_position_etat_balise').innerHTML = "<div class='panel panel-default'><div class='panel-body' ></div> <br><br></div>";
		document.getElementById('data_etat_balise').innerHTML = "<div class='panel panel-default'><div class='panel-body'><br><br><br><br><br><br></div> </div>";
		return;															    
	}else if(Id_Tracker.search(/,/) != -1){
		
		document.getElementById('titre_etat_balise').innerHTML =  getTextVeuillezChoisirQueUneBalise;
		document.getElementById('derniere_synchro_etat_balise').innerHTML = "<div class='panel panel-default'><div class='panel-body'><br><br></div> </div>";
		document.getElementById('derniere_position_etat_balise').innerHTML = "<div class='panel panel-default'><div class='panel-body' ><br><br></div> </div>";
		document.getElementById('data_etat_balise').innerHTML = "<div class='panel panel-default'><div class='panel-body'><br><br></div> </div>";
		return
	}else{
		document.body.className = "loading";
		$.ajax({
			url: '../carto/cartolastaddmarker.php',
			type: 'GET',
			data: "Id_Tracker="+Id_Tracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&timezone="+timezone,
			async: true,
			success: function (response) {
				if (response) {
					var chaine = response;
					var reg=new RegExp("[&]+", "g");
					var tableau=chaine.split(reg);
					var coordDateTimePosition;
					var coordLat;
					var coordLong;
					var coordPosStatut
					var coordPosVitesse
					var coordPosOdometre;
					var coordPosAdresse;
					var Pos_Key;
					var Statut2;
					var BattInt;
					var BattExt;
					var Alim;
					var TypeServer;
					var DecodedStatus;
					
					coordDateTimePosition =  tableau[0].substring(tableau[0].indexOf('Pos_DateTime_position')+22,tableau[0].indexOf('Pos_Latitude'));
					coordLat = tableau[0].substring(tableau[0].indexOf('Pos_Latitude')+13,tableau[0].indexOf('Pos_Longitude'));
					coordLong = tableau[0].substring(tableau[0].indexOf('Pos_Longitude')+14,tableau[0].indexOf('Pos_Statut'));
                    coordPosStatut = tableau[0].substring(tableau[0].indexOf('Pos_Statut') + 11, tableau[0].indexOf('Pos_Vitesse'));
                    coordPosVitesse = Math.round(parseInt(tableau[0].substring(tableau[0].indexOf('Pos_Vitesse') + 12, tableau[0].indexOf('Pos_Direction'))));
					coordPosOdometre = tableau[0].substring(tableau[0].indexOf('Pos_Odometre')+13,tableau[0].indexOf('Pos_Adresse'));
					coordPosAdresse = tableau[0].substring(tableau[0].indexOf('Pos_Adresse')+12, tableau[0].indexOf('Pos_Key'));//
					Pos_Key = tableau[0].substring(tableau[0].indexOf('Pos_Key:') + 8, tableau[0].indexOf('Statut2:'));
					Statut2 = tableau[0].substring(tableau[0].indexOf('Statut2:') + 8, tableau[0].indexOf('BattInt:'));
					BattInt = tableau[0].substring(tableau[0].indexOf('BattInt:') + 8, tableau[0].indexOf('BattExt:'));
					BattExt = tableau[0].substring(tableau[0].indexOf('BattExt:') + 8, tableau[0].indexOf('Alim:'));
					Alim =  tableau[0].substring(tableau[0].indexOf('Alim:') + 5, tableau[0].indexOf('TypeServer:'));
					TypeServer = tableau[0].substring(tableau[0].indexOf('TypeServer:') + 11, tableau[0].indexOf('Icone:'));
					
					DecodedStatus = DecodeStatus(coordPosStatut, coordPosOdometre, coordPosVitesse, Pos_Key, Statut2, BattInt, BattExt, Alim, TypeServer, 3);
					
					nomVersionBalise=versionBalise(coordPosOdometre);
					
					contenuTitre =	"<table><tr><td><b>"+nomBalise+"</b> (Version: <b>"+nomVersionBalise+"</b>) Id = <b>"+ Id_Tracker+"</b></td></tr>";

					contenuDernierePos = "<div class='panel panel-default'><div class='panel-body'><table><tr><td>"+getTextDernierePosition+": <b>"+coordDateTimePosition+ "</b> " +substractDateTime(coordDateTimePosition)+"<br><b>"+
										 coordPosAdresse+"</b></td></tr>"+
										 "<tr><td>Statut: "+DecodedStatus+"</td></tr></div></div>"; //by franck
						

					if(coordDateTimePosition == "") contenuDernierePos =	"<div class='panel panel-default'><div class='panel-body'><table><tr><td> "+getTextDernierePosition+": <b>"+getTextNon+" "+getTextTrouve;+"</b>"+
					"</td></tr><tr><td>Statut: <b>"+getTextNon+" "+getTextTrouve;+"</b></td></tr></div></div>";
					if(coordDateTimePosition == " ") contenuDernierePos =	"<div class='panel panel-default'><div class='panel-body'><table><tr><td> "+getTextDernierePosition+": <b>"+getTextNon+" "+getTextTrouve;+"</b>"+
					"</td></tr><tr><td>Statut: <b>"+getTextNon+" "+getTextTrouve;+"</b></td></tr></div></div>";

					document.getElementById('titre_etat_balise').innerHTML = contenuTitre;
					document.getElementById('derniere_position_etat_balise').innerHTML = contenuDernierePos;
					dataEtatBalise();
				}
			}
		});
	}
}

/**********************************************************************************/
/********************************afficheEtatBalise*********************************/
/**********************************************************************************/
function afficheEtatBalise(){
	infoTposition();
	infoTtracker();

}