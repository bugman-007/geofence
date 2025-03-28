var tourniquet = 0x00;

function tourniStart(mask)
{
	tourniquet |= mask;
	document.body.className = "loading";
}

function tourniStop(mask, affboutonOuvrir)
{
	tourniquet &= ~mask;
	
	if(tourniquet == 0)
	{
        document.body.className = "";
		if(affboutonOuvrir==1)
			document.getElementById("genererRapport").innerHTML = "<input type='submit' class='btn btn-default btn-xs dropdown-toggle' value='" + getTextOuvrir + "'>";
	}
}

function genererRapport()
{
	document.getElementById("genererRapport").innerHTML = "<b>Recherche des positions</b>";

    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    var tz = jstz.determine();
    var timezone = tz.name();

    var idTracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;
    var debutRapport = document.getElementById("debutRapport").value;
    var finRapport = document.getElementById("finRapport").value;
    var fd = new Date(debutRapport); // from date
    var td = new Date(finRapport); // to date
    if (idTracker == "")
    {
        alert(getTextVeuillezChoisirUneBalise);
        return;
    } else if (idTracker.search(/,/) != -1)
    {
        alert(getTextVeuillezChoisirQueUneBalise);
        return
    } else if (fd.getTime() > td.getTime())
    {
        alert(getTextFinSuperieurDebut);
        return;
    } else
    {

        if (debutRapport == "") {
            alert(getTextPasDebut);
            return;
        }
        if (finRapport == "") {
            alert(getTextPasFin);
            return;
        }

		if (document.getElementById("checkbox_address").checked == true)
		{
			if( !confirm("Vous avez demandé le chargement des adresses de chaque position, la génération du rapport peut alors prendre plusieurs minutes.\n\nVoulez-vous vraiment la liste de chaque position avec adresse ?") )
				return;
		}

		echecCageData = 0;

        //if(document.getElementById("formRapportTemps").action == window.location.protocol+"//"+window.location.host+"/Geo3xPhpv4/rapportpdftemps.php")
        afficheRapportEtape();
        rapportTempsOnSubmit();
        if (document.getElementById("checkbox_address").checked == true)
        {
            addAddressAllPosition();
        }

        addAddressAllEtape();
    }
}

/**********************************************************************************/
/************************************ Geocoding ***********************************/
/**********************************************************************************/
function rapportgeocoding(Id_Tracker, coordDateTimeUTC, coordLat, coordLong)
{
	if( echecCageData == 0 )
        //rapportgeocodingCage(Id_Tracker, coordDateTimeUTC, coordLat, coordLong);
        rapportgeocodingTile(Id_Tracker, coordDateTimeUTC, coordLat, coordLong);
	else
        //rapportgeocodingTile(Id_Tracker, coordDateTimeUTC, coordLat, coordLong);
        rapportgeocodingCage(Id_Tracker, coordDateTimeUTC, coordLat, coordLong);
}

function rapportgeocodingCage(Id_Tracker, coordDateTimeUTC, coordLat, coordLong)
{
	$.ajax({
		//async: false,
       // global: false,
		url: 'https://api-adresse.data.gouv.fr/reverse/?lon='+ coordLong +'&lat='+ coordLat,
		//dataType: "json",
		success: function (data) {
			//console.log(data.status.code, data.status.message, data.rate.remaining, data.results[0].formatted);
            if(data.features[0].properties.label != "")
            {
                var coordPosAdresse = data.features[0].properties.label;
				rapportinsertadresse(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, coordPosAdresse);
			}
			else
			{
				console.log("api-adresse.data no addresse");
				echecCageData = 0;//1;
				//rapportgeocodingTile(Id_Tracker, coordDateTimeUTC, coordLat, coordLong);
			}
		},
		error: function() {
			console.log("api-adresse.data request failed!");
			echecCageData = 0;//1;
			//rapportgeocodingTile(Id_Tracker, coordDateTimeUTC, coordLat, coordLong);
		}
	});
}

function rapportgeocodingGoogle(Id_Tracker, coordDateTimeUTC, coordLat, coordLong)
{
	$.ajax({
		async: false,
		global: false,
		url: 'https://maps..com/maps/api/geocode/json?latlng='+ coordLat +','+ coordLong +'&key=AIzaSyCWOutUA1jir2vHwqwLKyRmRiFIYhDPj8k',
		dataType: "json",
		success: function (data) {
			//console.log(data.status.code, data.status.message, data.rate.remaining, data.results[0].formatted_address);
			if( data.status == "OK") 
			{
				var coordPosAdresse = data.results[0].formatted_address;
				rapportinsertadresse(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, coordPosAdresse);
			}
			else
			{
				console.log("Google geocoding error!");
				echecCageData = 1;
				rapportgeocodingTile(Id_Tracker, coordDateTimeUTC, coordLat, coordLong);
			}
		},
		error: function() {
			console.log("Google geocoding failed!");
			echecCageData = 1;
			rapportgeocodingTile(Id_Tracker, coordDateTimeUTC, coordLat, coordLong);
		}
	});
}

function rapportgeocodingTile(Id_Tracker, coordDateTimeUTC, coordLat, coordLong)
{
	$.ajax({
		//async: false,
		//global: false,
        //url: 'https://geocoder.tilehosting.com/r/'+ coordLong +'/'+ coordLat +'.js?key=EUON3NGganG4JD5zzQlN',
        url: 'https://api.maptiler.com/geocoding/'+ coordLong +','+ coordLat +'.json?key=EevE8zHrA8OKNsj637Ms',
		dataType: "json",
		success: function (data) {
            //console.log(data.features[0].place_name);
			if(data.features[0].place_name != "")
            {
                var coordPosAdresse = data.features[0].place_name;
			    rapportinsertadresse(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, coordPosAdresse);
            }
            else
            {
                console.log("no address!");
                //echecCageData = 1;
                //modif 11/06
                rapportgeocodingCage(Id_Tracker, coordDateTimeUTC, coordLat, coordLong);
            }
		},
		error: function() {
			console.log("tilehosting request failed!");
            //modif 11/06
            rapportgeocodingCage(Id_Tracker, coordDateTimeUTC, coordLat, coordLong);
		}
	});
}

function rapportinsertadresse(Id_Tracker, coordDateTimeUTC, coordLat, coordLong, coordPosAdresse)
{
    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;
    
	$.ajax({
		async: false,
		url: '../rapport/rapportinsertadresse.php',
		type: 'GET',
		data: "datetime=" + coordDateTimeUTC + "&address=" + coordPosAdresse +
			  "&lat=" + coordLat + "&lng=" + coordLong + "&idTracker=" + Id_Tracker +
			  "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw,
	});
}

/**********************************************************************************/
/******************************* addAddressAllEtape *******************************/
/**********************************************************************************/
function addAddressAllEtape() {
    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    var tz = jstz.determine();
    var timezone = tz.name();

    var idTracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;
    var debutRapport = document.getElementById("debutRapport").value;
    var finRapport = document.getElementById("finRapport").value;
	
	tourniStart(0x02);

    $.ajax({
        url: '../rapport/rapportanalyseadresse.php',
        type: 'GET',
        data: "debutRapport=" + debutRapport + "&finRapport=" + finRapport + "&idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone,
        success: function (response) {
            if (response) {
                //console.log(response);
				var res = JSON.parse(response);
				var nbadr = res.length;
				var indp=0;
                for(var i in res) {
					if (document.getElementById("checkbox_address").checked == false){
						indp++;
						document.getElementById("genererRapport").innerHTML = "<b>Recherche adresse: " + indp + "/" + nbadr + "</b>";
					}
                    if ((!res[i].Adresse) || (res[i].Adresse == "undefined") || (res[i].Adresse == "") || (res[i].Adresse == null)) {
						rapportgeocoding(idTracker, res[i].DateTime_Position, res[i].Latitude, res[i].Longitude);
                    }
                }
            }
			if (document.getElementById("checkbox_address").checked == false)
				document.getElementById("genererRapport").innerHTML = "<b>Calcul du graphe vitesse</b>";
			tourniStop(0x02, 1);
        },
		error: function(){
			tourniStop(0x02, 0);
		}
    });
}
function addAddressAllPosition() {

    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    var tz = jstz.determine();
    var timezone = tz.name();

    var idTracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;
    var debutRapport = document.getElementById("debutRapport").value;
    var finRapport = document.getElementById("finRapport").value;
	var oldechecCageData = echecCageData;
	tourniStart(0x08);
	
    $.ajax({
        url: '../rapport/rapportanalysealladresse.php',
        type: 'GET',
        data: "debutRapport=" + debutRapport + "&finRapport=" + finRapport + "&idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone,
        success: function (response) {
            if (response) {
                
                var res = JSON.parse(response);
				var nbadr = res.length;
                var indp=0;
                //if (oldechecCageData == 0) echecCageData = 1;
                echecCageData = 0;
                for(var i in res) {
					indp++;
					document.getElementById("genererRapport").innerHTML = "<b>Recherche adresse: " + indp + "/" + nbadr + "</b>";
					
                    if ((!res[i].Adresse) || (res[i].Adresse == "undefined") || (res[i].Adresse == "") || (res[i].Adresse == null)) {
						rapportgeocoding(idTracker, res[i].DateTime_Position, res[i].Latitude, res[i].Longitude);
                    }
                }
                if (oldechecCageData == 0) echecCageData = 0;
            }
			document.getElementById("genererRapport").innerHTML = "<b>Calcul du graphe vitesse</b>";
			tourniStop(0x08, 1);
        },
		error : function(){
			tourniStop(0x08, 0);
		}
    });
}

function genererEtape() {
    document.getElementById("genererEtape").innerHTML = "";
    rapportEtapeOnSubmit();
    if (document.getElementById("avecCarto").checked == true) {
        document.body.className = "loading";
        var xmlhttp = null;
        var nomDatabaseGpw = globalnomDatabaseGpw;
        var ipDatabaseGpw = globalIpDatabaseGpw;
        var numeroEtape = document.getElementById("numeroEtape").innerHTML;
        var idTracker = document.getElementById("idBalise").innerHTML;
        var debutRapport = document.getElementById("debutRapport").value;
        var finRapport = document.getElementById("finRapport").value;

        var tz = jstz.determine();
        var timezone = tz.name();

        $.ajax({
            url: '../rapport/rapportcartoetape.php',
            type: 'GET',
            data: "debutRapport=" + debutRapport + "&finRapport=" + finRapport + "&numeroEtape=" + numeroEtape + "&idBaliseRapport=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw +
                    "&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone,
            success: function (response) {
                if (response) {
                    //alert(response);
                    document.getElementById("urlCarto").value = response;

                    document.getElementById("genererEtape").innerHTML = "<input type='submit' value='" + getTextOuvrir + "'>";
                    if (document.getElementById("graphVitesseEtape").checked == false)
                        document.body.className = "";
                }
            }
        });

    } else {
        if (document.getElementById("graphVitesseEtape").checked == false) {
            document.getElementById("genererEtape").innerHTML = "<input type='submit' class='btn btn-default btn-xs dropdown-toggle' value='" + getTextOuvrir + "'>";
        }
    }

    if (document.getElementById("graphVitesseEtape").checked == true) {
        document.body.className = "loading";
        rapportGraphEtape();
    }

}

function selectNumeroEtape(select) {

    var selectedOption = select.options[select.selectedIndex];
    document.getElementById("numeroEtape").innerHTML = selectedOption.value;

    enleverBoutonOuvrir2();
    // alert ("The selected option is " + selectedOption.value);
}

