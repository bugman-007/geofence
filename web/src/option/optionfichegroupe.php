
<?php
/*
* Afficher la fiche groupe
*/

	include '../dbgpw.php';
	include '../dbconnect2.php';

	session_start();
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

	$idClient = $_GET["idClient"];
	$nomBase = $_GET["nomBase"];
	$idGPW = "";
	$nomGPW = "";
	if(!empty($_GET['idGPW'])) $idGPW = $_GET['idGPW'] ;
	if(!empty($_GET['nomGPW'])) $nomGPW = $_GET['nomGPW'] ;

	if(!empty($_GET['idGPW'])){
		$arrayIdBalise = array();
		$arrayNomBalise = array();
		$i = 0;
		$y = 0;
		$connectGpwBalise = mysqli_connect($server, $db_user, $db_pass,$database);
		mysqli_set_charset($connectGpwBalise, "utf8");
		$queryGpwBalise = mysqli_query($connectGpwBalise,"SELECT Id_Balise,Nom_Balise FROM gpwbalise WHERE id_GPW = ".$idGPW." ORDER BY Nom_Balise");
		while($arrayGpwBalise = mysqli_fetch_array($queryGpwBalise)){
			$arrayIdBalise[$i] = $arrayGpwBalise['Id_Balise'];
			$arrayNomBalise[$i] = $arrayGpwBalise['Nom_Balise'];
			$i++;
		}
		mysqli_free_result($queryGpwBalise);
		mysqli_close($connectGpwBalise);
	}

?>

<div class="form-group"  >
	<div class="col-md-12">
		<b><?php echo _('option_baliseexistante'); ?>:</b>
	</div>
</div>


<?php
	$i=0;
	$connectGpwBalise = mysqli_connect($server, $db_user_2, $db_pass_2,$nomBase);
	mysqli_set_charset($connectGpwBalise, "utf8");
	$queryGpwBalise = mysqli_query($connectGpwBalise,"SELECT * FROM ttrackers WHERE Client = '".$idClient."'  ORDER BY Nom_tracker");
	$lengths = mysqli_num_rows($queryGpwBalise);
	echo '<div style="display: inline-block; height:250px; overflow:scroll;">';

	echo '<table id="sourcetable" class="sortable table table-bordered table-hover">';
	echo '<thead><tr><th style="width: 50px">'; echo _('idbalise'); echo'</th><th style="width: 100px">'; echo _('nombalise'); echo'</th></tr></thead>';
	while($arrayGpwBalise = mysqli_fetch_array($queryGpwBalise)) {
		if(!empty($_GET['idGPW'])) {
				if (!in_array($arrayGpwBalise['Id_tracker'], $arrayIdBalise))
					echo '<tr id="sour' . $arrayGpwBalise['Id_tracker'] . '" onclick="addRowDestGrp(this)" ><td>' . $arrayGpwBalise['Id_tracker'] . '</td><td>' . $arrayGpwBalise['Nom_tracker'] . '</td></tr>';
		}else{
			echo '<tr id="sour' . $arrayGpwBalise['Id_tracker'] . '" onclick="addRowDestGrp(this)" ><td>' . $arrayGpwBalise['Id_tracker'] . '</td><td>' . $arrayGpwBalise['Nom_tracker'] . '</td></tr>';
		}
		$i++;
	}
	mysqli_free_result($queryGpwBalise);
	mysqli_close($connectGpwBalise);


?>

	</table>
</div>
<div class="form-group"  >
	<div class="col-md-12">
		<b><?php echo _('option_baliseassocgroupe'); ?>:</b>
	</div>
</div>

<div style="display: inline-block; height:250px; border-width:1px;overflow: scroll">

	<table id="destinationtable" class="sortable table table-bordered table-hover" >
		<thead>
		<tr>
			<th style="width: 50px"><?php echo _('idbalise'); ?></th>
			<th style="width: 100px" ><?php echo _('nombalise'); ?></th>
		</tr>
		</thead>
		<?php
		if(!empty($_GET['idGPW'])){
			$i = 0;
			$y = 0;
			foreach ($arrayNomBalise as $val) {
				if($arrayIdBalise[$i]) {
					echo '<tr id="dest' . $arrayIdBalise[$i] . '" onclick="addRowSourceGrp(this)" ><td>' . $arrayIdBalise[$i] . '</td><td>' . $val . '</td></tr>';
					$y++;
				}
				$i++;
			}

		}
		?>
	</table>

</div>
<br/><br/>
<?php
if(!empty($_GET['idGPW'])) {
	echo '<center>'; echo _('option_nomgroupe'); echo':&nbsp;<input class="geo3x_input_text input-xs" style="width:200px;" id="nom_groupe" type="text" value="'.$nomGPW.'" disabled>';

	echo '&nbsp;&nbsp;<input type="button" class="btn btn-default btn-xs" onClick="annulerGroupe()" value="'; echo _('annuler'); echo'">';
	echo '&nbsp;<input type="button" class="btn btn-default btn-xs" onClick="modifyGPW(\''.$idClient.'\')" value="'; echo _('modifier'); echo'"></center>';
}else{
	echo '<center>'; echo _('option_nomgroupe'); echo':&nbsp;<input class="geo3x_input_text input-xs" style="width:200px;" id="nom_groupe" type="text">';

	echo '&nbsp;&nbsp;&nbsp;<input type="button" class="btn btn-default btn-xs" onClick="annulerGroupe()" value="'; echo _('annuler'); echo'">';
	echo '&nbsp;&nbsp;<input type="button" class="btn btn-default btn-xs" onClick="createGPW(\''.$idClient.'\')" value="'; echo _('creer'); echo'"></center>';
}
?>


