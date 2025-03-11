<?php

    /*
     * Script permettant le login après avoir valider le formulaire de index.php
     * On y retrouve ses fonctions javascripts pour la redirection
     * Les verifications d'acces, mot de passe, changement de mot de passe et superviseur
     */

    include 'web/src/dbgpw.php';

    $table = "gpwutilisateur";
    $link = mysqli_connect($server, $db_user, $db_pass,$database);

	/*franck*/
	function recupere_ip() {
		$ip = ($ip = getenv('HTTP_FORWARDED_FOR')) ? $ip :
		($ip = getenv('HTTP_X_FORWARDED_FOR'))     ? $ip :
		($ip = getenv('HTTP_X_COMING_FROM'))       ? $ip :
		($ip = getenv('HTTP_VIA'))                 ? $ip :
		($ip = getenv('HTTP_XROXY_CONNECTION'))    ? $ip :
		($ip = getenv('HTTP_CLIENT_IP'))           ? $ip :
		($ip = getenv('REMOTE_ADDR'))              ? $ip :
		'0.0.0.0';
		return $ip;
	}
	/*franck*/

    if(isset($_POST['go_login'])) {
        if(!empty($_POST['username']) ||  !empty($_POST['password'])) {

            $username = $_POST["username"];
            $password = $_POST["password"];
            $upperUsername = strtoupper($username);
            $upperPassword = strtoupper($password);

            $matchUser = "SELECT * from $table WHERE Login = '".$upperUsername."' ";
            $qry = mysqli_query($link,$matchUser);
            $user = mysqli_fetch_assoc($qry);
            $type = $user['Type'];
            $duree = $user['Duree'];
            $dateFin = $user['Datefin'];

            if ($upperUsername == $user['Login']){

                if($upperPassword == strtoupper($user['MotPasse'])) {

                    //Verification du MDP a saisir
                    if(($user['MotPasseASaisir']) == "1"){
                        session_start();
                        $_SESSION['username'] = $user['Login'];
                        $_SESSION['password'] = md5($user['MotPasse']);
                        $_SESSION["changeMdp"] = "1";
                        header('location:index.php');

                    }else{
                        session_start();
                        $_SESSION['username'] = $user['Login'];
                        $_SESSION['password'] = md5($user['MotPasse']);

                        if(isset($_POST['notice_checkbox'])) $_SESSION['notice'] = "1";

                        /* SUPERVISEUR ? */
                        $connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
                        $queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Base,Id_Client,Superviseur FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' ) ");
                        $assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
                        $superviseur = $assocGpwUser['Superviseur'];
                        $idBaseGpwUser = $assocGpwUser['Id_Base'];
                        $idClientGpwUser = $assocGpwUser['Id_Client'];
                        mysqli_close($connectGpwUser);
                        $_SESSION['idclient'] = $idClientGpwUser;

                        if( ($user['Id_Client'] != $idClientGpwUser) || ($user['Id_Base'] != $idBaseGpwUser) ) {
                            /************* gpwuser_gpw : Update l'Id_Base, Id_Client  de l'utilisateur *******************/
                            $connect = mysqli_connect($server, $db_user, $db_pass, $database);
                            if (!$connect) die('Impossible de se connecter: ' . mysqli_connect_error());
                            $result = mysqli_query($connect, "UPDATE gpwutilisateur SET Id_Base = '" . $idBaseGpwUser . "', Id_Client = '" . $idClientGpwUser . "' WHERE (Login = '".$_SESSION['username']."' ) ");
                            mysqli_close($connect);
                        }

                        date_default_timezone_set('Europe/Berlin');
                        $date = new DateTime();
                        $dateNow = new DateTime();
                        if($type == "0" || $type == "") {	// Si validité illimitée
                            if($superviseur == "1"){

                                $_SESSION["superviseur"] = "1";
								if (isset($_POST['autologin'])){
									if($_POST['autologin'] == "on") {
										header('location:index.php?superviseur=1&remember=1');
									}else {
										header('location:index.php?superviseur=1');
									}
                                }else {
                                    header('location:index.php?superviseur=1');
                                }
                            }else{
								if (isset($_POST['autologin'])){
									$post_autologin = $_POST['autologin'];
									if($post_autologin == "on") {
										$cookie_name = 'remember_user';
										$cookie_time = (3600 * 24 * 30);
										$password_hash = md5($password); // will result in a 32 characters hash
										setcookie ($cookie_name, 'usr='.$username.'&hash='.$password_hash, time() + $cookie_time);
									}
                                }
								
								//injection des données dans historique connexion
								$con = mysqli_connect($server, $db_user, $db_pass,$database);
								if (!$con) die('Impossible de se connecter: ' . mysqli_connect_error());
								$res = mysqli_query($con, "INSERT INTO gpwhistoriqueconnexion SET Application = 'Geo3xOSM', Login = '" . strtoupper($username) . "', DateConnexion = '" .date("y-m-d"). "', HeureConnexion = '" .date("H:i:s"). "', AdresseIP = '".recupere_ip()."'");
								mysqli_close($con);
								//fin injection
								
                                header('location:web/src/layout/layout.php');
                            }
                        }else{
                            if ( ($dateFin == "") || ($dateFin == "0000-00-00 00:00:00") ) {	// Si validité limitée et date de fin pas remplie (première utilisation)
								switch ($type) {
									case "1":
										$date->add(new DateInterval('PT' . $duree . 'H'));
										break;
									case "2":
										$date->add(new DateInterval('PT' . 24 * $duree . 'H'));
										break;
									case "3":
										$date->add(new DateInterval('PT' . 24 * 7 * $duree . 'H'));
										break;
									case "4":
										$date->add(new DateInterval('PT' . 24 * 7 * 4 * $duree . 'H'));
										break;
								}
								$dateFinTypeDuree = $date->format('Y-m-d H:i:s');
                                /************* gpwutilisateur : Remplir le champs Datefin  *******************/
                                $connect = mysqli_connect($server, $db_user, $db_pass, $database);
                                if (!$connect) die('Impossible de se connecter: ' . mysqli_connect_error());
                                $result = mysqli_query($connect, "UPDATE gpwutilisateur SET Datefin = '" . $dateFinTypeDuree . "' WHERE (Login = '".$_SESSION['username']."' ) ");
                                mysqli_close($connect);

                                if($superviseur == "1"){
                                    $_SESSION["superviseur"] = "1";
									if (isset($_POST['autologin'])){
										if($_POST['autologin'] == "on") {
											header('location:index.php?superviseur=1&remember=1');
										}else {
											header('location:index.php?superviseur=1');
										}
                                    }else {
                                        header('location:index.php?superviseur=1');
                                    }
                                }else{
									if (isset($_POST['autologin'])){
										$post_autologin = $_POST['autologin'];
										if($post_autologin == "on") {
											$cookie_name = 'remember_user';
											$cookie_time = (3600 * 24 * 30);
											$password_hash = md5($password); // will result in a 32 characters hash
											setcookie ($cookie_name, 'usr='.$username.'&hash='.$password_hash, time() + $cookie_time);
										}
                                    }
									
									//injection des données dans historique connexion
									$con = mysqli_connect($server, $db_user, $db_pass,$database);
									if (!$con) die('Impossible de se connecter: ' . mysqli_connect_error());
									$res = mysqli_query($con, "INSERT INTO gpwhistoriqueconnexion SET Application = 'Geo3xOSM', Login = '" . strtoupper($username)  . "', DateConnexion = '" .date("y-m-d"). "', HeureConnexion = '" .date("H:i:s"). "', AdresseIP = '".recupere_ip()."'");
									mysqli_close($con);
									//fin injection
									
                                    header('location:web/src/layout/layout.php');
                                }
                            }else{
                                //Verification du temps d'access a l'application
                                if ($dateNow->format('Y-m-d H:i:s') <= $dateFin) {
                                    if ($superviseur == "1") {
                                        $_SESSION["superviseur"] = "1";
										if (isset($_POST['autologin'])){
											if($_POST['autologin'] == "on") {
												header('location:index.php?superviseur=1&remember=1');
											}else {
												header('location:index.php?superviseur=1');
											}
                                        }else {
                                            header('location:index.php?superviseur=1');
                                        }
                                    } else {
										if (isset($_POST['autologin'])){
											$post_autologin = $_POST['autologin'];
											if($post_autologin == "on") {
												$cookie_name = 'remember_user';
												$cookie_time = (3600 * 24 * 30);
												$password_hash = md5($password); // will result in a 32 characters hash
												setcookie ($cookie_name, 'usr='.$username.'&hash='.$password_hash, time() + $cookie_time);
											}
                                        }
										
										//injection des données dans historique connexion
										$con = mysqli_connect($server, $db_user, $db_pass,$database);
										if (!$con) die('Impossible de se connecter: ' . mysqli_connect_error());
										$res = mysqli_query($con, "INSERT INTO gpwhistoriqueconnexion SET Application = 'Geo3xOSM', Login = '" . strtoupper($username) . "', DateConnexion = '" .date("y-m-d"). "', HeureConnexion = '" .date("H:i:s"). "', AdresseIP = '".recupere_ip()."'");
										mysqli_close($con);
										//fin injection

                                        header('location:web/src/layout/layout.php');
                                    }
                                }else{
                                    session_unset();
    //							session_destroy ();
                                    $_SESSION['message_erreur'] = "Votre durée d'utilisation est terminée depuis le ".$dateFin;
                                    header('location:index.php');
                                }

                            }
                        }
                    }


                }else{

                    if(($user['MotPasse']) == ""){
                        if(($user['MotPasseASaisir']) == "1") {
                            session_start();
                            $_SESSION['username'] = $user['Login'];
                            $_SESSION['password'] = "";
                            $_SESSION["changeMdp"] = "1";
                            header('location:index.php');


                        }else{
                            session_start();
                            session_unset();

                            $_SESSION['message_erreur'] = 'Mauvais mot de passe pour cet utilisateur';
                            header('location:index.php');
                        }
                    }else {
                        session_start();
                        session_unset();

                        $_SESSION['message_erreur'] = 'Mauvais mot de passe pour cet utilisateur';
                        header('location:index.php');
                    }

                }
            }else{
                session_start();
                session_unset();
                $_SESSION['message_erreur'] = "Cet utilisateur n'existe pas";
    //			$_SESSION['message_erreur'] = $_SESSION["username"];
                header('location:index.php');

            }
        }else{
            session_start();
            session_unset();
            $_SESSION['message_erreur'] = 'Vous devez remplir tous les champs';
            echo $_SESSION['message_erreur'];
            header('location:index.php');


        }

}
//Verification du superviseur
 if(isset($_POST['go_login_superviseur2'])) {
		session_start();
	 	$_SESSION["backSuper"] = 1;
		$_SESSION['superviseurIdBd'] = $_POST['loginBD'];
		$username = $_SESSION["username"];
		$password = md5($_SESSION["password"]);
		if (isset($_POST['autologin'])){
			$post_autologin = $_POST['autologin'];
			if($post_autologin == "on") {
				$cookie_name = 'remember_user';
				$cookie_time = (3600 * 24 * 30);
				$password_hash = md5($password); // will result in a 32 characters hash
				setcookie ($cookie_name, 'usr='.$username.'&hash='.$password_hash.'&superviseur=1'.'&remember=1', time() + $cookie_time);
			}
		}
		 
		//injection des données dans historique connexion
		$con = mysqli_connect($server, $db_user, $db_pass, $database);
		if (!$con) die('Impossible de se connecter: ' . mysqli_connect_error());
		$res = mysqli_query($con, "INSERT INTO gpwhistoriqueconnexion SET Application = 'Geo3xOSM', Login = '" . strtoupper($username)  . "', DateConnexion = '" .date("y-m-d"). "', HeureConnexion = '" .date("H:i:s"). "', AdresseIP = '".recupere_ip()."'");
		mysqli_close($con);
		//fin injection
		
		header('location:web/src/layout/layout.php');
		
}

