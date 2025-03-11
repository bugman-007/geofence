<?php
            
        
function wd_remove_accents($str, $charset='utf-8')
{
	$str = htmlentities($str, ENT_NOQUOTES, $charset);

	$str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
	$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
	$str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

	return $str;
}


function progression($indice){
	echo "document.getElementById('pourcentage').innerHTML='".$indice."%';";
	echo "document.getElementById('barre').style.width='".$indice."%';";
	echo "</script>";
	
	ob_flush();
	flush();
	ob_flush();
	flush();
}

function LocalTimeToGmtTime($date,$timezone) {
    $local_time = $date;
    date_default_timezone_set($timezone);
    $timestamp_in_localtime = strtotime($local_time);
    date_default_timezone_set("UTC");
    $gmt_time = date("Y-m-d H:i:s", $timestamp_in_localtime);
    //date_default_timezone_set('Asia/Calcutta');
    return $gmt_time;
}

function lireDirectionVitesse($direction, $vitesse){
	$positionFleche="";

	if($vitesse == 0){
		if(($direction >= 0) && ($direction <= 22.5)){
			$positionFleche = "fleche0degRouge";
		}
		if(($direction >= 337.5) && ($direction <= 360)){
			$positionFleche = "fleche0degRouge";
		}
		if(($direction > 22.5) && ($direction < 67.5)){
			$positionFleche = "fleche45degRouge";
		}
		if(($direction >= 67.5) && ($direction <= 112.5)) {
			$positionFleche = "fleche90degRouge";
		}
		if(($direction > 112.5) && ($direction < 157.5)){
			$positionFleche = "fleche135degRouge";
		}
		if(($direction >= 157.5) && ($direction <= 202.5)){
			$positionFleche = "fleche180degRouge";
		}
		if(($direction > 202.5) && ($direction < 247.5)){
			$positionFleche = "fleche225degRouge";
		}
		if(($direction >= 247.5) && ($direction <= 292.5)){
			$positionFleche = "fleche270degRouge";
		}
		if(($direction > 292.5) && ($direction < 337.5)){
			$positionFleche = "fleche315degRouge";
		}
	}else if($vitesse <= 10){
		if(($direction >= 0) && ($direction <= 22.5)){
			$positionFleche = "fleche0degJaune";
		}
		if(($direction >= 337.5) && ($direction <= 360)){
			$positionFleche = "fleche0degJaune";
		}
		if(($direction > 22.5) && ($direction < 67.5)){
			$positionFleche = "fleche45degJaune";
		}
		if(($direction >= 67.5) && ($direction <= 112.5)) {
			$positionFleche = "fleche90degJaune";
		}
		if(($direction > 112.5) && ($direction < 157.5)){
			$positionFleche = "fleche135degJaune";
		}
		if(($direction >= 157.5) && ($direction <= 202.5)){
			$positionFleche = "fleche180degJaune";
		}
		if(($direction > 202.5) && ($direction < 247.5)){
			$positionFleche = "fleche225degJaune";
		}
		if(($direction >= 247.5) && ($direction <= 292.5)){
			$positionFleche = "fleche270degJaune";
		}
		if(($direction > 292.5) && ($direction < 337.5)){
			$positionFleche = "fleche315degJaune";
		}
	}else if($vitesse > 10){
		if(($direction >= 0) && ($direction <= 22.5)){
			$positionFleche = "fleche0deg";
		}
		if(($direction >= 337.5) && ($direction <= 360)){
			$positionFleche = "fleche0deg";
		}
		if(($direction > 22.5) && ($direction < 67.5)){
			$positionFleche = "fleche45deg";
		}
		if(($direction >= 67.5) && ($direction <= 112.5)) {
			$positionFleche = "fleche90deg";
		}
		if(($direction > 112.5) && ($direction < 157.5)){
			$positionFleche = "fleche135deg";
		}
		if(($direction >= 157.5) && ($direction <= 202.5)){
			$positionFleche = "fleche180deg";
		}
		if(($direction > 202.5) && ($direction < 247.5)){
			$positionFleche = "fleche225deg";
		}
		if(($direction >= 247.5) && ($direction <= 292.5)){
			$positionFleche = "fleche270deg";
		}
		if(($direction > 292.5) && ($direction < 337.5)){
			$positionFleche = "fleche315deg";
		}
	}
	
	$positionFleche = "<img src='../../assets/img/ICONES/".$positionFleche.".ico'>";

	return $positionFleche;
}