/*******************************************************************************************************/
function afficheRapportEtape() {
    var xmlhttp = null;
    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;
    var etape = [];
    var vitesseMax = [];
    var vitesseMoy = [];
    var kms = [];
    var tz = jstz.determine();
    var timezone = tz.name();
    var idTracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;
    var debutRapport = document.getElementById("debutRapport").value;
    var finRapport = document.getElementById("finRapport").value;
    var option = "<select onchange='selectNumeroEtape(this)' id='selectEtape' name='selectEtape'>";
    
	tourniStart(0x01);
	
    $.ajax({
        url: '../rapport/rapportinfoetape.php',
        type: 'GET',
        data: "debutRapport=" + debutRapport + "&finRapport=" + finRapport + "&idBaliseRapport=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw +
                "&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone,
        success: function (response) {
            if (response) {
                //alert(response);
                //alert(document.getElementById("formRapportTemps").action);

                if (document.getElementById("formRapportTemps").action == window.location.protocol + "//" + window.location.host + "/web/src/rapport/rapportpdftemps.php")
                {
                    if (document.getElementById("graphCheckbox").checked == true)
                        rapportGraph();
                    
                    var chaine = response;
                    var reg = new RegExp("[&]+", "g");
                    var tableau = chaine.split(reg);
                    var nombreEtape = tableau[0].substring(tableau[0].indexOf('NombreEtape') + 12, tableau[0].indexOf('NumeroEtape'));
                    //console.log(tableau);
                    if (nombreEtape == "") {
                        alert($('<div />').html(getTextAlertPasEtape).text());
                        document.getElementById("bodyRapportEtape").innerHTML = "";
                        enleverBoutonOuvrir1();
						tourniStop(0x01, 0);
                        return;
                    }
					
                    for (var i = 0; i < nombreEtape; i++) {
                        etape[i] = tableau[i].substring(tableau[i].indexOf('NumeroEtape') + 12, tableau[i].indexOf('VitesseMax'));
                        vitesseMax[i] = tableau[i].substring(tableau[i].indexOf('VitesseMax') + 11, tableau[i].indexOf('VitesseMoy'));
                        vitesseMoy[i] = tableau[i].substring(tableau[i].indexOf('VitesseMoy') + 11, tableau[i].indexOf('Kms'));
                        kms[i] = tableau[i].substring(tableau[i].indexOf('Kms') + 4, tableau[i].indexOf('fin'));
                        option += "<option value='" + etape[i] + "'>" + etape[i] + "</option>";

                    }
                    //console.log("vitesseMax:",vitesseMax);
                    //console.log("vitesseMoy:",vitesseMoy);
                    if (nombreEtape > 0) {
						;
						/*
                        var body = "<form name=\"formRapportEtape\" action=\"../rapport/rapportpdfetape.php\" method=\"post\" onsubmit=\"rapportEtapeOnSubmit();\" target=\"_blank\">";
                        body += "<table class=\"table table-borderless\">";
                        body += '<tr><td colspan="2"><b> <br/>&nbsp;4) ' + getTextContenuEtape + ':</td><tr>';
                        body += "<tr><td style=\"text-align: center\"> <div class=\"checkbox\">" + getTextEtape + ":" + option + "</select></div></td>";
                        body += "<td style=\"text-align: center\"><div class=\"checkbox\"><label><input type='CHECKBOX' id='avecCarto' name='avecCarto' onClick='enleverBoutonOuvrir2()'>" + getTextAvecCarto + "</label><div></td>";
                        body += "<td style=\"text-align: center\"><div class=\"checkbox\"><label><input type='CHECKBOX' id='graphVitesseEtape' name='graphVitesseEtape'onClick='enleverBoutonOuvrir2()'> " + getTextGrapheVitesse + "</label><div></td></tr></table>";
                        body += "<input type=\"text\" name=\"nomBaliseRapportEtape\" id=\"nomBaliseRapportEtape\" value=\"test\" style=\"display:none\"/>";
                        body += "<input type=\"text\" name=\"idBaliseRapportEtape\" id=\"idBaliseRapportEtape\" value=\"test\" style=\"display:none\"/>";
                        body += "<input type=\"text\" name=\"debutRapportEtape\" id=\"debutRapportEtape\" value=\"test\" style=\"display:none\"/>";
                        body += "<input type=\"text\" name=\"finRapportEtape\" id=\"finRapportEtape\" value=\"test\" style=\"display:none\"/>";
                        body += "<input type=\"text\" name=\"nomDatabaseGpw\" id=\"nomDatabaseGpw\" value='" + nomDatabaseGpw + "' style=\"display:none\"/>";
                        body += "<input type=\"text\" name=\"ipDatabaseGpw\" id=\"ipDatabaseGpw\" value='" + ipDatabaseGpw + "'  style=\"display:none\"/>";
                        body += "<input type=\"text\" name=\"nombreMaxEtape\" id=\"nombreMaxEtape\" value='" + nombreEtape + "' style=\"display:none\"/>";
                        body += "<input type=\"text\" name=\"timezoneEtape\" id=\"timezoneEtape\" value='" + timezone + "' style=\"display:none\"/>";
                        body += "<input type=\"text\" name=\"urlCarto\" id=\"urlCarto\" value=\"\" style=\"display:none\"/>";

                        body += "<table class=\"table table-borderless\">";
                        body += '<tr><td colspan="4"><b> <br/>&nbsp;5)  ' + getTextGenererEtapeSurIntervalle + ': <a href="#" data-toggle="modal" data-target="#infoEtape"><i class="fa fa-info-circle info"></i></a> </b></td></tr>  ';

                        body += "<tr><td colspan=\"2\" style=\"text-align: center\"><input type='button' class='btn btn-default btn-xs dropdown-toggle' onClick='javascript:genererEtape();' value='" + getTextGenererEtape + "' />  </form>";
                        body += "<td colspan=\"2\" style=\"text-align: center\"><div id='genererEtape' > </div>";

                        body += "</center> </td></tr>";

                        document.getElementById("bodyRapportEtape").innerHTML = body;
						*/
                    } else {
                        alert($('<div />').html(getTextAlertPasEtape).text());
                    }
					
                } else {
					;
                    //document.getElementById("genererRapport").innerHTML = "<input type='submit' class='btn btn-default btn-xs dropdown-toggle' value='" + getTextOuvrir + "'>";
                   
                }
				tourniStop(0x01, 1);
            } else {
                //alert('Pas de positions pour balise ' + nomBalise + ' sur l\'intervalle de temps [' + debutRapport + '; ' + finRapport + ']');

                alert($('<div />').html(getTextPasDePositions + ': ' + nomBalise).text());
				tourniStop(0x01, 0);
                enleverBoutonOuvrir1();
            }
        },
		error : function(){
			tourniStop(0x01, 0);
		}
    });
}

/**********************************************************************************************************/
function rapportGraph() {
    var xmlhttp = null;
    var tz = jstz.determine();
    var timezone = tz.name();
    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;
    var idTracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;
    var debutRapport = document.getElementById("debutRapport").value;
    var finRapport = document.getElementById("finRapport").value;
	
	tourniStart(0x04);
	document.getElementById("genererRapport").innerHTML = "<b>Calcul du graphe vitesse</b>";

	
    $.ajax({
        url: '../rapport/rapportgraph.php',
        type: 'POST',
        data: "debutRapport=" + debutRapport + "&finRapport=" + finRapport + "&idBaliseRapport=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone,
        success: function (response) {
            // alert(response);
            //afficheRapportEtape();
			tourniStop(0x04, 1);
        },
		error : function(){
			tourniStop(0x04, 0);
		}
    })

}
function rapportGraphEtape() {

    var xmlhttp = null;
    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;
    var idTracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;
    var debutRapport = document.getElementById("debutRapport").value;
    var finRapport = document.getElementById("finRapport").value;
    var numeroEtape = document.getElementById("numeroEtape").innerHTML;
    var tz = jstz.determine();
    var timezone = tz.name();
    $.ajax({
        url: '../rapport/rapportgraphetape.php',
        type: 'POST',
        data: "debutRapport=" + debutRapport + "&finRapport=" + finRapport + "&idBaliseRapport=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&numeroEtape=" + numeroEtape + "&timezone=" + timezone,
        success: function (response) {
            //$(".progress span").html('100');
            //$(".progressBar .bar").css('width', '100%');
            document.getElementById("genererEtape").innerHTML = "<input type='submit' class='btn btn-default btn-xs dropdown-toggle' value='" + getTextOuvrir + "'>";
            document.body.className = "";
        }
    });

}
var rememberModeRapport;

function divModeRapport(num) {
    switch (num) {
        case 1:
            document.getElementById("onglet_rapport_instant").className = "effect active";
            document.getElementById("onglet_rapport_auto").className = "effect";
            document.body.className = "loading";
            $(document).ready(function () {
                $("#rapportTemps").load("../rapport/rapportinstantane.php", function () {
                    rememberModeRapport = "instant";
                    showCarburant();
                    //document.body.className = "";
                });
            });
            break;
        case 2:
            document.getElementById("onglet_rapport_instant").className = "effect";
            document.getElementById("onglet_rapport_auto").className = "effect active";
            document.body.className = "loading";
            $(document).ready(function () {
                $("#rapportTemps").load("../rapport/rapportautomatique.php", function () {
                    rememberModeRapport = "auto";
                    showCarburant();
                    //showListMail();
                    //listTypeRapport();

                    document.getElementById("tr_1").style.display = "none";
                    document.getElementById("tr_2_1").style.display = "none";
                    document.getElementById("tr_2_2").style.display = "none";
                    document.getElementById("tr_3_1").style.display = "none";
                    document.getElementById("tr_3_2").style.display = "none";
                    document.getElementById("tr_4_1").style.display = "none";
                    document.getElementById("tr_4_2").style.display = "none";
                    document.getElementById("tr_5_1").style.display = "none";
                    document.getElementById("tr_5_2").style.display = "none";
                    document.getElementById("tr_6_1").style.display = "none";
                    //document.body.className = "";
                });
            });
            break;
    }
}

function listTypeRapport() {
    var xmlhttp = null;
    var idTracker = document.getElementById("idBalise").innerHTML;
    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    if (idTracker.search(/,/) != -1) {
        alert(getTextVeuillezChoisirQueUneBalise);
        //document.getElementById("input_new_rapport").className = "btn btn-default btn-xs dropdown-toggle";
        //document.getElementById("input_update_rapport").className = "btn btn-default btn-xs dropdown-toggle";
        //document.getElementById("tr_1").style.display = "none";
        //document.getElementById("tr_2_1").style.display = "none";
        //document.getElementById("tr_2_2").style.display = "none";
        //document.getElementById("tr_3_1").style.display = "none";
        //document.getElementById("tr_3_2").style.display = "none";
        //document.getElementById("tr_4_1").style.display = "none";
        //document.getElementById("tr_4_2").style.display = "none";
        //document.getElementById("tr_5_1").style.display = "none";
        //document.getElementById("tr_5_2").style.display = "none";
        //document.getElementById("tr_6_1").style.display = "none";
    } else if (idTracker) {
        //document.getElementById("panelbody_rapportinstant").style.height = "150px";
        afficherButtonCarburant();
        document.getElementById("input_new_rapport").className = "btn btn-default btn-xs dropdown-toggle";
        document.getElementById("input_update_rapport").className = "btn btn-default btn-xs dropdown-toggle active";
        document.getElementById("tr_1").style.display = "";
        document.getElementById("tr_2_1").style.display = "";
        document.getElementById("tr_2_2").style.display = "";
        document.getElementById("tr_3_1").style.display = "";
        document.getElementById("tr_3_2").style.display = "";
        document.getElementById("tr_4_1").style.display = "";
        document.getElementById("tr_4_2").style.display = "";
        document.getElementById("tr_5_1").style.display = "";
        document.getElementById("tr_5_2").style.display = "";
        document.getElementById("tr_6_1").style.display = "";
        resetRapportAutomatique();
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == "aucun") {
                    alert(getTextPasRapportAuto);
                    document.getElementById("input_new_rapport").className = "btn btn-default btn-xs dropdown-toggle";
                    document.getElementById("input_update_rapport").className = "btn btn-default btn-xs dropdown-toggle";
                    document.getElementById("tr_1").style.display = "none";
                    document.getElementById("tr_2_1").style.display = "none";
                    document.getElementById("tr_2_2").style.display = "none";
                    document.getElementById("tr_3_1").style.display = "none";
                    document.getElementById("tr_3_2").style.display = "none";
                    document.getElementById("tr_4_1").style.display = "none";
                    document.getElementById("tr_4_2").style.display = "none";
                    document.getElementById("tr_5_1").style.display = "none";
                    document.getElementById("tr_5_2").style.display = "none";
                    document.getElementById("tr_6_1").style.display = "none";
                } else
                    document.getElementById("div_select_type_rapport").innerHTML = xmlhttp.responseText;

            }
        }
        xmlhttp.open('GET', "../rapport/rapportselecttyperapport.php?nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&idTracker=" + idTracker, false);
        xmlhttp.send();
    } else {
        alert(getTextVeuillezChoisirQueUneBalise);
        document.getElementById("input_new_rapport").className = "btn btn-default btn-xs dropdown-toggle";
        document.getElementById("input_update_rapport").className = "btn btn-default btn-xs dropdown-toggle";
        document.getElementById("tr_1").style.display = "none";
        document.getElementById("tr_2_1").style.display = "none";
        document.getElementById("tr_2_2").style.display = "none";
        document.getElementById("tr_3_1").style.display = "none";
        document.getElementById("tr_3_2").style.display = "none";
        document.getElementById("tr_4_1").style.display = "none";
        document.getElementById("tr_4_2").style.display = "none";
        document.getElementById("tr_5_1").style.display = "none";
        document.getElementById("tr_5_2").style.display = "none";
        document.getElementById("tr_6_1").style.display = "none";
    }
}

