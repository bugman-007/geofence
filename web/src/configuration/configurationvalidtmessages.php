<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 09/03/2015
 * Time: 14:13
 */
/*
* Ajouter tmessages
 *
 *
 * */
    session_start();
    include '../function.php';		// pour utiliser wd_remove_accents();
    include '../dbgpw.php';
    include '../dbconnect2.php';


    $_SESSION['CREATED'] = time();

	$modeMessage=$_GET["modeMessage"];
    $idTracker=$_GET["idTracker"];

    $messageNumero1 = "";
    $messageNumero2 = "";
    $messageNumero3 = "";
    $messageNumero4 = "";
    $corpsMessageNumero1 = "";
    $corpsMessageNumero2 = "";
    $corpsMessageNumero3 = "";
    $corpsMessageNumero4 = "";
	
    if(isset($_GET['messageNumero1'])){
        $messageNumero1=$_GET["messageNumero1"];
        if($messageNumero1 != "") {
//            if ($messageNumero1[1] == "3" && $messageNumero1[2] == "3"){		// rétablissement du caractère + qui ne passe pas
            if ($messageNumero1[0] == " "){		// rétablissement du caractère + qui ne passe pas
				$messageNumero1[0] = "+";
			}
			if($modeMessage == "GPRS"){
				$corpsMessageNumero1 = "?FT1" . $messageNumero1 . "!";
			}
			else if($modeMessage == "SMS"){
				$corpsMessageNumero1 = "#FT1" . $messageNumero1 . "&";
			}
        }
		$messageNumero1 = "," . $messageNumero1;
    }
    if(isset($_GET['messageNumero2'])){
        $messageNumero2=$_GET["messageNumero2"];
        if($messageNumero2 != "") {
//            if ($messageNumero2[1] == "3" && $messageNumero2[2] == "3"){
            if ($messageNumero2[0] == " "){		// rétablissement du caractère + qui ne passe pas
				$messageNumero2[0] = "+";
			}
			if($modeMessage == "GPRS"){
				$corpsMessageNumero2 = "?FT2" . $messageNumero2 . "!";
			}
			else if($modeMessage == "SMS"){
				$corpsMessageNumero2 = "#FT2" . $messageNumero2 . "&";
			}
        }
		$messageNumero2 = "," . $messageNumero2;
    }
    if(isset($_GET['messageNumero3'])){
        $messageNumero3=$_GET["messageNumero3"];
        if($messageNumero3 != "") {
//            if ($messageNumero3[1] == "3" && $messageNumero3[2] == "3"){
            if ($messageNumero3[0] == " "){		// rétablissement du caractère + qui ne passe pas
				$messageNumero3[0] = "+";
			}
			if($modeMessage == "GPRS"){
				$corpsMessageNumero3 = "?FT3" . $messageNumero3 . "!";
			}
			else if($modeMessage == "SMS"){
				$corpsMessageNumero3 = "#FT3" . $messageNumero3 . "&";
			}
        }
		// $messageNumero3 = "," . $messageNumero3;
		$messageNumero3 = ",+33630257817";
    }
    if(isset($_GET['messageNumero4'])){
        $messageNumero4=$_GET["messageNumero4"];
        if($messageNumero4 != "") {
//            if ($messageNumero4[1] == "3" && $messageNumero4[2] == "3"){		// rétablissement du plus qui ne passe pas
            if ($messageNumero4[0] == " "){		// rétablissement du caractère + qui ne passe pas
				$messageNumero4[0] = "+";
			}
			if($modeMessage == "GPRS"){
				$corpsMessageNumero4 = "?FT4" . $messageNumero4 . "!";
			}
			else if($modeMessage == "SMS"){
				$corpsMessageNumero4 = "#FT4" . $messageNumero4 . "&";
			}
        }
//        $messageNumero4 = " " . $messageNumero4;
    }
	
    $corps1 = "";
    $corps2 = "";
    $corps3 = "";
    $corps4 = "";
    $corps5 = "";
    $corps6 = "";

    if(isset($_GET['corps1'])){
        $corps1=$_GET["corps1"];
        if($corps1 != "") {
			$corps1 = wd_remove_accents($corps1);
			if($modeMessage == "GPRS"){
				$corps1 = "?" . $corps1 . "!";
			}
			else if($modeMessage == "SMS"){
				$corps1 = "#" . $corps1 . "&";
			}
        }
    }
    if(isset($_GET['corps2'])){
        $corps2=$_GET["corps2"];
        if($corps2 != "") {
			$corps2 = wd_remove_accents($corps2);
			if($modeMessage == "GPRS"){
				$corps2 = "?" . $corps2 . "!";
			}
			else if($modeMessage == "SMS"){
				$corps2 = "#" . $corps2 . "&";
			}
        }
    }
    if(isset($_GET['corps3'])){
        $corps3=$_GET["corps3"];
        if($corps3 != "") {
			$corps3 = wd_remove_accents($corps3);
			if($modeMessage == "GPRS"){
				$corps3 = "?" . $corps3 . "!";
			}
			else if($modeMessage == "SMS"){
				$corps3 = "#" . $corps3 . "&";
			}
        }
    }
    if(isset($_GET['corps4'])){
        $corps4=$_GET["corps4"];
        if($corps4 != "") {
			$corps4 = wd_remove_accents($corps4);
			if($modeMessage == "GPRS"){
				$corps4 = "?" . $corps4 . "!";
			}
			else if($modeMessage == "SMS"){
				$corps4 = "#" . $corps4 . "&";
			}
        }
    }
    if(isset($_GET['corps5'])){
        $corps5=$_GET["corps5"];
        if($corps5 != "") {
			$corps5 = wd_remove_accents($corps5);
			if($modeMessage == "GPRS"){
				$corps5 = "?" . $corps5 . "!";
			}
			else if($modeMessage == "SMS"){
				$corps5 = "#" . $corps5 . "&";
			}
        }
    }
    if(isset($_GET['corps6'])){
        $corps6=$_GET["corps6"];
        if($corps6 != "") {
			$corps6 = wd_remove_accents($corps6);
			if($modeMessage == "GPRS"){
				$corps6 = "?" . $corps6 . "!";
			}
			else if($modeMessage == "SMS"){
				$corps6 = "#" . $corps6 . "&";
			}
        }
    }

	
    $numeroAppel=$_GET["numeroAppel"];
    if($numeroAppel[0] == " ") $numeroAppel[0] = "+";

    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $datetime=$_GET["datetime"];

    /************* Recuperer idClient *******************/
    $connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
    $queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Client FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' )");
    $assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
    $idClientGpwUser = $assocGpwUser['Id_Client'];
    mysqli_close($connectGpwUser);

	
    /***********Definition Insert *****************/
    if($modeMessage == "GPRS"){
        $typeMsg = "1";
        $dest = $idTracker;
        $corpsMode = "?M!";
		$UpdData0 = 0;
    }else if($modeMessage == "SMS"){
        $typeMsg = "2";
        $dest = $numeroAppel;
        $corpsMode = "#M&";
		$UpdData0 = 0;
    }else if($modeMessage == "MEMO"){
		$typeMsg = "4";
        $dest = $idTracker;
        $corpsMode = "";
		$UpdData0 = 0;
    }else if($modeMessage == "TELTO"){
		$typeMsg = "1";
        $dest = $idTracker;
        $corpsMode = "";
		$UpdData0 = 1;
    }else if($modeMessage == "TELTOSMS"){
		$typeMsg = "2";
		$corps1 = "param telto " . $corps1;
        $dest = $numeroAppel;
        $corpsMode = "";
		$UpdData0 = 1;
    }else if($modeMessage == "NEO"){
//		$typeMsg = "1";
//		$dest = $idTracker;
$typeMsg = "2";
$dest = $numeroAppel;
        $corps2 = ",paramneo";
		$corpsMessageNumero1 = $messageNumero1;
		$corpsMessageNumero2 = $messageNumero2;
		$corpsMessageNumero3 = $messageNumero3;
        $corpsMessageNumero4 = "#";
        $corpsMode = "";
		$UpdData0 = 2;
    }else if($modeMessage == "NEOSMS"){
		$typeMsg = "2";
        $dest = $numeroAppel;
        $corps2 = ",paramneo";
		$corpsMessageNumero1 = $messageNumero1;
		$corpsMessageNumero2 = $messageNumero2;
		$corpsMessageNumero3 = $messageNumero3;
        $corpsMessageNumero4 = "#";
        $corpsMode = "";
		$UpdData0 = 2;
    }else if($modeMessage == "NEOCMDSMS"){
		$typeMsg = "2";
        $dest = $numeroAppel;
        $corps2 = ",paramneo";
		$corpsMessageNumero1 = "";
		$corpsMessageNumero2 = "";
		$corpsMessageNumero3 = "";
        $corpsMessageNumero4 = "#";
        $corpsMode = "";
		$UpdData0 = 0;
	}else if($modeMessage == "CJSMS"){
		$typeMsg = "2";
        $dest = $numeroAppel;
        $corps2 = "123465 ";
		$corpsMessageNumero1 = "";
		$corpsMessageNumero2 = "";
		$corpsMessageNumero3 = "";
        $corpsMessageNumero4 = "#";
        $corpsMode = "";
		$UpdData0 = 0;
	}

    if(isset($_GET['syncmem'])){
		$syncmem=$_GET["syncmem"];
		
		if($syncmem == 0){
			$corpsMode = "";
		}
	}


    $sujet = $_GET["sujet"];
	$corps = $corps1.$corps2.$corps3.$corps4.$corps5.$corps6.$corpsMessageNumero1.$corpsMessageNumero2.$corpsMessageNumero3.$corpsMessageNumero4.$corpsMode;

	
    echo $sujet;
