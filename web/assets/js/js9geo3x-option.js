var mySelectIcone = "";

function selectIcone(id, icone){
	$(id).parent().parent().find('.active').removeClass('active');
	$(id).addClass('active');
	mySelectIcone = icone;

}
function showOptionGroupeBalise(){

	var nomGroupe = document.getElementById('nomGroupe').innerHTML;

	document.getElementById('groupebalise_option').value = nomGroupe;
	
	if ((nomGroupe == getTextAllGoups)||(nomGroupe == "")) {
		document.getElementById('groupebalise_option').disabled = true;
		document.getElementById('button_groupebalise_option').disabled = true;
	}else{
		document.getElementById('groupebalise_option').disabled = false;
		document.getElementById('button_groupebalise_option').disabled = false;
	}
}

function showOptionNomBalise(){
	var nomGroupe = document.getElementById('nomGroupe').innerHTML;
	var Id_Tracker = document.getElementById("idBalise").innerHTML;
	var nomBalise = document.getElementById('nomBalise').innerHTML;

	document.getElementById('groupebalise_option').value = nomGroupe;
	
	if ((Id_Tracker=="") || (Id_Tracker.search(/,/) != -1)){
		
		document.getElementById('nombalise_option').value = "";
		
		if(document.getElementById('numerobalise_option'))
			document.getElementById('numerobalise_option').value = "";
		
		if(document.getElementById('codetrans_option'))
			document.getElementById('codetrans_option').value = "";
		
	}else{
		document.getElementById('nombalise_option').value = nomBalise;
		
		if(document.getElementById('numerobalise_option') || document.getElementById('codetrans_option')){
			var tz = jstz.determine();
			var timezone = tz.name();
			
			$.ajax({
				url: '../etatbalise/etatbalisettracker.php',
				type: 'GET',
				data: "Id_Tracker="+Id_Tracker+"&nomDatabaseGpw="+globalnomDatabaseGpw+"&ipDatabaseGpw="+globalIpDatabaseGpw+"&timezone="+timezone,
				async: false,
				success: function (response) {
					if (response) {
						var chaine=response;
						var reg=new RegExp("[&]+", "g");
						var tableau=chaine.split(reg);
						var numeroAppel = tableau[1].substring(12);
						//console.trace(tableau);
						if(document.getElementById('numerobalise_option'))
							document.getElementById('numerobalise_option').value = numeroAppel;
						
						if(document.getElementById('codetrans_option'))
							document.getElementById('codetrans_option').value = tableau[2].substring(7);
					}
				}
			});
		}
	}
}

function okOptionNomBalise(){
	var nomBaliseNew = document.getElementById('nombalise_option').value;
	var nomBalise = document.getElementById('nomBalise').innerHTML;
	var Id_Tracker =document.getElementById("idBalise").innerHTML;

	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	if (Id_Tracker==""){
		alert(getTextVeuillezChoisirUneBalise);
		return;
	}else if(Id_Tracker.search(/,/) != -1){
		alert(getTextVeuillezChoisirQueUneBalise);
		return;
	}else {
		if($("#nombalise_option").val().replace(/^\s+|\s+$/g, "").length  != 0) {
			if (confirm(getTextConfirmChangerNomBalise + " : " + nomBalise + " -> " + nomBaliseNew)) {
				$.ajax({
					url: '../option/optionoknom.php',
					type: 'GET',
					data: "Id_Tracker=" + Id_Tracker + "&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" + ipDatabaseGpw + "&nomBaliseNew=" + nomBaliseNew,
					success: function (response) {
						if (response) {
							//alert(response);
							document.body.className = "loading";
							document.location.reload(false);

						}
					}
				});
			}
		}else{
			document.getElementById('nombalise_option').value = nomBalise;
		}
	}
}

function okOptionGpw(){
	var nomGroupeNew = document.getElementById('groupebalise_option').value;
	var nomGroupe = document.getElementById('nomGroupe').innerHTML;
	//console.log(nomGroupe);
	if(nomGroupe) {
		if (nomGroupe != getTextAllGoups) {
			//$('#button_groupebalise_option').popover({
			//	trigger: 'focus',
			//	placement: 'left',
			//	content: function() {
			//		var message = getTextConfirmChangerNomGroupe+": " + nomGroupe + " -> " + nomGroupeNew;
			//		return message;
			//	}
			//});
			//$('#button_groupebalise_option').popover("show");

			//document.getElementById("button_groupebalise_option").setAttribute('data-content', );
			if($("#groupebalise_option").val().replace(/^\s+|\s+$/g, "").length  != 0) {
				if (confirm(getTextConfirmChangerNomGroupe+": " + nomGroupe + " -> " + nomGroupeNew)) {
					$.ajax({
						url: '../option/optionokgroupe.php',
						type: 'GET',
						dataType: "text",
						data: "nomGroupe=" + nomGroupe + "&nomGroupeNew=" + nomGroupeNew,
						success: function (response) {
							//console.log('result: '+response);
							if (response && response.length > 0) {
								response=$.parseJSON( response );
								//status = response.status;
								if (response.status == "ok") {
									document.body.className = "loading";
									document.location.reload(false);
								}else{
									alert(option_alert_nomgroupeexistedeja);
								}
								
							}
							//
						}
					});
				}
			}else{
				document.getElementById('groupebalise_option').value = nomGroupe;
			}

		} else {
			alert(getTextVeuillezChoisirUnGroupe);
		}
	} else {
		alert(getTextVeuillezChoisirUnGroupe);
	}
}