function showListMail() {

    var idTracker = document.getElementById("idBalise").innerHTML;
    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    if (idTracker.search(/,/) != -1) {
        var regIdTracker = new RegExp("[,]+", "g");
        var tableauIdTracker = idTracker.split(regIdTracker);
        $.ajax({
            url: '../rapport/rapportshowwarningdest.php',
            type: 'GET',
            data: "idTracker=" + tableauIdTracker[0] + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw,
            success: function (response) {
                var reg = new RegExp("[&]+", "g");
                var tableau = response.split(reg);

                var dest01 = tableau[0].substring(tableau[0].indexOf('dest01') + 7, tableau[0].indexOf('dest02'));
                var dest02 = tableau[0].substring(tableau[0].indexOf('dest02') + 7, tableau[0].indexOf('dest03'));
                var dest03 = tableau[0].substring(tableau[0].indexOf('dest03') + 7, tableau[0].indexOf('dest04'));
                var dest04 = tableau[0].substring(tableau[0].indexOf('dest04') + 7);

                if (dest01)
                    document.getElementById("text_mail_1").value = dest01;
                if (dest02)
                    document.getElementById("text_mail_2").value = dest02;
                if (dest03)
                    document.getElementById("text_mail_3").value = dest03;
                if (dest04)
                    document.getElementById("text_mail_4").value = dest04;
            }
        });

        //resetRapportAutomatique();

    } else if (idTracker) {
        $.ajax({
            url: '../rapport/rapportshowwarningdest.php',
            type: 'GET',
            data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw,
            success: function (response) {
                var reg = new RegExp("[&]+", "g");
                var tableau = response.split(reg);

                var dest01 = tableau[0].substring(tableau[0].indexOf('dest01') + 7, tableau[0].indexOf('dest02'));
                var dest02 = tableau[0].substring(tableau[0].indexOf('dest02') + 7, tableau[0].indexOf('dest03'));
                var dest03 = tableau[0].substring(tableau[0].indexOf('dest03') + 7, tableau[0].indexOf('dest04'));
                var dest04 = tableau[0].substring(tableau[0].indexOf('dest04') + 7);

                if (dest01)
                    document.getElementById("text_mail_1").value = dest01;
                if (dest02)
                    document.getElementById("text_mail_2").value = dest02;
                if (dest03)
                    document.getElementById("text_mail_3").value = dest03;
                if (dest04)
                    document.getElementById("text_mail_4").value = dest04;
            }
        });
    } else {
        resetRapportAutomatique();
    }


}
function saveTypeRapport() {

    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    var idTracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;
    var formatRapport;
    var type = document.getElementById("select_type_rapport").value;
    var dateTimeDebut;
    var dateTimeFin;
    var dateTimeEnvoi = document.getElementById("prochain_envoi_rapport").value;
    var heureJourD;
    var heureJourF;
    var sujet = document.getElementById("objet").value;
    var message = document.getElementById("message").value;
    var jourEnvoi;
    var date = new Date();

    if (document.getElementById("radio_pdf").checked == true)
        formatRapport = "1";
    if (document.getElementById("radio_htm").checked == true)
        formatRapport = "2";
    if (document.getElementById("radio_excel").checked == true)
        formatRapport = "3";
    if (document.getElementById("radio_xml").checked == true)
        formatRapport = "4";

    if (type == "0" || type == "1" || type == "4") {
        dateTimeDebut = document.getElementById("debutRapport").value;
        dateTimeFin = document.getElementById("finRapport").value;
        heureJourD = dateTimeDebut[11] + dateTimeDebut[12] + dateTimeDebut[14] + dateTimeDebut[15];
        heureJourF = dateTimeFin[11] + dateTimeFin[12] + dateTimeFin[14] + dateTimeFin[15];
        jourEnvoi = "0";
        if (type == "4")
            jourEnvoi = "1";
    }
    if (type == "2" || type == "5") {

        heureJourD = document.getElementById("select_debut_jour_hebdomadaire").value;
        heureJourF = document.getElementById("select_fin_jour_hebdomadaire").value;

        var dateJourD = new Date();
        dateJourD.setDate(date.getDate() - date.getDay() + parseInt(heureJourD));
        dateTimeDebut = dateJourD.getFullYear() + "-" + (((dateJourD.getMonth() + 1) < 10) ? "0" : "") + (dateJourD.getMonth() + 1) + "-" + ((dateJourD.getDate() < 10) ? "0" : "") + dateJourD.getDate() + " 00:00:00";
        var dateJourF = new Date();
        dateJourF.setDate(date.getDate() - date.getDay() + parseInt(heureJourF));
        dateTimeFin = dateJourF.getFullYear() + "-" + (((dateJourF.getMonth() + 1) < 10) ? "0" : "") + (dateJourF.getMonth() + 1) + "-" + ((dateJourF.getDate() < 10) ? "0" : "") + dateJourF.getDate() + " 23:59:00";

        if (type == "5") {
            dateTimeFin = new Date(dateJourF.getTime() + 7 * 24 * 60 * 60 * 1000);
            dateTimeFin = dateTimeFin.getFullYear() + "-" + (((dateTimeFin.getMonth() + 1) < 10) ? "0" : "") + (dateTimeFin.getMonth() + 1) + "-" + ((dateTimeFin.getDate() < 10) ? "0" : "") + dateTimeFin.getDate() + " 23:59:00";

        }


        jourEnvoi = document.getElementById("select_jour_envoi_hebdomadaire").value;
    }
    if (type == "3" || type == "6") {


        heureJourD = document.getElementById("select_debut_jour_mensuel").value;
        heureJourF = document.getElementById("select_fin_jour_mensuel").value;

        var dateJourD = new Date();
        var dateJourF = new Date();

        if ((date.getMonth() + 1) == ("4" || "6" || "9" || "11")) {
            if (heureJourD == "31") {
                dateJourD.setDate(30);
            }
            if (heureJourF == "31") {
                dateJourF.setDate(30);
            }
        } else {
            if ((date.getMonth() + 1) == ("2")) {
                if (heureJourD > "28") {
                    dateJourD.setDate(28);
                } else {
                    dateJourD.setDate(parseInt(heureJourD));
                }
                if (heureJourF > "28") {
                    dateJourF.setDate(28);
                } else {
                    dateJourF.setDate(parseInt(heureJourF));
                }
            } else {
                dateJourD.setDate(parseInt(heureJourD));
                dateJourF.setDate(parseInt(heureJourF));
            }
        }

        dateTimeDebut = dateJourD.getFullYear() + "-" + (((dateJourD.getMonth() + 1) < 10) ? "0" : "") + (dateJourD.getMonth() + 1) + "-" + ((dateJourD.getDate() < 10) ? "0" : "") + dateJourD.getDate() + " 00:00:00";
        dateTimeFin = dateJourF.getFullYear() + "-" + (((dateJourF.getMonth() + 1) < 10) ? "0" : "") + (dateJourF.getMonth() + 1) + "-" + ((dateJourF.getDate() < 10) ? "0" : "") + dateJourF.getDate() + " 23:59:00";
        if (type == "6")
            dateTimeFin = dateJourF.getFullYear() + "-" + (((dateJourF.getMonth() + 1) < 10) ? "0" : "") + (dateJourF.getMonth() + 2) + "-" + ((dateJourF.getDate() < 10) ? "0" : "") + dateJourF.getDate() + " 23:59:00";
        jourEnvoi = document.getElementById("select_jour_envoi_mensuel").value;
    }

    var destMethodArray = new Array();
    var destMethod;
    destMethodArray[0] = "0";
    destMethodArray[1] = "0";
    destMethodArray[2] = "0";
    destMethodArray[3] = "0";
    if (document.getElementById('checkbox_mail_1').checked == true)
        destMethodArray[7] = "1";
    if (document.getElementById('checkbox_mail_1').checked == false)
        destMethodArray[7] = "0";
    if (document.getElementById('checkbox_mail_2').checked == true)
        destMethodArray[6] = "1";
    if (document.getElementById('checkbox_mail_2').checked == false)
        destMethodArray[6] = "0";
    if (document.getElementById('checkbox_mail_3').checked == true)
        destMethodArray[5] = "1";
    if (document.getElementById('checkbox_mail_3').checked == false)
        destMethodArray[5] = "0";
    if (document.getElementById('checkbox_mail_4').checked == true)
        destMethodArray[4] = "1";
    if (document.getElementById('checkbox_mail_4').checked == false)
        destMethodArray[4] = "0";

    destMethod = binaryToDecimal(destMethodArray);

    var rapportEtapeArray = new Array();
    var rapportEtape;
    rapportEtapeArray[0] = "0";
    rapportEtapeArray[1] = "0";
    rapportEtapeArray[2] = "0";
    rapportEtapeArray[3] = "0";
    if (document.getElementById('etapeCheckbox').checked == true)
        rapportEtapeArray[7] = "1";
    if (document.getElementById('etapeCheckbox').checked == false)
        rapportEtapeArray[7] = "0";
    if (document.getElementById('stopCheckbox').checked == true)
        rapportEtapeArray[6] = "1";
    if (document.getElementById('stopCheckbox').checked == false)
        rapportEtapeArray[6] = "0";
    if (document.getElementById('graphCheckbox').checked == true)
        rapportEtapeArray[5] = "1";
    if (document.getElementById('graphCheckbox').checked == false)
        rapportEtapeArray[5] = "0";
    if (document.getElementById('checkbox_address').checked == true)
        rapportEtapeArray[4] = "1";
    if (document.getElementById('checkbox_address').checked == false)
        rapportEtapeArray[4] = "0";

    rapportEtape = binaryToDecimal(rapportEtapeArray);


    if (destMethod != "0") {
        if (idTracker.search(/,/) != -1) {
            var regIdTracker = new RegExp("[,]+", "g");
            var tableauIdTracker = idTracker.split(regIdTracker);
            var regNomBalise = new RegExp("[,]+", "g");
            var tableauNomBalise = nomBalise.split(regNomBalise);
            if (confirm(getTextConfirmWarningAllBalises + ": \n" + nomBalise)) {
                if (confirm(getTextConfirmEnregistrer + ": \n" + nomBalise + " ? ")) {
                    for (var i = 0; i < tableauIdTracker.length; i++) {
                        saveWarningDestRapport(tableauIdTracker[i], nomDatabaseGpw, ipDatabaseGpw);
                        $.ajax({
                            url: '../rapport/rapportsavetyperapport.php',
                            type: 'GET',
                            data: "idTracker=" + tableauIdTracker[i] + "&nomTracker=" + tableauNomBalise[i] + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw +
                                    "&formatRapport=" + formatRapport + "&rapportEtape=" + rapportEtape + "&type=" + type + "&heureJourD=" + heureJourD + "&heureJourF=" + heureJourF +
                                    "&dateTimeDebut=" + dateTimeDebut + "&dateTimeFin=" + dateTimeFin + "&dateTimeEnvoi=" + dateTimeEnvoi +
                                    "&jourEnvoi=" + jourEnvoi + "&fuseauDecalage=" + date.getTimezoneOffset() + "&sujet=" + encodeURIComponent(sujet) +
                                    "&message=" + message + "&destMethod=" + destMethod,
                            async: true,
                            success: function (response) {
                                alert($('<div />').html(response).text());
                                //alert(response);


                            }
                        });
                    }
                }
            }
        } else {
            if (confirm(getTextConfirmEnregistrer + ": " + nomBalise + " ? ")) {
                $.ajax({
                    url: '../rapport/rapportsavetyperapport.php',
                    type: 'GET',
                    data: "idTracker=" + idTracker + "&nomTracker=" + nomBalise + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw +
                            "&formatRapport=" + formatRapport + "&rapportEtape=" + rapportEtape + "&type=" + type + "&heureJourD=" + heureJourD + "&heureJourF=" + heureJourF +
                            "&dateTimeDebut=" + dateTimeDebut + "&dateTimeFin=" + dateTimeFin + "&dateTimeEnvoi=" + dateTimeEnvoi +
                            "&jourEnvoi=" + jourEnvoi + "&fuseauDecalage=" + date.getTimezoneOffset() + "&sujet=" + encodeURIComponent(sujet) +
                            "&message=" + message + "&destMethod=" + destMethod,
                    async: true,
                    success: function (response) {
                        alert($('<div />').html(response).text());
                        saveWarningDestRapport(idTracker, nomDatabaseGpw, ipDatabaseGpw);
                    }
                });
            }
        }
    } else {
        alert("Veuillez choisir au moins un destinataire");
    }

}
function saveWarningDestRapport(idTracker, nomDatabaseGpw, ipDatabaseGpw) {
    var mail1 = document.getElementById('text_mail_1').value;
    var mail2 = document.getElementById('text_mail_2').value;
    var mail3 = document.getElementById('text_mail_3').value;
    var mail4 = document.getElementById('text_mail_4').value;

    $.ajax({
        url: '../rapport/rapportsavewarningdest.php',
        type: 'GET',
        data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&mail1=" + mail1 + "&mail2=" + mail2 + "&mail3=" + mail3 + "&mail4=" + mail4
                //success: function(response) {
                //	if(response){
                //		alert("Le(s) mail(s) "+response+" a(ont) \351t\351 modifi\351");
                //		//alert(response);
                //	}
                //}
    });
}