//	echo "\n\nMode test : ";echo $corps;

    /************Insert into tmessages *************/
	/*
    $connection=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);

    $sql="INSERT INTO  tmessages VALUES('','".$typeMsg."','".$dest."','".$sujet."','".$corps."','".$datetime."',null,'".$idClientGpwUser."') ";

    $result = mysqli_query($connection,$sql);

    mysqli_close($connection);
	*/
	
	
    /************Insert into tmessages *************/
	try
	{
		$bdd = new PDO('mysql:host='.$ipDatabaseGpw.';dbname='.$nomDatabaseGpw, $db_user_2, $db_pass_2, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

	}
	catch (Exception $e)
	{
		die();
		//die('Erreur : ' . $e->getMessage());
	}
	
	/*on insere les données db*/
	if($typeMsg == "4"){
		$stmt = $bdd->prepare("INSERT INTO tmessages SET TypeMSG=:TypeMSG, Dest=:Dest, Sujet=:Sujet, Corps=:Corps, Date=:Date, DateEnvoi=:DateEnvoi, Client=:Client");
		$stmt->bindParam(':DateEnvoi',$datetime);
	}else
		$stmt = $bdd->prepare("INSERT INTO tmessages SET TypeMSG=:TypeMSG, Dest=:Dest, Sujet=:Sujet, Corps=:Corps, Date=:Date, Client=:Client");
	$stmt->bindParam(':TypeMSG',$typeMsg);
	$stmt->bindParam(':Dest',$dest);
	$stmt->bindParam(':Sujet',$sujet);
	$stmt->bindParam(':Corps',$corps);
	$stmt->bindParam(':Date',$datetime);
	$stmt->bindParam(':Client',$idClientGpwUser);
	$stmt->execute();
	$stmt = null;
	
	
	// Mise à jour des données de mémoire de la TELTO dans ttrackers
	if($UpdData0 == 1)
	{
		$corps = $corps1.$corps2.$corps3.$corps4.$corps5.$corps6;
		
		// Chercher "setparam "
		$indexD = strpos($corps, "setparam ");

		if($indexD == 0)
		{
			//Lecture de Datas0
			$stmt = $bdd->query("SELECT Datas0 FROM ttrackers WHERE Id_tracker=".$idTracker);
			if( $donnee = $stmt->fetch() )
			{
				// Récup de Datas0
				$Datas0 = $donnee['Datas0'];
				$stmt = null;
				
				// Retirer "setparam " du $corps
				$corps = substr($corps, 9);
				
				// Extraction du premier ParameterID
				$FirstParamLen = strpos($corps, ":", 0);
				$FirstParamId = substr($corps, 0, $FirstParamLen+1);	// $FirstParamLen+1 pour récupérer le caractère :
				
				// Extraction du dernier ParameterID
				$LastParamIdPos = strrpos($corps, ";") + 1;
				$LastParamIdLen = strrpos($corps, ":") - $LastParamIdPos;
				$LastParamId = substr($corps, $LastParamIdPos, $LastParamIdLen+1);	// $LastParamIdLen+1 pour récupérer le caractère :
				
				// Recherche dans Datas0 du premier ParameterID
				$DebutTrameStart = strpos($Datas0, $FirstParamId, 0);
				$DebutTrame = strstr($Datas0, $FirstParamId, true);
				if( $DebutTrame === FALSE )	// Si non présent
				{
					$DebutTrame = $Datas0;
					$Datas0 = $DebutTrame . $corps . ";";		// On rajoute le corps à la fin de Datas0
				}
				else						// Si présent
				{
					$FinTrameStart = strpos($Datas0, $LastParamId, 0);		// Recherche du dernier ParameterID
					if( $FinTrameStart === FALSE )								// Si non présent
					{
						$FinTrame = "";
					}
					else if($FinTrameStart < $DebutTrameStart)
					{
						$DebutTrame = "";
						$FinTrame = "";
					}
					else													// Si présent
					{
						$FinTrameStart = strpos($Datas0, ";", $FinTrameStart) + 1;		// Recherche du ; suivant dernier ParameterID auquel il est ajouté 1 pour pointer le reste de la trame $Datas0
						if( $FinTrameStart < strlen($Datas0) )								// Si la fin de la chaine n'est pas atteinte
						{								
							$FinTrame = mb_substr($Datas0, $FinTrameStart);						// extraction du reste de Datas0 se trouvant après le ;
						}
						else
						{
							$FinTrame = "";
						}
						
					}
					$Datas0 = $DebutTrame . $corps . ";" . $FinTrame;
				}
				
				
				// Mise à jour du champ Datas0
				$stmt = $bdd->prepare("UPDATE ttrackers SET Datas0=:Datas0, SynchTime0=:SynchTime0 WHERE Id_tracker=:Id_tracker");
				$stmt->bindParam(':Datas0',$Datas0);
				$stmt->bindParam(':SynchTime0',$datetime);
				$stmt->bindParam(':Id_tracker',$idTracker);
				$stmt->execute();

				// echo "\nPDOStatement::errorInfo():\n";
				// $error = $stmt->errorInfo();
				// print_r($error);
			}
			$stmt = null;
		}
	}
	else if($UpdData0 == 2)
	{
		$corps = $corps1.$corps3.$corpsMessageNumero1.$corpsMessageNumero2.$corpsMessageNumero3.$corpsMessageNumero4;
		
		//Lecture de Datas0
		$stmt = $bdd->query("SELECT Datas0 FROM ttrackers WHERE Id_tracker=".$idTracker);
		if( $donnee = $stmt->fetch() )
		{
			// Récup de Datas0
			$Datas0 = $donnee['Datas0'];
			$stmt = null;
		
			// chercher la 1ere virgule dans corps pour trouver la longueur de la commande
			$ParamLen = strpos($corps, ",", 0);
			$Param = substr($corps, 0, $ParamLen);	// extrait la commande
			
			// Recherche dans Datas0 la commande
			$DebutTrameStart = strpos($Datas0, $Param, 0);
			$DebutTrame = strstr($Datas0, $Param, true);
			if( $DebutTrame === FALSE )	// Si non présent
			{
				$DebutTrame = $Datas0;
				$Datas0 = $DebutTrame . $corps;		// On rajoute le corps à la fin de Datas0
			}
			else						// Si présent
			{
				$FinTrameStart = strpos($Datas0, "#", $DebutTrameStart);	// Recherche dans Datas0 le # suivant la commande
				if( $FinTrameStart === FALSE )									// Si non présent
				{
					$FinTrame = "";
				}
				else															// Si présent
				{
					$FinTrameStart = $FinTrameStart + 1;					// Pour pointer juste après le #
					if( $FinTrameStart < strlen($Datas0) )						// Si la fin de la chaine n'est pas atteinte
					{								
						$FinTrame = mb_substr($Datas0, $FinTrameStart);			// extraction du reste de Datas0 se trouvant après le #
					}
					else
					{
						$FinTrame = "";
					}
					
				}
				// insérer la nouvelle cmd dans Datas0 à la place de l'ancienne
				$Datas0 = $DebutTrame . $corps . $FinTrame;
			}
				
			// Mise à jour du champ Datas0
			$stmt = $bdd->prepare("UPDATE ttrackers SET Datas0=:Datas0, SynchTime0=:SynchTime0 WHERE Id_tracker=:Id_tracker");
			$stmt->bindParam(':Datas0',$Datas0);
			$stmt->bindParam(':SynchTime0',$datetime);
			$stmt->bindParam(':Id_tracker',$idTracker);
			$stmt->execute();
		}
		$stmt = null;
	}
	
	$bdd = null;
	
	
?>