	<?php
		session_start();
		$_SESSION['CREATED'] = time();

//        include('../../../lib/tubalmartin-spherical-geometry/spherical-geometry.class.php');

		include '../function.php';
		include '../dbconnect2.php';
		include('../dbtpositions.php');
		include('../ChromePhp.php');

		require('../../../lib/fpdf/mysql_table.php');

		$debutRapportEtape=$_POST['debutRapportEtape'];
		$finRapportEtape=$_POST['finRapportEtape'];
		ini_set('display_errors','off');
		$idBaliseRapportEtape=$_POST["idBaliseRapportEtape"];
		$nomBaliseRapportEtape=$_POST['nomBaliseRapportEtape'];
		$numeroEtape = $_POST['selectEtape'];
		$nombreMaxEtape = $_POST['nombreMaxEtape'];
		$urlCarto = $_POST['urlCarto'];
		$timezone=$_POST["timezoneEtape"];

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
		if( (substr($_SESSION['language'],-2) == "US"))$formatLangDateTime = "Y-m-d h:i:s A"; else $formatLangDateTime = "d-m-Y H:i:s";


	$dUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($debutRapportEtape)),$timezone);
	$fUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($finRapportEtape)),$timezone);


	class PDF extends PDF_MySQL_Table{
		var $col = 0;
		var $y0;

		function Header(){
			$titre = _("rapport_rapportgeo3x");
			$this->SetFont('Arial','B',15);
			$this->SetFillColor(60,70,69);
			$this->SetTextColor(254,254,254);
			$this->Cell(0,18,$titre,1,1,'C',true);
			$this->Ln(5);
			$this->Image('../../assets/img/logo.png',14,13,33);		// Geofence


		}

		function Footer(){
			$this->SetTextColor(0,0,0);
			$this->SetY(-20);
			$this->SetFont('Arial','I',8);
			$this->Cell(30,10,_("rapport_commentaire"),0,0,'C');
			$this->Cell(220,10,'',1,0,'C');
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		}

		function SetCol($col){
			$this->col = $col;
			$x = 10+$col*65;
			$this->SetLeftMargin($x);
			$this->SetX($x);
		}

		/************************************************************************************************
		*************************************************************************************************	PAGE PRINCIPALE
		************************************************************************************************/

		function pagePrincipale($nomBaliseRapportEtape,$idBaliseRapportEtape,$numeroEtape,$nombreMaxEtape,array $km,array $vitesseMoyenne,array $vitesseMax,array $dureeTrajet, array $dureeArret,array $positionEtape, array $dateDebutEtape, array $dateFinEtape, array $lieuDebutEtape, array $lieuFinEtape){

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
			if( (substr($_SESSION['language'],-2) == "US"))$formatLangDateTime = "Y-m-d h:i:s A"; else $formatLangDateTime = "Y-m-d H:i:s";
				$realNumeroEtape = $numeroEtape - 1;
			$this->AddPage();
			//EN-TETE//
			$this->SetFont('Arial','',12);
			$this->SetTextColor(254,254,254);
			$this->SetFillColor(96,104,103);
			$this->Cell(0,6,_("rapport_etape").": $numeroEtape / $nombreMaxEtape",0,1,'C',true);
			$this->Ln(4);

			// Sauvegarde de l'ordonn�e
			$this->y0 = $this->GetY();
			$this->SetXY(100,53);
			$this->SetTextColor(254,254,254);
			$this->SetFillColor(96,104,103);
			$this->Cell(100,6,utf8_decode(_("rapport_informationetape")." $numeroEtape"),0,1,'C','LR');
				// Police
			$this->SetTextColor(0,0,0);

			$this->SetCol(0);
			$w = array(50, 50);

			$this->SetXY(100,60);
			$this->Cell(array_sum($w),0,'','T');
			$this->SetXY(100,60);
			$this->SetFont('Times','',12);
			$this->Cell($w[0],6,_("nombalise"),'LR',0,'L');
			$this->SetFont('Times','I',10);
			$this->Cell($w[1],6,utf8_decode("$nomBaliseRapportEtape"),'LR',0,'R');

			$this->SetXY(100,66);
			$this->Cell(array_sum($w),0,'','T');
			$this->SetXY(100,66);
			$this->SetFont('Times','',12);
			$this->Cell($w[0],6,_("idbalise"),'LR',0,'L');
			$this->SetFont('Times','I',10);
			$this->Cell($w[1],6,"$idBaliseRapportEtape",'LR',0,'R');

			$this->SetXY(100,72);
			$this->Cell(array_sum($w),0,'','T');
			$this->SetXY(100,72);
			$this->SetFont('Times','',12);
			$this->Cell($w[0],6,utf8_decode(_("rapport_dureedestrajets")),'LR',0,'L');
			$this->SetFont('Times','I',10);
			$this->Cell($w[1],6,"$dureeTrajet[$realNumeroEtape]",'LR',0,'R');

			$this->SetXY(100,78);
			$this->Cell(array_sum($w),0,'','T');
			$this->SetXY(100,78);
			$this->SetFont('Times','',12);
			$this->Cell($w[0],6,utf8_decode(_("rapport_dureedesarrets")),'LR',0,'L');
			$this->SetFont('Times','I',10);
			$this->Cell($w[1],6,"$dureeArret[$numeroEtape]",'LR',0,'R');

			$this->SetXY(100,84);
			$this->Cell(array_sum($w),0,'','T');
			$this->SetXY(100,84);
			$this->SetFont('Times','',12);
			$this->Cell($w[0],6,utf8_decode(_("rapport_kmsparcourus")),'LR',0,'L');
			$this->SetFont('Times','I',10);
			$this->Cell($w[1],6,"$km[$realNumeroEtape]",'LR',0,'R');

			$this->SetXY(100,90);
			$this->Cell(array_sum($w),0,'','T');
			$this->SetXY(100,90);
			$this->SetFont('Times','',12);
			$this->Cell($w[0],6,_("rapport_nombrepositions"),'LR',0,'L');
			$this->SetFont('Times','I',10);
			$this->Cell($w[1],6,"$positionEtape[$realNumeroEtape]",'LR',0,'R');

			$this->SetXY(100,96);
			$this->Cell(array_sum($w),0,'','T');
			$this->SetXY(100,96);
			$this->SetFont('Times','',12);
			$this->Cell($w[0],6,_("vitesse").' Max','LR',0,'L');
			$this->SetFont('Times','I',10);
			$this->Cell($w[1],6,"$vitesseMax[$realNumeroEtape]",'LR',0,'R');

			$this->SetXY(100,102);
			$this->Cell(array_sum($w),0,'','T');
			$this->SetXY(100,102);
			$this->SetFont('Times','',12);
			$this->Cell($w[0],6,_("vitesse").' Moy','LR',0,'L');
			$this->SetFont('Times','I',10);
			$this->Cell($w[1],6,"$vitesseMoyenne[$realNumeroEtape]",'LR',0,'R');

			$this->SetXY(100,108);
			$this->Cell(array_sum($w),0,'','T');

			$this->SetXY(80,114);
			$this->Cell(140,0,'','T');
			$this->SetXY(80,114);
			$this->SetFont('Times','',12);
			$this->Cell(30,6,utf8_decode(_("rapport_datedebut")),'LR',0,'L');
			$this->SetFont('Times','I',10);
			$this->Cell(110,6,date($formatLangDateTime, strtotime($dateDebutEtape[$realNumeroEtape])),'LR',0,'R');

			$this->SetXY(80,120);
			$this->Cell(140,0,'','T');
			$this->SetXY(80,120);
			$this->SetFont('Times','',12);
			$this->Cell(30,6,utf8_decode(_("rapport_lieudebut")),'LR',0,'L');
			$this->SetFont('Times','I',10);
			$this->Cell(110,6,"$lieuDebutEtape[$realNumeroEtape]",'LR',0,'R');
			$this->SetXY(80,126);
			$this->Cell(140,0,'','T');

			$this->SetXY(80,132);
			$this->Cell(140,0,'','T');
			$this->SetXY(80,132);
			$this->SetFont('Times','',12);
			$this->Cell(30,6,_("rapport_datefin"),'LR',0,'L');
			$this->SetFont('Times','I',10);
			$this->Cell(110,6,date($formatLangDateTime, strtotime($dateFinEtape[$realNumeroEtape])),'LR',0,'R');

			$this->SetXY(80,138);
			$this->Cell(140,0,'','T');
			$this->SetXY(80,138);
			$this->SetFont('Times','',12);
			$this->Cell(30,6,_("rapport_lieufin"),'LR',0,'L');
			$this->SetFont('Times','I',10);
			$this->Cell(110,6,"$lieuFinEtape[$realNumeroEtape]",'LR',0,'R');
			$this->SetXY(80,144);
			$this->Cell(140,0,'','T');

			$this->SetTextColor(0,0,0);



			// Retour en premi�re colonne
			$this->SetCol(0);


		}
		/************************************************************************************************
		*************************************************************************************************	PAGE GRAPHE
		************************************************************************************************/
		function pageCarto($numeroEtape,$urlCarto){
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
			$this->AddPage();
			//EN-TETE//
			$this->SetFont('Arial','',12);
			$this->SetTextColor(254,254,254);
			$this->SetFillColor(96,104,103);
			$this->Cell(0,6,utf8_decode(_("rapport_cartosurletape")." $numeroEtape"),0,1,'C',true);
			$this->Ln(20);
			$this->Cell(62.5,10,"");
			$this->Image("$urlCarto",null, null, 0, 0, 'PNG');
			// $size = getimagesize("rapportgraphetape.png");
			// $largeur=$size[0];
			// $hauteur=$size[1];
			// $ratio=120/$hauteur;	//hauteur impos�e de 120mm
			// $newlargeur=$largeur*$ratio;
			// $posi=(300-$newlargeur)/2;	//300mm = largeur de page

			// $this->SetFont('Arial','B',16);
			// $this->Image("rapportgraphetape.png",$posi,40,0,0,'PNG');

		}
		/************************************************************************************************
		*************************************************************************************************	PAGE GRAPHE
		************************************************************************************************/
		function pageGraphe($numeroEtape,$idBaliseRapportEtape,$debutRapportEtape,$finRapportEtape){
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
			$this->AddPage();
			//EN-TETE//
			$this->SetFont('Arial','',12);
			$this->SetTextColor(254,254,254);
			$this->SetFillColor(96,104,103);
			$this->Cell(0,6,utf8_decode(_("rapport_graphevitessesurletape")." $numeroEtape"),0,1,'C',true);
			$this->Ln(4);

			$username = $_SESSION["username"];
			$nomDuFichier = $numeroEtape."_".$idBaliseRapportEtape . $username . $debutRapportEtape . $finRapportEtape;
			$nomDuFichier = str_replace(":","",$nomDuFichier);
			$nomDuFichier = str_replace("-","",$nomDuFichier);
			$nomDuFichier = str_replace(" ","",$nomDuFichier);
			$nomDuFichier = str_replace("*","",$nomDuFichier);
			$size = getimagesize("../../assets/img/graph/rapportgraphetape$nomDuFichier.png");
			$largeur=$size[0];
			$hauteur=$size[1];
			$ratio=120/$hauteur;	//hauteur impos�e de 120mm
			$newlargeur=$largeur*$ratio;
			$posi=(300-$newlargeur)/2;	//300mm = largeur de page

			$this->SetFont('Arial','B',16);

			$this->Image("../../assets/img/graph/rapportgraphetape$nomDuFichier.png",$posi,40,0,0,'PNG');

		}
		/************************************************************************************************
		*************************************************************************************************	calculDistance
		************************************************************************************************/
		function get_distance_m($lat1, $lng1, $lat2, $lng2) {
			$earth_radius = 6378137;   // Terre = sph�re de 6378km de rayon
			$rlo1 = deg2rad($lng1);
			$rla1 = deg2rad($lat1);
			$rlo2 = deg2rad($lng2);
			$rla2 = deg2rad($lat2);
			$dlo = ($rlo2 - $rlo1) / 2;
			$dla = ($rla2 - $rla1) / 2;
			$a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo
					));
			$d = 2 * atan2(sqrt($a), sqrt(1 - $a));
			return round(($earth_radius * $d)/1000,3);
		}

		function get_distance_m2($lat1, $lng1, $lat2, $lng2) {
			$earth_radius = 6378137;   // Terre = sph�re de 6378km de rayon
			$rlo1 = deg2rad($lng1);
			$rla1 = deg2rad($lat1);
			$rlo2 = deg2rad($lng2);
			$rla2 = deg2rad($lat2);
			$dlo = ($rlo2 - $rlo1) / 2;
			$dla = ($rla2 - $rla1) / 2;
			$a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo
					));
			$d = 2 * atan2(sqrt($a), sqrt(1 - $a));
			return round(($earth_radius * $d)/1000,3);
		}


		function calculDistance($x11,$y11,$x22,$y22){
			$pi = 3.14;
			$r = 6378000;
			$d="";
			$x1 = $x11*$pi/180;
			$y1 = $y11*$pi/180;
			$x2 = $x22*$pi/180;
			$y2 = $y22*$pi/180;

			$t1 = sin($x1) * sin($x2);
			$t2 = cos($x1) * cos($x2);
			$t3 = cos($y1 - $y2);

			// if( ($x11 != $x22) || ($y11 != $y22) ){
				// if( ( ($t1+ $t3 * $t2)>1 ) || ( ($t1+ $t3 * $t2) < -1 ) ) {
					// echo "<script>alert(\'Bug Calcul Distance entre 2 pos dans le Rapport')</script>";
				// }
				$d = $r * acos($t1+ $t3 * $t2 );



			// }else{
				// echo "<script>alert(\'Bug Calcul Distance entre 2 pos dans le Rapport')</script>";
			// }
			return round($d/100);
		}
			function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
			$theta = $longitude1 - $longitude2;
			$miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
			$miles = acos($miles);
			$miles = rad2deg($miles);
			$miles = $miles * 60 * 1.1515;
			$feet = $miles * 5280;
			$yards = $feet / 3;
			$kilometers = $miles * 1.609344;
			$meters = $kilometers * 1000;
			return round($kilometers);
		}
		function distance3($lat1, $lon1, $lat2, $lon2, $unit) {

		  $theta = $lon1 - $lon2;
		  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		  $dist = acos($dist);
		  $dist = rad2deg($dist);
		  $miles = $dist * 60 * 1.1515;
		  $unit = strtoupper($unit);

		  if ($unit == "K") {
			return ($miles * 1.609344);
		  } else if ($unit == "N") {
			  return ($miles * 0.8684);
			} else {
				return $miles;
			  }
		}

		function getPoiTracker($db_user_2,$db_pass_2){
			$arrayPoi = array();
			$idBaliseRapportEtape=$_POST["idBaliseRapportEtape"];

			$nomDatabaseGpw=$_POST["nomDatabaseGpw"];
			$ipDatabaseGpw=$_POST["ipDatabaseGpw"];

			$connection=mysqli_connect($ipDatabaseGpw,$db_user_2,$db_pass_2,$nomDatabaseGpw);
			$sql="SELECT Numero_Zone FROM twarnings2  WHERE Id_tracker = '".$idBaliseRapportEtape."' AND Type_Geometrie = '4' ";
			$result = mysqli_query($connection,$sql);

			while($row = mysqli_fetch_array($result)){
	//			echo "Numero_Zone:" . $row['Numero_Zone'] . "&";
				array_push($arrayPoi,$row['Numero_Zone']);
			}
			mysqli_free_result($result);
			mysqli_close($connection);

			return $arrayPoi;

		}

		/************************************************************************************************
		*************************************************************************************************	statutEncode
		************************************************************************************************/
		function statutEncodeRapport($sql,$arrayTpositions,$db_user_2,$db_pass_2){

			$i=0;
			$cbalise = array();
			$nomDatabaseGpw=$_POST["nomDatabaseGpw"];
			$ipDatabaseGpw=$_POST["ipDatabaseGpw"];
			$connectStatutEncode = mysqli_connect($ipDatabaseGpw,$db_user_2,$db_pass_2,$nomDatabaseGpw);

			$Hd1="";
			$Hf1="";
			$Hd2="";
			$Hf2="";
			$Lundi="";
			$Mardi="";
			$Mercredi="";
			$Jeudi="";
			$Vendredi="";
			$Samedi="";
			$Dimanche="";

			// $connection2=mysqli_connect($ipDatabaseGpw,$db_user_2, $db_pass_2,$nomDatabaseGpw);
			// $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $_POST['idBaliseRapportEtape'] . "' )";
			// $result2 = mysqli_query($connection2,$sql2);

			$queryFetchArray = function($cbalise,$rowStatutEncode,$i) {

				$statutRecup = $rowStatutEncode['Pos_Statut'];
				$statutEncode = array();
				$puissance = 31;
				while ($puissance > 0) {
					if (pow(2, $puissance) > $statutRecup) {
						array_push($statutEncode, "0");
					} else {
						$statutRecup = $statutRecup - pow(2, $puissance);
						array_push($statutEncode, "1");
					}
					$puissance--;
				}
				if ($statutEncode[29] == "1") {
					if ($rowStatutEncode['Pos_Vitesse'] == 0) {
						$cbalise[$i] = "rouge";
					}
					if ($rowStatutEncode['Pos_Vitesse'] <= 10) {
						$cbalise[$i] = "jaune";
					}
					if ($rowStatutEncode['Pos_Vitesse'] > 10) {
						$cbalise[$i] = "vert";
					}
				} else {
					$cbalise[$i] = "stop";
				}

				return $cbalise[$i];
			};

			if (sizeof($arrayTpositions) > 1 ) {
				$i = 0;
				if (mysqli_multi_query($connectStatutEncode, $sql)) {
					do {
						if ($resultStatutEncode = mysqli_store_result($connectStatutEncode)) {

							// if(mysqli_num_rows($result2) > 0 ) {

							// 	while ($row2 = mysqli_fetch_array($result2)) {
							// 		$NbrPlage = $row2['NbrPlage'];
							// 		$Hd1 = $row2['Hd1'];
							// 		$Hf1 = $row2['Hf1'];
							// 		$Hd2 = $row2['Hd2'];
							// 		$Hf2 = $row2['Hf2'];
							// 		$Lundi = $row2['Lundi'];
							// 		$Mardi = $row2['Mardi'];
							// 		$Mercredi = $row2['Mercredi'];
							// 		$Jeudi = $row2['Jeudi'];
							// 		$Vendredi = $row2['Vendredi'];
							// 		$Samedi = $row2['Samedi'];
							// 		$Dimanche = $row2['Dimanche'];
							// 	}
							// 	while($rowStatutEncode = mysqli_fetch_array($resultStatutEncode)) {

							// 		$utc_date = DateTime::createFromFormat(
							// 				'Y-m-d H:i:s',
							// 				$rowStatutEncode['Pos_DateTime_position'],
							// 				new DateTimeZone('UTC')
							// 		);
							// 		$local_date = $utc_date;
							// 		$local_date->setTimeZone(new DateTimeZone($_POST["timezoneEtape"]));

							// 		$dateNewDateTime = new DateTime();
							// 		if ( ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0].$Hd1[1]),intval($Hd1[2].$Hd1[3]))->format("H:i:s")))
							// 				&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0].$Hf1[1]),intval($Hf1[2].$Hf1[3]))->format("H:i:s"))	)	 )
							// 			||	( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0].$Hd2[1]),intval($Hd2[2].$Hd2[3]))->format("H:i:s")))
							// 				&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0].$Hf2[1]),intval($Hf2[2].$Hf2[3]))->format("H:i:s"))	)	 )  ) {

							// 			if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
							// 					($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
							// 					($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
							// 					($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
							// 					($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
							// 					($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
							// 					($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")) {

							// 				$cbalise[$i] = $queryFetchArray($cbalise,$rowStatutEncode,$i);
							// 				$i++;
							// 			}
							// 		}
							// 	}
							// }else{
								while($rowStatutEncode = mysqli_fetch_array($resultStatutEncode)){
									$cbalise[$i] = $queryFetchArray($cbalise,$rowStatutEncode,$i);
									$i++;
								}
							// }
							mysqli_free_result($resultStatutEncode);
						}
					} while (mysqli_more_results($connectStatutEncode) && mysqli_next_result($connectStatutEncode));
				}
			}else{
				$resultStatutEncode = mysqli_query($connectStatutEncode,$sql);

				// if(mysqli_num_rows($result2) > 0 ) {

				// 	while ($row2 = mysqli_fetch_array($result2)) {
				// 		$NbrPlage = $row2['NbrPlage'];
				// 		$Hd1 = $row2['Hd1'];
				// 		$Hf1 = $row2['Hf1'];
				// 		$Hd2 = $row2['Hd2'];
				// 		$Hf2 = $row2['Hf2'];
				// 		$Lundi = $row2['Lundi'];
				// 		$Mardi = $row2['Mardi'];
				// 		$Mercredi = $row2['Mercredi'];
				// 		$Jeudi = $row2['Jeudi'];
				// 		$Vendredi = $row2['Vendredi'];
				// 		$Samedi = $row2['Samedi'];
				// 		$Dimanche = $row2['Dimanche'];
				// 	}
				// 	while($rowStatutEncode = mysqli_fetch_array($resultStatutEncode)) {

				// 		$utc_date = DateTime::createFromFormat(
				// 				'Y-m-d H:i:s',
				// 				$rowStatutEncode['Pos_DateTime_position'],
				// 				new DateTimeZone('UTC')
				// 		);
				// 		$local_date = $utc_date;
				// 		$local_date->setTimeZone(new DateTimeZone($_POST["timezoneEtape"]));

				// 		$dateNewDateTime = new DateTime();
				// 		if ( ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0].$Hd1[1]),intval($Hd1[2].$Hd1[3]))->format("H:i:s")))
				// 				&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0].$Hf1[1]),intval($Hf1[2].$Hf1[3]))->format("H:i:s"))	)	 )
				// 			||	( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0].$Hd2[1]),intval($Hd2[2].$Hd2[3]))->format("H:i:s")))
				// 				&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0].$Hf2[1]),intval($Hf2[2].$Hf2[3]))->format("H:i:s"))	)	 )  ) {

				// 			if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
				// 					($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
				// 					($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
				// 					($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
				// 					($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
				// 					($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
				// 					($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")) {

				// 				$cbalise[$i] = $queryFetchArray($cbalise,$rowStatutEncode,$i);
				// 				$i++;
				// 			}
				// 		}
				// 	}
				// }else{
					while($rowStatutEncode = mysqli_fetch_array($resultStatutEncode)){
						$cbalise[$i] = $queryFetchArray($cbalise,$rowStatutEncode,$i);
						$i++;
					}
				// }
			}
			mysqli_close($connectStatutEncode);
			return $cbalise;
		}

	}

	$pdf = new PDF('L','mm','A4');
	$pdf->AliasNbPages();
	$pdf->SetFont('Times','',12);
	$nomDatabaseGpw=$_POST["nomDatabaseGpw"];
	$ipDatabaseGpw=$_POST["ipDatabaseGpw"];

	$rayonPOI =  array();
	$i = 0;

	$arrayPoi = $pdf->getPoiTracker($db_user_2,$db_pass_2);
	$ids = join(',',$arrayPoi);


	$connectEtapePOI = mysqli_connect($ipDatabaseGpw,$db_user_2,$db_pass_2,$nomDatabaseGpw);;
	$resultEtapePOI = mysqli_query($connectEtapePOI,"select * from tpoi WHERE Id IN ($ids)");
	$lengthEtapePOI = mysqli_num_rows($resultEtapePOI);
	while($rowPOI = mysqli_fetch_array($resultEtapePOI)){
		$nomPOI[$i] = utf8_decode($rowPOI['Name']);
		$descriptionPOI[$i] = utf8_decode($rowPOI['description']);
		$latPOI[$i] = $rowPOI['latitude'];
		$lngPOI[$i] = $rowPOI['longitude'];
		$adressePOI[$i] = $rowPOI['adresse'];
		$rayonPOI[$i] = $rowPOI['Rayon'];
		$i++;
	}
	mysqli_close($connectEtapePOI);

	$sql = "";
	$arrayTpositions = getAllPeriodTpositions($debutRapportEtape,$finRapportEtape);
	$i = 0;
	if (sizeof($arrayTpositions) > 1 ) {
		for ($i = 0; $i < sizeof($arrayTpositions); $i++) {
			$sql .= "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
						FROM $arrayTpositions[$i] WHERE (Pos_DateTime_position BETWEEN '" . $dUTC . "' AND '" . $fUTC . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapportEtape . "' )
						ORDER BY Pos_DateTime_position;";
		}
	}else{
		$sql = "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
					FROM $arrayTpositions[0] WHERE (Pos_DateTime_position BETWEEN '" . $dUTC . "' AND '" . $fUTC . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapportEtape . "' )
					ORDER BY Pos_DateTime_position";
	}
	$cbalise = $pdf->statutEncodeRapport($sql,$arrayTpositions,$db_user_2,$db_pass_2);




	$i=0;		//incrementation lecture sql
	$y=0;		//incrementation ligne tableau etape
	$v=0;		//incrementation nombre de position dans un trajet
	$boubou=0;

	$dateDebutEtape = array();
	$dateFinEtape = array();

	$lieuDebutEtape = array();
	$lieuFinEtape = array();

	$km = array();
	$vitesseMoyenne = array();
	$vitesseMax = array();
	$position = 0;
	$positionEtape = array();

	$dureeTrajet = array();
	$dureeTrajetTimestamp = array();
	$diffTotalTrajet = array();

	$dureeArret = array();
	$dureeArretTimestamp = array();
	$diffTotalArret = array();

	$latDebut = 0;
	$lngDebut = 0;
	$departureDate = 0;
	$conditionOk = "";
	$condition = "";


	$Hd1="";
	$Hf1="";
	$Hd2="";
	$Hf2="";
	$Lundi="";
	$Mardi="";
	$Mercredi="";
	$Jeudi="";
	$Vendredi="";
	$Samedi="";
	$Dimanche="";
	// $connection2=mysqli_connect($ipDatabaseGpw,$db_user_2, $db_pass_2,$nomDatabaseGpw);
	// $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $idBaliseRapportEtape . "' )";
	// $result2 = mysqli_query($connection2,$sql2);




	$connectionFinal = mysqli_connect($ipDatabaseGpw,$db_user_2,$db_pass_2,$nomDatabaseGpw);
	$result=mysqli_query($connectionFinal,$sql);
	if (sizeof($arrayTpositions) > 1 ) {

		if (mysqli_multi_query($connectionFinal, $sql)) {
			do {
				if ($result = mysqli_store_result($connectionFinal)) {
// 					if(mysqli_num_rows($result2) > 0 ) {

// 						while ($row2 = mysqli_fetch_array($result2)) {
// 							$NbrPlage = $row2['NbrPlage'];
// 							$Hd1 = $row2['Hd1'];
// 							$Hf1 = $row2['Hf1'];
// 							$Hd2 = $row2['Hd2'];
// 							$Hf2 = $row2['Hf2'];
// 							$Lundi = $row2['Lundi'];
// 							$Mardi = $row2['Mardi'];
// 							$Mercredi = $row2['Mercredi'];
// 							$Jeudi = $row2['Jeudi'];
// 							$Vendredi = $row2['Vendredi'];
// 							$Samedi = $row2['Samedi'];
// 							$Dimanche = $row2['Dimanche'];
// 						}
// 						while ($row = mysqli_fetch_array($result)) {
// 							$utc_date = DateTime::createFromFormat(
// 									'Y-m-d H:i:s',
// 									$row['Pos_DateTime_position'],
// 									new DateTimeZone('UTC')
// 							);
// 							$local_date = $utc_date;
// 							$local_date->setTimeZone(new DateTimeZone($timezone));
// 							ini_set('display_errors', 'off');

// 							$dateNewDateTime = new DateTime();
// 							if ( ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0].$Hd1[1]),intval($Hd1[2].$Hd1[3]))->format("H:i:s")))
// 									&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0].$Hf1[1]),intval($Hf1[2].$Hf1[3]))->format("H:i:s"))	)	 )
// 								||	( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0].$Hd2[1]),intval($Hd2[2].$Hd2[3]))->format("H:i:s")))
// 									&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0].$Hf2[1]),intval($Hf2[2].$Hf2[3]))->format("H:i:s"))	)	 )  ) {



// 								if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
// 										($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
// 										($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
// 										($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
// 										($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
// 										($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
// 										($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")
// 								) {
// 									if ($conditionOk == "") {
// 										if ((($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop")) ||
// 												(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop"))
// 												|| (($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop")) ||
// 												(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop"))
// 										) {

// 											$latDebut = $row["Pos_Latitude"];
// 											$lngDebut = $row["Pos_Longitude"];

// 											// $vitesse[$y][$v] = $row['Pos_Vitesse'];
// 											$dateDebutEtape[$y] = $local_date->format('Y-m-d H:i:s');


// 											$poiRetenu = "";
// 											if ($lengthEtapePOI) {
// 												for ($z = 0; $z < $lengthEtapePOI; $z++) {
// 													$distancePoiEtEtape = $pdf->get_distance_m2($latPOI[$z], $lngPOI[$z], $row["Pos_Latitude"], $row["Pos_Longitude"]);
// 													if ($poiRetenu == "") {
// 														if ($distancePoiEtEtape < 0.1) {
// 															if ($distancePoiEtEtape * 1609.344 < $rayonPOI[$z]) {
// 																if ($descriptionPOI[$z]) {
// 																	$lieuDebutEtape[$y] = $nomPOI[$z] . " - " . $descriptionPOI[$z];
// 																} else {
// 																	$lieuDebutEtape[$y] = $nomPOI[$z] . " " . $descriptionPOI[$z];
// 																}
// 																$poiRetenu = "1";
// 															} else {
// 																$lieuDebutEtape[$y] = utf8_decode($row["Pos_Adresse"]);
// 															}
// 														} else {
// 															$lieuDebutEtape[$y] = utf8_decode($row["Pos_Adresse"]);
// 														}
// 													}
// 												}
// 											} else {
// 												$lieuDebutEtape[$y] = utf8_decode($row["Pos_Adresse"]);
// 											}

// 											$departureDate = strtotime($local_date->format('Y-m-d H:i:s'));

// 											$dateFIN = strtotime($local_date->format('Y-m-d H:i:s'));
// 											if ($boubou != 0) {
// 												$diffArret = $dateFIN - $dateDebut;
// 												//					$diffArret = $dateDebut[$y] - $dateFIN[$y - 1];
// 												$dateArret = new DateTime();
// 												$dateArret->setTimestamp($diffArret);
// 												// $dureeArretTimestamp = $dateArret;
// 												$dureeArret[$y] = $dateArret->format('H:i:s');
// 												$boubou = 0;
// 											}
// 											$v++;
// 											$condition = "ok";
// 											$conditionOk = "ok";
// 										}
// 									}
// 									if ($condition == "ok") {

// 										if ((($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop")) ||
// 												(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop"))
// 												|| (($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop")) ||
// 												(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop"))
// 										) {
// 											if ($row['Pos_Vitesse'] != 0) $vitesse[$y][$v] = $row['Pos_Vitesse'];

// 											$vitesseMoyenne[$y] = round(array_sum($vitesse[$y]) / count($vitesse[$y]), 2);
// 											$vitesseMax[$y] = max($vitesse[$y]);

// 											$arrivalDate = strtotime($local_date->format('Y-m-d H:i:s'));
// 											$boubou = 1;
// 											$v++;

// 										}


// 										//		if( ( ($cbalise[$i] == "stop") && ($cbalise[$i+1] == "stop") ) || ( ($cbalise[$i] == "stop") && ($cbalise[$i+1] != "stop") ) ) {
// 										if ($cbalise[$i] != "stop" && ($cbalise[$i + 1] == "stop")) {
// 											$km[$y] = $pdf->get_distance_m($latDebut, $lngDebut, $row["Pos_Latitude"], $row["Pos_Longitude"]);
// //                                            $km[$y] = round(SphericalGeometry::computeDistanceBetween(new LatLng($latDebut, $lngDebut), new LatLng($row["Pos_Latitude"], $row["Pos_Longitude"]))/1000);



//                                             $diffTrajet = ($arrivalDate - $departureDate);

// 											$dateTrajet = new DateTime();
// 											$dateTrajet->setTimestamp($diffTrajet);
// 											$dureeTrajetTimestamp[$y] = $dateTrajet;
// 											$dureeTrajet[$y] = $dateTrajet->format('H:i:s');

// 											$dateDebut = strtotime($local_date->format('Y-m-d H:i:s'));

// 											$dateFinEtape[$y] = $local_date->format('Y-m-d H:i:s');
// 											$poiRetenu = "";
// 											if ($lengthEtapePOI) {
// 												for ($z = 0; $z < $lengthEtapePOI; $z++) {
// 													$distancePoiEtEtape = $pdf->get_distance_m2($latPOI[$z], $lngPOI[$z], $row["Pos_Latitude"], $row["Pos_Longitude"]);
// 													if ($poiRetenu == "") {
// 														if ($distancePoiEtEtape < 0.1) {
// 															if ($distancePoiEtEtape * 1609.344 < $rayonPOI[$z]) {
// 																if ($descriptionPOI[$z]) {
// 																	$lieuFinEtape[$y] = $nomPOI[$z] . " - " . $descriptionPOI[$z];
// 																} else {
// 																	$lieuFinEtape[$y] = $nomPOI[$z] . " " . $descriptionPOI[$z];
// 																}
// 																$poiRetenu = "1";
// 															} else {
// 																$lieuFinEtape[$y] = utf8_decode($row["Pos_Adresse"]);
// 															}
// 														} else {
// 															$lieuFinEtape[$y] = utf8_decode($row["Pos_Adresse"]);
// 														}
// 													}
// 												}
// 											} else {
// 												$lieuFinEtape[$y] = utf8_decode($row["Pos_Adresse"]);
// 											}

// 											$positionEtape[$y] = $position;
// 											$y++;
// 											$v = 0;
// 											$position = 0;
// 											$condition = "";
// 											$conditionOk = "";
// 										}
// 									}

// 									$position++;
// 									$i++;
// 								}
// 							}
// 						}
// 					}else {
						while ($row = mysqli_fetch_array($result)) {
							$utc_date = DateTime::createFromFormat(
									'Y-m-d H:i:s',
									$row['Pos_DateTime_position'],
									new DateTimeZone('UTC')
							);
							$local_date = $utc_date;
							$local_date->setTimeZone(new DateTimeZone($timezone));
							ini_set('display_errors', 'off');

							if ($conditionOk == "") {
								if ((($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop")) ||
										(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop"))
										|| (($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop")) ||
										(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop"))
								) {

									$latDebut = $row["Pos_Latitude"];
									$lngDebut = $row["Pos_Longitude"];

									// $vitesse[$y][$v] = $row['Pos_Vitesse'];
									$dateDebutEtape[$y] = $local_date->format('Y-m-d H:i:s');


									$poiRetenu = "";
									if ($lengthEtapePOI) {
										for ($z = 0; $z < $lengthEtapePOI; $z++) {
											$distancePoiEtEtape = $pdf->get_distance_m2($latPOI[$z], $lngPOI[$z], $row["Pos_Latitude"], $row["Pos_Longitude"]);
											if ($poiRetenu == "") {
												if ($distancePoiEtEtape < 0.1) {
													if ($distancePoiEtEtape * 1609.344 < $rayonPOI[$z]) {
														if ($descriptionPOI[$z]) {
															$lieuDebutEtape[$y] = $nomPOI[$z] . " - " . $descriptionPOI[$z];
														} else {
															$lieuDebutEtape[$y] = $nomPOI[$z] . " " . $descriptionPOI[$z];
														}
														$poiRetenu = "1";
													} else {
														$lieuDebutEtape[$y] = utf8_decode($row["Pos_Adresse"]);
													}
												} else {
													$lieuDebutEtape[$y] = utf8_decode($row["Pos_Adresse"]);
												}
											}
										}
									} else {
										$lieuDebutEtape[$y] = utf8_decode($row["Pos_Adresse"]);
									}

									$departureDate = strtotime($local_date->format('Y-m-d H:i:s'));

									$dateFIN = strtotime($local_date->format('Y-m-d H:i:s'));
									if ($boubou != 0) {
										$diffArret = $dateFIN - $dateDebut;
										//					$diffArret = $dateDebut[$y] - $dateFIN[$y - 1];
										$dateArret = new DateTime();
										$dateArret->setTimestamp($diffArret);
										// $dureeArretTimestamp = $dateArret;
										$dureeArret[$y] = $dateArret->format('H:i:s');
										$boubou = 0;
									}
									$v++;
									$condition = "ok";
									$conditionOk = "ok";
								}
							}
							if ($condition == "ok") {

								if ((($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop")) ||
										(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop"))
										|| (($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop")) ||
										(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop"))
								) {
									if ($row['Pos_Vitesse'] != 0) $vitesse[$y][$v] = $row['Pos_Vitesse'];

									$vitesseMoyenne[$y] = round(array_sum($vitesse[$y]) / count($vitesse[$y]), 2);
									$vitesseMax[$y] = max($vitesse[$y]);

									$arrivalDate = strtotime($local_date->format('Y-m-d H:i:s'));
									$boubou = 1;
									$v++;

								}


								//		if( ( ($cbalise[$i] == "stop") && ($cbalise[$i+1] == "stop") ) || ( ($cbalise[$i] == "stop") && ($cbalise[$i+1] != "stop") ) ) {
								if ($cbalise[$i] != "stop" && ($cbalise[$i + 1] == "stop")) {
									$km[$y] = $pdf->get_distance_m($latDebut, $lngDebut, $row["Pos_Latitude"], $row["Pos_Longitude"]);
//                                    $km[$y] = round(SphericalGeometry::computeDistanceBetween(new LatLng($latDebut, $lngDebut), new LatLng($row["Pos_Latitude"], $row["Pos_Longitude"]))/1000);

                                    $diffTrajet = ($arrivalDate - $departureDate);

									$dateTrajet = new DateTime();
									$dateTrajet->setTimestamp($diffTrajet);
									$dureeTrajetTimestamp[$y] = $dateTrajet;
									$dureeTrajet[$y] = $dateTrajet->format('H:i:s');

									$dateDebut = strtotime($local_date->format('Y-m-d H:i:s'));

									$dateFinEtape[$y] = $local_date->format('Y-m-d H:i:s');
									$poiRetenu = "";
									if ($lengthEtapePOI) {
										for ($z = 0; $z < $lengthEtapePOI; $z++) {
											$distancePoiEtEtape = $pdf->get_distance_m2($latPOI[$z], $lngPOI[$z], $row["Pos_Latitude"], $row["Pos_Longitude"]);
											if ($poiRetenu == "") {
												if ($distancePoiEtEtape < 0.1) {
													if ($distancePoiEtEtape * 1609.344 < $rayonPOI[$z]) {
														if ($descriptionPOI[$z]) {
															$lieuFinEtape[$y] = $nomPOI[$z] . " - " . $descriptionPOI[$z];
														} else {
															$lieuFinEtape[$y] = $nomPOI[$z] . " " . $descriptionPOI[$z];
														}
														$poiRetenu = "1";
													} else {
														$lieuFinEtape[$y] = utf8_decode($row["Pos_Adresse"]);
													}
												} else {
													$lieuFinEtape[$y] = utf8_decode($row["Pos_Adresse"]);
												}
											}
										}
									} else {
										$lieuFinEtape[$y] = utf8_decode($row["Pos_Adresse"]);
									}

									$positionEtape[$y] = $position;
									$y++;
									$v = 0;
									$position = 0;
									$condition = "";
									$conditionOk = "";
								}
							}

							$position++;
							$i++;
						}
					// }

					mysqli_free_result($result);
				}
			} while (mysqli_more_results($connectionFinal) && mysqli_next_result($connectionFinal));
		}
	}else{

		$result=mysqli_query($connectionFinal,$sql);
		if( $result !== false ) {

// 			if(mysqli_num_rows($result2) > 0 ) {

// 				while ($row2 = mysqli_fetch_array($result2)) {
// 					$NbrPlage = $row2['NbrPlage'];
// 					$Hd1 = $row2['Hd1'];
// 					$Hf1 = $row2['Hf1'];
// 					$Hd2 = $row2['Hd2'];
// 					$Hf2 = $row2['Hf2'];
// 					$Lundi = $row2['Lundi'];
// 					$Mardi = $row2['Mardi'];
// 					$Mercredi = $row2['Mercredi'];
// 					$Jeudi = $row2['Jeudi'];
// 					$Vendredi = $row2['Vendredi'];
// 					$Samedi = $row2['Samedi'];
// 					$Dimanche = $row2['Dimanche'];
// 				}
// 				while ($row = mysqli_fetch_array($result)) {
// 					$utc_date = DateTime::createFromFormat(
// 							'Y-m-d H:i:s',
// 							$row['Pos_DateTime_position'],
// 							new DateTimeZone('UTC')
// 					);
// 					$local_date = $utc_date;
// 					$local_date->setTimeZone(new DateTimeZone($timezone));
// 					ini_set('display_errors', 'off');
// 					$dateNewDateTime = new DateTime();
// 					if ( ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0].$Hd1[1]),intval($Hd1[2].$Hd1[3]))->format("H:i:s")))
// 							&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0].$Hf1[1]),intval($Hf1[2].$Hf1[3]))->format("H:i:s"))	)	 )
// 						||	( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0].$Hd2[1]),intval($Hd2[2].$Hd2[3]))->format("H:i:s")))
// 							&& ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0].$Hf2[1]),intval($Hf2[2].$Hf2[3]))->format("H:i:s"))	)	 )  ) {



// 						if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
// 								($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
// 								($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
// 								($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
// 								($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
// 								($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
// 								($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")
// 						) {
// 							if ($conditionOk == "") {
// 								if ((($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop")) ||
// 										(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop"))
// 										|| (($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop")) ||
// 										(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop"))
// 								) {

// 									$latDebut = $row["Pos_Latitude"];
// 									$lngDebut = $row["Pos_Longitude"];

// 									// $vitesse[$y][$v] = $row['Pos_Vitesse'];
// 									$dateDebutEtape[$y] = $local_date->format('Y-m-d H:i:s');


// 									$poiRetenu = "";
// 									if ($lengthEtapePOI) {
// 										for ($z = 0; $z < $lengthEtapePOI; $z++) {
// 											$distancePoiEtEtape = $pdf->get_distance_m2($latPOI[$z], $lngPOI[$z], $row["Pos_Latitude"], $row["Pos_Longitude"]);
// 											if ($poiRetenu == "") {
// 												if ($distancePoiEtEtape < 0.1) {
// 													if ($distancePoiEtEtape * 1609.344 < $rayonPOI[$z]) {
// 														if ($descriptionPOI[$z]) {
// 															$lieuDebutEtape[$y] = $nomPOI[$z] . " - " . $descriptionPOI[$z];
// 														} else {
// 															$lieuDebutEtape[$y] = $nomPOI[$z] . " " . $descriptionPOI[$z];
// 														}
// 														$poiRetenu = "1";
// 													} else {
// 														$lieuDebutEtape[$y] = utf8_decode($row["Pos_Adresse"]);
// 													}
// 												} else {
// 													$lieuDebutEtape[$y] = utf8_decode($row["Pos_Adresse"]);
// 												}
// 											}
// 										}
// 									} else {
// 										$lieuDebutEtape[$y] = utf8_decode($row["Pos_Adresse"]);
// 									}

// 									$departureDate = strtotime($local_date->format('Y-m-d H:i:s'));

// 									$dateFIN = strtotime($local_date->format('Y-m-d H:i:s'));
// 									if ($boubou != 0) {
// 										$diffArret = $dateFIN - $dateDebut;
// 										//					$diffArret = $dateDebut[$y] - $dateFIN[$y - 1];
// 										$dateArret = new DateTime();
// 										$dateArret->setTimestamp($diffArret);
// 										// $dureeArretTimestamp = $dateArret;
// 										$dureeArret[$y] = $dateArret->format('H:i:s');
// 										$boubou = 0;
// 									}
// 									$v++;
// 									$condition = "ok";
// 									$conditionOk = "ok";
// 								}
// 							}
// 							if ($condition == "ok") {

// 								if ((($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop")) ||
// 										(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop"))
// 										|| (($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop")) ||
// 										(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop"))
// 								) {
// 									if ($row['Pos_Vitesse'] != 0) $vitesse[$y][$v] = $row['Pos_Vitesse'];

// 									$vitesseMoyenne[$y] = round(array_sum($vitesse[$y]) / count($vitesse[$y]), 2);
// 									$vitesseMax[$y] = max($vitesse[$y]);

// 									$arrivalDate = strtotime($local_date->format('Y-m-d H:i:s'));
// 									$boubou = 1;
// 									$v++;

// 								}


// 								//		if( ( ($cbalise[$i] == "stop") && ($cbalise[$i+1] == "stop") ) || ( ($cbalise[$i] == "stop") && ($cbalise[$i+1] != "stop") ) ) {
// 								if ($cbalise[$i] != "stop" && ($cbalise[$i + 1] == "stop")) {
// 									$km[$y] = $pdf->get_distance_m($latDebut, $lngDebut, $row["Pos_Latitude"], $row["Pos_Longitude"]);
// //                                    $km[$y] = round(SphericalGeometry::computeDistanceBetween(new LatLng($latDebut, $lngDebut), new LatLng($row["Pos_Latitude"], $row["Pos_Longitude"]))/1000);


//                                     $diffTrajet = ($arrivalDate - $departureDate);

// 									$dateTrajet = new DateTime();
// 									$dateTrajet->setTimestamp($diffTrajet);
// 									$dureeTrajetTimestamp[$y] = $dateTrajet;
// 									$dureeTrajet[$y] = $dateTrajet->format('H:i:s');

// 									$dateDebut = strtotime($local_date->format('Y-m-d H:i:s'));

// 									$dateFinEtape[$y] = $local_date->format('Y-m-d H:i:s');
// 									$poiRetenu = "";
// 									if ($lengthEtapePOI) {
// 										for ($z = 0; $z < $lengthEtapePOI; $z++) {
// 											$distancePoiEtEtape = $pdf->get_distance_m2($latPOI[$z], $lngPOI[$z], $row["Pos_Latitude"], $row["Pos_Longitude"]);
// 											if ($poiRetenu == "") {
// 												if ($distancePoiEtEtape < 0.1) {
// 													if ($distancePoiEtEtape * 1609.344 < $rayonPOI[$z]) {
// 														if ($descriptionPOI[$z]) {
// 															$lieuFinEtape[$y] = $nomPOI[$z] . " - " . $descriptionPOI[$z];
// 														} else {
// 															$lieuFinEtape[$y] = $nomPOI[$z] . " " . $descriptionPOI[$z];
// 														}
// 														$poiRetenu = "1";
// 													} else {
// 														$lieuFinEtape[$y] = utf8_decode($row["Pos_Adresse"]);
// 													}
// 												} else {
// 													$lieuFinEtape[$y] = utf8_decode($row["Pos_Adresse"]);
// 												}
// 											}
// 										}
// 									} else {
// 										$lieuFinEtape[$y] = utf8_decode($row["Pos_Adresse"]);
// 									}

// 									$positionEtape[$y] = $position;
// 									$y++;
// 									$v = 0;
// 									$position = 0;
// 									$condition = "";
// 									$conditionOk = "";
// 								}
// 							}

// 							$position++;
// 							$i++;
// 						}
// 					}
// 				}
// 			}else {
				while ($row = mysqli_fetch_array($result)) {
					$utc_date = DateTime::createFromFormat(
							'Y-m-d H:i:s',
							$row['Pos_DateTime_position'],
							new DateTimeZone('UTC')
					);
					$local_date = $utc_date;
					$local_date->setTimeZone(new DateTimeZone($timezone));
					ini_set('display_errors', 'off');

					if ($conditionOk == "") {
						if ((($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop")) ||
								(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop"))
								|| (($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop")) ||
								(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop"))
						) {

							$latDebut = $row["Pos_Latitude"];
							$lngDebut = $row["Pos_Longitude"];

							// $vitesse[$y][$v] = $row['Pos_Vitesse'];
							$dateDebutEtape[$y] = $local_date->format('Y-m-d H:i:s');


							$poiRetenu = "";
							if ($lengthEtapePOI) {
								for ($z = 0; $z < $lengthEtapePOI; $z++) {
									$distancePoiEtEtape = $pdf->get_distance_m2($latPOI[$z], $lngPOI[$z], $row["Pos_Latitude"], $row["Pos_Longitude"]);
									if ($poiRetenu == "") {
										if ($distancePoiEtEtape < 0.1) {
											if ($distancePoiEtEtape * 1609.344 < $rayonPOI[$z]) {
												if ($descriptionPOI[$z]) {
													$lieuDebutEtape[$y] = $nomPOI[$z] . " - " . $descriptionPOI[$z];
												} else {
													$lieuDebutEtape[$y] = $nomPOI[$z] . " " . $descriptionPOI[$z];
												}
												$poiRetenu = "1";
											} else {
												$lieuDebutEtape[$y] = utf8_decode($row["Pos_Adresse"]);
											}
										} else {
											$lieuDebutEtape[$y] = utf8_decode($row["Pos_Adresse"]);
										}
									}
								}
							} else {
								$lieuDebutEtape[$y] = utf8_decode($row["Pos_Adresse"]);
							}

							$departureDate = strtotime($local_date->format('Y-m-d H:i:s'));

							$dateFIN = strtotime($local_date->format('Y-m-d H:i:s'));
							if ($boubou != 0) {
								$diffArret = $dateFIN - $dateDebut;
								//					$diffArret = $dateDebut[$y] - $dateFIN[$y - 1];
								$dateArret = new DateTime();
								$dateArret->setTimestamp($diffArret);
								// $dureeArretTimestamp = $dateArret;
								$dureeArret[$y] = $dateArret->format('H:i:s');
								$boubou = 0;
							}
							$v++;
							$condition = "ok";
							$conditionOk = "ok";
						}
					}
					if ($condition == "ok") {

						if ((($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop")) ||
								(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop"))
								|| (($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop")) ||
								(($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop"))
						) {
							if ($row['Pos_Vitesse'] != 0) $vitesse[$y][$v] = $row['Pos_Vitesse'];

							$vitesseMoyenne[$y] = round(array_sum($vitesse[$y]) / count($vitesse[$y]), 2);
							$vitesseMax[$y] = max($vitesse[$y]);

							$arrivalDate = strtotime($local_date->format('Y-m-d H:i:s'));
							$boubou = 1;
							$v++;

						}


						//		if( ( ($cbalise[$i] == "stop") && ($cbalise[$i+1] == "stop") ) || ( ($cbalise[$i] == "stop") && ($cbalise[$i+1] != "stop") ) ) {
						if ($cbalise[$i] != "stop" && ($cbalise[$i + 1] == "stop")) {
							$km[$y] = $pdf->get_distance_m($latDebut, $lngDebut, $row["Pos_Latitude"], $row["Pos_Longitude"]);
//                            $km[$y] = round(SphericalGeometry::computeDistanceBetween(new LatLng($latDebut, $lngDebut), new LatLng($row["Pos_Latitude"], $row["Pos_Longitude"]))/1000);



                            $diffTrajet = ($arrivalDate - $departureDate);

							$dateTrajet = new DateTime();
							$dateTrajet->setTimestamp($diffTrajet);
							$dureeTrajetTimestamp[$y] = $dateTrajet;
							$dureeTrajet[$y] = $dateTrajet->format('H:i:s');

							$dateDebut = strtotime($local_date->format('Y-m-d H:i:s'));

							$dateFinEtape[$y] = $local_date->format('Y-m-d H:i:s');
							$poiRetenu = "";
							if ($lengthEtapePOI) {
								for ($z = 0; $z < $lengthEtapePOI; $z++) {
									$distancePoiEtEtape = $pdf->get_distance_m2($latPOI[$z], $lngPOI[$z], $row["Pos_Latitude"], $row["Pos_Longitude"]);
									if ($poiRetenu == "") {
										if ($distancePoiEtEtape < 0.1) {
											if ($distancePoiEtEtape * 1609.344 < $rayonPOI[$z]) {
												if ($descriptionPOI[$z]) {
													$lieuFinEtape[$y] = $nomPOI[$z] . " - " . $descriptionPOI[$z];
												} else {
													$lieuFinEtape[$y] = $nomPOI[$z] . " " . $descriptionPOI[$z];
												}
												$poiRetenu = "1";
											} else {
												$lieuFinEtape[$y] = utf8_decode($row["Pos_Adresse"]);
											}
										} else {
											$lieuFinEtape[$y] = utf8_decode($row["Pos_Adresse"]);
										}
									}
								}
							} else {
								$lieuFinEtape[$y] = utf8_decode($row["Pos_Adresse"]);
							}

							$positionEtape[$y] = $position;
							$y++;
							$v = 0;
							$position = 0;
							$condition = "";
							$conditionOk = "";
						}
					}

					$position++;
					$i++;
				}
			// }

		}
		mysqli_free_result($result);
	}
	mysqli_close($connectionFinal);


	$filename= "Rapport d'étape de " . $nomBaliseRapportEtape . " du ". $debutRapportEtape . " au " . $finRapportEtape  . ".pdf";

	$pdf->pagePrincipale($nomBaliseRapportEtape,$idBaliseRapportEtape,$numeroEtape,$nombreMaxEtape,$km,$vitesseMoyenne,$vitesseMax,$dureeTrajet,$dureeArret,$positionEtape,$dateDebutEtape,$dateFinEtape,$lieuDebutEtape,$lieuFinEtape);
	if ((isset($_POST['avecCarto']))){
		$pdf->pageCarto($numeroEtape,$urlCarto);
	}
	if ((isset($_POST['graphVitesseEtape']))){
		$pdf->pageGraphe($numeroEtape,$idBaliseRapportEtape,$debutRapportEtape,$finRapportEtape);
	}

	$pdf->Output($filename,'I');
	?>