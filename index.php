<!DOCTYPE html>
<?php
/*
 * PAGE INDEX, on y retrouve le formulaire de connexion de GEO3X
 * Verification de la langue et de l'auto login avec les cookies
 * Redirige vers login.php à la validation du formulaire
 */

session_start();

/*Remise a zero du superviseur lors du bouton "retour" */
if (!empty($_SESSION['backSuper']))
	$_SESSION['superviseurIdBd'] = "";

/*Bibliotheque pour l'internationalisation et mise en place*/
require_once("lib/php-gettext-1.0.12/gettext.inc");
$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
//$language = $language{0}.$language{1};
$locale = "fr_FR";
if ($language != "fr") {
	$_SESSION['language'] = "en_US";
	$locale = "en_US";
} else {
	$_SESSION['language'] = "fr_FR";
	$locale = "fr_FR";
}
setlocale(LC_MESSAGES, $locale);
$encoding = "UTF-8";
$domain = "messages";
bindtextdomain($domain, 'locale');
bind_textdomain_codeset($domain, $encoding);
textdomain($domain);

/*Controle des cookies*/
error_reporting(E_ALL ^ E_NOTICE);
header('Cache-control: private'); // IE 6 FIX
// always modified
header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
// HTTP/1.1
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
// HTTP/1.0
header('Pragma: no-cache');
$cookie_name = 'remember_user';
$cookie_time = (3600 * 24 * 30); // 30 days

/*Auto login*/
if (isset($cookie_name)) {
	if (isset($_COOKIE[$cookie_name])) {
		parse_str($_COOKIE[$cookie_name], $received);
		$_SESSION['username'] = $received["usr"];
		if (!empty($received["superviseur"])) {
			$_SESSION['password'] = md5($received["hash"]);
			unset($_COOKIE['remember_user']);
			setcookie('remember_user', '', time() - 3600);
			$_SESSION["superviseur"] = "1";
			//if($received["remember"] == "1" )header('location:index.php?superviseur=1&remember=1');
			//else header('location:index.php?superviseur=1');
		} else {
			header('location:web/src/layout/layout.php');
		}
	}
}


?>
<html class="fontlog" lang="fr">

<head>
	<link rel="icon" type="image/png" href="web/assets/img/favicon.ico">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=2">
	<link href="web/assets/css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="web/assets/css/bootstrap/bootstrap-theme.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" media="all" href="web/assets/css/geo3x/geo3x-login.css" type="text/css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
	<style>
		// OSM
		.osm {
			animation: blinker 3s linear infinite;
		}

		@keyframes blinker {
			50% {
				opacity: 0;
			}
		}
	</style>
</head>

