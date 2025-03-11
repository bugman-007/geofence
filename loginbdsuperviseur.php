<?php
	/*
	 * Script permettant d'ajouter le contenu HTML pour le login d'un superviseur
	 */

	include 'web/src/dbgpw.php';

	$connectGpwBD= mysqli_connect($server, $db_user, $db_pass,$database);
	if (!$connectGpwBD) {
		die('Impossible de se connecter: '.mysqli_connect_error());
	}

	$queryGpwBD= mysqli_query($connectGpwBD,"SELECT Id_Base, NomBase, DescriptionBase FROM gpwbd ORDER BY Id_Base");
	$iGpw=0;

	echo "<h4>Choix de la Base de Donn&eacute;es</h4>";
?>

<select style='color:black' id='loginBD' name='loginBD'  style='font-size: 8px' ><?php

	while($fetchGpwBD = mysqli_fetch_array($queryGpwBD)){
			echo " <option   value='".$fetchGpwBD['Id_Base']."'>".$fetchGpwBD['NomBase']." - ".$fetchGpwBD['DescriptionBase']."</option>";
	} ?>
 </div>
	</select id>
	<div class="col-sm-offset-2 col-sm-10"> <br></div>
		<input class="Degrade" type="submit" name="go_login_superviseur2" id="go_login_superviseur2" value="Connexion" class="button">
		<!--input class="Degrade" type="button" onclick="javascript:loginConfigFiltre();" name="go_config" id="go_config" value="Config (filtre)" class="button"-->
		<input class="Degrade" type="button" onclick="javascript:databalise(document.getElementById('loginBD').value);" name="data_balise" id="data_balise" value="Datas Balises" class="button">
	</div>
	<div class="col-sm-offset-2 col-sm-10"> <br></div>
<?php
	mysqli_close($connectGpwBD);
	
?>
</table>