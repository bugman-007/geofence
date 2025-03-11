<?php

/*
* Affichage la page onglet etat balise
*/

$_SESSION['CREATED'] = time();

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
 ?>
<div id="TheContenu" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
	<div class="row" >
		<div class="col-lg-4 col col-lg-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"> 
					<i class="fa fa-wrench fa-fw"></i> <a style="color: black" data-toggle="collapse" data-parent="#accordion" href="#inforap"><?php echo _('layout_etatbalise'); ?></a>
				</div>
				<div id="inforap" class="panel-collapse collapse in">
					<div class="panel-body">
						<div class="container-fluid" style="overflow:hidden;border-radius:10px 10px 10px 10px;">
							<center>
							<div id="titre_etat_balise"></div> <br>
							<div id="derniere_synchro_etat_balise"> </div> <br>
							<div id="derniere_position_etat_balise"></div><br>
							<div id="data_etat_balise"></div> <br>
							</center>
						</div>
					</div>	
				</div>
			</div>
		</div>        
	</div>
</div>
<!--i class="fa fa-info-circle info"></i-->