<body class="login">
	<div class="blue">
		<center>
			<p align="center" style="padding-top:5em;">
			</p>
			<p align="center">
				<img src="web/assets/img/logo_main.png" width="380" height="128">
			</p>
			<div class="container">
				<div>
					<!--h1>Geo3X </h1-->
					<!--h5 class="osm">OpenStreetMap</h5-->
					<!--img width="20" height="20" src="http://www.openstreetmap.org/assets/osm_logo-f13314123f336d32682fddc79f2a5f0dedc894c637decede98a41587340dad19.svg"/-->
				</div>

				<div class="connect" style="padding-top: 5px;">
					<div class="col-sm-offset-2 col-sm-10 col-centered">
						<h4><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
							&nbsp;<?php echo _('index_connect'); ?></h4>
					</div>


					<?php

					//Display le message en cas d'erreur avec une variable de session
					if (isset($_SESSION['message_erreur'])) {
						echo '<div class="alert-danger" role="alert" style="margin: 10px; font-size: 12px"><b>';
						echo $_SESSION['message_erreur'];
						echo "</b></div>";
						unset($_SESSION['message_erreur']);
					} else {

					}
					?>

					<form action="login.php" method="post" style="margin: 10px">
						<?php
						/**************FORM LOGIN  SUPERVISEUR************************/
						if (!empty($_SESSION["superviseur"])) {

							?>
							<div class="form-group ">

								<input style="color:black" class="form-control" type="text" id="username" name="username"
									<?php echo "value='" . $_SESSION['username'] . "' "; ?> disabled>

							</div>
							<div class="form-group">

								<input style="color:black" class="form-control" type="password" id="password"
									name="password" <?php echo "value='" . md5($_SESSION['password']) . "' "; ?> disabled>

							</div>
							<div class="checkbox " style="text-align: right">
								<i>
									<?php
									if (!empty($_GET["remember"])) {
										?>
										<label><input class="pull-left" type="checkbox" id="autologin" name="autologin"
												checked>Se souvenir de moi</label><br>
										<?php
									} else {
										?>
										<label><input class="pull-left" type="checkbox" id="autologin" name="autologin">Se
											souvenir de moi</label><br>
										<?php
									}
									?>
									<!--										<label class="hidden-xs"><input class="pull-right" type="checkbox" id="notice_checkbox" name="notice_checkbox">Téléchargement de la notice</Télécha></label><br>-->
								</i>
							</div>
							<div class="alert-info" role="alert">
								<?php echo "\"" . $_SESSION['username'] . "\" est <b>Administrateur</b>"; ?>
							</div>
							<div class="form-group"><br />
								<input class=" Degrade" type="button" onclick="javascript:loginListeBD();"
									name="go_bd_superviseur" id="go_bd_superviseur" value="Connexion" class="button">
								<!--											<input class=" Degrade" type="button" onclick="javascript:loginConfigurer();" name="go_config" id="go_config" value="Configurer" class="button">-->
								<input class=" Degrade" type="button" onclick="javascript:location.href='logout.php'"
									name="" id="" value="Deconnexion" class="button">

								<div class="col-sm-offset-2 col-sm-10"> <br></div>
								<div id="superviseur"></div>
							</div>
							<?php
							echo '<br/>';
							/**************FORM LOGIN Changement de MDP ************************/
						} else if (!empty($_SESSION["changeMdp"])) {

							?>
								<div class="form-group ">

									<input style="color:black" class="form-control" type="text" id="username" name="username"
									<?php echo "value='" . $_SESSION['username'] . "' "; ?> disabled>

								</div>
								<div class="form-group">


									<input style="color:black" class="form-control" type="password" id="password"
										name="password" <?php echo "value='" . md5($_SESSION['password']) . "' "; ?> disabled>


								</div>
								<!--								<div class="form-group">-->
								<!---->
								<!--									<select class="btn btn-default" id="selectLanguage" onChange="changeLanguage(this.value)">-->
								<!--										<option value="geo3x_fr">Fran&#231;ais</option>-->
								<!--										<option value="geo3x_en">English</option>-->
								<!--									</select>-->
								<!---->
								<!--								</div>-->
								<?php

								echo "<h4>Changement du mot de passe</h4>";

								echo "<div class=\"form-group \"><input  style=\"color:black\" class=\"form-control\" onkeyup=\"this.value=this.value.toUpperCase()\" type=\"password\" id=\"newPassword\" name=\"newPassword\"  placeholder=\"";
								echo _('index_motdepasse');
								echo " \" value=\"\"  ></div>";
								//	echo '<div class="form-group "><input  style="color:black" class="form-control" type="password" id="newPasswordVerif" name="newPasswordVerif"  placeholder="'; echo _('index_motdepasse'); echo ' " value=""  ></div>';
							
								echo '<div class="col-sm-offset-2 col-sm-10"> </div>';
								echo '<input class="Degrade" type="submit" name="go_login_changepwd" id="go_login_changepwd" value="Connexion" class="button">';
								echo '<div class="col-sm-offset-2 col-sm-10">  </div>';
								echo '<div class="col-sm-offset-2 col-sm-10"> </div>';

								echo '<br/>';
						} else {
							/**************FORM LOGIN  NORMAL ************************/
							?>
								<div class="form-group">

									<input class="form-control" style="color:black" type="text" id="username" name="username"
										placeholder="<?php echo _('index_nomutilisateur'); ?>">

								</div>
								<div class="form-group">

									<input class="form-control" style="color:black; " type="password" id="password"
										name="password" placeholder="<?php echo _('index_motdepasse'); ?>">

								</div>

								<div class="checkbox " style="text-align: right">
									<i>
										<label><input class="pull-right" type="checkbox" id="autologin"
												name="autologin"><?php echo _('souvenirdemoi'); ?></label><br>
										<!-- <label class="hidden-xs"><input class="pull-right" type="checkbox" id="notice_checkbox" name="notice_checkbox"><?php //echo _('telechargementnotice'); ?></label><br> -->
									</i>
								</div>

								<div class="form-group">

									<select class="btn btn-default input-xs" id="selectLanguage"
										onChange="changeLanguage(this.value)">
										<option value="fr_FR">Fran&#231;ais</option>
										<option value="en_US">English</option>
									</select>
									<input class=" Degrade" type="submit" name="go_login" id="go_login" value="Connexion"
										class="button">

								</div>

								<br />
							<?php
						}
						?>

					</form>


				</div>
				<!--small>Accédez à l'ancienne version de Geo3X:  <a href="http://www.geo3x.fr/" >www.geo3x.fr</a></small-->
				<!--small>  &copy; Copyright SMS 2018 </small>	<br-->
				<h5><a style="color:#5d5d5d;"
						href='https://play.google.com/store/apps/details?id=com.stancom.geofence'>Disponible sur Google
						Play&nbsp;&nbsp;<i class="fa fa-android fa-2x" style="color:#A4CA39;"></i></a></h5>
				<h5><a style="color:#5d5d5d;"
						href='https://itunes.apple.com/us/app/geofence/id1455987522?mt=8'>Disponible sur App
						Store&nbsp;&nbsp;<i class="fa fa-apple fa-2x" style="color:#9E9E9E;"></i></a></h5>
				<!--br><br-->

				<h2>Gestion de balises et flottes de v&eacute;hicules</h2>
			</div>
		</center>

		<small> SURETÉ MANAGEMENT SERVICES<br> 4 rue Le Notre – 95190 GOUSSAINVILLE - T&eacute;l : 01 34 38 86 38 -
			contact@sms-active.com</small>
	</div>

	<script>

		document.getElementById("selectLanguage").value = "<?php echo $locale ?>";

		function changeLanguage(value) {

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					//					document.location.reload(false);
				}
			}
			xmlhttp.open("GET", "web/src/language_update.php?geo3x_lang=" + value, true);
			xmlhttp.send();
		}

		function loginListeBD() {
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("superviseur").innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "loginbdsuperviseur.php", true);
			xmlhttp.send();
		}

		function loginConfigurer() {
			alert('Ce n\'est pas encore accessible');
		}
		function loginConfigFiltre() {
			alert('Ce n\'est pas encore accessible');
		}
		function databalise(x) {
			window.open('databalise.php?ref=' + x, '_blank');
			return false;
		}
	</script>
</body>

</html>