function addNewTypeRapport() {
    var idTracker = document.getElementById("idBalise").innerHTML;
    document.getElementById('etapeCheckbox').checked = false;
    document.getElementById('stopCheckbox').checked = false;
    document.getElementById('graphCheckbox').checked = false;
    document.getElementById('checkbox_address').checked = false;

    //if(idTracker) {
    afficherButtonCarburant();
    //document.getElementById("panelbody_rapportinstant").style.height = "150px";
    document.getElementById("input_new_rapport").className = "btn btn-default btn-xs dropdown-toggle active";
    document.getElementById("input_update_rapport").className = "btn btn-default btn-xs dropdown-toggle";
    resetRapportAutomatique();
    //document.getElementById("div_select_type_rapport").innerHTML =  xmlhttp.responseText;
    document.getElementById("tr_1").style.display = "";
    document.getElementById("tr_2_1").style.display = "none";
    document.getElementById("tr_2_2").style.display = "none";
    document.getElementById("tr_3_1").style.display = "none";
    document.getElementById("tr_3_2").style.display = "none";
    document.getElementById("tr_4_1").style.display = "none";
    document.getElementById("tr_4_2").style.display = "none";
    document.getElementById("tr_5_1").style.display = "none";
    document.getElementById("tr_5_2").style.display = "none";
    document.getElementById("tr_6_1").style.display = "none";

    document.getElementById("div_select_type_rapport").innerHTML = '<select id="select_type_rapport" class="geo3x_input_datetime" onChange="selectNewTypeRapport(this.value);">' +
            '<option value="nothing" >' + getTextChoisirTypeSelect + '</option>' +
            '<option value="0" >' + getTextUneFois + '</option>' +
            '<option value="1" >' + getTextJournalier + '</option>' +
            '<option value="4" >' + getTextJournalierPlus + '</option>' +
            '<option value="2" >' + getTextHebdomadaire + '</option>' +
            '<option value="5" >' + getTextHebdomadairePlus + '</option>' +
            '<option value="3" >' + getTextMensuel + '</option>' +
            '<option value="6" >' + getTextMensuelPlus + '</option>' +
            '</select>';

    //}else{
    //	alert(getTextVeuillezChoisirUneBalise);
    //	document.getElementById("input_new_rapport").className = "btn btn-default btn-xs dropdown-toggle";
    //	document.getElementById("input_update_rapport").className = "btn btn-default btn-xs dropdown-toggle";
    //}
}

function selectNewTypeRapport(type) {

    var nameTracker = document.getElementById('nomBalise').innerHTML;
    showListMail();
    document.getElementById("tr_1").style.display = "";
    document.getElementById("tr_2_1").style.display = "";
    document.getElementById("tr_2_2").style.display = "";
    document.getElementById("tr_3_1").style.display = "";
    document.getElementById("tr_3_2").style.display = "";
    document.getElementById("tr_4_1").style.display = "";
    document.getElementById("tr_4_2").style.display = "";
    document.getElementById("tr_5_1").style.display = "";
    document.getElementById("tr_5_2").style.display = "";
    document.getElementById("tr_6_1").style.display = "";

    document.getElementById("radio_pdf").checked = true;
    document.getElementById("tr_affichecarburant").style.display = "none";

    if (type == "0" || type == "1" || type == "4") {
        var notreDateDebut = new Date();
        var notreMoisDebut = notreDateDebut.getMonth() + 1;
        var debutPeriode = notreDateDebut.getFullYear() + "-" + ((notreMoisDebut < 10) ? "0" : "") + notreMoisDebut + "-" + ((notreDateDebut.getDate() < 10) ? "0" : "") + notreDateDebut.getDate() + " 00:00:00";

        var notreDateFin = new Date();
        var notreMoisFin = notreDateFin.getMonth() + 1;
        var finPeriode = notreDateFin.getFullYear() + "-" + ((notreMoisFin < 10) ? "0" : "") + notreMoisFin + "-" + ((notreDateFin.getDate() < 10) ? "0" : "") + notreDateFin.getDate() + " 23:59:00";



        document.getElementById("div_une_fois").style.display = "";
        document.getElementById("div_hebdomadaire").style.display = "none";
        document.getElementById("div_mensuel").style.display = "none";
        if (document.getElementById("language").value == "")
            finPeriode = formatDateAMPM(finPeriode);
        document.getElementById("prochain_envoi_rapport").value = finPeriode;
        if (type == "0")
            document.getElementById("objet").value = getTextRapport + " " + getTextUneFois + " " + nameTracker;
        if (type == "1")
            document.getElementById("objet").value = getTextRapport + " " + getTextJournalier + " " + nameTracker;
        if (type == "4") {
            finPeriode = notreDateFin.getFullYear() + "-" + ((notreMoisFin < 10) ? "0" : "") + notreMoisFin + "-" + (((notreDateFin.getDate() + 1) < 10) ? "0" : "") + (notreDateFin.getDate() + 1) + " 00:00:00";
            var dateTimeEnvoi = notreDateFin.getFullYear() + "-" + ((notreMoisFin < 10) ? "0" : "") + notreMoisFin + "-" + (((notreDateFin.getDate() + 1) < 10) ? "0" : "") + (notreDateFin.getDate() + 1) + " 00:00:00";
            if (document.getElementById("language").value == "")
                dateTimeEnvoi = formatDateAMPM(dateTimeEnvoi);

            document.getElementById("prochain_envoi_rapport").value = dateTimeEnvoi;
            document.getElementById("objet").value = getTextRapport + " " + getTextJournalierPlus + " " + nameTracker;
            document.getElementById("du_div_jour").style.display = "inline";
            document.getElementById("au_div_jour").style.display = "inline";
        } else {
            document.getElementById("du_div_jour").style.display = "none";
            document.getElementById("au_div_jour").style.display = "none";
        }
        if (document.getElementById("language").value == "") {
            debutPeriode = formatDateAMPM(debutPeriode);
            finPeriode = formatDateAMPM(finPeriode);
        }
        document.getElementById("debutRapport").value = debutPeriode;
        document.getElementById("finRapport").value = finPeriode;
    }

    if (type == "2" || type == "5") {
        document.getElementById("div_une_fois").style.display = "none";
        document.getElementById("div_hebdomadaire").style.display = "";
        document.getElementById("div_mensuel").style.display = "none";


        if (type == "2") {
            document.getElementById("objet").value = getTextRapport + " " + getTextHebdomadaire + " " + nameTracker;
            document.getElementById("select_debut_jour_hebdomadaire").value = "1";
            document.getElementById("select_fin_jour_hebdomadaire").value = "7";
            document.getElementById("select_jour_envoi_hebdomadaire").value = "7";
            var dateJourEnvoi = new Date();
            dateJourEnvoi.setDate(date.getDate() - date.getDay() + parseInt(7));
            var dateEnvoi = dateJourEnvoi.getFullYear() + "-" + (((dateJourEnvoi.getMonth() + 1) < 10) ? "0" : "") +
                    (dateJourEnvoi.getMonth() + 1) + "-" + (((dateJourEnvoi.getDate()) < 10) ? "0" : "") + (dateJourEnvoi.getDate()) + " 00:00:00";

            if (document.getElementById("language").value == "")
                dateEnvoi = formatDateAMPM(dateEnvoi);
            document.getElementById("prochain_envoi_rapport").value = dateEnvoi;
        }
        if (type == "5") {
            document.getElementById("objet").value = getTextRapport + " " + getTextHebdomadairePlus + " " + nameTracker;
            document.getElementById("du_div_semaine").style.display = "inline";
            document.getElementById("au_div_semaine").style.display = "inline";
            document.getElementById("select_debut_jour_hebdomadaire").value = "1";
            document.getElementById("select_fin_jour_hebdomadaire").value = "1";
            document.getElementById("select_jour_envoi_hebdomadaire").value = "1";
            var dateJourEnvoi = new Date();
            dateJourEnvoi.setDate(date.getDate() - date.getDay() + parseInt(8));
            var dateEnvoi = dateJourEnvoi.getFullYear() + "-" + (((dateJourEnvoi.getMonth() + 1) < 10) ? "0" : "") +
                    (dateJourEnvoi.getMonth() + 1) + "-" + (((dateJourEnvoi.getDate()) < 10) ? "0" : "") + (dateJourEnvoi.getDate()) + " 00:00:00";
            if (document.getElementById("language").value == "")
                dateEnvoi = formatDateAMPM(dateEnvoi);
            document.getElementById("prochain_envoi_rapport").value = dateEnvoi;
        } else {
            document.getElementById("du_div_semaine").style.display = "none";
            document.getElementById("au_div_semaine").style.display = "none";
        }
    }

    if (type == "3" || type == "6") {
        document.getElementById("div_une_fois").style.display = "none";
        document.getElementById("div_hebdomadaire").style.display = "none";
        document.getElementById("div_mensuel").style.display = "";

        if (type == "3") {
            document.getElementById("objet").value = getTextRapport + " " + getTextMensuel + " " + nameTracker;
            document.getElementById("select_debut_jour_mensuel").value = "1";
            document.getElementById("select_fin_jour_mensuel").value = "31";
            document.getElementById("select_jour_envoi_mensuel").value = "31";
            var dateJourEnvoi = new Date();
            dateJourEnvoi.setDate(31);
            var dateEnvoi = dateJourEnvoi.getFullYear() + "-" + (((dateJourEnvoi.getMonth() + 1) < 10) ? "0" : "") +
                    (dateJourEnvoi.getMonth() + 1) + "-" + (((dateJourEnvoi.getDate()) < 10) ? "0" : "") + (dateJourEnvoi.getDate()) + " 00:00:00";
            if (document.getElementById("language").value == "")
                dateEnvoi = formatDateAMPM(dateEnvoi);
            document.getElementById("prochain_envoi_rapport").value = dateEnvoi;
        }
        if (type == "6") {
            document.getElementById("objet").value = getTextRapport + " " + getTextMensuelPlus + " " + nameTracker;
            document.getElementById("du_div_mois").style.display = "inline";
            document.getElementById("au_div_mois").style.display = "inline";
            document.getElementById("select_debut_jour_mensuel").value = "1";
            document.getElementById("select_fin_jour_mensuel").value = "1";
            document.getElementById("select_jour_envoi_mensuel").value = "1";
            var dateJourEnvoi = new Date();
            dateJourEnvoi.setDate(32);
            var dateEnvoi = dateJourEnvoi.getFullYear() + "-" + (((dateJourEnvoi.getMonth() + 1) < 10) ? "0" : "") +
                    (dateJourEnvoi.getMonth() + 1) + "-" + (((dateJourEnvoi.getDate()) < 10) ? "0" : "") + (dateJourEnvoi.getDate()) + " 00:00:00";
            if (document.getElementById("language").value == "")
                dateEnvoi = formatDateAMPM(dateEnvoi);
            document.getElementById("prochain_envoi_rapport").value = dateEnvoi;
        } else {
            document.getElementById("du_div_mois").style.display = "none";
            document.getElementById("au_div_mois").style.display = "none";
        }
    }

    document.getElementById("div_bouton_enregister").style.display = "inline";
    //document.getElementById("div_bouton_supprimer").style.display = "inline";
    $('#demo').trigger('change');

}