//Verification du changement de mot de passe
 if(isset($_POST['go_login_changepwd'])) {
	 session_start();
	 if(!empty($_POST['newPassword'])) {
		 $_SESSION["changeMdp"] = "";
		 $connect = mysqli_connect($server, $db_user, $db_pass, $database);
		 if (!$connect) die('Impossible de se connecter: ' . mysqli_connect_error());
		 $result = mysqli_query($connect, "UPDATE gpwutilisateur SET MotPasseASaisir = '0', MotPasse = '".$_POST['newPassword']."' WHERE (Login = '" . $_SESSION['username'] . "' ) ");
		 mysqli_close($connect);
		
		 //injection des données dans historique connexion
		$con = mysqli_connect($server, $db_user, $db_pass,$database);
		if (!$con) die('Impossible de se connecter: ' . mysqli_connect_error());
		$res = mysqli_query($con, "INSERT INTO gpwhistoriqueconnexion SET Application = 'Geo3xOSM', Login = '" . strtoupper($_SESSION['username'])  . "', DateConnexion = '" .date("y-m-d"). "', HeureConnexion = '" .date("H:i:s"). "', AdresseIP = '".recupere_ip()."'");
		mysqli_close($con);
		//fin injection
		
		header('location:web/src/layout/layout.php');
	 }else{
		 $_SESSION['message_erreur'] = 'Veuillez saisir un nouveau de mot de passe';
		 header("location:index.php");
	 }
//	 $_SESSION['superviseurIdBd'] = $_POST['loginBD'];
//	 header("location:web/src/layout/layout.php");
 }
mysqli_close($link);

?>