function validIcone() {

	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var idTracker = document.getElementById("idBalise").innerHTML;
	var nomBalise = document.getElementById('nomBalise').innerHTML;

	if (mySelectIcone){
		if (idTracker.search(/,/) != -1) {
			var regIdTracker = new RegExp("[,]+", "g");
			var tableauIdTracker = idTracker.split(regIdTracker);
			var regNomBalise = new RegExp("[,]+", "g");
			var tableauNomBalise = nomBalise.split(regNomBalise);
			if (confirm(getTextConfirmWarningMultipleBalises+": \n" + nomBalise)) {
				if (confirm(getTextConfirmEnregistrerIcone+": \n" + nomBalise + " ? ")) {
					for (var i = 0; i < tableauIdTracker.length; i++) {
						$.ajax({
							url: '../option/optionvalidicone.php',
							type: 'GET',
							data: "selectIcone=" + mySelectIcone + "&nomBalise=" + tableauNomBalise[i] + "&idTracker=" + tableauIdTracker[i] + "&nomDatabaseGpw=" + nomDatabaseGpw +
							"&ipDatabaseGpw=" + ipDatabaseGpw,
							async: false
						});
					}
					$('#listIcone').modal('hide');

					//document.body.className = "loading";
					//document.location.reload(false);
				}
			}
		} else if (idTracker != "") {
			if (confirm(getTextConfirmEnregistrerIcone+": " + nomBalise + " ? ")) {
				$.ajax({
					url: '../option/optionvalidicone.php',
					type: 'GET',
					data: "selectIcone=" + mySelectIcone + "&nomBalise=" + nomBalise + "&idTracker=" + idTracker + "&nomDatabaseGpw=" + nomDatabaseGpw +
					"&ipDatabaseGpw=" + ipDatabaseGpw,
					success: function (response) {
						if (response) {
							$('#listIcone').modal('hide');
							//document.body.className = "loading";
							//document.location.reload(false);
						}
						$('#listIcone').modal('hide');
					}

				});
			}
		} else {
			alert(getTextVeuillezChoisirUneBalise);
		}
	}else{
		alert(getTextVeuillezChoisirUneIcone);

	}
}
function listIcone(){

	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var Id_Tracker=	document.getElementById("idBalise").innerHTML;
	var nomBalise=document.getElementById('nomBalise').innerHTML;
	mySelectIcone = "";

	if(Id_Tracker) {
		$.ajax({
			url: '../option/optionlisticone.php',
			async: false,
			success: function (response) {
				if (response) {
					var reg = new RegExp("[&]+", "g");
					var tableau = response.split(reg);
					var nbreLigne = tableau[0].substring(tableau[0].indexOf('t') + 1, tableau[0].indexOf('g'))
					var contenuListIcone = "";
					if (nbreLigne) {
						for (var i = 0; i < nbreLigne; i++) {
							var dateEnvoi = tableau[i].substring(tableau[i].indexOf('g') + 1);
							contenuListIcone += dateEnvoi;
							$('#listIcone').modal('show');
						}
					}
					document.getElementById('listIcone_modal').innerHTML = contenuListIcone;

				}
			}
		});
		document.getElementById('myIcone_modal').innerHTML = "";

		if (Id_Tracker.search(/,/) != -1) {
			var regIdTracker = new RegExp("[,]+", "g");
			var tableauIdTracker = Id_Tracker.split(regIdTracker);
			for (var i = 0; i < tableauIdTracker.length; i++) {

				//var icone = "";
				//if (imageExists('../../assets/img/ICONESDifferencier/' + nomDatabaseGpw + '_' + tableauIdTracker[i] + '.png') == true)
				//	icone = '../../assets/img/ICONESDifferencier/' + nomDatabaseGpw + '_' + tableauIdTracker[i] + '.png';
				//else if (imageExists('web/assets/img/ICONESDifferencier/' + nomDatabaseGpw + '_' + tableauIdTracker[i] + '.ico') == true)
				//	icone = '../../assets/img/ICONESDifferencier/' + nomDatabaseGpw + '_' + tableauIdTracker[i] + '.ico';
				//else
				//	icone = '../../assets/img/BibliothequeIcone/default.png';

				icone = "../../assets/img/BibliothequeIcone/"+getIcone(tableauIdTracker[i]);

				document.getElementById('myIcone_modal').innerHTML += "<img src='" + icone + "'   alt=\"\" />";

			}
		} else {
			var icone = ""
			//document.getElementById('myIcone_modal').innerHTML = "<img src=\"web/assets/img/BibliothequeIcone/default.png\"  alt=\"\" />";
            //
			//if (imageExists('../../assets/img/ICONESDifferencier/' + nomDatabaseGpw + '_' + Id_Tracker + '.png') == true)
			//	icone = '../../assets/img/ICONESDifferencier/' + nomDatabaseGpw + '_' + Id_Tracker + '.png';
			//else if (imageExists('web/assets/img/ICONESDifferencier/' + nomDatabaseGpw + '_' + Id_Tracker + '.ico') == true)
			//	icone = '../../assets/img/ICONESDifferencier/' + nomDatabaseGpw + '_' + Id_Tracker + '.ico';
			//else
			//	icone = '../../assets/img/BibliothequeIcone/default.png';
			icone = "../../assets/img/BibliothequeIcone/"+getIcone(Id_Tracker);

			document.getElementById('myIcone_modal').innerHTML = "<img src='" + icone + "'   alt=\"\" />";


		}
	}else{
		alert(getTextVeuillezChoisirUneBalise);
	}
}