function teston() {

    var date = new Date();

    var dateMonday = new Date();
    dateMonday.setDate((date.getDate() - date.getDay() + 1));
    var dateFriday = new Date();
    dateFriday.setDate(date.getDate() - date.getDay() + 5);
    var lundi = dateMonday.getFullYear() + "-" + (((dateMonday.getMonth() + 1) < 10) ? "0" : "") + (dateMonday.getMonth() + 1) + "-" + ((dateMonday.getDate() < 10) ? "0" : "") + dateMonday.getDate() + " 00:00:00";
    var vendredi = dateFriday.getFullYear() + "-" + (((dateFriday.getMonth() + 1) < 10) ? "0" : "") + (dateFriday.getMonth() + 1) + "-" + ((dateFriday.getDate() < 10) ? "0" : "") + dateFriday.getDate() + " 00:00:00";

    var jourEnvoi = "5";
    var dateJourEnvoi = new Date();
    dateJourEnvoi.setDate(date.getDate() - date.getDay() + parseInt(jourEnvoi));
    var dateEnvoi = dateJourEnvoi.getFullYear() + "-" + (((dateJourEnvoi.getMonth() + 1) < 10) ? "0" : "") + (dateJourEnvoi.getMonth() + 1) + "-" + ((dateJourEnvoi.getDate() < 10) ? "0" : "") + dateJourEnvoi.getDate() + " 00:00:00";
    alert(lundi);
    alert(vendredi);
    alert(dateEnvoi);



}

function onChangeTextDebut(value) {
    var idTracker = document.getElementById("idBalise").innerHTML;

    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    var type = document.getElementById("select_type_rapport").value;


    var anneeValue = value[0] + value[1] + value[2] + value[3];
    var moisValue = value[5] + value[6];
    var jourValue = value[8] + value[9];

    if (idTracker) {
        if (type == "1" || type == "4") {
            var notreDateJournalier = new Date();
            var annee = notreDateJournalier.getFullYear();
            var mois = notreDateJournalier.getMonth() + 1;
            var jour = notreDateJournalier.getDate();

            var sousAnnee = anneeValue - annee;
            var sousMois = moisValue - mois;
            var sousJour = jourValue - jour;

            if ((sousAnnee != 0) || (sousMois != 0) || (sousJour > 1)) {
                alert(getTextChangerHeure);
                $.ajax({
                    url: '../rapport/rapportshowtyperapport.php',
                    type: 'GET',
                    data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&typeRapport=" + type,
                    async: true,
                    success: function (response) {
                        var dateTimeD = response.substring(response.indexOf('DateTimeD_UTC') + 14, response.indexOf('DateTimeF_UTC'));
                        document.getElementById("debutRapport").value = dateTimeD;
                        $('#demo').trigger('change');
                    }
                });
            }
        }
    }
}
function onChangeTextFin(value) {
    var idTracker = document.getElementById("idBalise").innerHTML;

    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    var type = document.getElementById("select_type_rapport").value;

    var anneeValue = value[0] + value[1] + value[2] + value[3];
    var moisValue = value[5] + value[6];
    var jourValue = value[8] + value[9];

    if (idTracker) {
        if (type == "1" || type == "4") {
            var notreDateJournalier = new Date();
            var annee = notreDateJournalier.getFullYear();
            var mois = notreDateJournalier.getMonth() + 1;
            var jour = notreDateJournalier.getDate();
            var sousAnnee = anneeValue - annee;
            var sousMois = moisValue - mois;
            var sousJour = jourValue - jour;

            if ((sousAnnee != 0) || (sousMois != 0) || (sousJour > 1)) {
                alert(getTextChangerHeure);
                $.ajax({
                    url: '../rapport/rapportshowtyperapport.php',
                    type: 'GET',
                    data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&typeRapport=" + type,
                    async: true,
                    success: function (response) {
                        var dateTimeF = response.substring(response.indexOf('DateTimeF_UTC') + 14, response.indexOf('DateTime_envoiUTC'));
                        document.getElementById("finRapport").value = dateTimeF;
                        $('#demo').trigger('change');
                    }
                });
            }
        }
    }
}
function onChangeTextEnvoi(value) {
    var idTracker = document.getElementById("idBalise").innerHTML;

    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    var type = document.getElementById("select_type_rapport").value;
    var jourEnvoi;

    var date = new Date();
    var dateJourEnvoi = new Date();

    var anneeValue = value[0] + value[1] + value[2] + value[3];
    var moisValue = value[5] + value[6];
    var jourValue = value[8] + value[9];


    if (idTracker) {
        if (type == "1" || type == "4") {
            var notreDateJournalier = new Date();
            var annee = notreDateJournalier.getFullYear();
            var mois = notreDateJournalier.getMonth() + 1;
            var jour = notreDateJournalier.getDate();

            var sousAnnee = anneeValue - annee;
            var sousMois = moisValue - mois;
            var sousJour = jourValue - jour;

            if ((sousAnnee != 0) || (sousMois != 0) || (sousJour > 1)) {
                alert(getTextChangerHeure);
                $.ajax({
                    url: '../rapport/rapportshowtyperapport.php',
                    type: 'GET',
                    data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&typeRapport=" + type,
                    async: true,
                    success: function (response) {
                        if (response) {
                            var dateTimeEnvoi = response.substring(response.indexOf('DateTime_envoiUTC') + 18, response.indexOf('JourEnvoi'));
                            document.getElementById("prochain_envoi_rapport").value = dateTimeEnvoi;
                        } else {
                            var notreDateFin = new Date();
                            var notreMoisFin = notreDateFin.getMonth() + 1;
                            var finPeriode = notreDateFin.getFullYear() + "-" + ((notreMoisFin < 10) ? "0" : "") + notreMoisFin + "-" + ((notreDateFin.getDate() < 10) ? "0" : "") + notreDateFin.getDate() + " 23:59:00";
                            document.getElementById("prochain_envoi_rapport").value = finPeriode;
                            if (type == "4") {
                                finPeriode = notreDateFin.getFullYear() + "-" + ((notreMoisFin < 10) ? "0" : "") + notreMoisFin + "-" + (((notreDateFin.getDate() + 1) < 10) ? "0" : "") + (notreDateFin.getDate() + 1) + " 00:00:00";
                                var dateTimeEnvoi = notreDateFin.getFullYear() + "-" + ((notreMoisFin < 10) ? "0" : "") + notreMoisFin + "-" + (((notreDateFin.getDate() + 1) < 10) ? "0" : "") + (notreDateFin.getDate() + 1) + " 00:00:00";
                                document.getElementById("prochain_envoi_rapport").value = dateTimeEnvoi;
                                document.getElementById("prochain_envoi_rapport").value = dateTimeEnvoi;
                            }

                        }
                        $('#demo').trigger('change');
                    }
                });
            }
        }
        if (type == "2" || type == "5") {
            jourEnvoi = document.getElementById("select_jour_envoi_hebdomadaire").value;

            var diff = date.getDate() - date.getDay() + parseInt(jourEnvoi);
            if (date.getDay() == 0)
                diff -= 7;
            diff += 7;
            dateJourEnvoi.setDate(diff);

            if (type == "2")
                dateJourEnvoi.setDate(date.getDate() - date.getDay() + parseInt(jourEnvoi));
            var annee = dateJourEnvoi.getFullYear();
            var mois = dateJourEnvoi.getMonth() + 1;
            var jour = ((dateJourEnvoi.getDate() < 10) ? "0" : "") + dateJourEnvoi.getDate();

            if ((annee != anneeValue) || (mois != moisValue) || (jour != jourValue)) {
                alert(getTextChangerHeureDate);
                $.ajax({
                    url: '../rapport/rapportshowtyperapport.php',
                    type: 'GET',
                    data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&typeRapport=" + type,
                    async: true,
                    success: function (response) {
                        if (response) {

                            var dateTimeEnvoi = response.substring(response.indexOf('DateTime_envoiUTC') + 18, response.indexOf('JourEnvoi'));
                            document.getElementById("prochain_envoi_rapport").value = dateTimeEnvoi;
                        } else {
                            if (type == "2") {
                                document.getElementById("select_debut_jour_hebdomadaire").value = "1";
                                document.getElementById("select_fin_jour_hebdomadaire").value = "7";
                                document.getElementById("select_jour_envoi_hebdomadaire").value = "7";
                                var dateJourEnvoi = new Date();
                                dateJourEnvoi.setDate(date.getDate() - date.getDay() + parseInt(7));
                                var dateEnvoi = dateJourEnvoi.getFullYear() + "-" + (((dateJourEnvoi.getMonth() + 1) < 10) ? "0" : "") +
                                        (dateJourEnvoi.getMonth() + 1) + "-" + (((dateJourEnvoi.getDate()) < 10) ? "0" : "") + (dateJourEnvoi.getDate()) + " 00:00:00";
                                document.getElementById("prochain_envoi_rapport").value = dateEnvoi;
                            }
                            if (type == "5") {
                                document.getElementById("select_debut_jour_hebdomadaire").value = "1";
                                document.getElementById("select_fin_jour_hebdomadaire").value = "1";
                                document.getElementById("select_jour_envoi_hebdomadaire").value = "1";
                                var dateJourEnvoi = new Date();
                                dateJourEnvoi.setDate(date.getDate() - date.getDay() + parseInt(8));
                                var dateEnvoi = dateJourEnvoi.getFullYear() + "-" + (((dateJourEnvoi.getMonth() + 1) < 10) ? "0" : "") +
                                        (dateJourEnvoi.getMonth() + 1) + "-" + (((dateJourEnvoi.getDate()) < 10) ? "0" : "") + (dateJourEnvoi.getDate()) + " 00:00:00";
                                document.getElementById("prochain_envoi_rapport").value = dateEnvoi;
                            }
                        }
                        $('#demo').trigger('change');
                    }
                });
            }
        }
        if (type == "3" || type == "6") {
            jourEnvoi = document.getElementById("select_jour_envoi_mensuel").value;
            dateJourEnvoi.setDate(parseInt(jourEnvoi));
            var annee = dateJourEnvoi.getFullYear();
            var mois = dateJourEnvoi.getMonth() + 1;
            var jour = dateJourEnvoi.getDate();
            if ((annee != anneeValue) || (mois != moisValue) || (jour != jourValue)) {
                alert(getTextChangerHeureDate);
                $.ajax({
                    url: '../rapport/rapportshowtyperapport.php',
                    type: 'GET',
                    data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&typeRapport=" + type,
                    async: true,
                    success: function (response) {
                        if (response) {
                            var dateTimeEnvoi = response.substring(response.indexOf('DateTime_envoiUTC') + 18, response.indexOf('JourEnvoi'));
                            document.getElementById("prochain_envoi_rapport").value = dateTimeEnvoi;
                        } else {
                            if (type == "3") {
                                document.getElementById("select_debut_jour_mensuel").value = "1";
                                document.getElementById("select_fin_jour_mensuel").value = "31";
                                document.getElementById("select_jour_envoi_mensuel").value = "31";

                                var dateJourEnvoi = new Date();
                                dateJourEnvoi.setDate(31);
                                var dateEnvoi = dateJourEnvoi.getFullYear() + "-" + (((dateJourEnvoi.getMonth() + 1) < 10) ? "0" : "") +
                                        (dateJourEnvoi.getMonth() + 1) + "-" + (((dateJourEnvoi.getDate()) < 10) ? "0" : "") + (dateJourEnvoi.getDate()) + " 00:00:00";
                                document.getElementById("prochain_envoi_rapport").value = dateEnvoi;
                            }
                            if (type == "6") {
                                document.getElementById("select_debut_jour_mensuel").value = "1";
                                document.getElementById("select_fin_jour_mensuel").value = "1";
                                document.getElementById("select_jour_envoi_mensuel").value = "1";
                                var dateJourEnvoi = new Date();
                                dateJourEnvoi.setDate(32);
                                var dateEnvoi = dateJourEnvoi.getFullYear() + "-" + (((dateJourEnvoi.getMonth() + 1) < 10) ? "0" : "") +
                                        (dateJourEnvoi.getMonth() + 1) + "-" + (((dateJourEnvoi.getDate()) < 10) ? "0" : "") + (dateJourEnvoi.getDate()) + " 00:00:00";
                                document.getElementById("prochain_envoi_rapport").value = dateEnvoi;
                            }
                        }
                        $('#demo').trigger('change');
                    }
                });
            }
        }

    }
}
function changeJourEnvoiHebdo(jourEnvoi) {
    var selectType = document.getElementById("select_type_rapport").value;
    var prochainEnvoi = document.getElementById("prochain_envoi_rapport").value;
    var dateJourEnvoi = new Date();
    if (selectType == "2")
        dateJourEnvoi.setDate(date.getDate() - date.getDay() + parseInt(jourEnvoi));
    if (selectType == "5")
        dateJourEnvoi.setDate(date.getDate() - date.getDay() + parseInt(jourEnvoi) + 7);

    var dateEnvoi = dateJourEnvoi.getFullYear() + "-" + (((dateJourEnvoi.getMonth() + 1) < 10) ? "0" : "") +
            (dateJourEnvoi.getMonth() + 1) + "-" + ((dateJourEnvoi.getDate() < 10) ? "0" : "") + dateJourEnvoi.getDate() +
            " " + prochainEnvoi[11] + prochainEnvoi[12] + prochainEnvoi[13] + prochainEnvoi[14] + prochainEnvoi[15] + prochainEnvoi[16] +
            prochainEnvoi[17] + prochainEnvoi[18];

    document.getElementById("prochain_envoi_rapport").value = dateEnvoi;

    $('#demo').trigger('change');
}

