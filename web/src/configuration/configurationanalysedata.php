<?php
session_start();
$_SESSION['CREATED'] = time();
?>
<?php
	/*
	* Recuperer la configration data data1 de la balise  pour javascript
	*
	*/
	function ajoutechaine($chaine){
		if(strlen($chaine)<2){
			return $chaine = "0".$chaine;
		}else{
			return $chaine;
		}	
	}
	
	function extractEmailsFromString($sChaine) {
	  if(false !== preg_match_all('`\w(?:[-_.]?\w)*@\w(?:[-_.]?\w)*\.(?:[a-z]{2,4})`', $sChaine, $aEmails)) {
		if(is_array($aEmails[0]) && sizeof($aEmails[0])>0) {
		  return array_unique($aEmails[0]);
		}
	  }
	  return null;
	}
	
	function identifierData($data, $data1){
		
		print("NVM												=> ".substr($data, 0,5));
		print("<br> Identifiant 								=> ".hexdec(bin2hex(strrev(substr($data, 5,4)))));
		print("<br> Latitude 									=> ".(hexdec(bin2hex(strrev(substr($data, 9,4)))))/10000);
		print("<br> Longitude 									=> ".(hexdec(bin2hex(strrev(substr($data, 13,4)))))/10000);
		print("<br> Direction  									=> ".(hexdec(bin2hex(strrev(substr($data, 17,4)))))/100);
		print("<br> Speed  										=> ".hexdec(bin2hex(strrev(substr($data, 21,1)))));
		print("<br> Date  										=> ".hexdec(bin2hex(strrev(substr($data, 22,4)))));
		print("<br> Time  										=> ".hexdec(bin2hex(strrev(substr($data, 26,4)))));
		print("<br> Odometer									=> ".(hexdec(bin2hex(strrev(substr($data, 30,4)))))/1000);
		print("<br> Key  										=> ".hexdec(bin2hex(strrev(substr($data, 34,4)))));
		print("<br> Tel  Alarm									=> ".hexdec(substr($data, 38,1))); 
		print("<br> Time RST									=> ".hexdec(bin2hex(strrev(substr($data, 39,4)))));
		print("<br> Save GPS trajet								=> ".hexdec(bin2hex(strrev(substr($data, 43,4)))));
		print("<br> Save GPS arret								=> ".hexdec(bin2hex(strrev(substr($data, 47,4)))));
		print("<br> Mode Sleep									=> ".hexdec(bin2hex(strrev(substr($data, 51,4)))));
		print("<br> Index DB									=> ".hexdec(bin2hex(strrev(substr($data, 55,4)))));
		print("<br> Mode Relais									=> ".hexdec(substr($data, 59,1)));
		print("<br> Mode GPRS									=> ".hexdec(bin2hex(strrev(substr($data, 60,4)))));
		print("<br> Mode GPS									=> ".hexdec(bin2hex(strrev(substr($data, 64,4)))));
		print("<br> Timeout GPS									=> ".hexdec(bin2hex(strrev(substr($data, 68,4)))));
		print("<br> WakeUp GPS									=> ".hexdec(bin2hex(strrev(substr($data, 72,4)))));
		print("<br> MODE_DEBUG									=> ".hexdec(bin2hex(strrev(substr($data, 76,1)))));
		print("<br> Filtrage GPS								=> ".hexdec(bin2hex(strrev(substr($data, 77,1)))));
		print("<br> TIME VIB FOR APC START						=> ".hexdec(bin2hex(strrev(substr($data, 78,4)))));
		print("<br> TIME VIB FOR APC STOP						=> ".hexdec(bin2hex(strrev(substr($data, 82,4)))));
		print("<br> TIMEOUT GPRS Close tcp						=> ".hexdec(bin2hex(strrev(substr($data, 86,4)))));
		print("<br> TIME MIN VIB								=> ".hexdec(bin2hex(strrev(substr($data, 90,1)))));
		print("<br> CALL TIME REALTIME							=> ".hexdec(bin2hex(strrev(substr($data, 91,1)))));
		print("<br> TPS REBOOT GSM								=> ".hexdec(bin2hex(strrev(substr($data, 92,2)))));
		print("<br> TIMING MODE GPS 							=> ".hexdec(bin2hex(strrev(substr($data, 94,4)))));
		print("<br> REALTIME START								=> ".hexdec(bin2hex(strrev(substr($data, 98,2)))));
		print("<br> TIMING MODE GPRS 							=> ".hexdec(bin2hex(strrev(substr($data, 100,4)))));
		print("<br> MODE_APC									=> ".hexdec(bin2hex(strrev(substr($data, 104,1)))));
		print("<br> STATE_APC									=> ".hexdec(bin2hex(strrev(substr($data, 105,1)))));
		print("<br> MODE_RELAIS									=> ".hexdec(bin2hex(strrev(substr($data, 106,1)))));
		print("<br> LAST REL		 							=> ".hexdec(bin2hex(strrev(substr($data, 107,1)))));
		print("<br> SPEED APC									=> ".hexdec(bin2hex(strrev(substr($data, 108,1)))));
		print("<br> TIME NO VIB GPS								=> ".hexdec(bin2hex(strrev(substr($data, 109,2)))));
		print("<br> OLD STATE BAT								=> ".hexdec(bin2hex(strrev(substr($data, 111,1)))));
		print("<br> OLD STATE ALIM								=> ".hexdec(bin2hex(strrev(substr($data, 112,1)))));
		print("<br> MODE_BAT_INT								=> ".hexdec(bin2hex(strrev(substr($data, 113,1)))));
		print("<br> CFG AL1 									=> ".hexdec(bin2hex(strrev(substr($data, 114,1)))));
		print("<br> OLD AL1										=> ".hexdec(bin2hex(strrev(substr($data, 115,1)))));
		print("<br> SMS AL1										=> ".hexdec(bin2hex(strrev(substr($data, 116,1)))));
		print("<br> AL1 TIME BETWEEN							=> ".hexdec(bin2hex(strrev(substr($data, 117,2))))); 
		print("<br> CFG AL2 									=> ".hexdec(bin2hex(strrev(substr($data, 119,1)))));
		print("<br> OLD AL2										=> ".hexdec(bin2hex(strrev(substr($data, 120,1)))));
		print("<br> SMS AL2										=> ".hexdec(bin2hex(strrev(substr($data, 121,1)))));
		print("<br> AL2 TIME BETWEEN							=> ".hexdec(bin2hex(strrev(substr($data, 122,2)))));
		print("<br> REALTIME AL1								=> ".hexdec(bin2hex(strrev(substr($data, 124,2)))));
		print("<br> REALTIME AL2								=> ".hexdec(bin2hex(strrev(substr($data, 126,2)))));
		print("<br> CFG ALA APC									=> ".hexdec(bin2hex(strrev(substr($data, 128,1)))));
		print("<br> SMS ALA APC									=> ".hexdec(bin2hex(strrev(substr($data, 129,1)))));
		print("<br> CFG ALA BAT									=> ".hexdec(bin2hex(strrev(substr($data, 130,1)))));
		print("<br> SMS ALA BAT 								=> ".hexdec(bin2hex(strrev(substr($data, 131,1)))));
		print("<br> CFG ALA ALIM								=> ".hexdec(bin2hex(strrev(substr($data, 132,1)))));
		print("<br> SMS ALA ALIM								=> ".hexdec(bin2hex(strrev(substr($data, 133,1)))));
		print("<br> SEUIL BATTERY								=> ".hexdec(bin2hex(strrev(substr($data, 134,1))))); //seuil bat
		print("<br> BAT TIME BETWEEN							=> ".hexdec(bin2hex(strrev(substr($data, 135,2)))));
		print("<br> ALIM TIME BETWEEN							=> ".hexdec(bin2hex(strrev(substr($data, 137,2)))));
		print("<br> tryPDP			 							=> ".hexdec(bin2hex(strrev(substr($data, 139,4)))));		
		print("<br> tryDNS			 							=> ".hexdec(bin2hex(strrev(substr($data, 143,4)))));
		print("<br> tryGPS			 							=> ".hexdec(bin2hex(strrev(substr($data, 147,4)))));		
		print("<br> trySMS										=> ".hexdec(bin2hex(strrev(substr($data, 151,4)))));
		print("<br> tm SMS			 							=> ".hexdec(bin2hex(strrev(substr($data, 155,4)))));
		print("<br> tryTCP			 							=> ".hexdec(bin2hex(strrev(substr($data, 159,4)))));
		print("<br> APC BETWEEN									=> ".hexdec(bin2hex(strrev(substr($data, 163,2)))));
		print("<br> TELEPHONE 1									=> ".substr($data, 165,20));
		print("<br> TELEPHONE 2									=> ".substr($data, 185,20));
		print("<br> TELEPHONE 3									=> ".substr($data, 205,20));
		print("<br> TELEPHONE 4									=> ".substr($data, 225,20));
		print("<br> MESSAGE APPARITION ALARME 1					=> ".substr($data, 245,30));
		print("<br> MESSAGE	DISPARITION ALARME 1				=> ".substr($data, 275,30));
		print("<br> MESSAGE	APPARITION ALARME 2					=> ".substr($data, 305,30));
		print("<br> MESSAGE	DISPARITION ALARME 2				=> ".substr($data, 335,30));
		print("<br> MESSAGE	APPARITION APC 						=> ".substr($data, 365,30));
		print("<br> MESSAGE	DISPARITION APC 					=> ".substr($data, 395,30));
		print("<br> MESSAGE	BATTERY FAIBLE ALARME BAT 			=> ".substr($data, 425,30));	
		print("<br> MESSAGE	BATTERY OK ALARME BAT 				=> ".substr($data, 455,30));	
		print("<br> MESSAGE	ALIM DEFAULT ALARME ALIM 			=> ".substr($data, 485,30));
		print("<br> MESSAGE	ALIM OK ALARME ALIM 				=> ".substr($data, 515,30));
		print("<br> NO/NF ALARM1								=> ".hexdec(bin2hex(strrev(substr($data, 545,1)))));
		print("<br> NO/NF ALARM2								=> ".hexdec(bin2hex(strrev(substr($data, 546,1)))));
		print("<br> NO/NF APC									=> ".hexdec(bin2hex(strrev(substr($data, 547,1)))));
		print("<br> NO/NF RELAIS 								=> ".hexdec(bin2hex(strrev(substr($data, 548,1)))));
		print("<br> CFG GEO 1									=> ".hexdec(bin2hex(strrev(substr($data, 549,1)))));
		print("<br> SMS GEO 1									=> ".hexdec(bin2hex(strrev(substr($data, 550,1)))));
		print("<br> CFG GEO 2									=> ".hexdec(bin2hex(strrev(substr($data, 551,1)))));
		print("<br> SMS GEO 2									=> ".hexdec(bin2hex(strrev(substr($data, 552,1)))));
		print("<br> CFG GEO 3									=> ".hexdec(bin2hex(strrev(substr($data, 553,1)))));
		print("<br> SMS GEO 3									=> ".hexdec(bin2hex(strrev(substr($data, 554,1)))));
		print("<br> CFG GEO 4									=> ".hexdec(bin2hex(strrev(substr($data, 555,1)))));
		print("<br> SMS GEO 4									=> ".hexdec(bin2hex(strrev(substr($data, 556,1)))));
		print("<br> CFG GEO 5									=> ".hexdec(bin2hex(strrev(substr($data, 557,1)))));
		print("<br> SMS GEO 5									=> ".hexdec(bin2hex(strrev(substr($data, 558,1)))));
		print("<br> MODE_RING									=> ".hexdec(bin2hex(strrev(substr($data, 559,1)))));
		print("<br> DST PARK GEO								=> ".hexdec(bin2hex(strrev(substr($data, 560,2)))));
		print("<br> ALIM EXT									=> ".hexdec(bin2hex(strrev(substr($data, 562,1)))));
		print("<br> CFG MODE_PARK								=> ".hexdec(bin2hex(strrev(substr($data, 563,1)))));
		print("<br> LAPS MODE_PARK								=> ".hexdec(bin2hex(strrev(substr($data, 564,2)))));
		print("<br> RT MODE_PARK								=> ".hexdec(bin2hex(strrev(substr($data, 566,2)))));
		print("<br> STATE MODE_PARK								=> ".hexdec(bin2hex(strrev(substr($data, 568,1)))));
		print("<br> SMS MODE_PARK								=> ".hexdec(bin2hex(strrev(substr($data, 569,1)))));
		print("<br> NB POS MODEPARK								=> ".hexdec(bin2hex(strrev(substr($data, 570,1)))));
		print("<br> VIB_MODEPARK								=> ".hexdec(bin2hex(strrev(substr($data, 571,2)))));
		print("<br> SPEED_MODEPARK								=> ".hexdec(bin2hex(strrev(substr($data, 573,1)))));
		print("<br> MODE_PARKING								=> ".hexdec(bin2hex(strrev(substr($data, 574,1)))));
		print("<br> MESSAGE	APPARITION ALARME PARKING			=> ".substr($data, 575,30));
		print("<br> MESSAGE	RETABLISSEMENT ALARME PARKING		=> ".substr($data, 605,30));
		print("<br> MESSAGE	GEO 1 APPARITION					=> ".substr($data, 635,30));
		print("<br> MESSAGE	GEO 1 DISPARITION					=> ".substr($data, 665,30));
		print("<br> MESSAGE GEO 2 APPARITION					=> ".substr($data, 695,30));
		print("<br> MESSAGE	GEO 2 DISPARITION					=> ".substr($data, 725,30));
		print("<br> MESSAGE GEO 3 APPARITION					=> ".substr($data, 755,30));
		print("<br> MESSAGE	GEO 3 DISPARITION					=> ".substr($data, 785,30));
		print("<br> MESSAGE GEO 4 APPARITION					=> ".substr($data, 815,30));
		print("<br> MESSAGE	GEO 4 DISPARITION					=> ".substr($data, 845,30));
		print("<br> MESSAGE GEO 5 APPARITION					=> ".substr($data, 875,30));
		print("<br> MESSAGE	GEO 5 DISPARITION					=> ".substr($data, 905,30));
		// print("<br> LATITUDE MAX 1			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 935,4)))))))));
		// print("<br> LATITUDE MIN 1			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 939,4)))))))));
		// print("<br> LONGITUDE MAX 1			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 943,4)))))))));
		// print("<br> LONGITUDE MIN 1			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 947,4)))))))));
		// print("<br> LATITUDE MAX 2			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 951,4)))))))));
		// print("<br> LATITUDE MIN 2			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 955,4)))))))));
		// print("<br> LONGITUDE MAX 2			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 959,4)))))))));
		// print("<br> LONGITUDE MIN 2			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 963,4)))))))));
		// print("<br> LATITUDE MAX 3			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 967,4)))))))));
		// print("<br> LATITUDE MIN 3			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 971,4)))))))));
		// print("<br> LONGITUDE MAX 3			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 975,4)))))))));
		// print("<br> LONGITUDE MIN 3			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 979,4)))))))));
		// print("<br> LATITUDE MAX 4			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 983,4)))))))));
		// print("<br> LATITUDE MIN 4			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 987,4)))))))));
		// print("<br> LONGITUDE MAX 4			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 991,4)))))))));
		// print("<br> LONGITUDE MIN 4			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 995,4)))))))));
		// print("<br> LATITUDE MAX 5			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 999,4)))))))));
		// print("<br> LATITUDE MIN 5			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 1003,4)))))))));
		// print("<br> LONGITUDE MAX 5			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 1007,4)))))))));
		// print("<br> LONGITUDE MIN 5			 					=> ".reset(unpack("l", pack("l", (hexdec(bin2hex(strrev(substr($data, 1011,4)))))))));
		print("<br> LATITUDE MAX 1			 					=> ".hexdec(bin2hex(strrev(substr($data, 935,4)))));
		print("<br> LATITUDE MIN 1			 					=> ".hexdec(bin2hex(strrev(substr($data, 939,4)))));
		print("<br> LONGITUDE MAX 1			 					=> ".hexdec(bin2hex(strrev(substr($data, 943,4)))));
		print("<br> LONGITUDE MIN 1			 					=> ".hexdec(bin2hex(strrev(substr($data, 947,4)))));
		print("<br> LATITUDE MAX 2			 					=> ".hexdec(bin2hex(strrev(substr($data, 951,4)))));
		print("<br> LATITUDE MIN 2			 					=> ".hexdec(bin2hex(strrev(substr($data, 955,4)))));
		print("<br> LONGITUDE MAX 2			 					=> ".hexdec(bin2hex(strrev(substr($data, 959,4)))));
		print("<br> LONGITUDE MIN 2			 					=> ".hexdec(bin2hex(strrev(substr($data, 963,4)))));
		print("<br> LATITUDE MAX 3			 					=> ".hexdec(bin2hex(strrev(substr($data, 967,4)))));
		print("<br> LATITUDE MIN 3			 					=> ".hexdec(bin2hex(strrev(substr($data, 971,4)))));
		print("<br> LONGITUDE MAX 3			 					=> ".hexdec(bin2hex(strrev(substr($data, 975,4)))));
		print("<br> LONGITUDE MIN 3			 					=> ".hexdec(bin2hex(strrev(substr($data, 979,4)))));
		print("<br> LATITUDE MAX 4			 					=> ".hexdec(bin2hex(strrev(substr($data, 983,4)))));
		print("<br> LATITUDE MIN 4			 					=> ".hexdec(bin2hex(strrev(substr($data, 987,4)))));
		print("<br> LONGITUDE MAX 4			 					=> ".hexdec(bin2hex(strrev(substr($data, 991,4)))));
		print("<br> LONGITUDE MIN 4			 					=> ".hexdec(bin2hex(strrev(substr($data, 995,4)))));
		print("<br> LATITUDE MAX 5			 					=> ".hexdec(bin2hex(strrev(substr($data, 999,4)))));
		print("<br> LATITUDE MIN 5			 					=> ".hexdec(bin2hex(strrev(substr($data, 1003,4)))));
		print("<br> LONGITUDE MAX 5			 					=> ".hexdec(bin2hex(strrev(substr($data, 1007,4)))));
		print("<br> LONGITUDE MIN 5			 					=> ".hexdec(bin2hex(strrev(substr($data, 1011,4)))));
		print("<br> MODE GSM									=> ".hexdec(bin2hex(strrev(substr($data, 1015,1)))));
		print("<br> GSM - APC									=> ".hexdec(bin2hex(strrev(substr($data, 1016,1)))));
		print("<br> MODE GSM LAPS								=> ".hexdec(bin2hex(strrev(substr($data, 1017,2)))));
		print("<br> MODE GSM TIMING	OFFLINE						=> ".hexdec(bin2hex(strrev(substr($data, 1019,2)))));
		print("<br> MODE GSM TIMING	ONLINE						=> ".hexdec(bin2hex(strrev(substr($data, 1021,2)))));
		print("<br> tALARME LAPS GEO							=> ".hexdec(bin2hex(strrev(substr($data, 1023,2)))));
		
		
		print("<br><br> NVM										=> ".substr($data1, 0,5));
		print("<br> MODE_SMS									=> ".hexdec(bin2hex(strrev(substr($data1, 5,1)))));
		print("<br> Send on APC		 							=> ".hexdec(bin2hex(strrev(substr($data1, 6,4)))));
		print("<br> Send on STOP		 						=> ".hexdec(bin2hex(strrev(substr($data1, 10,4)))));
		print("<br> Send on RT		 							=> ".hexdec(bin2hex(strrev(substr($data1, 14,4)))));
		print("<br> Send on TIMING		 						=> ".hexdec(bin2hex(strrev(substr($data1, 18,4)))));
		print("<br> SMS STATE CHANGED							=> ".hexdec(bin2hex(strrev(substr($data1, 22,1)))));
		print("<br> APN											=> ".substr($data1, 23,30));
		print("<br> LOGIN										=> ".substr($data1, 53,30));
		print("<br> login										=> ".substr($data1, 83,30));
		print("<br> DNS											=> ".substr($data1, 113,26).hexdec(bin2hex(strrev(substr($data1, 139,1)))).hexdec(bin2hex(strrev(substr($data1, 140,1)))).hexdec(bin2hex(strrev(substr($data1, 141,1)))).hexdec(bin2hex(strrev(substr($data1, 142,1)))));
		print("<br> PORT DNS		 							=> ".hexdec(bin2hex(substr($data1, 158,1)).bin2hex(substr($data1, 157,1)).bin2hex(substr($data1, 156,1)).bin2hex(substr($data1, 155,1)))); 
		print("<br> IP1		 									=> ".hexdec(bin2hex(strrev(substr($data1, 162,1)))).".".hexdec(bin2hex(strrev(substr($data1, 161,1)))).".".hexdec(bin2hex(strrev(substr($data1, 160,1)))).".".hexdec(bin2hex(strrev(substr($data1, 159,1)))));
		print("<br> IPP1		 								=> ".hexdec(bin2hex(substr($data1, 166,1)).bin2hex(substr($data1, 165,1)).bin2hex(substr($data1, 164,1)).bin2hex(substr($data1, 163,1))));
		print("<br> IP2		 									=> ".hexdec(bin2hex(strrev(substr($data1, 170,1)))).".".hexdec(bin2hex(strrev(substr($data1, 169,1)))).".".hexdec(bin2hex(strrev(substr($data1, 168,1)))).".".hexdec(bin2hex(strrev(substr($data1, 167,1)))));
		print("<br> IPP2		 								=> ".hexdec(bin2hex(substr($data1, 174,1)).bin2hex(substr($data1, 173,1)).bin2hex(substr($data1, 172,1)).bin2hex(substr($data1, 171,1)))); 
		print("<br> SEND GPRS		 							=> ".hexdec(bin2hex(strrev(substr($data1, 175,4)))));
		print("<br> SEND GPRS VEILLE		 					=> ".hexdec(bin2hex(strrev(substr($data1, 179,4)))));
		print("<br> DELAY 1										=> ".hexdec(bin2hex(strrev(substr($data1, 183,1)))));
		print("<br> DELAY 2										=> ".hexdec(bin2hex(strrev(substr($data1, 184,1)))));
		print("<br> DELAY 3										=> ".hexdec(bin2hex(strrev(substr($data1, 185,1)))));
		print("<br> tALARME LAPS GEO2		 					=> ".hexdec(bin2hex(strrev(substr($data1, 186,4)))));
		print("<br> iTimeoutModeGsm								=> ".hexdec(bin2hex(strrev(substr($data1, 190,2)))));
		print("<br> RealtimeAPC									=> ".hexdec(bin2hex(strrev(substr($data1, 192,1)))));
		print("<br> Led											=> ".hexdec(bin2hex(substr($data1, 194,1))));
		print("<br> FWI                                         => ".hexdec(bin2hex(strrev(substr($data1, 195,4)))));
		print("<br> Serveur ADR 0                               => ".substr($data1, 123,30));
		print("<br> Serveur Port 0                              => ".substr($data1, 287,5));
		print("<br> Serveur ADR 1                               => ".substr($data1, 292,30));
		print("<br> Serveur Port 1                              => ".substr($data1, 327,5));
		print("<br> Serveur ADR 2                               => ".substr($data1, 332,30));
		print("<br> Serveur Port 2                              => ".substr($data1, 364,5));
		print("<br> AccGravite									=> ".hexdec(bin2hex(strrev(substr($data1, 425,1)))));
		print("<br> AccTemps									=> ".hexdec(bin2hex(strrev(substr($data1, 426,1)))));
		print("<br> GeolocGSM									=> ".hexdec(bin2hex(strrev(substr($data1, 427,1)))));
		print("<br> GeolocGSMtps								=> ".hexdec(bin2hex(strrev(substr($data1, 428,1)))));
		// Fenetre GSM 1
		print("<br> Fen1Mode									=> ".hexdec(bin2hex(substr($data1, 209,1))));
		print("<br> Fen1Jour									=> ".hexdec(bin2hex(substr($data1, 210,1))));
		$fen1dheur = hexdec(bin2hex(substr($data1, 211,1))); $fen1dmin = hexdec(bin2hex(substr($data1, 212,1)));
		print("<br> Fen1HDeb									=> ".ajoutechaine($fen1dheur).":".ajoutechaine($fen1dmin));
		$fen1fheur = hexdec(bin2hex(substr($data1, 213,1))); $fen1fmin = hexdec(bin2hex(substr($data1, 214,1)));
		print("<br> Fen1HFin									=> ".ajoutechaine($fen1fheur).":".ajoutechaine($fen1fmin));
		print("<br> Fen1MRepli									=> ".hexdec(bin2hex(strrev(substr($data1, 215,2)))));
		// Fenetre GSM 2
		print("<br> Fen2Mode									=> ".hexdec(bin2hex(substr($data1, 217,1))));
		print("<br> Fen2Jour									=> ".hexdec(bin2hex(substr($data1, 218,1))));
		$fen2dheur = hexdec(bin2hex(substr($data1, 219,1))); $fen2dmin = hexdec(bin2hex(substr($data1, 220,1)));
		print("<br> Fen2HDeb									=> ".ajoutechaine($fen2dheur).":".ajoutechaine($fen2dmin));
		$fen2fheur = hexdec(bin2hex(substr($data1, 221,1))); $fen2fmin = hexdec(bin2hex(substr($data1, 222,1)));
		print("<br> Fen2HFin									=> ".ajoutechaine($fen2fheur).":".ajoutechaine($fen2fmin));
		print("<br> Fen2MRepli									=> ".hexdec(bin2hex(strrev(substr($data1, 223,2)))));
		// Fenetre GSM 3
		print("<br> Fen3Mode									=> ".hexdec(bin2hex(substr($data1, 225,1))));
		print("<br> Fen3Jour									=> ".hexdec(bin2hex(substr($data1, 226,1))));
		$fen3dheur = hexdec(bin2hex(substr($data1, 227,1))); $fen3dmin = hexdec(bin2hex(substr($data1, 228,1)));
		print("<br> Fen3HDeb									=> ".ajoutechaine($fen3dheur).":".ajoutechaine($fen3dmin));
		$fen3fheur = hexdec(bin2hex(substr($data1, 229,1))); $fen3fmin = hexdec(bin2hex(substr($data1, 230,1)));
		print("<br> Fen3HFin									=> ".ajoutechaine($fen3fheur).":".ajoutechaine($fen3fmin));
		print("<br> Fen3MRepli									=> ".hexdec(bin2hex(strrev(substr($data1, 231,2)))));
		// Fenetre GSM 4
		print("<br> Fen4Mode									=> ".hexdec(bin2hex(substr($data1, 233,1))));
		print("<br> Fen4Jour									=> ".hexdec(bin2hex(substr($data1, 234,1))));
		$fen4dheur = hexdec(bin2hex(substr($data1, 235,1))); $fen4dmin = hexdec(bin2hex(substr($data1, 236,1)));
		print("<br> Fen4HDeb									=> ".ajoutechaine($fen4dheur).":".ajoutechaine($fen4dmin));
		$fen4fheur = hexdec(bin2hex(substr($data1, 237,1))); $fen4fmin = hexdec(bin2hex(substr($data1, 238,1)));
		print("<br> Fen4HFin									=> ".ajoutechaine($fen4fheur).":".ajoutechaine($fen4fmin));
		print("<br> Fen4MRepli									=> ".hexdec(bin2hex(strrev(substr($data1, 239,2)))));
		// Fenetre GSM 5
		print("<br> Fen5Mode									=> ".hexdec(bin2hex(substr($data1, 241,1))));
		print("<br> Fen5Jour									=> ".hexdec(bin2hex(substr($data1, 242,1))));
		$fen5dheur = hexdec(bin2hex(substr($data1, 243,1))); $fen5dmin = hexdec(bin2hex(substr($data1, 244,1)));
		print("<br> Fen5HDeb									=> ".ajoutechaine($fen5dheur).":".ajoutechaine($fen5dmin));
		$fen5fheur = hexdec(bin2hex(substr($data1, 245,1))); $fen5fmin = hexdec(bin2hex(substr($data1, 246,1)));
		print("<br> Fen5HFin									=> ".ajoutechaine($fen5fheur).":".ajoutechaine($fen5fmin));
		print("<br> Fen5MRepli									=> ".hexdec(bin2hex(strrev(substr($data1, 247,2)))));
		// Fenetre GSM 6
		print("<br> Fen6Mode									=> ".hexdec(bin2hex(substr($data1, 249,1))));
		print("<br> Fen6Jour									=> ".hexdec(bin2hex(substr($data1, 250,1))));
		$fen6dheur = hexdec(bin2hex(substr($data1, 251,1))); $fen6dmin = hexdec(bin2hex(substr($data1, 252,1)));
		print("<br> Fen6HDeb									=> ".ajoutechaine($fen6dheur).":".ajoutechaine($fen6dmin));
		$fen6fheur = hexdec(bin2hex(substr($data1, 253,1))); $fen6fmin = hexdec(bin2hex(substr($data1, 254,1)));
		print("<br> Fen6HFin									=> ".ajoutechaine($fen6fheur).":".ajoutechaine($fen6fmin));
		// print("<br> Fen6HDeb									=> ".hexdec(bin2hex(substr($data1, 251,1)))).":".hexdec(bin2hex(substr($data1, 252,1)));
		// print("<br> Fen6HFin									=> ".hexdec(bin2hex(substr($data1, 253,1)))).":".hexdec(bin2hex(substr($data1, 254,1)));
		print("<br> Fen6MRepli									=> ".hexdec(bin2hex(strrev(substr($data1, 255,2)))));
		
		//print(extractEmailsFromString(substr($data1, 505,48))[0]); clef -> valeur
		// MAIL ET SMTP
		//print("<br> MAIL1										=> ".extractEmailsFromString(substr($data1, 505,48))[0]);	// Set by FE1
		print("<br> MAIL1										=> ".substr($data1, 505,48));	// Set by FE1
		print("<br> SRV_SMTP									=> ".substr($data1, 553,46));
		print("<br> PORT_SMTP									=> ".hexdec(bin2hex(strrev(substr($data1, 599,2)))));
		print("<br> MEL_SMTP									=> ".hexdec(bin2hex(strrev(substr($data1, 601,48)))));
		print("<br> PW_SMTP										=> ".hexdec(bin2hex(strrev(substr($data1, 649,32)))));

		// MAIL ET ALARMES
		print("<br> MEL_AL1										=> ".hexdec(bin2hex(substr($data1, 681,1))));	// Set by FA1
		print("<br> MEL_AL2										=> ".hexdec(bin2hex(substr($data1, 682,1))));	// Set by FA2
		print("<br> MEL_MODE_PARK								=> ".hexdec(bin2hex(substr($data1, 683,1))));	// Set by FA3
		print("<br> MEL_AL_APC									=> ".hexdec(bin2hex(substr($data1, 684,1))));	// Set by FA4
		print("<br> MEL_AL_ALIM									=> ".hexdec(bin2hex(substr($data1, 685,1))));	// Set by FA5
		print("<br> MEL_AL_BAT									=> ".hexdec(bin2hex(substr($data1, 692,1))));	// Set by FA6

		// MAIL ET GEOFENCING
		print("<br> MEL_GEOF1									=> ".hexdec(bin2hex(substr($data1, 693,1))));	// Set by FZ0
		print("<br> MEL_GEOF2									=> ".hexdec(bin2hex(substr($data1, 694,1))));	// Set by FZ1
		print("<br> MEL_GEOF3									=> ".hexdec(bin2hex(substr($data1, 695,1))));	// Set by FZ2
		print("<br> MEL_GEOF4									=> ".hexdec(bin2hex(substr($data1, 696,1))));	// Set by FZ3
		print("<br> MEL_GEOF5									=> ".hexdec(bin2hex(substr($data1, 697,1))));	// Set by FZ4
		
		// Alim et Batterie
		print("<br> SEUIL_ALIM									=> ".hexdec(bin2hex(substr($data1, 698,1))));	// Set by FAe
		print("<br> Cut"); // suite au faite que javascritp ne renvoillait pas la valeur exacte de SEUIL_ALIM
		print("<br> MODE_BAT									=> ".hexdec(bin2hex(substr($data1, 699,1))));	// Set by Faf
		print("<br> SEUIL2_AL_BAT								=> ".hexdec(bin2hex(substr($data1, 700,1))));	// Set by Faf
		print("<br> SEUIL3_AL_BAT								=> ".hexdec(bin2hex(substr($data1, 701,1))));	// Set by Faf
		
		//Strategie low batt
		print("<br> MODE_GPRS_SB								=> ".hexdec(bin2hex(substr($data1, 702,1))));	// Set by Fwm1
		print("<br> GPRS_ACTIF_SB								=> ".hexdec(bin2hex(strrev(substr($data1, 703,4)))));	// Set by Fwe1
		print("<br> GPRS_VEILLE_SB								=> ".hexdec(bin2hex(strrev(substr($data1, 707,4)))));	// Set by Fwe1
		print("<br> GPRS_TIMING_SB								=> ".hexdec(bin2hex(strrev(substr($data1, 711,4)))));	// Set by Fwe1
		print("<br> MODE_GPS_SB									=> ".hexdec(bin2hex(strrev(substr($data1, 715,4)))));	// Set by Fgm1
		print("<br> GPS_ACTIF_SB								=> ".hexdec(bin2hex(strrev(substr($data1, 719,4)))));	// Set by Fgs1
		print("<br> GPS_VEILLE_SB								=> ".hexdec(bin2hex(strrev(substr($data1, 723,4)))));	// Set by Fgs1
		print("<br> GPS_TIMING_SB								=> ".hexdec(bin2hex(strrev(substr($data1, 727,4)))));	// Set by Fgs1
		print("<br> MODE_GSM_SB									=> ".hexdec(bin2hex(substr($data1, 731,1))));	// Set by FRm
		print("<br> APC_GSM_SB									=> ".hexdec(bin2hex(substr($data1, 732,1))));	// Set by FRm
		print("<br> TPS_LAT_GSM_SB								=> ".hexdec(bin2hex(strrev(substr($data1, 733,2)))));	// Set by FRm
		print("<br> TPS_ACT_GSM_SB								=> ".hexdec(bin2hex(strrev(substr($data1, 735,2)))));	// Set by FRm
		print("<br> TPS_DESACT_GSM_SB							=> ".hexdec(bin2hex(strrev(substr($data1, 735,2)))));	// Set by FRm
		
		//Strategie Geofencing
		print("<br> STRATEGIE_GEOF								=> ".hexdec(bin2hex(substr($data1, 739,1))));	// Set by ?
		print("<br> MODE_GPRS_SG								=> ".hexdec(bin2hex(substr($data1, 740,1))));	// Set by Fwm1
		print("<br> GPRS_ACTIF_SG								=> ".hexdec(bin2hex(strrev(substr($data1, 741,4)))));	// Set by Fwe1
		print("<br> GPRS_VEILLE_SG								=> ".hexdec(bin2hex(strrev(substr($data1, 745,4)))));	// Set by Fwe1
		print("<br> GPRS_TIMING_SG								=> ".hexdec(bin2hex(strrev(substr($data1, 749,4)))));	// Set by Fwe1
		print("<br> MODE_GPS_SG									=> ".hexdec(bin2hex(strrev(substr($data1, 753,4)))));	// Set by Fgm1
		print("<br> GPS_ACTIF_SG								=> ".hexdec(bin2hex(strrev(substr($data1, 757,4)))));	// Set by Fgs1
		print("<br> GPS_VEILLE_SG								=> ".hexdec(bin2hex(strrev(substr($data1, 761,4)))));	// Set by Fgs1
		print("<br> GPS_TIMING_SG								=> ".hexdec(bin2hex(strrev(substr($data1, 765,4)))));	// Set by Fgs1
		print("<br> MODE_GSM_SG									=> ".hexdec(bin2hex(substr($data1, 769,1))));	// Set by FRm
		print("<br> APC_GSM_SG									=> ".hexdec(bin2hex(substr($data1, 770,1))));	// Set by FRm
		print("<br> TPS_LAT_GSM_SG								=> ".hexdec(bin2hex(strrev(substr($data1, 771,2)))));	// Set by FRm
		print("<br> TPS_ACT_GSM_SG								=> ".hexdec(bin2hex(strrev(substr($data1, 773,2)))));	// Set by FRm
		print("<br> TPS_DESACT_GSM_SG							=> ".hexdec(bin2hex(strrev(substr($data1, 775,2)))));	// Set by FRm
		
		// mode vitesse
		print("<br> FGc_ACT							=> ".hexdec(bin2hex(strrev(substr($data1, 777,1)))));
		print("<br> FGc_VITESSE							=> ".hexdec(bin2hex(strrev(substr($data1, 778,1)))));
		print("<br> FGc_FRE_ACQ							=> ".hexdec(bin2hex(strrev(substr($data1, 779,2)))));
		print("<br> FGc_FRE_RAP							=> ".hexdec(bin2hex(strrev(substr($data1, 781,2)))));

		printf("&");
	}
	
	//INITIALISATION VARIABLE
	$q=$_GET["idTracker"];
	$nomDatabaseGpw=$_GET["nomDatabaseGpw"];
	$ipDatabaseGpw=$_GET["ipDatabaseGpw"];

	include '../dbconnect2.php';
	//CONNEXION BDD GLOBALE
	$connection=mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2,$nomDatabaseGpw);
	if (!$connection) {
		die('Not connected : ' . mysqli_connect_error());
	}

	//INSTRUCTION D'UNE REQUETE SQL
	$sql="SELECT Datas0,Datas1 FROM ttrackers WHERE Id_tracker = '".$q."' ";
	$result = mysqli_query($connection,$sql);
	// $assoc = mysql_fetch_assoc($result);
	// $datas0 = $assoc['Datas0'];
	// $datas1 = $assoc['Datas1'];
		
	while($row = mysqli_fetch_array($result))
	{
		
		// echo "<html><table border='1' width='100px'><tr ><th width='50%'>Datas0</th><th width='50%'>Datas1</th></tr>";
		// echo "<tr><td>".$row['Datas0']."</td><td>".$row['Datas1']."</td></tr></table></html>";
		// print
		// print(strlen($row['Datas0']));
		
		if($q > 352094080000000)		// TELTO , NEO & SOLO
			print($row['Datas0']);
		else							// Stancom
			identifierData($row['Datas0'], $row['Datas1']);

		// print(($row['Datas0']));
		
		// echo "Datas0:".$row['Datas0'];
		// echo "Datas1:".$row['Datas1'].",";


	}  


	mysqli_close($connection);
?>
