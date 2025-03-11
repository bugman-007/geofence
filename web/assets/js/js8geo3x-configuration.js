var paramPage = 1;
function paramAvancee(num){
	document.body.className = "loading";
	switch(num){
		case 1: 
			$(document).ready(function(){
				$("#parametre_avancee").load("../configuration/configurationmodeettemps.php", function () {
					// document.getElementById("fenetreConfiguration").innerHTML = "Mode de fonctionnement / Temps r&eacute;el";
					detecterModeFonctionnementEtTempsReel();
					paramPage = 1;
				});	
			});

			break;
		case 2: 
			$(document).ready(function(){
				$("#parametre_avancee").load("../configuration/configurationdeplacementetarret.php", function () {
					// document.getElementById("fenetreConfiguration").innerHTML = "D&eacute;tection d&eacute;placement / arr&ecirc;t";
					detecterDeplacementEtArret();
					paramPage = 2;
				});	
			});
	
			break;
		case 3: 
			$(document).ready(function(){
				$("#parametre_avancee").load("../configuration/configurationalertetsms.php", function () {
					// document.getElementById("fenetreConfiguration").innerHTML = "Alerte et SMS";
					detecterAlertEtSmS();
					paramPage = 3;
				});	
			});
	
			break;
		case 4: 
			$(document).ready(function(){
				$("#parametre_avancee").load("../configuration/configurationfonctiontechnique.php", function () {
					// document.getElementById("fenetreConfiguration").innerHTML = "Fonction techniques";
					fonctiontechnique();
					paramPage = 4;
				});	
			});
	
			break;
		case 5: 
			$(document).ready(function(){
				$("#parametre_avancee").load("../configuration/configurationstrategie.php", function () {
					// document.getElementById("fenetreConfiguration").innerHTML = "Strategie";
					strategie();
					paramPage = 5;
				});	
			});
	
			break;
		case 6: 
			$(document).ready(function(){
				$("#parametre_avancee").load("../configuration/configurationradio.php", function () {
					// document.getElementById("fenetreConfiguration").innerHTML = "Radio";
					radio();
					paramPage = 6;
				});	
			});
	
			break;
		case 7: 
			$(document).ready(function(){
				$("#parametre_avancee").load("../configuration/configurationrencontrebalise.php", function () {
					// document.getElementById("fenetreConfiguration").innerHTML = "Rencontre Balise";
					rencontrebalise();
					paramPage = 7;
				});	
			});
	
			break;
		case 8: 
			$(document).ready(function(){
				$("#parametre_avancee").load("../configuration/configurationplaning_gsm.php", function () {
					// document.getElementById("fenetreConfiguration").innerHTML = "Planning Gsm";
					planing_gsm();
					paramPage = 8;
				});	
			});
	
			break;
	}
}
function onCheckTempsReel(obj){
	var div = document.getElementById( 'temps_reel_active_desactive' );
	if (obj.checked){
		document.getElementById('temps_reel_active_desactive').innerHTML = getTextModeBienSurDeplacementActive;
		div.style.color  = '#00FF00';
		div.style.fontWeight = 'bold';

	}else{
		document.getElementById('temps_reel_active_desactive').innerHTML = getTextModeBienSurDeplacementDesactive;
		div.style.color  = '';
		div.style.fontWeight = 'normal';
	}
}
function onCheckAlert(obj){
	var selectTypeAlert = document.getElementById("select_type_alert");

	var div = document.getElementById( 'alert_active_desactive' );
	if (obj.checked){
		document.getElementById('alert_active_desactive').innerHTML = getTextModeBienAlertActive;
		div.style.backgroundColor = '#00FF00';
		document.getElementById('alert_active').style.visibility = "visible";
		if( selectTypeAlert.options[selectTypeAlert.selectedIndex].value == "alarmeparking") document.getElementById('alert_active_parking').style.visibility = "visible";
	}else{
		document.getElementById('alert_active_desactive').innerHTML = getTextModeBienAlertDesactive;
		div.style.backgroundColor = '';
		document.getElementById('alert_active').style.visibility = "hidden";
		document.getElementById('alert_active_parking').style.visibility = "hidden";
	}
}
function lireParamBalise(){
	
	var idTracker =document.getElementById("idBalise").innerHTML;
	var nomBalise=document.getElementById('nomBalise').innerHTML;

	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var numeroAppel = numeroAppelGlobal;

	var notreDate = new Date();
	var notreMois = notreDateFin.getMonth()+1;
	var datetime 	= 	notreDate.getFullYear() + "-" + ((notreMois < 10)?"0":"") + notreMois+ "-" + ((notreDate.getDate() < 10)?"0":"") + notreDate.getDate() + " "
					+ 	((notreDate.getHours() < 10)?"0":"") + notreDate.getHours() + ":" + ((notreDate.getMinutes() < 10)?"0":"") +  notreDate.getMinutes() + ":" + ((notreDate.getSeconds() < 10)?"0":"") + notreDate.getSeconds();

	var modeMessage = modeMessageGlobal;
	var sujet = "Demande des paramètres de la balise (Synchronisation de la mémoire)";

	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else if(idTracker.search(/,/) != -1){
		alert(getTextVeuillezChoisirQueUneBalise);
		return;
	}else{
		$.ajax({
			url: '../configuration/configurationvalidtmessages.php',
			type: 'GET',
			data: "datetime=" + datetime + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
			"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel +
			"&sujet=" + sujet,

			success: function (sujet) {
				alert(getTextModeBienEnregistrerParam+" " + nomBalise + "\n\n" + ""
						+ sujet + "\n\n" +
						getTextModeAttentionParamRapatrie);
			}
		})
	}
}

function afficherFichierLog(){

	var xmlhttp = null;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var idTracker =document.getElementById("idBalise").innerHTML;
	var nomBalise=document.getElementById('nomBalise').innerHTML;
	var numeroAppel = numeroAppelGlobal;

	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		$('#fichier_log').modal('hide');
	}else {
		$.ajax({
			url: '../configuration/configurationfichierlog.php',
			type: 'GET',
			data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel,
			//async: false,
			success: function (response2) {
				var chaine = response2;
				var reg = new RegExp("[&]+", "g");
				var tableau = chaine.split(reg);
				var nbreLigne = tableau[0].substring(tableau[0].indexOf('t') + 1, tableau[0].indexOf('g'));
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
		});




		//xmlhttp.open("GET", "configurationfichierlog.php?idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel, false);
		//xmlhttp.send();
	}

}


function infoConf(Id_Tracker,nomBalise,multipleTracker){
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
		document.getElementById('config_nom_balise').innerHTML = getTextNomBalise+": <b></b>";
		document.getElementById('config_numero_appel').innerHTML = getTextNumeroAppel+": <b></b>";
		document.getElementById('config_derniere_synchro').innerHTML = '<a href="#" data-toggle="modal" data-target="#info_derniere_synchro"><i class="fa fa-info-circle info"></i></a>';

		numeroAppelGlobal = "";
		return;
	}else if(Id_Tracker.search(/,/) != -1){
		document.getElementById('config_nom_balise').innerHTML = getTextNomBalise+": <b></b>";
		document.getElementById('config_numero_appel').innerHTML = getTextNumeroAppel+": <b></b>";
		document.getElementById('config_derniere_synchro').innerHTML = '<a href="#" data-toggle="modal" data-target="#info_derniere_synchro"><i class="fa fa-info-circle info"></i></a>';

		numeroAppelGlobal = "";
		return;
	}else{
		if(window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			var chaine=xmlhttp.responseText;
			var reg=new RegExp("[&]+", "g");
			var tableau=chaine.split(reg);

			var derniereSynchro =  tableau[0].substring(tableau[0].indexOf('SynchTime1:')+11,tableau[0].indexOf('Tel_tracker:'));
			var numeroAppel = tableau[0].substring(tableau[0].indexOf('Tel_tracker:')+12);

			if(derniereSynchro == "") derniereSynchro = getTextNon + " " + getTextTrouve;
			if(derniereSynchro == " ") derniereSynchro = getTextNon + " " + getTextTrouve;
			if(numeroAppel == "") numeroAppel = getTextNon + " " + getTextTrouve;

			document.getElementById('config_nom_balise').innerHTML = getTextNomBalise+": <b>"+nomBalise+"</b>";
			document.getElementById('config_numero_appel').innerHTML = getTextNumeroAppel+": <b>"+numeroAppel+"</b>";
			document.getElementById('config_derniere_synchro').innerHTML = derniereSynchro+' <a href="#" data-toggle="modal" data-target="#info_derniere_synchro"><i class="fa fa-info-circle info"></i></a>';

			numeroAppelGlobal = numeroAppel;

		}

		xmlhttp.open("GET","../etatbalise/etatbalisettracker.php?Id_Tracker="+Id_Tracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&timezone="+timezone,true);
		xmlhttp.send();
	}
}

function detecterModeFonctionnementEtTempsReel(){
	var xmlhttp = null;
	document.getElementById("nospam2").innerHTML = "0";
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	if (idTracker==""){
		// alert('Pas de Balise');
		$('#lpb').show();											// Montrer lecture parametres balise
		$('#select_mode_fonctionnement').prop('disabled', false);	// Activer liste select_mode_fonctionnement
		document.body.className = "";
		return;
	}else if(idTracker.search(/,/) != -1){
		alert(getTextVeuillezChoisirQueUneBalise);
		baliseUnSelectAll();
		document.body.className = "";
		return;
	}else{
		if(window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				var test = xmlhttp.responseText;
				var reg = new RegExp("[&]+", "g");
				var tableau = test.split(reg);

				var firmwareBalise = firmwareBaliseGlobal;

				var selectModeFonctionnement = document.getElementById('select_mode_fonctionnement');
				var selectConnexionGsm = document.getElementById('connexion_gsm');
				
				
				if(versionBaliseGlobal == "20"){ 	// TELTO
					$('#lpb').hide();											// syncmem
					$('#select_mode_fonctionnement').prop('disabled', true);	// Desactiver liste select_mode_fonctionnement
					$('#div_freq_acq_timing').hide();							// cacher timing acq
					$('#div_freq_rap_timing').hide();							// cacher timing rap
					$('#modetr').hide();										// cacher temps réel
					
					// Ajout liste connexion GSM
					var eco = document.createElement('option');
					eco.value = "eco";
					eco.innerHTML = "ECO";
					selectConnexionGsm.appendChild(eco);
					
					var indexD;
					var indexF;

					var saveGpsTrajet = 0;
					var saveGpsArret = 0;
					var sendGprs = 0;
					var sendGprsVeille = 0;
						
					if(idTracker > 356173060000000){		// FM1120
						
						indexD = tableau[0].indexOf('1550:');
						if(indexD != -1){
							indexD += 5;
							indexF = tableau[0].indexOf(';',indexD);
							saveGpsTrajet = parseInt(tableau[0].substring(indexD, indexF));
						}
						
						indexD = tableau[0].indexOf('1540:');
						if(indexD != -1){
							indexD += 5;
							indexF = tableau[0].indexOf(';',indexD);
							saveGpsArret = parseInt(tableau[0].substring(indexD, indexF));
						}
						
						indexD = tableau[0].indexOf('1554:');
						if(indexD != -1){
							indexD += 5;
							indexF = tableau[0].indexOf(';',indexD);
							sendGprs = parseInt(tableau[0].substring(indexD, indexF));
						}
						
						indexD = tableau[0].indexOf('1544:');
						if(indexD != -1){
							indexD += 5;
							indexF = tableau[0].indexOf(';',indexD);
							sendGprsVeille = parseInt(tableau[0].substring(indexD, indexF));
						}
						
						// Initialisation liste connexion GSM
						indexD = tableau[0].indexOf('1000:');
						if(indexD != -1){
							indexD += 5;
							indexF = tableau[0].indexOf(';',indexD);
							var modeGsm = parseInt(tableau[0].substring(indexD, indexF));
							
							if (modeGsm == 1)
								$('#connexion_gsm').val('permanent');
							else if (modeGsm == 2)
								$('#connexion_gsm').val('eco');
						}
						else
							$('#connexion_gsm').val('permanent');
						
					}else{									// FMB920
						
						indexD = tableau[0].indexOf('10050:');
						if(indexD != -1){
							indexD += 6;
							indexF = tableau[0].indexOf(';',indexD);
							saveGpsTrajet = parseInt(tableau[0].substring(indexD, indexF));
						}
						
						indexD = tableau[0].indexOf('10000:');
						if(indexD != -1){
							indexD += 6;
							indexF = tableau[0].indexOf(';',indexD);
							saveGpsArret = parseInt(tableau[0].substring(indexD, indexF));
						}

						indexD = tableau[0].indexOf('10055:');
						if(indexD != -1){
							indexD += 6;
							indexF = tableau[0].indexOf(';',indexD);
							sendGprs = parseInt(tableau[0].substring(indexD, indexF));
						}
						
						indexD = tableau[0].indexOf('10005:');
						if(indexD != -1){
							indexD += 6;
							indexF = tableau[0].indexOf(';',indexD);
							sendGprsVeille = parseInt(tableau[0].substring(indexD, indexF));
						}
						
						// Initialisation liste connexion GSM
						indexD = tableau[0].indexOf('102:');
						if(indexD != -1){
							indexD += 4;
							indexF = tableau[0].indexOf(';',indexD);
							var modeGsm = parseInt(tableau[0].substring(indexD, indexF));
							
							if (modeGsm == 3)
								$('#connexion_gsm').val('permanent');
							else if (modeGsm == 2)
								$('#connexion_gsm').val('eco');
						}
						else
							$('#connexion_gsm').val('permanent');
						
					}
					
					// Initialisation des temps
					if(saveGpsTrajet == 0) saveGpsTrajet = 15;
					if(saveGpsArret == 0) saveGpsArret = 7200;
					
					$('#select_freq_acquisition_trajet').val(saveGpsTrajet);
					$('#select_freq_acquisition_arret').val(saveGpsArret);
					
					if(sendGprsVeille == 0) sendGprsVeille = 21600;
					
					$('#select_freq_rapatriement_trajet').val(sendGprs/60);
					$('#select_freq_rapatriement_arret').val(sendGprsVeille/60);

				}else if((versionBaliseGlobal == "3370")||(versionBaliseGlobal == "7003")||(versionBaliseGlobal == "7201")){ // SC NEO & SOLO
					$('#lpb').hide();											// syncmem
					$('#div_freq_rap').hide();									// cacher rap
					$('#modetr').hide();										// cacher temps réel
					$("#select_freq_acquisition_timing option[value='8']").remove();
					$("#select_freq_acquisition_timing option[value='30']").remove();
					
					var periscope = document.createElement('option');
					periscope.value = "periscope";
					periscope.innerHTML = getTextModePeriscope;
					selectModeFonctionnement.appendChild(periscope);
					
					var modeGsm = 0;
					var saveGpsTrajet = 120;
					var saveGpsArret = 21600;
					
					indexD = tableau[0].indexOf('MODE,');
					if(indexD != -1){
						indexD += 5;
						indexF = tableau[0].indexOf(',',indexD);
						modeGsm = parseInt(tableau[0].substring(indexD, indexF));
						
						indexD = indexF + 1;
						indexF = tableau[0].indexOf(',',indexD);
						if(indexD != -1){
							saveGpsTrajet = parseInt(tableau[0].substring(indexD, indexF));
							
							indexD = indexF + 1;
							indexF = tableau[0].indexOf('#',indexD);
							if(indexD != -1){
								saveGpsArret = parseInt(tableau[0].substring(indexD, indexF));
							}
						}
					}
					
					
					if(modeGsm == 2)
					{
						selectModeFonctionnement.value = "periscope";
						$('#div_freq_acq_trajet').hide();
						$('#div_freq_acq_timing').show();
						$('#div_freq_acq_arret').hide();
						
						$('#select_freq_acquisition_timing').prop('disabled', false);
						
						$('#select_freq_acquisition_trajet').val(120);
						$('#select_freq_acquisition_timing').val(saveGpsArret*60);
						$('#select_freq_acquisition_arret').val(21600);
					}
					else
					{
						selectModeFonctionnement.value = "normal";
						$('#div_freq_acq_timing').hide();							// cacher timing acq
						
						$('#select_freq_acquisition_trajet').val(saveGpsTrajet);
						$('#select_freq_acquisition_timing').val(360);
						$('#select_freq_acquisition_arret').val(saveGpsArret);
					}
					
					
					if(((versionBaliseGlobal != "3370") && (versionBaliseGlobal != "8045")) || (modeGsm == 2))
					{
						$("#labelgsm").hide();
						$('#connexion_gsm').hide();			// cacher GSM
					}
					
					
					if(versionBaliseGlobal == "3370")
					{
						var stpgps = 300;
						var stpgprs = 30;
						var stpgsm = 0;
						var stpfreq = 7200;
						
						var eco = document.createElement('option');
						eco.value = "eco";
						eco.innerHTML = "ECO";
						selectConnexionGsm.appendChild(eco);
						
						indexD = tableau[0].indexOf('STOPMODE,');
						if(indexD != -1){
							indexD += 9;
							indexF = tableau[0].indexOf(',',indexD);
							
							if(indexF != -1){
								stpgps = parseInt(tableau[0].substring(indexD, indexF));
								
								indexD = indexF + 1;
								indexF = tableau[0].indexOf(',',indexD);
								if(indexF != -1){
									stpgprs = parseInt(tableau[0].substring(indexD, indexF));
									
									indexD = indexF + 1;
									indexF = tableau[0].indexOf(',',indexD);
									if(indexF != -1){
										stpgsm = parseInt(tableau[0].substring(indexD, indexF));
										
										indexD = indexF + 1;
										indexF = tableau[0].indexOf('#',indexD);
										if(indexF != -1){
											stpfreq = parseInt(tableau[0].substring(indexD, indexF));
										}
									}
								}
							}
						}
						
						if(stpgprs && (stpgsm == 0) )
							$('#connexion_gsm').val('permanent');
						else if( (stpgprs == 0) && stpgsm)
							$('#connexion_gsm').val(eco.value);
					}
					
					
					// angl
					$("#modevitesse").show();
					$("#divchkv").hide();
					$("#divmvfrp").hide();
					$("#mv").html("Angle:");				// traduction manquante
					$("#lmvv").html("");					// traduction manquante
					$("#lmvfp").html("");					// traduction manquante
					
					//
					var optOFF = document.createElement('option');
					optOFF.value = "OFF";
					optOFF.innerHTML = "OFF";
					var optON = document.createElement('option');
					optON.value = "ON";
					optON.innerHTML = "ON";
					
					$('#mvv option').remove();
					var selectmvv = document.getElementById('mvv');
					selectmvv.appendChild(optOFF);
					selectmvv.appendChild(optON);
					
					//
					var opt20deg = document.createElement('option');
					opt20deg.value = "20";
					opt20deg.innerHTML = "20°";
					var opt40deg = document.createElement('option');
					opt40deg.value = "40";
					opt40deg.innerHTML = "40°";
					var opt60deg = document.createElement('option');
					opt60deg.value = "60";
					opt60deg.innerHTML = "60°";
					var opt80deg = document.createElement('option');
					opt80deg.value = "80";
					opt80deg.innerHTML = "80°";
					
					$('#mvfp option').remove();
					var selectmvfp = document.getElementById('mvfp');
					selectmvfp.appendChild(opt20deg);
					selectmvfp.appendChild(opt40deg);
					selectmvfp.appendChild(opt60deg);
					selectmvfp.appendChild(opt80deg);
					
					//
					var anglst = "OFF";
					var anglval = "20";
					var angltime = "5";
					
					indexD = tableau[0].indexOf('ANGLEREP,');
					if(indexD != -1){
						indexD += 9;
						indexF = tableau[0].indexOf(',',indexD);
						
						if(indexF != -1){
							anglst = tableau[0].substring(indexD, indexF);
							
							indexD = indexF + 1;
							indexF = tableau[0].indexOf(',',indexD);
							if(indexF != -1){
								anglval = parseInt(tableau[0].substring(indexD, indexF));
								
								indexD = indexF + 1;
								indexF = tableau[0].indexOf(',',indexD);
								if(indexF != -1){
									angltime = parseInt(tableau[0].substring(indexD, indexF));
								}
							}
						}
					}
					
					if(anglst == "ON")
						$("#mvv").val("ON");
					else
						$("#mvv").val("OFF");

					$("#mvfp").val(anglval);
					
				}else{			// Balise Stancom
					
					var saveGpsTrajet = parseInt(tableau[0].substring(tableau[0].indexOf('<br> Save GPS trajet') + 30, tableau[0].indexOf('<br> Save GPS arret')));
					var saveGpsArret = parseInt(tableau[0].substring(tableau[0].indexOf('<br> Save GPS arret') + 30, tableau[0].indexOf('<br> Mode Sleep')));
					var timingModeGps = parseInt(tableau[0].substring(tableau[0].indexOf('<br> TIMING MODE GPS ') + 30, tableau[0].indexOf('<br> REALTIME START')));

					var sendGprs = parseInt(tableau[0].substring(tableau[0].indexOf('<br> SEND GPRS') + 27, tableau[0].indexOf('<br> SEND GPRS VEILLE')));
					var sendGprsVeille = parseInt(tableau[0].substring(tableau[0].indexOf('<br> SEND GPRS VEILLE') + 32, tableau[0].indexOf('<br> DELAY 1')));
					var timingModeGprs = parseInt(tableau[0].substring(tableau[0].indexOf('<br> TIMING MODE GPRS') + 32, tableau[0].indexOf('<br> MODE_APC')));

					var callTimeRealtime = parseInt(tableau[0].substring(tableau[0].indexOf('<br> CALL TIME REALTIME') + 32, tableau[0].indexOf('<br> TPS REBOOT GSM')));
					var realtimeStart = parseInt(tableau[0].substring(tableau[0].indexOf('<br> REALTIME START') + 30, tableau[0].indexOf('<br> TIMING MODE GPRS')));
					var realtimeApc = tableau[0].substring(tableau[0].indexOf('<br> RealtimeAPC') + 28);

					var modeGprs = tableau[0].substring(tableau[0].indexOf('<br> Mode GPRS') + 26, tableau[0].indexOf('<br> Mode GPS'));
					var modeGps = tableau[0].substring(tableau[0].indexOf('<br> Mode GPS') + 25, tableau[0].indexOf('<br> Timeout GPS'));
					var modeGsm = tableau[0].substring(tableau[0].indexOf('<br> MODE GSM') + 25, tableau[0].indexOf('<br> GSM - APC'));
					var Gsmapc = tableau[0].substring(tableau[0].indexOf('<br> GSM - APC									=> ') + 26, tableau[0].indexOf('<br> MODE GSM LAPS'));
					var modeGsmLaps = tableau[0].substring(tableau[0].indexOf('<br> MODE GSM LAPS')+29,tableau[0].indexOf('<br> MODE GSM TIMING	OFFLINE'));


					// Ajouts liste mode fonctionnement
					if (versionBaliseGlobal!="43" || (versionBaliseGlobal == "43" && firmwareBalise != "01")) {		// Si différent de SC400MB avec firmware V01
						var historique = document.createElement('option');
						historique.value = "historique";
						historique.innerHTML = getTextModeHistorique;
						selectModeFonctionnement.appendChild(historique);
						var periscope = document.createElement('option');
						periscope.value = "periscope";
						periscope.innerHTML = getTextModePeriscope;
						selectModeFonctionnement.appendChild(periscope);
					}
					
					
					// Initialisation des temps
					if(saveGpsTrajet == 0) saveGpsTrajet = 15;
					if(saveGpsArret == 0) saveGpsArret = 7200;
					if(timingModeGps == 0) timingModeGps = 30;
					
					$('#select_freq_acquisition_trajet').val(saveGpsTrajet);
					$('#select_freq_acquisition_arret').val(saveGpsArret);
					$('#select_freq_acquisition_timing').val(timingModeGps);
					
					if(sendGprsVeille == 0) sendGprsVeille = 360;
					if(timingModeGprs == 0) timingModeGprs = 30;
					
					$('#select_freq_rapatriement_trajet').val(sendGprs);
					$('#select_freq_rapatriement_arret').val(sendGprsVeille);
					$('#select_freq_rapatriement_timing').val(timingModeGprs);
					
					
					// Ajouts liste connexion GSM
					if (versionBaliseGlobal == "55" || versionBaliseGlobal == "57") {		// 600St & 600Av
						var actifarret = document.createElement('option');
						actifarret.value = "actifarret";
						actifarret.innerHTML = "Actif &agrave; l'arr&ecirc;t";
						selectConnexionGsm.appendChild(actifarret);
						var actiftrajet = document.createElement('option');
						actiftrajet.value = "actiftrajet";
						actiftrajet.innerHTML = "Actif en mouvement";
						selectConnexionGsm.appendChild(actiftrajet);
						var planning = document.createElement('option');
						planning.value = "planning";
						planning.innerHTML = "Planning";
						selectConnexionGsm.appendChild(planning);
					}else{
						var eco = document.createElement('option');
						eco.value = "eco";
						eco.innerHTML = "ECO";
						selectConnexionGsm.appendChild(eco);
					
						if ($.inArray(versionBaliseGlobal, ['24', '31', '32', '33', '41', '42', '44', '52', '46']) >= 0) {
							var ecoplus = document.createElement('option');
							ecoplus.value = "eco+";
							ecoplus.innerHTML = "ECO +";
							selectConnexionGsm.appendChild(ecoplus);
						}
					}
					
					
					// Detection mode fonctionnement + mode GSM + retard à l'activation
					selectModeFonctionnement.value = "normal";

					if (modeGps == "16") {
						if (modeGprs == "16") {
							if (modeGsm == "108" || modeGsm == "12") {
								selectModeFonctionnement.value = "periscope";
								document.getElementById('select_freq_acquisition_trajet').disabled = true;
								document.getElementById('select_freq_acquisition_arret').disabled = true;
								document.getElementById('select_freq_acquisition_timing').disabled = false;
								document.getElementById('select_freq_rapatriement_trajet').disabled = true;
								document.getElementById('select_freq_rapatriement_arret').disabled = true;
								document.getElementById('select_freq_rapatriement_timing').disabled = false;
								$("#labelgsm").hide(); $('#connexion_gsm').hide();
								$("#r").hide(); $("#rt").hide(); $("#retard").val("0");		// cacher retard à l'activation
							}
						}
					}else {
						if (modeGps == "5" || modeGps == "7") {
							if(modeGprs == "16"){
								 if(modeGsm == "108" || modeGsm == "12"){
									selectModeFonctionnement.value = "historique";
									document.getElementById('select_freq_rapatriement_trajet').disabled = true;
									document.getElementById('select_freq_rapatriement_arret').disabled = true;
									document.getElementById('select_freq_rapatriement_timing').disabled = false;
									$("#labelgsm").hide(); $('#connexion_gsm').hide();
									$("#r").hide(); $("#rt").hide(); $("#retard").val("0");		// cacher retard à l'activation
								 }
							}else{
								if (modeGprs == "4" || modeGprs == "8" || modeGprs == "10") {
									selectModeFonctionnement.value = "normal";
									if (modeGsm == "0") {
										$('#connexion_gsm').val('permanent');
										$("#r").hide(); $("#rt").hide(); $("#retard").val("0");		// cacher retard à l'activation
									}
									else if (modeGsm == "16") {
										$('#connexion_gsm').val(planning.value);
										$("#r").hide(); $("#rt").hide(); $("#retard").val("0");		// cacher retard à l'activation
									}
									else{
										if (versionBaliseGlobal == "55" || versionBaliseGlobal == "57") {
											if (modeGsm == "13" && Gsmapc == "1") {
												$('#connexion_gsm').val(actiftrajet.value);
												$("#r").show(); $("#rt").show(); $("#retard").val(modeGsmLaps);		// montrer retard à l'activation
											}
											else if(modeGsm == "9" && Gsmapc == "0"){
												$('#connexion_gsm').val(actifarret.value);
												$("#r").show(); $("#rt").show(); $("#retard").val(modeGsmLaps);		// montrer retard à l'activation
											}
										}
										else{
											if (modeGsm == "109" && Gsmapc == "1") {
												$('#connexion_gsm').val(eco.value);
											}
											else if (modeGsm == "108") {
												$('#connexion_gsm').val(ecoplus.value);
											}
										}
									}
									
									if(versionBaliseGlobal == "56")
									{
										var FWI = tableau[0].substring(tableau[0].indexOf('<br> FWI')+52,tableau[0].indexOf('<br> Serveur ADR 0'));
										$("#r").html("Freq. acquisition Iridium:");					// traduction manquante
										$("#r").show(); $("#rt").show(); $("#retard").val(FWI);		// montrer retard à l'activation
										$("#retard option[value='0']").remove();
										//console.log(FWI);
									}
								}
							}
						}else{
							$("#r").hide(); $("#rt").hide();		// Cacher retard à l'activation
						}
					}
				
				
					// if(modeGsm == "16"){
						// if( versionBaliseGlobal != "43" && versionBaliseGlobal != "45"){
							// var silencieux = document.createElement('option');
							// silencieux.value = "silencieux";
							// silencieux.innerHTML = "Mode Silencieux";
							// selectModeFonctionnement.appendChild(silencieux);
						// }
					// }
					
					// Affichage du Mode vitesse
					if ($.inArray(versionBaliseGlobal, ['55', '57']) >= 0) {
						
						$("#modevitesse").show();

						var cosh = tableau[0].substring(tableau[0].indexOf('<br> FGc_ACT							=> ') + 22, tableau[0].indexOf('<br> FGc_VITESSE'));
						var fgcvitesse = tableau[0].substring(tableau[0].indexOf('<br> FGc_VITESSE							=> ') + 26, tableau[0].indexOf('<br> FGc_FRE_ACQ'));
						var fgcfreacq = tableau[0].substring(tableau[0].indexOf('<br> FGc_FRE_ACQ							=> ') + 26, tableau[0].indexOf('<br> FGc_FRE_RAP'));
						var fgcfrerap = tableau[0].substring(tableau[0].indexOf('<br> FGc_FRE_RAP							=> ') + 26);
						
						if(cosh == 1)
							{document.getElementById("chkv").checked = true;}
						else
							{document.getElementById("chkv").checked = false;}
						
						$("#mvv").val(fgcvitesse);
						$("#mvfp").val(fgcfreacq);
						$("#mvfrp").val(fgcfrerap);
					}
				
					// Initilisation du temps réel
					$('#select_temps_reel_appel').val(callTimeRealtime);
					$('#select_temps_reel_demarrage').val(realtimeStart);
					
					var div = document.getElementById('temps_reel_active_desactive');
					if (realtimeApc == "0") {
						document.getElementById('temps_reel_active_desactive').innerHTML = getTextModeBienSurDeplacementDesactive;
						document.getElementById('checkbox_temps_reel_active_desactive').checked = false;
						div.style.color = '#';
						div.style.fontWeight = 'normal';
					} else if (realtimeApc == "1") {
						document.getElementById('temps_reel_active_desactive').innerHTML = getTextModeBienSurDeplacementActive;
						document.getElementById('checkbox_temps_reel_active_desactive').checked = true;
						div.style.color = '#00FF00';
						div.style.fontWeight = 'bold';
					}
				}

				document.body.className = "";
			}
		}
		xmlhttp.open("GET","../configuration/configurationanalysedata.php?idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,false);
		xmlhttp.send();
	}
}