function changeJourEnvoiMensuel(jourEnvoi) {
    var selectType = document.getElementById("select_type_rapport").value;
    var date = new Date();
    var prochainEnvoi = document.getElementById("prochain_envoi_rapport").value;
    var dateJourEnvoi = new Date();

    if ((date.getMonth() + 1) == ("4" || "6" || "9" || "11")) {
        if (jourEnvoi == "31") {
            alert(getTextDernierJourDuMois + " 30");
            document.getElementById('select_jour_envoi_mensuel').value = "30";
            dateJourEnvoi.setDate(30);
        }
    } else {
        if ((date.getMonth() + 1) == ("2")) {
            if (jourEnvoi > "28") {

                alert(getTextDernierJourDuMois + " 28");
                document.getElementById('select_jour_envoi_mensuel').value = "28";
                dateJourEnvoi.setDate(28);
            } else {
                if (selectType == "3")
                    dateJourEnvoi.setDate(parseInt(jourEnvoi));
                if (selectType == "6")
                    dateJourEnvoi.setDate(parseInt(jourEnvoi) + 31);
            }

        } else {
            if (selectType == "3")
                dateJourEnvoi.setDate(parseInt(jourEnvoi));
            if (selectType == "6")
                dateJourEnvoi.setDate(parseInt(jourEnvoi) + 31);
        }
    }

    var dateEnvoi = dateJourEnvoi.getFullYear() + "-" + (((dateJourEnvoi.getMonth() + 1) < 10) ? "0" : "") +
            (dateJourEnvoi.getMonth() + 1) + "-" + ((dateJourEnvoi.getDate() < 10) ? "0" : "") + dateJourEnvoi.getDate() +
            " " + prochainEnvoi[11] + prochainEnvoi[12] + prochainEnvoi[13] + prochainEnvoi[14] + prochainEnvoi[15] + prochainEnvoi[16] +
            prochainEnvoi[17] + prochainEnvoi[18];

    document.getElementById("prochain_envoi_rapport").value = dateEnvoi;

    $('#demo').trigger('change');
}

function selectTypeRapport(type) {

    var idTracker = document.getElementById("idBalise").innerHTML;
    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;
    var typeRapport;

    showListMail();
    if (idTracker) {
        document.getElementById("tr_affichecarburant").style.display = "none";
        //if(type == "unefois")typeRapport = "0";
        //if(type == "journalier") typeRapport = "1";
        //if(type == "hebdomadaire") typeRapport = "2";
        //if(type == "mensuel") typeRapport = "3";
        //if(type == "journalier+") typeRapport = "4";
        //if(type == "hebdomadaire+") typeRapport = "5";
        //if(type == "mensuel+") typeRapport = "6";
        $.ajax({
            url: '../rapport/rapportshowtyperapport.php',
            type: 'GET',
            data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&typeRapport=" + type,
            async: true,
            success: function (response) {
                var formatRapport = response.substring(response.indexOf('Format_rapport') + 15, response.indexOf('RapportEtape'));
                var rapportEtape = response.substring(response.indexOf('RapportEtape') + 13, response.indexOf('HeureJourD'));
                var heureJourD = response.substring(response.indexOf('HeureJourD') + 11, response.indexOf('HeureJourF'));
                var heureJourF = response.substring(response.indexOf('HeureJourF') + 11, response.indexOf('DateTimeD_UTC'));
                var dateTimeD = response.substring(response.indexOf('DateTimeD_UTC') + 14, response.indexOf('DateTimeF_UTC'));
                var dateTimeF = response.substring(response.indexOf('DateTimeF_UTC') + 14, response.indexOf('DateTime_envoiUTC'));
                var dateTimeEnvoi = response.substring(response.indexOf('DateTime_envoiUTC') + 18, response.indexOf('JourEnvoi'));
                var jourEnvoi = response.substring(response.indexOf('JourEnvoi') + 10, response.indexOf('Sujet'));
                var sujet = response.substring(response.indexOf('Sujet') + 6, response.indexOf('Message'));
                var message = response.substring(response.indexOf('Message') + 8, response.indexOf('Dest_Method'));
                var destMethod = response.substring(response.indexOf('Dest_Method') + 12);

                checkRapport(rapportEtape);

                if (document.getElementById("language").value == "") {
                    dateTimeD = formatDateAMPM(dateTimeD);
                    dateTimeF = formatDateAMPM(dateTimeF);
                    dateTimeEnvoi = formatDateAMPM(dateTimeEnvoi);
                }

                if (formatRapport == "1") {
                    document.getElementById("radio_pdf").checked = true;
                    document.getElementById("radio_htm").checked = false;
                    document.getElementById("radio_excel").checked = false;
                    document.getElementById("radio_xml").checked = false;
                }
                if (formatRapport == "2") {
                    document.getElementById("radio_pdf").checked = false;
                    document.getElementById("radio_htm").checked = true;
                    document.getElementById("radio_excel").checked = false;
                    document.getElementById("radio_xml").checked = false;
                }
                if (formatRapport == "3") {
                    document.getElementById("radio_pdf").checked = false;
                    document.getElementById("radio_htm").checked = false;
                    document.getElementById("radio_excel").checked = true;
                    document.getElementById("radio_xml").checked = false;
                }
                if (formatRapport == "4") {
                    document.getElementById("radio_pdf").checked = false;
                    document.getElementById("radio_htm").checked = false;
                    document.getElementById("radio_excel").checked = false;
                    document.getElementById("radio_xml").checked = true;
                }

                document.getElementById("debutRapport").value = dateTimeD;
                document.getElementById("finRapport").value = dateTimeF;

                document.getElementById("objet").value = sujet;
                document.getElementById("message").value = message;
                document.getElementById("prochain_envoi_rapport").value = dateTimeEnvoi;

                if (type == "0" || type == "1" || type == "4") {
                    //document.getElementById("tr_vide").style.display = "none";
                    document.getElementById("div_une_fois").style.display = "";
                    document.getElementById("div_hebdomadaire").style.display = "none";
                    document.getElementById("div_mensuel").style.display = "none";
                    if (type == "4") {
                        document.getElementById("du_div_jour").style.display = "inline";
                        document.getElementById("au_div_jour").style.display = "inline";
                    } else {
                        document.getElementById("du_div_jour").style.display = "none";
                        document.getElementById("au_div_jour").style.display = "none";
                    }
                }
                if (type == "2" || type == "5") {
                    document.getElementById("div_une_fois").style.display = "none";
                    document.getElementById("div_hebdomadaire").style.display = "";
                    document.getElementById("div_mensuel").style.display = "none";
                    document.getElementById("select_debut_jour_hebdomadaire").value = heureJourD;
                    document.getElementById("select_fin_jour_hebdomadaire").value = heureJourF;
                    document.getElementById("select_jour_envoi_hebdomadaire").value = jourEnvoi;
                    if (type == "5") {
                        document.getElementById("du_div_semaine").style.display = "inline";
                        document.getElementById("au_div_semaine").style.display = "inline";
                    } else {
                        document.getElementById("du_div_semaine").style.display = "none";
                        document.getElementById("au_div_semaine").style.display = "none";
                    }
                }

                if (type == "3" || type == "6") {
                    document.getElementById("div_une_fois").style.display = "none";
                    document.getElementById("div_hebdomadaire").style.display = "none";
                    document.getElementById("div_mensuel").style.display = "";
                    var date = new Date();
                    if ((date.getMonth() + 1) == ("4" || "6" || "9" || "11")) {
                        if (heureJourD == "31") {
                            document.getElementById("select_debut_jour_mensuel").value = "30";
                        }
                        if (heureJourF == "31") {
                            document.getElementById("select_fin_jour_mensuel").value = "30";
                        }
                        if (jourEnvoi == "31") {
                            document.getElementById("select_jour_envoi_mensuel").value = "30";
                        }
                    } else {
                        if ((date.getMonth() + 1) == ("2")) {
                            if (heureJourD > "28") {
                                document.getElementById("select_debut_jour_mensuel").value = "28";
                            }
                            if (heureJourF > "28") {
                                document.getElementById("select_fin_jour_mensuel").value = "28";
                            }
                            if (jourEnvoi > "28") {
                                document.getElementById("select_jour_envoi_mensuel").value = "28";
                            }
                        } else {
                            document.getElementById("select_debut_jour_mensuel").value = heureJourD;
                            document.getElementById("select_fin_jour_mensuel").value = heureJourF;
                            document.getElementById("select_jour_envoi_mensuel").value = jourEnvoi;
                        }
                    }

                    if (type == "6") {
                        document.getElementById("du_div_mois").style.display = "inline";
                        document.getElementById("au_div_mois").style.display = "inline";
                    } else {
                        document.getElementById("du_div_mois").style.display = "none";
                        document.getElementById("au_div_mois").style.display = "none";
                    }
                }

                //document.getElementById("demo").value = "date";
                document.getElementById("div_bouton_enregister").style.display = "inline";
                document.getElementById("div_bouton_supprimer").style.display = "inline";
                checkMail(destMethod);
                $('#demo').trigger('change');

                if (type == "nothing")
                    resetRapportAutomatique();
            }
        });
    } else {
        alert(getTextVeuillezChoisirUneBalise);
        resetRapportAutomatique();
    }
}

