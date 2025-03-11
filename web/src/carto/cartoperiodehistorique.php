<?php

/*
	 * 	Affiche l'onglet historique periode
	 * 	Carto:js
	 */



session_start();
$_SESSION['CREATED'] = time();
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
<br>
<center>
	<div class="settings">
		<div data-role="fieldcontain" style="display:none">
			<label for="language">Language</label>
			<select name="language" id="language">
				<option value="">English</option>
				<option value="de">Deutsch</option>
				<option value="es">Espa�ol</option>
				<option value="fr" selected>Fran�ais</option>
				<option value="hu">Magyar</option>
				<option value="it">Italiano</option>
			</select>
		</div>
		<div data-role="fieldcontain" style="display:none">
			<label for="demo">Demo</label>
			<select name="demo" id="demo">
				<option value="date">Date</option>
				<option value="datetime"selected>Datetime</option>
				<option value="time" >Time</option>
			</select>
		</div>
	</div>
	<table>
		<?php
		$date = date("Y-m-d");
		$heure = date("H")+2;
		$heures = date($heure.":i:s");
		$dateTime = $date." 00:00:00";

		$localDateTime = new DateTime(null, new DateTimezone('Europe/Berlin'));
		// echo $localDateTime->format('Y-m-d H:i:s');

		?>
		<tr>
			<td style="font-size: 12px;"><label for="test" style="width:54px"><?php echo _('debut'); ?></label><input onchange="resetSelectPeriode()" name="ddebut" id="debutperiode" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time" <?php echo "value='".$dateTime."' "; ?> /> </td>
		</tr>
		<tr>
			<td style="font-size: 12px;"><label for="test" style="width:50px"><?php echo _('fin'); ?></label> <input name="dfin" onchange="resetSelectPeriode()" id="finperiode" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time" <?php echo "value='".$localDateTime->format('Y-m-d H:i:s')."' "; ?> /></td>
		</tr>
	</table>
	<select style="font-size: 12px;" id="selectPeriode" onChange="selectPeriode(this.value);" class="geo3x_input_datetime">
		<option value="aucun" disabled><?php echo _('aucune'); ?></option>
		<option value="aujourdhui"><?php echo _('aujourdhui'); ?></option>
		<option value="hier"><?php echo _('hier'); ?></option>
		<option value="semaine"><?php echo _('semaineflottante'); ?></option>
		<option value="mois"><?php echo _('moisflottant'); ?></option>
		<option value="annee"><?php echo _('anneeflottante'); ?></option>
	</select>
	&nbsp;
	<input type="button" class="btn btn-default btn-xs dropdown-toggle" onClick="boutonPeriodePosition()" value="<?php echo _('recherche'); ?>">
<!--	<input type="button" class="btn btn-default btn-xs dropdown-toggle" onClick="allDayInInterval('test','test', 1)" value="test">-->

	<br>
	<div class="checkbox">
		<label style="font-size: 12px;"><input class="pull-right" type="checkbox" id="id_historique_filtrage" name="name_historique_filtrage" checked><?php echo _('filtrerlessarrets'); ?></label> &nbsp;
		<label style="font-size: 12px;"><input class="pull-right" type="checkbox" id="id_historique_infobulle" name="name_historique_infobulle"  onclick="" checked><?php echo _('infobulle'); ?></label>
		<label style="font-size: 12px;"><input class="pull-right" type="checkbox" id="id_historique_trait_trajet" name="name_historique_trait_trajet" onclick="traitTrajet()">TraitTrajet</label> &nbsp;
		<label style="font-size: 12px;"><input class="pull-right" type="checkbox" id="id_historique_poi" name="name_historique_poi" >POI</label>&nbsp;
		<label style="font-size: 12px;"><input class="pull-right" type="checkbox" id="id_historique_icon" name="name_historique_icon"  onclick="iconHistorique()" checked><?php echo _('icone'); ?></label>
		<br>
		<label style="font-size: 12px;"><?php echo _('distancefiltree'); ?>(m): <input class="geo3x_input_text" type="number" value="0" step="50" min="0" max="1000" id="id_historique_distance_filtree"  name="name_historique_distance_filtree" style="width: 150px;"></label>

	</div>
	</center>