//*franck detecter fonctiontechnique 
function fonctiontechnique(){
	var xmlhttp = null;
	document.getElementById("nospam2").innerHTML = "0";
	document.getElementById("nospam").innerHTML = "0";
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	
	if (idTracker==""){
		// alert('Pas de Balise');
		document.body.className = "";
		return;
	}else if(idTracker.search(/,/) != -1){
		alert(getTextVeuillezChoisirQueUneBalise);
		baliseUnSelectAll();
		document.body.className = "";
		return;
	}else{
		if(window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var test=xmlhttp.responseText;
				var reg=new RegExp("[&]+", "g");
				var tableau=test.split(reg);
			
/*
				var serveur = tableau[0].substring(tableau[0].indexOf('<br> Serveur ADR 1')+52,tableau[0].indexOf('<br> Serveur Port 1')-16);
				if(serveur.trim() == "sc401.geo3x.fr"){
					$('#serveur').val("judiciaire");
				}else if(serveur.trim() == "sc400.geo3x.fr"){
					$('#serveur').val("administratif");
				}else*/
				{
					// Adresse serveur
					if((versionBaliseGlobal == "3370")||(versionBaliseGlobal == "7003")||(versionBaliseGlobal == "7201")){		// SC NEO & SOLO
						$("#fonctiontechnique").show();
						$("#serveur").prop('disabled', true);
						$('#serveur').val("autre");
						$("#groupserveur").show();
						$("#ip1").prop('disabled', true);
						$("#port1").prop('disabled', true);
						$("#ip2").prop('disabled', true);
						$("#port2").prop('disabled', true);
						
						$("#ip0").val("scneo.geo3x.fr");
						$("#port0").val("3570");
						$("#ip1").val("...");
						$("#port1").val("000");
						$("#ip2").val("...");
						$("#port2").val("000");
					}else if(versionBaliseGlobal == 17 || versionBaliseGlobal == 18 || versionBaliseGlobal == 19 || versionBaliseGlobal >= 43){	// Type Jalon, SC400, SC500, SC600, SC CUBE
						$("#fonctiontechnique").show();
						$("#serveur").prop('disabled', true);
						$('#serveur').val("autre");
						$("#groupserveur").show();
						
						$("#ip0").val(tableau[0].substring(tableau[0].indexOf('<br> Serveur ADR 0')+52,tableau[0].indexOf('<br> Serveur Port 0')-16));
						$("#port0").val(tableau[0].substring(tableau[0].indexOf('<br> Serveur Port 0')+57,tableau[0].indexOf('<br> Serveur ADR 1')-5)); 
						$("#ip1").val(tableau[0].substring(tableau[0].indexOf('<br> Serveur ADR 1')+52,tableau[0].indexOf('<br> Serveur Port 1')-16)); 
						$("#port1").val(tableau[0].substring(tableau[0].indexOf('<br> Serveur Port 1')+57,tableau[0].indexOf('<br> Serveur ADR 2')-5)); 
						$("#ip2").val(tableau[0].substring(tableau[0].indexOf('<br> Serveur ADR 2')+52,tableau[0].indexOf('<br> Serveur Port 2')-16)); 
						$("#port2").val(tableau[0].substring(tableau[0].indexOf('<br> Serveur Port 2')+57,tableau[0].indexOf('<br> AccGravite')-5));
					}else if(versionBaliseGlobal == 20){		// TELTO
						$("#fonctiontechnique").show();
						$("#serveur").prop('disabled', true);
						$('#serveur').val("autre");
						$("#groupserveur").show();
						$("#ip2").prop('disabled', true);
						$("#port2").prop('disabled', true);
						
						var indexD;
						var indexF;
						var ip0 = "telto1.geo3x.fr";
						var port0 = "5164";
					
						if(idTracker > 356173060000000){		// FM1120
							$("#ip1").prop('disabled', true);
							$("#port1").prop('disabled', true);
							
							var ip1 = "...";
							var port1 = "000";
							
							indexD = tableau[0].indexOf('1245:');
							if(indexD != -1){
								indexD += 5;
								indexF = tableau[0].indexOf(';',indexD);
								ip0 = tableau[0].substring(indexD, indexF);
							}
							
							indexD = tableau[0].indexOf('1246:');
							if(indexD != -1){
								indexD += 5;
								indexF = tableau[0].indexOf(';',indexD);
								port0 = parseInt(tableau[0].substring(indexD, indexF));
							}
						}
						else									// FMB920
						{
							var ip1 = "telto2.geo3x.fr";
							var port1 = "5164";
							
							indexD = tableau[0].indexOf('2004:');
							if(indexD != -1){
								indexD += 5;
								indexF = tableau[0].indexOf(';',indexD);
								ip0 = tableau[0].substring(indexD, indexF);
							}
							
							indexD = tableau[0].indexOf('2005:');
							if(indexD != -1){
								indexD += 5;
								indexF = tableau[0].indexOf(';',indexD);
								port0 = parseInt(tableau[0].substring(indexD, indexF));
							}
							
							indexD = tableau[0].indexOf('2007:');
							if(indexD != -1){
								indexD += 5;
								indexF = tableau[0].indexOf(';',indexD);
								ip1 = tableau[0].substring(indexD, indexF);
							}
							
							indexD = tableau[0].indexOf('2008:');
							if(indexD != -1){
								indexD += 5;
								indexF = tableau[0].indexOf(';',indexD);
								port1 = parseInt(tableau[0].substring(indexD, indexF));
							}
						}
						
						// Initialisation champs
						$("#ip0").val(ip0);
						$("#port0").val(port0);
						$("#ip1").val(ip1);
						$("#port1").val(port1);
						$("#ip2").val("...");
						$("#port2").val("000");
					}else{
						$("#fonctiontechnique").hide();
					}
				}
				
				// Affichage Desactivation LED
				if(versionBaliseGlobal == 55 || versionBaliseGlobal == 56 || versionBaliseGlobal == 57){
					var vled = tableau[0].substring(tableau[0].indexOf('<br> Led')+22,tableau[0].indexOf('<br> FWI'));
					if(vled == "1"){
						$('#led').bootstrapToggle('on');
					}else{
						$('#led').bootstrapToggle('off');
					}
				}else{
					$('#led').bootstrapToggle('on');
				}
				
				// Affichage Geoloc GSM
				// (versionBaliseGlobal == 48 && (idTracker>=5617000 && 5621 5624 5625 5696 5697 5698 idTracker<=5699999 / idTracker>=5157000 && 5158 5159 5160 idTracker<=5161999 || idTracker>=5167000 && idTracker<=5167999))
				if(( $.inArray(versionBaliseGlobal, ['51', '53', '55', '56', '57']) >= 0 ) || (versionBaliseGlobal == 48 && ((idTracker>=5617000 && idTracker<=5699999) || (idTracker>=5157000 && idTracker<=5161999) || (idTracker>=5167000 && idTracker<=5167999))) ) {
					$("#geogsm").show();
					$("#etatgsm").val(tableau[0].substring(tableau[0].indexOf('<br> GeolocGSM									=> ')+26,tableau[0].indexOf('<br> GeolocGSMtps')));
					$("#tagsm").val(tableau[0].substring(tableau[0].indexOf('<br> GeolocGSMtps								=> ')+28,tableau[0].indexOf('<br> Fen1Mode')));
					grisedgrise();
				}else{
					$("#geogsm").hide();
				}
				
				//console.log("0"+serveur.trim()+"0");
				//console.log(tableau[0].substring(tableau[0].indexOf('<br> AccGravite')+27,tableau[0].indexOf('<br> AccTemps')));
				
				document.body.className = "";
			}
		}

			xmlhttp.open("GET","../configuration/configurationanalysedata.php?idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,true);
			xmlhttp.send();
	}
}

function strategie(){
	var xmlhttp = null;
	document.getElementById("nospam2").innerHTML = "0";
	document.getElementById("nospam").innerHTML = "0";
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	if (idTracker==""){
		// alert('Pas de Balise');
		document.body.className = "";
		return;
	}else if(idTracker.search(/,/) != -1){
		alert(getTextVeuillezChoisirQueUneBalise);
		baliseUnSelectAll();
		document.body.className = "";
		return;
	}else{
		if(window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var test=xmlhttp.responseText;
				var reg=new RegExp("[&]+", "g");
				var tableau=test.split(reg); 
				
				$('#trajetacp').val(tableau[0].substring(tableau[0].indexOf('<br> GPS_ACTIF_SB								=> ')+28,tableau[0].indexOf('<br> GPS_VEILLE_SB'))); 
				$('#trajetrap').val(tableau[0].substring(tableau[0].indexOf('<br> GPRS_ACTIF_SB								=> ')+29,tableau[0].indexOf('<br> GPRS_VEILLE_SB')));
				
				$('#timingacp').val(tableau[0].substring(tableau[0].indexOf('<br> GPS_TIMING_SB								=> ')+29,tableau[0].indexOf('<br> MODE_GSM_SB')));
				$('#timingrap').val(tableau[0].substring(tableau[0].indexOf('<br> GPRS_TIMING_SB								=> ')+30,tableau[0].indexOf('<br> MODE_GPS_SB')));
				
				$('#arretacp').val(tableau[0].substring(tableau[0].indexOf('<br> GPS_VEILLE_SB								=> ')+29,tableau[0].indexOf('<br> GPS_TIMING_SB')));
				$('#arretrap').val(tableau[0].substring(tableau[0].indexOf('<br> GPRS_VEILLE_SB								=> ')+30,tableau[0].indexOf('<br> GPRS_TIMING_SB')));
				
				
				$('#trajetacpgeo').val(tableau[0].substring(tableau[0].indexOf('<br> GPS_ACTIF_SG								=> ')+28,tableau[0].indexOf('<br> GPS_VEILLE_SG')));
				$('#trajetrapgeo').val(tableau[0].substring(tableau[0].indexOf('<br> GPRS_ACTIF_SG								=> ')+29,tableau[0].indexOf('<br> GPRS_VEILLE_SG')));
				
				$('#timingacpgeo').val(tableau[0].substring(tableau[0].indexOf('<br> GPS_TIMING_SG								=> ')+29,tableau[0].indexOf('<br> MODE_GSM_SG')));
				$('#timingrapgeo').val(tableau[0].substring(tableau[0].indexOf('<br> GPRS_TIMING_SG								=> ')+30,tableau[0].indexOf('<br> MODE_GPS_SG')));
				
				$('#arretacpgeo').val(tableau[0].substring(tableau[0].indexOf('<br> GPS_VEILLE_SG								=> ')+29,tableau[0].indexOf('<br> GPS_TIMING_SG')));
				$('#arretrapgeo').val(tableau[0].substring(tableau[0].indexOf('<br> GPRS_VEILLE_SG								=> ')+30,tableau[0].indexOf('<br> GPRS_TIMING_SG')));
				
				var gpsbf = tableau[0].substring(tableau[0].indexOf('<br> MODE_GPS_SB									=> ')+28,tableau[0].indexOf('<br> GPS_ACTIF_SB')); 
				var gprsbf = tableau[0].substring(tableau[0].indexOf('<br> MODE_GPRS_SB								=> ')+28,tableau[0].indexOf('<br> GPRS_ACTIF_SB')); 
				var gsmbf = tableau[0].substring(tableau[0].indexOf('<br> MODE_GSM_SB									=> ')+28,tableau[0].indexOf('<br> APC_GSM_SB')); 
				var apc_gsmbf = tableau[0].substring(tableau[0].indexOf('<br> APC_GSM_SB									=> ')+27,tableau[0].indexOf('<br> TPS_LAT_GSM_SB')); 
				var lapsbf = tableau[0].substring(tableau[0].indexOf('<br> TPS_LAT_GSM_SB								=> ')+30,tableau[0].indexOf('<br> TPS_ACT_GSM_SB')); 
				
				
				var gpsgeo = tableau[0].substring(tableau[0].indexOf('<br> MODE_GPS_SG									=> ')+28,tableau[0].indexOf('<br> GPS_ACTIF_SG')); 
				var gprsgeo = tableau[0].substring(tableau[0].indexOf('<br> MODE_GPRS_SG								=> ')+28,tableau[0].indexOf('<br> GPRS_ACTIF_SG')); 
				var gsmgeo = tableau[0].substring(tableau[0].indexOf('<br> MODE_GSM_SG									=> ')+28,tableau[0].indexOf('<br> APC_GSM_SG')); 
				var apc_gsmgeo = tableau[0].substring(tableau[0].indexOf('<br> APC_GSM_SG									=> ')+27,tableau[0].indexOf('<br> TPS_LAT_GSM_SG')); 
				var lapsgeo = tableau[0].substring(tableau[0].indexOf('<br> TPS_LAT_GSM_SG								=> ')+30,tableau[0].indexOf('<br> TPS_ACT_GSM_SG'));
				
				
				if(gpsbf == "5"){
					if(gprsbf == "8"){
						$("#modefonctbf").val("normal"); $("#gsmbf").show(); $("#labelgsmbf").show();
						if(gsmbf == "0"){
							$("#gsmbf").val("permanent"); $("#r").hide(); $("#rt").hide();
						}else if(gsmbf == "9" && apc_gsmbf == "0"){
							$("#gsmbf").val("eco"); $("#r").show(); $("#rt").show(); $("#retardbf").val(lapsbf);
						}else if(gsmbf == "13" && apc_gsmbf == "1"){
							$("#gsmbf").val("eco+"); $("#r").show(); $("#rt").show(); $("#retardbf").val(lapsbf);
						}
					}else if(gprsbf == "16"){
						$("#modefonctbf").val("historique");
						$("#gsmbf").hide(); $("#labelgsmbf").hide(); 
						$("#r").hide(); $("#rt").hide();
					}
				}else if(gpsbf == "16" && gprsbf == "16"){
					$("#modefonctbf").val("periscope");
					$("#gsmbf").hide(); $("#labelgsmbf").hide(); 
					$("#r").hide(); $("#rt").hide();
				}
				
				if(gpsgeo == "5"){
					if(gprsgeo == "8"){
						$("#modefonctgeo").val("normal"); $("#gsmgeo").show(); $("#labelgsmgeo").show();
						if(gsmgeo == "0"){
							$("#gsmgeo").val("permanent"); $("#rg").hide(); $("#rtg").hide();
						}else if(gsmgeo == "9" && apc_gsmgeo == "0"){
							$("#gsmgeo").val("eco"); $("#rg").show(); $("#rtg").show(); $("#retardgeo").val(lapsgeo);
						}else if(gsmgeo == "13" && apc_gsmgeo == "1"){
							$("#gsmgeo").val("eco+"); $("#rg").show(); $("#rtg").show(); $("#retardgeo").val(lapsgeo);
						}
					}else if(gprsgeo == "16"){
						$("#modefonctgeo").val("historique");
						$("#gsmgeo").hide(); $("#labelgsmgeo").hide(); 
						$("#rg").hide(); $("#rtg").hide();
					}
				}else if(gpsgeo == "16" && gprsgeo == "16"){
					$("#modefonctgeo").val("periscope");
					$("#gsmgeo").hide(); $("#labelgsmgeo").hide(); 
					$("#rg").hide(); $("#rtg").hide();
				}
				
				document.body.className = "";
			}
		}

			xmlhttp.open("GET","../configuration/configurationanalysedata.php?idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,true);
			xmlhttp.send();
	}
}

function radio(){
	var xmlhttp = null;
	document.getElementById("nospam2").innerHTML = "0";
	document.getElementById("nospam").innerHTML = "0";
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	if (idTracker==""){
		// alert('Pas de Balise');
		document.body.className = "";
		return;
	}else if(idTracker.search(/,/) != -1){
		alert(getTextVeuillezChoisirQueUneBalise);
		baliseUnSelectAll();
		document.body.className = "";
		return;
	}else{
		if(window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var test=xmlhttp.responseText;
				var reg=new RegExp("[&]+", "g");
				var tableau=test.split(reg);
				// radio
				
				document.body.className = "";
			}
		}

			xmlhttp.open("GET","../configuration/configurationanalysedata.php?idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,true);
			xmlhttp.send();
	}
}

function rencontrebalise(){
	var xmlhttp = null;
	document.getElementById("nospam2").innerHTML = "0";
	document.getElementById("nospam").innerHTML = "0";
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	
	var chk1 = document.getElementById("chk1"), chk2 = document.getElementById("chk2"), chk3 = document.getElementById("chk3"), chk4 = document.getElementById("chk4");
	
	if (idTracker==""){
		// alert('Pas de Balise');
		document.body.className = "";
		return;
	}else if(idTracker.search(/,/) != -1){
		alert(getTextVeuillezChoisirQueUneBalise);
		baliseUnSelectAll();
		document.body.className = "";
		return;
	}else{
		if(window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var test=xmlhttp.responseText;
				var reg=new RegExp("[&]+", "g");
				var tableau=test.split(reg);
				
				var telephone1 = tableau[0].substring(tableau[0].indexOf('<br> TELEPHONE 1')+28,tableau[0].indexOf('<br> TELEPHONE 2'));
				var telephone2 = tableau[0].substring(tableau[0].indexOf('<br> TELEPHONE 2')+28,tableau[0].indexOf('<br> TELEPHONE 3'));
				var telephone3 = tableau[0].substring(tableau[0].indexOf('<br> TELEPHONE 3')+28,tableau[0].indexOf('<br> TELEPHONE 4'));
				var telephone4 = tableau[0].substring(tableau[0].indexOf('<br> TELEPHONE 4')+28,tableau[0].indexOf('<br> MESSAGE APPARITION ALARME 1'));
			
				if(escape(telephone1[0]) != "%00") document.getElementById("n1").value = escape(telephone1).replace(/%00/g,"");
				if(escape(telephone2[0]) != "%00") document.getElementById("n2").value = escape(telephone2).replace(/%00/g,"");
				if(escape(telephone3[0]) != "%00") document.getElementById("n3").value = escape(telephone3).replace(/%00/g,"");
				if(escape(telephone4[0]) != "%00") document.getElementById("n4").value = escape(telephone4).replace(/%00/g,"");
				
				if(document.getElementById("n1").value !== ""){chk1.disabled = false;}
				if(document.getElementById("n2").value !== ""){chk2.disabled = false;}
				if(document.getElementById("n3").value !== ""){chk3.disabled = false;}
				if(document.getElementById("n4").value !== ""){chk4.disabled = false;}
				
				document.body.className = "";
			}
		}

			xmlhttp.open("GET","../configuration/configurationanalysedata.php?idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,true);
			xmlhttp.send();
			
			$.ajax({
				url: '../configuration/configurationvaliderencontres.php',
				type: 'GET',
				data: "find=0&idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw,
				success: function (response) {
					var data = JSON.parse(response), id = data.ID, balise1 = data.Balise1, balise2 = data.Balise2, distance = data.Distance, filtrage = data.Filtrage, methode = data.Methode, destinataire = data.Destinataire, list, listf, listff;
					
					for(p=0; p<xnbr; p++){
						list = $("#balise"+p+"").text();
						listf = list.split("=");
						listff = listf[1].split(")");
						if($.trim(listff[0]) === balise2){
							$("#"+p+"").addClass("glyphicon glyphicon-ok");
						}
					}
					
					$("#dis").val(distance); $("#fil").val(filtrage);
					if(methode & 1){chk1.checked = true;}else{chk1.checked = false;}
					if(methode & 2){chk2.checked = true;}else{chk2.checked = false;}
					if(methode & 4){chk3.checked = true;}else{chk3.checked = false;}
					if(methode & 8){chk4.checked = true;}else{chk4.checked = false;}
				}
			})
	}
	
}