function addPrivacyConfig(){


	var Id_Tracker = document.getElementById("idBalise").innerHTML;
	var nomBalise=document.getElementById('nomBalise').innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;
	if(Id_Tracker) {
		if(Id_Tracker.search(/,/) != -1) {
			var regIdTracker = new RegExp("[,]+", "g");
			var tableauIdTracker=Id_Tracker.split(regIdTracker);
			var regNomBalise = new RegExp("[,]+", "g");
			var tableauNomBalise=nomBalise.split(regNomBalise);
			//if (confirm(getTextWarningGeofMultiple+": \n" + nomBalise)) {
				for (var i=0; i<tableauIdTracker.length; i++) {
					$.ajax({
						url: '../option/optionprivacyconfig.php',
						type: 'GET',
						data: "ipBase=" + ipDatabaseGpw + "&nomBase=" + nomDatabaseGpw + "&idTracker=" + tableauIdTracker[i] + "&nomBalise=" + tableauNomBalise[i],
						success: function (response) {
							if (response) {
								//alert(response);
								//$('#privacy_div').modal('show');
								//document.getElementById("config_privacy_balise").innerHTML = response;
								var NbrPlage = response.substring(response.indexOf('NbrPlage') + 8, response.indexOf('Hd1'));
								var Hd1 = response.substring(response.indexOf('Hd1') + 3, response.indexOf('Hf1'));
								var Hf1 = response.substring(response.indexOf('Hf1') + 3, response.indexOf('Hd2'));
								var Hd2 = response.substring(response.indexOf('Hd2') + 3, response.indexOf('Hf2'));
								var Hf2 = response.substring(response.indexOf('Hf2') + 3, response.indexOf('Lundi'));
								var Lundi = response.substring(response.indexOf('Lundi') + 5, response.indexOf('Mardi'));
								var Mardi = response.substring(response.indexOf('Mardi') + 5, response.indexOf('Mercredi'));
								var Mercredi = response.substring(response.indexOf('Mercredi') + 8, response.indexOf('Jeudi'));
								var Jeudi = response.substring(response.indexOf('Jeudi') + 5, response.indexOf('Vendredi'));
								var Vendredi = response.substring(response.indexOf('Vendredi') + 8, response.indexOf('Samedi'));
								var Samedi = response.substring(response.indexOf('Samedi') + 6, response.indexOf('Dimanche'));
								var Dimanche = response.substring(response.indexOf('Dimanche') + 8);

								if (Lundi == "1") document.getElementById("privacy_lundi").checked = true;
								if (Mardi == "1") document.getElementById("privacy_mardi").checked = true;
								if (Mercredi == "1") document.getElementById("privacy_mercredi").checked = true;
								if (Jeudi == "1") document.getElementById("privacy_jeudi").checked = true;
								if (Vendredi == "1") document.getElementById("privacy_vendredi").checked = true;
								if (Samedi == "1") document.getElementById("privacy_samedi").checked = true;
								if (Dimanche == "1") document.getElementById("privacy_dimanche").checked = true;


								document.getElementById("nom_balise_privacy").innerHTML = nomBalise;
								if(document.getElementById("selectLanguage").value == "en_US"){
									var dd1 = "AM";
									var df1 = "AM";
									var dd2 = "AM";
									var df2 = "AM";

									var d1 = new Date();
									d1.setHours(Hd1[0]+ Hd1[1]);
									d1.setMinutes(Hd1[2]+ Hd1[3]);
									var f1 = new Date();
									f1.setHours(Hf1[0]+ Hf1[1]);
									f1.setMinutes(Hf1[2]+ Hf1[3]);
									var d2 = new Date();
									d2.setHours(Hd2[0]+ Hd2[1]);
									d2.setMinutes(Hd2[2]+ Hd2[3]);
									var f2 = new Date();
									f2.setHours(Hf2[0]+ Hf2[1]);
									f2.setMinutes(Hf2[2]+ Hf2[3]);


									if (d1.getHours() >= 12) {
										d1.setHours(d1.getHours()-12);
										dd1 = "PM";
									}
									if (f1.getHours() >= 12) {
										f1.setHours(f1.getHours()-12);
										df1 = "PM";
									}
									if (d2.getHours() >= 12) {
										d2.setHours(d2.getHours()-12);
										dd2 = "PM";
									}
									if (f2.getHours() >= 12) {
										f2.setHours(f2.getHours()-12);
										df2 = "PM";
									}

									if(d1.getHours() == 0) d1.setHours(d1.getHours()+12);
									if(f1.getHours() == 0) f1.setHours(f1.getHours()+12);
									if(d2.getHours() == 0) d2.setHours(d2.getHours()+12);
									if(f2.getHours() == 0) f2.setHours(f2.getHours()+12);

									document.getElementById("debut1").value = (d1.getHours()<10?'0':'') + d1.getHours()+ ":" + (d1.getMinutes()<10?'0':'') + d1.getMinutes() +" "+ dd1;
									document.getElementById("fin1").value = (f1.getHours()<10?'0':'') + f1.getHours()+ ":" + (f1.getMinutes()<10?'0':'') + f1.getMinutes()+" "+ df1;
									document.getElementById("debut2").value = (d2.getHours()<10?'0':'') + d2.getHours()+ ":" + (d2.getMinutes()<10?'0':'') + d2.getMinutes()+" "+ dd2;
									document.getElementById("fin2").value = (f2.getHours()<10?'0':'') + f2.getHours()+ ":" + (f2.getMinutes()<10?'0':'') + f2.getMinutes()+" "+ df2;

								}else {
									document.getElementById("debut1").value = Hd1[0] + Hd1[1] + ":" + Hd1[2] + Hd1[3];
									document.getElementById("fin1").value = Hf1[0] + Hf1[1] + ":" + Hf1[2] + Hf1[3];
									document.getElementById("debut2").value = Hd2[0] + Hd2[1] + ":" + Hd2[2] + Hd2[3];
									document.getElementById("fin2").value = Hf2[0] + Hf2[1] + ":" + Hf2[2] + Hf2[3];

								}
								document.getElementById("affichage_privacy").style.display = "";


								$('#demo').trigger('change');

								document.body.className = "";
							}
						}
					});
				//}
			}
		}else {
			$.ajax({
				url: '../option/optionprivacyconfig.php',
				type: 'GET',
				data: "ipBase=" + ipDatabaseGpw + "&nomBase=" + nomDatabaseGpw + "&idTracker=" + Id_Tracker + "&nomBalise=" + nomBalise,
				success: function (response) {
					if (response) {
						//alert(response);
						//$('#privacy_div').modal('show');
						//document.getElementById("config_privacy_balise").innerHTML = response;
						var NbrPlage = response.substring(response.indexOf('NbrPlage') + 8, response.indexOf('Hd1'));
						var Hd1 = response.substring(response.indexOf('Hd1') + 3, response.indexOf('Hf1'));
						var Hf1 = response.substring(response.indexOf('Hf1') + 3, response.indexOf('Hd2'));
						var Hd2 = response.substring(response.indexOf('Hd2') + 3, response.indexOf('Hf2'));
						var Hf2 = response.substring(response.indexOf('Hf2') + 3, response.indexOf('Lundi'));
						var Lundi = response.substring(response.indexOf('Lundi') + 5, response.indexOf('Mardi'));
						var Mardi = response.substring(response.indexOf('Mardi') + 5, response.indexOf('Mercredi'));
						var Mercredi = response.substring(response.indexOf('Mercredi') + 8, response.indexOf('Jeudi'));
						var Jeudi = response.substring(response.indexOf('Jeudi') + 5, response.indexOf('Vendredi'));
						var Vendredi = response.substring(response.indexOf('Vendredi') + 8, response.indexOf('Samedi'));
						var Samedi = response.substring(response.indexOf('Samedi') + 6, response.indexOf('Dimanche'));
						var Dimanche = response.substring(response.indexOf('Dimanche') + 8);

						if (Lundi == "1") document.getElementById("privacy_lundi").checked = true;
						if (Mardi == "1") document.getElementById("privacy_mardi").checked = true;
						if (Mercredi == "1") document.getElementById("privacy_mercredi").checked = true;
						if (Jeudi == "1") document.getElementById("privacy_jeudi").checked = true;
						if (Vendredi == "1") document.getElementById("privacy_vendredi").checked = true;
						if (Samedi == "1") document.getElementById("privacy_samedi").checked = true;
						if (Dimanche == "1") document.getElementById("privacy_dimanche").checked = true;

						document.getElementById("nom_balise_privacy").innerHTML = nomBalise;

						if(document.getElementById("selectLanguage").value == "en_US"){
							var dd1 = "AM";
							var df1 = "AM";
							var dd2 = "AM";
							var df2 = "AM";

							var d1 = new Date();
							d1.setHours(Hd1[0]+ Hd1[1]);
							d1.setMinutes(Hd1[2]+ Hd1[3]);
							var f1 = new Date();
							f1.setHours(Hf1[0]+ Hf1[1]);
							f1.setMinutes(Hf1[2]+ Hf1[3]);
							var d2 = new Date();
							d2.setHours(Hd2[0]+ Hd2[1]);
							d2.setMinutes(Hd2[2]+ Hd2[3]);
							var f2 = new Date();
							f2.setHours(Hf2[0]+ Hf2[1]);
							f2.setMinutes(Hf2[2]+ Hf2[3]);


							if (d1.getHours() >= 12) {
								d1.setHours(d1.getHours()-12);
								dd1 = "PM";
							}
							if (f1.getHours() >= 12) {
								f1.setHours(f1.getHours()-12);
								df1 = "PM";
							}
							if (d2.getHours() >= 12) {
								d2.setHours(d2.getHours()-12);
								dd2 = "PM";
							}
							if (f2.getHours() >= 12) {
								f2.setHours(f2.getHours()-12);
								df2 = "PM";
							}

							if(d1.getHours() == 0) d1.setHours(d1.getHours()+12);
							if(f1.getHours() == 0) f1.setHours(f1.getHours()+12);
							if(d2.getHours() == 0) d2.setHours(d2.getHours()+12);
							if(f2.getHours() == 0) f2.setHours(f2.getHours()+12);

							document.getElementById("debut1").value = (d1.getHours()<10?'0':'') + d1.getHours()+ ":" + (d1.getMinutes()<10?'0':'') + d1.getMinutes() +" "+ dd1;
							document.getElementById("fin1").value = (f1.getHours()<10?'0':'') + f1.getHours()+ ":" + (f1.getMinutes()<10?'0':'') + f1.getMinutes()+" "+ df1;
							document.getElementById("debut2").value = (d2.getHours()<10?'0':'') + d2.getHours()+ ":" + (d2.getMinutes()<10?'0':'') + d2.getMinutes()+" "+ dd2;
							document.getElementById("fin2").value = (f2.getHours()<10?'0':'') + f2.getHours()+ ":" + (f2.getMinutes()<10?'0':'') + f2.getMinutes()+" "+ df2;

						}else {
							document.getElementById("debut1").value = Hd1[0] + Hd1[1] + ":" + Hd1[2] + Hd1[3];
							document.getElementById("fin1").value = Hf1[0] + Hf1[1] + ":" + Hf1[2] + Hf1[3];
							document.getElementById("debut2").value = Hd2[0] + Hd2[1] + ":" + Hd2[2] + Hd2[3];
							document.getElementById("fin2").value = Hf2[0] + Hf2[1] + ":" + Hf2[2] + Hf2[3];
						}
						document.getElementById("affichage_privacy").style.display = "";


						$('#demo').trigger('change');

						document.body.className = "";
					}
				}
			});
		}
	}else{
		alert(getTextVeuillezChoisirUneBalise);
	}
}
function onChangeHeurePrivacy(id) {
	var debut1 = document.getElementById("debut1").value;
	var fin1 = document.getElementById("fin1").value;
	var debut2 = document.getElementById("debut2").value;
	var fin2 = document.getElementById("fin2").value;



	if(document.getElementById("selectLanguage").value == "en_US") {
		var d1 = new Date();
		var f1 = new Date();
		var d2 = new Date();
		var f2 = new Date();
		var d1AMPM = debut1[6]+""+debut1[7];
		var f1AMPM = fin1[6]+""+fin1[7];
		var d2AMPM = debut2[6]+""+debut2[7];
		var f2AMPM = fin2[6]+""+fin2[7];

		d1.setHours((parseInt(debut1[0]+debut1[1])));
		f1.setHours((parseInt(fin1[0]+fin1[1])));
		d2.setHours((parseInt(debut2[0]+debut2[1])));
		f2.setHours((parseInt(fin2[0]+fin2[1])));


		if(d1AMPM == "PM" && d1.getHours() != 12) d1.setHours((parseInt(debut1[0]+debut1[1]) + 12));
		if(f1AMPM == "PM" && f1.getHours() != 12) f1.setHours((parseInt(fin1[0]+fin1[1]) + 12));
		if(d2AMPM == "PM" && d2.getHours() != 12) d2.setHours((parseInt(debut2[0]+debut2[1]) + 12));
		if(f2AMPM == "PM" && f2.getHours() != 12) f2.setHours((parseInt(fin2[0]+fin2[1]) + 12));


		if(d1.getHours() == 12 && d1AMPM == "AM") d1.setHours(0);
		if(f1.getHours() == 12 && f1AMPM == "AM") f1.setHours(0);
		if(d2.getHours() == 12 && d2AMPM == "AM") d2.setHours(0);
		if(f2.getHours() == 12 && f2AMPM == "AM") f2.setHours(0);

		if (id == "debut1") {


			if ((d1.getHours() > f1.getHours())) {
				document.getElementById("fin1").value = debut1;
			}
			if ((d1.getHours() > d2.getHours())) {
				document.getElementById("debut2").value = debut1;
			}
			if ((d1.getHours() > f2.getHours())) {
				document.getElementById("fin2").value = debut1;
			}
		}
		if (id == "fin1") {

			if ((f1.getHours() < d1.getHours())) {
				document.getElementById("debut1").value = fin1;
			}
			if ((f1.getHours() > d2.getHours())) {
				document.getElementById("debut2").value = fin1;
			}
			if ((f1.getHours() > f2.getHours())) {
				document.getElementById("fin2").value = fin1;
			}
		}
		if (id == "debut2") {

			if ((d2.getHours() < d1.getHours())) {
				document.getElementById("debut1").value = debut2;
			}
			if ((d2.getHours() < f1.getHours())) {
				document.getElementById("fin1").value = debut2;
			}
			if ((d2.getHours() > f2.getHours())) {
				document.getElementById("fin2").value = debut2;
			}
		}
		if (id == "fin2") {

			if ((f2.getHours() < d1.getHours())) {
				document.getElementById("debut1").value = fin2;
			}
			if ((f2.getHours() < f1.getHours())) {
				document.getElementById("fin1").value = fin2;
			}
			if ((f2.getHours() < d2.getHours())) {
				document.getElementById("debut2").value = fin2;
			}
		}

	}else {
		if (id == "debut1") {
			if (debut1 > fin1) {
				document.getElementById("fin1").value = debut1;
			}
			if (debut1 > debut2) {
				document.getElementById("debut2").value = debut1;
			}
			if (debut1 > fin2) {
				document.getElementById("fin2").value = debut1;
			}
		}
		if (id == "fin1") {
			if (fin1 < debut1) {
				document.getElementById("debut1").value = fin1;
			}
			if (fin1 > debut2) {
				document.getElementById("debut2").value = fin1;
			}
			if (fin1 > fin2) {
				document.getElementById("fin2").value = fin1;
			}
		}
		if (id == "debut2") {
			if (debut2 < debut1) {
				document.getElementById("debut1").value = debut2;
			}
			if (debut2 < fin1) {
				document.getElementById("fin1").value = debut2;
			}
			if (debut2 > fin2) {
				document.getElementById("fin2").value = debut2;
			}
		}
		if (id == "fin2") {
			if (fin2 < debut1) {
				document.getElementById("debut1").value = fin2;
			}
			if (fin2 < fin1) {
				document.getElementById("fin1").value = fin2;
			}
			if (fin2 < debut2) {
				document.getElementById("debut2").value = fin2;
			}
		}
	}

	$('#demo').trigger('change');
}
function updateConfigPrivacy(){


	var Id_Tracker = document.getElementById("idBalise").innerHTML;
	var nomBalise=document.getElementById('nomBalise').innerHTML;
	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var Lundi = "0";
	var Mardi = "0";
	var Mercredi = "0";
	var Jeudi = "0";
	var Vendredi = "0";
	var Samedi = "0";
	var Dimanche = "0";
	if(document.getElementById("privacy_lundi").checked  == true) Lundi = "1";
	if(document.getElementById("privacy_mardi").checked  == true) Mardi = "1";
	if(document.getElementById("privacy_mercredi").checked  == true) Mercredi = "1";
	if(document.getElementById("privacy_jeudi").checked  == true) Jeudi = "1";
	if(document.getElementById("privacy_vendredi").checked  == true) Vendredi = "1";
	if(document.getElementById("privacy_samedi").checked  == true) Samedi = "1";
	if(document.getElementById("privacy_dimanche").checked  == true) Dimanche = "1";

	var debut1 = document.getElementById("debut1").value;
	var fin1 = document.getElementById("fin1").value;
	var debut2 = document.getElementById("debut2").value;
	var fin2 = document.getElementById("fin2").value;


	var d1AMPM = debut1[6]+debut1[7];
	var f1AMPM = fin1[6]+fin1[7];
	var d2AMPM = debut2[6]+debut2[7];
	var f2AMPM = fin2[6]+fin2[7];

	var Hd1 = debut1[0]+debut1[1]+debut1[3]+debut1[4];
	var Hf1 = fin1[0]+fin1[1]+fin1[3]+fin1[4];
	var Hd2 = debut2[0]+debut2[1]+debut2[3]+debut2[4];
	var Hf2 = fin2[0]+fin2[1]+fin2[3]+fin2[4];

	var heureHd1 = debut1[0]+debut1[1];
	var heureHf1 = fin1[0]+fin1[1];
	var heureHd2 = debut2[0]+debut2[1];
	var heureHf2 = fin2[0]+fin2[1];
	if(document.getElementById("selectLanguage").value == "en_US") {
		if (d1AMPM == "PM" && ( heureHd1 != "12")) Hd1 = (parseInt(debut1[0]+debut1[1]) + 12) + "" +debut1[3]+debut1[4];
		if (f1AMPM == "PM" && ( heureHf1 != "12")) Hf1 = (parseInt(fin1[0]+fin1[1]) + 12) + "" +fin1[3]+fin1[4];
		if (d2AMPM == "PM" && ( heureHd2 != "12")) Hd2 = (parseInt(debut2[0]+debut2[1]) + 12) + "" +debut2[3]+debut2[4];
		if (f2AMPM == "PM" && ( heureHf2 != "12")) Hf2 = (parseInt(fin2[0]+fin2[1]) + 12) + "" +fin2[3]+fin2[4];


		if (d1AMPM == "AM" && ( heureHd1 == "12")) Hd1 = ((parseInt(debut1[0]+debut1[1]) - 12)<10?'0':'') + (parseInt(debut1[0]+debut1[1]) - 12) + "" +debut1[3]+debut1[4];
		if (f1AMPM == "AM" && ( heureHf1 == "12")) Hf1 =  ((parseInt(fin1[0]+fin1[1]) - 12)<10?'0':'') + (parseInt(fin1[0]+fin1[1]) - 12) + "" +fin1[3]+fin1[4];
		if (d2AMPM == "AM" && ( heureHd2 == "12")) Hd2 =  ((parseInt(debut2[0]+debut2[1]) - 12)<10?'0':'') + (parseInt(debut2[0]+debut2[1]) - 12) + "" +debut2[3]+debut2[4];
		if (f2AMPM == "AM" && ( heureHf2 == "12")) Hf2 =  ((parseInt(fin2[0]+fin2[1]) - 12)<10?'0':'') + (parseInt(fin2[0]+fin2[1]) - 12) + "" +fin2[3]+fin2[4];


	}

	if(Id_Tracker) {
		if(Id_Tracker.search(/,/) != -1) {

			var regIdTracker = new RegExp("[,]+", "g");
			var tableauIdTracker=Id_Tracker.split(regIdTracker);
			var regNomBalise = new RegExp("[,]+", "g");
			var tableauNomBalise=nomBalise.split(regNomBalise);

			if (confirm(getTextWarningGeofMultiple+": \n" + nomBalise)) {
				for (var i=0; i<tableauIdTracker.length; i++) {
					$.ajax({
						url: '../option/optionprivacyupdateconfig.php',
						type: 'GET',
						data: "ipBase=" + ipDatabaseGpw + "&nomBase=" + nomDatabaseGpw + "&idTracker=" + tableauIdTracker[i] + "&nomBalise=" + tableauNomBalise[i] +
						"&Lundi=" + Lundi + "&Mardi=" + Mardi + "&Mercredi=" + Mercredi + "&Jeudi=" + Jeudi + "&Vendredi=" + Vendredi + "&Samedi=" + Samedi + "&Dimanche=" + Dimanche +
						"&Hd1=" + Hd1 + "&Hf1=" + Hf1 + "&Hd2=" + Hd2 + "&Hf2=" + Hf2,
						success: function (response) {
							if (response) {


								document.body.className = "";
							}
						}
					});
				}
			}
		}else{
			 $.ajax({
				 url: '../option/optionprivacyupdateconfig.php',
				 type: 'GET',
				 data: "ipBase=" + ipDatabaseGpw + "&nomBase=" + nomDatabaseGpw + "&idTracker=" + Id_Tracker + "&nomBalise=" + nomBalise +
				 "&Lundi=" + Lundi + "&Mardi=" + Mardi + "&Mercredi=" + Mercredi + "&Jeudi=" + Jeudi + "&Vendredi=" + Vendredi + "&Samedi=" + Samedi + "&Dimanche=" + Dimanche +
				 "&Hd1=" + Hd1 + "&Hf1=" + Hf1 + "&Hd2=" + Hd2 + "&Hf2=" + Hf2,
				 success: function (response) {
					 if (response) {
					 	if(response == "ok") alert(getTextConfidenceOk);
						else alert(response);

					 	document.body.className = "";
					 }
				 }
			 });
		 }
	}else{
		alert(getTextVeuillezChoisirUneBalise);
	}
}
/****************************************************************************************************/
//function showManageGroup() {
//
//	var idClient = document.getElementById('select_choix_client').value;
//	var nomBase =  globalnomDatabaseGpw;
//
//	idGPWGroupe = "" ;
//	nomGPWGroupe = "";
//
//	if(idClient != "-1" && idClient != "all") {
//
//		$.ajax({
//			url: 'optionlistgroupe.php',
//			type: 'GET',
//			data: "idClient="+idClient+"&nomBase="+nomBase,
//			success: function (response) {
//				if (response) {
//					document.getElementById('TableGroupe').innerHTML = response;
//					$('#gerer_groupe').modal('show');
//				}
//			}
//		});
//
//	}else{
//		alert("Veuillez selectionner un client precis");
//	}
//}
//function showManageAccount() {
//	loginCompte = "";
//	dureeCompte = "";
//	finValidCompte = "";
//	nomCompte = "";
//	prenomCompte = "";
//	var idClient = document.getElementById('select_choix_client').value;
//	var nomBase =  globalnomDatabaseGpw;
//
//	if(idClient != "-1" && idClient != "all") {
//
//		$.ajax({
//			url: 'optionlistcompte.php',
//			type: 'GET',
//			data: "idClient="+idClient,
//			success: function (response) {
//				if (response) {
//					document.getElementById('TableCompte').innerHTML = response;
//					$('#gerer_compte').modal('show');
//				}
//			}
//		});
//
//	}else{
//		alert("Veuillez selectionner un client precis");
//	}
//}
function showManage(idClientReel) {

	loginCompte = "";
	dureeCompte = "";
	finValidCompte = "";
	nomCompte = "";
	prenomCompte = "";

	idGPWGroupe = "";
	nomGPWGroupe = "";

	var idClient;
	var nomBase = globalnomDatabaseGpw;
	var idBase = globalIdDatabaseGpw;

	if (idClientReel) {
		idClient = idClientReel;
	}else{
		idClient = document.getElementById('select_choix_client').value;
	}



	if(idClient != "-1" && idClient != "all") {

		$.ajax({
			url: '../option/optionlistcompte.php',
			type: 'GET',
			data: "idClient="+idClient+"&idBase="+idBase,
			success: function (response) {
				if (response) {
					document.getElementById('TableCompte').innerHTML = response;
				}
			}
		});

		$.ajax({
			url: '../option/optionlistgroupe.php',
			type: 'GET',
			data: "idClient="+idClient+"&nomBase="+nomBase,
			success: function (response) {
				if (response) {
					document.getElementById('TableGroupe').innerHTML = response;
					$('#gerer').modal('show');
				}
			}
		});

	}else{
		alert(getTextVeuillezChoisirUnClientPrecis);
	}
}
/****************************************************************************************************/