function versionBalise($odometre){
	$version1 = substr($odometre,0,4);
	$nomVersion = "";
	
    switch ($version1) {
		case "3006":
			$nomVersion = "GEONEO 3G";		// Geofence CUBEV2
            break;
		case "3370":
			$nomVersion = "GEONEO 3G";		// Geofence NEO 3G
            break;
		case "8079":
		case "8045":
			$nomVersion = "GEONEO 4G";		// Geofence NEO 3G
			break;
		case "2205":
			$nomVersion = "GEONANO";		// Geofence CJ
            break;
		case "7003":
			$nomVersion = "GEONEO";			// Geofence NEO
            break;
		case "7201":
			$nomVersion = "GEOSOLO";		// Geofence SOLO
            break;
		case "8000":
			$nomVersion = "GEOSOLAIRE";		// Geofence SOLAR
            break;
        default:
			$version = substr($odometre,0,2);
			$firmware = $odometre[2].".".$odometre[3];
			switch($version){
				default:
					$nomVersion = "Inconnue";
					break;
				case "11":
					$nomVersion = "SC200G v".$firmware;
					break;
				case "17":
					$nomVersion = "SCx00J v".$firmware;		// SC400J et premières SC500J produites avant rectification du n° de type
					break;
				case "18":
					$nomVersion = "SC500J v".$firmware;
					break;
				case "19":
					$nomVersion = "SC500JS v".$firmware;
					break;
				case "20":
					$nomVersion = "GEOTRACK";				// ECO TELTO Geofence
					break;
				case "23":
					$nomVersion = "SC200M v".$firmware;
					break;
				case "24":
					$nomVersion = "SC300x v".$firmware;
					break;
				case "31":
					$nomVersion = "SC300M v".$firmware;
					break;
				case "32":
					$nomVersion = "SC300MB v".$firmware;
					break;
				case "33":
					$nomVersion = "SC300ME v".$firmware;
					break;
				case "41":
					$nomVersion = "SC300G v".$firmware;
					break;
				case "42":
					$nomVersion = "SC300GB v".$firmware;
					break;
				case "52":
					$nomVersion = "SC300E v".$firmware;
					break;
				case "43":
					$nomVersion = "SC400MB v".$firmware;
					break;
//				case "44":
//					$nomVersion = "SC300P v".$firmware;
//					break;
				case "45":
					$nomVersion = "SC400M/ME v".$firmware;
					break;
				case "46":
					$nomVersion = "SC400n v".$firmware;		// SC400u + SC400n + SC500n
					break;
				case "47":
					$nomVersion = "GEOFLEET v".$firmware;
					break;
				case "48":
					$nomVersion = "GEOTRACK v".$firmware;	// Geofence 400LC
					break;
				case "49":
					$nomVersion = "SC400PM v".$firmware;	// SC400PM
					break;
				case "50":
					$nomVersion = "SC500MB v".$firmware;
					break;
				case "51":
					$nomVersion = "SC500MB v".$firmware;
					break;
//				case "52":
//					$nomVersion = "SC400BLE v".$firmware;
//					break;
				case "53":
					$nomVersion = "GEOCUBE v".$firmware;	// Geofence
					break;
				case "54":
					$nomVersion = "SC500H v".$firmware;
					break;
				case "55":
					$nomVersion = "600St v".$firmware;
					break;
				case "56":
					$nomVersion = "SC HYBRID+ v".$firmware;
					break;
				case "57":
					$nomVersion = "600Av v".$firmware;
					break;
			}
	}
	return $nomVersion;
}

