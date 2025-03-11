<?php

session_start();
$_SESSION['CREATED'] = time();

include '../function.php';
include '../dbconnect2.php';
include('../dbtpositions.php');
include('../ChromePhp.php');
//	include('../../../lib/tubalmartin-spherical-geometry/spherical-geometry.class.php');

require('../../../lib/fpdf/mysql_table.php');

$formatLangDateTime = "";

require_once ("../../../lib/php-gettext-1.0.12/gettext.inc");
$locale = "fr_FR";
if (isset($_SESSION["language"]))
{
    $locale = $_SESSION['language'];
}
else
{
    $_SESSION['language'] = "fr_FR";
    $locale = "fr_FR";
}
T_setlocale(LC_MESSAGES, $locale);
$encoding = "UTF-8";
$domain = "messages";
bindtextdomain($domain, '../../../locale');
bind_textdomain_codeset($domain, $encoding);
textdomain($domain);
if ((substr($_SESSION['language'], -2) == "US"))
    $formatLangDateTime = "Y-m-d h:i:s A";
else
    $formatLangDateTime = "d-m-Y H:i:s";

$idBaliseRapport = $_POST["idBaliseRapport"];
$timezone = $_POST["timezone"];
$carburantRapport = $_POST['carburantRapport'];
$carburant100KmRapport = $_POST['carburant100KmRapport'];
$typeCarburantRapport = $_POST['typeCarburantRapport'];
$debutRapport = $_POST['debutRapport'];
$finRapport = $_POST['finRapport'];
$nomBaliseRapport = $_POST['nomBaliseRapport'];
//Chromephp::log($nomBaliseRapport);
$titrePeriode = utf8_decode(_('rapport_periodeentre') . " " . date($formatLangDateTime, strtotime($debutRapport)) . " " . _('rapport_et') . " " . date($formatLangDateTime, strtotime($finRapport)));
$dUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($debutRapport)), $timezone);
$fUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($finRapport)), $timezone);

ini_set('display_errors', 'off');

class PDF extends PDF_MySQL_Table
{

    var $col = 0;
    var $y0;
    var $widths;
    var $aligns;

    function SetWidths($w)
    {
        //Tableau des largeurs de colonnes
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        //Tableau des alignements de colonnes
        $this->aligns = $a;
    }