/****************************************************************************************************/
function createGPW(idClientReel){
	var nomGroupe = document.getElementById('nom_groupe').value;


	if(nomGroupe != "") {


		var idClient = "";
		if (idClientReel) {
			idClient = idClientReel;
		}else{
			idClient = document.getElementById('select_choix_client').value;
		}
		var nomBase =  globalnomDatabaseGpw;

		var arrayId = [];
		var arrayNom = [];
		var table = document.getElementById('destinationtable');
		for (var r = 1, n = table.rows.length; r < n; r++) {
			for (var c = 0, m = table.rows[r].cells.length; c < m; c++) {
				if (c % 2) arrayNom.push(table.rows[r].cells[c].innerHTML);
				else   arrayId.push(table.rows[r].cells[c].innerHTML);
			}
		}

		if(arrayNom.length > 0){
			
			// Ajout du groupe
			$.ajax({
				url: '../option/optioninsertgroupe.php',
				type: 'GET',
				async: false, // Mode synchrone, sert a attendre la fin de l'execution de l'AJAX avant l'ajout des balises au groupe
				data: "idClient=" + idClient + "&nomBase=" + nomBase + "&nomGroupe=" + nomGroupe,
				success: function (response) {
					if (response) {
						alert( response.substr(2) );
						if(response[0] == '0'){
							nomGroupe="";
						}
					}
				}
			});
			
			// Ajout des balises au groupe
			if(nomGroupe != ""){
				for( var i = 0 ; i < arrayNom.length ; i++){
					$.ajax({
						url: '../option/optioninsertgroupebalise.php',
						type: 'GET',
						async: false, // Mode synchrone, sert a attendre la fin de l'execution de l'AJAX avant l'ajout des balises suivantes au groupe
						data: "idClient=" + idClient + "&nomBase=" + nomBase + "&nomGroupe=" + nomGroupe + "&idBalise=" + arrayId[i] + "&nomBalise=" + arrayNom[i],
						success: function (response) {
							if (response) {
								alert(response);
							}
						}
					});
				}
			}
			
			$('#fiche_groupe').modal('hide');
			showManage(idClientReel);
		}else{
			alert(getTextAucuneBaliseAjoutee+"\n"+getTextVeuillezAjouterAuMoinsUneBalise);
		}

	}else{
		alert(getTextVeuillezSaisirNomGroupe);
	}
}