<script>

	document.getElementById("language").value = "<?php if( (substr($_SESSION['language'],-2) == "US")) echo ""; else echo "fr" ?>";

	//Aujourdhui
	var notreDateDebut = new Date();
	var notreMoisDebut = notreDateDebut.getMonth()+1;
	debutPeriode = 	notreDateDebut.getFullYear() + "-" + ((notreMoisDebut < 10)?"0":"") + notreMoisDebut+ "-" + ((notreDateDebut.getDate() < 10)?"0":"") + notreDateDebut.getDate() + " 00:00:00";

	var notreDateFin = new Date();
	var notreMoisFin = notreDateFin.getMonth()+1;
	finPeriode 	= 	notreDateFin.getFullYear() + "-" + ((notreMoisFin < 10)?"0":"") + notreMoisFin+ "-" + ((notreDateFin.getDate() < 10)?"0":"") + notreDateFin.getDate() + " "
				+ 	((notreDateFin.getHours() < 10)?"0":"") + notreDateFin.getHours() + ":" + ((notreDateFin.getMinutes() < 10)?"0":"") +  notreDateFin.getMinutes() + ":" + ((notreDateFin.getSeconds() < 10)?"0":"") + notreDateFin.getSeconds();
	var myTimeFormat = "HH:ii:ss";
	if(document.getElementById("language").value == ""){
		myTimeFormat = "hh:ii:ss A";
		debutPeriode = formatDateAMPM(debutPeriode);
		finPeriode = formatDateAMPM(finPeriode);
	}

	document.getElementById("debutperiode").value = debutPeriode;
	document.getElementById("finperiode").value = finPeriode;


	function replaceStr(str, pos, value){
		var arr = str.split('');
		arr[pos]=value;
		return arr.join('');
	}
	function selectPeriode(value) {


//		document.getElementById("selectPeriode").value = "";

		var debutPeriode = document.getElementById("debutperiode").value;
		var finPeriode = document.getElementById("finperiode").value;
		//Aujourdhui
		var notreDateDebut = new Date();
		var notreMoisDebut = notreDateDebut.getMonth()+1;
		debutPeriode = 	notreDateDebut.getFullYear() + "-" + ((notreMoisDebut < 10)?"0":"") + notreMoisDebut+ "-" + ((notreDateDebut.getDate() < 10)?"0":"") + notreDateDebut.getDate() + " 00:00:00";

		var notreDateFin = new Date();
		var notreMoisFin = notreDateFin.getMonth()+1;
		finPeriode 	= 	notreDateFin.getFullYear() + "-" + ((notreMoisFin < 10)?"0":"") + notreMoisFin+ "-" + ((notreDateFin.getDate() < 10)?"0":"") + notreDateFin.getDate() + " "
					+ 	((notreDateFin.getHours() < 10)?"0":"") + notreDateFin.getHours() + ":" + ((notreDateFin.getMinutes() < 10)?"0":"") +  notreDateFin.getMinutes() + ":" + ((notreDateFin.getSeconds() < 10)?"0":"") + notreDateFin.getSeconds();

		switch(value){
			/*****************************************************************************************/
			case "hier":
				var jourHierDebutPeriode;
				var moisHierDebutPeriode;
				var anneeHierDebutPeriode;
				// alert((debutPeriode[8]+""+debutPeriode[9]-1));
				//Si hier on estle mois d'avant
				if((debutPeriode[8]+""+debutPeriode[9]-1) == ("0" || "00") ){
					moisHierDebutPeriode = (((debutPeriode[5]+""+debutPeriode[6]-1)<10)?"0":"") + (debutPeriode[5]+""+debutPeriode[6]-1);
					anneeHierDebutPeriode = debutPeriode[0]+""+debutPeriode[1]+""+debutPeriode[2]+""+debutPeriode[3];
					
					//Si le mois d'avant on est  � l'ann�e pr�c�dente
					if( (moisHierDebutPeriode ==  "0") || (moisHierDebutPeriode ==  "00") ){
						anneeHierDebutPeriode = debutPeriode[0]+""+debutPeriode[1]+""+(debutPeriode[2]+""+debutPeriode[3] -1) ;
						moisHierDebutPeriode = "12";
						jourHierDebutPeriode = "31";
						
						debutPeriode = replaceStr(debutPeriode,0,anneeHierDebutPeriode[0]);
						debutPeriode = replaceStr(debutPeriode,1,anneeHierDebutPeriode[1]);
						debutPeriode = replaceStr(debutPeriode,2,anneeHierDebutPeriode[2]);
						debutPeriode = replaceStr(debutPeriode,3,anneeHierDebutPeriode[3]);
						
						finPeriode = replaceStr(finPeriode,0,anneeHierDebutPeriode[0]);
						finPeriode = replaceStr(finPeriode,1,anneeHierDebutPeriode[1]);
						finPeriode = replaceStr(finPeriode,2,anneeHierDebutPeriode[2]);
						finPeriode = replaceStr(finPeriode,3,anneeHierDebutPeriode[3]);
					//Si le mois d'avant est impaire
					}else if( (moisHierDebutPeriode ==  "01") || (moisHierDebutPeriode ==  "03") || (moisHierDebutPeriode ==  "05") || (moisHierDebutPeriode ==  "07") || (moisHierDebutPeriode ==  "08") || (moisHierDebutPeriode ==  "10") ){
						jourHierDebutPeriode = "31";
					//Si le mois d'avant est paire
					}else if( (moisHierDebutPeriode ==  "04") || (moisHierDebutPeriode ==  "06") || (moisHierDebutPeriode ==  "09") || (moisHierDebutPeriode ==  "11") ){
						jourHierDebutPeriode = "30";
					//Si le mois d'avant on est en F�vrier
					}else if( (moisHierDebutPeriode == "02") && (anneeHierDebutPeriode%4) ){	// pas bissextile
						jourHierDebutPeriode ="28";
					}else{
						jourHierDebutPeriode ="29";
					}
					debutPeriode = replaceStr(debutPeriode,5,moisHierDebutPeriode[0]);
					debutPeriode = replaceStr(debutPeriode,6,moisHierDebutPeriode[1]);
					finPeriode = replaceStr(finPeriode,5,moisHierDebutPeriode[0]);
					finPeriode = replaceStr(finPeriode,6,moisHierDebutPeriode[1]);
				}else{
				//Sinon (si on n'est pas au mois d'avant)
					jourHierDebutPeriode = (((debutPeriode[8]+""+debutPeriode[9]-1)<10)?"0":"") + (debutPeriode[8]+""+debutPeriode[9]-1);
				}
				
				debutPeriode = replaceStr(debutPeriode,8,jourHierDebutPeriode[0]);
				debutPeriode = replaceStr(debutPeriode,9,jourHierDebutPeriode[1]);
				finPeriode = replaceStr(finPeriode,8,jourHierDebutPeriode[0]);
				finPeriode = replaceStr(finPeriode,9,jourHierDebutPeriode[1]);

				var heureHierFinPeriode = "23";
				finPeriode = replaceStr(finPeriode,11,heureHierFinPeriode[0]);
				finPeriode = replaceStr(finPeriode,12,heureHierFinPeriode[1]);

				var minuteHierFinPeriode = "59";
				finPeriode = replaceStr(finPeriode,14,minuteHierFinPeriode[0]);
				finPeriode = replaceStr(finPeriode,15,minuteHierFinPeriode[1]);

				var secondeHierFinPeriode = "59";
				finPeriode = replaceStr(finPeriode,17,secondeHierFinPeriode[0]);
				finPeriode = replaceStr(finPeriode,18,secondeHierFinPeriode[1]);
				break;

			/*****************************************************************************************/
			case "semaine":
				var jourSemaineDebutPeriode;
				var moisSemaineDebutPeriode;
				var anneeSemaineDebutPeriode;

				//Si en semaine flottante on est le mois d'avant
				if((debutPeriode[8]+""+debutPeriode[9]-7) <= ("0" || "00") ){
					moisSemaineDebutPeriode = (((debutPeriode[5]+""+debutPeriode[6]-1)<10)?"0":"") + (debutPeriode[5]+""+debutPeriode[6]-1);
					debutPeriode = replaceStr(debutPeriode,5,moisSemaineDebutPeriode[0]);
					debutPeriode = replaceStr(debutPeriode,6,moisSemaineDebutPeriode[1]);
					
					anneeSemaineDebutPeriode = debutPeriode[0]+""+debutPeriode[1]+""+debutPeriode[2]+""+debutPeriode[3];
					
					//Si le mois d'avant on est  � l'ann�e pr�c�dente
					if( (moisSemaineDebutPeriode ==  "0") || (moisSemaineDebutPeriode ==  "00") ){
						anneeSemaineDebutPeriode = debutPeriode[0]+""+debutPeriode[1]+""+(debutPeriode[2]+""+debutPeriode[3] -1) ;
						debutPeriode = replaceStr(debutPeriode,0,anneeSemaineDebutPeriode[0]);
						debutPeriode = replaceStr(debutPeriode,1,anneeSemaineDebutPeriode[1]);
						debutPeriode = replaceStr(debutPeriode,2,anneeSemaineDebutPeriode[2]);
						debutPeriode = replaceStr(debutPeriode,3,anneeSemaineDebutPeriode[3]);

						moisSemaineDebutPeriode = "12";
						debutPeriode = replaceStr(debutPeriode,5,moisSemaineDebutPeriode[0]);
						debutPeriode = replaceStr(debutPeriode,6,moisSemaineDebutPeriode[1]);
						
						jourSemaineDebutPeriode = 31 + parseInt(debutPeriode[8]+""+debutPeriode[9]-7);
						jourSemaineDebutPeriode = jourSemaineDebutPeriode+"";
						debutPeriode = replaceStr(debutPeriode,8,jourSemaineDebutPeriode[0]);
						debutPeriode = replaceStr(debutPeriode,9,jourSemaineDebutPeriode[1]);
					//Si le mois d'avant est impaire
					}else if( (moisSemaineDebutPeriode ==  "01") || (moisSemaineDebutPeriode ==  "03") || (moisSemaineDebutPeriode ==  "05") || (moisSemaineDebutPeriode ==  "07") || (moisSemaineDebutPeriode ==  "08") || (moisSemaineDebutPeriode ==  "10") ){
						jourSemaineDebutPeriode = 31 + parseInt(debutPeriode[8]+""+debutPeriode[9]-7);
						jourSemaineDebutPeriode = jourSemaineDebutPeriode+"";
						debutPeriode = replaceStr(debutPeriode,8,jourSemaineDebutPeriode[0]);
						debutPeriode = replaceStr(debutPeriode,9,jourSemaineDebutPeriode[1]);
					//Si le mois d'avant est paire
					}else if( (moisSemaineDebutPeriode ==  "04") || (moisSemaineDebutPeriode ==  "06") || (moisSemaineDebutPeriode ==  "09") || (moisSemaineDebutPeriode ==  "11") ){
						jourSemaineDebutPeriode = 30 + parseInt(debutPeriode[8]+""+debutPeriode[9]-7);
						jourSemaineDebutPeriode = jourSemaineDebutPeriode+"";
						debutPeriode = replaceStr(debutPeriode,8,jourSemaineDebutPeriode[0]);
						debutPeriode = replaceStr(debutPeriode,9,jourSemaineDebutPeriode[1]);
						//Si le mois d'avant on est en F�vrier
					}else if( (moisSemaineDebutPeriode == "02") && (anneeSemaineDebutPeriode%4) ){	// pas bissextile
						jourSemaineDebutPeriode =28+ parseInt(debutPeriode[8]+""+debutPeriode[9]-7);
						jourSemaineDebutPeriode = jourSemaineDebutPeriode+"";
						debutPeriode = replaceStr(debutPeriode,8,jourSemaineDebutPeriode[0]);
						debutPeriode = replaceStr(debutPeriode,9,jourSemaineDebutPeriode[1]);
					}else{
						jourSemaineDebutPeriode =29+ parseInt(debutPeriode[8]+""+debutPeriode[9]-7);
						jourSemaineDebutPeriode = jourSemaineDebutPeriode+"";
						debutPeriode = replaceStr(debutPeriode,8,jourSemaineDebutPeriode[0]);
						debutPeriode = replaceStr(debutPeriode,9,jourSemaineDebutPeriode[1]);
					}
				}else{
					//Sinon (si on n'est pas au mois d'avant) 
					jourSemaineDebutPeriode = (((debutPeriode[8]+""+debutPeriode[9]-7)<10)?"0":"") + (debutPeriode[8]+""+debutPeriode[9]-7);
					debutPeriode = replaceStr(debutPeriode,8,jourSemaineDebutPeriode[0]);
					debutPeriode = replaceStr(debutPeriode,9,jourSemaineDebutPeriode[1]);
				}
				break;
			/*****************************************************************************************/
			case "mois":
				//Mois flottant *********************************
				var moisMoisDebutPeriode;
				var anneeMoisDebutPeriode;
				moisMoisDebutPeriode = (((debutPeriode[5]+""+debutPeriode[6]-1)<10)?"0":"") + (debutPeriode[5]+""+debutPeriode[6]-1);
				//Si le mois d'avant on est  � l'ann�e pr�c�dente
				if( (moisMoisDebutPeriode ==  "0") || (moisMoisDebutPeriode ==  "00") ){
					anneeMoisDebutPeriode = debutPeriode[0]+""+debutPeriode[1]+""+(debutPeriode[2]+""+debutPeriode[3] -1) ;
					debutPeriode = replaceStr(debutPeriode,0,anneeMoisDebutPeriode[0]);
					debutPeriode = replaceStr(debutPeriode,1,anneeMoisDebutPeriode[1]);
					debutPeriode = replaceStr(debutPeriode,2,anneeMoisDebutPeriode[2]);
					debutPeriode = replaceStr(debutPeriode,3,anneeMoisDebutPeriode[3]);
					moisMoisDebutPeriode = "12";
				}
				debutPeriode = replaceStr(debutPeriode,5,moisMoisDebutPeriode[0]);
				debutPeriode = replaceStr(debutPeriode,6,moisMoisDebutPeriode[1]);

				break;
			/*****************************************************************************************/
			case "annee":
				//Mois flottant *********************************
				var anneeAnneeDebutPeriode;
				anneeAnneeDebutPeriode = debutPeriode[0]+""+debutPeriode[1]+""+(debutPeriode[2]+""+debutPeriode[3] -1) ;
				debutPeriode = replaceStr(debutPeriode,0,anneeAnneeDebutPeriode[0]);
				debutPeriode = replaceStr(debutPeriode,1,anneeAnneeDebutPeriode[1]);
				debutPeriode = replaceStr(debutPeriode,2,anneeAnneeDebutPeriode[2]);
				debutPeriode = replaceStr(debutPeriode,3,anneeAnneeDebutPeriode[3]);
				// alert(anneeAnneeDebutPeriode[0]);

				break;
		}

		if(document.getElementById("language").value == ""){
			debutPeriode = formatDateAMPM(debutPeriode);
			finPeriode = formatDateAMPM(finPeriode);
		}
		document.getElementById("debutperiode").value = debutPeriode;
		document.getElementById("finperiode").value = finPeriode;
		$('#demo').trigger('change');

	}
	$(function () {
		var curr = new Date().getFullYear();
		var opt = {
			'date': {
				preset: 'date',
				dateOrder: 'd Dmmyy',
				invalid: { daysOfWeek: [0, 6], daysOfMonth: ['5/1', '12/24', '12/25'] }
			},
			'datetime': {
				preset: 'datetime',
				minDate: new Date(2019, 1, 1, 0, 0),
				maxDate: new Date(2050, 2, 1, 0, 0),
				stepMinute: 1,
				dateFormat: 'yy-mm-dd',
				timeFormat: myTimeFormat
			},
			'time': {
				preset: 'time'
			}
		}

		$('.settings select').bind('change', function() {
			var demo = $('#demo').val();
			// if (!demo.match(/select/i)) {
				// $('.demo-test-' + demo).val('');
			// }
			$('.demo-test-' + demo).scroller('destroy').scroller($.extend(opt[demo], {
				theme: $('#theme').val(),
				mode: $('#mode').val(),
				lang: $('#language').val(),
				display: $('#display').val(),
				animate: $('#animation').val()
			}));
			$('.demo').hide();
			$('.demo-' + demo).show();

//			if(document.getElementById("selectPeriode").value != "aujourdhui")
//				document.getElementById("selectPeriode").value = "";

		});
		$('#demo').scroller('setValue', "test", true);
		$('#demo').trigger('change');
	});

</script>