// cette fonction doit disparaitre quand le decodage status façon Christophe sera remplacé partout.
function statutEncode($statut){
    
	$statutRecup = sprintf("%u", $statut);
      
	$statutEncode =  array();
	$puissance = 31;

	while($puissance >= 0){
		if( pow(2,$puissance) > $statutRecup ){ 	
			array_push($statutEncode,"0");
		}else{
			$statutRecup = $statutRecup - pow(2,$puissance);
			array_push($statutEncode,"1");
		}
		$puissance --;
	}

	return $statutEncode;
}

// cette fonction doit disparaitre quand le decodage status façon Christophe sera remplacé partout.
function lireBatterie($b0,$b1,$b2,$b3){
	$niveauBatterie = "";
	$pourcentageBatterie = "";
	$niveauBatterie = (8*intval($b3))+(4*intval($b2))+(2*intval($b1))+intval($b0);
	$pourcentageBatterie = (100*$niveauBatterie)/15 ;

	return round($pourcentageBatterie)."%";
}

// Nouvelle variante qui doit remplacer function lireBatterie($b0,$b1,$b2,$b3) 
function DecodeBatLvl($Pos_Statut){
	$pourcentageBatterie = 0;
	
	// Obtention des 4 bits du niveau batterie
	if($Pos_Statut & 0x00800000)		$pourcentageBatterie = 8;	// b3 (poids fort)
	if($Pos_Statut & 0x00400000)		$pourcentageBatterie = $pourcentageBatterie + 4;	// b2
	if($Pos_Statut & 0x20000000)		$pourcentageBatterie = $pourcentageBatterie + 2;	// b1
	if($Pos_Statut & 0x40000000)		$pourcentageBatterie = $pourcentageBatterie + 1;	// b0 (poids faible)
	
	// Calcul du pourcentage à partir des 4 bits de niveau batterie
	$pourcentageBatterie = (100*$pourcentageBatterie)/15 ;

	return round($pourcentageBatterie)."%";
}