function modifyGPW(idClientReel){
	var nomGroupe = document.getElementById('nom_groupe').value;

	if(nomGroupe != "") {

		var idClient = "";
		if (idClientReel) {
			idClient = idClientReel;
		}else{
			idClient = document.getElementById('select_choix_client').value;
		}
		var nomBase =  globalnomDatabaseGpw;

		var arrayId = [];
		var arrayNom = [];
		var table = document.getElementById('destinationtable');
		for (var r = 1, n = table.rows.length; r < n; r++) {
			for (var c = 0, m = table.rows[r].cells.length; c < m; c++) {
				if (c % 2) arrayNom.push(table.rows[r].cells[c].innerHTML);
				else   arrayId.push(table.rows[r].cells[c].innerHTML);
			}
		}
		deleteGPWBalise();
		if(arrayNom.length > 0){

			for( var i = 0 ; i < arrayNom.length ; i++){
				$.ajax({
					url: '../option/optioninsertgroupebalise.php',
					type: 'GET',
					data: "idClient=" + idClient + "&nomBase=" + nomBase + "&nomGroupe=" + nomGroupe + "&idBalise=" + arrayId[i] + "&nomBalise=" + arrayNom[i],
					success: function (response) {
						if (response) {

						}
					}
				});
			}
			alert(nomGPWGroupe+" "+getTextAlertModifier);
			$('#fiche_groupe').modal('hide');
			//showManageGroup();
		}else{
			alert(getTextAucuneBaliseAjoutee+"\n"+getTextVeuillezAjouterAuMoinsUneBalise);
		}

	}else{
		alert(getTextVeuillezSaisirNomGroupe);
	}
}