function deleteRapportAutomatique() {
    var sujet = document.getElementById("objet").value;
    var selectType = document.getElementById("select_type_rapport").value;
    var idTracker = document.getElementById("idBalise").innerHTML;
    var nameTracker = document.getElementById("nomBalise").innerHTML;
    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;
    if (confirm(getTextConfirmSupprimer + "? " + sujet)) {
        $.ajax({
            url: '../rapport/rapportdeleterapport.php',
            type: 'GET',
            data: "idTracker=" + idTracker + "&type=" + selectType + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw,
            success: function (response) {
                //if(selectType == "0") alert("Le rapport automatique Une Fois a été supprimé pour la Balise: "+ nameTracker);
                //if(selectType == "1") alert("Le rapport automatique Journalier a été supprimé pour la Balise: "+ nameTracker);
                //if(selectType == "2") alert("Le rapport automatique Hebdomadaire a été supprimé pour la Balise: "+ nameTracker);
                //if(selectType == "3") alert("Le rapport automatique Mensuel a été supprimé pour la Balise: "+ nameTracker);
                //if(selectType == "4") alert("Le rapport automatique Journalier+ a été supprimé pour la Balise: "+ nameTracker);
                //if(selectType == "5") alert("Le rapport automatique Hebdomadaire+ a été supprimé pour la Balise: "+ nameTracker);
                //if(selectType == "6") alert("Le rapport automatique Mensuel+ a été supprimé pour la Balise: "+ nameTracker);

                if (selectType == "0")
                    alert($('<div />').html(getTextUneFois + " " + getTextAlertSupprimer + ": " + nameTracker).text());
                if (selectType == "1")
                    alert($('<div />').html(getTextJournalier + " " + getTextAlertSupprimer + ": " + nameTracker).text());
                if (selectType == "2")
                    alert($('<div />').html(getTextHebdomadaire + " " + getTextAlertSupprimer + ": " + nameTracker).text());
                if (selectType == "3")
                    alert($('<div />').html(getTextMensuel + " " + getTextAlertSupprimer + ": " + nameTracker).text());
                if (selectType == "4")
                    alert($('<div />').html(getTextJournalierPlus + " " + getTextAlertSupprimer + ": " + nameTracker).text());
                if (selectType == "5")
                    alert($('<div />').html(getTextHebdomadairePlus + " " + getTextAlertSupprimer + ": " + nameTracker).text());
                if (selectType == "6")
                    alert($('<div />').html(getTextMensuelPlus + " " + getTextAlertSupprimer + ": " + nameTracker).text());

                resetRapportAutomatique();
                listTypeRapport();
                showListMail();
            }

        })
    }
}
function resetRapportAutomatique() {
    document.getElementById("select_type_rapport").value = "nothing";
    document.getElementById('text_mail_1').style.backgroundColor = "";
    document.getElementById('text_mail_2').style.backgroundColor = "";
    document.getElementById('text_mail_3').style.backgroundColor = "";
    document.getElementById('text_mail_4').style.backgroundColor = "";
    document.getElementById('checkbox_mail_1').checked = false;
    document.getElementById('checkbox_mail_2').checked = false;
    document.getElementById('checkbox_mail_3').checked = false;
    document.getElementById('checkbox_mail_4').checked = false;
    document.getElementById("text_mail_1").value = "";
    document.getElementById("text_mail_2").value = "";
    document.getElementById("text_mail_3").value = "";
    document.getElementById("text_mail_4").value = "";
    document.getElementById("div_une_fois").style.display = "none";
    document.getElementById("div_hebdomadaire").style.display = "none";
    document.getElementById("div_mensuel").style.display = "none";
    document.getElementById("div_bouton_enregister").style.display = "none";
    document.getElementById("div_bouton_supprimer").style.display = "none";
    document.getElementById("radio_pdf").checked = false;
    document.getElementById("radio_htm").checked = false;
    document.getElementById("radio_excel").checked = false;
    document.getElementById("radio_xml").checked = false;
    document.getElementById("objet").value = "";
    document.getElementById("message").value = "";
    document.getElementById("prochain_envoi_rapport").value = "";
    document.getElementById('etapeCheckbox').checked = false;
    document.getElementById('stopCheckbox').checked = false;
    document.getElementById('graphCheckbox').checked = false;
    document.getElementById('checkbox_address').checked = false;
}
function checkRapport(dec) {
    var decimalEncode = decimaltoBinary(dec);

    if (decimalEncode[7] == "0")
        document.getElementById('etapeCheckbox').checked = false;
    if (decimalEncode[7] == "1")
        document.getElementById('etapeCheckbox').checked = true;
    if (decimalEncode[6] == "0")
        document.getElementById('stopCheckbox').checked = false;
    if (decimalEncode[6] == "1")
        document.getElementById('stopCheckbox').checked = true;
    if (decimalEncode[5] == "0")
        document.getElementById('graphCheckbox').checked = false;
    if (decimalEncode[5] == "1")
        document.getElementById('graphCheckbox').checked = true;
    if (decimalEncode[4] == "0")
        document.getElementById('checkbox_address').checked = false;
    if (decimalEncode[4] == "1")
        document.getElementById('checkbox_address').checked = true;


}

function checkMail(dec) {
    var decimalEncode = decimaltoBinary(dec);


    if (decimalEncode[7] == "0")
        document.getElementById('checkbox_mail_1').checked = false;
    if (decimalEncode[7] == "1")
        document.getElementById('checkbox_mail_1').checked = true;
    if (decimalEncode[6] == "0")
        document.getElementById('checkbox_mail_2').checked = false;
    if (decimalEncode[6] == "1")
        document.getElementById('checkbox_mail_2').checked = true;
    if (decimalEncode[5] == "0")
        document.getElementById('checkbox_mail_3').checked = false;
    if (decimalEncode[5] == "1")
        document.getElementById('checkbox_mail_3').checked = true;
    if (decimalEncode[4] == "0")
        document.getElementById('checkbox_mail_4').checked = false;
    if (decimalEncode[4] == "1")
        document.getElementById('checkbox_mail_4').checked = true;

    if ((document.getElementById('checkbox_mail_1').checked == true)) {
        document.getElementById('text_mail_1').style.backgroundColor = "#00FF00";
    } else {
        document.getElementById('text_mail_1').style.backgroundColor = "";
    }
    if ((document.getElementById('checkbox_mail_2').checked == true)) {
        document.getElementById('text_mail_2').style.backgroundColor = "#00FF00";
    } else {
        document.getElementById('text_mail_2').style.backgroundColor = "";
    }
    if ((document.getElementById('checkbox_mail_3').checked == true)) {
        document.getElementById('text_mail_3').style.backgroundColor = "#00FF00";
    } else {
        document.getElementById('text_mail_3').style.backgroundColor = "";
    }
    if ((document.getElementById('checkbox_mail_4').checked == true)) {
        document.getElementById('text_mail_4').style.backgroundColor = "#00FF00";
    } else {
        document.getElementById('text_mail_4').style.backgroundColor = "";
    }

    if (document.getElementById('text_mail_1').value == "") {
        document.getElementById('text_mail_1').style.backgroundColor = "";
        document.getElementById('checkbox_mail_1').checked = false;
    }
    if (document.getElementById('text_mail_2').value == "") {
        document.getElementById('text_mail_2').style.backgroundColor = "";
        document.getElementById('checkbox_mail_2').checked = false;
    }
    if (document.getElementById('text_mail_3').value == "") {
        document.getElementById('text_mail_3').style.backgroundColor = "";
        document.getElementById('checkbox_mail_3').checked = false;
    }
    if (document.getElementById('text_mail_4').value == "") {
        document.getElementById('text_mail_4').style.backgroundColor = "";
        document.getElementById('checkbox_mail_4').checked = false;
    }

}

function onCheckMail(numero) {
    switch (numero) {
        case 1:
            if (document.getElementById('text_mail_1').value) {
                if (document.getElementById('checkbox_mail_1').checked) {
                    document.getElementById('text_mail_1').style.backgroundColor = "#00FF00";
                } else {
                    document.getElementById('text_mail_1').style.backgroundColor = "";
                }
            } else {
                alert($('<div />').html("Mail N°1 " + getTextPasEncoreEnregistrer).text());
                document.getElementById('checkbox_mail_1').checked = false;
            }
            break;
        case 2:
            if (document.getElementById('text_mail_2').value) {
                if (document.getElementById('checkbox_mail_2').checked) {
                    document.getElementById('text_mail_2').style.backgroundColor = "#00FF00";
                } else {
                    document.getElementById('text_mail_2').style.backgroundColor = "";
                }
            } else {
                alert($('<div />').html("Mail N°2 " + getTextPasEncoreEnregistrer).text());
                document.getElementById('checkbox_mail_2').checked = false;
            }
            break;
        case 3:
            if (document.getElementById('text_mail_3').value) {
                if (document.getElementById('checkbox_mail_3').checked) {
                    document.getElementById('text_mail_3').style.backgroundColor = "#00FF00";
                } else {
                    document.getElementById('text_mail_3').style.backgroundColor = "";
                }
            } else {
                alert($('<div />').html("Mail N°3 " + getTextPasEncoreEnregistrer).text());
                document.getElementById('checkbox_mail_3').checked = false;
            }
            break;
        case 4:
            if (document.getElementById('text_mail_4').value) {
                if (document.getElementById('checkbox_mail_4').checked) {
                    document.getElementById('text_mail_4').style.backgroundColor = "#00FF00";
                } else {
                    document.getElementById('text_mail_4').style.backgroundColor = "";
                }
            } else {
                alert($('<div />').html("Mail N°4 " + getTextPasEncoreEnregistrer).text());
                document.getElementById('checkbox_mail_4').checked = false;
            }
            break;
    }
}
function changeFormatRapport(format) {
    document.getElementById("tr_affichecarburant").style.display = "";
    document.getElementById("tr_debut_rapport").style.display = "";
    document.getElementById("tr_fin_rapport").style.display = "";
    if (format == "pdf") {
        document.getElementById("formRapportTemps").action = "../rapport/rapportpdftemps.php";
        document.getElementById("rapport_pdf").className = "btn btn-default btn-xs dropdown-toggle active";
        document.getElementById("rapport_excel").className = "btn btn-default btn-xs dropdown-toggle";
        //document.getElementById("rapport_en_ligne").className = "btn btn-default btn-xs dropdown-toggle";
        document.getElementById("checkboxgraf").style.display = "";
    }
    if (format == "excel") {
        document.getElementById("formRapportTemps").action = "../rapport/rapportexceltemps.php";
        document.getElementById("rapport_excel").className = "btn btn-default btn-xs dropdown-toggle active";
        document.getElementById("rapport_pdf").className = "btn btn-default btn-xs dropdown-toggle";
        //document.getElementById("rapport_en_ligne").className = "btn btn-default btn-xs dropdown-toggle";
        document.getElementById("checkboxgraf").style.display = "none";
    }
    //if (format == "html") {
     //   document.getElementById("rapport_en_ligne").className = "btn btn-default btn-xs dropdown-toggle active";
    //    document.getElementById("rapport_pdf").className = "btn btn-default btn-xs dropdown-toggle";
    //    document.getElementById("rapport_excel").className = "btn btn-default btn-xs dropdown-toggle";
    //}
    document.getElementById("etapeCheckbox").checked = false;
    document.getElementById("stopCheckbox").checked = false;
    document.getElementById("graphCheckbox").checked = false;

    document.getElementById("bodyRapportEtape").innerHTML = "";

    document.getElementById("rapport_2_title").style.display = "";
    document.getElementById("rapport_2_content").style.display = "";
    document.getElementById("rapport_3_title").style.display = "";
    document.getElementById("rapport_3_content").style.display = "";

    document.getElementById("genererRapport").innerHTML = "";

}