//********************************************************** TRAITEMENT STATUT **********************************************************
//
// Decode le statut pour la table pos
//
//***************************************************************************************************************************************
function DecodeStatus($Pos_Statut, $Pos_Odometre, $Pos_Key, $Statut2, $BattInt, $BattExt, $Alim, $TypeServer)
{
	
	$nomVersionBalise = versionBalise($Pos_Odometre);
	$TypeBalise = substr($Pos_Odometre,0,4);
	
	if(($TypeBalise!="3006")&&($TypeBalise!="3370")&&($TypeBalise!="8045")&&($TypeBalise!="8079")&&($TypeBalise!="2205")&&($TypeBalise!="7003")&&($TypeBalise!="7201")&&($TypeBalise!="8000")&&($TypeBalise!="2201")&&($TypeBalise!="2801")&&($TypeBalise!="2601"))
	{
		$TypeBalise = substr($Pos_Odometre,0,2);
		$FirmVer = substr($Pos_Odometre,2,2);
	}
	
	//Brouilleur
	if( (substr($nomVersionBalise,0,5) == "SC200") || (substr($nomVersionBalise,0,5) == "SC300") || ($nomVersionBalise.substr(0,3) == "600") || ($TypeBalise == "56") || ($TypeBalise == "3006") ||  ($TypeBalise == "3370") || ($TypeBalise == "3600") || ($TypeBalise == "7003") || ($TypeBalise == "7201") || ($TypeBalise == "8000") ){
		$statutBrouilleur = "";
	}else{
		if($Pos_Statut & 0x10000000){
			$statutBrouilleur = "(" . _('brouille') . ") ";
		}else{
				$statutBrouilleur = "(" . _('nonbrouille') . ") ";
		}
	}
	
	//NIVEAU RESEAU GSM
	$niveauReseau = 0;
	if($Pos_Statut & 0x02000000){
		$niveauReseau = 2;
	}
	if($Pos_Statut & 0x01000000){
		$niveauReseau = $niveauReseau + 1;
	}
	
	//ALARM 2
	if($Pos_Statut & 0x00000002){
		$alarm1	= "<br>";
		if( ($TypeBalise == "3006") || ($TypeBalise == "3370") || ($TypeBalise == "8045") || ($TypeBalise == "8079") || ($TypeBalise == "7003") || ($TypeBalise == "7201") ){			// SC NEO, SC SOLO
			$alarm2	= "<img src='../../assets/img/ICONES/alarmeMulti.ico'> <b>"._('configuration_alarme')." Couvercle</b>";			// traduction manquante
		}else if($TypeBalise == "47"){
			$alarm2	= "<img src='../../assets/img/ICONES/alarme2.ico'> <b>Sous surv.</b>";			
		}else{
			$alarm2	= "<img src='../../assets/img/ICONES/alarme2.ico'> <b>"._('configuration_alarme')." 2 active</b>";			
		}
	}else {
		$alarm1	= "";
		$alarm2	= "";
	}
	
	
	//ALARM 1
	if($Pos_Statut & 0x00000001){
		if( ($TypeBalise == "56") || ($TypeBalise == "57") || ($TypeBalise == "53") || ($TypeBalise == "3370") || ($TypeBalise == "8045") || ($TypeBalise == "8079") || ($TypeBalise == "7003") || ($TypeBalise == "7201") ){	// HYBRID+, 600Av, SC CUBE, SC NEO, SC SOLO
			$alarm1	= "<br><img src='../../assets/img/ICONES/alarmeMulti.ico'> <b>"._('configuration_alarme')." Arrachement</b>";			// traduction manquante
		}else if($TypeBalise == "47"){
			$alarm1	= "<br><img src='../../assets/img/ICONES/alarmeMulti.ico'> <b>"._('configuration_alarme')." Porte</b>";
		}else{
			$alarm1	= "<br><img src='../../assets/img/ICONES/alarme1.ico'> <b>"._('configuration_alarme')." 1 active</b>";
		}
		if($alarm2	!= "")
			$alarm1	.= " - ";
	}
	
	
	// CONTACT
	if($Pos_Statut & 0x00000004){
		$statutSTOP = "";
	}else {
		$statutSTOP = "STOP - ";
	}

	//VIBRATION
	if($Pos_Statut & 0x00000040){
		$statutVIB = "VIB";
	}else{
		$statutVIB = _('pas')." VIB";
	}
	
	// GPS Reception valide ?
	if($Pos_Statut & 0x00000020){
		$GPS = "<b>".$Pos_Odometre[4]."/".$Pos_Odometre[5].".".$Pos_Odometre[6]."</b>";
	}else{
		//POSITION GSM ?
		if(($TypeServer == 1) && ($Pos_Key == 1) && ($Statut2 & 0x01)) {
			$GPS = "<b>PositionGSM</b>";	// traduction manquante
		}else{
			$GPS = "<b>No</b>";
		}
	}
	
	
	
	//ALIM
	if(($TypeBalise == "3006") || ($TypeBalise == "3370") || ($TypeBalise == "8045") || ($TypeBalise == "8079") || ($TypeBalise == "7003") || ($TypeBalise == "8000")|| ($TypeBalise == "2205")|| ($TypeBalise == "2201")|| ($TypeBalise == "2801")|| ($TypeBalise == "2601"))		// NEO 3G & NEO
	{
		if($Alim > 0 ){
			$alimEtBatterie = " - En charge: <b>".round($BattInt)."%</b>";			// traduction manquante
		}else{
			$alimEtBatterie = " - B.Int: <b>".round($BattInt)."%</b>";				// traduction manquante
		}
	}
	else if($TypeBalise == "7201")								// SOLO
	{	
			$alimEtBatterie = "";													// traduction manquante
	}
	else if($TypeBalise == "20")								// SC ECO
	{
		if($Alim > 0 ){
			$volt = ($Alim * 0.23) + 5;
			$volt = $volt * 10;		// Pour arrondir 1 chiffre après la virgule
			$volt = round($volt);	// Pour arrondir 1 chiffre après la virgule
			$volt = $volt / 10;		// Pour arrondir 1 chiffre après la virgule
		}else{
			$volt = 0;
		}
		$alimEtBatterie = "<br>"._('alimext').": <b>".$volt."V</b> - B.Int: <b>".round($BattInt)."%</b>";		// traduction manquante
		//$alimEtBatterie = "<br>"._('alimext').": <b>".$volt."V</b>";		// traduction manquante
		
	}
	else if( ($TypeServer == 1) && ($Pos_Key == 1) && ( ($TypeBalise == "55") || ($TypeBalise == "56") || ($TypeBalise == "57") || ($TypeBalise == "17") || ($TypeBalise == "18") || ($TypeBalise == "19") || ($TypeBalise == "48") || ($TypeBalise == "53")&&($FirmVer != "01") ))		// (600St, HYBRID+, 600Av, SCx00J, SC500J, SC500JS, SC400LC, CUBE v02+ )
	{
		/*if($TypeBalise == "53")					// SC CUBE
		{
			$alimEtBatterie = " - Bat.: <b>".round($BattInt)."%</b>";				// traduction manquante
		}
		else*/ if($TypeBalise == "19")			// SC500JS
		{
			$volt = ($Alim * 56)/1000;
			$volt = $volt * 10;		// Pour arrondir 1 chiffre après la virgule
			$volt = round($volt);	// Pour arrondir 1 chiffre après la virgule
			$volt = $volt / 10;		// Pour arrondir 1 chiffre après la virgule
			$alimEtBatterie = "<br>Solaire: <b>".$volt."V</b> - Bat.1: <b>".round($BattExt)."%</b> - Bat.2: <b>".round($BattInt)."%</b>";		// traduction manquante
		}
		else									// 600St, 600Av, SCx00J, SC500J, SC400LC
		{
			if($Alim > 0 ){
				$volt = ($Alim * 0.253) + 5.5;
				$volt = $volt * 10;		// Pour arrondir 1 chiffre après la virgule
				$volt = round($volt);	// Pour arrondir 1 chiffre après la virgule
				$volt = $volt / 10;		// Pour arrondir 1 chiffre après la virgule
			}else{
				$volt = 0;
			}
			
			if($TypeBalise == "48"){			// SC400LC
				$alimEtBatterie = "<br>"._('alimext').": <b>".$volt."V</b> - B.Int: <b>".round($BattInt)."%</b>";		// traduction manquante
			}else{
				$alimEtBatterie = "<br>"._('alimext').": <b>".$volt."V</b> - B.Ext: <b>".round($BattExt)."%</b> - B.Int: <b>".round($BattInt)."%</b>";		// traduction manquante
			}
		}
	}
	else
	{
		$niveauBat = DecodeBatLvl($Pos_Statut);
		if($Pos_Statut & 0x00020000){
			$alimEtBatterie = " - "._('alimext');
		}else if($Pos_Statut & 0x00040000){
			$alimEtBatterie = " - B.Ext <b>".$niveauBat."</b>";				// traduction manquante
		}else if($Pos_Statut & 0x00080000){
			$alimEtBatterie = " - B.Int <b>".$niveauBat."</b>";				// traduction manquante
		}else{
			$alimEtBatterie = " - "._('alimbasse')." <b>".$niveauBat."</b>";
		}
	}
	
	
		$DecodedStatus = "<b>".$statutSTOP.$statutVIB."</b> - GSM <b>".$statutBrouilleur.$niveauReseau."/3</b> - GPS ".$GPS.$alimEtBatterie."".$alarm1."".$alarm2;
	
	//return $DecodedStatus.$TypeBalise."/".$TypeServer."/".$Pos_Key;
	return $DecodedStatus;
}