function deleteGPWBalise(){
	var nomGroupe = document.getElementById('nom_groupe').value;

	$.ajax({
		url: '../option/optiondeletegroupebalise.php',
		type: 'GET',
		data: "nomGroupe=" + nomGroupe,
		async: false
	});


}
function deleteGPW(idClientReel){
	var idClient = "";
	if (idClientReel) {
		idClient = idClientReel;
	}else{
		idClient = document.getElementById('select_choix_client').value;
	}
	var nomBase =  globalnomDatabaseGpw;

	var idGPW = idGPWGroupe;
	var nomGPW = nomGPWGroupe;
	if(idGPW != "") {

		if (idClient != "-1" && idClient != "all") {
			if (confirm(getTextConfirmSupprimerGroupe+": "+ nomGPW +" ? ")) {
				
				$.ajax({
					url: '../option/optiondeletegroupe.php',
					type: 'GET',
					async: false, // Mode synchrone, sert a attendre la fin de l'execution de l'AJAX avant de lancer la fonction showManage(idClientReel) pour réafficher le panneau Gérer
					data: "idClient=" + idClient + "&nomBase=" + nomBase + "&idGPW=" + idGPW + "&nomGPW=" + nomGPW,
					success: function (response) {
						alert(nomGPW + " " + getTextAlertSupprimer);
						//$('#gerer').modal('show');
					}
				});
				
				showManage(idClientReel);
			}
		} else {
			alert(getTextVeuillezChoisirUnClientPrecis);
		}
	}else{
		alert(getTextVeuillezChoisirUnGroupe);
	}
}


function createAccount(idClientReel){
	var compteLogin = document.getElementById('compte_login').value;
	var compteMdp = document.getElementById('compte_mdp').value;
	var compteNom = document.getElementById('compte_nom').value;
	var comptePrenom = document.getElementById('compte_prenom').value;
	//var compteConfig = document.getElementById('compte_config').value;
	var compteConfig = "aucun";
	var compteDuree = document.getElementById('compte_duree').value;
	var compteMail = document.getElementById('compte_mail').value;


	var saisieMdp = document.getElementById('saisieMdp').checked;
	var compteAdmin = document.getElementById('compte_admin').checked;

	var compteType = "";

	if (document.getElementById("type_heure").checked == true) compteType = "1";
	if (document.getElementById("type_jour").checked == true) compteType = "2";
	if (document.getElementById("type_semaine").checked == true) compteType = "3";
	if (document.getElementById("type_mois").checked == true) compteType = "4";
	if (document.getElementById("type_illimite").checked == true) compteType = "0";

	if(document.getElementById("rien") !== null)
		if (document.getElementById("rien").checked == true) compteConfig = "WEB_UTILISATEUR";
	if(document.getElementById("alarmes")  !== null)
		if (document.getElementById("alarmes").checked == true) compteConfig = "WEB_UTILISATEUR";
	if(document.getElementById("tout")  !== null)
		if (document.getElementById("tout").checked == true) compteConfig = "WEB_UTILISATEUR";

	if(document.getElementById("option_droit_option")  !== null)
		if (document.getElementById("option_droit_option").checked == true) compteConfig += "_NI";

	if(document.getElementById("alarmes")  !== null)
		if (document.getElementById("alarmes").checked == true) compteConfig += "_ALARMES";
	if(document.getElementById("tout")  !== null)
		if (document.getElementById("tout").checked == true) compteConfig += "_AVANCE";

	var nomDatabaseGpw = globalnomDatabaseGpw;
	var ipDatabaseGpw = globalIpDatabaseGpw;

	var date = new Date();
	var notreDate 	= 	date.getFullYear() + "-" + (((date.getMonth()+1) < 10)?"0":"") + (date.getMonth()+1)+ "-" + ((date.getDate() < 10)?"0":"") + date.getDate() + " "
			+ 	((date.getHours() < 10)?"0":"") + date.getHours() + ":" + ((date.getMinutes() < 10)?"0":"") +  date.getMinutes() + ":" + ((date.getSeconds() < 10)?"0":"") + date.getSeconds();


	if(compteLogin != "") {
		if(compteMdp != "" || saisieMdp == true) {
			if(compteConfig != "aucun") {
				if(compteType != "") {
					if(compteDuree != "aucun" || compteType == "0") {
						var arrayId = [];
						var arrayNom = [];
						var table = document.getElementById('cdestinationtable');
						for (var r = 1, n = table.rows.length; r < n; r++) {
							for (var c = 0, m = table.rows[r].cells.length; c < m; c++) {
								if (c % 2) arrayNom.push(table.rows[r].cells[c].innerHTML);
								else   arrayId.push(table.rows[r].cells[c].innerHTML);
							}
						}

						if (arrayNom.length > 0) {

							var idClient = "";
							if (idClientReel) {
								idClient = idClientReel;
							}else{
								idClient = document.getElementById('select_choix_client').value;
							}
							var nomBase = globalnomDatabaseGpw;
							var checkedSaisieMdp = "";
							var checkedAdmin = "";
							if(saisieMdp == true) checkedSaisieMdp = "1";
							else checkedSaisieMdp = "0";
							if(compteAdmin == true) checkedAdmin = "2";
							else checkedAdmin = "0";



							$.ajax({
								url: '../option/optioninsertcompte.php',
								type: 'GET',
								async: false, // Mode synchrone, sert a attendre la fin de l'execution de l'AJAX avant de lancer la fonction showManage(idClientReel) pour réafficher le panneau Gérer
								data: "idClient=" + idClient + "&nomBase=" + nomBase + "&compteLogin=" + compteLogin +
								"&compteMdp=" + compteMdp + "&compteNom=" + compteNom +  "&comptePrenom=" + comptePrenom +
								"&compteConfig=" + compteConfig + "&compteDuree=" + compteDuree + "&compteMail=" + compteMail +
								"&checkedSaisieMdp=" + checkedSaisieMdp + "&checkedAdmin=" + checkedAdmin + "&compteType=" + compteType,
								success: function (response) {
									if(response){
										alert(response);
										$('#fiche_compte').modal('hide');
										
										for (var i = 0; i < arrayNom.length; i++) {
											$.ajax({
												url: '../option/optioninsertcomptebalise.php',
												type: 'GET',
												data: "idClient=" + idClient + "&nomBase=" + nomBase + "&compteLogin=" + compteLogin +
												"&checkedAdmin=" + checkedAdmin + "&idBalise=" + arrayId[i] + "&nomBalise=" + arrayNom[i]
											});
											if(compteMail != "") {
												$.ajax({
													url: '../option/optionenvoiemail.php',
													type: 'GET',
													data: "datetime=" + notreDate +"&nomDatabaseGpw=" + nomDatabaseGpw + "&ipDatabaseGpw=" +ipDatabaseGpw+ "&compteLogin=" + compteLogin + "&compteMdp=" + compteMdp + "&compteDuree="
													+ compteDuree + "&compteMail=" + compteMail + "&checkedSaisieMdp=" + checkedSaisieMdp
													+ "&compteType=" + compteType,
													success: function (response) {
														if (response) {
															alert(response);
														}
													}
												});
											}
										}
										
									}else{
										alert(getTextAlertNomExist);
									}
								}
							});
							showManage(idClientReel);
						} else {
							alert(getTextVeuillezAjouterAuMoinsUnGroupe)
						}
					}else{
						alert(getTextVeuillezAjouterAuMoinsUneDuree)
					}
				}else{
					alert(getTextVeuillezSelectionnerUneDuree);
				}
			}else{
				alert(getTextVeuillezSaisirUneConfig);
			}
		}else{
			alert(getTextVeuillezSaisirUnMdp);
		}
	}else{
		alert(getTextVeuillezSaisirUnLogin);
	}
}

