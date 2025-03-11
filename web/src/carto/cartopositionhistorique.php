
	<br>
	<center>
		<?php

		/*
		 * 	Affiche l'onglet historique positions
		* 	Carto:js
		*/

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


		$localDateTime = new DateTime(null, new DateTimezone('Europe/Berlin'));

		?>	

		<div class="settings">
			<div data-role="fieldcontain" style="display:none">
				<label for="demo">Demo</label>
				<select name="demo" id="demo">
					<option value="date">Date</option>
					<option value="datetime"selected>Datetime</option>
					<option value="time" >Time</option>
				</select>
			</div>
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
		</div>
		<div data-role="fieldcontain" class="demo demo-date demo-datetime demo-time ">
			<label style="font-size: 12px;">Datetime</label>
			<input name="datetime" id="datetime" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time" <?php echo "value='".$localDateTime->format('Y-m-d H:i:s')."' "; ?> />
			<select id="selectposition" class="geo3x_input_datetime " style="min-width: 15px">
				<option value="avant" selected="selected"><?php echo _('avant'); ?></option>
				<option value="apres"><?php echo _('apres'); ?></option></select><br>
		</div>
		<br/>
		<label style="font-size: 12px;">N <?php echo _('dernierespositions'); ?>: </label> <input class="geo3x_input_text" type='number' id='n' size='2' min='1' max='9999' step='10' value='10' style='width: 50px;'>

		<input type="button" class="btn btn-default btn-xs dropdown-toggle" onClick="addPositionTablePosition();addPositionPagination();addPositionMarker()" value="<?php echo _('recherche'); ?>">

	<br>
	<div class="checkbox">
		<label style="font-size: 12px;"><input class="pull-right" type="checkbox" id="id_historique_filtrage" name="name_historique_filtrage" checked><?php echo _('filtrerlessarrets'); ?></label> &nbsp;
<!--		<label style="font-size: 12px;"><input class="pull-right" type="checkbox" id="id_historique_infulbulle" name="name_historique_infobulle"  onclick="" checked>--><?php //echo _('infobulle'); ?><!--</label>-->
		<label style="font-size: 12px;"><input class="pull-right" type="checkbox" id="id_historique_trait_trajet" name="name_historique_trait_trajet" onclick="traitTrajet()">TraitTrajet</label> &nbsp;
		<label style="font-size: 12px;"><input class="pull-right" type="checkbox" id="id_historique_poi" name="name_historique_poi" >POI</label>&nbsp;
		<label style="font-size: 12px;"><input class="pull-right" type="checkbox" id="id_historique_icon" name="name_historique_icon"  onclick="iconHistorique()" checked><?php echo _('icone'); ?></label><br>
		<label style="font-size: 12px;"><?php echo _('distancefiltree'); ?>(m): <input class="geo3x_input_text " type="number" value="0" step="50" min="0" max="1000" id="id_historique_distance_filtree"  name="name_historique_distance_filtree" style="width: 150px;"></label>
	</div>
	</center>
<script>

		document.getElementById("language").value = "<?php session_start(); if( (substr($_SESSION['language'],-2) == "US")) echo ""; else echo "fr" ?>";
		var myTimeFormat = "HH:ii:ss";

		var notreDateFin = new Date();
		var notreMoisFin = notreDateFin.getMonth()+1;
		datetime 	= 	notreDateFin.getFullYear() + "-" + ((notreMoisFin < 10)?"0":"") + notreMoisFin+ "-" + ((notreDateFin.getDate() < 10)?"0":"") + notreDateFin.getDate() + " " 
					+ 	((notreDateFin.getHours() < 10)?"0":"") + notreDateFin.getHours() + ":" + ((notreDateFin.getMinutes() < 10)?"0":"") +  notreDateFin.getMinutes() + ":" + ((notreDateFin.getSeconds() < 10)?"0":"") + notreDateFin.getSeconds();


		if(document.getElementById("language").value == ""){
			myTimeFormat = "hh:ii:ss A";
			datetime = formatDateAMPM(datetime);
		}

		document.getElementById("datetime").value = datetime;


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
				});

				$('#demo').trigger('change');

			});
	</script>