    function Row($data)
    {
        //Calcule la hauteur de la ligne
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Effectue un saut de page si n�cessaire
        $this->CheckPageBreak($h);
        //Dessine les cellules
        for ($i = 0; $i < count($data); $i++)
        {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Sauve la position courante
            $x = $this->GetX();
            $y = $this->GetY();
            //Dessine le cadre
            $this->Rect($x, $y, $w, $h);
            //Imprime le texte
            $this->MultiCell($w, 5, $data[$i], 0, $a);
            //Repositionne � droite
            $this->SetXY($x + $w, $y);
        }
        //Va � la ligne
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //Si la hauteur h provoque un d�bordement, saut de page manuel
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        //Calcule le nombre de lignes qu'occupe un MultiCell de largeur w
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb)
        {
            $c = $s[$i];
            if ($c == "\n")
            {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l+=$cw[$c];
            if ($l > $wmax)
            {
                if ($sep == -1)
                {
                    if ($i == $j)
                        $i++;
                }
                else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

    function Header()
    {
        $titre = _('rapport_rapportgeo3x');
        $this->SetFont('Arial', 'B', 15);
        $this->SetFillColor(60, 70, 69);
        $this->SetTextColor(254, 254, 254);
        $this->Cell(0, 18, $titre, 1, 1, 'C', true);
        $this->Ln(5);
        $this->Image('../../assets/img/logo.png', 14, 13, 33);		// Geofence
    }

    function Footer()
    {
        $this->SetTextColor(0, 0, 0);
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(30, 10, _('rapport_commentaire'), 0, 0, 'C');
        $this->Cell(220, 10, '', 1, 0, 'C');
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function SetCol($col)
    {
        $this->col = $col;
        $x = 10 + $col * 65;
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }
    /*     * **********************************************************************************************
     * ************************************************************************************************	PAGE PRINCIPALE
     * ********************************************************************************************** */

    function pagePrincipale($titrePeriode, $nomBaliseRapport, $idBaliseRapport, $position, $etape, $totalKm, $totalDateTrajet, $totalDateArret, $additionDureeTrajet, $additionDureeArret, $litrePerKm, $emisionCO2, $typeCarburant, $litrePer100Km, $litreCarburantConsomme)
    {
        include("../../../lib/pChart2.1.4/class/pData.class.php");
        include("../../../lib/pChart2.1.4/class/pDraw.class.php");
        include("../../../lib/pChart2.1.4/class/pImage.class.php");
        include("../../../lib/pChart2.1.4/class/pPie.class.php");
        include("../../../lib/pChart2.1.4/class/pIndicator.class.php");
        //Chromephp::log($totalDateTrajet);
        /* pData object creation */
        $MyData = new pData();

        /* Data definition */
//			$str_time = $totalDateTrajet;
//			$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
//			$minutes = "";
//			$seconds = "";
//			sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
//			$time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
//
//			$str_time2 = $totalDateArret;
//			$str_time2 = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time2);
//			$minutes2 = "";
//			$seconds2 = "";
//			sscanf($str_time2, "%d:%d:%d", $hours2, $minutes2, $seconds2);
//			$time_seconds2 = $hours2 * 3600 + $minutes2 * 60 + $seconds2;

        $MyData->addPoints(array($additionDureeTrajet / 10000, $additionDureeArret / 10000), "Value");

        /* Labels definition */
        $MyData->addPoints(array(utf8_decode(_('rapport_dureedestrajets')), utf8_decode(_('rapport_dureedesarrets'))), "Legend");
        $MyData->setAbscissa("Legend");

        /* Create the pChart object */
        $myPicture = new pImage(500, 250, $MyData);

        /* Create the pPie object */
        $PieChart = new pPie($myPicture, $MyData);

        /* Enable shadow computing */
        $myPicture->setShadow(FALSE);

        /* Set the default font properties */
        $myPicture->setFontProperties(array("FontName" => "../../../lib/pChart2.1.4/fonts/Forgotte.ttf", "FontSize" => 10, "R" => 80, "G" => 80, "B" => 80));

        /* Draw a splitted pie chart */
        $PieChart->setSliceColor(0, array("R" => 60, "G" => 55, "B" => 128));

        $PieChart->setSliceColor(1, array("R" => 175, "G" => 0, "B" => 0));
        $PieChart->draw3DPie(250, 150, array("WriteValues" => TRUE, "Precision" => TRUE, "DataGapAngle" => 0, "Radius" => 150, "DrawLabels" => TRUE, "DataGapRadius" => 6, "Border" => TRUE));

        /* Render the picture (choose the best way) */
       // $nomDuFichier = $_POST["idBaliseRapport"] . $_POST["debutRapport"] . $_POST["finRapport"];
        $nomDuFichier = $_POST["idBaliseRapport"] . $_SESSION["username"] . $_POST["debutRapport"] . $_POST["finRapport"];
        $nomDuFichier = str_replace(":", "", $nomDuFichier);
        $nomDuFichier = str_replace("-", "", $nomDuFichier);
        $nomDuFichier = str_replace(" ", "", $nomDuFichier);
        $nomDuFichier = str_replace("*", "", $nomDuFichier);
        
        $myPicture->Render("../../assets/img/pie/rapportpie" . $nomDuFichier . ".png");

        //ChromePhp::LOG("additionDureeArret",$additionDureeArret);
        //ChromePhp::LOG("additionDureeTrajet",$additionDureeTrajet);
        
        $stringJour = _('jour');
       // ChromePhp::LOG("additionDureeTrajet",$additionDureeTrajet);
        //ChromePhp::LOG("totalDateArret",$totalDateArret);
       // ChromePhp::LOG("additionDureeArret",$additionDureeArret);
       // ChromePhp::LOG("totalDateArret",$totalDateArret);
        //Affichage duree trajet
        
        if ($additionDureeTrajet / (3600) >= 744)
        {
            $valueTrajet = array(
                "years" => 0, "days" => 0, "hours" => 0,
                "minutes" => 0, "seconds" => 0,
            );
            if ($additionDureeTrajet >= 86400)
            {
                $valueTrajet["days"] = floor($additionDureeTrajet / 86400);
                $additionDureeTrajet = ($additionDureeTrajet % 86400);
            }
            if ($additionDureeTrajet >= 3600)
            {
                $valueTrajet["hours"] = floor($additionDureeTrajet / 3600);
                $additionDureeTrajet = ($additionDureeTrajet % 3600);
            }
            if ($additionDureeTrajet >= 60)
            {
                $valueTrajet["minutes"] = floor($additionDureeTrajet / 60);
                $additionDureeTrajet = ($additionDureeTrajet % 60);
            }
            $valueTrajet["seconds"] = floor($additionDureeTrajet);

            $totalDureeTrajet = $valueTrajet["days"] . strtolower($stringJour[0]) . " " . sprintf("%02d", $valueTrajet["hours"])
                    . ":" . sprintf("%02d", $valueTrajet["minutes"]) . ":" . sprintf("%02d", $valueTrajet["seconds"]);
        }
        else
        if ($additionDureeTrajet / (3600) >= 24)
        {
            $totalDureeTrajet = $totalDateTrajet->format('d')-1 . strtolower($stringJour[0]) . $totalDateTrajet->format(' H:i:s');
        }
        else
        {
            $totalDureeTrajet = $totalDateTrajet->format('H:i:s');
        }
           //Affichage duree arret
        if ($additionDureeArret / (60 * 60) >= 744)
        {
            $valueArret = array(
                "years" => 0, "days" => 0, "hours" => 0,
                "minutes" => 0, "seconds" => 0,
            );
            if ($additionDureeArret >= 86400)
            {
                $valueArret["days"] = floor($additionDureeArret / 86400);
                $additionDureeArret = ($additionDureeArret % 86400);
            }
            if ($additionDureeArret >= 3600)
            {
                $valueArret["hours"] = floor($additionDureeArret / 3600);
                $additionDureeArret = ($additionDureeArret % 3600);
            }
            if ($additionDureeArret >= 60)
            {
                $valueArret["minutes"] = floor($additionDureeArret / 60);
                $additionDureeArret = ($additionDureeArret % 60);
            }
            $valueArret["seconds"] = floor($additionDureeArret);
            $totalDureeArret = $valueArret["days"] . strtolower($stringJour[0]) . " " . sprintf("%02d", $valueArret["hours"]) . ":" .
                    sprintf("%02d", $valueArret["minutes"]) . ":" . sprintf("%02d", $valueArret["seconds"]);
        }
        else if ($additionDureeArret / (60 * 60) >= 24)
        {
            $totalDureeArret = $totalDateArret->format('d')-1 . strtolower($stringJour[0]) . $totalDateArret->format(' H:i:s');
        }
        else
        {
            $totalDureeArret = $totalDateArret->format('H:i:s');
        }

        require_once ("../../../lib/php-gettext-1.0.12/gettext.inc");
        $locale = "fr_FR";
        if (isset($_SESSION["language"]))
        {
            $locale = $_SESSION['language'];
        }
        else
        {
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
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(254, 254, 254);
        $this->SetFillColor(96, 104, 103);
        $this->Cell(0, 6, "$titrePeriode", 0, 1, 'C', true);
        $this->Ln(4);

        // Sauvegarde de l'ordonn�e
        $this->y0 = $this->GetY();
        $this->SetXY(45, 53);
        $this->SetTextColor(254, 254, 254);
        $this->SetFillColor(96, 104, 103);
        $this->Cell(100, 6, utf8_decode(_('rapport_informationbalise') . ": $nomBaliseRapport"), 0, 1, 'C', 'LR');
        // Police
        $this->SetTextColor(0, 0, 0);


        $this->SetCol(0);
        $w = array(50, 50);

        $this->SetXY(45, 60);
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->SetXY(45, 60);
        $this->SetFont('Times', '', 12);
        $this->Cell($w[0], 6, _('nombalise'), 'LR', 0, 'L');
        $this->SetFont('Times', 'I', 10);
        $this->Cell($w[1], 6, utf8_decode("$nomBaliseRapport"), 'LR', 0, 'R');

        $this->SetXY(45, 66);
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->SetXY(45, 66);
        $this->SetFont('Times', '', 12);
        $this->Cell($w[0], 6, _('idbalise'), 'LR', 0, 'L');
        $this->SetFont('Times', 'I', 10);
        $this->Cell($w[1], 6, "$idBaliseRapport", 'LR', 0, 'R');

       //ChromePhp::log($totalDureeTrajet);
        $this->SetXY(45, 72);
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->SetXY(45, 72);
        $this->SetFont('Times', '', 12);
        $this->Cell($w[0], 6, utf8_decode(_('rapport_dureedestrajets')), 'LR', 0, 'L');
        $this->SetFont('Times', 'I', 10);
        $this->Cell($w[1], 6, "$totalDureeTrajet", 'LR', 0, 'R');

        $this->SetXY(45, 78);
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->SetXY(45, 78);
        $this->SetFont('Times', '', 12);
        $this->Cell($w[0], 6, utf8_decode(_('rapport_dureedesarrets')), 'LR', 0, 'L');
        $this->SetFont('Times', 'I', 10);
        $this->Cell($w[1], 6, "$totalDureeArret", 'LR', 0, 'R');

        $this->SetXY(45, 84);
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->SetXY(45, 84);
        $this->SetFont('Times', '', 12);
        $this->Cell($w[0], 6, _('rapport_kmsparcourus'), 'LR', 0, 'L');
        $this->SetFont('Times', 'I', 10);
        $this->Cell($w[1], 6, "$totalKm", 'LR', 0, 'R');

        $this->SetXY(45, 90);
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->SetXY(45, 90);
        $this->SetFont('Times', '', 12);
        $this->Cell($w[0], 6, _('rapport_nombrepositions'), 'LR', 0, 'L');
        $this->SetFont('Times', 'I', 10);
        $this->Cell($w[1], 6, "$position", 'LR', 0, 'R');

        $this->SetXY(45, 96);
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->SetXY(45, 96);
        $this->SetFont('Times', '', 12);
        $this->Cell($w[0], 6, utf8_decode(_('rapport_nombreetapes')), 'LR', 0, 'L');
        $this->SetFont('Times', 'I', 10);
        $this->Cell($w[1], 6, "$etape", 'LR', 0, 'R');

        $this->SetXY(45, 102);
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->SetXY(45, 102);
        $this->SetFont('Times', '', 12);
        $this->Cell($w[0], 6, utf8_decode('Volume de données (Ko)'), 'LR', 0, 'L');
        $this->SetFont('Times', 'I', 10);
        $this->Cell($w[1], 6, (47*"$position")/1000, 'LR', 0, 'R');

        $this->SetXY(45, 108);
        $this->Cell(array_sum($w), 0, '', 'T');

        if ($litrePer100Km != "??")
        {
            $this->SetFont('Arial', '', 12);
            $this->SetXY(100, 115);
            $this->SetTextColor(254, 254, 254);
            $this->SetFillColor(96, 104, 103);
            $this->Cell(100, 6, utf8_decode(_('rapport_consocarburant')), 0, 1, 'C', 'LR');
            // Police
            $this->SetTextColor(0, 0, 0);


            $this->SetXY(100, 121);
            $this->Cell(array_sum($w), 0, '', 'T');
            $this->SetXY(100, 121);
            $this->SetFont('Times', '', 12);
            $this->Cell($w[0], 6, utf8_decode(_('rapport_typedecarburants')), 'LR', 0, 'L');
            $this->SetFont('Times', 'I', 10);
            $this->Cell($w[1], 6, "$typeCarburant", 'LR', 0, 'R');

            $this->SetXY(100, 127);
            $this->Cell(array_sum($w), 0, '', 'T');
            $this->SetXY(100, 127);
            $this->SetFont('Times', '', 12);
            $this->Cell($w[0], 6, utf8_decode(_('rapport_litrekm')), 'LR', 0, 'L');
            $this->SetFont('Times', 'I', 10);
            $this->Cell($w[1], 6, $litrePerKm, 'LR', 0, 'R');

            $this->SetXY(100, 133);
            $this->Cell(array_sum($w), 0, '', 'T');
            $this->SetXY(100, 133);
            $this->SetFont('Times', '', 12);
            $this->Cell($w[0], 6, utf8_decode(_('rapport_litre100km')), 'LR', 0, 'L');
            $this->SetFont('Times', 'I', 10);
            $this->Cell($w[1], 6, $litrePer100Km, 'LR', 0, 'R');


            $this->SetXY(100, 139);
            $this->Cell(array_sum($w), 0, '', 'T');
            $this->SetXY(100, 139);
            $this->SetFont('Times', '', 12);
            $this->Cell($w[0], 6, utf8_decode(_('rapport_emissionco2') . ' (g)'), 'LR', 0, 'L');
            $this->SetFont('Times', 'I', 10);
            $this->Cell($w[1], 6, "$emisionCO2", 'LR', 0, 'R');

            $this->SetXY(100, 145);
            $this->Cell(array_sum($w), 0, '', 'T');
            $this->SetXY(100, 145);
            $this->SetFont('Times', '', 12);
            $this->Cell($w[0], 6, utf8_decode(_('rapport_litreconsommes')), 'LR', 0, 'L');
            $this->SetFont('Times', 'I', 10);
            $this->Cell($w[1], 6, "$litreCarburantConsomme", 'LR', 0, 'R');

            $this->SetXY(100, 151);



            $this->Cell(array_sum($w), 0, '', 'T');
        }
		/*
        $size = getimagesize("../../assets/img/graph/example.draw3DPie.labels.png");
        $largeur = $size[0];
        $hauteur = $size[1];
        $ratio = 120 / $hauteur; //hauteur impos�e de 120mm
        $newlargeur = $largeur * $ratio;
        $posi = (300 - $newlargeur) / 2; //300mm = largeur de page
        */
        
        $this->SetFont('Arial', 'B', 16);

        $this->Image("../../assets/img/pie/rapportpie" . $nomDuFichier . ".png", 150, 40, 0, 0, 'PNG');
    }

    /*     * **********************************************************************************************
     * ************************************************************************************************	PAGE ETAPE
     * ********************************************************************************************** */

    function pageEtape($nomBaliseRapport, array $cbalise, $km, $vitesseMoyenne, $vitesseMax, $dureeTrajet, $sql, $arrayTpositions,$db_user_2,$db_pass_2)
    {
        require_once ("../../../lib/php-gettext-1.0.12/gettext.inc");
        $locale = "fr_FR";
        if (isset($_SESSION["language"]))
        {
            $locale = $_SESSION['language'];
        }
        else
        {
            $_SESSION['language'] = "fr_FR";
            $locale = "fr_FR";
        }
        
        T_setlocale(LC_MESSAGES, $locale);
        $encoding = "UTF-8";
        $domain = "messages";
        bindtextdomain($domain, '../../../locale');
        bind_textdomain_codeset($domain, $encoding);
        textdomain($domain);
        //ChromePhp::log($vitesseMoyenne,$vitesseMax);
        $this->AddPage();

        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(254, 254, 254);
        $this->SetFillColor(96, 104, 103);
        $this->Cell(0, 6, utf8_decode(_('rapport_resumeetape') . ": " . _('balise') . " $nomBaliseRapport"), 0, 1, 'C', true);
        $this->Ln(4);

        $this->y0 = $this->GetY();

        $header = array(_('rapport_etape'), utf8_decode(_('rapport_datedebut')), utf8_decode(_('rapport_lieudebut')), _('rapport_datefin'), _('rapport_lieufin'), _('rapport_dureetrajet'), 'Km', _('vitesse') . ' Moy', _('vitesse') . ' Max', _('rapport_dureearret'));
        $nomDatabaseGpw = $_POST["nomDatabaseGpwRapport"];
        $ipDatabaseGpw = $_POST["ipDatabaseGpwRapport"];
        $nomPOI = array();
        $descriptionPOI = array();
        $latPOI = array();
        $lngPOI = array();
        $rayonPOI = array();
        $i = 0;
        $arrayPoi = $this->getPoiTracker($db_user_2,$db_pass_2);
        $ids = join(',', $arrayPoi);

        $connectEtapePOI = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
        mysqli_set_charset($connectEtapePOI, "utf8");
        $resultEtapePOI = mysqli_query($connectEtapePOI, "select * from tpoi WHERE Id IN ($ids)");
        $lengthEtapePOI = mysqli_num_rows($resultEtapePOI);
        while ($rowPOI = mysqli_fetch_array($resultEtapePOI))
        {
            $nomPOI[$i] = utf8_decode($rowPOI['Name']);
            $descriptionPOI[$i] = utf8_decode($rowPOI['description']);
            $latPOI[$i] = $rowPOI['latitude'];
            $lngPOI[$i] = $rowPOI['longitude'];
            $adressePOI[$i] = $rowPOI['adresse'];
            $rayonPOI[$i] = $rowPOI['Rayon'];
            $i++;
        }
        mysqli_free_result($resultEtapePOI);
        mysqli_close($connectEtapePOI);



        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(254, 254, 254);
        $this->SetFillColor(96, 104, 103);
        $this->SetDrawColor(96, 104, 103);
        $this->SetLineWidth(.3);
        $w = array(10, 24, 68, 24, 68, 18, 11, 18, 18, 18);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('', '', 8);


        $i = 0;
        
        $timezone = $_POST["timezone"];

        if ((substr($_SESSION['language'], -2) == "US"))
            $formatLangDateTime = "Y-m-d h:i:s A";
        else
            $formatLangDateTime = "Y-m-d H:i:s";

        $queryFetchArray = function($local_date, $lengthEtape, $lengthEtapePOI, $w, $formatLangDateTime, $row, $this2,
                $cbalise, $rapportEtape, $latPOI, $lngPOI, $rayonPOI, $descriptionPOI, $nomPOI,
                $km, $vitesseMoyenne, $vitesseMax, $dureeTrajet) {


            if ($rapportEtape->conditionOk == "")
            {
                if ((($cbalise[$rapportEtape->i - 1] == "stop") && ($cbalise[$rapportEtape->i] != "stop") && ($cbalise[$rapportEtape->i + 1] != "stop")) ||
                        (($cbalise[$rapportEtape->i - 1] != "stop") && ($cbalise[$rapportEtape->i] != "stop") && ($cbalise[$rapportEtape->i + 1] != "stop")) ||
                        (($cbalise[$rapportEtape->i - 1] == "stop") && ($cbalise[$rapportEtape->i] != "stop") && ($cbalise[$rapportEtape->i + 1] == "stop")) ||
                        (($cbalise[$rapportEtape->i - 1] != "stop") && ($cbalise[$rapportEtape->i] != "stop") && ($cbalise[$rapportEtape->i + 1] == "stop")))
                {
                    //				if ( (($cbalise[$i] != "stop") )){
                    //Calcul des temps d'arrets
                    if ($rapportEtape->boubou != 0)
                    {
                        $rapportEtape->dateFIN = strtotime($local_date->format($formatLangDateTime));

                        $rapportEtape->diffArret = $rapportEtape->dateFIN - $rapportEtape->dateDebut;
                        $rapportEtape->dateArret = new DateTime();
                        $rapportEtape->dateArret->setTimestamp($rapportEtape->diffArret);
                        $rapportEtape->dureeArret = $rapportEtape->dateArret->format('H:i:s');

                       // ChromePhp::LOG($cbalise[$rapportEtape->i - 1], $cbalise[$rapportEtape->i], $cbalise[$rapportEtape->i + 1]);
                        //ChromePhp::LOG("rapportEtape->diffArret", $rapportEtape->diffArret);
                       // ChromePhp::LOG("rapportEtape->dateDebut", date('Y-m-d H:i:s', $rapportEtape->dateDebut));
                        //ChromePhp::LOG("rapportEtape->dateFIN", date('Y-m-d H:i:s', $rapportEtape->dateFIN));
                       // ChromePhp::LOG("rapportEtape->dureeArret", $rapportEtape->dureeArret);
                        $this2->Cell($w[9], 6, $rapportEtape->dureeArret, 'LR', 0, 'C', $rapportEtape->fill);
                        $this2->Ln();

                        $this2->Cell($w[0], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                        $this2->Cell($w[1], 6, substr($rapportEtape->debutdate, 11), 'LR', 0, 'C', $rapportEtape->fill);
                        $this2->Cell($w[2], 6, substr($rapportEtape->debutlieu, $rapportEtape->indexDebutLieu), 'LR', 0, 'C', $rapportEtape->fill);

                        $this2->Cell($w[3], 6, substr($rapportEtape->findate, 11), 'LR', 0, 'C', $rapportEtape->fill);
                        $this2->Cell($w[4], 6, substr($rapportEtape->finLieu, $rapportEtape->indexFinLieu), 'LR', 0, 'C', $rapportEtape->fill);
                        //						else $this2->Cell($w[4],6,'','LR',0,'C',$fill);
                        $this2->Cell($w[5], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                        $this2->Cell($w[6], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                        $this2->Cell($w[7], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                        $this2->Cell($w[8], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                        $this2->Cell($w[9], 6, '', 'LR', 0, 'L', $rapportEtape->fill);
                        $this2->Ln();

                        //ChromePhp::LOG("rapportEtape->findate", $rapportEtape->finLieu);
                        $rapportEtape->fill = !$rapportEtape->fill;
                        $rapportEtape->boubou = 0;
                    }
                    $rapportEtape->debutdate = $local_date->format($formatLangDateTime);

                    //					$this2->Cell($w[0],6,$etape,'LR',0,'C',$fill);
                    //					$this2->Cell($w[1],6,$debutdate,'LR',0,'C',$fill);

                    $poiRetenu = "";
                    if ($lengthEtapePOI)
                    {
                        for ($z = 0; $z < $lengthEtapePOI; $z++)
                        {
                            $distancePoiEtEtape = $this2->get_distance_m2($latPOI[$z], $lngPOI[$z], $row["Pos_Latitude"], $row["Pos_Longitude"]);
                            //ChromePhp::LOG("distancePoiEtEtape", $distancePoiEtEtape);
                            if ($poiRetenu == "")
                            {
                                if ($distancePoiEtEtape < 0.1)
                                {
                                    if ($rayonPOI[$z])
                                    {
                                        if ($distancePoiEtEtape * 1609.344 < $rayonPOI[$z])
                                        {
                                            if ($descriptionPOI[$z])
                                            {
                                                $rapportEtape->adresse = $nomPOI[$z] . " - " . $descriptionPOI[$z];
                                            }
                                            else
                                            {
                                                $rapportEtape->adresse = $nomPOI[$z] . " " . $descriptionPOI[$z];
                                            }
                                            $poiRetenu = "1";
                                        }
                                        else
                                        {
                                            $rapportEtape->adresse = utf8_decode($row["Pos_Adresse"]);
                                        }
                                    }
                                    else
                                    {
                                        $rapportEtape->adresse = utf8_decode($row["Pos_Adresse"]);
                                    }
                                }
                                else
                                {
                                    $rapportEtape->adresse = utf8_decode($row["Pos_Adresse"]);
                                }
                            }
                        }
                    }
                    else
                    {
                        $rapportEtape->adresse = utf8_decode($row["Pos_Adresse"]);
                    }

                    $rapportEtape->condition = "ok";
                    $rapportEtape->conditionOk = "ok";
                }
            }
            if ($rapportEtape->condition == "ok")
            {

                //if( $cbalise[$rapportEtape->i] != "stop" && ($cbalise[$rapportEtape->i+1] == "stop") ) {
                if (($cbalise[$rapportEtape->i - 1] != "stop") && ($cbalise[$rapportEtape->i] == "stop") && ($cbalise[$rapportEtape->i + 1] == "stop") || ($cbalise[$rapportEtape->i - 1] != "stop") && ($cbalise[$rapportEtape->i] == "stop") && ($cbalise[$rapportEtape->i + 1] != "stop"))
                {
                    if ($rapportEtape->etape % 12 == 0)
                    {
                        $this2->Ln();
                        $this2->Ln();
                    }
                    $this2->Cell($w[0], 6, $rapportEtape->etape, 'LR', 0, 'C', $rapportEtape->fill);

                    $this2->Cell($w[1], 6, substr($rapportEtape->debutdate, 0, 10), 'LR', 0, 'C', $rapportEtape->fill);
                    $rapportEtape->debutlieu = $rapportEtape->adresse;
                    $rapportEtape->indexDebutLieu = strrpos($rapportEtape->adresse, ' ', -10);
                    if (strlen($rapportEtape->debutlieu) > 80)
                        $rapportEtape->indexDebutLieu = strrpos($rapportEtape->adresse, ' ', -45);
                        
                    $this2->Cell($w[2], 6, substr($rapportEtape->debutlieu, 0, $rapportEtape->indexDebutLieu), 'LR', 0, 'C', $rapportEtape->fill);
                    //else $this2->Cell($w[2],6,$debutlieu,'LR',0,'C',$fill);

                    //ChromePhp::LOG($cbalise[$rapportEtape->i - 1], $cbalise[$rapportEtape->i], $cbalise[$rapportEtape->i + 1]);

                    $rapportEtape->findate = $local_date->format($formatLangDateTime);
                    //ChromePhp::LOG("rapportEtape->findate2", $rapportEtape->adresse);
                    $this2->Cell($w[3], 6, substr($rapportEtape->findate, 0, 10), 'LR', 0, 'C', $rapportEtape->fill);

                    $poiRetenu = "";
                    if ($lengthEtapePOI)
                    {
                        for ($z = 0; $z < $lengthEtapePOI; $z++)
                        {
                            $distancePoiEtEtape = $this2->get_distance_m2($latPOI[$z], $lngPOI[$z], $row["Pos_Latitude"], $row["Pos_Longitude"]);
                            if ($poiRetenu == "")
                            {
                                if ($distancePoiEtEtape < 0.1)
                                {
                                    if ($distancePoiEtEtape * 1609.344 < $rayonPOI[$z])
                                    {
                                        if ($descriptionPOI[$z])
                                        {
                                            $rapportEtape->adresse = $nomPOI[$z] . " - " . $descriptionPOI[$z];
                                        }
                                        else
                                        {
                                            $rapportEtape->adresse = $nomPOI[$z] . " " . $descriptionPOI[$z];
                                        }
                                        $poiRetenu = "1";
                                    }
                                    else
                                    {
                                        $rapportEtape->adresse = utf8_decode($row["Pos_Adresse"]);
                                    }
                                }
                                else
                                {
                                    $rapportEtape->adresse = utf8_decode($row["Pos_Adresse"]);
                                }
                            }
                        }
                    }
                    else
                    {
                        $rapportEtape->adresse = utf8_decode($row["Pos_Adresse"]);
                    }

                    $rapportEtape->finLieu = $rapportEtape->adresse;
                    $rapportEtape->indexFinLieu = strrpos($rapportEtape->adresse, ' ', -10);
                    if (strlen($rapportEtape->finLieu) > 80)
                        $rapportEtape->indexFinLieu = strrpos($rapportEtape->adresse, ' ', -45);
                    $this2->Cell($w[4], 6, substr($rapportEtape->finLieu, 0, $rapportEtape->indexFinLieu), 'LR', 0, 'C', $rapportEtape->fill);
                    //						else $this2->Cell($w[4],6,$finLieu,'LR',0,'C',$fill);

                    $this2->Cell($w[5], 6, $dureeTrajet[$rapportEtape->y], 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[6], 6, $km[$rapportEtape->y], 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[7], 6, $vitesseMoyenne[$rapportEtape->y], 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[8], 6, $vitesseMax[$rapportEtape->y], 'LR', 0, 'C', $rapportEtape->fill);
                }
                if ($cbalise[$rapportEtape->i - 1] != "stop" && ($cbalise[$rapportEtape->i] == "stop"))
                {
                    $rapportEtape->dateDebut = strtotime($local_date->format($formatLangDateTime));

                    $rapportEtape->boubou = 1;
                    $rapportEtape->condition = "";
                    $rapportEtape->conditionOk = "";
                    $rapportEtape->etape++;
                    $rapportEtape->y++;
                }
            }
            if ($rapportEtape->i == $lengthEtape - 1 && $dureeTrajet != "")
            {
                if ($cbalise[$rapportEtape->i] == "stop")
                {


                    $rapportEtape->diffArret = strtotime($_POST['finRapport']) - $rapportEtape->dateDebut;
                    $rapportEtape->dateArret = new DateTime();
                    $rapportEtape->dateArret->setTimestamp($rapportEtape->diffArret);
                    $rapportEtape->dureeArret = $rapportEtape->dateArret->format('H:i:s');

                    $this2->Cell($w[9], 6, $rapportEtape->dureeArret, 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Ln();

                    $this2->Cell($w[0], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[1], 6, substr($rapportEtape->debutdate, 11), 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[2], 6, substr($rapportEtape->debutlieu, $rapportEtape->indexDebutLieu), 'LR', 0, 'C', $rapportEtape->fill);

                    $this2->Cell($w[3], 6, substr($rapportEtape->findate, 11), 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[4], 6, substr($rapportEtape->finLieu, $rapportEtape->indexFinLieu), 'LR', 0, 'C', $rapportEtape->fill);
                    //						else $this2->Cell($w[4],6,'','LR',0,'C',$fill);
                    $this2->Cell($w[5], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[6], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[7], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[8], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[9], 6, '', 'LR', 0, 'L', $rapportEtape->fill);
                    $this2->Ln();
                }
                else
                {

                    if ($rapportEtape->etape % 12 == 0)
                    {
                        $this2->Ln();
                        $this2->Ln();
                    }
                    $this2->Cell($w[0], 6, $rapportEtape->etape, 'LR', 0, 'C', $rapportEtape->fill);

                    $this2->Cell($w[1], 6, substr($rapportEtape->debutdate, 0, 10), 'LR', 0, 'C', $rapportEtape->fill);
                    $rapportEtape->debutlieu = $rapportEtape->adresse;
                    
                    $rapportEtape->indexDebutLieu = strrpos($rapportEtape->adresse, ' ', -10);
                    if (strlen($rapportEtape->debutlieu) > 80)
                        $rapportEtape->indexDebutLieu = strrpos($rapportEtape->adresse, ' ', -45);

                    $this2->Cell($w[2], 6, substr($rapportEtape->debutlieu, 0, $rapportEtape->indexDebutLieu), 'LR', 0, 'C', $rapportEtape->fill);
                    //else $this2->Cell($w[2],6,$debutlieu,'LR',0,'C',$fill);



                    $rapportEtape->findate = $local_date->format($formatLangDateTime);

                    //ChromePhp::log(date("Y-m-d H:i:s",$rapport->arrivalDate), date("Y-m-d H:i:s",$rapport->departureDate),$finRapport);
                    //$this2->Cell($w[3], 6, $rapport->dateFIN, 'LR', 0, 'C', $rapportEtape->fill);    
                    $this2->Cell($w[3], 6, "Incomplet", 'LR', 0, 'C', $rapportEtape->fill);
                    $poiRetenu = "";
                    if ($lengthEtapePOI)
                    {
                        for ($z = 0; $z < $lengthEtapePOI; $z++)
                        {
                            $distancePoiEtEtape = $this2->get_distance_m2($latPOI[$z], $lngPOI[$z], $row["Pos_Latitude"], $row["Pos_Longitude"]);
                            if ($poiRetenu == "")
                            {
                                if ($distancePoiEtEtape < 0.1)
                                {
                                    if ($distancePoiEtEtape * 1609.344 < $rayonPOI[$z])
                                    {
                                        if ($descriptionPOI[$z])
                                        {
                                            $rapportEtape->adresse = $nomPOI[$z] . " - " . $descriptionPOI[$z];
                                        }
                                        else
                                        {
                                            $rapportEtape->adresse = $nomPOI[$z] . " " . $descriptionPOI[$z];
                                        }
                                        $poiRetenu = "1";
                                    }
                                    else
                                    {
                                        $rapportEtape->adresse = utf8_decode($row["Pos_Adresse"]);
                                    }
                                }
                                else
                                {
                                    $rapportEtape->adresse = utf8_decode($row["Pos_Adresse"]);
                                }
                            }
                        }
                    }
                    else
                    {
                        $rapportEtape->adresse = utf8_decode($row["Pos_Adresse"]);
                    }


                    $rapportEtape->finLieu = $rapportEtape->adresse;
                    $rapportEtape->indexFinLieu = strrpos($rapportEtape->adresse, ' ', -20);
                    if (strlen($rapportEtape->finLieu) > 80)
                        $indexFinLieu = strrpos($rapportEtape->adresse, ' ', -45);
                    $this2->Cell($w[4], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    //						else $this2->Cell($w[4],6,$finLieu,'LR',0,'C',$fill);

                    $this2->Cell($w[5], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[6], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[7], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[8], 6, "", 'LR', 0, 'C', $rapportEtape->fill);

                    $this2->Cell($w[9], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Ln();

                    $this2->Cell($w[0], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[1], 6, substr($rapportEtape->debutdate, 11), 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[2], 6, substr($rapportEtape->debutlieu, $rapportEtape->indexDebutLieu), 'LR', 0, 'C', $rapportEtape->fill);

                    $this2->Cell($w[3], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[4], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    //						else $this2->Cell($w[4],6,'','LR',0,'C',$fill);
                    $this2->Cell($w[5], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[6], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[7], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[8], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
                    $this2->Cell($w[9], 6, '', 'LR', 0, 'L', $rapportEtape->fill);
                    $this2->Ln();
                }
            }
            $rapportEtape->i++;

            return $rapportEtape;
        };

        $Hd1 = "";
        $Hf1 = "";
        $Hd2 = "";
        $Hf2 = "";
        $Lundi = "";
        $Mardi = "";
        $Mercredi = "";
        $Jeudi = "";
        $Vendredi = "";
        $Samedi = "";
        $Dimanche = "";

        // $connection2 = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
        // $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $_POST["idBaliseRapport"] . "' )";
        // $result2 = mysqli_query($connection2, $sql2);

        $connectEtape = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
        mysqli_set_charset($connectEtape, "utf8");
        $lengthEtape = 0;
        if (sizeof($arrayTpositions) > 1)
        {
            if (mysqli_multi_query($connectEtape, $sql))
            {
                do
                {
                    if ($resultEtape = mysqli_store_result($connectEtape))
                    {
                        $lengthEtape += mysqli_num_rows($resultEtape);
                    }
                } while (mysqli_more_results($connectEtape) && mysqli_next_result($connectEtape));
            }
            if (mysqli_multi_query($connectEtape, $sql))
            {

                do
                {
                    if ($resultEtape = mysqli_store_result($connectEtape))
                    {

                        $rapportEtape = new RapportEtape();
//                         if (mysqli_num_rows($result2) > 0)
//                         {

//                             while ($row2 = mysqli_fetch_array($result2))
//                             {
//                                 $NbrPlage = $row2['NbrPlage'];
//                                 $Hd1 = $row2['Hd1'];
//                                 $Hf1 = $row2['Hf1'];
//                                 $Hd2 = $row2['Hd2'];
//                                 $Hf2 = $row2['Hf2'];
//                                 $Lundi = $row2['Lundi'];
//                                 $Mardi = $row2['Mardi'];
//                                 $Mercredi = $row2['Mercredi'];
//                                 $Jeudi = $row2['Jeudi'];
//                                 $Vendredi = $row2['Vendredi'];
//                                 $Samedi = $row2['Samedi'];
//                                 $Dimanche = $row2['Dimanche'];
//                             }
//                             while ($row = mysqli_fetch_array($resultEtape))
//                             {
//                                 $utc_date = DateTime::createFromFormat(
//                                                 'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
//                                 );
//                                 $local_date = $utc_date;
//                                 $local_date->setTimeZone(new DateTimeZone($timezone));

//                                 $dateNewDateTime = new DateTime();
//                                 if (( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0] . $Hd1[1]), intval($Hd1[2] . $Hd1[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0] . $Hf1[1]), intval($Hf1[2] . $Hf1[3]))->format("H:i:s")) ) ) || ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0] . $Hd2[1]), intval($Hd2[2] . $Hd2[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0] . $Hf2[1]), intval($Hf2[2] . $Hf2[3]))->format("H:i:s")) ) ))
//                                 {

//                                     if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
//                                             ($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
//                                             ($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
//                                             ($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
//                                             ($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
//                                             ($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
//                                             ($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday"))
//                                     {


//                                         $rapportEtape = $queryFetchArray($local_date, $lengthEtape, $lengthEtapePOI, $w, $formatLangDateTime, $row, $this, $cbalise, $rapportEtape, $latPOI, $lngPOI, $rayonPOI, $descriptionPOI, $nomPOI, $km, $vitesseMoyenne, $vitesseMax, $dureeTrajet);
//                                     }
//                                 }
//                             }
//                             if ($rapportEtape->i != $lengthEtape)
//                             {
//                                 if ($cbalise[$rapportEtape->i - 1] == "stop")
//                                 {

//                                     $rapportEtape->diffArret = strtotime($_POST['finRapport']) - $rapportEtape->dateDebut;
//                                     $rapportEtape->dateArret = new DateTime();
//                                     $rapportEtape->dateArret->setTimestamp($rapportEtape->diffArret);
//                                     $rapportEtape->dureeArret = $rapportEtape->dateArret->format('H:i:s');

//                                     $this->Cell($w[9], 6, $rapportEtape->dureeArret, 'LR', 0, 'C', $rapportEtape->fill);
//                                     $this->Ln();

//                                     $this->Cell($w[0], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
//                                     $this->Cell($w[1], 6, substr($rapportEtape->debutdate, 11), 'LR', 0, 'C', $rapportEtape->fill);
//                                     $this->Cell($w[2], 6, substr($rapportEtape->debutlieu, $rapportEtape->indexDebutLieu), 'LR', 0, 'C', $rapportEtape->fill);

//                                     $this->Cell($w[3], 6, substr($rapportEtape->findate, 11), 'LR', 0, 'C', $rapportEtape->fill);
//                                     $this->Cell($w[4], 6, substr($rapportEtape->finLieu, $rapportEtape->indexFinLieu), 'LR', 0, 'C', $rapportEtape->fill);
//                                     //						else $this->Cell($w[4],6,'','LR',0,'C',$fill);
//                                     $this->Cell($w[5], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
//                                     $this->Cell($w[6], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
//                                     $this->Cell($w[7], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
//                                     $this->Cell($w[8], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
//                                     $this->Cell($w[9], 6, '', 'LR', 0, 'L', $rapportEtape->fill);
//                                     $this->Ln();
//                                 }
//                                 else
//                                 {

// //										if ($rapportEtape->etape % 12 == 0) {
// //											$this->Ln();
// //											$this->Ln();
// //										}
// //										$this->Cell($w[0], 6, $rapportEtape->etape, 'LR', 0, 'C', $rapportEtape->fill);
// //
// //										$this->Cell($w[1], 6, substr($rapportEtape->debutdate, 0, 10), 'LR', 0, 'C', $rapportEtape->fill);
// //										$rapportEtape->debutlieu = $rapportEtape->adresse;
// //										$rapportEtape->indexDebutLieu = strrpos($rapportEtape->adresse, ' ', -10);
// //										if (strlen($rapportEtape->debutlieu) > 80) $rapportEtape->indexDebutLieu = strrpos($rapportEtape->adresse, ' ', -45);
// //
// //										$this->Cell($w[2], 6, substr($rapportEtape->debutlieu, 0, $rapportEtape->indexDebutLieu), 'LR', 0, 'C', $rapportEtape->fill);
// //										$rapportEtape->findate = $local_date->format($formatLangDateTime);
// //										$this->Cell($w[3], 6, "Incomplet", 'LR', 0, 'C', $rapportEtape->fill);
// //
// //
// //										$rapportEtape->finLieu = $rapportEtape->adresse;
// //										$rapportEtape->indexFinLieu = strrpos($rapportEtape->adresse, ' ', -20);
// //										if (strlen($rapportEtape->finLieu) > 80) $indexFinLieu = strrpos($rapportEtape->adresse, ' ', -45);
// //										$this->Cell($w[4], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //										//						else $this->Cell($w[4],6,$finLieu,'LR',0,'C',$fill);
// //
// //										$this->Cell($w[5], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //										$this->Cell($w[6], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //										$this->Cell($w[7], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //										$this->Cell($w[8], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //
// //										$this->Cell($w[9], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //										$this->Ln();
// //
// //										$this->Cell($w[0], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //										$this->Cell($w[1], 6, substr($rapportEtape->debutdate, 11), 'LR', 0, 'C', $rapportEtape->fill);
// //										$this->Cell($w[2], 6, substr($rapportEtape->debutlieu, $rapportEtape->indexDebutLieu), 'LR', 0, 'C', $rapportEtape->fill);
// //
// //										$this->Cell($w[3], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //										$this->Cell($w[4], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //										//						else $this->Cell($w[4],6,'','LR',0,'C',$fill);
// //										$this->Cell($w[5], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //										$this->Cell($w[6], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //										$this->Cell($w[7], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //										$this->Cell($w[8], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //										$this->Cell($w[9], 6, '', 'LR', 0, 'L', $rapportEtape->fill);
// //										$this->Ln();
//                                 }
//                             }
//                         }
//                         else
//                         {
                            while ($row = mysqli_fetch_array($resultEtape))
                            {
                                $utc_date = DateTime::createFromFormat(
                                                'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                                );
                                $local_date = $utc_date;
                                $local_date->setTimeZone(new DateTimeZone($timezone));

                                $queryFetchArray($local_date, $lengthEtape, $lengthEtapePOI, $w, $formatLangDateTime, $row, $this, $cbalise, $rapportEtape, $latPOI, $lngPOI, $rayonPOI, $descriptionPOI, $nomPOI, $km, $vitesseMoyenne, $vitesseMax, $dureeTrajet);
                            }
                        // }
                        if (!mysqli_more_results($connectEtape))
                            $this->Cell(array_sum($w), 0, '', 'T');
                        mysqli_free_result($resultEtape);
                    }
                } while (mysqli_more_results($connectEtape) && mysqli_next_result($connectEtape));
            }
        }else
        {

            $resultEtape = mysqli_query($connectEtape, $sql);
            $lengthEtape = mysqli_num_rows($resultEtape);
            if ($resultEtape !== false)
            {
                $rapportEtape = new RapportEtape();

//                 if (mysqli_num_rows($result2) > 0)
//                 {

//                     while ($row2 = mysqli_fetch_array($result2))
//                     {
//                         $NbrPlage = $row2['NbrPlage'];
//                         $Hd1 = $row2['Hd1'];
//                         $Hf1 = $row2['Hf1'];
//                         $Hd2 = $row2['Hd2'];
//                         $Hf2 = $row2['Hf2'];
//                         $Lundi = $row2['Lundi'];
//                         $Mardi = $row2['Mardi'];
//                         $Mercredi = $row2['Mercredi'];
//                         $Jeudi = $row2['Jeudi'];
//                         $Vendredi = $row2['Vendredi'];
//                         $Samedi = $row2['Samedi'];
//                         $Dimanche = $row2['Dimanche'];
//                     }

//                     while ($row = mysqli_fetch_array($resultEtape))
//                     {
//                         $utc_date = DateTime::createFromFormat(
//                                         'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
//                         );
//                         $local_date = $utc_date;
//                         $local_date->setTimeZone(new DateTimeZone($timezone));

//                         $dateNewDateTime = new DateTime();
//                         if (( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0] . $Hd1[1]), intval($Hd1[2] . $Hd1[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0] . $Hf1[1]), intval($Hf1[2] . $Hf1[3]))->format("H:i:s")) ) ) || ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0] . $Hd2[1]), intval($Hd2[2] . $Hd2[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0] . $Hf2[1]), intval($Hf2[2] . $Hf2[3]))->format("H:i:s")) ) ))
//                         {

//                             if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
//                                     ($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
//                                     ($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
//                                     ($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
//                                     ($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
//                                     ($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
//                                     ($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday"))
//                             {

//                                 $rapportEtape = $queryFetchArray($local_date, $lengthEtape, $lengthEtapePOI, $w, $formatLangDateTime, $row, $this, $cbalise, $rapportEtape, $latPOI, $lngPOI, $rayonPOI, $descriptionPOI, $nomPOI, $km, $vitesseMoyenne, $vitesseMax, $dureeTrajet);
//                             }
//                         }
//                     }

//                     if ($rapportEtape->i != $lengthEtape)
//                     {
//                         if ($cbalise[$rapportEtape->i - 1] == "stop")
//                         {


//                             $rapportEtape->diffArret = strtotime($_POST['finRapport']) - $rapportEtape->dateDebut;
//                             $rapportEtape->dateArret = new DateTime();
//                             $rapportEtape->dateArret->setTimestamp($rapportEtape->diffArret);
//                             $rapportEtape->dureeArret = $rapportEtape->dateArret->format('H:i:s');

//                             $this->Cell($w[9], 6, $rapportEtape->dureeArret, 'LR', 0, 'C', $rapportEtape->fill);
//                             $this->Ln();

//                             $this->Cell($w[0], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
//                             $this->Cell($w[1], 6, substr($rapportEtape->debutdate, 11), 'LR', 0, 'C', $rapportEtape->fill);
//                             $this->Cell($w[2], 6, substr($rapportEtape->debutlieu, $rapportEtape->indexDebutLieu), 'LR', 0, 'C', $rapportEtape->fill);

//                             $this->Cell($w[3], 6, substr($rapportEtape->findate, 11), 'LR', 0, 'C', $rapportEtape->fill);
//                             $this->Cell($w[4], 6, substr($rapportEtape->finLieu, $rapportEtape->indexFinLieu), 'LR', 0, 'C', $rapportEtape->fill);
//                             //						else $this->Cell($w[4],6,'','LR',0,'C',$fill);
//                             $this->Cell($w[5], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
//                             $this->Cell($w[6], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
//                             $this->Cell($w[7], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
//                             $this->Cell($w[8], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
//                             $this->Cell($w[9], 6, '', 'LR', 0, 'L', $rapportEtape->fill);
//                             $this->Ln();
//                         }
//                         else
//                         {

// //								if ($rapportEtape->etape % 12 == 0) {
// //									$this->Ln();
// //									$this->Ln();
// //								}
// //								$this->Cell($w[0], 6, $rapportEtape->etape, 'LR', 0, 'C', $rapportEtape->fill);
// //
// //								$this->Cell($w[1], 6, substr($rapportEtape->debutdate, 0, 10), 'LR', 0, 'C', $rapportEtape->fill);
// //								$rapportEtape->debutlieu = $rapportEtape->adresse;
// //								$rapportEtape->indexDebutLieu = strrpos($rapportEtape->adresse, ' ', -10);
// //								if (strlen($rapportEtape->debutlieu) > 80) $rapportEtape->indexDebutLieu = strrpos($rapportEtape->adresse, ' ', -45);
// //
// //								$this->Cell($w[2], 6, substr($rapportEtape->debutlieu, 0, $rapportEtape->indexDebutLieu), 'LR', 0, 'C', $rapportEtape->fill);
// //								$rapportEtape->findate = $local_date->format($formatLangDateTime);
// //								$this->Cell($w[3], 6, "Incomplet", 'LR', 0, 'C', $rapportEtape->fill);
// //
// //
// //								$rapportEtape->finLieu = $rapportEtape->adresse;
// //								$rapportEtape->indexFinLieu = strrpos($rapportEtape->adresse, ' ', -20);
// //								if (strlen($rapportEtape->finLieu) > 80) $indexFinLieu = strrpos($rapportEtape->adresse, ' ', -45);
// //								$this->Cell($w[4], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //								//						else $this->Cell($w[4],6,$finLieu,'LR',0,'C',$fill);
// //
// //								$this->Cell($w[5], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //								$this->Cell($w[6], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //								$this->Cell($w[7], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //								$this->Cell($w[8], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //
// //								$this->Cell($w[9], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //								$this->Ln();
// //
// //								$this->Cell($w[0], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //								$this->Cell($w[1], 6, substr($rapportEtape->debutdate, 11), 'LR', 0, 'C', $rapportEtape->fill);
// //								$this->Cell($w[2], 6, substr($rapportEtape->debutlieu, $rapportEtape->indexDebutLieu), 'LR', 0, 'C', $rapportEtape->fill);
// //
// //								$this->Cell($w[3], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //								$this->Cell($w[4], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //								//						else $this->Cell($w[4],6,'','LR',0,'C',$fill);
// //								$this->Cell($w[5], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //								$this->Cell($w[6], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //								$this->Cell($w[7], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //								$this->Cell($w[8], 6, "", 'LR', 0, 'C', $rapportEtape->fill);
// //								$this->Cell($w[9], 6, '', 'LR', 0, 'L', $rapportEtape->fill);
// //								$this->Ln();
//                         }
//                     }
//                 }
//                 else
//                 {
                    while ($row = mysqli_fetch_array($resultEtape))
                    {
                        $utc_date = DateTime::createFromFormat(
                                        'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                        );
                        $local_date = $utc_date;
                        $local_date->setTimeZone(new DateTimeZone($timezone));
                        $queryFetchArray($local_date, $lengthEtape, $lengthEtapePOI, $w, $formatLangDateTime, $row, $this, $cbalise, $rapportEtape, $latPOI, $lngPOI, $rayonPOI, $descriptionPOI, $nomPOI, $km, $vitesseMoyenne, $vitesseMax, $dureeTrajet);
                    }
                // }
                $this->Cell(array_sum($w), 0, '', 'T');
            }
            mysqli_free_result($resultEtape);
        }
        mysqli_close($connectEtape);
    }

    /*     * **********************************************************************************************
     * ************************************************************************************************	PAGE STOP
     * ********************************************************************************************** */

    function pageStop($finRapport, $nomBaliseRapport, array $cbalise, $sql, $arrayTpositions,$db_user_2,$db_pass_2)
    {
        require_once ("../../../lib/php-gettext-1.0.12/gettext.inc");
        $locale = "fr_FR";
        if (isset($_SESSION["language"]))
        {
            $locale = $_SESSION['language'];
        }
        else
        {
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

        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(254, 254, 254);
        $this->SetFillColor(96, 104, 103);
        $this->Cell(0, 6, utf8_decode(_('rapport_resumearret') . ": " . _('balise') . " $nomBaliseRapport"), 0, 1, 'C', true);
        $this->Ln(4);

        $this->y0 = $this->GetY();

        $header = array(_('rapport_etape'), utf8_decode(_('rapport_datedebut')) . ' Stop', utf8_decode(_('rapport_dureearret')), _('rapport_lieustop'), _('rapport_commentaire'));
        $nomDatabaseGpw = $_POST["nomDatabaseGpwRapport"];
        $ipDatabaseGpw = $_POST["ipDatabaseGpwRapport"];

        $nomPOI = array();
        $descriptionPOI = array();
        $latPOI = array();
        $lngPOI = array();
        $rayonPOI = array();
        $i = 0;

        $arrayPoi = $this->getPoiTracker($db_user_2,$db_pass_2);
        $ids = join(',', $arrayPoi);


        $connectEtapePOI = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
        mysqli_set_charset($connectEtapePOI, "utf8");
        $resultEtapePOI = mysqli_query($connectEtapePOI, "select * from tpoi WHERE Id IN ($ids)");
        $lengthEtapePOI = mysqli_num_rows($resultEtapePOI);
        while ($rowPOI = mysqli_fetch_array($resultEtapePOI))
        {
            $nomPOI[$i] = utf8_decode($rowPOI['Name']);
            $descriptionPOI[$i] = utf8_decode($rowPOI['description']);
            $latPOI[$i] = $rowPOI['latitude'];
            $lngPOI[$i] = $rowPOI['longitude'];
            $adressePOI[$i] = $rowPOI['adresse'];
            $rayonPOI[$i] = $rowPOI['Rayon'];
            $i++;
        }

        mysqli_free_result($resultEtapePOI);
        mysqli_close($connectEtapePOI);


        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(254, 254, 254);
        $this->SetFillColor(96, 104, 103);
        $this->SetDrawColor(96, 104, 103);
        $this->SetLineWidth(.3);
        $w = array(10, 24, 18, 68, 157);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        //		$this->SetFillColor(251,200,202);
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('', '', 8);

        $timezone = $_POST["timezone"];


        if ((substr($_SESSION['language'], -2) == "US"))
            $formatLangDateTime = "Y-m-d h:i:s A";
        else
            $formatLangDateTime = "Y-m-d H:i:s";

        $queryFetchArray = function($local_date, $lengthStop, $lengthEtapePOI, $w, $formatLangDateTime, $row, $this2,
                $cbalise, $rapportStop, $latPOI, $lngPOI, $rayonPOI, $descriptionPOI, $nomPOI) {

            $finRapport = $_POST['finRapport'];
            //ChromePhp::LOG("rapportStop->i", $rapportStop->i);
            //On cherche le premier stop du trajet
            if ($rapportStop->iDureeStop == 0)
            {
                if ($rapportStop->conditionOkFirstStop == "")
                {
                    if ($cbalise[$rapportStop->i] == "stop")
                    {
                        $rapportStop->dateDebutStop = strtotime($local_date->format($formatLangDateTime));
                        if ($rapportStop->i == 0)
                            $rapportStop->dateDebutStop = strtotime($_POST['debutRapport']);
                        $rapportStop->thatEtape = $rapportStop->etape;
                        $rapportStop->thatDateStop = $local_date->format($formatLangDateTime);

                        if ($rapportStop->i == 0)
                            $rapportStop->thatDateStop = date($formatLangDateTime, strtotime($_POST['debutRapport']));
                        $poiRetenu = "";
                        if ($lengthEtapePOI)
                        {
                            for ($z = 0; $z < $lengthEtapePOI; $z++)
                            {
                                $distancePoiEtEtape = $this2->get_distance_m2($latPOI[$z], $lngPOI[$z], $row["Pos_Latitude"], $row["Pos_Longitude"]);
                                if ($poiRetenu == "")
                                {
                                    if ($distancePoiEtEtape < 0.1)
                                    {
                                        if ($distancePoiEtEtape * 1609.344 < $rayonPOI[$z])
                                        {
                                            if ($descriptionPOI[$z])
                                            {
                                                $rapportStop->thatAdresse = $nomPOI[$z] . " - " . $descriptionPOI[$z];
                                            }
                                            else
                                            {
                                                $rapportStop->thatAdresse = $nomPOI[$z] . " " . $descriptionPOI[$z];
                                            }
                                            $poiRetenu = "1";
                                        }
                                        else
                                        {
                                            $rapportStop->thatAdresse = utf8_decode($row["Pos_Adresse"]);
                                        }
                                    }
                                    else
                                    {
                                        $rapportStop->thatAdresse = utf8_decode($row["Pos_Adresse"]);
                                    }
                                }
                            }
                        }
                        else
                        {
                            $rapportStop->thatAdresse = utf8_decode($row["Pos_Adresse"]);
                        }
                        $rapportStop->conditionFirstStop = "ok";
                        $rapportStop->conditionOkFirstStop = "ok";
                    }
                }
                //rechercher fin du premier stop
                if ($rapportStop->conditionFirstStop == "ok")
                {
                    //if ((($cbalise[$rapportStop->i - 1] == "stop") && ($cbalise[$rapportStop->i] != "stop") && ($cbalise[$rapportStop->i + 1] != "stop")) 
                    //        || (($cbalise[$rapportStop->i - 1] != "stop") && ($cbalise[$rapportStop->i] != "stop") && ($cbalise[$rapportStop->i + 1] != "stop")) 
                    //       || (($cbalise[$rapportStop->i - 1] == "stop") && ($cbalise[$rapportStop->i] != "stop") && ($cbalise[$rapportStop->i + 1] == "stop")) 
                    //         || (($cbalise[$rapportStop->i - 1] != "stop") && ($cbalise[$rapportStop->i] != "stop") && ($cbalise[$rapportStop->i + 1] == "stop")))
                    //{
                    if (($cbalise[$rapportStop->i - 1] == "stop") && ($cbalise[$rapportStop->i] != "stop"))
                    {
                        $rapportStop->dateFinStop = strtotime($local_date->format($formatLangDateTime));

                        $rapportStop->diffStop = $rapportStop->dateFinStop - $rapportStop->dateDebutStop;
                        $rapportStop->resultatDiffStop = new DateTime();
                        $rapportStop->resultatDiffStop->setTimestamp($rapportStop->diffStop);
                        $stringJour = _('jour');
                        if ($rapportStop->diffStop / (60 * 60) >= 24)
                        {
                            $rapportStop->dureeStop = $rapportStop->resultatDiffStop->format('d')-1 . strtolower($stringJour[0]) . $rapportStop->resultatDiffStop->format(' H:i:s');
                        }
                        else
                        {
                            $rapportStop->dureeStop = $rapportStop->resultatDiffStop->format('H:i:s');
                        }
//							$rapportStop->dureeStop = $rapportStop->resultatDiffStop->format('d H:i:s');

                        $this2->Cell($w[0], 6, $rapportStop->thatEtape, 'LR', 0, 'C', $rapportStop->fill);
                        $this2->Cell($w[1], 6, substr($rapportStop->thatDateStop, 0, 10), 'LR', 0, 'C', $rapportStop->fill);
                        $this2->Cell($w[2], 6, $rapportStop->dureeStop, 'LR', 0, 'C', $rapportStop->fill);
                        //						$this2->Cell($w[3],6,$thatAdresse ,'LR',0,'C',$fill);
                        //						$indexThatAdresse = strrpos($thatAdresse,' ') ;
                        $rapportStop->indexThatAdresse = strrpos($rapportStop->thatAdresse, ' ', -10);
                        if (strlen($rapportStop->thatAdresse) > 80)
                            $rapportStop->indexThatAdresse = strrpos($rapportStop->thatAdresse, ' ', -45);
                        $this2->Cell($w[3], 6, substr($rapportStop->thatAdresse, 0, $rapportStop->indexThatAdresse), 'LR', 0, 'C', $rapportStop->fill);
                        //						else $this2->Cell($w[3],6,$thatAdresse,'LR',0,'C',$fill);
                        $this2->Cell($w[4], 6, "", 'LR', 0, 'C', $rapportStop->fill);

                        $this2->Ln();
                        $this2->Cell($w[0], 6, "", 'LR', 0, 'C', $rapportStop->fill);
                        $this2->Cell($w[1], 6, substr($rapportStop->thatDateStop, 11), 'LR', 0, 'C', $rapportStop->fill);
                        $this2->Cell($w[2], 6, "", 'LR', 0, 'C', $rapportStop->fill);
                        $this2->Cell($w[3], 6, substr($rapportStop->thatAdresse, $rapportStop->indexThatAdresse), 'LR', 0, 'C', $rapportStop->fill);
                        //						if(strlen($thatAdresse) > 45) $this2->Cell($w[3],6,substr($thatAdresse, $indexThatAdresse),'LR',0,'C',$fill);
                        //						else $this2->Cell($w[3],6,$thatAdresse,'LR',0,'C',$fill);
                        $this2->Cell($w[4], 6, "", 'LR', 0, 'C', $rapportStop->fill);
                        $this2->Ln();
                        $rapportStop->fill = !$rapportStop->fill;

                        $rapportStop->iDureeStop++;
                        //							$i++;
                        $rapportStop->etape++;
                        //ChromePhp::LOG($cbalise[$rapportStop->i - 1], $cbalise[$rapportStop->i], $cbalise[$rapportStop->i + 1]);
                        //ChromePhp::LOG("rapportStop->dateFinStop", date('Y-m-d H:i:s', $rapportStop->dateFinStop));
                        //  ChromePhp::LOG("rapportEtape->dateDebut",date('Y-m-d H:i:s',$rapportEtape->dateDebut)); 
                        // ChromePhp::LOG("rapportEtape->dateFIN",date('Y-m-d H:i:s',$rapportEtape->dateFIN)); 
                        // ChromePhp::LOG("rapportEtape->dureeArret",$rapportEtape->dureeArret); 
//			
                    }
                }
            }
            //On cherche les prochain stop du trajet
            if ($rapportStop->iDureeStop != 0)
            {
                if ($rapportStop->conditionOk == "")
                {
                    //						if (($cbalise[$i - 1] != "stop") && ($cbalise[$i] == "stop") && ($cbalise[$i + 1] == "stop") || ($cbalise[$i - 1] != "stop") && ($cbalise[$i] == "stop") && ($cbalise[$i + 1] != "stop")) {
                    if ($cbalise[$rapportStop->i] == "stop")
                    {
                        $rapportStop->dateDebutStop = strtotime($local_date->format($formatLangDateTime));
                        $rapportStop->thatEtape = $rapportStop->etape;
                        $rapportStop->thatDateStop = $local_date->format($formatLangDateTime);
                        $poiRetenu = "";
                        if ($lengthEtapePOI)
                        {
                            for ($z = 0; $z < $lengthEtapePOI; $z++)
                            {
                                $distancePoiEtEtape = $this2->get_distance_m2($latPOI[$z], $lngPOI[$z], $row["Pos_Latitude"], $row["Pos_Longitude"]);
                                if ($poiRetenu == "")
                                {
                                    if ($distancePoiEtEtape < 0.1)
                                    {
                                        if ($distancePoiEtEtape * 1609.344 < $rayonPOI[$z])
                                        {
                                            if ($descriptionPOI[$z])
                                            {
                                                $rapportStop->thatAdresse = $nomPOI[$z] . " - " . $descriptionPOI[$z];
                                            }
                                            else
                                            {
                                                $rapportStop->thatAdresse = $nomPOI[$z] . " " . $descriptionPOI[$z];
                                            }
                                            $poiRetenu = "1";
                                        }
                                        else
                                        {
                                            $rapportStop->thatAdresse = utf8_decode($row["Pos_Adresse"]);
                                        }
                                    }
                                    else
                                    {
                                        $rapportStop->thatAdresse = utf8_decode($row["Pos_Adresse"]);
                                    }
                                }
                            }
                        }
                        else
                        {
                            $rapportStop->thatAdresse = utf8_decode($row["Pos_Adresse"]);
                        }
                        $rapportStop->condition = "ok";
                        $rapportStop->conditionOk = "ok";
                    }
                }

                if ($rapportStop->condition == "ok")
                {
                    if ($rapportStop->i == $lengthStop - 1)
                    {
                        if ($cbalise[$rapportStop->i] == "stop")
                        {

                            $utc_date = DateTime::createFromFormat(
                                            'Y-m-d H:i:s', $finRapport
                                            //						new DateTimeZone('UTC')
                            );
                            $local_date = $utc_date;
//								$local_date->setTimeZone(new DateTimeZone($rapportStop->timezone));
                            $rapportStop->dateFinStop = strtotime($local_date->format($formatLangDateTime));
                            $rapportStop->diffStop = $rapportStop->dateFinStop - $rapportStop->dateDebutStop;
                            $rapportStop->resultatDiffStop = new DateTime();
                            $rapportStop->resultatDiffStop->setTimestamp($rapportStop->diffStop);
                            $stringJour = _('jour');
                            if ($rapportStop->diffStop / (60 * 60) >= 24)
                            {
                                $rapportStop->dureeStop = $rapportStop->resultatDiffStop->format('d')-1 . strtolower($stringJour[0]) . $rapportStop->resultatDiffStop->format(' H:i:s');
                            }
                            else
                            {
                                $rapportStop->dureeStop = $rapportStop->resultatDiffStop->format('H:i:s');
                            }
//								$rapportStop->dureeStop = $rapportStop->resultatDiffStop->format('d H:i:s');

                            $this2->Cell($w[0], 6, $rapportStop->etape, 'LR', 0, 'C', $rapportStop->fill);
                            $this2->Cell($w[1], 6, substr($rapportStop->thatDateStop, 0, 10), 'LR', 0, 'C', $rapportStop->fill);
                            $this2->Cell($w[2], 6, $rapportStop->dureeStop, 'LR', 0, 'C', $rapportStop->fill);

                            $rapportStop->indexThatAdresse = strrpos($rapportStop->thatAdresse, ' ', -10);
                            if (strlen($rapportStop->thatAdresse) > 60)
                                $rapportStop->indexThatAdresse = strrpos($rapportStop->thatAdresse, ' ', -45);
                            $this2->Cell($w[3], 6, substr($rapportStop->thatAdresse, 0, $rapportStop->indexThatAdresse), 'LR', 0, 'C', $rapportStop->fill);

                            $this2->Cell($w[4], 6, "", 'LR', 0, 'C', $rapportStop->fill);
                            $this2->Ln();
                            $this2->Cell($w[0], 6, "", 'LR', 0, 'C', $rapportStop->fill);
                            $this2->Cell($w[1], 6, substr($rapportStop->thatDateStop, 11), 'LR', 0, 'C', $rapportStop->fill);
                            $this2->Cell($w[2], 6, "", 'LR', 0, 'C', $rapportStop->fill);
                            $this2->Cell($w[3], 6, substr($rapportStop->thatAdresse, $rapportStop->indexThatAdresse), 'LR', 0, 'C', $rapportStop->fill);
                            $this2->Cell($w[4], 6, "", 'LR', 0, 'C', $rapportStop->fill);
                            $this2->Ln();
                            $rapportStop->fill = !$rapportStop->fill;
                        }
                    }
                    if ((($cbalise[$rapportStop->i - 1] == "stop") && ($cbalise[$rapportStop->i] != "stop")))
                    {

                        //						if( ($cbalise[$i-1] != "stop") && ($cbalise[$i] == "stop") ) {
                        //						if ($cbalise[$i - 1] != "stop" && $cbalise[$i] != "stop") {
                        $rapportStop->dateFinStop = strtotime($local_date->format($formatLangDateTime));
                        $rapportStop->diffStop = $rapportStop->dateFinStop - $rapportStop->dateDebutStop;
                        $rapportStop->resultatDiffStop = new DateTime();
                        $rapportStop->resultatDiffStop->setTimestamp($rapportStop->diffStop);
                        $stringJour = _('jour');
                        if ($rapportStop->diffStop / (60 * 60) >= 24)
                        {
                            $rapportStop->dureeStop = $rapportStop->resultatDiffStop->format('d')-1 . strtolower($stringJour[0]) . $rapportStop->resultatDiffStop->format(' H:i:s');
                        }
                        else
                        {
                            $rapportStop->dureeStop = $rapportStop->resultatDiffStop->format('H:i:s');
                        }
//							$rapportStop->dureeStop = $rapportStop->resultatDiffStop->format('d H:i:s');
                        if ($rapportStop->etape == 11)
                        {
                            $this2->Ln();
                            $this2->Ln();
                        }
                        else if ($rapportStop->etape > 12)
                        {
                            if ($rapportStop->etape % 12 == 0)
                            {
                                $this2->Ln();
                                $this2->Ln();
                            }
                        }

                        $this2->Cell($w[0], 6, $rapportStop->thatEtape, 'LR', 0, 'C', $rapportStop->fill);
                        $this2->Cell($w[1], 6, substr($rapportStop->thatDateStop, 0, 10), 'LR', 0, 'C', $rapportStop->fill);
                        $this2->Cell($w[2], 6, $rapportStop->dureeStop, 'LR', 0, 'C', $rapportStop->fill);
                        //						$this2->Cell($w[3],6,$thatAdresse ,'LR',0,'C',$fill);
                        $rapportStop->indexThatAdresse = strrpos($rapportStop->thatAdresse, ' ', -10);
                        if (strlen($rapportStop->thatAdresse) > 60)
                            $rapportStop->indexThatAdresse = strrpos($rapportStop->thatAdresse, ' ', -45);
                        $this2->Cell($w[3], 6, substr($rapportStop->thatAdresse, 0, $rapportStop->indexThatAdresse), 'LR', 0, 'C', $rapportStop->fill);
                        //						$this2->Cell($w[3],6,$thatAdresse,'LR',0,'C',$fill);

                        $this2->Cell($w[4], 6, "", 'LR', 0, 'C', $rapportStop->fill);

                        $this2->Ln();
                        $this2->Cell($w[0], 6, "", 'LR', 0, 'C', $rapportStop->fill);
                        $this2->Cell($w[1], 6, substr($rapportStop->thatDateStop, 11), 'LR', 0, 'C', $rapportStop->fill);
                        $this2->Cell($w[2], 6, "", 'LR', 0, 'C', $rapportStop->fill);
                        $this2->Cell($w[3], 6, substr($rapportStop->thatAdresse, $rapportStop->indexThatAdresse), 'LR', 0, 'C', $rapportStop->fill);
                        //						else $this2->Cell($w[3],6,'','LR',0,'C',$fill);
                        $this2->Cell($w[4], 6, "", 'LR', 0, 'C', $rapportStop->fill);
                        $this2->Ln();
                        $rapportStop->fill = !$rapportStop->fill;

                        $rapportStop->iDureeStop++;
                        $rapportStop->etape++;
                        $rapportStop->condition = "";
                        $rapportStop->conditionOk = "";
                    }
                }
            }

            $rapportStop->i++;

            return $rapportStop;
        };

        $Hd1 = "";
        $Hf1 = "";
        $Hd2 = "";
        $Hf2 = "";
        $Lundi = "";
        $Mardi = "";
        $Mercredi = "";
        $Jeudi = "";
        $Vendredi = "";
        $Samedi = "";
        $Dimanche = "";

        // $connection2 = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
        // $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $_POST["idBaliseRapport"] . "' )";
        // $result2 = mysqli_query($connection2, $sql2);

        $connectStop = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
        $resultStop = mysqli_query($connectStop, $sql);
        $lengthStop = 0;
        $rapportStop = new RapportStop();
        if (sizeof($arrayTpositions) > 1)
        {
            if (mysqli_multi_query($connectStop, $sql))
            {
                do
                {
                    if ($resultStop = mysqli_store_result($connectStop))
                    {
                        $lengthStop += mysqli_num_rows($resultStop);
                    }
                } while (mysqli_more_results($connectStop) && mysqli_next_result($connectStop));
            }
            if (mysqli_multi_query($connectStop, $sql))
            {
                do
                {
                    if ($resultStop = mysqli_store_result($connectStop))
                    {
//                         if (mysqli_num_rows($result2) > 0)
//                         {

//                             while ($row2 = mysqli_fetch_array($result2))
//                             {
//                                 $NbrPlage = $row2['NbrPlage'];
//                                 $Hd1 = $row2['Hd1'];
//                                 $Hf1 = $row2['Hf1'];
//                                 $Hd2 = $row2['Hd2'];
//                                 $Hf2 = $row2['Hf2'];
//                                 $Lundi = $row2['Lundi'];
//                                 $Mardi = $row2['Mardi'];
//                                 $Mercredi = $row2['Mercredi'];
//                                 $Jeudi = $row2['Jeudi'];
//                                 $Vendredi = $row2['Vendredi'];
//                                 $Samedi = $row2['Samedi'];
//                                 $Dimanche = $row2['Dimanche'];
//                             }
//                             while ($row = mysqli_fetch_array($resultStop))
//                             {
//                                 $utc_date = DateTime::createFromFormat(
//                                                 'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
//                                 );
//                                 $local_date = $utc_date;
//                                 $local_date->setTimeZone(new DateTimeZone($timezone));
// //									ini_set('display_errors', 'off');
//                                 $dateNewDateTime = new DateTime();
//                                 if (( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0] . $Hd1[1]), intval($Hd1[2] . $Hd1[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0] . $Hf1[1]), intval($Hf1[2] . $Hf1[3]))->format("H:i:s")) ) ) || ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0] . $Hd2[1]), intval($Hd2[2] . $Hd2[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0] . $Hf2[1]), intval($Hf2[2] . $Hf2[3]))->format("H:i:s")) ) ))
//                                 {


//                                     if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
//                                             ($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
//                                             ($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
//                                             ($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
//                                             ($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
//                                             ($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
//                                             ($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")
//                                     )
//                                     {
//                                         $rapportStop = $queryFetchArray($local_date, $lengthStop, $lengthEtapePOI, $w, $formatLangDateTime, $row, $this, $cbalise, $rapportStop, $latPOI, $lngPOI, $rayonPOI, $descriptionPOI, $nomPOI);
//                                     }
//                                 }
//                             }
//                         }
//                         else
//                         {
                            while ($row = mysqli_fetch_array($resultStop))
                            {
                                $utc_date = DateTime::createFromFormat(
                                                'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                                );
                                $local_date = $utc_date;
                                $local_date->setTimeZone(new DateTimeZone($timezone));
//									ini_set('display_errors', 'off');

                                $rapportStop = $queryFetchArray($local_date, $lengthStop, $lengthEtapePOI, $w, $formatLangDateTime, $row, $this, $cbalise, $rapportStop, $latPOI, $lngPOI, $rayonPOI, $descriptionPOI, $nomPOI);
                            }
                        // }
                        if (!mysqli_more_results($connectStop))
                            $this->Cell(array_sum($w), 0, '', 'T');

                        mysqli_free_result($resultStop);
                    }
                } while (mysqli_more_results($connectStop) && mysqli_next_result($connectStop));
            }
        }else
        {

            $resultStop = mysqli_query($connectStop, $sql);
            if ($resultStop !== false)
            {
                $lengthStop = mysqli_num_rows($resultStop);
//                 if (mysqli_num_rows($result2) > 0)
//                 {

//                     while ($row2 = mysqli_fetch_array($result2))
//                     {
//                         $NbrPlage = $row2['NbrPlage'];
//                         $Hd1 = $row2['Hd1'];
//                         $Hf1 = $row2['Hf1'];
//                         $Hd2 = $row2['Hd2'];
//                         $Hf2 = $row2['Hf2'];
//                         $Lundi = $row2['Lundi'];
//                         $Mardi = $row2['Mardi'];
//                         $Mercredi = $row2['Mercredi'];
//                         $Jeudi = $row2['Jeudi'];
//                         $Vendredi = $row2['Vendredi'];
//                         $Samedi = $row2['Samedi'];
//                         $Dimanche = $row2['Dimanche'];
//                     }
//                     while ($row = mysqli_fetch_array($resultStop))
//                     {
//                         $utc_date = DateTime::createFromFormat(
//                                         'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
//                         );
//                         $local_date = $utc_date;
//                         $local_date->setTimeZone(new DateTimeZone($timezone));
// //							ini_set('display_errors', 'off');
//                         $dateNewDateTime = new DateTime();
//                         if (( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0] . $Hd1[1]), intval($Hd1[2] . $Hd1[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0] . $Hf1[1]), intval($Hf1[2] . $Hf1[3]))->format("H:i:s")) ) ) || ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0] . $Hd2[1]), intval($Hd2[2] . $Hd2[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0] . $Hf2[1]), intval($Hf2[2] . $Hf2[3]))->format("H:i:s")) ) ))
//                         {


//                             if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
//                                     ($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
//                                     ($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
//                                     ($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
//                                     ($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
//                                     ($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
//                                     ($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")
//                             )
//                             {
//                                 $rapportStop = $queryFetchArray($local_date, $lengthStop, $lengthEtapePOI, $w, $formatLangDateTime, $row, $this, $cbalise, $rapportStop, $latPOI, $lngPOI, $rayonPOI, $descriptionPOI, $nomPOI);
//                             }
//                         }
//                     }
//                 }
//                 else
//                 {
                    while ($row = mysqli_fetch_array($resultStop))
                    {
                        $utc_date = DateTime::createFromFormat(
                                        'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                        );
                        $local_date = $utc_date;
                        $local_date->setTimeZone(new DateTimeZone($timezone));
//							ini_set('display_errors', 'off');

                        $rapportStop = $queryFetchArray($local_date, $lengthStop, $lengthEtapePOI, $w, $formatLangDateTime, $row, $this, $cbalise, $rapportStop, $latPOI, $lngPOI, $rayonPOI, $descriptionPOI, $nomPOI);
                    }
                // }
                $this->Cell(array_sum($w), 0, '', 'T');
            }
            mysqli_free_result($resultStop);
        }
        mysqli_close($connectStop);
    }

    /*     * **********************************************************************************************
     * ************************************************************************************************	PAGE STOP
     * ********************************************************************************************** */

    function pageAdresse($nomBaliseRapport, array $cbalise, $sql, $arrayTpositions,$db_user_2,$db_pass_2)
    {
        require_once ("../../../lib/php-gettext-1.0.12/gettext.inc");
        $locale = "fr_FR";
        if (isset($_SESSION["language"]))
        {
            $locale = $_SESSION['language'];
        }
        else
        {
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
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(254, 254, 254);
        $this->SetFillColor(96, 104, 103);
        $this->Cell(0, 6, utf8_decode(_('rapport_resumeadresse') . ": " . _('balise') . " $nomBaliseRapport"), 0, 1, 'C', true);
        $this->Ln(4);
        $this->y0 = $this->GetY();

        $header = array("N", "Etat", "Date position", utf8_decode(_('vitesse')), _('rapport_lieu'), _('rapport_commentaire'));

        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(254, 254, 254);
        $this->SetFillColor(96, 104, 103);
        $this->SetDrawColor(96, 104, 103);
        $this->SetLineWidth(.3);
        $w = array(10, 10, 33, 15, 98, 111);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $this->Ln();

        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('', '', 8);


        $nomDatabaseGpw = $_POST["nomDatabaseGpwRapport"];
        $ipDatabaseGpw = $_POST["ipDatabaseGpwRapport"];
        $connect = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
        mysqli_set_charset($connect, "utf8");
        $timezone = $_POST["timezone"];
        if ((substr($_SESSION['language'], -2) == "US"))
            $formatLangDateTime = "Y-m-d h:i:s A";
        else
            $formatLangDateTime = "Y-m-d H:i:s";

        $Hd1 = "";
        $Hf1 = "";
        $Hd2 = "";
        $Hf2 = "";
        $Lundi = "";
        $Mardi = "";
        $Mercredi = "";
        $Jeudi = "";
        $Vendredi = "";
        $Samedi = "";
        $Dimanche = "";

        // $connection2 = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
        // $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $_POST["idBaliseRapport"] . "' )";
        // $result2 = mysqli_query($connection2, $sql2);

        $queryFetchArray = function($local_date, $w, $formatLangDateTime, $row, $this2,
                $cbalise, $rapportAdresse) {

            if (($cbalise[$rapportAdresse->i - 1] != "stop") && ($cbalise[$rapportAdresse->i] == "stop"))
            {
                $this2->Cell($w[0], 6, $rapportAdresse->i, 'LR', 0, 'C', $rapportAdresse->fill);
                if ($cbalise[$rapportAdresse->i] == "stop")
                    $this2->Cell($w[1], 6, $this2->Image('../../assets/img/ICONES/stop16.png', $this2->GetX() + 3, $this2->GetY() + 1, 4, 4, 'PNG'), 'LR', 0, 'C', 0);
                if ($cbalise[$rapportAdresse->i] == "rouge")
                    $this2->Cell($w[1], 6, $this2->Image('../../assets/img/ICONES/cRouge.png', $this2->GetX() + 3, $this2->GetY() + 1, 4, 4, 'PNG'), 'LR', 0, 'C', 0);
                if ($cbalise[$rapportAdresse->i] == "jaune")
                    $this2->Cell($w[1], 6, $this2->Image('../../assets/img/ICONES/cJaune.png', $this2->GetX() + 3, $this2->GetY() + 1, 4, 4, 'PNG'), 'LR', 0, 'C', 0);
                if ($cbalise[$rapportAdresse->i] == "vert")
                    $this2->Cell($w[1], 6, $this2->Image('../../assets/img/ICONES/cVert.png', $this2->GetX() + 3, $this2->GetY() + 1, 4, 4, 'PNG'), 'LR', 0, 'C', 0);
                $this2->Cell($w[2], 6, $local_date->format($formatLangDateTime), 'LR', 0, 'C', $rapportAdresse->fill);
                $this2->Cell($w[3], 6, $row['Pos_Vitesse'], 'LR', 0, 'C', $rapportAdresse->fill);
                $rapportAdresse->address[$rapportAdresse->i] = utf8_decode($row['Pos_Adresse']);
                if (strcmp($rapportAdresse->address[$rapportAdresse->i], $rapportAdresse->address[$rapportAdresse->i - 1]) !== 0)
                    $this2->Cell($w[4], 6, utf8_decode($row['Pos_Adresse']), 'LR', 0, 'C', $rapportAdresse->fill);
                else
                    $this2->Cell($w[4], 6, "-", 'LR', 0, 'C', $rapportAdresse->fill);
                $this2->Cell($w[5], 6, "", 'LR', 0, 'C', $rapportAdresse->fill);

                $this2->Ln();
                $rapportAdresse->fill = !$rapportAdresse->fill;
            }
            if (($cbalise[$rapportAdresse->i] != "stop"))
            {
                $this2->Cell($w[0], 6, $rapportAdresse->i, 'LR', 0, 'C', $rapportAdresse->fill);
                if ($cbalise[$rapportAdresse->i] == "stop")
                    $this2->Cell($w[1], 6, $this2->Image('../../assets/img/ICONES/stop16.png', $this2->GetX() + 3, $this2->GetY() + 1, 4, 4, 'PNG'), 'LR', 0, 'C', 0);
                if ($cbalise[$rapportAdresse->i] == "rouge")
                    $this2->Cell($w[1], 6, $this2->Image('../../assets/img/ICONES/cRouge.png', $this2->GetX() + 3, $this2->GetY() + 1, 4, 4, 'PNG'), 'LR', 0, 'C', 0);
                if ($cbalise[$rapportAdresse->i] == "jaune")
                    $this2->Cell($w[1], 6, $this2->Image('../../assets/img/ICONES/cJaune.png', $this2->GetX() + 3, $this2->GetY() + 1, 4, 4, 'PNG'), 'LR', 0, 'C', 0);
                if ($cbalise[$rapportAdresse->i] == "vert")
                    $this2->Cell($w[1], 6, $this2->Image('../../assets/img/ICONES/cVert.png', $this2->GetX() + 3, $this2->GetY() + 1, 4, 4, 'PNG'), 'LR', 0, 'C', 0);
                $this2->Cell($w[2], 6, $local_date->format($formatLangDateTime), 'LR', 0, 'C', $rapportAdresse->fill);
                $this2->Cell($w[3], 6, $row['Pos_Vitesse'], 'LR', 0, 'C', $rapportAdresse->fill);
                $address[$rapportAdresse->i] = utf8_decode($row['Pos_Adresse']);
                if (strcmp($address[$rapportAdresse->i], $address[$rapportAdresse->i - 1]) !== 0)
                    $this2->Cell($w[4], 6, utf8_decode($row['Pos_Adresse']), 'LR', 0, 'C', $rapportAdresse->fill);
                else
                    $this2->Cell($w[4], 6, "-", 'LR', 0, 'C', $rapportAdresse->fill);
                $this2->Cell($w[5], 6, "", 'LR', 0, 'C', $rapportAdresse->fill);

                $this2->Ln();
                $rapportAdresse->fill = !$rapportAdresse->fill;
            }
            $rapportAdresse->i++;
            return $rapportAdresse;
        };

        $rapportAdresse = new RapportAdresse();

        if (sizeof($arrayTpositions) > 1)
        {

            if (mysqli_multi_query($connect, $sql))
            {
                do
                {
                    if ($result = mysqli_store_result($connect))
                    {

                        while ($row = mysqli_fetch_array($result))
                        {

//                             if (mysqli_num_rows($result2) > 0)
//                             {

//                                 while ($row2 = mysqli_fetch_array($result2))
//                                 {
//                                     $NbrPlage = $row2['NbrPlage'];
//                                     $Hd1 = $row2['Hd1'];
//                                     $Hf1 = $row2['Hf1'];
//                                     $Hd2 = $row2['Hd2'];
//                                     $Hf2 = $row2['Hf2'];
//                                     $Lundi = $row2['Lundi'];
//                                     $Mardi = $row2['Mardi'];
//                                     $Mercredi = $row2['Mercredi'];
//                                     $Jeudi = $row2['Jeudi'];
//                                     $Vendredi = $row2['Vendredi'];
//                                     $Samedi = $row2['Samedi'];
//                                     $Dimanche = $row2['Dimanche'];
//                                 }

//                                 $utc_date = DateTime::createFromFormat(
//                                                 'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
//                                 );
//                                 $local_date = $utc_date;
//                                 $local_date->setTimeZone(new DateTimeZone($timezone));
// //									ini_set('display_errors', 'off');

//                                 $dateNewDateTime = new DateTime();
//                                 if (( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0] . $Hd1[1]), intval($Hd1[2] . $Hd1[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0] . $Hf1[1]), intval($Hf1[2] . $Hf1[3]))->format("H:i:s")) ) ) || ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0] . $Hd2[1]), intval($Hd2[2] . $Hd2[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0] . $Hf2[1]), intval($Hf2[2] . $Hf2[3]))->format("H:i:s")) ) ))
//                                 {

//                                     if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
//                                             ($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
//                                             ($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
//                                             ($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
//                                             ($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
//                                             ($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
//                                             ($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")
//                                     )
//                                     {
//                                         $queryFetchArray($local_date, $w, $formatLangDateTime, $row, $this, $cbalise, $rapportAdresse);
//                                     }
//                                 }
//                             }
//                             else
//                             {
                                $utc_date = DateTime::createFromFormat(
                                                'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                                );
                                $local_date = $utc_date;
                                $local_date->setTimeZone(new DateTimeZone($timezone));
//									ini_set('display_errors', 'off');


                                $queryFetchArray($local_date, $w, $formatLangDateTime, $row, $this, $cbalise, $rapportAdresse);
                            // }
                        }

                        if (!mysqli_more_results($connect))
                            $this->Cell(array_sum($w), 0, '', 'T');
                        mysqli_free_result($result);
                    }
                } while (mysqli_more_results($connect) && mysqli_next_result($connect));
            }
        }else
        {

            $result = mysqli_query($connect, $sql);
            if ($result !== false)
            {

                while ($row = mysqli_fetch_array($result))
                {
//                     if (mysqli_num_rows($result2) > 0)
//                     {

//                         while ($row2 = mysqli_fetch_array($result2))
//                         {
//                             $NbrPlage = $row2['NbrPlage'];
//                             $Hd1 = $row2['Hd1'];
//                             $Hf1 = $row2['Hf1'];
//                             $Hd2 = $row2['Hd2'];
//                             $Hf2 = $row2['Hf2'];
//                             $Lundi = $row2['Lundi'];
//                             $Mardi = $row2['Mardi'];
//                             $Mercredi = $row2['Mercredi'];
//                             $Jeudi = $row2['Jeudi'];
//                             $Vendredi = $row2['Vendredi'];
//                             $Samedi = $row2['Samedi'];
//                             $Dimanche = $row2['Dimanche'];
//                         }

//                         $utc_date = DateTime::createFromFormat(
//                                         'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
//                         );
//                         $local_date = $utc_date;
//                         $local_date->setTimeZone(new DateTimeZone($timezone));
// //							ini_set('display_errors', 'off');

//                         $dateNewDateTime = new DateTime();
//                         if (( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0] . $Hd1[1]), intval($Hd1[2] . $Hd1[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0] . $Hf1[1]), intval($Hf1[2] . $Hf1[3]))->format("H:i:s")) ) ) || ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0] . $Hd2[1]), intval($Hd2[2] . $Hd2[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0] . $Hf2[1]), intval($Hf2[2] . $Hf2[3]))->format("H:i:s")) ) ))
//                         {


//                             if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
//                                     ($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
//                                     ($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
//                                     ($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
//                                     ($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
//                                     ($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
//                                     ($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")
//                             )
//                             {
//                                 $queryFetchArray($local_date, $w, $formatLangDateTime, $row, $this, $cbalise, $rapportAdresse);
//                             }
//                         }
//                     }
//                     else
//                     {
                        $utc_date = DateTime::createFromFormat(
                                        'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                        );
                        $local_date = $utc_date;
                        $local_date->setTimeZone(new DateTimeZone($timezone));
//							ini_set('display_errors', 'off');


                        $queryFetchArray($local_date, $w, $formatLangDateTime, $row, $this, $cbalise, $rapportAdresse);
                    // }
                }

                $this->Cell(array_sum($w), 0, '', 'T');
            }
            mysqli_free_result($result);
        }
        mysqli_close($connect);
    }

    /*     * **********************************************************************************************
     * ************************************************************************************************	PAGE GRAPHE
     * ********************************************************************************************** */

    function pageGraphe($debutRapport, $finRapport, $nomBaliseRapport, $idBaliseRapport)
    {

        require_once ("../../../lib/php-gettext-1.0.12/gettext.inc");
        $locale = "fr_FR";
        if (isset($_SESSION["language"]))
        {
            $locale = $_SESSION['language'];
        }
        else
        {
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
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(254, 254, 254);
        $this->SetFillColor(96, 104, 103);
        $this->Cell(0, 6, _("rapport_resumegraphevitesse"), 0, 1, 'C', true);
        $this->Ln(4);

        $username = $_SESSION["username"];
        $nomDuFichier = $idBaliseRapport . $username . $debutRapport . $finRapport;
        $nomDuFichier = str_replace(":", "", $nomDuFichier);
        $nomDuFichier = str_replace("-", "", $nomDuFichier);
        $nomDuFichier = str_replace(" ", "", $nomDuFichier);
        $nomDuFichier = str_replace("*", "", $nomDuFichier);
        $size = getimagesize("../../assets/img/graph/rapportgraph$nomDuFichier.png");
        $largeur = $size[0];
        $hauteur = $size[1];
        $ratio = 120 / $hauteur; //hauteur impos�e de 120mm
        $newlargeur = $largeur * $ratio;
        $posi = (300 - $newlargeur) / 2; //300mm = largeur de page

        $this->SetFont('Arial', 'B', 16);

        $this->Image("../../assets/img/graph/rapportgraph$nomDuFichier.png", $posi, 40, 0, 0, 'PNG');
    }

    function getPoiTracker($db_user_2,$db_pass_2)
    {
        $arrayPoi = array();
        $idBaliseRapport = $_POST["idBaliseRapport"];

        $nomDatabaseGpw = $_POST["nomDatabaseGpwRapport"];
        $ipDatabaseGpw = $_POST["ipDatabaseGpwRapport"];

        $connection = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
        $sql = "SELECT Numero_Zone FROM twarnings2  WHERE Id_tracker = '" . $idBaliseRapport . "' AND Type_Geometrie = '4' ";
        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_array($result))
        {
            //			echo "Numero_Zone:" . $row['Numero_Zone'] . "&";
            array_push($arrayPoi, $row['Numero_Zone']);
        }
        mysqli_free_result($result);
        mysqli_close($connection);

        return $arrayPoi;
    }

    /*     * **********************************************************************************************
     * ************************************************************************************************	calculDistance
     * ********************************************************************************************** */

    function get_distance_m($lat1, $lng1, $lat2, $lng2)
    {
        $earth_radius = 6378137;   // Terre = sph�re de 6378km de rayon
        $rlo1 = deg2rad($lng1);
        $rla1 = deg2rad($lat1);
        $rlo2 = deg2rad($lng2);
        $rla2 = deg2rad($lat2);
        $dlo = ($rlo2 - $rlo1) / 2;
		$dla = ($rla2 - $rla1) / 2;
        $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
        $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        $result = round(($earth_radius * $d) / 1000, 3);
        return $result;
    }

    function get_distance_m2($lat1, $lng1, $lat2, $lng2)
    {
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
        return round(($earth_radius * $d) / 1000, 3);
    }

    function statutEncodeRapport($sql, $arrayTpositions,$db_user_2,$db_pass_2)
    {
        $i = 0;
        $cbalise = array();
        $nomDatabaseGpw = $_POST["nomDatabaseGpwRapport"];
        $ipDatabaseGpw = $_POST["ipDatabaseGpwRapport"];
        $connectStatutEncode = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);

        $Hd1 = "";
        $Hf1 = "";
        $Hd2 = "";
        $Hf2 = "";
        $Lundi = "";
        $Mardi = "";
        $Mercredi = "";
        $Jeudi = "";
        $Vendredi = "";
        $Samedi = "";
        $Dimanche = "";

        // $connection2 = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
        // $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $_POST["idBaliseRapport"] . "' )";
        // $result2 = mysqli_query($connection2, $sql2);

        $queryFetchArray = function($cbalise, $rowStatutEncode, $i) {

            $statutRecup = $rowStatutEncode['Pos_Statut'];
			$vitesseRecup = round($rowStatutEncode['Pos_Vitesse']);
			
            if ($statutRecup & 0x00000004)		// Position en trajet ?
            {
                if ($vitesseRecup == 0)
                {
                    $cbalise[$i] = "rouge";
                }
				else if ($vitesseRecup <= 10)
                {
					$cbalise[$i] = "jaune";
                }
				else if ($vitesseRecup > 10)
                {
                    $cbalise[$i] = "vert";
                }
            }
            else
            {
                $cbalise[$i] = "stop";
            }

            return $cbalise[$i];
        };
        
        if (sizeof($arrayTpositions) > 1)
        {
            $i = 0;
            if (mysqli_multi_query($connectStatutEncode, $sql))
            {
                do
                {
                    if ($resultStatutEncode = mysqli_store_result($connectStatutEncode))
                    {

                        // if (mysqli_num_rows($result2) > 0)
                        // {

                        //     while ($row2 = mysqli_fetch_array($result2))
                        //     {
                        //         $NbrPlage = $row2['NbrPlage'];
                        //         $Hd1 = $row2['Hd1'];
                        //         $Hf1 = $row2['Hf1'];
                        //         $Hd2 = $row2['Hd2'];
                        //         $Hf2 = $row2['Hf2'];
                        //         $Lundi = $row2['Lundi'];
                        //         $Mardi = $row2['Mardi'];
                        //         $Mercredi = $row2['Mercredi'];
                        //         $Jeudi = $row2['Jeudi'];
                        //         $Vendredi = $row2['Vendredi'];
                        //         $Samedi = $row2['Samedi'];
                        //         $Dimanche = $row2['Dimanche'];
                        //     }
                        //     while ($rowStatutEncode = mysqli_fetch_array($resultStatutEncode))
                        //     {

                        //         $utc_date = DateTime::createFromFormat(
                        //                         'Y-m-d H:i:s', $rowStatutEncode['Pos_DateTime_position'], new DateTimeZone('UTC')
                        //         );
                        //         $local_date = $utc_date;
                        //         $local_date->setTimeZone(new DateTimeZone($_POST["timezone"]));

                        //         $dateNewDateTime = new DateTime();
                        //         if (( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0] . $Hd1[1]), intval($Hd1[2] . $Hd1[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0] . $Hf1[1]), intval($Hf1[2] . $Hf1[3]))->format("H:i:s")) ) ) || ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0] . $Hd2[1]), intval($Hd2[2] . $Hd2[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0] . $Hf2[1]), intval($Hf2[2] . $Hf2[3]))->format("H:i:s")) ) ))
                        //         {

                        //             if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
                        //                     ($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
                        //                     ($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
                        //                     ($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
                        //                     ($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
                        //                     ($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
                        //                     ($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday"))
                        //             {

                        //                 $cbalise[$i] = $queryFetchArray($cbalise, $rowStatutEncode, $i);
                        //                 $i++;
                        //             }
                        //         }
                        //     }
                        // }
                        // else
                        // {
                            while ($rowStatutEncode = mysqli_fetch_array($resultStatutEncode))
                            {
                                $cbalise[$i] = $queryFetchArray($cbalise, $rowStatutEncode, $i);
                                $i++;
                            }
                        // }
                        mysqli_free_result($resultStatutEncode);
                    }
                } while (mysqli_more_results($connectStatutEncode) && mysqli_next_result($connectStatutEncode));
            }
        }
        else
        {
            $resultStatutEncode = mysqli_query($connectStatutEncode, $sql);

            // if (mysqli_num_rows($result2) > 0)
            // {

            //     while ($row2 = mysqli_fetch_array($result2))
            //     {
            //         $NbrPlage = $row2['NbrPlage'];
            //         $Hd1 = $row2['Hd1'];
            //         $Hf1 = $row2['Hf1'];
            //         $Hd2 = $row2['Hd2'];
            //         $Hf2 = $row2['Hf2'];
            //         $Lundi = $row2['Lundi'];
            //         $Mardi = $row2['Mardi'];
            //         $Mercredi = $row2['Mercredi'];
            //         $Jeudi = $row2['Jeudi'];
            //         $Vendredi = $row2['Vendredi'];
            //         $Samedi = $row2['Samedi'];
            //         $Dimanche = $row2['Dimanche'];
            //     }
            //     while ($rowStatutEncode = mysqli_fetch_array($resultStatutEncode))
            //     {

            //         $utc_date = DateTime::createFromFormat(
            //                         'Y-m-d H:i:s', $rowStatutEncode['Pos_DateTime_position'], new DateTimeZone('UTC')
            //         );
            //         $local_date = $utc_date;
            //         $local_date->setTimeZone(new DateTimeZone($_POST["timezone"]));

            //         $dateNewDateTime = new DateTime();
            //         if (( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0] . $Hd1[1]), intval($Hd1[2] . $Hd1[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0] . $Hf1[1]), intval($Hf1[2] . $Hf1[3]))->format("H:i:s")) ) ) || ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0] . $Hd2[1]), intval($Hd2[2] . $Hd2[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0] . $Hf2[1]), intval($Hf2[2] . $Hf2[3]))->format("H:i:s")) ) ))
            //         {

            //             if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
            //                     ($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
            //                     ($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
            //                     ($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
            //                     ($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
            //                     ($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
            //                     ($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday"))
            //             {

            //                 $cbalise[$i] = $queryFetchArray($cbalise, $rowStatutEncode, $i);
            //                 $i++;
            //             }
            //         }
            //     }
            // }
            // else
            // {
                while ($rowStatutEncode = mysqli_fetch_array($resultStatutEncode))
                {
                    $cbalise[$i] = $queryFetchArray($cbalise, $rowStatutEncode, $i);
                    $i++;
                }
            // }
        }
        mysqli_close($connectStatutEncode);
        return $cbalise;
    }

}

class Rapport
{

    public $km = array();
    public $km_total=0;
    public $vitesseMoyenne = array();
    public $vitesseMax = array();
    public $vitesse = array();
    public $diffTotalTrajet = array();
    public $diffTotalArret = array();
    public $nbrePosition = 0;
    public $nbreEtape = 0;
    public $v = 0;
    public $conditionOk = "";
    public $condition = "";
    public $iDureeStop = 0;
    public $conditionOkFirstStop = "";
    public $conditionFirstStop = "";
    public $boubou = "";
    public $dateDebut = "";
    public $dateFIN = "";
    public $diffTrajet = "";
    public $latDebut = "";
    public $lngDebut = "";
    public $departureDate = "";
    public $arrivalDate = "";
    public $lastlat = "";
    public $lastlng = "";
}

class RapportEtape extends Rapport
{

    public $i = 0;
    public $y = 0;
    public $fill = false;
    public $adresse;
    public $etape = 1;
    public $debutlieu;
    public $indexDebutLieu;		// pointe une position dans la chaine debutlieu
    public $finLieu;
    public $indexFinLieu;
    public $debutdate;
    public $findate;

}

class RapportStop extends RapportEtape
{

    public $etape = 0;
    public $dateDebutStop = "";
    public $thatEtape = "";
    public $thatDateStop = "";
    public $thatAdresse = "";
    public $indexThatAdresse = "";
    public $diffStop = "";
    public $resultatDiffStop = "";
    public $dureeStop = "";

}

class RapportAdresse extends RapportStop
{

    public $address = array();

}

$rapport = new Rapport();

$pdf = new PDF('L', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 12);

$nomDatabaseGpw = $_POST["nomDatabaseGpwRapport"];
$ipDatabaseGpw = $_POST["ipDatabaseGpwRapport"];

$sql = "";
$arrayTpositions = getAllPeriodTpositions($debutRapport, $finRapport);
$i = 0;

if (sizeof($arrayTpositions) > 1)
{
    for ($i = 0; $i < sizeof($arrayTpositions); $i++)
    {
        $sql .= "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
				FROM $arrayTpositions[$i] WHERE (Pos_DateTime_position BETWEEN '" . $dUTC . "' AND '" . $fUTC . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapport . "' ) ORDER BY Pos_DateTime_position;";
    }
}
else
{
    $sql = "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
							FROM $arrayTpositions[0] WHERE (Pos_DateTime_position BETWEEN '" . $dUTC . "' AND '" . $fUTC . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapport . "' )
							ORDER BY Pos_DateTime_position";
}


$cbalise = $pdf->statutEncodeRapport($sql, $arrayTpositions,$db_user_2,$db_pass_2);

$Hd1 = "";
$Hf1 = "";
$Hd2 = "";
$Hf2 = "";
$Lundi = "";
$Mardi = "";
$Mercredi = "";
$Jeudi = "";
$Vendredi = "";
$Samedi = "";
$Dimanche = "";
// $connection2 = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
// $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $idBaliseRapport . "' )";
// $result2 = mysqli_query($connection2, $sql2);

$connectionMain = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
mysqli_set_charset($connectionMain, "utf8");
$lengths = 0;
$rapport->nbrePosition = 0;
//TRaitemùent des données du tableau de synthese
$queryFetchArray = function($formatLangDateTime, $local_date, $row, $cbalise, $lengths, $rapport, $pdf) {

    $debutRapport = $_POST['debutRapport'];
    $finRapport = $_POST['finRapport'];

    
    if (($rapport->nbrePosition > 0) && (($cbalise[$rapport->nbrePosition - 1] != "stop")  || ($cbalise[$rapport->nbrePosition] != "stop") || ($cbalise[$rapport->nbrePosition + 1] != "stop")))
    {
        $rapport->km_total += $pdf->get_distance_m($rapport->lastlat, $rapport->lastlng, $row["Pos_Latitude"], $row["Pos_Longitude"]);
    }
    if ($rapport->iDureeStop == 0)
    {
        if ($rapport->conditionOkFirstStop == "")
        {
            if ($cbalise[$rapport->nbrePosition] == "stop")
            {
                $rapport->dateDebut = strtotime($local_date->format($formatLangDateTime));
                if ($rapport->nbrePosition == 0)
                    $rapport->dateDebut = strtotime($debutRapport);
                $rapport->conditionFirstStop = "ok";
                $rapport->conditionOkFirstStop = "ok";
            }
        }
        if ($rapport->conditionFirstStop == "ok")
        {
            if ((($cbalise[$rapport->nbrePosition - 1] == "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] != "stop")) 
                    ||(($cbalise[$rapport->nbrePosition - 1] != "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] != "stop")) 
                    ||(($cbalise[$rapport->nbrePosition - 1] == "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] == "stop")) 
                    ||(($cbalise[$rapport->nbrePosition - 1] != "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] == "stop")))
            {

                $rapport->dateFIN = strtotime($local_date->format($formatLangDateTime));
                $rapport->diffTotalArret[$rapport->nbreEtape] = ($rapport->dateFIN - $rapport->dateDebut);
                $rapport->iDureeStop++;
            }
        }
    }
    
    //Recherche départ
    if ($rapport->conditionOk == "")
    {
        
       /* if ((($cbalise[$rapport->nbrePosition - 1] == "stop") && ($cbalise[$rapport->nbrePosition] != "stop"))) && ($cbalise[$rapport->nbrePosition + 1] != "stop")) 
                || (($cbalise[$rapport->nbrePosition - 1] != "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] != "stop")) 
                || (($cbalise[$rapport->nbrePosition - 1] == "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] == "stop")) 
                || (($cbalise[$rapport->nbrePosition - 1] != "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] == "stop")))*/

        if (($cbalise[$rapport->nbrePosition - 1] == "stop") && ($cbalise[$rapport->nbrePosition] != "stop") ) 
        {

            $rapport->latDebut = $row["Pos_Latitude"];
            $rapport->lngDebut = $row["Pos_Longitude"];
            //ChromePhp::log("depart",$local_date->format($formatLangDateTime));
            $rapport->departureDate = strtotime($local_date->format($formatLangDateTime));
            $rapport->dateFIN = strtotime($local_date->format($formatLangDateTime));
            if ($rapport->boubou != 0)
            {
                $rapport->diffTotalArret[$rapport->nbreEtape] = ($rapport->dateFIN - $rapport->dateDebut);
                $rapport->boubou = 0;
            }
            
            $rapport->v++;
            $rapport->condition = "ok";
            $rapport->conditionOk = "ok";

        }
    }
    

    //Recherche parcours
    if ($rapport->condition == "ok")
    {
        //recherche fin du trajet + déplacement
        //if ((($cbalise[$rapport->nbrePosition - 1] == "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] != "stop")) 
        //        || (($cbalise[$rapport->nbrePosition - 1] != "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] != "stop")) 
        //        || (($cbalise[$rapport->nbrePosition - 1] == "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] == "stop")) 
         //       || (($cbalise[$rapport->nbrePosition - 1] != "stop") && ($cbalise[$rapport->nbrePosition] == "stop") && ($cbalise[$rapport->nbrePosition + 1] == "stop")))
        
        //recherche fin du trajet + déplacement
        if ((($cbalise[$rapport->nbrePosition -1] != "stop") && ($cbalise[$rapport->nbrePosition ] == "stop"))  || (($cbalise[$rapport->nbrePosition -1] != "stop") && ($cbalise[$rapport->nbrePosition ] != "stop")))
        {
            //ChromePhp::log($row['Pos_Vitesse']);
            if ($row['Pos_Vitesse'] > 0)
                $rapport->vitesse[$rapport->nbreEtape][$rapport->v] = $row['Pos_Vitesse'];
                
            $rapport->vitesseMoyenne[$rapport->nbreEtape] = floor(array_sum($rapport->vitesse[$rapport->nbreEtape]) / count($rapport->vitesse[$rapport->nbreEtape]));
            $rapport->vitesseMax[$rapport->nbreEtape] = floor(max($rapport->vitesse[$rapport->nbreEtape]));
            if ($rapport->vitesseMax[$rapport->nbreEtape] == "")
                $rapport->vitesseMax[$rapport->nbreEtape] = "0";
               
            $rapport->arrivalDate = strtotime($local_date->format($formatLangDateTime));
           // $rapport->km_total += $pdf->get_distance_m($rapport->lastlat, $rapport->lastlng, $row["Pos_Latitude"], $row["Pos_Longitude"]);
            $rapport->boubou = 1;
            $rapport->v++;
           
        }
        //recuperation debut  trajet
        if ((($cbalise[$rapport->nbrePosition] == "stop") && ($cbalise[$rapport->nbrePosition + 1] == "stop")) 
                || ( ($cbalise[$rapport->nbrePosition] == "stop") && ($cbalise[$rapport->nbrePosition + 1] != "stop")))
        {
            //
            //($cbalise[$rapport->nbrePosition - 1] == "stop") &&
            $rapport->dateDebut = strtotime($local_date->format($formatLangDateTime));
            $rapport->diffTrajet = ($rapport->arrivalDate - $rapport->departureDate);
            
            $rapport->diffTotalTrajet[$rapport->nbreEtape] = ($rapport->arrivalDate - $rapport->departureDate);
            $rapport->dateTrajet = new DateTime();
            $rapport->dateTrajet->setTimestamp($rapport->diffTrajet);

            $rapport->dureeTrajet[$rapport->nbreEtape] = $rapport->dateTrajet->format('H:i:s');
            
            //$rapport->km[$rapport->nbreEtape] = round(SphericalGeometry::computeDistanceBetween(new LatLng($rapport->latDebut, $rapport->lngDebut), new LatLng($row["Pos_Latitude"], $row["Pos_Longitude"]))/1000);
            $rapport->km[$rapport->nbreEtape] = $rapport->km_total;
            $rapport->km_total = 0;
            $rapport->condition = "";
            $rapport->conditionOk = "";
            $rapport->nbreEtape++;
            $rapport->v = 0;
            
        }
        
    }
    if ($rapport->nbrePosition == $lengths - 1)
    {
        if ($cbalise[$rapport->nbrePosition] == "stop")
        {
            $rapport->dateFIN = strtotime($finRapport);
            $rapport->diffTotalArret[$rapport->nbreEtape] = ($rapport->dateFIN - $rapport->arrivalDate);
        }
        else
        {
           
            //ChromePhp::log(date("Y-m-d H:i:s",$rapport->arrivalDate), date("Y-m-d H:i:s",$rapport->departureDate),$finRapport);
            //Correction le 22/07/2020
             $rapport->dateFIN = strtotime($finRapport);
             $rapport->diffTrajet = ($finRapport - $rapport->departureDate);
             $rapport->diffTotalTrajet[$rapport->nbreEtape] = ($rapport->dateFIN - $rapport->departureDate);
             
            //$rapport->diffTrajet = ($rapport->arrivalDate - $rapport->departureDate);
            //$rapport->diffTotalTrajet[$rapport->nbreEtape] = ($rapport->arrivalDate - $rapport->departureDate);
            $rapport->dateTrajet = new DateTime();
            $rapport->dateTrajet->setTimestamp($rapport->diffTrajet);

            $rapport->dureeTrajet[$rapport->nbreEtape] = $rapport->dateTrajet->format('H:i:s');
            //$rapport->km[$rapport->nbreEtape] = $pdf->get_distance_m($rapport->latDebut, $rapport->lngDebut, $row["Pos_Latitude"], $row["Pos_Longitude"]);
             $rapport->km[$rapport->nbreEtape] = $rapport->km_total;
            $rapport->km_total = 0;
//                $rapport->km[$rapport->nbreEtape] = round(SphericalGeometry::computeDistanceBetween(new LatLng($rapport->latDebut, $rapport->lngDebut), new LatLng($row["Pos_Latitude"], $row["Pos_Longitude"]))/1000);
        }
    }
    $rapport->lastlat = $row["Pos_Latitude"];
    $rapport->lastlng = $row["Pos_Longitude"];
    $rapport->nbrePosition++;

    return $rapport;
};

if (sizeof($arrayTpositions) > 1)
{
    if (mysqli_multi_query($connectionMain, $sql))
    {
        do
        {
            if ($result = mysqli_store_result($connectionMain))
            {
                $lengths += mysqli_num_rows($result);
            }
        } while (mysqli_more_results($connectionMain) && mysqli_next_result($connectionMain));
    }
    if (mysqli_multi_query($connectionMain, $sql))
    {
        do
        {
            if ($result = mysqli_store_result($connectionMain))
            {
                // if (mysqli_num_rows($result2) > 0)
                // {

                //     while ($row2 = mysqli_fetch_array($result2))
                //     {
                //         $NbrPlage = $row2['NbrPlage'];
                //         $Hd1 = $row2['Hd1'];
                //         $Hf1 = $row2['Hf1'];
                //         $Hd2 = $row2['Hd2'];
                //         $Hf2 = $row2['Hf2'];
                //         $Lundi = $row2['Lundi'];
                //         $Mardi = $row2['Mardi'];
                //         $Mercredi = $row2['Mercredi'];
                //         $Jeudi = $row2['Jeudi'];
                //         $Vendredi = $row2['Vendredi'];
                //         $Samedi = $row2['Samedi'];
                //         $Dimanche = $row2['Dimanche'];
                //     }

                //     while ($row = mysqli_fetch_array($result))
                //     {
                //         $utc_date = DateTime::createFromFormat(
                //                         'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                //         );
                //         $local_date = $utc_date;
                //         $local_date->setTimeZone(new DateTimeZone($timezone));

                //         $dateNewDateTime = new DateTime();
                //         if (( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0] . $Hd1[1]), intval($Hd1[2] . $Hd1[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0] . $Hf1[1]), intval($Hf1[2] . $Hf1[3]))->format("H:i:s")) ) ) || ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0] . $Hd2[1]), intval($Hd2[2] . $Hd2[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0] . $Hf2[1]), intval($Hf2[2] . $Hf2[3]))->format("H:i:s")) ) ))
                //         {


                //             if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
                //                     ($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
                //                     ($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
                //                     ($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
                //                     ($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
                //                     ($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
                //                     ($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")
                //             )
                //             {
                //                 $rapport = $queryFetchArray($formatLangDateTime, $local_date, $row, $cbalise, $lengths, $rapport, $pdf);
                //             }
                //         }
                //     }
                // }
                // else
                // {
                    while ($row = mysqli_fetch_array($result))
                    {
                        $utc_date = DateTime::createFromFormat(
                                        'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                        );
                        $local_date = $utc_date;
                        $local_date->setTimeZone(new DateTimeZone($timezone));

                        $rapport = $queryFetchArray($formatLangDateTime, $local_date, $row, $cbalise, $lengths, $rapport, $pdf);
                    }
                // }
            }
        } while (mysqli_more_results($connectionMain) && mysqli_next_result($connectionMain));
    }
}
else
{
//     if (mysqli_num_rows($result2) > 0)
//     {
//         $result = mysqli_query($connectionMain, $sql);
//         if ($result !== false)
//         {
//             $lengths = mysqli_num_rows($result);
//             while ($row2 = mysqli_fetch_array($result2))
//             {
//                 $NbrPlage = $row2['NbrPlage'];
//                 $Hd1 = $row2['Hd1'];
//                 $Hf1 = $row2['Hf1'];
//                 $Hd2 = $row2['Hd2'];
//                 $Hf2 = $row2['Hf2'];
//                 $Lundi = $row2['Lundi'];
//                 $Mardi = $row2['Mardi'];
//                 $Mercredi = $row2['Mercredi'];
//                 $Jeudi = $row2['Jeudi'];
//                 $Vendredi = $row2['Vendredi'];
//                 $Samedi = $row2['Samedi'];
//                 $Dimanche = $row2['Dimanche'];
//             }

//             while ($row = mysqli_fetch_array($result))
//             {
//                 $utc_date = DateTime::createFromFormat(
//                                 'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
//                 );
//                 $local_date = $utc_date;
//                 $local_date->setTimeZone(new DateTimeZone($timezone));
// //					ini_set('display_errors', 'off');
//                 $dateNewDateTime = new DateTime();
//                 if (( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0] . $Hd1[1]), intval($Hd1[2] . $Hd1[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0] . $Hf1[1]), intval($Hf1[2] . $Hf1[3]))->format("H:i:s")) ) ) || ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0] . $Hd2[1]), intval($Hd2[2] . $Hd2[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0] . $Hf2[1]), intval($Hf2[2] . $Hf2[3]))->format("H:i:s")) ) ))
//                 {



//                     if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
//                             ($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
//                             ($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
//                             ($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
//                             ($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
//                             ($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
//                             ($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")
//                     )
//                     {
//                         $rapport = $queryFetchArray($formatLangDateTime, $local_date, $row, $cbalise, $lengths, $rapport, $pdf);
//                     }
//                 }
//             }
//         }
//     }
//     else
//     {
        $result = mysqli_query($connectionMain, $sql);
        if ($result !== false)
        {
            $lengths = mysqli_num_rows($result);
            while ($row = mysqli_fetch_array($result))
            {
                $utc_date = DateTime::createFromFormat(
                                'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                );
                $local_date = $utc_date;
                $local_date->setTimeZone(new DateTimeZone($timezone));
//					ini_set('display_errors', 'off');

                $rapport = $queryFetchArray($formatLangDateTime, $local_date, $row, $cbalise, $lengths, $rapport, $pdf);
            }
        }
    // }
    mysqli_free_result($result);
}
mysqli_close($connectionMain);



require_once ("../../../lib/php-gettext-1.0.12/gettext.inc");
$locale = "fr_FR";
if (isset($_SESSION["language"]))
{
    $locale = $_SESSION['language'];
}
else
{
    $_SESSION['language'] = "fr_FR";
    $locale = "fr_FR";
}
T_setlocale(LC_MESSAGES, $locale);
$encoding = "UTF-8";
$domain = "messages";
bindtextdomain($domain, '../../../locale');
bind_textdomain_codeset($domain, $encoding);
textdomain($domain);

// Durees des trajets et arrets
//ChromePhp::log($rapport->diffTotalTrajet);
$additionDureeTrajet = array_sum($rapport->diffTotalTrajet);
$totalDateTrajet = new DateTime();
$totalDateTrajet->setTimestamp($additionDureeTrajet);

$additionDureeArret = array_sum($rapport->diffTotalArret);
$totalDateArret = new DateTime();
$totalDateArret->setTimestamp($additionDureeArret);

$totalKm = array_sum($rapport->km);

// Carburant + CO2
$litrePer100Km = $carburant100KmRapport;
$litrePerKm = $litrePer100Km / 100;

$emisionCO2 = 0;
$typeCarburant = "";

if ($typeCarburantRapport == "1")
{
    $emisionCO2 = (2380 * $litrePer100Km * $totalKm) / 100;
    $typeCarburant = "Essence";
}
if ($typeCarburantRapport == "2")
{
    $emisionCO2 = (2650 * $litrePer100Km * $totalKm) / 100;
    $typeCarburant = _('rapport_gazole');
}
if ($typeCarburantRapport == "3")
{
    $emisionCO2 = (1780 * $litrePer100Km * $totalKm) / 100;
    $typeCarburant = "GPL";
}
if ($typeCarburantRapport == "4")
{
    $emisionCO2 = (2740 * $litrePer100Km * $totalKm) / 100;
    $typeCarburant = _('rapport_gaznaturel');
}

$litreCarburantConsomme = round($totalKm * $litrePerKm, 2);
$emisionCO2 = round($emisionCO2);
$litrePer100Km = round($litrePer100Km, 2);
$litrePerKm = round($litrePerKm, 2);

if ($emisionCO2 == 0)
{
    $emisionCO2 = "??";
    $litrePer100Km = "??";
    $litrePerKm = "??";
}


// Generation PDF
$filename= "Rapport de " . $nomBaliseRapport . " du ". $debutRapport . " au " . $finRapport  . ".pdf";

$pdf->pagePrincipale($titrePeriode, $nomBaliseRapport, $idBaliseRapport, $rapport->nbrePosition, $rapport->nbreEtape, $totalKm, $totalDateTrajet, $totalDateArret, $additionDureeTrajet, $additionDureeArret, $litrePerKm, $emisionCO2, $typeCarburant, $litrePer100Km, $litreCarburantConsomme);
if ((isset($_POST['etapeCheckbox'])))
{
    $pdf->pageEtape($nomBaliseRapport, $cbalise, $rapport->km, $rapport->vitesseMoyenne, $rapport->vitesseMax, $rapport->dureeTrajet, $sql, $arrayTpositions,$db_user_2,$db_pass_2);
}
if ((isset($_POST['stopCheckbox'])))
{
    $pdf->pageStop($fUTC, $nomBaliseRapport, $cbalise, $sql, $arrayTpositions,$db_user_2,$db_pass_2);
}
if ((isset($_POST['checkbox_address'])))
{
    $pdf->pageAdresse($nomBaliseRapport, $cbalise, $sql, $arrayTpositions,$db_user_2,$db_pass_2);
}
if ((isset($_POST['graphCheckbox'])))
{
    $pdf->pageGraphe($debutRapport, $finRapport, $nomBaliseRapport, $idBaliseRapport);
}
$pdf->Output($filename,'I');
?>