function modifyAccount(idClientReel){
	var compteLogin = document.getElementById('compte_login').value;
	var compteMdp = document.getElementById('compte_mdp').value;
	var compteNom = document.getElementById('compte_nom').value;
	var comptePrenom = document.getElementById('compte_prenom').value;
	//var compteConfig = document.getElementById('compte_config').value;
	var compteConfig = "aucun";
	var compteDuree = document.getElementById('compte_duree').value;
	var compteMail = document.getElementById('compte_mail').value;


	var saisieMdp = document.getElementById('saisieMdp').checked;
	var compteAdmin = document.getElementById('compte_admin').checked;

	var compteType = "";

	if (document.getElementById("type_heure").checked == true) compteType = "1";
	if (document.getElementById("type_jour").checked == true) compteType = "2";
	if (document.getElementById("type_semaine").checked == true) compteType = "3";
	if (document.getElementById("type_mois").checked == true) compteType = "4";
	if (document.getElementById("type_illimite").checked == true) compteType = "0";

	if(document.getElementById("rien") !== null)
		if (document.getElementById("rien").checked == true) compteConfig = "WEB_UTILISATEUR";
	if(document.getElementById("alarmes")  !== null)
		if (document.getElementById("alarmes").checked == true) compteConfig = "WEB_UTILISATEUR";
	if(document.getElementById("tout")  !== null)
		if (document.getElementById("tout").checked == true) compteConfig = "WEB_UTILISATEUR";

	if(document.getElementById("option_droit_option")  !== null)
		if (document.getElementById("option_droit_option").checked == true) compteConfig += "_NI";

	if(document.getElementById("alarmes")  !== null)
		if (document.getElementById("alarmes").checked == true) compteConfig += "_ALARMES";
	if(document.getElementById("tout")  !== null)
		if (document.getElementById("tout").checked == true) compteConfig += "_AVANCE";

	if(compteLogin != "") {
		if(compteMdp != "") {

				if(compteType != "") {
					if(compteDuree != "aucun" || compteType == "0") {
						var arrayId = [];
						var arrayNom = [];
						var table = document.getElementById('cdestinationtable');
						for (var r = 1, n = table.rows.length; r < n; r++) {
							for (var c = 0, m = table.rows[r].cells.length; c < m; c++) {
								if (c % 2) arrayNom.push(table.rows[r].cells[c].innerHTML);
								else   arrayId.push(table.rows[r].cells[c].innerHTML);
							}
						}

						if (arrayNom.length > 0) {

							var idClient = "";
							if (idClientReel) {
								idClient = idClientReel;
							}else{
								idClient = document.getElementById('select_choix_client').value;
							}
							var nomBase = globalnomDatabaseGpw;
							var checkedSaisieMdp = "";
							var checkedAdmin = "";
							if(saisieMdp == true) checkedSaisieMdp = "1";
							else checkedSaisieMdp = "0";
							if(compteAdmin == true) checkedAdmin = "2";
							else checkedAdmin = "0";

							deleteAccountBalise(idClient);
							if(compteConfig == "aucun") alert("La configuration web n'a pas été changé");
							$.ajax({
								url: '../option/optionupdatecompte.php',
								type: 'GET',
								async: false, // Mode synchrone, sert a attendre la fin de l'execution de l'AJAX avant de lancer la fonction showManage(idClientReel) pour réafficher le panneau Gérer
								data: "idClient=" + idClient + "&nomBase=" + nomBase + "&compteLogin=" + compteLogin +
								"&compteMdp=" + compteMdp + "&compteNom=" + compteNom +  "&comptePrenom=" + comptePrenom +
								"&compteConfig=" + compteConfig + "&compteDuree=" + compteDuree + "&compteMail=" + compteMail +
								"&checkedSaisieMdp=" + checkedSaisieMdp + "&checkedAdmin=" + checkedAdmin + "&compteType=" + compteType,
								success: function (response) {
									if (response) {
										alert(response);
										$('#fiche_compte').modal('hide');
										for (var i = 0; i < arrayNom.length; i++) {
											$.ajax({
												url: '../option/optioninsertcomptebalise.php',
												type: 'GET',
												data: "idClient=" + idClient + "&nomBase=" + nomBase + "&compteLogin=" + compteLogin +
												"&checkedAdmin=" + checkedAdmin + "&idBalise=" + arrayId[i] + "&nomBalise=" + arrayNom[i]
											});
										}
									}
								}
							});
							
							showManage(idClientReel);
						} else {
							alert(getTextVeuillezAjouterAuMoinsUnGroupe)
						}
					}else{
						alert(getTextVeuillezAjouterAuMoinsUneDuree)
					}
				}else{
					alert(getTextVeuillezSelectionnerUneDuree);
				}
		}else{
			alert(getTextVeuillezSaisirUnMdp);
		}
	}else{
		alert(getTextVeuillezSaisirUnLogin);
	}
}

function deleteAccountBalise(idClientReel){
	var idClient = "";
	if (idClientReel) {
		idClient = idClientReel;
	}else{
		idClient = document.getElementById('select_choix_client').value;
	}
	var compteLogin = document.getElementById('compte_login').value;
	var login = compteLogin;
	$.ajax({
		url: '../option/optiondeletecomptebalise.php',
		type: 'GET',
		data: "idClient=" + idClient + "&login=" + login ,
		async: false
	});


}
function deleteAccount(idClientReel){
	var idClient = "";
	if (idClientReel) {
		idClient = idClientReel;
	}else{
		idClient = document.getElementById('select_choix_client').value;
	}

	var login = loginCompte;
    if(username != login) {
        if (login != "") {
            if (idClient != "-1" && idClient != "all") {
                if (confirm(getTextConfirmSupprimerCompte + ": " + login + " ? ")) {
                    $.ajax({
                        url: '../option/optiondeletecompte.php',
                        type: 'GET',
						async: false, // Mode synchrone, sert a attendre la fin de l'execution de l'AJAX avant de lancer la fonction showManage(idClientReel) pour réafficher le panneau Gérer
                        data: "idClient=" + idClient + "&login=" + login,
                        success: function (response) {
                            alert(login + " " + getTextAlertSupprimer);
                            //$('#gerer').modal('show');
                        }
                    });
					showManage(idClientReel);
                }
            } else {
                alert(getTextVeuillezChoisirUnClientPrecis);
            }
        } else {
            alert(getTextVeuillezSelectionnerUnCompte);
        }
    }
}


/****************************************************************************************************/
function showCreateGroup(idClientReel){

	var idClient = "";
	if (idClientReel) {
		idClient = idClientReel;
	}else{
		idClient = document.getElementById('select_choix_client').value;
	}
	var nomBase =  globalnomDatabaseGpw;

	if(idClient != "-1" && idClient != "all") {
		//$('#gerer_groupe').modal('show');
		$.ajax({
			url: '../option/optionfichegroupe.php',
			type: 'GET',
			data: "idClient="+idClient+"&nomBase="+nomBase,
			success: function (response) {
				if (response) {
					document.getElementById('create_groupe_content').innerHTML = response;
					$('#fiche_groupe').modal('show');
				}
			}
		});

	}else{
		alert(getTextVeuillezChoisirUnClientPrecis);
	}
}