var bal;
function getbalise(x){
	var bf, f;
	balisefinale = x.innerHTML;
	bf = balisefinale.split("=");
	f = bf[1].split(")")
	bal = $.trim(f[0]);
}

var y,r=-1;
							
function addsomeclass(y){
	if(r>=0){
		removesomeclass(r);
	}
	r = y;
	$("#"+y+"").addClass("glyphicon glyphicon-ok");
}

function removesomeclass(r){
	$("#"+r+"").removeClass("glyphicon glyphicon-ok");
}

function validerencontre(){
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();

	var numeroAppel = numeroAppelGlobal;

	var modeMessage = modeMessageGlobal;
	
	var dis = $("#dis").val(), fil = $("#fil").val(), chk1 = document.getElementById("chk1"), chk2 = document.getElementById("chk2"), chk3 = document.getElementById("chk3"), chk4 = document.getElementById("chk4"),
	a = 0, sujet;
	// var tel1 = $("#n1").val(), tel2 = $("#n2").val(), tel3 = $("#n3").val(), tel4 = $("#n4").val();
	
	sujet = "Rencontre balise entre "+idTracker+" et "+ bal;
	
	if(chk1.checked == true){a+=1;}	if(chk2.checked == true){a+=2;} if(chk3.checked == true){a+=4;}	if(chk4.checked == true){a+=8;}
	
	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else {
		if(!bal){
			alert("veuillez choisir une balise à surveiller.");
		}else if(bal == idTracker){
			alert("Les balises ne doivent pas être identiques.");
		}else{
			if (confirm("Voulez vous enregistrer/modifier la configuration de rencontre balise entre "+ idTracker +" et "+ bal +" ?")) {
				$.ajax({
					url: '../configuration/configurationvaliderencontres.php',
					type: 'GET',
					data: "find=1&idTracker=" + idTracker + "&balise=" + bal + "&distance=" + dis + "&filtrage=" + fil + "&desmethod=" + a + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw,

					success: function (sujet) {
						alert("La configuration de rencontre balise entre "+ idTracker +" et "+ bal +" a été prise en compte.");
					}
				})
			}
		}
	}
}

function planing_gsm(){
	var xmlhttp = null;
	document.getElementById("nospam2").innerHTML = "0";
	document.getElementById("nospam").innerHTML = "0";
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	if (idTracker==""){
		// alert('Pas de Balise');
		document.body.className = "";
		return;
	}else if(idTracker.search(/,/) != -1){
		alert(getTextVeuillezChoisirQueUneBalise);
		baliseUnSelectAll();
		document.body.className = "";
		return;
	}else{
		if(window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var test=xmlhttp.responseText;
				var reg=new RegExp("[&]+", "g");
				var tableau=test.split(reg);
				
				var act1 = tableau[0].substring(tableau[0].indexOf('<br> Fen1Mode									=> ')+25,tableau[0].indexOf('<br> Fen1Jour')), 
						jour1 = tableau[0].substring(tableau[0].indexOf('<br> Fen1Jour									=> ')+25,tableau[0].indexOf('<br> Fen1HDeb')), 
							de1 = tableau[0].substring(tableau[0].indexOf('<br> Fen1HDeb									=> ')+25,tableau[0].indexOf('<br> Fen1HFin')), 
								fin1 = tableau[0].substring(tableau[0].indexOf('<br> Fen1HFin									=> ')+25,tableau[0].indexOf('<br> Fen1MRepli')),
					act2 = tableau[0].substring(tableau[0].indexOf('<br> Fen2Mode									=> ')+25,tableau[0].indexOf('<br> Fen2Jour')), 
						jour2 = tableau[0].substring(tableau[0].indexOf('<br> Fen2Jour									=> ')+25,tableau[0].indexOf('<br> Fen2HDeb')), 
							de2 = tableau[0].substring(tableau[0].indexOf('<br> Fen2HDeb									=> ')+25,tableau[0].indexOf('<br> Fen2HFin')), 
								fin2 = tableau[0].substring(tableau[0].indexOf('<br> Fen2HFin									=> ')+25,tableau[0].indexOf('<br> Fen2MRepli')),
					act3 = tableau[0].substring(tableau[0].indexOf('<br> Fen3Mode									=> ')+25,tableau[0].indexOf('<br> Fen3Jour')), 
						jour3 = tableau[0].substring(tableau[0].indexOf('<br> Fen3Jour									=> ')+25,tableau[0].indexOf('<br> Fen3HDeb')), 
							de3 = tableau[0].substring(tableau[0].indexOf('<br> Fen3HDeb									=> ')+25,tableau[0].indexOf('<br> Fen3HFin')), 
								fin3 = tableau[0].substring(tableau[0].indexOf('<br> Fen3HFin									=> ')+25,tableau[0].indexOf('<br> Fen3MRepli')),
					act4 = tableau[0].substring(tableau[0].indexOf('<br> Fen4Mode									=> ')+25,tableau[0].indexOf('<br> Fen4Jour')), 
						jour4 = tableau[0].substring(tableau[0].indexOf('<br> Fen4Jour									=> ')+25,tableau[0].indexOf('<br> Fen4HDeb')), 
							de4 = tableau[0].substring(tableau[0].indexOf('<br> Fen4HDeb									=> ')+25,tableau[0].indexOf('<br> Fen4HFin')), 
								fin4 = tableau[0].substring(tableau[0].indexOf('<br> Fen4HFin									=> ')+25,tableau[0].indexOf('<br> Fen4MRepli')),
					act5 = tableau[0].substring(tableau[0].indexOf('<br> Fen5Mode									=> ')+25,tableau[0].indexOf('<br> Fen5Jour')), 
						jour5 = tableau[0].substring(tableau[0].indexOf('<br> Fen5Jour									=> ')+25,tableau[0].indexOf('<br> Fen5HDeb')), 
							de5 = tableau[0].substring(tableau[0].indexOf('<br> Fen5HDeb									=> ')+25,tableau[0].indexOf('<br> Fen5HFin')), 
								fin5 = tableau[0].substring(tableau[0].indexOf('<br> Fen5HFin									=> ')+25,tableau[0].indexOf('<br> Fen5MRepli')),
					act6 = tableau[0].substring(tableau[0].indexOf('<br> Fen6Mode									=> ')+25,tableau[0].indexOf('<br> Fen6Jour')), 
						jour6 = tableau[0].substring(tableau[0].indexOf('<br> Fen6Jour									=> ')+25,tableau[0].indexOf('<br> Fen6HDeb')), 
							de6 = tableau[0].substring(tableau[0].indexOf('<br> Fen6HDeb									=> ')+25,tableau[0].indexOf('<br> Fen6HFin')), 
								fin6 = tableau[0].substring(tableau[0].indexOf('<br> Fen6HFin									=> ')+25,tableau[0].indexOf('<br> Fen6MRepli'));
								
				if(act1 == "2" ){
					document.getElementById("act1").checked = true; document.getElementById("jour1").value = jour1;
						document.getElementById("de1").value = de1; document.getElementById("fin1").value = fin1; 
				}else if(act1 == "0"){
					document.getElementById("act1").checked = false; document.getElementById("jour1").value = jour1;
						document.getElementById("de1").value = de1; document.getElementById("fin1").value = fin1;
				}
				
				if(act2 == "2" ){
					document.getElementById("act2").checked = true; document.getElementById("jour2").value = jour2;
						document.getElementById("de2").value = de2; document.getElementById("fin2").value = fin2;
				}else if(act2 == "0"){
					document.getElementById("act2").checked = false; document.getElementById("jour2").value = jour2;
						document.getElementById("de2").value = de2; document.getElementById("fin2").value = fin2;
				}
				
				if(act3 == "2" ){
					document.getElementById("act3").checked = true; document.getElementById("jour3").value = jour3;
						document.getElementById("de3").value = de3; document.getElementById("fin3").value = fin3;
				}else if(act3 == "0"){
					document.getElementById("act3").checked = false; document.getElementById("jour3").value = jour3;
						document.getElementById("de3").value = de3; document.getElementById("fin3").value = fin3;
				}
				
				if(act4 == "2" ){
					document.getElementById("act4").checked = true; document.getElementById("jour4").value = jour4;
						document.getElementById("de4").value = de4; document.getElementById("fin4").value = fin4;
				}else if(act4 == "0"){
					document.getElementById("act4").checked = false; document.getElementById("jour4").value = jour4;
						document.getElementById("de4").value = de4; document.getElementById("fin4").value = fin4;
				}
				
				if(act5 == "2" ){
					document.getElementById("act5").checked = true; document.getElementById("jour5").value = jour5;
						document.getElementById("de5").value = de5; document.getElementById("fin5").value = fin5;
				}else if(act5 == "0"){
					document.getElementById("act5").checked = false; document.getElementById("jour5").value = jour5;
						document.getElementById("de5").value = de5; document.getElementById("fin5").value = fin5;
				}
				
				if(act6 == "2" ){
					document.getElementById("act6").checked = true; document.getElementById("jour6").value = jour6;
						document.getElementById("de6").value = de6; document.getElementById("fin6").value = fin6;
				}else if(act6 == "0"){
					document.getElementById("act6").checked = false; document.getElementById("jour6").value = jour6;
						document.getElementById("de6").value = de6; document.getElementById("fin6").value = fin6;
				}
				
				
				document.body.className = "";
			}
		}

			xmlhttp.open("GET","../configuration/configurationanalysedata.php?idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,true);
			xmlhttp.send();
	}
}

//*franck*//
function detecterDeplacementEtArret(){
	var xmlhttp = null;
	document.getElementById("nospam2").innerHTML = "0";
	document.getElementById("nospam").innerHTML = "0";
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	if (idTracker==""){
		// alert('Pas de Balise');
		$('#lpb').show();					// Montrer lecture parametres balise
		document.body.className = "";
		return;
	}else if(idTracker.search(/,/) != -1){
		alert(getTextVeuillezChoisirQueUneBalise);
		baliseUnSelectAll();
		document.body.className = "";
		return;
	}else{
		if(window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var test=xmlhttp.responseText;
				var reg=new RegExp("[&]+", "g");
				var tableau=test.split(reg);
				
				var timeVibForApcStart = 0;
				var timeVibForApcStop = 0;
				var speedApc = 5;
				
				if(versionBaliseGlobal == "20"){	// TELTO
					if(idTracker > 356173060000000){		// FM1120 : rien à régler
						$("#detection_deplacement_temps_vib").prop('disabled', true);
						$("#detection_arret_temps_absence_vib").prop('disabled', true);
						
					}else{									// FMB920 : réglage de detection_deplacement_temps_vib et detection_arret_temps_absence_vib
						// Vidage des choix des listes déroulantes
						$("#detection_deplacement_temps_vib option").each(function () {
							$(this).remove();
						});
						$("#detection_arret_temps_absence_vib option").each(function () {
							$(this).remove();
						});
						
						// Ajout des choix acceptés par les FMB920 dans detection_deplacement_temps_vib
						var opt_15s = document.createElement('option');
						opt_15s.value = 15;
						opt_15s.innerHTML = "15s";
						document.getElementById('detection_deplacement_temps_vib').appendChild(opt_15s);
						
						var opt_30s = document.createElement('option');
						opt_30s.value = 30;
						opt_30s.innerHTML = "30s";
						document.getElementById('detection_deplacement_temps_vib').appendChild(opt_30s);
						
						var opt_45s = document.createElement('option');
						opt_45s.value = 45;
						opt_45s.innerHTML = "45s";
						document.getElementById('detection_deplacement_temps_vib').appendChild(opt_45s);
						
						var opt_60s = document.createElement('option');
						opt_60s.value = 60;
						opt_60s.innerHTML = "60s";
						document.getElementById('detection_deplacement_temps_vib').appendChild(opt_60s);
						
						// Ajout des choix acceptés par les FMB920 dans detection_arret_temps_absence_vib
						var opt_arr15s = document.createElement('option');
						opt_arr15s.value = 15;
						opt_arr15s.innerHTML = "15s";
						document.getElementById('detection_arret_temps_absence_vib').appendChild(opt_arr15s);
						
						var opt_arr30s = document.createElement('option');
						opt_arr30s.value = 30;
						opt_arr30s.innerHTML = "30s";
						document.getElementById('detection_arret_temps_absence_vib').appendChild(opt_arr30s);
						
						var opt_arr45s = document.createElement('option');
						opt_arr45s.value = 45;
						opt_arr45s.innerHTML = "45s";
						document.getElementById('detection_arret_temps_absence_vib').appendChild(opt_arr45s);
						
						var opt_arr60s = document.createElement('option');
						opt_arr60s.value = 60;
						opt_arr60s.innerHTML = "60s";
						document.getElementById('detection_arret_temps_absence_vib').appendChild(opt_arr60s);
						
						// Lecture des paramètres de Datas0
						var indexD;
						var indexF;
						
						indexD = tableau[0].indexOf('19001:');
						if(indexD != -1){
							indexD += 6;
							indexF = tableau[0].indexOf(';',indexD);
							timeVibForApcStart = parseInt(tableau[0].substring(indexD, indexF));
						}
						
						indexD = tableau[0].indexOf('19002:');
						if(indexD != -1){
							indexD += 6;
							indexF = tableau[0].indexOf(';',indexD);
							timeVibForApcStop = parseInt(tableau[0].substring(indexD, indexF));
						}
						
						if(timeVibForApcStart == 0) timeVibForApcStart = 30;
						if(timeVibForApcStop == 0) timeVibForApcStop = 60;
						
					}
				}if((versionBaliseGlobal == "3370")||(versionBaliseGlobal == "7003")||(versionBaliseGlobal == "7201")){		// SC NEO & SOLO
					timeVibForApcStart = 30;
					timeVibForApcStop = 30;
					speedApc = 5;
					
					indexD = tableau[0].indexOf('SENSOR,');
					if(indexD != -1){
						indexD += 7;
						indexF = tableau[0].indexOf(',',indexD);
						timeVibForApcStart = parseInt(tableau[0].substring(indexD, indexF));
						
						indexD = indexF + 1;
						indexF = tableau[0].indexOf(',',indexD);
						if(indexF != -1){
							timeVibForApcStop = parseInt(tableau[0].substring(indexD, indexF));
							
							indexD = indexF + 1;
							indexF = tableau[0].indexOf('#',indexD);
							if(indexF != -1){
								speedApc = parseInt(tableau[0].substring(indexD, indexF));
							}
						}
					}
				}else{			// Balise Stancom
					timeVibForApcStart = parseInt(tableau[0].substring(tableau[0].indexOf('<br> TIME VIB FOR APC START')+36,tableau[0].indexOf('<br> TIME VIB FOR APC STOP')));
					timeVibForApcStop = parseInt(tableau[0].substring(tableau[0].indexOf('<br> TIME VIB FOR APC STOP')+35,tableau[0].indexOf('<br> TIMEOUT GPRS Close tcp')));
					speedApc = parseInt(tableau[0].substring(tableau[0].indexOf('<br> SPEED APC')+26,tableau[0].indexOf('<br> TIME NO VIB GPS')));
					speedApc = Math.floor(speedApc * 1.851999999984);
					
					if(timeVibForApcStart == 0) timeVibForApcStart = 30;
					if(timeVibForApcStop == 0) timeVibForApcStop = 90;
					if(speedApc == 0) speedApc = 9;

					//franck - Accelerometre
					var sens = tableau[0].substring(tableau[0].indexOf('<br> AccGravite')+27,tableau[0].indexOf('<br> AccTemps'));
					if(sens < 2){
						$('#sensibilite').val("3");
					}else if(sens > 15){
						$('#sensibilite').val("15");
					}else{
						$('#sensibilite').val(sens);
					}
				}
				
				$('#detection_deplacement_temps_vib').val(timeVibForApcStart);
				$('#detection_arret_temps_absence_vib').val(timeVibForApcStop);
				$('#detection_deplacement_seuil_vitesse').val(speedApc);
				
				document.body.className = "";
			}
		}

			xmlhttp.open("GET","../configuration/configurationanalysedata.php?idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,true);
			xmlhttp.send();
	}
}