//***************************************************************************************************************************************
// retourne l'icone pour la table positions en fonction de la réception GPS, de l'état stop, de la vitesse et la direction.
//
// 1 = table pos (si fonction appelée par une page php)
// 2 = carto (si fonction appelée par une page php elle même appelée par ajax dans une fonction javascript )
//
//***************************************************************************************************************************************
function IconeBalise($Pos_Statut, $Pos_Vitesse, $Pos_Direction){
	
	// GPS Reception valide ?
	if($Pos_Statut & 0x00000020){
		// Etat deplacement ?
		if($Pos_Statut & 0x00000004){
			$cbalise = lireDirectionVitesse($Pos_Direction, round($Pos_Vitesse));
		}else {
			$cbalise = "<img src='../../assets/img/ICONES/stop16.ico'>";
		}
	}else{
		// Etat deplacement ?
		if($Pos_Statut & 0x00000004){
			$cbalise = "<img src='../../assets/img/ICONES/noGPS.ico'>";
		}else{
			$cbalise = "<img src='../../assets/img/ICONES/noGPS_Stop.ico'>";
		}
	}
	
	return $cbalise;
}

//***************************************************************************************************************************************
//Icone brouilleur
//***************************************************************************************************************************************
function IconeBrouilleur($Pos_Odometre,$Pos_Statut)
{
	$nomVersionBalise = versionBalise($Pos_Odometre);
	$TypeBalise = substr($Pos_Odometre,0,4);
	
	if(($TypeBalise!= "3006") && ($TypeBalise!= "3370") || ($TypeBalise == "8045") && ($TypeBalise!= "8079") && ($TypeBalise!= "2205") && ($TypeBalise!= "7003") && ($TypeBalise!= "7201") && ($TypeBalise!= "8000")&& ($TypeBalise!= "2201")&& ($TypeBalise!= "2801")&& ($TypeBalise!= "2601"))
	{
		$TypeBalise = substr($Pos_Odometre,0,2);
		//$FirmVer = substr($Pos_Odometre,2,2);
	}
	
	if( (substr($nomVersionBalise,0,5) == "SC200") || (substr($nomVersionBalise,0,5) == "SC300") || (substr($nomVersionBalise,0,3) == "600") || ($TypeBalise == "56") || ($TypeBalise == "3006") || ($TypeBalise == "3370") || ($TypeBalise == "3600") || ($TypeBalise == "7003") || ($TypeBalise == "7201") || ($TypeBalise == "8000") ){
		$brouilleur = "<td><img src='../../assets/img/ICONES/nonBrouillage.ico'></td>";
	}else{
		if($Pos_Statut & 0x10000000){
			$brouilleur = "<td><img src='../../assets/img/ICONES/brouillage.ico'></td>";
		}else{
			$brouilleur = "<td><img src='../../assets/img/ICONES/nonBrouillage.ico'></td>";
		}
	}
	
	return $brouilleur;
}

//***************************************************************************************************************************************
//Icone Defaut Alim/BatExt et BatInt
//***************************************************************************************************************************************
function IconeDefautAlim($Pos_Statut)
{
	if( ($Pos_Statut & 0x00000018) == 0x00000018){		// Defaut alim et batterie à la fois
		$defaultAlim = "<img src='../../assets/img/ICONES/alarmeMulti.ico'>";
	}else if($Pos_Statut & 0x00000010){					// Defaut batterie seul
		$defaultAlim = "<img src='../../assets/img/ICONES/alarmeBatterie.ico'>";
	}else if($Pos_Statut & 0x00000008){					// Defaut alim seul
		$defaultAlim = "<img src='../../assets/img/ICONES/alarmeAlim3232.ico' width='17' height='17'>";
	}else{
		$defaultAlim = "";
	}
	
	return $defaultAlim;
}


?>