function showModifyGroup(idClientReel){


	var idClient = "";
	if (idClientReel) {
		idClient = idClientReel;
	}else{
		idClient = document.getElementById('select_choix_client').value;
	}
	var nomBase =  globalnomDatabaseGpw;

	var idGPW = idGPWGroupe;
	var nomGPW = nomGPWGroupe;
	if(idGPW != "") {

		if (idClient != "-1" && idClient != "all") {
			$.ajax({
				url: '../option/optionfichegroupe.php',
				type: 'GET',
				data: "idClient=" + idClient + "&nomBase=" + nomBase + "&idGPW=" + idGPW + "&nomGPW=" + nomGPW,
				success: function (response) {
					if (response) {
						document.getElementById('create_groupe_content').innerHTML = response;
						$('#fiche_groupe').modal('show');
					}
				}
			});

		} else {
			alert(getTextVeuillezChoisirUnClientPrecis);
		}
	}else{
		alert(getTextVeuillezChoisirUnGroupe)
	}
}
function showCreateAccount(idClientReel){
	//$('#gerer_compte').modal('hide');

	var idClient;
	if (idClientReel) {
		idClient = idClientReel;
	}else{
		idClient = document.getElementById('select_choix_client').value;
	}

	var nomBase =  globalnomDatabaseGpw;
	var idBase =  globalIdDatabaseGpw;

	var login = "";
	var nom = "";
	var prenom = "";

	$.ajax({
		url: '../option/optionfichecompte.php',
		type: 'GET',
		data: "idClient="+idClient+"&nomBase="+nomBase+"&idBase="+idBase+"&login="+login+"&nom="+nom+"&prenom="+prenom,
		success: function (response) {
			if (response) {
				document.getElementById('fiche_compte_modal').innerHTML = response;
				$('#fiche_compte').modal('show');
			}
		}
	});

}
function showModifyAccount(idClientReel){


	var login = loginCompte;
	var duree = dureeCompte;
	var finValid = finValidCompte;
	var nom = nomCompte;
	var prenom = prenomCompte;

	//$('#gerer_compte').modal('hide');

	if(login != "") {
		var idClient = "";
		if (idClientReel) {
			idClient = idClientReel;
		}else{
			idClient = document.getElementById('select_choix_client').value;
		}
		var nomBase = globalnomDatabaseGpw;
		var idBase = globalIdDatabaseGpw;
		$.ajax({
			url: '../option/optionfichecompte.php',
			type: 'GET',
			data: "idClient=" + idClient + "&nomBase=" + nomBase + "&idBase=" + idBase + "&login=" + login + "&nom=" + nom + "&prenom=" + prenom,
			success: function (response) {
				if (response) {
					document.getElementById('fiche_compte_modal').innerHTML = response;
					if (duree == "0" || duree == "") {
						document.getElementById('compte_duree').style.display = "none";
						document.getElementById("type_heure").checked = false;
						document.getElementById("type_jour").checked = false;
						document.getElementById("type_semaine").checked = false;
						document.getElementById("type_mois").checked = false;
						document.getElementById("type_illimite").checked = true;
					} else {
						document.getElementById('compte_duree').style.display = "";
						document.getElementById('compte_duree').value = duree;

						if (finValid == "1") {
							document.getElementById("type_heure").checked = true;
							document.getElementById("type_jour").checked = false;
							document.getElementById("type_semaine").checked = false;
							document.getElementById("type_mois").checked = false;
							document.getElementById("type_illimite").checked = false;
						}
						if (finValid == "2") {
							document.getElementById("type_heure").checked = false;
							document.getElementById("type_jour").checked = true;
							document.getElementById("type_semaine").checked = false;
							document.getElementById("type_mois").checked = false;
							document.getElementById("type_illimite").checked = false;
						}
						if (finValid == "3") {
							document.getElementById("type_heure").checked = false;
							document.getElementById("type_jour").checked = false;
							document.getElementById("type_semaine").checked = true;
							document.getElementById("type_mois").checked = false;
							document.getElementById("type_illimite").checked = false;
						}
						if (finValid == "4") {
							document.getElementById("type_heure").checked = false;
							document.getElementById("type_jour").checked = false;
							document.getElementById("type_semaine").checked = false;
							document.getElementById("type_mois").checked = true;
							document.getElementById("type_illimite").checked = false;
						}


					}
					$.ajax({
						url: '../option/optiongetconfigweb.php',
						type: 'GET',
						data: "login=" + login,
						success: function (response2) {
							if (response2) {
								var chaine=response2;
								var reg=new RegExp("[&]+", "g");
								var tableau=chaine.split(reg);


								var accountConfig =  tableau[0].substring(tableau[0].indexOf('Account:')+8,tableau[0].indexOf('Target:'));
								var targetConfig = tableau[0].substring(tableau[0].indexOf('Target:')+7);


								if( targetConfig.indexOf('_NI') >= 0 || (targetConfig.indexOf('GESTIONNAIRE')  >= 0)){
									if(document.getElementById("option_droit_option") !== null)  document.getElementById("option_droit_option").checked = true;
								}

								if( (targetConfig.indexOf('AVANCE')  >= 0) || (targetConfig.indexOf('GESTIONNAIRE')  >= 0) )
									if(document.getElementById("tout") !== null) document.getElementById("tout").checked = true;
								if( targetConfig.indexOf('ALARMES') >= 0)
									if(document.getElementById("alarmes") !== null) document.getElementById("alarmes").checked = true;
								if( (targetConfig.indexOf('WEB_UTILISATEUR') >= 0) && (targetConfig.indexOf('AVANCE') < 0) && (targetConfig.indexOf('ALARMES') < 0) )
									if(document.getElementById("rien") !== null) document.getElementById("rien").checked = true;

								$('#fiche_compte').modal('show');
							}

						}
					});
					//$('#fiche_compte').modal('show');
				}
			}

		});

	}else{
		alert(getTextVeuillezSelectionnerUnCompte);
	}
}
/****************************************************************************************************/
function openDuree(){
	document.getElementById('compte_duree').style.display = "";
	typeDureeCompte();
}
function closeDuree(){
	document.getElementById('compte_duree').style.display = "none";
}
/****************************************************************************************************/

var loginCompte;
var dureeCompte;
var finValidCompte;
var nomCompte;
var prenomCompte;

function clickTableCompte(id) {
	this.id = id;
	$('tr').children('td').removeClass('active');
	$(this.id).children('td').addClass('active');

	var arrayColonnes = this.id.cells;

	loginCompte = arrayColonnes[0].innerHTML;
	dureeCompte = arrayColonnes[5].innerHTML;
	finValidCompte = arrayColonnes[6].innerHTML;
	nomCompte = arrayColonnes[3].innerHTML;
	prenomCompte =arrayColonnes[4].innerHTML;
}

var idGPWGroupe;
var nomGPWGroupe;
function clickTableGroupe(id) {
	this.id = id;
	$('tr').children('td').removeClass('active');
	$(this.id).children('td').addClass('active');

	var arrayColonnes = this.id.cells;

	idGPWGroupe = arrayColonnes[0].innerHTML;
	nomGPWGroupe = arrayColonnes[1].innerHTML;
}

function selectChangeBdd(){
	var selectBdd = document.getElementById("select_bdd").value ;
	//alert(selectBdd);

	$.ajax({
		url: '../option/optionchangebdd.php',
		type: 'GET',
		data: "selectBdd=" + selectBdd ,
		success: function (response) {
			document.body.className = "loading";
			document.location.reload(false);

		}
	});
}

function annulerGroupe(){
	$('#fiche_groupe').modal('hide');
}

function annulerCompte(){
	$('#fiche_compte').modal('hide');
}

function changeModeFonctionnement(value){
	modeMessageGlobal = value;
}

function visibilitePassword(){
	if(document.getElementById('compte_mdp').type == 'text')
		document.getElementById('compte_mdp').type = 'password';

	if(document.getElementById('compte_mdp').type == 'password')
		document.getElementById('compte_mdp').type = 'text';
}


function typeDureeCompte(){
	$("#compte_duree option").each(function() {
		$(this).remove();
	});
	var compte_duree = document.getElementById('compte_duree');
	var compteur = 0;
	if (document.getElementById("type_heure").checked == true) compteur = 24;
	if (document.getElementById("type_jour").checked == true) compteur = 10;
	if (document.getElementById("type_semaine").checked == true) compteur = 10;
	if (document.getElementById("type_mois").checked == true) compteur = 48;

	for(var i = 1; i <= compteur ; i++) {
		var option = document.createElement('option');
		option.value = i;
		option.innerHTML = i;
		compte_duree.appendChild(option);
	}
}

function deleteInputPwd(){
	if (document.getElementById("saisieMdp").checked == true) {
		document.getElementById("compte_mdp").value = "";
	}
}

function uncheckedSaisieMdp(){
	document.getElementById("saisieMdp").checked = false;
}