function detecterAlertEtSmS(){

	var xmlhttp = null;
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var selectTypeAlert = document.getElementById('select_type_alert');

	document.getElementById("message_apparition").value = "";
	document.getElementById("message_disparition").value = "";

	if (idTracker==""){
		//alert('Pas de Balise');
		$('#lpb').show();							// Montrer lecture parametres balise
		$("#select_type_alert option").each(function()
		{
			$(this).remove();
		});
		document.body.className = "";
		return;
	}else if(idTracker.search(/,/) != -1){
		alert(getTextVeuillezChoisirQueUneBalise);
		baliseUnSelectAll();
		document.body.className = "";
		return;
	}else{
		if(window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var test=xmlhttp.responseText;
				var reg=new RegExp("[&]+", "g");
				var tableau=test.split(reg);
				var filtrageGps = parseInt(tableau[0].substring(tableau[0].indexOf('<br> Filtrage GPS')+27,tableau[0].indexOf('<br> TIME VIB FOR APC START')));
				var mail = tableau[0].substring(tableau[0].indexOf('<br> MAIL1										=> ')+23,tableau[0].indexOf('<br> SRV_SMTP'));
				
				var AjoutePARK = 0;
				var AjouteAL1 = 0;
				var AjouteAL2 = 0;
				var AjouteALALIM = 0;
				var AjouteALBAT = 0;
				var AjouteSeuilBat = 0;
				var warnings2_dest = 0;
				
				$("#select_type_alert option").each(function()
				{
					$(this).remove();
				});
				var nothing = document.createElement('option');
				nothing.value = "nothing";
				nothing.innerHTML = "-- choisir type --";
				nothing.disabled = true;
				nothing.selected = true;
				selectTypeAlert.appendChild(nothing);
				
				var alarmedeplacement = document.createElement('option');
				alarmedeplacement.value = "alarmedeplacement";
				alarmedeplacement.innerHTML = getTextAlarmDeplacement;
				selectTypeAlert.appendChild(alarmedeplacement);

				//alert(versionBaliseGlobal)
				switch(versionBaliseGlobal){
					case "3370":				// SC NEO 3G
						AjoutePARK = 0;
						AjouteAL1 = 2;
						AjouteAL2 = 2;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 0;
						
						warnings2_dest = 1;
						$('#alerte_filtrage').prop('disabled', true);
						$('#lpb').hide();
						
						break;
					case "7003":				// SC NEO
						AjoutePARK = 0;
						AjouteAL1 = 2;
						AjouteAL2 = 2;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 0;
						
						warnings2_dest = 1;
						$('#alerte_filtrage').prop('disabled', true);
						$('#lpb').hide();
						
						break;
					case "7201":				// SC SOLO
						AjoutePARK = 0;
						AjouteAL1 = 2;
						AjouteAL2 = 0;
						AjouteALALIM = 0;
						AjouteALBAT = 1;
						AjouteSeuilBat = 0;
						
						warnings2_dest = 1;
						$('#alerte_filtrage').prop('disabled', true);
						$('#lpb').hide();
					
						break;
					case "20":					// TELTO
						AjoutePARK = 0;
						AjouteAL1 = 0;
						AjouteAL2 = 0;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 0;
						
						warnings2_dest = 1;
						$('#alerte_filtrage').prop('disabled', true);
						$('#lpb').hide();
						
						break;
					case "31":
					case "33":
						AjoutePARK = 1;
						AjouteAL1 = 0;
						AjouteAL2 = 0;
						AjouteALALIM = 0;
						AjouteALBAT = 0;
						AjouteSeuilBat = 0;
						
						document.getElementById("alerte_filtrage").min = 5;
						document.getElementById("alerte_filtrage").value = 5;

						break;
					case "32":
					case "52":
						AjoutePARK = 1;
						AjouteAL1 = 0;
						AjouteAL2 = 0;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 1;
						
						document.getElementById("alerte_filtrage").min = 5;
						document.getElementById("alerte_filtrage").value = 5;
						
						break;
					case "41":
						AjoutePARK = 1;
						AjouteAL1 = 1;
						AjouteAL2 = 1;
						AjouteALALIM = 0;
						AjouteALBAT = 0;
						AjouteSeuilBat = 0;
						
						document.getElementById("alerte_filtrage").min = 5;
						document.getElementById("alerte_filtrage").value = 5;
						
						break;
					case "42":
						AjoutePARK = 1;
						AjouteAL1 = 1;
						AjouteAL2 = 1;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 1;
						
						document.getElementById("alerte_filtrage").min = 5;
						document.getElementById("alerte_filtrage").value = 5;
						
						break;
					case "43":	// SC400MB
						AjoutePARK = 1;
						AjouteAL1 = 0;
						AjouteAL2 = 0;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 2;
						
						document.getElementById("alerte_filtrage").min = 0;
						document.getElementById("alerte_filtrage").value = 0;
						
						break;
					case "44":	// SC300PM
						AjoutePARK = 1;
						AjouteAL1 = 0;
						AjouteAL2 = 0;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 1;
						
						var alarmGoupille = document.createElement('option');
						alarmGoupille.value = "alarmegoupille";
						alarmGoupille.innerHTML = "Alarme Goupille";
						selectTypeAlert.appendChild(alarmGoupille);
						
						document.getElementById("alerte_filtrage").min = 5;
						document.getElementById("alerte_filtrage").value = 5;
						
						break;
					case "45":	// SC400M/ME
						AjoutePARK = 1;
						AjouteAL1 = 0;
						AjouteAL2 = 0;
						AjouteALALIM = 0;
						AjouteALBAT = 0;
						AjouteSeuilBat = 0;
						
						document.getElementById("alerte_filtrage").min = 0;
						document.getElementById("alerte_filtrage").value = 0;
						
						break;
					case "46":
						AjoutePARK = 0;
						AjouteAL1 = 0;
						AjouteAL2 = 0;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 2;
						
						warnings2_dest = 1;
						$('#alerte_filtrage').prop('disabled', true);
						
						document.getElementById("alerte_filtrage").min = 0;
						document.getElementById("alerte_filtrage").value = 0;
						
						break;
					case "47":
						AjoutePARK = 0;
						AjouteAL1 = 3;
						AjouteAL2 = 3;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 3;
						
						warnings2_dest = 1;
						$('#alerte_filtrage').prop('disabled', true);
						
						document.getElementById("alerte_filtrage").min = 0;
						document.getElementById("alerte_filtrage").value = 0;
						
						break;
					case "48":
						AjoutePARK = 0;
						//AjouteAL1 = 1;										// Geo3X
						//AjouteAL2 = 1;										// Geo3X
						AjouteAL1 = 0;										// Geofence
						AjouteAL2 = 0;										// Geofence
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 3;
						
						//warnings2_dest = 0;									// Geo3X
						warnings2_dest = 1;									// Geofence
						$('#alerte_filtrage').prop('disabled', true);		// Geofence
						
						document.getElementById("alerte_filtrage").min = 0;
						document.getElementById("alerte_filtrage").value = 0;
						
						break;
					case "50":
					case "51":
						AjoutePARK = 1;
						AjouteAL1 = 0;
						AjouteAL2 = 0;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 3;
						
						break;
					case "53":				// SC CUBE
						AjoutePARK = 0;
						AjouteAL1 = 2;
						AjouteAL2 = 0;
						AjouteALALIM = 0;
						AjouteALBAT = 1;
						AjouteSeuilBat = 3;
						
						warnings2_dest = 1;
						$('#alerte_filtrage').prop('disabled', true);
						
						break;
/*
					case "53":
						AjoutePARK = 1;
						AjouteAL1 = 2;
						AjouteAL2 = 0;
						AjouteALALIM = 0;
						AjouteALBAT = 1;
						AjouteSeuilBat = 3;
						
						document.getElementById("alerte_filtrage").min = 1;
						document.getElementById("alerte_filtrage").value = 5;
						
						break;
*/
					case "54":
						AjoutePARK = 1;
						AjouteAL1 = 0;
						AjouteAL2 = 0;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 2;
						
						document.getElementById("alerte_filtrage").min = 0;
						document.getElementById("alerte_filtrage").value = 0;
						
						break;
					case "55":
						AjoutePARK = 1;
						AjouteAL1 = 0;
						AjouteAL2 = 0;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 0;
						
						var selectSeuilBat = document.getElementById("select_seuil_bat");
						$('#select_seuil_bat option').remove();
						var opt25 = document.createElement('option');
						opt25.value = "5";
						opt25.innerHTML = "5%";
						var opt50 = document.createElement('option');
						opt50.value = "10";
						opt50.innerHTML = "10%";
						var opt75 = document.createElement('option');
						opt75.value = "15";
						opt75.innerHTML = "15%";
						selectSeuilBat.appendChild(opt25);
						selectSeuilBat.appendChild(opt50);
						selectSeuilBat.appendChild(opt75);
						
						document.getElementById("alerte_filtrage").min = 0;
						document.getElementById("alerte_filtrage").value = 1;
						
						break;
					case "56":
						AjoutePARK = 0;
						AjouteAL1 = 2;
						AjouteAL2 = 0;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 2;
												
						document.getElementById("alerte_filtrage").min = 0;
						document.getElementById("alerte_filtrage").value = 1;
						
						break;
					case "57":
						AjoutePARK = 1;
						AjouteAL1 = 2;
						AjouteAL2 = 0;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 0;
						
						var selectSeuilBat = document.getElementById("select_seuil_bat");
						$('#select_seuil_bat option').remove();
						var opt25 = document.createElement('option');
						opt25.value = "5";
						opt25.innerHTML = "5%";
						var opt50 = document.createElement('option');
						opt50.value = "10";
						opt50.innerHTML = "10%";
						var opt75 = document.createElement('option');
						opt75.value = "15";
						opt75.innerHTML = "15%";
						selectSeuilBat.appendChild(opt25);
						selectSeuilBat.appendChild(opt50);
						selectSeuilBat.appendChild(opt75);
						
						document.getElementById("alerte_filtrage").min = 0;
						document.getElementById("alerte_filtrage").value = 1;
						
						break;
					case "undefined":
						AjoutePARK = 1;
						AjouteAL1 = 1;
						AjouteAL2 = 1;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 2;
					
						document.getElementById("alerte_filtrage").min = 0;
						document.getElementById("alerte_filtrage").value = 0;
						
						break;
					default:
						AjoutePARK = 1;
						AjouteAL1 = 1;
						AjouteAL2 = 1;
						AjouteALALIM = 1;
						AjouteALBAT = 1;
						AjouteSeuilBat = 2;
						
						document.getElementById("alerte_filtrage").min = 0;
						document.getElementById("alerte_filtrage").value = 0;
						
						break;
				}
				
				// PARK
				if(AjoutePARK == 1)
				{
					var alarmeparking = document.createElement('option');
					alarmeparking.value = "alarmeparking";
					alarmeparking.innerHTML = getTextAlarmParking;
					selectTypeAlert.appendChild(alarmeparking);
				}
				
				// AL1
				if(AjouteAL1 == 1)
				{
					var selectAlarm1 = document.createElement('option');
					selectAlarm1.value = "alarme1";
					selectAlarm1.innerHTML = getTextAlarm+" 1";
					selectTypeAlert.appendChild(selectAlarm1);
				}
				else if(AjouteAL1 == 2)
				{
					var selectAlarm1 = document.createElement('option');
					selectAlarm1.value = "alarme1";
					selectAlarm1.innerHTML = "Alarme Arrachement";
					selectTypeAlert.appendChild(selectAlarm1);
				}
				else if(AjouteAL1 == 3)
				{
					var selectAlarm1 = document.createElement('option');
					selectAlarm1.value = "alarme1";
					selectAlarm1.innerHTML = "Alarme Porte";
					selectTypeAlert.appendChild(selectAlarm1);
				}
				
				// AL2
				if(AjouteAL2 == 1)
				{
					var selectAlarm2 = document.createElement('option');
					selectAlarm2.value = "alarme2";
					selectAlarm2.innerHTML = getTextAlarm+" 2";
					selectTypeAlert.appendChild(selectAlarm2);
				}
				else if(AjouteAL2 == 2)
				{
					var selectAlarm2 = document.createElement('option');
					selectAlarm2.value = "alarme2";
					selectAlarm2.innerHTML = "Alarme Couvercle";
					selectTypeAlert.appendChild(selectAlarm2);
				}
				else if(AjouteAL2 == 3)
				{
					var selectAlarm2 = document.createElement('option');
					selectAlarm2.value = "alarme2";
					selectAlarm2.innerHTML = "Surveillance";
					selectTypeAlert.appendChild(selectAlarm2);
				}
				
				// ALIM
				if(AjouteALALIM == 1)
				{
					var alarmealimentation = document.createElement('option');
					alarmealimentation.value = "alarmealimentation";
					alarmealimentation.innerHTML = getTextAlarmAlimentation;
					selectTypeAlert.appendChild(alarmealimentation);
				}
				
				// BAT
				if(AjouteALBAT == 1)
				{
					var selectAlarmBat = document.createElement('option');
					selectAlarmBat.value = "alarmebat";
					selectAlarmBat.innerHTML = getTextAlarmBatterie;
					selectTypeAlert.appendChild(selectAlarmBat);
				}
				
				// Seuil
				if(AjouteSeuilBat == 1)
				{
					var selectSeuilBat = document.getElementById("select_seuil_bat");
					$('#select_seuil_bat option').remove();
					var opt25 = document.createElement('option');
					opt25.value = "1";
					opt25.innerHTML = "10%";
					selectSeuilBat.appendChild(opt25);
					
					var opt50 = document.createElement('option');
					opt50.value = "2";
					opt50.innerHTML = "20%";
					selectSeuilBat.appendChild(opt50);
					
					var opt75 = document.createElement('option');
					opt75.value = "3";
					opt75.innerHTML = "30%";
					selectSeuilBat.appendChild(opt75);
				}
				else if(AjouteSeuilBat > 2)
				{
					var selectSeuilBat = document.getElementById("select_seuil_bat");
					$('#select_seuil_bat option').remove();
					var opt10 = document.createElement('option');
					opt10.value = "1";
					opt10.innerHTML = "10%";
					selectSeuilBat.appendChild(opt10);
					
					var opt20 = document.createElement('option');
					opt20.value = "2";
					opt20.innerHTML = "20%";
					selectSeuilBat.appendChild(opt20);
					
					var opt30 = document.createElement('option');
					opt30.value = "3";
					opt30.innerHTML = "30%";
					selectSeuilBat.appendChild(opt30);
					
					if(AjouteSeuilBat == 3)
					{
						var opt40 = document.createElement('option');
						opt40.value = "4";
						opt40.innerHTML = "40%";
						selectSeuilBat.appendChild(opt40);
					}
				}
				
				// TEL
				if(warnings2_dest == 1)
				{
					var telephone1 = "";
					var telephone2 = "";
					var telephone3 = "";
					var telephone4 = "";
					
					$.ajax({
						url: '../geofencing/geofencingzonewarningdest.php',
						type: 'GET',
						async: false,
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
								
								if(dest01) telephone1 = dest01;
								if(dest02) telephone2 = dest02;
								if(dest03) telephone3 = dest03;
								if(dest04) telephone4 = dest04;
							}
						}
					});
				}
				else
				{
					var telephone1 = tableau[0].substring(tableau[0].indexOf('<br> TELEPHONE 1')+28,tableau[0].indexOf('<br> TELEPHONE 2'));
					var telephone2 = tableau[0].substring(tableau[0].indexOf('<br> TELEPHONE 2')+28,tableau[0].indexOf('<br> TELEPHONE 3'));
					var telephone3 = tableau[0].substring(tableau[0].indexOf('<br> TELEPHONE 3')+28,tableau[0].indexOf('<br> TELEPHONE 4'));
					var telephone4 = tableau[0].substring(tableau[0].indexOf('<br> TELEPHONE 4')+28,tableau[0].indexOf('<br> MESSAGE APPARITION ALARME 1'));
				}
				
				if(escape(telephone1[0]) != "%00")document.getElementById("message_numero_1").value = escape(telephone1).replace(/%00/g,"");
				if(escape(telephone2[0]) != "%00")document.getElementById("message_numero_2").value = escape(telephone2).replace(/%00/g,"");
				if(escape(telephone3[0]) != "%00")document.getElementById("message_numero_3").value = escape(telephone3).replace(/%00/g,"");
				if(escape(telephone4[0]) != "%00")document.getElementById("message_numero_4").value = escape(telephone4).replace(/%00/g,"");
				
				mail = unescape(escape(mail).replace(/%00/g,"").replace(/%uFFFD/g,""));
				$('#mail_as').val(mail);
				
				//document.getElementById("alerte_filtrage").value = filtrageGps;
				document.getElementById("alerte_filtrage").value = "";

				document.body.className = "";
			}
		}

			xmlhttp.open("GET","../configuration/configurationanalysedata.php?idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,true);
			xmlhttp.send();
	}
}

function choisirModeFonctionnement(mode){

	var connexionGSM = document.getElementById("connexion_gsm").value;

	if(mode == "normal")
	{
		document.getElementById('select_freq_acquisition_trajet').disabled = false;
		document.getElementById('select_freq_acquisition_arret').disabled = false;
		document.getElementById('select_freq_acquisition_timing').disabled = true;
		document.getElementById('select_freq_rapatriement_trajet').disabled = false;
		document.getElementById('select_freq_rapatriement_arret').disabled = false;
		document.getElementById('select_freq_rapatriement_timing').disabled = true;
		
		if((versionBaliseGlobal == "3370")||(versionBaliseGlobal == "7003")||(versionBaliseGlobal == "7201")){
			$('#div_freq_acq_trajet').show();
			$('#div_freq_acq_timing').hide();
			$('#div_freq_acq_arret').show();
			$('#div_freq_rap_trajet').show();
			$('#div_freq_rap_timing').hide();
			$('#div_freq_rap_arret').show();
			
			if(versionBaliseGlobal == "3370"){
				$("#labelgsm").show(); $('#connexion_gsm').show();
			}
			
		}else{
			$("#labelgsm").show(); $('#connexion_gsm').show();
			if((connexionGSM == "actifarret")||(connexionGSM == "actiftrajet")||(versionBaliseGlobal == "56")){
				$("#r").show(); $("#rt").show();
			}
		}
	}
	else if(mode == "historique")
	{
		document.getElementById('select_freq_acquisition_trajet').disabled = false;
		document.getElementById('select_freq_acquisition_arret').disabled = false;
		document.getElementById('select_freq_acquisition_timing').disabled = true;
		document.getElementById('select_freq_rapatriement_trajet').disabled = true;
		document.getElementById('select_freq_rapatriement_arret').disabled = true;
		document.getElementById('select_freq_rapatriement_timing').disabled = false;
		$("#labelgsm").hide(); $('#connexion_gsm').hide();			// cacher GSM
		$("#r").hide(); $("#rt").hide();

		if((versionBaliseGlobal == "3370")||(versionBaliseGlobal == "7003")||(versionBaliseGlobal == "7201")){
			$('#div_freq_acq_trajet').show();
			$('#div_freq_acq_timing').hide();
			$('#div_freq_acq_arret').show();
			$('#div_freq_rap_trajet').hide();
			$('#div_freq_rap_timing').show();
			$('#div_freq_rap_arret').hide();
		}
	}
	else if(mode == "periscope")
	{
		document.getElementById('select_freq_acquisition_trajet').disabled = true;
		document.getElementById('select_freq_acquisition_arret').disabled = true;
		document.getElementById('select_freq_acquisition_timing').disabled = false;
		document.getElementById('select_freq_rapatriement_trajet').disabled = true;
		document.getElementById('select_freq_rapatriement_arret').disabled = true;
		document.getElementById('select_freq_rapatriement_timing').disabled = false;
		$("#labelgsm").hide(); $('#connexion_gsm').hide();			// cacher GSM
		$("#r").hide(); $("#rt").hide();

		if((versionBaliseGlobal == "3370")||(versionBaliseGlobal == "7003")||(versionBaliseGlobal == "7201")){
			$('#div_freq_acq_trajet').hide();
			$('#div_freq_acq_timing').show();
			$('#div_freq_acq_arret').hide();
			$('#div_freq_rap_trajet').hide();
			$('#div_freq_rap_timing').show();
			$('#div_freq_rap_arret').hide();
		}
	}
}

