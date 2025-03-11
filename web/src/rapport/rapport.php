<?php
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
<div id="TheContenu" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="background-image: url('')">
	<div id="rapportFormat" style="display:none">PDF</div>
	<div id="genererFormat" style="display:none"></div>
	<div id="numeroEtape" style="display:none">1</div>

	<div class="row" >
<!--		<img src="img/background-rapport4.jpg" style="width: 100%; height: 900px; position: absolute">-->
<!--		<div class="col-lg-4 col col-lg-offset-7 " style="margin-top: 20px">-->
		<div class="col-lg-4 col col-lg-offset-4">
			<div class="panel panel-default">
				<!-- DEBUT Panel Rapport HEADING -->
				<div class="panel-heading">
					<i class="fa fa-edit fa-fw"></i> <a style="color: black" data-toggle="collapse" data-parent="#accordion" href="#rapportTemps"><?php echo _('layout_rapport'); ?></a>
					<div class="pull-right">
						<div class="btn-group">
							<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
								Modes
								<span class="caret"></span>
							</button>
							<ul class="menu dropdown-menu pull-right" role="menu">
								<li id="onglet_rapport_instant" class="effect "><a href="javascript:divModeRapport(1)"><?php echo _('rapport_rapportinstant'); ?></a>
								</li>
								<li id="onglet_rapport_auto" class="effect "><a href="javascript:divModeRapport(2)"><?php echo _('rapport_rapportauto'); ?></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- FIN Panel Rapport HEADING -->

				<div id="rapportTemps" class="panel-collapse collapse in">
					<div class="panel-body" style="min-height: 300px">

					</div> 			<!-- FIN Panel Rapport BODY -->
				</div>
			</div>  <!--FIN Panel Rapport GLOBAL -->
	</div>
	</div>
		
	
</div>