function retourCarburant() {
    document.getElementById("carburant100km").readOnly = true;
    document.getElementById("selectCarburant").disabled = true;
    //document.getElementById("tr_affichecarburant").style.display = "none";
    document.getElementById("input_retour_carburant").style.display = "none";
    document.getElementById("input_save_carburant").style.display = "none";
    document.getElementById("tr_boutoncarburant").style.display = "";
    document.getElementById("tr_calculkm").style.display = "none";

    showCarburant();
}
function ouvrirCalculPar100Km() {
    var idTracker = document.getElementById("idBalise").innerHTML;
    if (document.getElementById("tr_calculkm").style.display == "none") {
        if (idTracker) {
            if (idTracker.search(/,/) == -1) {


                document.getElementById("tr_affichecarburant").style.display = "";
                document.getElementById("input_retour_carburant").style.display = "";
                document.getElementById("input_save_carburant").style.display = "";
                document.getElementById("tr_calculkm").style.display = "none";
                document.getElementById("tr_boutoncarburant").style.display = "none";


                //if (confirm("Attention, le calcul s'effectue en fonction de l'intervalle de temps choisi")) {
                document.getElementById("tr_calculkm").style.display = "";
                if (rememberModeRapport == "auto") {
                    document.getElementById("div_une_fois").style.display = "";
                    document.getElementById("tr_1").style.display = "none";
                    document.getElementById("tr_2_1").style.display = "none";
                    document.getElementById("tr_2_2").style.display = "none";
                    document.getElementById("tr_3_1").style.display = "none";
                    document.getElementById("tr_3_2").style.display = "none";
                    document.getElementById("tr_4_1").style.display = "none";
                    document.getElementById("tr_4_2").style.display = "none";
                    document.getElementById("tr_5_1").style.display = "none";
                    document.getElementById("tr_5_2").style.display = "none";
                    document.getElementById("tr_6_1").style.display = "none";
                }
                //alert(rememberModeRapport);
                if (rememberModeRapport == "instant") {

                    document.getElementById("tr_debut_rapport").style.display = "";
                    document.getElementById("tr_fin_rapport").style.display = "";
                }
                //document.getElementById("panelbody_rapportinstant").style.height = "190px";

                //}
                //}else{
                //	alert("Veuillez saisir les litres totals de carburants consommés")
                //}
            } else {
                alert(getTextVeuillezChoisirQueUneBalise);
            }
        } else {
            alert(getTextVeuillezChoisirUneBalise);
        }
    } else {
        if (rememberModeRapport == "instant") {
            document.getElementById("tr_debut_rapport").style.display = "none";
            document.getElementById("tr_fin_rapport").style.display = "none";
        }
        document.getElementById("tr_calculkm").style.display = "none";
        //document.getElementById("panelbody_rapportinstant").style.height = "150px";
    }
}
function rapportAutoChangeTracker() {

    document.getElementById("input_new_rapport").className = "btn btn-default btn-xs dropdown-toggle";
    document.getElementById("input_update_rapport").className = "btn btn-default btn-xs dropdown-toggle";
    document.getElementById("tr_1").style.display = "none";
    document.getElementById("tr_2_1").style.display = "none";
    document.getElementById("tr_2_2").style.display = "none";
    document.getElementById("tr_3_1").style.display = "none";
    document.getElementById("tr_3_2").style.display = "none";
    document.getElementById("tr_4_1").style.display = "none";
    document.getElementById("tr_4_2").style.display = "none";
    document.getElementById("tr_5_1").style.display = "none";
    document.getElementById("tr_5_2").style.display = "none";
    document.getElementById("tr_6_1").style.display = "none";
    //document.getElementById("tr_boutoncarburant").style.display = "none";

}
function afficherButtonCarburant() {
    document.getElementById("tr_affichecarburant").style.display = "";
    document.getElementById("tr_calculkm").style.display = "none";
    document.getElementById("tr_boutoncarburant").style.display = "";
    document.getElementById("input_save_carburant").style.display = "none";
    document.getElementById("input_retour_carburant").style.display = "none";
}

function cacherButtonCarburant() {
    document.getElementById("tr_affichecarburant").style.display = "";
    document.getElementById("tr_calculkm").style.display = "none";
    document.getElementById("tr_boutoncarburant").style.display = "none";

}
function buttonModifierCarburant() {
    showCarburant();
    ouvrirCalculPar100Km();
    if (rememberModeRapport == "auto") {
        document.getElementById("input_new_rapport").className = "btn btn-default btn-xs dropdown-toggle";
        document.getElementById("input_update_rapport").className = "btn btn-default btn-xs dropdown-toggle";
        //document.getElementById("div_une_fois").style.display = "";
        document.getElementById("div_hebdomadaire").style.display = "none";
        document.getElementById("div_mensuel").style.display = "none";
        document.getElementById("tr_1").style.display = "none";
        document.getElementById("tr_2_1").style.display = "none";
        document.getElementById("tr_2_2").style.display = "none";
        document.getElementById("tr_3_1").style.display = "none";
        document.getElementById("tr_3_2").style.display = "none";
        document.getElementById("tr_4_1").style.display = "none";
        document.getElementById("tr_4_2").style.display = "none";
        document.getElementById("tr_5_1").style.display = "none";
        document.getElementById("tr_5_2").style.display = "none";
        document.getElementById("tr_6_1").style.display = "none";
        document.getElementById('etapeCheckbox').checked = false;
        document.getElementById('stopCheckbox').checked = false;
        document.getElementById('graphCheckbox').checked = false;
        document.getElementById('checkbox_address').checked = false;

        document.getElementById("du_div_jour").style.display = "none";
        document.getElementById("au_div_jour").style.display = "none";

    }
    document.getElementById("carburant100km").readOnly = false;
    document.getElementById("selectCarburant").disabled = false;


    //
    //document.getElementById("tr_affichecarburant").style.display = "";
    //document.getElementById("tr_calculkm").style.display = "none";
    //document.getElementById("tr_boutoncarburant").style.display = "none";

    //document.getElementById("panelbody_rapportinstant").style.height = "190px";

}
function showCarburant() {
    document.getElementById("tr_calculkm").style.display = "none";
    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    var idTracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;
    document.getElementById("carburant100km").value = "";
    document.getElementById("selectCarburant").value = "";

    document.body.className = "loading";
    if (idTracker != "") {
        if (idTracker.search(/,/) == -1) {

            $.ajax({
                url: '../rapport/rapportshowcarburant.php',
                type: 'GET',
                data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw,
                async: true,
                success: function (response) {
                    if (response) {
                        var TypeCarburant = response.substring(response.indexOf('TypeCarburant') + 14, response.indexOf('LitrePar100Km'));
                        var LitrePar100Km = response.substring(response.indexOf('LitrePar100Km') + 14);
                        //var CO2ParL = response.substring(response.indexOf('CO2ParL')+8);

                        document.getElementById("carburant100km").value = (parseFloat(LitrePar100Km) || 0).toFixed(2);
                        document.getElementById("selectCarburant").value = TypeCarburant;
                        document.body.className = "";
                    } else {
                        document.body.className = "";
                    }


                }
            });
        } else {
            //alert(getTextVeuillezChoisirQueUneBalise);
            document.body.className = "";
            //baliseUnSelectAll();
        }

    } else {
        document.body.className = "";
    }
}
function deleteCarburant() {

    var idTracker = document.getElementById("idBalise").innerHTML;
    var nameTracker = document.getElementById("nomBalise").innerHTML;
    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;
    if (idTracker) {
        if (idTracker.search(/,/) == -1) {
            if (confirm(getTextDeleteCarburant + ": " + nameTracker + " ?")) {
                $.ajax({
                    url: '../rapport/rapportdeletecarburant.php',
                    type: 'GET',
                    data: "idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw,
                    success: function (response) {
                        alert(getTextConfirmDeleteCarburant + ": " + nameTracker);

                    }

                })
            }
        } else {
            alert(getTextVeuillezChoisirQueUneBalise);
        }
    } else {
        alert(getTextVeuillezChoisirUneBalise);
    }
}
function saveCarburant() {
    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;

    var idTracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;
    var carburant = document.getElementById("carburant").value;
    var carburant100km = document.getElementById("carburant100km").value;
    var selectCarburant = document.getElementById("selectCarburant").value;

    if (idTracker) {
        if (idTracker.search(/,/) == -1) {
            if (selectCarburant != "") {
                if (carburant100km != "") {
                    $.ajax({
                        url: '../rapport/rapportsavecarburant.php',
                        type: 'GET',
                        data: "selectCarburant=" + selectCarburant + "&carburant100km=" + carburant100km + "&idTracker=" + idTracker + "&nomBalise=" + nomBalise + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&carburant=" + carburant,
                        success: function (response) {
                            if (response) {
                                alert(response);
                            }
                        }
                    });
                } else {
                    alert(getTextSaisirValeurLitreCarburant100km);
                }
            } else {
                alert(getTextVeuillezSelectTypeCarbu);
            }
        } else {
            alert(getTextVeuillezChoisirQueUneBalise);
        }
    } else {
        alert(getTextVeuillezChoisirUneBalise);
    }
}


function calculpar100Km() {

    var nomDatabaseGpw = globalnomDatabaseGpw;
    var ipDatabaseGpw = globalIpDatabaseGpw;
    var tz = jstz.determine();
    var timezone = tz.name();
    var idTracker = document.getElementById("idBalise").innerHTML;
    var nomBalise = document.getElementById('nomBalise').innerHTML;
    var debutRapport = document.getElementById("debutRapport").value;
    var finRapport = document.getElementById("finRapport").value;
    var carburant = document.getElementById("carburant").value;
    document.body.className = "loading";
    $.ajax({
        url: '../rapport/rapportcalcul100km.php',
        type: 'GET',
        data: "debutRapport=" + debutRapport + "&finRapport=" + finRapport + "&idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&timezone=" + timezone + "&carburant=" + carburant,
        success: function (response) {
            if (response) {
                //alert(response);
                var response100km = response.substring(response.indexOf('litre100km') + 11, response.indexOf('totalekilometre'));
                var responsetotalekm = response.substring(response.indexOf('totalekilometre') + 16);
                document.getElementById("carburant100km").value = (parseFloat(response100km) || 0).toFixed(2);
                if (parseInt(responsetotalekm) <= 0) {
                    alert(getTextPaDeTrajetIntervalle);
                }
                document.body.className = "";
                //document.getElementById("tr_calculkm").style.display = "none";
                //document.getElementById("panelbody_rapportinstant").style.height = "150px";
            }
        }
    });

}
/**********************************************************************************************************/
function rapportTempsOnSubmit() {
    var tz = jstz.determine();
    var timezone = tz.name();
    var carburant = document.getElementById("carburant").value;
    var typeCarburant = document.getElementById("selectCarburant").value;
    var carburant100Km = document.getElementById("carburant100km").value;
    document.getElementById("timezone").value = timezone;
    document.getElementById("nomBaliseRapport").value = document.getElementById('nomBalise').innerHTML;
    document.getElementById("idBaliseRapport").value = document.getElementById("idBalise").innerHTML;
    document.getElementById("ipDatabaseGpwRapport").value = globalIpDatabaseGpw;
    document.getElementById("nomDatabaseGpwRapport").value = globalnomDatabaseGpw;
    document.getElementById("carburantRapport").value = carburant;
    document.getElementById("carburant100KmRapport").value = carburant100Km;
    document.getElementById("typeCarburantRapport").value = typeCarburant;

    //document.getElementById("genererRapport").innerHTML = "";
}
function rapportEtapeOnSubmit() {

    document.getElementById("nomBaliseRapportEtape").value = document.getElementById('nomBalise').innerHTML;
    document.getElementById("idBaliseRapportEtape").value = document.getElementById("idBalise").innerHTML;
    document.getElementById("debutRapportEtape").value = document.getElementById('debutRapport').value;
    document.getElementById("finRapportEtape").value = document.getElementById("finRapport").value;
    // document.getElementById("numeroChoixEtape").value = document.getElementById("numeroEtape").innerHTML;
    //	document.getElementById("genererEtape").innerHTML = "";

}

function enleverBoutonOuvrir1(config) {

    if (document.getElementById("formRapportTemps").action == window.location.protocol + "//" + window.location.host + "/web/src/rapport/rapportexceltemps.php") {
        //if (config == "vitesse") {
        //    alert(getTextAlertExcelPdf);
        //    document.getElementById("graphCheckbox").checked = false;
       // }
       // if (config == "address") {
            // alert(getTextAlertExcelPdf);
       //     document.getElementById("checkbox_address").checked = false;
       // }
       
       // if (document.getElementById("etapeCheckbox").checked == true && document.getElementById("stopCheckbox").checked == true) {
            //alert(getTextAlertExcelMultiple);
        if (config == "etape") 
        {    
            document.getElementById("checkbox_address").checked = false;
            document.getElementById("stopCheckbox").checked = false;
        }
        if (config == "stop") 
        {    
            document.getElementById("etapeCheckbox").checked = false;
            document.getElementById("checkbox_address").checked = false;
        }
        if (config == "address") 
        {    
            document.getElementById("etapeCheckbox").checked = false;
            document.getElementById("stopCheckbox").checked = false;
        }
    }
    document.getElementById("genererRapport").innerHTML = "";
    document.getElementById("bodyRapportEtape").innerHTML = "";
    if (document.getElementById("genererEtape"))
        document.getElementById("genererEtape").innerHTML = "";


}
function enleverBoutonOuvrir2() {
    document.getElementById("genererEtape").innerHTML = "";
}


function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds) {
            break;
        }
    }
}