function pad (str, max) {
	str = str.toString();
	return str.length < max ? pad("0" + str, max) : str;
}
function smsConverted(sms){
	if(sms[0] == "1"){
		document.getElementById('disparition_numero_4').checked = true;
		document.getElementById('numero_4').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('disparition_numero_4').checked = false;
		document.getElementById('numero_4').style.backgroundColor = "";
	}
	if(sms[1] == "1"){
		document.getElementById('disparition_numero_3').checked = true;
		document.getElementById('numero_3').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('disparition_numero_3').checked = false;
		document.getElementById('numero_3').style.backgroundColor = "";
	}
	if(sms[2] == "1"){
		document.getElementById('disparition_numero_2').checked = true;
		document.getElementById('numero_2').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('disparition_numero_2').checked = false;
		document.getElementById('numero_2').style.backgroundColor = "";
	}
	if(sms[3] == "1"){
		document.getElementById('disparition_numero_1').checked = true;
		document.getElementById('numero_1').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('disparition_numero_1').checked = false;
		document.getElementById('numero_1').style.backgroundColor = "";
	}
	if(sms[4] == "1"){
		document.getElementById('apparition_numero_4').checked = true;
		document.getElementById('numero_4').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('apparition_numero_4').checked = false;
		document.getElementById('numero_4').style.backgroundColor = "";
	}
	if(sms[5] == "1"){
		document.getElementById('apparition_numero_3').checked = true;
		document.getElementById('numero_3').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('apparition_numero_3').checked = false;
		document.getElementById('numero_3').style.backgroundColor = "";
	}
	if(sms[6] == "1"){
		document.getElementById('apparition_numero_2').checked = true;
		document.getElementById('numero_2').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('apparition_numero_2').checked = false;
		document.getElementById('numero_2').style.backgroundColor = "";
	}
	if(sms[7] == "1"){
		document.getElementById('apparition_numero_1').checked = true;
		document.getElementById('numero_1').style.backgroundColor = "#00FF00";
	}else{
		document.getElementById('apparition_numero_1').checked = false;
		document.getElementById('numero_1').style.backgroundColor = "";
	}
}
function minAlerteFiltrage(object,value)
{
	object.value = value;
	if (value < object.min)
		object.value = object.min;
}
function typeAlerte(id){
	// alert(id);
	var xmlhttp = null;
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	document.getElementById("checkbox_alert_active_desactive").checked = false;
	if (idTracker==""){
		//alert('Pas de Balise');
		return;
	}
//	else if( $.inArray(versionBaliseGlobal, ['20','46','47','53','3370','7003','7201']) >= 0)			// Geo3X : TELTO, 400n, CUBE, NEO 3G, NEO & SOLO
	else if( $.inArray(versionBaliseGlobal, ['20','46','47','53','3370','7003','7201']) >= 0 || ((versionBaliseGlobal == "48")&&(id =="alarmedeplacement")) )	// Geofence : TELTO, 400n, CUBE, NEO 3G, NEO & SOLO + alarme deplacement de SC400LC
	{
		var TypeAlarme = 0;
		
		if(id == "alarmebat")
			TypeAlarme = 5;
		else if(id =="alarmealimentation")
			TypeAlarme = 6;
		else if(id =="alarmedeplacement")
			TypeAlarme = 7;
		else if(id == "alarme1")
			TypeAlarme = 8;
		else if(id == "alarme2")
			TypeAlarme = 9;
		
		
		$('#alerte_filtrage').val("");
		$('#input_alert_temps_reel').val("");
		$('#select_seuil_bat').val("");
		
		$('#alerte_filtrage').prop('disabled', true);
		$('#input_alert_temps_reel').prop('disabled', true);
		$('#select_seuil_bat').prop('disabled', true);
		

		if((versionBaliseGlobal == "47")||(versionBaliseGlobal == "53")){		// FLEET, CUBE
			
			if(window.XMLHttpRequest)
				xmlhttp=new XMLHttpRequest();
			else
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					var test=xmlhttp.responseText;
					var reg=new RegExp("[&]+", "g");
					var tableau=test.split(reg);
					
					if(TypeAlarme == 5){
						var seuilBattery = parseInt(tableau[0].substring(tableau[0].indexOf('<br> SEUIL BATTERY')+29,tableau[0].indexOf('<br> BAT TIME BETWEEN')));
						var batTimeBetween = parseInt(tableau[0].substring(tableau[0].indexOf('<br> BAT TIME BETWEEN')+31,tableau[0].indexOf('<br> ALIM TIME BETWEEN')));
						
						$('#alerte_filtrage').val(batTimeBetween);
						$('#alerte_filtrage').prop('disabled', false);
						
						$('#select_seuil_bat').val(seuilBattery);
						$('#select_seuil_bat').prop('disabled', false);
					}
					else if(TypeAlarme == 8)
					{
						var realtimeAl1 = parseInt(tableau[0].substring(tableau[0].indexOf('<br> REALTIME AL1')+28,tableau[0].indexOf('<br> REALTIME AL2')));
						var al1TimeBetween = parseInt(tableau[0].substring(tableau[0].indexOf('<br> AL1 TIME BETWEEN')+31,tableau[0].indexOf('<br> CFG AL2')));
						
						$('#alerte_filtrage').val(al1TimeBetween);
						$('#alerte_filtrage').prop('disabled', false);
						
						$('#input_alert_temps_reel').val(realtimeAl1);
						$('#input_alert_temps_reel').prop('disabled', false);
					}
					else if((TypeAlarme == 9)&&(versionBaliseGlobal == "47"))
					{
						//var realtimeAl2 = parseInt(tableau[0].substring(tableau[0].indexOf('<br> REALTIME AL2')+28,tableau[0].indexOf('<br> CFG ALA APC')));
						var al2TimeBetween = parseInt(tableau[0].substring(tableau[0].indexOf('<br> AL2 TIME BETWEEN')+31,tableau[0].indexOf('<br> REALTIME AL1')));
						
						$('#alerte_filtrage').val(al2TimeBetween);
						$('#alerte_filtrage').prop('disabled', false);
						
						//$('#input_alert_temps_reel').val(realtimeAl2);
						//$('#input_alert_temps_reel').prop('disabled', false);
					}
				}
			}
			xmlhttp.open("GET","../configuration/configurationanalysedata.php?idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,true);
			xmlhttp.send();
		}
		
		
		$.ajax({
			url: '../geofencing/geofencingzonewarning.php',
			type: 'GET',
			async: false,
			data: "nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&idTracker=" + idTracker + "&zone=1&Type_Geometrie=" + TypeAlarme,
			success: function (response) {
				if (response) {
					var chaine = response;
					var reg = new RegExp("[&]+", "g");
					var tableau = chaine.split(reg);

					var destMethod = tableau[0].substring(tableau[0].indexOf('Dest_Method') + 12, tableau[0].indexOf('Warning_Type'));
					var cfg = tableau[0].substring(tableau[0].indexOf('Warning_Type') + 13, tableau[0].indexOf('Msg_app'));
					var msgApp = tableau[0].substring(tableau[0].indexOf('Msg_app') + 8, tableau[0].indexOf('Msg_disp'));
					var msgDisp = tableau[0].substring(tableau[0].indexOf('Msg_disp') + 9);
					
					document.getElementById("message_apparition").value = unescape(escape(msgApp).replace(/%00/g,""));
					document.getElementById("message_disparition").value = unescape(escape(msgDisp).replace(/%00/g,""));
					
					if (cfg == "1") {
						document.getElementById('alert_active_desactive').innerHTML = getTextModeBienAlertActive
						document.getElementById('alert_active_desactive').style.backgroundColor = '#00FF00';
						document.getElementById('checkbox_alert_active_desactive').checked = true;
						document.getElementById('alert_active').style.visibility = "visible";
					} else {
						document.getElementById('alert_active_desactive').innerHTML = getTextModeBienAlertDesactive;
						document.getElementById('alert_active_desactive').style.backgroundColor = '';
						document.getElementById('checkbox_alert_active_desactive').checked = false;
						document.getElementById('alert_active').style.visibility = "hidden";
					}
					
					destMethod = pad(parseInt(destMethod, 10).toString(2), 8);
					smsConverted(destMethod);
				}
			}
		});
	}else{
		if(window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var test=xmlhttp.responseText;
				var reg=new RegExp("[&]+", "g");
				var tableau=test.split(reg);

				var cfg = "0";
				var msgApp = "";
				var msgDisp = "";
				
				var messageApparitionAlarme1 = unescape(escape(tableau[0].substring(tableau[0].indexOf('<br> MESSAGE APPARITION ALARME 1')+40,tableau[0].indexOf('<br> MESSAGE	DISPARITION ALARME 1'))).replace(/%uFFFD/g,""));
				var messageDisparitionAlarme1 =  unescape(escape(tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	DISPARITION ALARME 1')+40,tableau[0].indexOf('<br> MESSAGE	APPARITION ALARME 2'))).replace(/%uFFFD/g,""));
				var messageApparitionAlarme2 =  unescape(escape(tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	APPARITION ALARME 2')+40,tableau[0].indexOf('<br> MESSAGE	DISPARITION ALARME 2'))).replace(/%uFFFD/g,""));
				var messageDisparitionAlarme2 =  unescape(escape(tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	DISPARITION ALARME 2')+40,tableau[0].indexOf('<br> MESSAGE	APPARITION APC'))).replace(/%uFFFD/g,""));
				var messageApparitionAlarmeApc =  unescape(escape(tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	APPARITION APC')+37,tableau[0].indexOf('<br> MESSAGE	DISPARITION APC'))).replace(/%uFFFD/g,""));
				var messageDisparitionAlarmeApc =  unescape(escape(tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	DISPARITION APC')+37,tableau[0].indexOf('<br> MESSAGE	BATTERY FAIBLE ALARME BAT'))).replace(/%uFFFD/g,""));
				var messageBatteryFaibleAlarmeBat =  unescape(escape(tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	BATTERY FAIBLE ALARME BAT')+45,tableau[0].indexOf('<br> MESSAGE	BATTERY OK ALARME BAT'))).replace(/%uFFFD/g,""));
				var messageBatteryOkAlarmeBat =  unescape(escape(tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	BATTERY OK ALARME BAT')+42,tableau[0].indexOf('<br> MESSAGE	ALIM DEFAULT ALARME ALIM'))).replace(/%uFFFD/g,""));
				var messageAlimDefaultAlarmeAlim =  unescape(escape(tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	ALIM DEFAULT ALARME ALIM')+44,tableau[0].indexOf('<br> MESSAGE	ALIM OK ALARME ALIM'))).replace(/%uFFFD/g,""));
				var messageAlimOkAlarmeAlim =  unescape(escape(tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	ALIM OK ALARME ALIM')+40,tableau[0].indexOf('<br> NO/NF ALARM1'))).replace(/%uFFFD/g,""));
				var messageApparitionAlarmeParking = unescape(escape(tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	APPARITION ALARME PARKING')+44,tableau[0].indexOf('<br> MESSAGE	RETABLISSEMENT ALARME PARKING'))).replace(/%uFFFD/g,""));
				var messageRetablissementAlarmeParking = unescape(escape(tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	RETABLISSEMENT ALARME PARKING')+47,tableau[0].indexOf('<br> MESSAGE	GEO 1 APPARITION'))).replace(/%uFFFD/g,""));

				var cfgAl1 = tableau[0].substring(tableau[0].indexOf('<br> CFG AL1')+25,tableau[0].indexOf('<br> OLD AL1'));
				var smsAl1 = tableau[0].substring(tableau[0].indexOf('<br> SMS AL1')+25,tableau[0].indexOf('<br> AL1 TIME BETWEEN'));
				var smsAl1Converted = pad(parseInt(smsAl1, 10).toString(2), 8);
				var realtimeAl1 = parseInt(tableau[0].substring(tableau[0].indexOf('<br> REALTIME AL1')+28,tableau[0].indexOf('<br> REALTIME AL2')));
				var al1TimeBetween = parseInt(tableau[0].substring(tableau[0].indexOf('<br> AL1 TIME BETWEEN')+31,tableau[0].indexOf('<br> CFG AL2')));
				var noNfAlarm1 =  tableau[0].substring(tableau[0].indexOf('<br> NO/NF ALARM1')+28,tableau[0].indexOf('<br> NO/NF ALARM2'));

				var cfgAl2 = tableau[0].substring(tableau[0].indexOf('<br> CFG AL2')+25,tableau[0].indexOf('<br> OLD AL2'));
				var smsAl2 = tableau[0].substring(tableau[0].indexOf('<br> SMS AL2')+25,tableau[0].indexOf('<br> AL2 TIME BETWEEN'));
				var smsAl2Converted = pad(parseInt(smsAl2, 10).toString(2), 8);
				var realtimeAl2 = parseInt(tableau[0].substring(tableau[0].indexOf('<br> REALTIME AL2')+28,tableau[0].indexOf('<br> CFG ALA APC')));
				var al2TimeBetween = parseInt(tableau[0].substring(tableau[0].indexOf('<br> AL2 TIME BETWEEN')+31,tableau[0].indexOf('<br> REALTIME AL1')));
				var noNfAlarm2 =  tableau[0].substring(tableau[0].indexOf('<br> NO/NF ALARM2')+28,tableau[0].indexOf('<br> NO/NF APC'));

				var seuilBattery = parseInt(tableau[0].substring(tableau[0].indexOf('<br> SEUIL BATTERY')+29,tableau[0].indexOf('<br> BAT TIME BETWEEN')));
				var seuilalim = tableau[0].substring(tableau[0].indexOf('<br> SEUIL_ALIM									=> ')+27,tableau[0].indexOf('<br> Cut'));
//alert(seuilalim); console.log(seuilalim);
				var seuil2 = tableau[0].substring(tableau[0].indexOf('<br> SEUIL2_AL_BAT								=> ')+29,tableau[0].indexOf('<br> SEUIL3_AL_BAT'));
				var seuil3 = tableau[0].substring(tableau[0].indexOf('<br> SEUIL3_AL_BAT								=> ')+29,tableau[0].indexOf('<br> MODE_GPRS_SB')); 
				var asbf = tableau[0].substring(tableau[0].indexOf('<br> MODE_BAT									=> ')+25,tableau[0].indexOf('<br> SEUIL2_AL_BAT')); 
				
				var batTimeBetween = parseInt(tableau[0].substring(tableau[0].indexOf('<br> BAT TIME BETWEEN')+31,tableau[0].indexOf('<br> ALIM TIME BETWEEN')));
				var cfgAlaBat = tableau[0].substring(tableau[0].indexOf('<br> CFG ALA BAT')+28,tableau[0].indexOf('<br> SMS ALA BAT'));
				var smsAlaBat = tableau[0].substring(tableau[0].indexOf('<br> SMS ALA BAT')+28,tableau[0].indexOf('<br> CFG ALA ALIM'));
				var smsAlaBatConverted = pad(parseInt(smsAlaBat, 10).toString(2), 8);


				var cfgModePark = parseInt(tableau[0].substring(tableau[0].indexOf('<br> CFG MODE_PARK')+29,tableau[0].indexOf('<br> LAPS MODE_PARK')));
				var lapsModePark = parseInt(tableau[0].substring(tableau[0].indexOf('<br> LAPS MODE_PARK')+30,tableau[0].indexOf('<br> RT MODE_PARK')));
				var realtimePark = parseInt(tableau[0].substring(tableau[0].indexOf('<br> RT MODE_PARK')+28,tableau[0].indexOf('<br> STATE MODE_PARK')));
				var nbPosModePark = tableau[0].substring(tableau[0].indexOf('<br> NB POS MODEPARK')+31,tableau[0].indexOf('<br> VIB_MODEPARK'));
				var vibModePark = tableau[0].substring(tableau[0].indexOf('<br> VIB_MODEPARK')+28,tableau[0].indexOf('<br> SPEED_MODEPARK'));
				var speedModePark = tableau[0].substring(tableau[0].indexOf('<br> SPEED_MODEPARK')+30,tableau[0].indexOf('<br> MODE_PARKING'));
				var modeParking = tableau[0].substring(tableau[0].indexOf('<br> MODE_PARKING')+28,tableau[0].indexOf('<br> MESSAGE	APPARITION ALARME PARKING'));
				var modeParkingConverted = pad(parseInt(modeParking, 10).toString(2), 3);
				var smsModePark = tableau[0].substring(tableau[0].indexOf('<br> SMS MODE_PARK')+29,tableau[0].indexOf('<br> NB POS MODEPARK'));
				var smsModeParkConverted = pad(parseInt(smsModePark, 10).toString(2), 8);


				var alimTimeBetween = parseInt(tableau[0].substring(tableau[0].indexOf('<br> ALIM TIME BETWEEN')+32,tableau[0].indexOf('<br> tryPDP')));
				var cfgAlaAlim = tableau[0].substring(tableau[0].indexOf('<br> CFG ALA ALIM')+28,tableau[0].indexOf('<br> SMS ALA ALIM'));
				var smsAlaAlim = tableau[0].substring(tableau[0].indexOf('<br> SMS ALA ALIM')+28,tableau[0].indexOf('<br> SEUIL BATTERY'));
				var smsAlaAlimConverted = pad(parseInt(smsAlaAlim, 10).toString(2), 8);

				var apcBetween = parseInt(tableau[0].substring(tableau[0].indexOf('<br> APC BETWEEN')+28,tableau[0].indexOf('<br> TELEPHONE 1')));
				var cfgAlaApc = tableau[0].substring(tableau[0].indexOf('<br> CFG ALA APC')+28,tableau[0].indexOf('<br> SMS ALA APC'));
				var smsAlaApc = tableau[0].substring(tableau[0].indexOf('<br> SMS ALA APC')+28,tableau[0].indexOf('<br> CFG ALA BAT'));
				var smsAlaApcConverted = pad(parseInt(smsAlaApc, 10).toString(2), 8);
				var realtimeApc = tableau[0].substring(tableau[0].indexOf('<br> RealtimeAPC')+28);

				if(id =="alarmedeplacement") {
					$("#abat").hide();
					document.getElementById("select_seuil_bat").disabled = true;
					document.getElementById("alertsms_normalement").disabled= true;
					document.getElementById("input_alert_temps_reel").disabled = true;
					$('#input_alert_temps_reel').val("");
					document.getElementById("alert_active_parking").style.visibility = "hidden";
					minAlerteFiltrage(document.getElementById("alerte_filtrage"),apcBetween);
					//document.getElementById("alerte_filtrage").value = apcBetween;
					
					msgApp = messageApparitionAlarmeApc;
					msgDisp = messageDisparitionAlarmeApc;
					cfg = cfgAlaApc;
					smsConverted(smsAlaApcConverted);
				}
				
				if(id =="alarmeparking"){
					$("#abat").hide();
					document.getElementById("select_seuil_bat").disabled = true;
					document.getElementById("alertsms_normalement").disabled= true;
					document.getElementById("input_alert_temps_reel").disabled = true;
					$('#input_alert_temps_reel').val(realtimePark);
					document.getElementById("alert_active_parking").style.visibility = "hidden";
					minAlerteFiltrage(document.getElementById("alerte_filtrage"),lapsModePark);


					document.getElementById('alert_active_desactive').innerHTML = getTextModeBienAlertDesactive;
					document.getElementById('alert_active_desactive').style.backgroundColor = '';
					document.getElementById('alert_active').style.visibility = "hidden";
					document.getElementById('detection_alerte_vibration').value = vibModePark;
					document.getElementById('detection_alerte_vitesse').value = Math.floor(parseInt(speedModePark) /  0.53995680346039 );
					
					msgApp = messageApparitionAlarmeParking;
					msgDisp = messageRetablissementAlarmeParking;
					cfg = cfgModePark;
					if (cfg == 1) {
						document.getElementById("alert_active_parking").style.visibility = "visible";
					}
					smsConverted(smsModeParkConverted);
					
					if(modeParkingConverted[0] == "1"){
						document.getElementById('checkbox_parking_sur_vitesse').checked = true;
						document.getElementById('parking_sur_vitesse').style.backgroundColor = "#00FF00";
					}else{
						document.getElementById('checkbox_parking_sur_vitesse').checked = false;
						document.getElementById('parking_sur_vitesse').style.backgroundColor = "";
					}
					if(modeParkingConverted[1] == "1"){
						document.getElementById('checkbox_parking_sur_vibration').checked = true;
						document.getElementById('parking_sur_vibration').style.backgroundColor = "#00FF00";
					}else{
						document.getElementById('checkbox_parking_sur_vibration').checked = false;
						document.getElementById('parking_sur_vibration').style.backgroundColor = "";
					}
					if(modeParkingConverted[2] == "1"){
						document.getElementById('checkbox_parking_sur_deplacement').checked = true;
						document.getElementById('parking_sur_deplacement').style.backgroundColor = "#00FF00";
					}else{
						document.getElementById('checkbox_parking_sur_deplacement').checked = false;
						document.getElementById('parking_sur_deplacement').style.backgroundColor = "";
					}
				}

				if(id =="alarmealimentation"){
					$("#abat").hide();
					if(versionBaliseGlobal == "55" || versionBaliseGlobal == "57"){
						document.getElementById("select_seuil_bat").disabled = false;
						var selectSeuilBat = document.getElementById("select_seuil_bat");
						$('#select_seuil_bat option').remove();
						var opt4 = document.createElement('option'); opt4.value = "4"; opt4.innerHTML = "6,50 V (4%)";
						var opt5 = document.createElement('option'); opt5.value = "5"; opt5.innerHTML = "6,75 V (5%)";
						var opt6 = document.createElement('option'); opt6.value = "6"; opt6.innerHTML = "7,00 V (6%)";
						var opt7 = document.createElement('option'); opt7.value = "7"; opt7.innerHTML = "7,25 V (7%)";
						var opt8 = document.createElement('option'); opt8.value = "8"; opt8.innerHTML = "7,50 V (8%)";
						var opt9 = document.createElement('option'); opt9.value = "9"; opt9.innerHTML = "7,75 V (9%)";
						
						var opt18 = document.createElement('option'); opt18.value = "18"; opt18.innerHTML = "10,05 V (18%)";
						var opt19 = document.createElement('option'); opt19.value = "19"; opt19.innerHTML = "10,30 V (19%)";
						var opt20 = document.createElement('option'); opt20.value = "20"; opt20.innerHTML = "10,55 V (20%)";
						var opt21 = document.createElement('option'); opt21.value = "21"; opt21.innerHTML = "10,80 V (21%)";
						var opt22 = document.createElement('option'); opt22.value = "22"; opt22.innerHTML = "11,05 V (22%)";
						var opt23 = document.createElement('option'); opt23.value = "23"; opt23.innerHTML = "11,30 V (23%)";
						var opt24 = document.createElement('option'); opt24.value = "24"; opt24.innerHTML = "11,55 V (24%)";
						var opt25 = document.createElement('option'); opt25.value = "25"; opt25.innerHTML = "11,80 V (25%)";
						
						var opt33 = document.createElement('option'); opt33.value = "33"; opt33.innerHTML = "13,85 V (33%)";
						var opt34 = document.createElement('option'); opt34.value = "34"; opt34.innerHTML = "14,10 V (34%)";
						var opt35 = document.createElement('option'); opt35.value = "35"; opt35.innerHTML = "14,35 V (35%)";
						var opt36 = document.createElement('option'); opt36.value = "36"; opt36.innerHTML = "14,60 V (36%)";
						var opt37 = document.createElement('option'); opt37.value = "37"; opt37.innerHTML = "14,85 V (37%)";
						var opt38 = document.createElement('option'); opt38.value = "38"; opt38.innerHTML = "15,11 V (38%)";
						selectSeuilBat.appendChild(opt4); selectSeuilBat.appendChild(opt5); selectSeuilBat.appendChild(opt6);
						selectSeuilBat.appendChild(opt7); selectSeuilBat.appendChild(opt8); selectSeuilBat.appendChild(opt9);
						
						selectSeuilBat.appendChild(opt18); selectSeuilBat.appendChild(opt19); selectSeuilBat.appendChild(opt20);
						selectSeuilBat.appendChild(opt21); selectSeuilBat.appendChild(opt22); selectSeuilBat.appendChild(opt23);
						selectSeuilBat.appendChild(opt24); selectSeuilBat.appendChild(opt25);
						
						selectSeuilBat.appendChild(opt33); selectSeuilBat.appendChild(opt34); selectSeuilBat.appendChild(opt35);
						selectSeuilBat.appendChild(opt36); selectSeuilBat.appendChild(opt37); selectSeuilBat.appendChild(opt38);
						
						if(seuilalim<4) seuilalim=4;
						else if((seuilalim>9) &&(seuilalim<18)) seuilalim=9;
						else if((seuilalim>25)&&(seuilalim<33)) seuilalim=25;
						else if(seuilalim>38) seuilalim=38;
						
						selectSeuilBat.value = seuilalim; 
					}else{
						document.getElementById("select_seuil_bat").disabled = true;
					}
					document.getElementById("alertsms_normalement").disabled = true;
					document.getElementById("input_alert_temps_reel").disabled = true;
					$('#input_alert_temps_reel').val("");
					document.getElementById("alert_active_parking").style.visibility = "hidden";
					minAlerteFiltrage(document.getElementById("alerte_filtrage"),alimTimeBetween);
					//document.getElementById("alerte_filtrage").value = alimTimeBetween;

					msgApp = messageAlimDefaultAlarmeAlim;
					msgDisp = messageAlimOkAlarmeAlim;
					cfg = cfgAlaAlim;
					smsConverted(smsAlaAlimConverted);
				}
				
				if(id == "alarme1"){
					$("#abat").hide();
					document.getElementById("select_seuil_bat").disabled = true;
					if(versionBaliseGlobal == "53" || versionBaliseGlobal == "56" || versionBaliseGlobal == "57"){
						document.getElementById("alertsms_normalement").disabled = true;
					}else{
						document.getElementById("alertsms_normalement").disabled = false;
					}
					document.getElementById("input_alert_temps_reel").disabled = false;
					$('#input_alert_temps_reel').val(realtimeAl1);
					document.getElementById("alert_active_parking").style.visibility = "hidden";
					//document.getElementById("alerte_filtrage").value = al1TimeBetween;
					minAlerteFiltrage(document.getElementById("alerte_filtrage"),al1TimeBetween);
					if(noNfAlarm1 == "1"){
						document.getElementById("alertsms_normalement").value = "ferme";
					}else{
						document.getElementById("alertsms_normalement").value = "ouvert";
					}
					
					msgApp = messageApparitionAlarme1;
					msgDisp = messageDisparitionAlarme1;
					cfg = cfgAl1;
					smsConverted(smsAl1Converted);
				}

				if(id =="alarme2"){
					$("#abat").hide();
					document.getElementById("select_seuil_bat").disabled = true;
					document.getElementById("alertsms_normalement").disabled = false;
					document.getElementById("input_alert_temps_reel").disabled = false;
					$('#input_alert_temps_reel').val(realtimeAl2);
					document.getElementById("alert_active_parking").style.visibility = "hidden";
					//document.getElementById("alerte_filtrage").value = al2TimeBetween;
					minAlerteFiltrage(document.getElementById("alerte_filtrage"),al2TimeBetween);
					if(noNfAlarm2 == "1"){
						document.getElementById("alertsms_normalement").value = "ferme";
					}else{
						document.getElementById("alertsms_normalement").value = "ouvert";
					}
					
					msgApp = messageApparitionAlarme2;
					msgDisp = messageDisparitionAlarme2;
					cfg = cfgAl2;
					smsConverted(smsAl2Converted);
				}

				if(id == "alarmebat"){
					document.getElementById("input_alert_temps_reel").disabled = true;
					$('#input_alert_temps_reel').val("");
					document.getElementById("alertsms_normalement").disabled = true;
					document.getElementById("select_seuil_bat").disabled = false;
					if(versionBaliseGlobal == "55" || versionBaliseGlobal == "57")
					{
						$("#abat").show();
						
						var asb = document.getElementById("asbf");
						if((asbf / 8) >= 1){ 
							asb.checked = true; 
						}else{ 
							asb.checked = false; 
						}
						selectSeuilBat.value = seuilBattery;
						$("#seuil2").val(seuil2);
						$("#seuil3").val(seuil3);
					}
					else
					{
						$("#select_seuil_bat").val(seuilBattery);
					}
					document.getElementById("alert_active_parking").style.visibility = "hidden";
					//document.getElementById("alerte_filtrage").value = batTimeBetween;
					minAlerteFiltrage(document.getElementById("alerte_filtrage"),batTimeBetween);

					msgApp = messageBatteryFaibleAlarmeBat;
					msgDisp = messageBatteryOkAlarmeBat;
					cfg = cfgAlaBat;
					smsConverted(smsAlaBatConverted);
				}
				
				// msg
				document.getElementById("message_apparition").value = unescape(escape(msgApp).replace(/%00/g,""));
				document.getElementById("message_disparition").value = unescape(escape(msgDisp).replace(/%00/g,""));
				
				if (cfg == "1") {
					document.getElementById('alert_active_desactive').innerHTML = getTextModeBienAlertActive
					document.getElementById('alert_active_desactive').style.backgroundColor = '#00FF00';
					document.getElementById('checkbox_alert_active_desactive').checked = true;
					document.getElementById('alert_active').style.visibility = "visible";
				} else {
					document.getElementById('alert_active_desactive').innerHTML = getTextModeBienAlertDesactive;
					document.getElementById('alert_active_desactive').style.backgroundColor = '';
					document.getElementById('checkbox_alert_active_desactive').checked = false;
					document.getElementById('alert_active').style.visibility = "hidden";
				}
			}
		}
			xmlhttp.open("GET","../configuration/configurationanalysedata.php?idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,true);
			xmlhttp.send();
	}

}


function validModeDeFonctionnement(){

	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	
	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();

	var vide = "0000000";

	var numeroAppel = numeroAppelGlobal;
	var selectModeFonctionnement = document.getElementById('select_mode_fonctionnement').value;

	var modeMessage = modeMessageGlobal;

	var sujet;
	var nsujet = "";
	var corps1, corps2, corps3, corps4, corps5, corps6;

	var acqTrajet = document.getElementById("select_freq_acquisition_trajet");
	var acqArret = document.getElementById("select_freq_acquisition_arret");
	var acqTiming = document.getElementById("select_freq_acquisition_timing");

	var rapTrajet = document.getElementById("select_freq_rapatriement_trajet");
	var rapArret = document.getElementById("select_freq_rapatriement_arret");
	var rapTiming = document.getElementById("select_freq_rapatriement_timing");

	var connexionGSM = document.getElementById("connexion_gsm");

	var corpsAcquisitionTrajet = vide.substring(0, vide.length - acqTrajet.value.length) + acqTrajet.value;
	var corpsAcquisitionArret = vide.substring(0, vide.length - acqArret.value.length) + acqArret.value;
	var corpsAcquisitionTiming = vide.substring(0, vide.length - acqTiming.value.length) + acqTiming.value;

	var corpsRapatriementTrajet = vide.substring(0, vide.length - rapTrajet.value.length) + rapTrajet.value;
	var corpsRapatriementArret = vide.substring(0, vide.length - rapArret.value.length) + rapArret.value;
	var corpsRapatriementTiming = vide.substring(0, vide.length - rapTiming.value.length) + rapTiming.value;

	if(selectModeFonctionnement == "normal") {
		sujet = "Acquisition des positions: En trajet " + acqTrajet.options[acqTrajet.selectedIndex].text + "/ En Arrêt " + acqArret.options[acqArret.selectedIndex].text + " -- " +
		"Rapatriement des positions: En trajet " + rapTrajet.options[rapTrajet.selectedIndex].text + "/ En Arrêt " + rapArret.options[rapArret.selectedIndex].text + " -- " +
		"Mode normal - GSM " + connexionGSM.options[connexionGSM.selectedIndex].text;
		
		if(versionBaliseGlobal == "55" || versionBaliseGlobal == "57"){
			sujet = "Stratégie normale: " + sujet;
			
			var rtd = document.getElementById('retard').value;
			var retard = document.getElementById("retard").options[document.getElementById("retard").selectedIndex].text;
			
			corps1 = "Fgs0," + acqTrajet.value + "," + acqArret.value + "," + acqTiming.value;
			corps2 = "Fgm0,5";
			corps3 = "Fwe0," + rapTrajet.value + "," + rapArret.value + "," + rapTiming.value;
			corps4 = "Fwm0,8";
			
			if (connexionGSM.value == "permanent") corps5 = "Frm0,0,1,5,5," + rtd;
			if (connexionGSM.value == "actifarret") {corps5 = "Frm0,9,0,5,5," + rtd;		sujet += " - Retard à l'activation: " + retard;}
			if (connexionGSM.value == "actiftrajet") {corps5 = "Frm0,13,1,5,5," + rtd;	sujet += " - Retard à l'activation: " + retard;}
			if (connexionGSM.value == "planning") corps5 = "Frm0,16,1,5,5," + rtd;
			
			corps6 = "";
			
		}else if(versionBaliseGlobal == "20"){	// TELTO
			if(modeMessage == "SMS")
				modeMessage = "TELTOSMS";
			else
				modeMessage = "TELTO";
			
			if(idTracker > 356173060000000){		// FM1120
				corps1 = "setparam ";
				corps2 = "1540:" + acqArret.value + ";1560:" + acqArret.value + ";1580:" + acqArret.value;								// Home network , roaming, unknown
				corps3 = ";1544:" + (rapArret.value * 60) + ";1564:" + (rapArret.value * 60) + ";1584:" + (rapArret.value * 60);		// Home network , roaming, unknown
				corps4 = ";1550:" + acqTrajet.value + ";1570:" + acqTrajet.value + ";1590:" + acqTrajet.value;							// Home network , roaming, unknown
				corps5 = ";1554:" + (rapTrajet.value * 60) + ";1574:" + (rapTrajet.value * 60) + ";1594:" + (rapTrajet.value * 60);		// Home network , roaming, unknown
				if (connexionGSM.value == "permanent")	corps6 = ";1000:1";
				if (connexionGSM.value == "eco") 		corps6 = ";1000:2";
			}else{									// FMB920
				corps1 = "setparam ";
				corps2 = "10000:" + acqArret.value + ";10100:" + acqArret.value + ";10200:" + acqArret.value;					// Home network , roaming, unknown
				corps3 = ";10005:" + (rapArret.value * 60) + ";10105:" + (rapArret.value * 60) + ";10205:" + (rapArret.value * 60);		// Home network , roaming, unknown
				corps4 = ";10050:" + acqTrajet.value + ";10150:" + acqTrajet.value + ";10250:" + acqTrajet.value;						// Home network , roaming, unknown
				corps5 = ";10055:" + (rapTrajet.value * 60) + ";10155:" + (rapTrajet.value * 60) + ";10255:" + (rapTrajet.value * 60);	// Home network , roaming, unknown
				
				if (connexionGSM.value == "permanent")	corps6 = ";102:1";
				else if (connexionGSM.value == "eco")	corps6 = ";102:2";
			}
		}else if((versionBaliseGlobal == "3370")||(versionBaliseGlobal == "7003")||(versionBaliseGlobal == "7201")){		// SC NEO & SOLO
			if(modeMessage == "SMS")
				modeMessage = "NEOSMS";
			else
				modeMessage = "NEO";
			
			corpsAcquisitionTrajet = acqTrajet.value;
			corpsAcquisitionArret = acqArret.value;
			
			if(corpsAcquisitionTrajet > 3600)
				corpsAcquisitionTrajet = 3600;
			else if(corpsAcquisitionTrajet < 10)
				corpsAcquisitionTrajet = 10;
			
			if(corpsAcquisitionArret > 86400)
				corpsAcquisitionArret = 21600;
			else if(corpsAcquisitionArret < 180)
				corpsAcquisitionArret = 180;
			
			
			if(versionBaliseGlobal == "3370")
			{
				if (connexionGSM.value == "permanent")
				{
					var ncorps1 = "STOPMODE";
					var ncorps3 = ",300,30,0,7200"
				}
				else if (connexionGSM.value == "eco")
				{
					var ncorps1 = "STOPMODE";
					var ncorps3 = ",300,0,60,7200"
				}
				
				nsujet = "Mode de fonctionnement: GSM " + connexionGSM.options[connexionGSM.selectedIndex].text;
			}
			
			sujet = "Acquisition des positions: En trajet " + acqTrajet.options[acqTrajet.selectedIndex].text + "/ En Arrêt " + acqArret.options[acqArret.selectedIndex].text + " -- Mode normal";
			corps1 = "MODE";
			corps2 = "";
			corps3 = ",1," + corpsAcquisitionTrajet + "," + corpsAcquisitionArret;
			corps4 = "";
			corps5 = "";
			corps6 = "";
			
		}else{
			
			corps1 = "FGS" + corpsAcquisitionTrajet + "," + corpsAcquisitionArret + "," + corpsAcquisitionTiming;
			corps2 = "FGM5";
			corps3 = "FWE" + corpsRapatriementTrajet + "," + corpsRapatriementArret + "," + corpsRapatriementTiming;
			if((versionBaliseGlobal == "48")||(versionBaliseGlobal == "53"))
				corps4 = "FWM4";
			else
				corps4 = "FWM8";
			
			if (connexionGSM.value == "permanent")	corps5 = "FRm00000,1,00005,00005,00000";
			else if (connexionGSM.value == "eco")	corps5 = "FRm00109,1,00005,00005,00000";
			else if (connexionGSM.value == "eco+")	corps5 = "FRm00108,1,00005,00005,00000";
			
			if(versionBaliseGlobal == "56")
			{
				var ird = document.getElementById('retard').value;
				var irdsujet = document.getElementById("retard").options[document.getElementById("retard").selectedIndex].text;
				corps6 = "FWI" + ird;
				sujet += " - Freq. acquisition Iridium: "+irdsujet;
			}
			else
				corps6 = "";
			
		}
	}
	else if(selectModeFonctionnement == "historique") {
		sujet = "Acquisition des positions: En trajet " + acqTrajet.options[acqTrajet.selectedIndex].text + "/ En Arrêt " + acqArret.options[acqArret.selectedIndex].text + " -- " +
		"Rapatriement des positions: Timing " + rapTiming.options[rapTiming.selectedIndex].text + " -- " +
		"Mode historique";
		
		if(versionBaliseGlobal == "55" || versionBaliseGlobal == "57"){
			sujet = "Stratégie normale: " + sujet;
			corps1 = "Fgs0," + acqTrajet.value + "," + acqArret.value + "," + acqTiming.value;
			corps2 = "Fgm0,5";
			corps3 = "Fwe0," + rapTrajet.value + "," + rapArret.value + "," + rapTiming.value;
			corps4 = "Fwm0,16";
			corps5 = "Frm0,12,1,5,5,0";
			corps6 = "F0k0";
			
		}else{
			corps1 = "FGS" + corpsAcquisitionTrajet + "," + corpsAcquisitionArret + "," + corpsAcquisitionTiming;
			corps2 = "FGM5";
			corps3 = "FWE" + corpsRapatriementTrajet + "," + corpsRapatriementArret + "," + corpsRapatriementTiming;
			corps4 = "FWM16";
			corps5 = "FRm00108,1,00005,00005,00000";
			corps6 = "F0k0";
			
		}	
	}
	else if(selectModeFonctionnement == "periscope"){
		sujet = "Acquisition des positions: Timing " +acqTiming.options[acqTiming.selectedIndex].text + " -- " +
		"Rapatriement des positions: Timing " + rapTiming.options[rapTiming.selectedIndex].text + " -- " +
		"Mode périscope";
		
		if(versionBaliseGlobal == "55" || versionBaliseGlobal == "57"){
			sujet = "Stratégie normale: " + sujet;
			corps1 = "Fgs0," + acqTrajet.value + "," + acqArret.value + "," + acqTiming.value;
			corps2 = "Fgm0,16";
			corps3 = "Fwe0," + rapTrajet.value + "," + rapArret.value + "," + rapTiming.value;
			corps4 = "Fwm0,16";
			corps5 = "Frm0,12,1,5,5,0";
			corps6 = "F0k0";
			
		}else if((versionBaliseGlobal == "3370")||(versionBaliseGlobal == "7003")||(versionBaliseGlobal == "7201")){		// SC NEO & SOLO
			if(modeMessage == "SMS")
				modeMessage = "NEOSMS";
			else
				modeMessage = "NEO";
			
			corpsAcquisitionTiming = acqTiming.value;
			corpsAcquisitionTiming = corpsAcquisitionTiming/60;
			
			if(corpsAcquisitionTiming > 24)
				corpsAcquisitionTiming = 24;
			else if(corpsAcquisitionTiming < 1)
				corpsAcquisitionTiming = 1;
			
			sujet = "Acquisition des positions: Timing " +acqTiming.options[acqTiming.selectedIndex].text + " -- Mode périscope";
			corps1 = "MODE";
			corps2 = "";
			corps3 = ",2,00:00," + corpsAcquisitionTiming;
			corps4 = "";
			corps5 = "";
			corps6 = "";
			
		}else{
			corps1 = "FGS" + corpsAcquisitionTrajet + "," + corpsAcquisitionArret + "," + corpsAcquisitionTiming;
			corps2 = "FGM16";
			corps3 = "FWE" + corpsRapatriementTrajet + "," + corpsRapatriementArret + "," + corpsRapatriementTiming;
			corps4 = "FWM16";
			corps5 = "FRm00108,1,00005,00005,00000";
			corps6 = "F0k0";
			
		}
	}

	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else {
		if (confirm(getTextModeConfirmModeDeFonctionnement)) {
			$.ajax({
				url: '../configuration/configurationvalidtmessages.php',
				type: 'GET',
				data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
				"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet +
				"&corps1=" + corps1 + "&corps2=" + corps2 + "&corps3=" + corps3 + "&corps4=" + corps4 + "&corps5=" + corps5 + "&corps6=" + corps6,

				success: function (rsujet)
				{
					if(nsujet == "")
					{
						alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
								+ rsujet + "\n\n" +
								getTextModeAttentionParamRapatrie);
					}
					else
					{
						$.ajax({
							url: '../configuration/configurationvalidtmessages.php',
							type: 'GET',
							data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
							"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + nsujet +
							"&corps1=" + ncorps1 + "&corps2=&corps3=" + ncorps3 + "&corps4=&corps5=&corps6=",

							success: function (rnsujet) {
								alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
										+ rsujet + "\n\n"
										+ rnsujet + "\n\n" +
										getTextModeAttentionParamRapatrie);
							}
						})
					}
				}
			})
		}
	}
}

// Mode vitesse
function validvitesse(){
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	var numeroAppel = numeroAppelGlobal;
	var modeMessage = modeMessageGlobal;
	
	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();
	
	//
	if( $.inArray(versionBaliseGlobal, ['3370','7003','7201']) >= 0)
	{
		if(modeMessage == "SMS")
			modeMessage = "NEOSMS";
		else
			modeMessage = "NEO";
		
		var confirme = "Voulez-vous vraiment modifier le Mode angle de la balise ?";
		var sujet = "Mode angle: " + $('#mvv :selected').text() + ", angle: " + $('#mvfp :selected').text();
		var corps1 = "ANGLEREP";
		var corps3 = "," + $("#mvv").val() + "," + $("#mvfp").val() + ",5";
	}
	else
	{
		var confirme = "Voulez-vous vraiment modifier le Mode vitesse de la balise ?";
		var sujet = "Configuration Mode vitesse sur la balise",
			corps1, corps3, chkv = document.getElementById("chkv"), mvv = $("#mvv").val(), mvfp = $("#mvfp").val(), mvfrp = $("#mvfrp").val();
		
		if(chkv.checked == true){
			corps1 = "FGc1" + "," + mvv + "," + mvfp + "," + mvfrp;
		}else if(chkv.checked == false){
			corps1 = "FGc0" + "," + mvv + "," + mvfp + "," + mvfrp;
		}
	}
	
	//
	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else {
		if (confirm(confirme)) {
			$.ajax({
				url: '../configuration/configurationvalidtmessages.php',
				type: 'GET',
				data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
				"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet +
				"&corps1=" + corps1 + "&corps3=" + corps3,

				success: function (sujet) {
					//alert("La configuration Mode vitesse de cette balise " + nomTracker + " a été prise en compte.");
					alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
							+ sujet + "\n\n" +
							getTextModeAttentionParamRapatrie);
				}
			})
		}
	}
	
}
// Fin Mode vitesse

function validTempsReel(){

	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();


	var numeroAppel = numeroAppelGlobal;
	var selectTempsReelImmediat = document.getElementById('select_temps_reel_immediat').value;
	var selectTempsReelAppel = document.getElementById('select_temps_reel_appel').value;
	var selectTempsReelDemarrage = document.getElementById('select_temps_reel_demarrage').value;

	var modeMessage = modeMessageGlobal;

	var sujet;
	var corps1;

	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else {
		if (confirm(getTextModeConfirmTempsReel)) {
			sujet = "Temps réel - Immédiat pendant " + selectTempsReelImmediat + " min";
			corps1 = "F0a" + selectTempsReelImmediat;
			$.ajax({
				url: '../configuration/configurationvalidtmessages.php',
				type: 'GET',
				data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
				"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet + "&corps1=" + corps1,

				success: function (sujet) {
					alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
							+ sujet + "\n\n" +
							getTextModeAttentionParamRapatrie);
				}
			})
			sujet = "Temps réel - Sur Appel pendant " + selectTempsReelAppel + " min";
			corps1 = "F0d" + selectTempsReelAppel;
			$.ajax({
				url: '../configuration/configurationvalidtmessages.php',
				type: 'GET',
				data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
				"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet + "&corps1=" + corps1,

				success: function (sujet) {
					alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
							+ sujet + "\n\n" +
							getTextModeAttentionParamRapatrie);
				}
			})
			sujet = "Temps réel - Au démarrage pendant " + selectTempsReelDemarrage + " sec";
			corps1 = "F0f" + selectTempsReelDemarrage;
			$.ajax({
				url: '../configuration/configurationvalidtmessages.php',
				type: 'GET',
				data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
				"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet + "&corps1=" + corps1,

				success: function (sujet) {
					alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
							+ sujet + "\n\n" +
							getTextModeAttentionParamRapatrie);
				}
			})
		}
	}
}

function validTempsReelActivation(){

	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();

	var numeroAppel = numeroAppelGlobal;

	var modeMessage = modeMessageGlobal;

	var sujet;
	var corps1;

	if(document.getElementById("checkbox_temps_reel_active_desactive").checked == true){
		sujet = "Temps réel , Sur déplacement (activé)";
		corps1 = "F0k1";
	}else{
		sujet = "Temps réel , Sur déplacement (désactivé)";
		corps1 = "F0k0";
	}

	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else {
		if (confirm(getTextModeConfirmEtatTempsReel)) {
			$.ajax({
				url: '../configuration/configurationvalidtmessages.php',
				type: 'GET',
				data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
				"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet + "&corps1=" + corps1,

				success: function (sujet) {
					alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
							+ sujet + "\n\n" +
							getTextModeAttentionParamRapatrie);
				}
			})
		}
	}
}

function validDeplacementArret(){

	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();

	var numeroAppel = numeroAppelGlobal;

	var modeMessage = modeMessageGlobal;
	var vide = "0000";

	var selectSeuilVitesse = Math.round((document.getElementById('detection_deplacement_seuil_vitesse').value) / 1.851999999984);
	selectSeuilVitesse += "";
	var selectTempsVib = document.getElementById('detection_deplacement_temps_vib').value;
	var selectTempsAbsenceVib = document.getElementById('detection_arret_temps_absence_vib').value;
	//franck
	var rangeSensibilite = document.getElementById('sensibilite').value;
	// 
	var corpsSeuilVitesse = vide.substring(0, vide.length - selectSeuilVitesse.length) + selectSeuilVitesse;
	var corpsTempsVib = vide.substring(0, vide.length - selectTempsVib.length) + selectTempsVib;
	var corpsTempsAbsenceVib = vide.substring(0, vide.length - selectTempsAbsenceVib.length) + selectTempsAbsenceVib;

	var sujet = "Temps de Vibration nécessaire au déclenchement du mode trajet : "+selectTempsVib+ " sec" +
			", Seuil de vitesse nécessaire au déclenchement du mode trajet : "+document.getElementById('detection_deplacement_seuil_vitesse').value+" km/h" +
			", Temps Absence de Vibration du mode stop : "+selectTempsAbsenceVib+" sec";
	
	var corps1 = "FCT"+corpsTempsVib+","+corpsTempsAbsenceVib+","+corpsSeuilVitesse;
	var corps2 = ""; 
	var corps3 = "";
	var syncmem = 1;
	
	// Configuration sensibilité accéléromètre sur balises 400LC, 500MB, SC CUBE, 600St et 600Av
	if(versionBaliseGlobal == "48" || versionBaliseGlobal == "51" || versionBaliseGlobal == "53" || versionBaliseGlobal == "55" || versionBaliseGlobal == "56" || versionBaliseGlobal == "57"){
		corps2 = "F0m"+rangeSensibilite+","+rangeSensibilite;
		corps3 = "Z";
		if(versionBaliseGlobal == "48" || versionBaliseGlobal == "51"){
			syncmem = 0;
		}
		sujet += ", Sensibilité accéléromètre : " + rangeSensibilite;	// Traduction manquante
	}else if(versionBaliseGlobal == "20"){		// TELTO
		sujet = "Temps de Vibration nécessaire au déclenchement du mode trajet : "+selectTempsVib+ " sec" +
			", Temps Absence de Vibration du mode stop : "+selectTempsAbsenceVib+" sec";
		
		if(modeMessage == "SMS")
			modeMessage = "TELTOSMS";
		else
			modeMessage = "TELTO";
		
		if(idTracker > 356173060000000){		// FM1120
			corps1 = "";
			corps2 = "";
			corps3 = "";
		}else{									// FMB920
			corps1 = "setparam ";
			corps2 = "19001:" + selectTempsVib + ";19002:" + selectTempsAbsenceVib;
			corps3 = "";
		}
	}else if((versionBaliseGlobal == "3370")||(versionBaliseGlobal == "7003")||(versionBaliseGlobal == "7201")){		// SC NEO & SOLO
		if(modeMessage == "SMS")
			modeMessage = "NEOSMS";
		else
			modeMessage = "NEO";
		
		sujet = "Temps de detection : "+selectTempsVib+ " sec" +
			", delai d'alerte : "+selectTempsAbsenceVib+" sec";
		
		corps1 = "SENSOR";
		corps2 = "";
		corps3 = ","+selectTempsVib+","+selectTempsAbsenceVib+",5";
	}
	
	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else if(idTracker > 356173060000000){
		alert("Cette fonctionnalité ne peut être configurée pour cette balise");
		return;
	}else {
		if (confirm(getTextModeConfirmDetectionBalise)) {
			$.ajax({
				url: '../configuration/configurationvalidtmessages.php',
				type: 'GET',
				data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
				"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + 
				"&sujet=" + sujet + "&corps1=" + corps1 + "&corps2=" + corps2 + "&corps3=" + corps3 + "&syncmem=" + syncmem,

				success: function (sujet) {
					alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
							+ sujet + "\n\n" +
							getTextModeAttentionParamRapatrie);
				}
			})
		}
	}
}

function flashbalise(){
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();

	var numeroAppel = numeroAppelGlobal;
	var sujet = "Commande redémarrage de la balise "+idTracker; // traduction manquante
	var syncmem = 0;
	
	if((versionBaliseGlobal == "3370")||(versionBaliseGlobal == "7003")||(versionBaliseGlobal == "7201"))		// SC NEO & SOLO
	{
		var modeMessage = "NEOCMDSMS";
		var corps1 = "RESET";
	}
	else
	{
//		var modeMessage = modeMessageGlobal;
		var modeMessage = "SMS";
		var corps1 = "Z";
	}
	
	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else {
		if (confirm("Voulez vous redémarrer cette balise "+ nomTracker +" ?")) {
			$.ajax({
				url: '../configuration/configurationvalidtmessages.php',
				type: 'GET',
				data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
				"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet + "&corps1=" + corps1 + "&syncmem=" + syncmem,

				success: function (sujet) {
					alert("La balise " + nomTracker + " redémarrera au bout de quelques minutes.");
				}
			})
		}
	}
}

function validegeogsm(){
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();

	var numeroAppel = numeroAppelGlobal;

	var modeMessage = modeMessageGlobal;
	
	var etat = $("#etatgsm").val(), tagsm = $("#tagsm").val();
	
	var sujet = "Geolocalisation GSM de la balise "+idTracker;
	var corps1 = "FGL" + etat +","+ tagsm;
	
	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else {
		if (confirm("Voulez vous enregistrer/modifier la configuration de géolocalisation GSM de cette balise "+ nomTracker +" ?")) {
			$.ajax({
				url: '../configuration/configurationvalidtmessages.php',
				type: 'GET',
				data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
				"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet + "&corps1=" + corps1,

				success: function (sujet) {
					alert("La configuration de géolocalisation GSM de la balise "+ nomTracker +" a été prise en compte.");
				}
			})
		}
	}
}

function validProgrammabilite(){
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var numeroAppel = numeroAppelGlobal;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	//var modeMessage = modeMessageGlobal;
	var modeMessage = "SMS";

	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();

	
	var ip0 = document.getElementById("ip0").value;
	var ip1 = document.getElementById("ip1").value;
	var ip2 = document.getElementById("ip2").value;
	var port0 = document.getElementById("port0").value;
	var port1 = document.getElementById("port1").value;
	var port2 = document.getElementById("port2").value;
	
	var sujet;
	
	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else {
		/*var corps = document.getElementById("serveur").value;
		if(versionBaliseGlobal == "55" || versionBaliseGlobal == "57"){
			if(corps == "judiciaire"){
				sujet = "Programmation adresse serveur Judiciare";
				var corps1 = "FWS002568,sc400.geo3x.fr";
				var corps2 = "FWS102568,sc401.geo3x.fr";
				var corps3 = "FWS202568,sc402.geo3x.fr";
				var corps4 = "Z";
			}else if(corps == "administratif"){
				sujet = "Programmation adresse serveur Administratif";
				var corps1 = "FWS002568,sc401.geo3x.fr";
				var corps2 = "FWS102568,sc400.geo3x.fr";
				var corps3 = "FWS202568,sc402.geo3x.fr";
				var corps4 = "Z";
			}else if(corps == "autre"){*/
			
				if(port0.length == 3){ port0 = "00"+port0;}else if(port0.length == 4){ port0 = "0"+port0;}else if(port0.length == 5){ port0 = port0;}
				if(port1.length == 3){ port1 = "00"+port1;}else if(port1.length == 4){ port1 = "0"+port1;}else if(port1.length == 5){ port1 = port1;}
				if(port2.length == 3){ port2 = "00"+port2;}else if(port2.length == 4){ port2 = "0"+port2;}else if(port2.length == 5){ port2 = port2;}
				
				sujet = "Programmation manuelle de l'adresse serveur";
				if(versionBaliseGlobal == "20"){		// TELTO
					if(modeMessage == "SMS")
						modeMessage = "TELTOSMS";
					else
						modeMessage = "TELTO";
					
					if(idTracker > 356173060000000){		// FM1120
						var corps1 = "setparam ";
						var corps2 = "1245:"+ip0+";1246:"+port0;
						var corps3 = "";
						var corps4 = "";
						ip1 = "...";			//pour eviter message "Tous les champs (DNS, Ip et Port) doivent être définis."
						port1 = 100;			//pour eviter message "Tous les champs (DNS, Ip et Port) doivent être définis." ou "Port 2 incorrect."
					}else{									// FMB920
						var corps1 = "setparam ";
						var corps2 = "2004:"+ip0+";2005:"+port0;
						var corps3 = ";2007:"+ip1+";2008:"+port1;
						var corps4 = "";
					}
					ip2 = "...";			//pour eviter message "Tous les champs (DNS, Ip et Port) doivent être définis."
					port2 = 100;			//pour eviter message "Tous les champs (DNS, Ip et Port) doivent être définis." ou "Port 3 incorrect."
				}else if((versionBaliseGlobal == "3370")||(versionBaliseGlobal == "7003")||(versionBaliseGlobal == "7201")){		// SC NEO & SOLO
					if(modeMessage == "SMS")
						modeMessage = "NEOSMS";
					else
						modeMessage = "NEO";
					
					var corps1 = "SERVER";
					var corps2 = "";
					var corps3 = ",1,"+ip0+","+port0+",0";
					var corps4 = "";
					port1 = 100;			//pour eviter message "Port 2 incorrect."
					port2 = 100;			//pour eviter message "Port 3 incorrect."
				}else{					// Balise Stancom
					var corps1 = "FWS0"+port0+","+ip0;
					var corps2 = "FWS1"+port1+","+ip1;
					var corps3 = "FWS2"+port2+","+ip2;
					var corps4 = "Z";
				}
			/*}
		}else{
			sujet = "Programmation adresse serveur (aucune)";
			var corps1 = "";
			var corps2 = "";
			var corps3 = "";
			var corps4 = "";
		}*/

		if(versionBaliseGlobal == "55" || versionBaliseGlobal == "56" || versionBaliseGlobal == "57"){
			var led = document.getElementById("led");
			if(led.checked == true){
				sujet += " - Activation LED";
				var corps5 = "F0l1";
			}else if(led.checked == false){
				sujet += " - Desactivation LED";
				var corps5 = "F0l0";
			}
			var syncmem = 1;
		}else{
			var corps5 = "";
			var syncmem = 0;
		}
		
		if (confirm("Voulez vous enregistrer/modifier la programmabilité serveur de cette balise ?")) {
			if(ip0 == "" || ip1 == "" || ip2 == "" || port0 == "" || port1 == "" || port2 == ""){
				alert("Tous les champs (DNS, Ip et Port) doivent être définis.");
			}else if(port0 > 65535 || port0 < 100 ){
				alert("Port 1 incorrect.")
			}else if(port1 > 65535 || port1 < 100 ){
				alert("Port 2 incorrect.");
			}else if(port2 > 65535 || port2 < 100 ){
				alert("Port 3 incorrect.");
			}else{
				$.ajax({
					url: '../configuration/configurationvalidtmessages.php',
					type: 'GET',
					data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
					"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet + 
					"&corps1=" + corps1 + "&corps2=" + corps2 + "&corps3=" + corps3 + "&corps4=" + corps4 + "&corps5=" + corps5 + "&syncmem=" + syncmem,

					success: function (sujet) {
						alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
								+ sujet + "\n\n" +
								getTextModeAttentionParamRapatrie);
					}
				});
			}
		}
	}
}

// Strategie batterie faible
/*function validbatfaible(){
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();

	var numeroAppel = numeroAppelGlobal;

	var modeMessage = modeMessageGlobal;

	var mode, corps1, corps2, corps3, corps4, corps5, tra1, tra2, tim1, tim2, ar1, ar2, sujet, choixgsm, choixretard;
	
	mode = document.getElementById("modefonctbf").value; choixgsm = document.getElementById("gsmbf").value; choixretard = document.getElementById("retardbf").value;
	
	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else {
		// if(versionBaliseGlobal == "48" || versionBaliseGlobal == "51" || versionBaliseGlobal == "55" || versionBaliseGlobal == "57"){
		// }else{
		// }
		tra1 = document.getElementById("trajetacp").value; tra2 = document.getElementById("trajetrap").value;
		tim1 = document.getElementById("timingacp").value; tim2 = document.getElementById("timingrap").value;
		ar1 = document.getElementById("arretacp").value; ar2 = document.getElementById("arretrap").value;
		//niv = document.getElementById("seuilacp").value;
		sujet = "Configuration Stratégie batterie faible"; 
		
		if(mode == "normal"){
			if(choixgsm == "permanent"){
				corps1 = "Fgs1," + tra1 + "," + ar1 + "," + tim1;
				corps2 = "Fgm1,5";
				corps3 = "Fwe1," + tra2 + "," + ar2 + "," + tim2;
				corps4 = "Fwm1,8";
				corps5 = "Frm1,0,1,5,10,0";
			}else if(choixgsm == "eco"){
				corps1 = "Fgs1," + tra1 + "," + ar1 + "," + tim1;
				corps2 = "Fgm1,5";
				corps3 = "Fwe1," + tra2 + "," + ar2 + "," + tim2;
				corps4 = "Fwm1,8";
				corps5 = "Frm1,9,0,5,10," + choixretard;
			}else if(choixgsm == "eco+" ){
				corps1 = "Fgs1," + tra1 + "," + ar1 + "," + tim1;
				corps2 = "Fgm1,5";
				corps3 = "Fwe1," + tra2 + "," + ar2 + "," + tim2;
				corps4 = "Fwm1,8";
				corps5 = "Frm1,13,1,5,10," + choixretard;
			}		
		}else if(mode == "historique"){
			corps1 = "Fgs1," + tra1 + "," + ar1 + "," + tim1;
			corps2 = "Fgm1,5";
			corps3 = "Fwe1," + tra2 + "," + ar2 + "," + tim2;
			corps4 = "Fwm1,16";
			corps5 = "Frm1,12,1,5,10,0";
		}
		else if(mode == "periscope"){
			corps1 = "Fgs1," + tra1 + "," + ar1 + "," + tim1;
			corps2 = "Fgm1,16";
			corps3 = "Fwe1," + tra2 + "," + ar2 + "," + tim2;
			corps4 = "Fwm1,16";
			corps5 = "Frm1,12,1,5,10,0";
		}
		
		if (confirm("Voulez vous enregistrer/modifier la stratégie batterie faible de cette balise ?")) {
			$.ajax({
				url: '../configuration/configurationvalidtmessages.php',
				type: 'GET',
				data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
				"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet +
				"&corps1=" + corps1 + "&corps2=" + corps2 + "&corps3=" + corps3 + "&corps4=" + corps4 + "&corps5=" + corps5,

				success: function (sujet) {
					alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
							+ sujet + "\n\n" +
							getTextModeAttentionParamRapatrie);
				}
			});
		}
	}
}


// Strategie geofencing embarquee
// Convertit une coordonn�e de degr�s vers DDMMmmmm (DD = degr�s / MM = partie eni�re des minutes / mmmm = partie fractionnaire des minutes)
function d2dms(x) {
	if(x<0){
		var d = Math.ceil(x);		// récupère partie entière des degrés
	}else{
		var d = Math.floor(x);		// récupère partie entière des degrés
	}
	var y = (x - d) * 60;
	var m = Math.round(y*10000);
	d = d*1000000;
	var resultat = d+m;
	return resultat; 
}

// Convertit une coordonn�e de type DDMMmmmm en degr�s (DD = degr�s / MM = partie eni�re des minutes / mmmm = partie fractionnaire des minutes)
function dms2d(y){
	if(y<0){
		var a = Math.ceil(y / 1000000);		// récupère partie entière des degrés
	}else{
		var a = Math.floor(y / 1000000);	// récupère partie entière des degrés
	}
	var b = (y-(a * 1000000)) / 600000;		 // convertit les minutes en degrés
	var resultat = a+b;
	return resultat;
}

function geoembarquee(fz, strat, gmsg, msge, msgs, ent1, ent2, ent3, ent4, stie1, stie2, stie3, stie4, latne, latso, lngne, lngso){
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;	
	var numeroAppel = numeroAppelGlobal;
	var modeMessage = modeMessageGlobal;
	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();
	
	var cfg;
	
	if(gmsg.checked == true){
		cfg = 1;
		if(strat.checked == true){
			cfg += 2;
		}
	}
	else{
		cfg=0;
	}
	
	var ent_stie = 0;
	if(ent1.checked == true){ ent_stie += 1 ;} if(ent2.checked == true){ ent_stie += 2 ;} if(ent3.checked == true){ ent_stie += 4 ;} if(ent4.checked == true){ ent_stie += 8 ;}
	if(stie1.checked == true){ ent_stie += 16 ;} if(stie2.checked == true){ ent_stie += 32 ;} if(stie3.checked == true){ ent_stie += 64 ;} if(stie4.checked == true){ ent_stie += 128 ;}

	//xxxxxxx1xxxxxxx//
	var zone, dd;
	if(parseInt(fz,10) == 0){zone = "a"; dd = 1;} if(parseInt(fz,10) == 1){zone = "b"; dd = 2;} if(parseInt(fz,10) == 2){zone = "c"; dd = 3;} if(parseInt(fz,10) == 3){zone = "d"; dd = 4;} if(parseInt(fz,10) == 4){zone = "e"; dd = 5;}
	
	var corpslatlng = "FZ" + zone + d2dms(latne) + ","+ d2dms(latso) +","+ d2dms(lngne) + "," + d2dms(lngso);
	var corps = "FZ" + fz + cfg + "," + ent_stie + ",0,0";
	var msgent = "FM"+ ((fz * 60) + 630) + ",0030," + msge;
	var msgstie = "FM"+ ((fz * 60) + 660) + ",0030," + msgs;
	
	sujet = "Configuration zone " + dd + " géofecing embarqué.";
	
	if (confirm("Voulez vous enregistrer/modifier la zone " + dd + " de cette balise ?")) {
		$.ajax({
			url: '../configuration/configurationvalidtmessages.php',
			type: 'GET',
			data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
			"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet +
			"&corps1=" + corps + "&corps2=" + corpslatlng + "&corps3=" + msgent + "&corps4=" + msgstie,

			success: function (sujet) {
				alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
						+ sujet + "\n\n" +
						getTextModeAttentionParamRapatrie);
			}
		});
	}	
}

function loadzgfe(){
	var xmlhttp = null;
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	if (idTracker==""){	
		alert(getTextVeuillezChoisirUneBalise);
		return;															    
	}else{
		if (window.XMLHttpRequest){				
			xmlhttp=new XMLHttpRequest();						
		}else{									
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");		
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var test=xmlhttp.responseText;
				var reg=new RegExp("[&]+", "g");
				var tableau=test.split(reg);
				
				var latmax1 = tableau[0].substring(tableau[0].indexOf('<br> LATITUDE MAX 1')+31,tableau[0].indexOf('<br> LATITUDE MIN 1'));
				var latmin1 = tableau[0].substring(tableau[0].indexOf('<br> LATITUDE MIN 1')+31,tableau[0].indexOf('<br> LONGITUDE MAX 1'));
				var lngmax1 = tableau[0].substring(tableau[0].indexOf('<br> LONGITUDE MAX 1')+32,tableau[0].indexOf('<br> LONGITUDE MIN 1'));
				var lngmin1 = tableau[0].substring(tableau[0].indexOf('<br> LONGITUDE MIN 1')+32,tableau[0].indexOf('<br> LATITUDE MAX 2'));
				
				var latmax2 = tableau[0].substring(tableau[0].indexOf('<br> LATITUDE MAX 2')+31,tableau[0].indexOf('<br> LATITUDE MIN 2'));
				var latmin2 = tableau[0].substring(tableau[0].indexOf('<br> LATITUDE MIN 2')+31,tableau[0].indexOf('<br> LONGITUDE MAX 2'));
				var lngmax2 = tableau[0].substring(tableau[0].indexOf('<br> LONGITUDE MAX 2')+32,tableau[0].indexOf('<br> LONGITUDE MIN 2'));
				var lngmin2 = tableau[0].substring(tableau[0].indexOf('<br> LONGITUDE MIN 2')+32,tableau[0].indexOf('<br> LATITUDE MAX 3'));
				
				var latmax3 = tableau[0].substring(tableau[0].indexOf('<br> LATITUDE MAX 3')+31,tableau[0].indexOf('<br> LATITUDE MIN 3'));
				var latmin3 = tableau[0].substring(tableau[0].indexOf('<br> LATITUDE MIN 3')+31,tableau[0].indexOf('<br> LONGITUDE MAX 3'));
				var lngmax3 = tableau[0].substring(tableau[0].indexOf('<br> LONGITUDE MAX 3')+32,tableau[0].indexOf('<br> LONGITUDE MIN 3'));
				var lngmin3 = tableau[0].substring(tableau[0].indexOf('<br> LONGITUDE MIN 3')+32,tableau[0].indexOf('<br> LATITUDE MAX 4'));
				
				var latmax4 = tableau[0].substring(tableau[0].indexOf('<br> LATITUDE MAX 4')+31,tableau[0].indexOf('<br> LATITUDE MIN 4'));
				var latmin4 = tableau[0].substring(tableau[0].indexOf('<br> LATITUDE MIN 4')+31,tableau[0].indexOf('<br> LONGITUDE MAX 4'));
				var lngmax4 = tableau[0].substring(tableau[0].indexOf('<br> LONGITUDE MAX 4')+32,tableau[0].indexOf('<br> LONGITUDE MIN 4'));
				var lngmin4 = tableau[0].substring(tableau[0].indexOf('<br> LONGITUDE MIN 4')+32,tableau[0].indexOf('<br> LATITUDE MAX 5'));
				
				var latmax5 = tableau[0].substring(tableau[0].indexOf('<br> LATITUDE MAX 5')+31,tableau[0].indexOf('<br> LATITUDE MIN 5'));
				var latmin5 = tableau[0].substring(tableau[0].indexOf('<br> LATITUDE MIN 5')+31,tableau[0].indexOf('<br> LONGITUDE MAX 5'));
				var lngmax5 = tableau[0].substring(tableau[0].indexOf('<br> LONGITUDE MAX 5')+32,tableau[0].indexOf('<br> LONGITUDE MIN 5'));
				var lngmin5 = tableau[0].substring(tableau[0].indexOf('<br> LONGITUDE MIN 5')+32,tableau[0].indexOf('<br> MODE GSM'));
				
				document.body.className = "";
				
				$('#modalmap').modal('show');
				
				var fz = document.getElementById("selectzge").value, strat = document.getElementById("gstrat"), gmsg = document.getElementById("gact"),
				msge = document.getElementById("message_entreeg"), msgs = document.getElementById("message_sortieg"), 
				ent1 = document.getElementById("Anumg1"), ent2 = document.getElementById("Anumg2"), ent3 = document.getElementById("Anumg3"), ent4 = document.getElementById("Anumg4"), 
				stie1 = document.getElementById("Snumg1"), stie2 = document.getElementById("Snumg2"), stie3 = document.getElementById("Snumg3"), stie4 = document.getElementById("Snumg4");
				
				if(parseInt(fz,10) == 0){
					var cfg = tableau[0].substring(tableau[0].indexOf('<br> CFG GEO 1')+26,tableau[0].indexOf('<br> SMS GEO 1'));
					var sms = tableau[0].substring(tableau[0].indexOf('<br> SMS GEO 1')+26,tableau[0].indexOf('<br> CFG GEO 2'));
					var msggeoa = tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	GEO 1 APPARITION')+37,tableau[0].indexOf('<br> MESSAGE	GEO 1 DISPARITION'));
					var msggeod = tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	GEO 1 DISPARITION					=> ')+38,tableau[0].indexOf('<br> MESSAGE GEO 2 APPARITION'));
					
					drawcarreone(dms2d(latmax1), dms2d(lngmax1), dms2d(latmin1), dms2d(lngmin1), 1);
					// if(!latmax1 || !lngmax1 || !latmin1 || !lngmin1){ centremap(); }
				}else if(parseInt(fz,10) == 1){
					var cfg = tableau[0].substring(tableau[0].indexOf('<br> CFG GEO 2')+26,tableau[0].indexOf('<br> SMS GEO 2'));
					var sms = tableau[0].substring(tableau[0].indexOf('<br> SMS GEO 2')+26,tableau[0].indexOf('<br> CFG GEO 3'));
					var msggeoa = tableau[0].substring(tableau[0].indexOf('<br> MESSAGE GEO 2 APPARITION')+37,tableau[0].indexOf('<br> MESSAGE	GEO 2 DISPARITION'));
					var msggeod = tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	GEO 2 DISPARITION					=> ')+38,tableau[0].indexOf('<br> MESSAGE GEO 3 APPARITION'));
					
					drawcarreone(dms2d(latmax2), dms2d(lngmax2), dms2d(latmin2), dms2d(lngmin2), 2);
					// if(!latmax2 || !lngmax2 || !latmin2 || !lngmin2){ centremap(); }
				}else if(parseInt(fz,10) == 2){
					var cfg = tableau[0].substring(tableau[0].indexOf('<br> CFG GEO 3')+26,tableau[0].indexOf('<br> SMS GEO 3'));
					var sms = tableau[0].substring(tableau[0].indexOf('<br> SMS GEO 3')+26,tableau[0].indexOf('<br> CFG GEO 4'));
					var msggeoa = tableau[0].substring(tableau[0].indexOf('<br> MESSAGE GEO 3 APPARITION')+37,tableau[0].indexOf('<br> MESSAGE	GEO 3 DISPARITION'));
					var msggeod = tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	GEO 3 DISPARITION					=> ')+38,tableau[0].indexOf('<br> MESSAGE GEO 4 APPARITION'));
					
					drawcarreone(dms2d(latmax3), dms2d(lngmax3), dms2d(latmin3), dms2d(lngmin3), 3);
					// if(!latmax3 || !lngmax3 || !latmin3 || !lngmin3){ centremap(); }
				}else if(parseInt(fz,10) == 3){
					var cfg = tableau[0].substring(tableau[0].indexOf('<br> CFG GEO 4')+26,tableau[0].indexOf('<br> SMS GEO 4'));
					var sms = tableau[0].substring(tableau[0].indexOf('<br> SMS GEO 4')+26,tableau[0].indexOf('<br> CFG GEO 5'));
					var msggeoa = tableau[0].substring(tableau[0].indexOf('<br> MESSAGE GEO 4 APPARITION')+37,tableau[0].indexOf('<br> MESSAGE	GEO 4 DISPARITION'));
					var msggeod = tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	GEO 4 DISPARITION					=> ')+38,tableau[0].indexOf('<br> MESSAGE GEO 5 APPARITION'));
					
					drawcarreone(dms2d(latmax4), dms2d(lngmax4), dms2d(latmin4), dms2d(lngmin4), 4);
					// if(!latmax4 || !lngmax4 || !latmin4 || !lngmin4){ centremap(); }
				}else if(parseInt(fz,10) == 4){
					var cfg = tableau[0].substring(tableau[0].indexOf('<br> CFG GEO 5')+26,tableau[0].indexOf('<br> SMS GEO 5'));
					var sms = tableau[0].substring(tableau[0].indexOf('<br> SMS GEO 5')+26,tableau[0].indexOf('<br> MODE_RING'));
					var msggeoa = tableau[0].substring(tableau[0].indexOf('<br> MESSAGE GEO 5 APPARITION')+37,tableau[0].indexOf('<br> MESSAGE	GEO 5 DISPARITION'));
					var msggeod = tableau[0].substring(tableau[0].indexOf('<br> MESSAGE	GEO 5 DISPARITION					=> ')+38,tableau[0].indexOf('<br> LATITUDE MAX 1'));
					
					drawcarreone(dms2d(latmax5), dms2d(lngmax5), dms2d(latmin5), dms2d(lngmin5), 5);
					// if(!latmax5 || !lngmax5 || !latmin5 || !lngmin5){ centremap(); }
				}else{
					var cfg=0;
					var sms=0;
					var msggeoa = "";
					var msggeod = "";
					//dessiner toutes les zones
					drawcarre(dms2d(latmax1), dms2d(lngmax1), dms2d(latmin1), dms2d(lngmin1), 1);
					drawcarre(dms2d(latmax2), dms2d(lngmax2), dms2d(latmin2), dms2d(lngmin2), 2);
					drawcarre(dms2d(latmax3), dms2d(lngmax3), dms2d(latmin3), dms2d(lngmin3), 3);
					drawcarre(dms2d(latmax4), dms2d(lngmax4), dms2d(latmin4), dms2d(lngmin4), 4);
					drawcarre(dms2d(latmax5), dms2d(lngmax5), dms2d(latmin5), dms2d(lngmin5), 5);
				}
				
				
				if(cfg & 0x01){
					gmsg.checked = true;
					if(cfg & 0x02){
						strat.checked = true;
					}else{
						strat.checked = false;
					}
				}
				else{
					gmsg.checked = false;
					strat.checked = false;
				}
				shownumber();
				showmes();
				
				msggeoa = unescape(escape(msggeoa).replace(/%00/g,"").replace(/%uFFFD/g,""));
				msggeod = unescape(escape(msggeod).replace(/%00/g,"").replace(/%uFFFD/g,""));
				msge.value = msggeoa;
				msgs.value = msggeod;
				
				if(sms & 1){ent1.checked = true;}else{ent1.checked = false;}
				if(sms & 2){ent2.checked = true;}else{ent2.checked = false;}
				if(sms & 4){ent3.checked = true;}else{ent3.checked = false;}
				if(sms & 8){ent4.checked = true;}else{ent4.checked = false;}
				if(sms & 16){stie1.checked = true;}else{stie1.checked = false;}
				if(sms & 32){stie2.checked = true;}else{stie2.checked = false;}
				if(sms & 64){stie3.checked = true;}else{stie3.checked = false;}
				if(sms & 128){stie4.checked = true;}else{stie4.checked = false;}
			}
		}
		xmlhttp.open("GET","../configuration/configurationanalysedata.php?idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,false);
		xmlhttp.send();	
	}
}


function shownumber(){
	if(idTracker){
		if(document.getElementById("selectzge").value == "all"){
			$("#groupzoneact").hide();
		}else{
			$("#groupzoneact").show();
		}
		if(document.getElementById("gact").checked == true){
			document.getElementById("gdesac").innerHTML = "2) Message activ&eacute;";
			$("#groupstrat").show();
			$("#groupmsg").show();
			$("#gmsg").show();
			numbergeo(document.getElementById("message_numero_1"), document.getElementById("message_numero_2"), document.getElementById("message_numero_3"), document.getElementById("message_numero_4"));
		}else if(document.getElementById("gact").checked == false){
			document.getElementById("gdesac").innerHTML = "2) Message d&eacute;sactiv&eacute;";
			$("#groupstrat").hide();
			$("#groupmsg").hide();
			$("#gmsg").hide();
		}
	}else{
		alert("Veuillez choisir une balise.");
	}
}

function showmes(){
	if(idTracker){
		if(document.getElementById("gstrat").checked == true){
			document.getElementById("gt").innerHTML = "3) Strat&eacute;gie activ&eacute; "; 
		}else if(document.getElementById("gstrat").checked == false){
			document.getElementById("gt").innerHTML = "3) Strat&eacute;gie d&eacute;sactiv&eacute; ";
		}
	}else{
		alert("Veuillez choisir une balise.");
	}
}

function numbergeo(w,x,y,z){
	var xmlhttp = null;
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	if (idTracker==""){	
		alert(getTextVeuillezChoisirUneBalise);
		return;															    
	}else{
		if (window.XMLHttpRequest){				
			xmlhttp=new XMLHttpRequest();						
		}else{									
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");		
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var test=xmlhttp.responseText;
				var reg=new RegExp("[&]+", "g");
				var tableau=test.split(reg);
				
				var telephone1 = tableau[0].substring(tableau[0].indexOf('<br> TELEPHONE 1')+28,tableau[0].indexOf('<br> TELEPHONE 2'));
				var telephone2 = tableau[0].substring(tableau[0].indexOf('<br> TELEPHONE 2')+28,tableau[0].indexOf('<br> TELEPHONE 3'));
				var telephone3 = tableau[0].substring(tableau[0].indexOf('<br> TELEPHONE 3')+28,tableau[0].indexOf('<br> TELEPHONE 4'));
				var telephone4 = tableau[0].substring(tableau[0].indexOf('<br> TELEPHONE 4')+28,tableau[0].indexOf('<br> MESSAGE APPARITION ALARME 1'));
			
				if(escape(telephone1[0]) != "%00") w.value = escape(telephone1).replace(/%00/g,"");
				if(escape(telephone2[0]) != "%00") x.value = escape(telephone2).replace(/%00/g,"");
				if(escape(telephone3[0]) != "%00") y.value = escape(telephone3).replace(/%00/g,"");
				if(escape(telephone4[0]) != "%00") z.value = escape(telephone4).replace(/%00/g,"");
			
				document.body.className = "";
			}
		}
		xmlhttp.open("GET","../configuration/configurationanalysedata.php?idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw,false);
		xmlhttp.send();	
	}
}

// Strategie geofencing
function validgeo(){
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();

	var numeroAppel = numeroAppelGlobal;

	var modeMessage = modeMessageGlobal;

	var mode, corps1, corps2, corps3, corps4, corps5, gtra1, gtra2, gtim1, gtim2, gar1, gar2, sujet, choixgsm, choixretard;
	
	mode = document.getElementById("modefonctgeo").value; choixgsm = document.getElementById("gsmgeo").value; choixretard = document.getElementById("retardgeo").value;
	
	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else {
		// if(versionBaliseGlobal == "48" || versionBaliseGlobal == "51" || versionBaliseGlobal == "55" || versionBaliseGlobal == "57"){
		// }else{
		// }
		gtra1 = document.getElementById("trajetacpgeo").value; gtra2 = document.getElementById("trajetrapgeo").value;
		gtim1 = document.getElementById("timingacpgeo").value; gtim2 = document.getElementById("timingrapgeo").value;
		gar1 = document.getElementById("arretacpgeo").value; gar2 = document.getElementById("arretrapgeo").value;
		//niv = document.getElementById("seuilacp").value;
		sujet = "Configuration Stratégie entrée et sortie de zone géofencing de la balise";

		if(mode == "normal"){
			if(choixgsm == "permanent"){
				corps1 = "Fgs2," + gtra1 + "," + gar1 + "," + gtim1;
				corps2 = "Fgm2,5";
				corps3 = "Fwe2," + gtra2 + "," + gar2 + "," + gtim2;
				corps4 = "Fwm2,8";
				corps5 = "Frm2,0,1,5,10,0";
			}else if(choixgsm == "eco"){
				corps1 = "Fgs2," + gtra1 + "," + gar1 + "," + gtim1;
				corps2 = "Fgm2,5";
				corps3 = "Fwe2," + gtra2 + "," + gar2 + "," + gtim2;
				corps4 = "Fwm2,8";
				corps5 = "Frm2,9,0,5,10," + choixretard;
			}else if(choixgsm == "eco+" ){
				corps1 = "Fgs2," + gtra1 + "," + gar1 + "," + gtim1;
				corps2 = "Fgm2,5";
				corps3 = "Fwe2," + gtra2 + "," + gar2 + "," + gtim2;
				corps4 = "Fwm2,8";
				corps5 = "Frm2,13,1,5,10," + choixretard;
			}		
		}else if(mode == "historique"){
			corps1 = "Fgs2," + gtra1 + "," + gar1 + "," + gtim1;
			corps2 = "Fgm2,5";
			corps3 = "Fwe2," + gtra2 + "," + gar2 + "," + gtim2;
			corps4 = "Fwm2,16";
			corps5 = "Frm2,12,1,5,10,0";
		}
		else if(mode == "periscope"){
			corps1 = "Fgs2," + gtra1 + "," + gar1 + "," + gtim1;
			corps2 = "Fgm2,16";
			corps3 = "Fwe2," + gtra2 + "," + gar2 + "," + gtim2;
			corps4 = "Fwm2,16";
			corps5 = "Frm2,12,1,5,10,0";
		}	
				
		if (confirm("Voulez vous enregistrer/modifier la stratégie entrée et sortie de zone géofencing de cette balise ?")) {
			$.ajax({
				url: '../configuration/configurationvalidtmessages.php',
				type: 'GET',
				data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
				"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet +
				"&corps1=" + corps1 + "&corps2=" + corps2 + "&corps3=" + corps3 + "&corps4=" + corps4 + "&corps5=" + corps5,

				success: function (sujet) {
					alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
							+ sujet + "\n\n" +
							getTextModeAttentionParamRapatrie);
				}
			});
		}
	}
}

function validradio(){
}

function validplaning(){
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();

	var numeroAppel = numeroAppelGlobal;

	var modeMessage = modeMessageGlobal;
	
	var sujet, corps1, corps2, corps3, corps4, corps5, corps6;

	var act1 = document.getElementById("act1"), jour1 = document.getElementById("jour1").value, de1 = document.getElementById("de1").value, fin1 = document.getElementById("fin1").value,
			act2 = document.getElementById("act2"), jour2 =  document.getElementById("jour2").value, de2 = document.getElementById("de2").value, fin2 = document.getElementById("fin2").value,
				act3 = document.getElementById("act3"), jour3 =  document.getElementById("jour3").value, de3 = document.getElementById("de3").value, fin3 = document.getElementById("fin3").value,
					act4 = document.getElementById("act4"), jour4 = document.getElementById("jour4").value , de4 = document.getElementById("de4").value, fin4 = document.getElementById("fin4").value,
						act5 = document.getElementById("act5"), jour5 = document.getElementById("jour5").value, de5 = document.getElementById("de5").value, fin5 = document.getElementById("fin5").value,
							act6 = document.getElementById("act6"), jour6 = document.getElementById("jour6").value, de6 = document.getElementById("de6").value, fin6 = document.getElementById("fin6").value;
	
	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else {
		// if(versionBaliseGlobal == "48" || versionBaliseGlobal == "51" || versionBaliseGlobal == "55" || versionBaliseGlobal == "57"){
		// }else{
		// }
		
		sujet = "Configuration Planning Gsm"; 
		
		if(act1.checked == true){
			corps1 = "FR12," + jour1 + "," + de1 + "," + fin1 + ",0";
		}else if(act1.checked == false){
			corps1 = "FR10," + jour1 + "," + de1 + "," + fin1 + ",0";
		}else{
			alert("Désolé il semble que nous aillons perdu la connexion à la base de données.");
		}
		
		if(act2.checked == true){
			corps2 = "FR22," + jour2 + "," + de2 + "," + fin2 + ",0";
		}else if(act2.checked == false){
			corps2 = "FR20," + jour2 + "," + de2 + "," + fin2 + ",0";
		}else{
			alert("Désolé il semble que nous aillons perdu la connexion à la base de données.");
		}
		
		if(act3.checked == true){
			corps3 = "FR32," + jour3 + "," + de3 + "," + fin3 + ",0";
		}else if(act3.checked == false){
			corps3 = "FR30," + jour3 + "," + de3 + "," + fin3 + ",0";
		}else{
			alert("Désolé il semble que nous aillons perdu la connexion à la base de données.");
		}
		
		if(act4.checked == true){
			corps4 = "FR42," + jour4 + "," + de4 + "," + fin4 + ",0";
		}else if(act4.checked == false){
			corps4 = "FR40," + jour4 + "," + de4 + "," + fin4 + ",0";
		}else{
			alert("Désolé il semble que nous aillons perdu la connexion à la base de données.");
		}
		
		if(act5.checked == true){
			corps5 = "FR52," + jour5 + "," + de5 + "," + fin5 + ",0";
		}else if(act5.checked == false){
			corps5 = "FR50," + jour5 + "," + de5 + "," + fin5 + ",0";
		}else{
			alert("Désolé il semble que nous aillons perdu la connexion à la base de données.");
		}
		
		if(act6.checked == true){
			corps6 = "FR62," + jour6 + "," + de6 + "," + fin6 + ",0";
		}else if(act6.checked == false){
			corps6 = "FR60," + jour6 + "," + de6 + "," + fin6 + ",0";
		}else{
			alert("Désolé il semble que nous aillons perdu la connexion à la base de données.");
		}
		
		if (confirm("Voulez vous enregistrer/modifier le Planning Gsm de cette balise ?")) {
			$.ajax({
				url: '../configuration/configurationvalidtmessages.php',
				type: 'GET',
				data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
				"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet +
				"&corps1=" + corps1 + "&corps2=" + corps2 + "&corps3=" + corps3 + "&corps4=" + corps4 + "&corps5=" + corps5 + "&corps6=" + corps6,

				success: function (sujet) {
					alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
							+ sujet + "\n\n" +
							getTextModeAttentionParamRapatrie);
				}
			});
		}
	}
}
*/
function validAlert(){

	var idTracker = document.getElementById("idBalise").innerHTML;
	var selectTypeAlert = document.getElementById("select_type_alert").value;
	
	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else if(selectTypeAlert == "nothing"){
		alert(getTextAlertChoisirAlert);
		return;
	}else if( confirm(getTextModeConfirmAlerteBalise) ){
		
		// commun
		var sujetType = "";
		var sujetEtat = "";
		
		var nomTracker = document.getElementById("nomBalise").innerHTML;
		var nomDatabaseGpw = globalnomDatabaseGpw;
		var ipDatabaseGpw = globalIpDatabaseGpw;
		
		
		var messageApparition = unescape(escape(document.getElementById('message_apparition').value).replace(/%00/g,""));
		var messageDisparition = unescape(escape(document.getElementById('message_disparition').value).replace(/%00/g,""));
		
		var alerteCheckbox = document.getElementById("checkbox_alert_active_desactive").checked;
		var cfg = "";
		var sms = 0;
		
		var apparition1 = "";
		var apparition2 = "";
		var apparition3 = "";
		var apparition4 = "";
		var disparition1 = "";
		var disparition2 = "";
		var disparition3 = "";
		var disparition4 = "";
		var apparitionTotale = "";
		var disparitionTotale = "";

		var messageNumero1 = document.getElementById('message_numero_1').value;
		var messageNumero2 = document.getElementById('message_numero_2').value;
		var messageNumero3 = document.getElementById('message_numero_3').value;
		var messageNumero4 = document.getElementById('message_numero_4').value;
		
		
		if(alerteCheckbox == true){
			if(messageApparition != "" && messageDisparition != "") {
				sujetEtat = "Etat: Activée, Apparition: " + messageApparition + ", Disparition: " + messageDisparition;
				cfg = "001";
			}else{
				alert("Vous n'avez pas encore saisi le contenu des messages d'apparitions et de disparitions.");
				return;
			}
		}else{
			sujetEtat = "Etat: Désactivée";
			cfg = "000";
		}
		
		
		if(document.getElementById('apparition_numero_1').checked == true) {
			apparition1 = " N°1 " + messageNumero1;
			sms += 1;
		}
		if(document.getElementById('apparition_numero_2').checked == true){
			apparition2 = " N°2 "+messageNumero2;
			sms += 2;
		}
		if(document.getElementById('apparition_numero_3').checked == true){
			apparition3 = " N°3 "+messageNumero3;
			sms += 4;
		}
		if(document.getElementById('apparition_numero_4').checked == true){
			apparition4 = " N°4 "+messageNumero4;
			sms += 8;
		}
		if(document.getElementById('disparition_numero_1').checked == true){
			disparition1 = " N°1 "+messageNumero1;
			sms += 16;
		}
		if(document.getElementById('disparition_numero_2').checked == true){
			disparition2 = " N°2 "+messageNumero2;
			sms += 32;
		}
		if(document.getElementById('disparition_numero_3').checked == true){
			disparition3 = " N°3 "+messageNumero3;
			sms += 64;
		}
		if(document.getElementById('disparition_numero_4').checked == true){
			disparition4 = " N°4 "+messageNumero4;
			sms += 128;
		}

		if(apparition1 == "" && apparition2 == "" && apparition3 == ""  && apparition4 == "" )
			apparitionTotale = " Aucun téléphone";
		else
			apparitionTotale = apparition1 + "" + apparition2 + "" + apparition3 + "" + apparition4;

		if(disparition1 == "" && disparition2 == "" && disparition3 == ""  && disparition4 == "" )
			disparitionTotale = " Aucun téléphone";
		else
			disparitionTotale = disparition1 + "" + disparition2 + "" + disparition3 + "" + disparition4;
		
		
		var date = new Date();
		var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
			+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();
		
		
//		if( $.inArray(versionBaliseGlobal, ['20','46','47','53','3370','7003','7201']) >= 0)			// Geo3X : TELTO, 400n, CUBE, NEO 3G, NEO & SOLO
		if( $.inArray(versionBaliseGlobal, ['20','46','47','53','3370','7003','7201']) >= 0 || ((versionBaliseGlobal == "48")&&(selectTypeAlert =="alarmedeplacement")) )	// Geofence : TELTO, 400n, CUBE, NEO 3G, NEO & SOLO + alarme deplacement de SC400LC
		{
			var modeMessage = "MEMO";
			var sujetSeuilBat = "";
			var corps1 = "MEMO";
			var corps2 = "";
			var TypeAlarme = 0;
			
			switch(selectTypeAlert){
				case "alarmebat":
					TypeAlarme = 5;
					sujetType = "Config Alarme Batterie:";
					if((versionBaliseGlobal == "46" )||(versionBaliseGlobal == "47" )||(versionBaliseGlobal == "53" ))		// SC TABLO, FLEET, CUBE
					{
						modeMessage = modeMessageGlobal;
						corps1 = "FA6"+cfg+",0,0,0";
						corps2 = "FAf"+ document.getElementById("select_seuil_bat").value +","+ document.getElementById("alerte_filtrage").value;
						sujetSeuilBat = " * Seuil d'alerte: "+ document.getElementById("select_seuil_bat").options[document.getElementById("select_seuil_bat").selectedIndex].text +" * Filtrage: "+ document.getElementById("alerte_filtrage").value +" mn";
					}
					break;
				case "alarmealimentation":
					TypeAlarme = 6;
					sujetType = "Config Alarme Alimentation:";
					break;
				case "alarmedeplacement":
					TypeAlarme = 7;
					sujetType = "Config Alarme Déplacement:";
					break;
				case "alarme1":
					TypeAlarme = 8;
					sujetType = "Config Alarme Arrachement:";
					if((versionBaliseGlobal == "47" )||(versionBaliseGlobal == "53" ))		// FLEET, CUBE
					{
						if(versionBaliseGlobal == "47" )		// FLEET
							sujetType = "Config Alarme Porte:";
						
						modeMessage = modeMessageGlobal;
						var rt = document.getElementById("input_alert_temps_reel").value;
						if(cfg == "000") rt = "0";
						corps1 = "FA1001,0,0,"+rt;
						corps2 = "FAa0,"+ document.getElementById("alerte_filtrage").value;
						sujetSeuilBat = " * Temps réel: "+rt+" mn"+" * Filtrage: "+ document.getElementById("alerte_filtrage").value +" mn";
					}
					
					break;
				case "alarme2":
					TypeAlarme = 9;
					if(versionBaliseGlobal == "47" )		// FLEET
					{
						sujetType = "Config SMS Surveillance:";
						
						modeMessage = modeMessageGlobal;
						//var rt = document.getElementById("input_alert_temps_reel").value;
						//if(cfg == "000") rt = "0";
						//corps1 = "FA2001,0,0,"+rt;
						corps1 = "FA2001,0,0,0";
						corps2 = "FAb0,"+ document.getElementById("alerte_filtrage").value;
						sujetSeuilBat = " * Filtrage: "+ document.getElementById("alerte_filtrage").value +" mn";
					}
					else
						sujetType = "Config Alarme Couvercle:";
					
					break;
			}
			
			var sujet = sujetType+ "  " + sujetEtat + " * Apparition sur:" + apparitionTotale + ", Disparition sur:"+ disparitionTotale + sujetSeuilBat;
			
			$.ajax({
				url : '../geofencing/geofencingvalidwarning.php',
				type : 'GET',
				data : "idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&zone=1"+
				"&messageEntree="+messageApparition+"&messageSortie="+messageDisparition+"&destMethod="+sms+"&warningType="+cfg+"&Type_Geometrie="+TypeAlarme+
				"&warningLap="+date.getTimezoneOffset(),
				
				success: function(response) {
					if(response){
						$.ajax({
							url: '../configuration/configurationvalidtmessages.php',
							type: 'GET',
							data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
							"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + encodeURIComponent(sujet) +
							"&corps1=" + corps1 + "&corps2=" + corps2,
							async: true,
							success: function (response2) {
								if(response2){
									alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
											+ sujet + "\n\n");
								}
							}
						});
					}
				}
			});
		}
		else
		{
			// Stancom
			var sujetRtNoNf = "";
			var sujetParking = "";
			var sujetSeuilBat = "";
			
			var corpsType = "";
			var corpsParkingMode = "";
			var parkingMode = 0;
			var corpsFiltrage = "";
			var corpsMessagesApparition = "";
			var corpsMessagesDisparition = "";
			
			var filtrage = document.getElementById("alerte_filtrage").value;
			var rt = "0";
			var noNf = "";
			var mel = "000";
			
			if(document.getElementById("alertsms_normalement").value == "ferme") noNf = "1";
			if(document.getElementById("alertsms_normalement").value == "ouvert") noNf = "0";
	
			switch(selectTypeAlert){
				case "alarme1":
					rt = document.getElementById("input_alert_temps_reel").value;
					sujetRtNoNf = " * Temps réel: "+rt+" mn";
					if(versionBaliseGlobal == "53" || versionBaliseGlobal == "56" || versionBaliseGlobal == "57"){
						noNf = "0";
						sujetType = "Config Alarme Arrachement:";
					}else{
						sujetRtNoNf += " * Normalement: "+document.getElementById("alertsms_normalement").options[document.getElementById("alertsms_normalement").selectedIndex].text;
						sujetType = "Config Alarme 1:";
					}
					corpsType = "FA1";
					corpsFiltrage = "FAa"+noNf+",";			
					corpsMessagesApparition = "FM0240";
					corpsMessagesDisparition = "FM0270";
					break;
				case "alarme2":
					rt = document.getElementById("input_alert_temps_reel").value;
					sujetRtNoNf = " * Temps réel: "+rt+" mn";
					sujetRtNoNf += " * Normalement: "+document.getElementById("alertsms_normalement").options[document.getElementById("alertsms_normalement").selectedIndex].text;
					sujetType = "Config Alarme 2:";
					corpsType = "FA2";
					corpsFiltrage = "FAb"+noNf+",";
					corpsMessagesApparition = "FM0300";
					corpsMessagesDisparition = "FM0330";
					break;
				case "alarmeparking":
					var secVib = 0;
					var noeudVitesse = 0;
					if (document.getElementById('checkbox_parking_sur_deplacement').checked == true) {
						sujetParking += " Sur Déplacement";
						parkingMode += 1;
					}
					if (document.getElementById('checkbox_parking_sur_vibration').checked == true) {
						sujetParking += " Sur Vibration";
						parkingMode += 2;
					}
					if (document.getElementById('checkbox_parking_sur_vitesse').checked == true) {
						sujetParking += " Sur Vitesse";
						parkingMode += 4;
					}
					secVib = parseInt(document.getElementById('detection_alerte_vibration').value);
					noeudVitesse = Math.round((parseInt(document.getElementById('detection_alerte_vitesse').value) * 0.53995680346039));

					sujetType = "Config Alarme Parking:"+sujetParking;
					corpsType = "FA3";
					corpsParkingMode = "FAC"+parkingMode;
					corpsFiltrage = "FAc003,"+pad(noeudVitesse, 3)+","+pad(secVib, 4)+',0000,';
					corpsMessagesApparition = "FM0570";
					corpsMessagesDisparition = "FM0600";
					rt = document.getElementById("input_alert_temps_reel").value;
					sujetRtNoNf = " * Temps réel: "+rt+" mn";
					break;
				case "alarmedeplacement":
					sujetType = "Config Alarme Déplacement:";
					corpsType = "FA4";
					corpsFiltrage = "FAd";
					corpsMessagesApparition = "FM0360";
					corpsMessagesDisparition = "FM0390";
					break;
				case "alarmealimentation":
					sujetType = "Config Alarme Alimentation:";
					corpsType = "FA5";
					if(versionBaliseGlobal == "55" || versionBaliseGlobal == "57"){		// ajout seuil alim ext a la place du 0 pour les type 55 et 57
						sujetSeuilBat = " * Seuil d'alerte: "+document.getElementById("select_seuil_bat").options[document.getElementById("select_seuil_bat").selectedIndex].text;
						corpsFiltrage = "FAe"+ $("#select_seuil_bat").val() +","; 
					}else{
						corpsFiltrage = "FAe0,";
					}
					corpsMessagesApparition = "FM0480";
					corpsMessagesDisparition = "FM0510";
					break;
				case "alarmebat":
					sujetType = "Config Alarme Batterie:";
					corpsType = "FA6";
					if(versionBaliseGlobal == "55" || versionBaliseGlobal == "57"){		// ajout seuil alim ext a la place du 0 pour les type 55 et 57
						sujetSeuilBat = " * Seuils d'alertes: "+ $("#select_seuil_bat").val() + "/" + $("#seuil2").val() + "/" + $("#seuil3").val() + "%";
						var chk = document.getElementById("asbf");
						if(chk.checked == true){
							corpsFiltrage = "Faf15," + $("#select_seuil_bat").val() + "," + $("#seuil2").val() + "," + $("#seuil3").val() + ","; 
						}else{
							corpsFiltrage = "Faf7," + $("#select_seuil_bat").val() + "," + $("#seuil2").val() + "," + $("#seuil3").val() + ","; 
						}
					}else{
						sujetSeuilBat = " * Seuil d'alerte: "+document.getElementById("select_seuil_bat").options[document.getElementById("select_seuil_bat").selectedIndex].text;
						corpsFiltrage = "FAf"+pad(parseInt(document.getElementById("select_seuil_bat").value), 3)+",";
					}
					corpsMessagesApparition = "FM0420";
					corpsMessagesDisparition = "FM0450";
					break;
			}

			var sujet = sujetType+ "  " + sujetEtat + " * Apparition sur:" + apparitionTotale + ", Disparition sur:"+ disparitionTotale+sujetRtNoNf+sujetSeuilBat+ " * Filtrage: "+filtrage+ " mn";
			corpsType += cfg+","+pad(sms, 3)+","+mel+","+rt;
			corpsMessagesApparition += ",0030,"+messageApparition;
			corpsMessagesDisparition += ",0030,"+messageDisparition;
			corpsFiltrage += filtrage;
		

			var numeroAppel = numeroAppelGlobal;
			var modeMessage = modeMessageGlobal;
		
		
			$.ajax({
				url: '../configuration/configurationvalidtmessages.php',
				type: 'GET',
				data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
				"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + encodeURIComponent(sujet) +
				"&corps1=" + corpsType + "&corps2=" + corpsMessagesApparition + "&corps3=" + corpsMessagesDisparition + "&corps4=" + corpsParkingMode + "&corps5=" + corpsFiltrage,

				success: function (sujet) {
					alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
							+ sujet + "\n\n" +
							getTextModeAttentionParamRapatrie);
				}
			})
		}
	}
}

function validTelephone(){

	var idTracker = document.getElementById("idBalise").innerHTML;
	
	var messageNumero1 = document.getElementById('message_numero_1').value;
	var messageNumero2 = document.getElementById('message_numero_2').value;
	var messageNumero3 = document.getElementById('message_numero_3').value;
	var messageNumero4 = document.getElementById('message_numero_4').value;
	
	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else if(messageNumero1 == "" && messageNumero2 == "" && messageNumero3 == "" && messageNumero4 == "") {
		alert(getTextAlertTelAucunValid);
		return;
	}else if (confirm($('<div />').html( getTextModeConfirmNumTel).text())) {
		
		var nomTracker = document.getElementById("nomBalise").innerHTML;
		var nomDatabaseGpw = globalnomDatabaseGpw;
		var ipDatabaseGpw = globalIpDatabaseGpw;

		var date = new Date();
		var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
			+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();

		var numeroAppel = numeroAppelGlobal;

		var modeMessage = modeMessageGlobal;
		var vide = "0000";

		var sujet = "Enregistrer les téléphones => N°1: "+messageNumero1+" N°2: "+messageNumero2+" N°3: "+messageNumero3+" N°4: "+messageNumero4;
		
		
		// var corpsMessageNumero1 = "";
		// var corpsMessageNumero2 = "";
		// var corpsMessageNumero3 = "";
		// var corpsMessageNumero4 = "";
		
		// if(document.getElementById('message_numero_1').value != "") corpsMessageNumero1 = "?FT1"+document.getElementById('message_numero_1').value+"!";
		// if(document.getElementById('message_numero_2').value != "")	corpsMessageNumero2 = "?FT2"+document.getElementById('message_numero_2').value+"!";
		// if(document.getElementById('message_numero_3').value != "")	corpsMessageNumero3 = "?FT3"+document.getElementById('message_numero_3').value+"!";
		// if(document.getElementById('message_numero_4').value != "")	corpsMessageNumero4 = "?FT4"+document.getElementById('message_numero_4').value+"!";
		
		
//		if( $.inArray(versionBaliseGlobal, ['20','46','47','53','3370','7003','7201']) >= 0)			// Geo3X    : TELTO, 400n, FLEET, CUBE, NEO3G, NEO & SOLO
		if( $.inArray(versionBaliseGlobal, ['20','46','47','53','3370','7003','7201','48']) >= 0) 		// Geofence : TELTO, 400n, FLEET, CUBE, NEO3G, NEO & SOLO + 400LC
		{
			if((versionBaliseGlobal == "3370")||(versionBaliseGlobal == "7003"))		// SC NEO & NEO 3G
			{
				var corps1 = "SOS";
				var corps3 = ",A";
				if(modeMessageGlobal == "SMS")
					modeMessage = "NEOSMS";
				else
					modeMessage = "NEO";
				
				var sujet2 = "\n\n" + getTextModeAttentionParamRapatrie;
			}
			else if(versionBaliseGlobal == "48" )			// Geo3X : Mettre en commentaire tout le traitement du if
			{
				var corps1 = "";
				var corps3 = "";
				modeMessage = modeMessageGlobal;
				var sujet2 = "\n\n" + getTextModeAttentionParamRapatrie;
			}
			else
			{
				var corps1 = "MEMO";
				var corps3 = "";
				modeMessage = "MEMO"
				var sujet2 = "";
			}
			
			
			$.ajax({
				url : '../geofencing/geofencingvalidwarningdest.php',
				type : 'GET',
				data : "idTracker="+idTracker+"&nomDatabaseGpw="+nomDatabaseGpw+"&ipDatabaseGpw="+ipDatabaseGpw+"&numero1="+messageNumero1+"&numero2="+messageNumero2+"&numero3="+messageNumero3+"&numero4="+messageNumero4,
				success: function(response) {
					if(response){
						
						$.ajax({
							url: '../configuration/configurationvalidtmessages.php',
							type: 'GET',
							data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
							"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet +
							"&messageNumero1=" + messageNumero1 + "&messageNumero2=" + messageNumero2 + 
							"&messageNumero3=" + messageNumero3 + "&messageNumero4=" + messageNumero4 + "&corps1=" + corps1 + "&corps3=" + corps3,

							success: function (sujet) {
								alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
										+ sujet + sujet2);
							}
						})
						
					}
				}
			});
		}
		else
		{
			$.ajax({
				url: '../configuration/configurationvalidtmessages.php',
				type: 'GET',
				data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
				"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet +
				"&messageNumero1=" + messageNumero1 + "&messageNumero2=" + messageNumero2 + 
				"&messageNumero3=" + messageNumero3 + "&messageNumero4=" + messageNumero4,

				success: function (sujet) {
					alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
							+ sujet + "\n\n" +
							getTextModeAttentionParamRapatrie);
				}
			})
		}
	}
}

function validateEmail(mail) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(mail);
}

function validMail(){
	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomTracker = document.getElementById("nomBalise").innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
		+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();

	var numeroAppel = numeroAppelGlobal;

	var modeMessage = modeMessageGlobal;

	var mail_as = document.getElementById('mail_as').value;
	
	var sujet = "Enregistrement de l'email: " + mail_as;
	var corpsmail_as = "FE1" + mail_as;

	if (idTracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else {
		if(!validateEmail(mail_as)) {
			alert("Adresse mail invalide.");
			return;
		}else{		
			if (confirm($('<div />').html("Voulez-vous vraiment modifier l'addresse mail ?").text())) {
				$.ajax({
					url: '../configuration/configurationvalidtmessages.php',
					type: 'GET',
					data: "datetime=" + notreDate + "&modeMessage=" + modeMessage + "&idTracker=" + idTracker +
					"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroAppel=" + numeroAppel + "&sujet=" + sujet + "&corps1=" + corpsmail_as,

					success: function (sujet) {
						alert(getTextModeBienEnregistrerParam+" " + nomTracker + "\n\n" + ""
								+ sujet + "\n\n" +
								getTextModeAttentionParamRapatrie);
						//document.getElementById('mail_as').value = "";
					}
				})
			}
		}
	}
}


function onCheckAlertePark(numero){
	switch(numero){
		case 1:
			if (document.getElementById('checkbox_parking_sur_deplacement').checked) {
				document.getElementById('parking_sur_deplacement').style.backgroundColor = "#00FF00";
			} else {
				document.getElementById('parking_sur_deplacement').style.backgroundColor = "";
			}
			break;
		case 2:
			if (document.getElementById('checkbox_parking_sur_vibration').checked) {
				document.getElementById('parking_sur_vibration').style.backgroundColor = "#00FF00";
			} else {
				document.getElementById('parking_sur_vibration').style.backgroundColor = "";
			}
			break;
		case 3:
			if (document.getElementById('checkbox_parking_sur_vitesse').checked) {
				document.getElementById('parking_sur_vitesse').style.backgroundColor = "#00FF00";
			} else {
				document.getElementById('parking_sur_vitesse').style.backgroundColor = "";
			}
			break;
	}
}

function onCheckNumeroApparitionDisparition2(numero){
	switch(numero){
		case 1:
			if(document.getElementById('message_numero_1').value) {
				if (document.getElementById('apparition_numero_1').checked || document.getElementById('disparition_numero_1').checked) {
					document.getElementById('message_numero_1').style.backgroundColor = "#00FF00";
					document.getElementById('numero_1').style.backgroundColor = "#00FF00";
				} else {
					document.getElementById('message_numero_1').style.backgroundColor = "";
					document.getElementById('numero_1').style.backgroundColor = "";
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
					document.getElementById('numero_2').style.backgroundColor = "#00FF00";
				} else {
					document.getElementById('message_numero_2').style.backgroundColor = "";
					document.getElementById('numero_2').style.backgroundColor = "";
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
					document.getElementById('numero_3').style.backgroundColor = "#00FF00";
				} else {
					document.getElementById('message_numero_3').style.backgroundColor = "";
					document.getElementById('numero_3').style.backgroundColor = "";
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
					document.getElementById('numero_4').style.backgroundColor = "#00FF00";
				} else {
					document.getElementById('message_numero_4').style.backgroundColor = "";
					document.getElementById('numero_4').style.backgroundColor = "";
				}
			}else{
				alert($('<div />').html( getTextAlertTel4PasEnregistrer).text());
				document.getElementById('apparition_numero_4').checked = false;
				document.getElementById('disparition_numero_4').checked = false;
			}
			break;
	}
}