<?php

session_start();
$_SESSION['CREATED'] = time();

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


include '../function.php';
include '../dbconnect2.php';
include('../dbtpositions.php');
include('../ChromePhp.php');

require_once '../../../lib/phpExcel1.8.0/Classes/PHPExcel.php';
require_once '../../../lib/phpExcel1.8.0/Classes/PHPExcel/IOFactory.php';
ini_set('precision', '16');
ini_set('max_execution_time', '300');
$idBaliseRapport = $_POST["idBaliseRapport"];
$timezone = $_POST["timezone"];
if ((substr($_SESSION['language'], -2) == "US"))
    $formatLangDateTime = "Y-m-d h:i:s A";
else
    $formatLangDateTime = "Y-m-d H:i:s";
$debutRapport = $_POST['debutRapport'];
$finRapport = $_POST['finRapport'];
$nomBaliseRapport = $_POST['nomBaliseRapport'];
//$titrePeriode = utf8_decode(_('rapport_periodeentre') . " " . date($formatLangDateTime, strtotime($debutRapport)) . " " . _('and') . " " . date($formatLangDateTime, strtotime($finRapport)));
$titrePeriode = (_('rapport_periodeentre') . " " . date("Y-m-d H:i:s", strtotime($debutRapport)) . " " . _('rapport_et') . " " . date("Y-m-d H:i:s", strtotime($finRapport)));
$dUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($debutRapport)), $timezone);
$fUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($finRapport)), $timezone);

/* * **********************************************************************************************
 * ************************************************************************************************	PAGE PRINCIPALE
 * ********************************************************************************************** */

function pagePrincipale($titrePeriode, $nomBaliseRapport, $idBaliseRapport, $position, $etape, $totalKm, $totalDateTrajet, $totalDateArret, $additionDureeTrajet, $additionDureeArret)
{
    $variableJour = _('jour') . "";
    if ($additionDureeTrajet / (60 * 60) >= 744)
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
        $totalDureeTrajet = $valueTrajet["days"] . strtolower($variableJour[0]) . " " . sprintf("%02d", $valueTrajet["hours"])
                . ":" . sprintf("%02d", $valueTrajet["minutes"]) . ":" . sprintf("%02d", $valueTrajet["seconds"]);
    }
    else
    if ($additionDureeTrajet / (60 * 60) >= 24)
    {
        $totalDureeTrajet = $totalDateTrajet->format('d') - 1 . strtolower($variableJour[0]) . $totalDateTrajet->format(' H:i:s');
    }
    else
    {
        $totalDureeTrajet = $totalDateTrajet->format('H:i:s');
    }

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

        $totalDureeArret = $valueArret["days"] . strtolower($variableJour[0]) . " " . sprintf("%02d", $valueArret["hours"]) . ":" .
                sprintf("%02d", $valueArret["minutes"]) . ":" . sprintf("%02d", $valueArret["seconds"]);
    }
    else if ($additionDureeArret / (60 * 60) >= 24)
    {
        $totalDureeArret = $totalDateArret->format('d') - 1 . strtolower($variableJour[0]) . $totalDateArret->format(' H:i:s');
    }
    else
    {
        $totalDureeArret = $totalDateArret->format('H:i:s');
    }

    $classeur = new PHPExcel;
    $classeur->getProperties()->setCreator("GEOFENCE");
    $classeur->setActiveSheetIndex(0);
    $feuille = $classeur->getActiveSheet();
    $feuille->setTitle('RESUME');

    $feuille->getStyle('A0:C10')
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $feuille->getStyle('C4')
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

    $feuille->getStyle('C4')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $feuille->getStyle('B0:B10')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    //$feuille->getDefaultStyle()->applyFromArray($style);

    $feuille->getColumnDimension('A')->setWidth(30);
    $feuille->getColumnDimension('B')->setWidth(5);
    $feuille->getColumnDimension('C')->setWidth(30);

    $feuille->getStyle("B1")->getFont()->setBold(true)
            ->setSize(16);
    $feuille->setCellValueByColumnAndRow(1, 1, _('rapport_rapportgeo3x'));
    $feuille->setCellValueByColumnAndRow(1, 2, $titrePeriode);

    $feuille->setCellValueByColumnAndRow(0, 3, _('nombalise'));
    $feuille->setCellValueByColumnAndRow(2, 3, $nomBaliseRapport);
    $feuille->setCellValueByColumnAndRow(0, 4, 'ID Balise');
    $feuille->setCellValueByColumnAndRow(2, 4, $idBaliseRapport);
    $feuille->setCellValueByColumnAndRow(0, 5, _('rapport_dureedestrajets'));
    $feuille->setCellValueByColumnAndRow(2, 5, $totalDureeTrajet);
    $feuille->setCellValueByColumnAndRow(0, 6, _('rapport_dureedesarrets'));
    $feuille->setCellValueByColumnAndRow(2, 6, $totalDureeArret);

    $feuille->setCellValueByColumnAndRow(0, 7, _('rapport_kmsparcourus'));
    $feuille->setCellValueByColumnAndRow(2, 7, $totalKm);
    $feuille->setCellValueByColumnAndRow(0, 8, _('rapport_nombrepositions'));
    $feuille->setCellValueByColumnAndRow(2, 8, $position);
    $feuille->setCellValueByColumnAndRow(0, 9, _('rapport_nombreetapes'));
    $feuille->setCellValueByColumnAndRow(2, 9, $etape);



    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Rapport résumé de ' . $nomBaliseRapport . ' - Geofence.xlsx"');
    header('Cache-Control: max-age=0');
    $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
    $cacheSettings = array( 'memoryCacheSize'  => '8MB');
    PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    $writer = PHPExcel_IOFactory::createWriter($classeur, 'Excel2007');
    $writer->save('php://output');
}

/* * **********************************************************************************************
 * ************************************************************************************************	PAGE ETAPE
 * ********************************************************************************************** */

function pageEtape($nomBaliseRapport, array $cbalise, array $km, array $vitesseMoyenne, array $vitesseMax, array $dureeTrajet, $sql, $arrayTpositions,$db_user_2,$db_pass_2)
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

    $nomDatabaseGpw = $_POST["nomDatabaseGpwRapport"];
    $ipDatabaseGpw = $_POST["ipDatabaseGpwRapport"];
    $timezone = $_POST["timezone"];

    $header = array(_('rapport_etape'), _('rapport_datedebut'), _('rapport_lieudebut'), _('rapport_datefin'), _('rapport_lieufin'), _('rapport_dureetrajet'), 'Km', _('vitesse') . ' Moy', _('vitesse') . ' Max', _('rapport_dureearret'));
    if ((substr($_SESSION['language'], -2) == "en"))
        $formatLangDateTime = "Y-m-d h:i:s A";
    else
        $formatLangDateTime = "Y-m-d H:i:s";



    $nomPOI = array();
    $descriptionPOI = array();
    $latPOI = array();
    $lngPOI = array();
    $rayonPOI = array();
    $i = 0;
    $connectEtapePOI = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
    $resultEtapePOI = mysqli_query($connectEtapePOI, "select * from tpoi");
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


    $classeur = new PHPExcel;
    $classeur->getProperties()->setCreator("GEOFENCE");
    $classeur->setActiveSheetIndex(0);
    $feuille = $classeur->getActiveSheet();
    $feuille->setTitle('ETAPES');

    $feuille->getColumnDimension('A')->setWidth(20);
    $feuille->getColumnDimension('B')->setWidth(23);
    $feuille->getColumnDimension('C')->setWidth(67);
    $feuille->getColumnDimension('D')->setWidth(23);
    $feuille->getColumnDimension('E')->setWidth(67);
    $feuille->getColumnDimension('F')->setWidth(15);
    $feuille->getColumnDimension('G')->setWidth(12);
    $feuille->getColumnDimension('H')->setWidth(12);
    $feuille->getColumnDimension('I')->setWidth(12);
    $feuille->getColumnDimension('J')->setWidth(12);

    $feuille->setCellValueByColumnAndRow(0, 1, _('balise') . ' ' . $nomBaliseRapport);
    $feuille->setCellValueByColumnAndRow(8, 1, "");
    $feuille->setCellValueByColumnAndRow(9, 1, "");

    for ($i = 0; $i < count($header); $i++)
        $feuille->setCellValueByColumnAndRow($i, 2, $header[$i]);


    $queryFetchArray = function($local_date, $lengthEtape, $formatLangDateTime, $row, $feuille,
            $cbalise, $rapportEtape, $km, $vitesseMoyenne, $vitesseMax, $dureeTrajet) {


        if ($rapportEtape->conditionOk == "")
        {
            if ((($cbalise[$rapportEtape->i - 1] == "stop") && ($cbalise[$rapportEtape->i] != "stop") && ($cbalise[$rapportEtape->i + 1] != "stop")) ||
                    (($cbalise[$rapportEtape->i - 1] != "stop") && ($cbalise[$rapportEtape->i] != "stop") && ($cbalise[$rapportEtape->i + 1] != "stop")) ||
                    (($cbalise[$rapportEtape->i - 1] == "stop") && ($cbalise[$rapportEtape->i] != "stop") && ($cbalise[$rapportEtape->i + 1] == "stop")) ||
                    (($cbalise[$rapportEtape->i - 1] != "stop") && ($cbalise[$rapportEtape->i] != "stop") && ($cbalise[$rapportEtape->i + 1] == "stop")))
            {


                if ($rapportEtape->boubou != 0)
                {
                    $rapportEtape->dateFIN = strtotime($local_date->format($formatLangDateTime));

                    $rapportEtape->diffArret = $rapportEtape->dateFIN - $rapportEtape->dateDebut;
                    $rapportEtape->dateArret = new DateTime();
                    $rapportEtape->dateArret->setTimestamp($rapportEtape->diffArret);
                    $rapportEtape->dureeArret = $rapportEtape->dateArret->format('H:i:s');

                    $feuille->setCellValueByColumnAndRow(0, $rapportEtape->etape + 1, $rapportEtape->etape - 1);
                    $feuille->setCellValueByColumnAndRow(1, $rapportEtape->etape + 1, $rapportEtape->debutdate);
                    $feuille->setCellValueByColumnAndRow(2, $rapportEtape->etape + 1, $rapportEtape->debutAddr);
                    $feuille->setCellValueByColumnAndRow(3, $rapportEtape->etape + 1, $rapportEtape->findate);
                    $feuille->setCellValueByColumnAndRow(4, $rapportEtape->etape + 1, $rapportEtape->finAddr);
                    $feuille->setCellValueByColumnAndRow(5, $rapportEtape->etape + 1, $dureeTrajet[$rapportEtape->y - 1]);
                    $feuille->setCellValueByColumnAndRow(6, $rapportEtape->etape + 1, $km[$rapportEtape->y - 1]);
                    $feuille->setCellValueByColumnAndRow(7, $rapportEtape->etape + 1, $vitesseMoyenne[$rapportEtape->y - 1]);
                    $feuille->setCellValueByColumnAndRow(8, $rapportEtape->etape + 1, $vitesseMax[$rapportEtape->y - 1]);
                    $feuille->setCellValueByColumnAndRow(9, $rapportEtape->etape + 1, $rapportEtape->dureeArret);

                    $rapportEtape->boubou = 0;
                }
                $rapportEtape->debutdate = $local_date->format($formatLangDateTime);
                $rapportEtape->debutdate = $local_date->format($formatLangDateTime);

                $rapportEtape->debutAddr = utf8_decode(iconv("ISO-8859-1//TRANSLIT", "UTF-8", $row["Pos_Adresse"]));

                $rapportEtape->condition = "ok";
                $rapportEtape->conditionOk = "ok";
            }
        }
        if ($rapportEtape->condition == "ok")
        {

            if ($cbalise[$rapportEtape->i] != "stop" && ($cbalise[$rapportEtape->i + 1] == "stop"))
            {

                $rapportEtape->dateFIN[$rapportEtape->y] = strtotime($local_date->format($formatLangDateTime));
                $rapportEtape->findate = $local_date->format($formatLangDateTime);
                $rapportEtape->finAddr = utf8_decode(iconv("ISO-8859-1//TRANSLIT", "UTF-8", $row["Pos_Adresse"]));
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
        if ($rapportEtape->i == $lengthEtape - 1)
        {
            if ($cbalise[$rapportEtape->i] == "stop")
            {

                $rapportEtape->diffArret = strtotime($_POST['finRapport']) - $rapportEtape->dateDebut;
                $rapportEtape->dateArret = new DateTime();
                $rapportEtape->dateArret->setTimestamp($rapportEtape->diffArret);
                $rapportEtape->dureeArret = $rapportEtape->dateArret->format('H:i:s');


                $feuille->setCellValueByColumnAndRow(0, $rapportEtape->etape + 1, $rapportEtape->etape - 1);
                $feuille->setCellValueByColumnAndRow(1, $rapportEtape->etape + 1, $rapportEtape->debutdate);
                $feuille->setCellValueByColumnAndRow(2, $rapportEtape->etape + 1, $rapportEtape->debutAddr);
                $feuille->setCellValueByColumnAndRow(3, $rapportEtape->etape + 1, $rapportEtape->findate);
                $feuille->setCellValueByColumnAndRow(4, $rapportEtape->etape + 1, $rapportEtape->finAddr);
                $feuille->setCellValueByColumnAndRow(5, $rapportEtape->etape + 1, $dureeTrajet[$rapportEtape->y - 1]);
                $feuille->setCellValueByColumnAndRow(6, $rapportEtape->etape + 1, $km[$rapportEtape->y - 1]);
                $feuille->setCellValueByColumnAndRow(7, $rapportEtape->etape + 1, $vitesseMoyenne[$rapportEtape->y - 1]);
                $feuille->setCellValueByColumnAndRow(8, $rapportEtape->etape + 1, $vitesseMax[$rapportEtape->y - 1]);
                $feuille->setCellValueByColumnAndRow(9, $rapportEtape->etape + 1, $rapportEtape->dureeArret);
            }
            else
            {
                $rapportEtape->etape++;
                $feuille->setCellValueByColumnAndRow(0, $rapportEtape->etape + 1, $rapportEtape->etape - 1);
                $feuille->setCellValueByColumnAndRow(1, $rapportEtape->etape + 1, $rapportEtape->debutdate);
                $feuille->setCellValueByColumnAndRow(2, $rapportEtape->etape + 1, $rapportEtape->debutAddr);
                $feuille->setCellValueByColumnAndRow(3, $rapportEtape->etape + 1, "Incomplet");
                $feuille->setCellValueByColumnAndRow(4, $rapportEtape->etape + 1, "");
                $feuille->setCellValueByColumnAndRow(5, $rapportEtape->etape + 1, "");
                $feuille->setCellValueByColumnAndRow(6, $rapportEtape->etape + 1, "");
                $feuille->setCellValueByColumnAndRow(7, $rapportEtape->etape + 1, "");
                $feuille->setCellValueByColumnAndRow(8, $rapportEtape->etape + 1, "");
                $feuille->setCellValueByColumnAndRow(9, $rapportEtape->etape + 1, "");
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
    $lengthEtape = 0;
    ;
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
                    //     while ($row = mysqli_fetch_array($resultEtape))
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
                    //                     ($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday"))
                    //             {


                    //                 $rapportEtape = $queryFetchArray($local_date, $lengthEtape, $formatLangDateTime, $row, $feuille, $cbalise, $rapportEtape, $km, $vitesseMoyenne, $vitesseMax, $dureeTrajet);
                    //             }
                    //         }
                    //     }
                    //     if ($rapportEtape->i != $lengthEtape)
                    //     {
                    //         if ($cbalise[$rapportEtape->i - 1] == "stop")
                    //         {

                    //             $rapportEtape->diffArret = strtotime($_POST['finRapport']) - $rapportEtape->dateDebut;
                    //             $rapportEtape->dateArret = new DateTime();
                    //             $rapportEtape->dateArret->setTimestamp($rapportEtape->diffArret);
                    //             $rapportEtape->dureeArret = $rapportEtape->dateArret->format('H:i:s');


                    //             $feuille->setCellValueByColumnAndRow(0, $rapportEtape->etape + 1, $rapportEtape->etape - 1);
                    //             $feuille->setCellValueByColumnAndRow(1, $rapportEtape->etape + 1, $rapportEtape->debutdate);
                    //             $feuille->setCellValueByColumnAndRow(2, $rapportEtape->etape + 1, $rapportEtape->debutAddr);
                    //             $feuille->setCellValueByColumnAndRow(3, $rapportEtape->etape + 1, $rapportEtape->findate);
                    //             $feuille->setCellValueByColumnAndRow(4, $rapportEtape->etape + 1, $rapportEtape->finAddr);
                    //             $feuille->setCellValueByColumnAndRow(5, $rapportEtape->etape + 1, $dureeTrajet[$rapportEtape->y - 1]);
                    //             $feuille->setCellValueByColumnAndRow(6, $rapportEtape->etape + 1, $km[$rapportEtape->y - 1]);
                    //             $feuille->setCellValueByColumnAndRow(7, $rapportEtape->etape + 1, $vitesseMoyenne[$rapportEtape->y - 1]);
                    //             $feuille->setCellValueByColumnAndRow(8, $rapportEtape->etape + 1, $vitesseMax[$rapportEtape->y - 1]);
                    //             $feuille->setCellValueByColumnAndRow(9, $rapportEtape->etape + 1, $rapportEtape->dureeArret);
                    //         }
                    //         else
                    //         {
                    //             $rapportEtape->etape++;
                    //             $feuille->setCellValueByColumnAndRow(0, $rapportEtape->etape + 1, $rapportEtape->etape - 1);
                    //             $feuille->setCellValueByColumnAndRow(1, $rapportEtape->etape + 1, $rapportEtape->debutdate);
                    //             $feuille->setCellValueByColumnAndRow(2, $rapportEtape->etape + 1, $rapportEtape->debutAddr);
                    //             $feuille->setCellValueByColumnAndRow(3, $rapportEtape->etape + 1, "Incomplet");
                    //             $feuille->setCellValueByColumnAndRow(4, $rapportEtape->etape + 1, "");
                    //             $feuille->setCellValueByColumnAndRow(5, $rapportEtape->etape + 1, "");
                    //             $feuille->setCellValueByColumnAndRow(6, $rapportEtape->etape + 1, "");
                    //             $feuille->setCellValueByColumnAndRow(7, $rapportEtape->etape + 1, "");
                    //             $feuille->setCellValueByColumnAndRow(8, $rapportEtape->etape + 1, "");
                    //             $feuille->setCellValueByColumnAndRow(9, $rapportEtape->etape + 1, "");
                    //         }
                    //     }
                    // }
                    // else
                    // {
                        while ($row = mysqli_fetch_array($resultEtape))
                        {
                            $utc_date = DateTime::createFromFormat(
                                            'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                            );
                            $local_date = $utc_date;
                            $local_date->setTimeZone(new DateTimeZone($timezone));

                            $rapportEtape = $queryFetchArray($local_date, $lengthEtape, $formatLangDateTime, $row, $feuille, $cbalise, $rapportEtape, $km, $vitesseMoyenne, $vitesseMax, $dureeTrajet);
                        }
                    //}
                    mysqli_free_result($resultEtape);
                }
            } while (mysqli_more_results($connectEtape) && mysqli_next_result($connectEtape));
        }
    }
    else
    {

        $resultEtape = mysqli_query($connectEtape, $sql);
        $lengthEtape = mysqli_num_rows($resultEtape);
        if ($resultEtape !== false)
        {
            $rapportEtape = new RapportEtape();

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

            //     while ($row = mysqli_fetch_array($resultEtape))
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
            //                     ($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday"))
            //             {

            //                 $rapportEtape = $queryFetchArray($local_date, $lengthEtape, $formatLangDateTime, $row, $feuille, $cbalise, $rapportEtape, $km, $vitesseMoyenne, $vitesseMax, $dureeTrajet);
            //             }
            //         }
            //     }

            //     if ($rapportEtape->i != $lengthEtape)
            //     {
            //         if ($cbalise[$rapportEtape->i - 1] == "stop")
            //         {

            //             $rapportEtape->diffArret = strtotime($_POST['finRapport']) - $rapportEtape->dateDebut;
            //             $rapportEtape->dateArret = new DateTime();
            //             $rapportEtape->dateArret->setTimestamp($rapportEtape->diffArret);
            //             $rapportEtape->dureeArret = $rapportEtape->dateArret->format('H:i:s');


            //             $feuille->setCellValueByColumnAndRow(0, $rapportEtape->etape + 1, $rapportEtape->etape - 1);
            //             $feuille->setCellValueByColumnAndRow(1, $rapportEtape->etape + 1, $rapportEtape->debutdate);
            //             $feuille->setCellValueByColumnAndRow(2, $rapportEtape->etape + 1, $rapportEtape->debutAddr);
            //             $feuille->setCellValueByColumnAndRow(3, $rapportEtape->etape + 1, $rapportEtape->findate);
            //             $feuille->setCellValueByColumnAndRow(4, $rapportEtape->etape + 1, $rapportEtape->finAddr);
            //             $feuille->setCellValueByColumnAndRow(5, $rapportEtape->etape + 1, $dureeTrajet[$rapportEtape->y - 1]);
            //             $feuille->setCellValueByColumnAndRow(6, $rapportEtape->etape + 1, $km[$rapportEtape->y - 1]);
            //             $feuille->setCellValueByColumnAndRow(7, $rapportEtape->etape + 1, $vitesseMoyenne[$rapportEtape->y - 1]);
            //             $feuille->setCellValueByColumnAndRow(8, $rapportEtape->etape + 1, $vitesseMax[$rapportEtape->y - 1]);
            //             $feuille->setCellValueByColumnAndRow(9, $rapportEtape->etape + 1, $rapportEtape->dureeArret);
            //         }
            //         else
            //         {
            //             $rapportEtape->etape++;
            //             $feuille->setCellValueByColumnAndRow(0, $rapportEtape->etape + 1, $rapportEtape->etape - 1);
            //             $feuille->setCellValueByColumnAndRow(1, $rapportEtape->etape + 1, $rapportEtape->debutdate);
            //             $feuille->setCellValueByColumnAndRow(2, $rapportEtape->etape + 1, $rapportEtape->debutAddr);
            //             $feuille->setCellValueByColumnAndRow(3, $rapportEtape->etape + 1, "Incomplet");
            //             $feuille->setCellValueByColumnAndRow(4, $rapportEtape->etape + 1, "");
            //             $feuille->setCellValueByColumnAndRow(5, $rapportEtape->etape + 1, "");
            //             $feuille->setCellValueByColumnAndRow(6, $rapportEtape->etape + 1, "");
            //             $feuille->setCellValueByColumnAndRow(7, $rapportEtape->etape + 1, "");
            //             $feuille->setCellValueByColumnAndRow(8, $rapportEtape->etape + 1, "");
            //             $feuille->setCellValueByColumnAndRow(9, $rapportEtape->etape + 1, "");
            //         }
            //     }
            // }
            // else
            // {
                while ($row = mysqli_fetch_array($resultEtape))
                {
                    $utc_date = DateTime::createFromFormat(
                                    'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                    );
                    $local_date = $utc_date;
                    $local_date->setTimeZone(new DateTimeZone($timezone));

                    $rapportEtape = $queryFetchArray($local_date, $lengthEtape, $formatLangDateTime, $row, $feuille, $cbalise, $rapportEtape, $km, $vitesseMoyenne, $vitesseMax, $dureeTrajet);
                }
            // }
        }
        mysqli_free_result($connectEtape);
    }
    mysqli_close($connectEtape);

    $feuille->setCellValueByColumnAndRow(0, $rapportEtape->etape + 2, _('rapport_commentaire'));

    // envoi du fichier au navigateur
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Rapport des étapes de ' . $nomBaliseRapport . ' - Geofence.xlsx"');
    header('Cache-Control: max-age=0');
    $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
    $cacheSettings = array( 'memoryCacheSize'  => '8MB');
    PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    $writer = PHPExcel_IOFactory::createWriter($classeur, 'Excel2007');
    $writer->save('php://output');
}




/* * **********************************************************************************************
 * ************************************************************************************************	PAGE STOP
 * ********************************************************************************************** */

function pageStop($nomBaliseRapport, array $cbalise, $sql, $arrayTpositions,$db_user_2,$db_pass_2)
{
    $nomDatabaseGpw = $_POST["nomDatabaseGpwRapport"];
    $ipDatabaseGpw = $_POST["ipDatabaseGpwRapport"];
    $timezone = $_POST["timezone"];
    $header = array(_('rapport_etape'), _('rapport_datedebut') . ' Stop', iconv("ISO-8859-1//TRANSLIT", "UTF-8", _('rapport_dureearret')), _('rapport_lieustop'), _('rapport_commentaire'));

    if ((substr($_SESSION['language'], -2) == "en"))
        $formatLangDateTime = "Y-m-d h:i:s A";
    else
        $formatLangDateTime = "Y-m-d H:i:s";
    $classeur = new PHPExcel;
    $classeur->getProperties()->setCreator("GEOFENCE");
    $classeur->setActiveSheetIndex(0);
    $feuille = $classeur->getActiveSheet();

    $feuille->getColumnDimension('A')->setWidth(20);
    $feuille->getColumnDimension('B')->setWidth(23);
    $feuille->getColumnDimension('C')->setWidth(15);
    $feuille->getColumnDimension('D')->setWidth(67);
    $feuille->getColumnDimension('E')->setWidth(67);
    $feuille->getColumnDimension('F')->setWidth(15);

    $feuille->setTitle('STOP');
    $feuille->setCellValueByColumnAndRow(0, 1, _('balise') . ' ' . $nomBaliseRapport);
    $feuille->setCellValueByColumnAndRow(8, 1, "");
    $feuille->setCellValueByColumnAndRow(9, 1, "");
    for ($i = 0; $i < count($header); $i++)
        $feuille->setCellValueByColumnAndRow($i, 2, $header[$i]);


    $nomPOI = array();
    $descriptionPOI = array();
    $latPOI = array();
    $lngPOI = array();
    $rayonPOI = array();
    $i = 0;
    $connectEtapePOI = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
    $resultEtapePOI = mysqli_query($connectEtapePOI, "select * from tpoi");
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


    $queryFetchArray = function($local_date, $lengthStop, $formatLangDateTime, $row, $feuille,
            $cbalise, $rapportStop) {

        $finRapport = $_POST['finRapport'];

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

                    $rapportStop->thatAdresse = utf8_decode(iconv("ISO-8859-1//TRANSLIT", "UTF-8", $row["Pos_Adresse"]));

                    $rapportStop->conditionFirstStop = "ok";
                    $rapportStop->conditionOkFirstStop = "ok";
                }
            }
            if ($rapportStop->conditionFirstStop == "ok")
            {
                if ((($cbalise[$rapportStop->i - 1] == "stop") && ($cbalise[$rapportStop->i] != "stop") && ($cbalise[$rapportStop->i + 1] != "stop")) ||
                        (($cbalise[$rapportStop->i - 1] != "stop") && ($cbalise[$rapportStop->i] != "stop") && ($cbalise[$rapportStop->i + 1] != "stop")) ||
                        (($cbalise[$rapportStop->i - 1] == "stop") && ($cbalise[$rapportStop->i] != "stop") && ($cbalise[$rapportStop->i + 1] == "stop")) ||
                        (($cbalise[$rapportStop->i - 1] != "stop") && ($cbalise[$rapportStop->i] != "stop") && ($cbalise[$rapportStop->i + 1] == "stop")))
                {
                    //						if ($cbalise[$i - 1] != "stop" && $cbalise[$i] == "stop") {
                    $rapportStop->dateFinStop = strtotime($local_date->format($formatLangDateTime));

                    $rapportStop->diffStop = $rapportStop->dateFinStop - $rapportStop->dateDebutStop;
                    $rapportStop->resultatDiffStop = new DateTime();
                    $rapportStop->resultatDiffStop->setTimestamp($rapportStop->diffStop);
                    $rapportStop->dureeStop = $rapportStop->resultatDiffStop->format('H:i:s');

                    $feuille->setCellValueByColumnAndRow(0, $rapportStop->etape + 3, $rapportStop->thatEtape);
                    $feuille->setCellValueByColumnAndRow(1, $rapportStop->etape + 3, $rapportStop->thatDateStop);
                    $feuille->setCellValueByColumnAndRow(2, $rapportStop->etape + 3, $rapportStop->dureeStop);
                    $feuille->setCellValueByColumnAndRow(3, $rapportStop->etape + 3, $rapportStop->thatAdresse);
                    $feuille->setCellValueByColumnAndRow(4, $rapportStop->etape + 3, " ");

                    $rapportStop->iDureeStop++;
                    $rapportStop->etape++;
                }
            }
        }
        //On cherche les prochain stop du trajet
        if ($rapportStop->iDureeStop != 0)
        {
            if ($rapportStop->conditionOk == "")
            {

                if ($cbalise[$rapportStop->i] == "stop")
                {
                    $rapportStop->dateDebutStop = strtotime($local_date->format($formatLangDateTime));
                    $rapportStop->thatEtape = $rapportStop->etape;
                    $rapportStop->thatDateStop = $local_date->format($formatLangDateTime);

                    $rapportStop->thatAdresse = utf8_decode(iconv("ISO-8859-1//TRANSLIT", "UTF-8", $row["Pos_Adresse"]));

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
//							$local_date->setTimeZone(new DateTimeZone($rapportStop->timezone));
                        $rapportStop->dateFinStop = strtotime($local_date->format($formatLangDateTime));
                        $rapportStop->diffStop = $rapportStop->dateFinStop - $rapportStop->dateDebutStop;
                        $rapportStop->resultatDiffStop = new DateTime();
                        $rapportStop->resultatDiffStop->setTimestamp($rapportStop->diffStop);
                        $rapportStop->dureeStop = $rapportStop->resultatDiffStop->format('H:i:s');

                        $feuille->setCellValueByColumnAndRow(0, $rapportStop->etape + 3, $rapportStop->thatEtape);
                        $feuille->setCellValueByColumnAndRow(1, $rapportStop->etape + 3, $rapportStop->thatDateStop);
                        $feuille->setCellValueByColumnAndRow(2, $rapportStop->etape + 3, $rapportStop->dureeStop);
                        $feuille->setCellValueByColumnAndRow(3, $rapportStop->etape + 3, $rapportStop->thatAdresse);
                        $feuille->setCellValueByColumnAndRow(4, $rapportStop->etape + 3, " ");

                        $rapportStop->etape++;
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
                    $rapportStop->dureeStop = $rapportStop->resultatDiffStop->format('H:i:s');

                    $feuille->setCellValueByColumnAndRow(0, $rapportStop->etape + 3, $rapportStop->thatEtape);
                    $feuille->setCellValueByColumnAndRow(1, $rapportStop->etape + 3, $rapportStop->thatDateStop);
                    $feuille->setCellValueByColumnAndRow(2, $rapportStop->etape + 3, $rapportStop->dureeStop);
                    $feuille->setCellValueByColumnAndRow(3, $rapportStop->etape + 3, $rapportStop->thatAdresse);
                    $feuille->setCellValueByColumnAndRow(4, $rapportStop->etape + 3, " ");


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
                    //     while ($row = mysqli_fetch_array($resultStop))
                    //     {
                    //         $utc_date = DateTime::createFromFormat(
                    //                         'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                    //         );
                    //         $local_date = $utc_date;
                    //         $local_date->setTimeZone(new DateTimeZone($timezone));
                    //         ini_set('display_errors', 'off');
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
                    //                 $rapportStop = $queryFetchArray($local_date, $lengthStop, $formatLangDateTime, $row, $feuille, $cbalise, $rapportStop);
                    //             }
                    //         }
                    //     }
                    // }
                    // else
                    // {
                        while ($row = mysqli_fetch_array($resultStop))
                        {
                            $utc_date = DateTime::createFromFormat(
                                            'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                            );
                            $local_date = $utc_date;
                            $local_date->setTimeZone(new DateTimeZone($timezone));
                            ini_set('display_errors', 'off');

                            $rapportStop = $queryFetchArray($local_date, $lengthStop, $formatLangDateTime, $row, $feuille, $cbalise, $rapportStop);
                        }
                    // }

                    mysqli_free_result($resultStop);
                }
            } while (mysqli_more_results($connectStop) && mysqli_next_result($connectStop));
        }
    }
    else
    {

        $resultStop = mysqli_query($connectStop, $sql);
        if ($resultStop !== false)
        {
            $lengthStop = mysqli_num_rows($resultStop);
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
            //     while ($row = mysqli_fetch_array($resultStop))
            //     {
            //         $utc_date = DateTime::createFromFormat(
            //                         'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
            //         );
            //         $local_date = $utc_date;
            //         $local_date->setTimeZone(new DateTimeZone($timezone));
            //         ini_set('display_errors', 'off');
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
            //                 $rapportStop = $queryFetchArray($local_date, $lengthStop, $formatLangDateTime, $row, $feuille, $cbalise, $rapportStop);
            //             }
            //         }
            //     }
            // }
            // else
            // {
                while ($row = mysqli_fetch_array($resultStop))
                {
                    $utc_date = DateTime::createFromFormat(
                                    'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                    );
                    $local_date = $utc_date;
                    $local_date->setTimeZone(new DateTimeZone($timezone));
                    ini_set('display_errors', 'off');

                    $rapportStop = $queryFetchArray($local_date, $lengthStop, $formatLangDateTime, $row, $feuille, $cbalise, $rapportStop);
                }
            // }
        }
        mysqli_free_result($resultStop);
    }
    mysqli_close($connectStop);


    $feuille->setCellValueByColumnAndRow(0, $rapportStop->etape + 3, _('rapport_commentaire'));
    // envoi du fichier au navigateur
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Rapport des arrêts de ' . $nomBaliseRapport . ' - Geofence.xlsx"');
    header('Cache-Control: max-age=0');
    $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
    $cacheSettings = array( 'memoryCacheSize'  => '8MB');
    PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    $writer = PHPExcel_IOFactory::createWriter($classeur, 'Excel2007');
    $writer->save('php://output');
}
//pageAdress($nomBaliseRapport, $cbalise,$rapport->latDebut,$rapport->lngDebut,$rapport->dateDebut, $rapport->vitesse, $sql, $arrayTpositions);
function pageAdresse($nomBaliseRapport, array $cbalise, $sql, $arrayTpositions,$db_user_2,$db_pass_2)
//function pageAdresse($nomBaliseRapport, array $cbalise, array $lat, array $long, array $dt, array $vit, $sql, $arrayTpositions)
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
    
    $nomDatabaseGpw = $_POST["nomDatabaseGpwRapport"];
    $ipDatabaseGpw = $_POST["ipDatabaseGpwRapport"];
    $timezone = $_POST["timezone"];
    $header = array("N°","Date position", utf8_decode(_('vitesse')),"Latitude", "Longitude", _('rapport_lieu'), _('rapport_commentaire'));

    if ((substr($_SESSION['language'], -2) == "en"))
        $formatLangDateTime = "Y-m-d h:i:s A";
    else
        $formatLangDateTime = "Y-m-d H:i:s";
    
    $classeur = new PHPExcel;
    $classeur->getProperties()->setCreator("GEOFENCE");
    $classeur->setActiveSheetIndex(0);
    $feuille = $classeur->getActiveSheet();

    $feuille->getColumnDimension('A')->setWidth(5);
    $feuille->getColumnDimension('B')->setWidth(20);
    $feuille->getColumnDimension('C')->setWidth(7);
    $feuille->getColumnDimension('D')->setWidth(10);
    $feuille->getColumnDimension('E')->setWidth(10);
    $feuille->getColumnDimension('F')->setWidth(60);
    $feuille->getColumnDimension('G')->setWidth(20);

    $feuille->setTitle("ADRESSES");
    $feuille->setCellValueByColumnAndRow(0, 1, _('balise') . ' ' . $nomBaliseRapport);
    $feuille->setCellValueByColumnAndRow(8, 1, "");
    $feuille->setCellValueByColumnAndRow(9, 1, "");
    for ($i = 0; $i < count($header); $i++)
        $feuille->setCellValueByColumnAndRow($i, 2, $header[$i]);

    //$connection2 = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
     //   $sql2 = "SELECT * FROM tplanning WHERE (Id_tracker = '" . $_POST["idBaliseRapport"] . "' )";
     //   $result2 = mysqli_query($connection2, $sql2);
  
    $queryFetchArray = function($local_date, $w, $formatLangDateTime, $row,$feuille,
                $cbalise, $rapportAdresse) 
    {
        // $rapportAdresse->thatAdresse = utf8_decode(iconv("ISO-8859-1//TRANSLIT", "UTF-8", $row["Pos_Adresse"]));
        $feuille->setCellValueByColumnAndRow(0, $rapportAdresse->i + 3,$rapportAdresse->i+1);
        $feuille->setCellValueByColumnAndRow(1, $rapportAdresse->i + 3, $local_date->format($formatLangDateTime));
        $feuille->setCellValueByColumnAndRow(2, $rapportAdresse->i + 3, $row['Pos_Vitesse']);
        $feuille->setCellValueByColumnAndRow(3, $rapportAdresse->i + 3, $row["Pos_Latitude"]);
        $feuille->setCellValueByColumnAndRow(4, $rapportAdresse->i + 3, $row["Pos_Longitude"]);
        $feuille->setCellValueByColumnAndRow(5, $rapportAdresse->i + 3, $row["Pos_Adresse"]);
       
       // ChromePhp::LOG($rapportAdresse->i,($local_date->format($formatLangDateTime)),$row["Pos_Longitude"],$row["Pos_Latitude"],$row['Pos_Vitesse'],$rapportAdresse->thatAdresse);

        $rapportAdresse->i++;
      
        return $rapportAdresse;
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
    $rapportAdresse = new RapportStop();
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
                    //     while ($row = mysqli_fetch_array($resultStop))
                    //     {
                    //         $utc_date = DateTime::createFromFormat(
                    //                         'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                    //         );
                    //         $local_date = $utc_date;
                    //         $local_date->setTimeZone(new DateTimeZone($timezone));
                    //         ini_set('display_errors', 'off');
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
                    //                 $rapportAdresse = $queryFetchArray($local_date, $lengthStop, $formatLangDateTime, $row, $feuille, $cbalise, $rapportAdresse);
                    //             }
                    //         }
                    //     }
                    // }
                    // else
                    // {
                        while ($row = mysqli_fetch_array($resultStop))
                        {
                            $utc_date = DateTime::createFromFormat(
                                            'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                            );
                            $local_date = $utc_date;
                            $local_date->setTimeZone(new DateTimeZone($timezone));
                            ini_set('display_errors', 'off');

                            $rapportAdresse = $queryFetchArray($local_date, $lengthStop, $formatLangDateTime, $row, $feuille, $cbalise, $rapportAdresse);
                        }
                    // }

                    mysqli_free_result($resultStop);
                }
            } while (mysqli_more_results($connectStop) && mysqli_next_result($connectStop));
        }
    }
    else
    {

        $resultStop = mysqli_query($connectStop, $sql);
        if ($resultStop !== false)
        {
            $lengthStop = mysqli_num_rows($resultStop);
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
            //     while ($row = mysqli_fetch_array($resultStop))
            //     {
            //         $utc_date = DateTime::createFromFormat(
            //                         'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
            //         );
            //         $local_date = $utc_date;
            //         $local_date->setTimeZone(new DateTimeZone($timezone));
            //         ini_set('display_errors', 'off');
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
            //                 $rapportAdresse = $queryFetchArray($local_date, $lengthStop, $formatLangDateTime, $row, $feuille, $cbalise, $rapportAdresse);
            //             }
            //         }
            //     }
            // }
            // else
            // {
                while ($row = mysqli_fetch_array($resultStop))
                {
                    $utc_date = DateTime::createFromFormat(
                                    'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
                    );
                    $local_date = $utc_date;
                    $local_date->setTimeZone(new DateTimeZone($timezone));
                    ini_set('display_errors', 'off');

                    $rapportAdresse = $queryFetchArray($local_date, $lengthStop, $formatLangDateTime, $row, $feuille, $cbalise, $rapportAdresse);
                }
            // }
        }
        mysqli_free_result($resultStop);
    }
    mysqli_close($connectStop);
    
        
   
    // envoi du fichier au navigateur
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Liste des positions de ' . $nomBaliseRapport . ' - Geofence.xlsx"');
    header('Cache-Control: max-age=0');
    $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
    $cacheSettings = array( 'memoryCacheSize'  => '8MB');
    PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    $writer = PHPExcel_IOFactory::createWriter($classeur, 'Excel2007');
    $writer->save('php://output');
}
    
/* * **********************************************************************************************
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
    $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo
    ));
    $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return round(($earth_radius * $d) / 1000, 3);
}

/* * **********************************************************************************************
 * ************************************************************************************************	statutEncode
 * ********************************************************************************************** */

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
        $statutEncode = array();
        $puissance = 31;
        while ($puissance > 0)
        {
            if (pow(2, $puissance) > $statutRecup)
            {
                array_push($statutEncode, "0");
            }
            else
            {
                $statutRecup = $statutRecup - pow(2, $puissance);
                array_push($statutEncode, "1");
            }
            $puissance--;
        }
        if ($statutEncode[29] == "1")
        {
            if ($rowStatutEncode['Pos_Vitesse'] == 0)
            {
                $cbalise[$i] = "rouge";
            }
            if ($rowStatutEncode['Pos_Vitesse'] <= 10)
            {
                $cbalise[$i] = "jaune";
            }
            if ($rowStatutEncode['Pos_Vitesse'] > 10)
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

class Rapport
{

    public $km = array();
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
    public $km_total = 0;
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
    public $indexDebutLieu;
    public $finLieu;
    public $indexFinLieu;
    public $debutdate;
    public $findate;
    public $debutAddr = "";
    public $finAddr = "";

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

$rapport = new Rapport();

$nomDatabaseGpw = $_POST["nomDatabaseGpwRapport"];
$ipDatabaseGpw = $_POST["ipDatabaseGpwRapport"];

$connectionMain = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);

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

$cbalise = statutEncodeRapport($sql, $arrayTpositions,$db_user_2,$db_pass_2);

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
$lengths = 0;

$queryFetchArray = function($formatLangDateTime, $local_date, $row, $cbalise, $lengths, $rapport) {

    $debutRapport = $_POST['debutRapport'];
    $finRapport = $_POST['finRapport'];

    //ChromePhp::LOG("nbrePosition",$rapport->nbrePosition);
    //     ChromePhp::LOG("rapport->currentlat-1",$currentlat[$rapport->nbrePosition-1]);
    // ChromePhp::LOG("rapport->currentlng-1",$currentlng[$rapport->nbrePosition-1]);
    // ChromePhp::LOG("rapport->currentlat",$currentlat[$rapport->nbrePosition]);
    // ChromePhp::LOG("rapport->currentlng",$currentlng[$rapport->nbrePosition]);
    if (($rapport->nbrePosition > 0) && (($cbalise[$rapport->nbrePosition - 1] != "stop") || ($cbalise[$rapport->nbrePosition] != "stop") || ($cbalise[$rapport->nbrePosition + 1] != "stop")))
    {
        $rapport->km_total += get_distance_m($rapport->lastlat, $rapport->lastlng, $row["Pos_Latitude"], $row["Pos_Longitude"]);
        //ChromePhp::LOG("rapport->km",$rapport->km_total);       
    }
    //ChromePhp::LOG($cbalise[$rapport->nbrePosition - 1],$cbalise[$rapport->nbrePosition],$cbalise[$rapport->nbrePosition + 1] ); 
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
            // ChromePhp::LOG("conditionFirstStop",$rapport->conditionOkFirstStop );
            // ChromePhp::LOG($cbalise[$rapport->nbrePosition] );
        }
        if ($rapport->conditionFirstStop == "ok")
        {
            if ((($cbalise[$rapport->nbrePosition - 1] == "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] != "stop")) || (($cbalise[$rapport->nbrePosition - 1] != "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] != "stop")) || (($cbalise[$rapport->nbrePosition - 1] == "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] == "stop")) || (($cbalise[$rapport->nbrePosition - 1] != "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] == "stop")))
            {
                $rapport->dateFIN = strtotime($local_date->format($formatLangDateTime));
                $rapport->diffTotalArret[$rapport->nbreEtape] = ($rapport->dateFIN - $rapport->dateDebut);
                $rapport->iDureeStop++;

                //ChromePhp::LOG("StopStart");
                //ChromePhp::LOG($cbalise[$rapport->nbrePosition - 1],$cbalise[$rapport->nbrePosition],$cbalise[$rapport->nbrePosition + 1] );       
                //ChromePhp::LOG("dateDebut",date('Y-m-d H:i:s', $rapport->dateDebut));
                //ChromePhp::LOG("dateFIN",date('Y-m-d H:i:s', $rapport->dateFIN));
                // ChromePhp::LOG("diffTotalArret",$rapport->diffTotalArret);
            }
        }
    }

    //REcherche départ
    if ($rapport->conditionOk == "")
    {
        if ((($cbalise[$rapport->nbrePosition - 1] == "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] != "stop")) || (($cbalise[$rapport->nbrePosition - 1] != "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] != "stop")) || (($cbalise[$rapport->nbrePosition - 1] == "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] == "stop")) || (($cbalise[$rapport->nbrePosition - 1] != "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] == "stop")))
        {

            $rapport->latDebut = $row["Pos_Latitude"];
            $rapport->lngDebut = $row["Pos_Longitude"];

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
            //ChromePhp::LOG($cbalise[$rapport->nbrePosition - 1],$cbalise[$rapport->nbrePosition],$cbalise[$rapport->nbrePosition + 1] );       
            //ChromePhp::LOG("departureDate",date('Y-m-d H:i:s', $rapport->departureDate));
            //ChromePhp::LOG("dateDebut",date('Y-m-d H:i:s', $rapport->dateDebut));
            //ChromePhp::LOG("dateFIN",date('Y-m-d H:i:s', $rapport->dateFIN));
            //ChromePhp::LOG("diffTotalArret",$rapport->diffTotalArret);
        }
    }
    //recherceh arrivée
    if ($rapport->condition == "ok")
    {

        if ((($cbalise[$rapport->nbrePosition - 1] == "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] != "stop")) || (($cbalise[$rapport->nbrePosition - 1] != "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] != "stop")) || (($cbalise[$rapport->nbrePosition - 1] == "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] == "stop")) || (($cbalise[$rapport->nbrePosition - 1] != "stop") && ($cbalise[$rapport->nbrePosition] != "stop") && ($cbalise[$rapport->nbrePosition + 1] == "stop")))
        {

            if ($row['Pos_Vitesse'] > 0)
                $rapport->vitesse[$rapport->nbreEtape][$rapport->v] = $row['Pos_Vitesse'];
            $rapport->vitesseMoyenne[$rapport->nbreEtape] = floor(array_sum($rapport->vitesse[$rapport->nbreEtape]) / count($rapport->vitesse[$rapport->nbreEtape]));
            $rapport->vitesseMax[$rapport->nbreEtape] = floor(max($rapport->vitesse[$rapport->nbreEtape]));
            if ($rapport->vitesseMax[$rapport->nbreEtape] == "")
                $rapport->vitesseMax[$rapport->nbreEtape] = "0";
            $rapport->arrivalDate = strtotime($local_date->format($formatLangDateTime));

            $rapport->boubou = 1;
            $rapport->v++;
            //ChromePhp::LOG("arrivalDate",date('Y-m-d H:i:s', $rapport->arrivalDate));
        }
        //recuperation debut - fin  trajet
        if ((($cbalise[$rapport->nbrePosition] == "stop") && ($cbalise[$rapport->nbrePosition + 1] == "stop")) || ( ($cbalise[$rapport->nbrePosition] == "stop") && ($cbalise[$rapport->nbrePosition + 1] != "stop")))
        {
            $rapport->arrivalDate = strtotime($local_date->format($formatLangDateTime));
            $rapport->dateDebut = strtotime($local_date->format($formatLangDateTime));
            $rapport->diffTrajet = ($rapport->arrivalDate - $rapport->departureDate);
            $rapport->diffTotalTrajet[$rapport->nbreEtape] = ($rapport->arrivalDate - $rapport->departureDate);
            $rapport->dateTrajet = new DateTime();
            $rapport->dateTrajet->setTimestamp($rapport->diffTrajet);

            $rapport->dureeTrajet[$rapport->nbreEtape] = $rapport->dateTrajet->format('H:i:s');
            //$rapport->km[$rapport->nbreEtape] = get_distance_m($rapport->latDebut, $rapport->lngDebut, $row["Pos_Latitude"], $row["Pos_Longitude"]);
            $rapport->km[$rapport->nbreEtape] = $rapport->km_total;
            $rapport->km_total = 0;
            $rapport->condition = "";
            $rapport->conditionOk = "";
            $rapport->nbreEtape++;
            $rapport->v = 0;
            //ChromePhp::LOG($cbalise[$rapport->nbrePosition - 1],$cbalise[$rapport->nbrePosition],$cbalise[$rapport->nbrePosition + 1] );       
            //ChromePhp::LOG("rapport->diffTrajet",$rapport->diffTrajet);
            //ChromePhp::LOG("rapport->departureDate",date('Y-m-d H:i:s', $rapport->departureDate));
            //ChromePhp::LOG("rapport->arrivalDate",date('Y-m-d H:i:s', $rapport->arrivalDate));
            //ChromePhp::LOG("diffTotalTrajet", $rapport->diffTotalTrajet);
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
            $rapport->diffTrajet = ($rapport->arrivalDate - $rapport->departureDate);
            $rapport->diffTotalTrajet[$rapport->nbreEtape] = ($rapport->arrivalDate - $rapport->departureDate);
            $rapport->dateTrajet = new DateTime();
            $rapport->dateTrajet->setTimestamp($rapport->diffTrajet);

            $rapport->dureeTrajet[$rapport->nbreEtape] = $rapport->dateTrajet->format('H:i:s');
            $rapport->km[$rapport->nbreEtape] = $rapport->km_total;
            $rapport->km_total = 0;
            //ChromePhp::LOG("rapport->km[rapport->nbreEtape]",$rapport->km[$rapport->nbreEtape]);
            // ChromePhp::LOG("rapport->latDebut",$rapport->latDebut);
            // ChromePhp::LOG("rapport->lngDebut",$rapport->lngDebut);
            //ChromePhp::LOG("row[Pos_Latitude]",$row["Pos_Latitude"]);
            // ChromePhp::LOG("row[Pos_Longitude]",$row["Pos_Longitude"]);
//                $rapport->km[$rapport->nbreEtape] = round(SphericalGeometry::computeDistanceBetween(new LatLng($rapport->latDebut, $rapport->lngDebut), new LatLng($row["Pos_Latitude"], $row["Pos_Longitude"]))/1000);
        }
        //ChromePhp::LOG("rapport->diffTotalArret", $rapport->diffTotalArret);
        //ChromePhp::LOG("rapport->arrivalDate", date('Y-m-d H:i:s', $rapport->arrivalDate));
        //ChromePhp::LOG("rapport->dateFIN", date('Y-m-d H:i:s', $rapport->dateFIN));
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
                //                 $rapport = $queryFetchArray($formatLangDateTime, $local_date, $row, $cbalise, $lengths, $rapport);
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

                        $rapport = $queryFetchArray($formatLangDateTime, $local_date, $row, $cbalise, $lengths, $rapport);
                    }
                // }
            }
        } while (mysqli_more_results($connectionMain) && mysqli_next_result($connectionMain));
    }
}
else
{
    // if (mysqli_num_rows($result2) > 0)
    // {
    //     $result = mysqli_query($connectionMain, $sql);
    //     if ($result !== false)
    //     {
    //         $lengths = mysqli_num_rows($result);
    //         while ($row2 = mysqli_fetch_array($result2))
    //         {
    //             $NbrPlage = $row2['NbrPlage'];
    //             $Hd1 = $row2['Hd1'];
    //             $Hf1 = $row2['Hf1'];
    //             $Hd2 = $row2['Hd2'];
    //             $Hf2 = $row2['Hf2'];
    //             $Lundi = $row2['Lundi'];
    //             $Mardi = $row2['Mardi'];
    //             $Mercredi = $row2['Mercredi'];
    //             $Jeudi = $row2['Jeudi'];
    //             $Vendredi = $row2['Vendredi'];
    //             $Samedi = $row2['Samedi'];
    //             $Dimanche = $row2['Dimanche'];
    //         }

    //         while ($row = mysqli_fetch_array($result))
    //         {
    //             $utc_date = DateTime::createFromFormat(
    //                             'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
    //             );
    //             $local_date = $utc_date;
    //             $local_date->setTimeZone(new DateTimeZone($timezone));
    //             ini_set('display_errors', 'off');
    //             $dateNewDateTime = new DateTime();
    //             if (( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd1[0] . $Hd1[1]), intval($Hd1[2] . $Hd1[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf1[0] . $Hf1[1]), intval($Hf1[2] . $Hf1[3]))->format("H:i:s")) ) ) || ( ( ($local_date->format("H:i:s")) >= ($dateNewDateTime->setTime(intval($Hd2[0] . $Hd2[1]), intval($Hd2[2] . $Hd2[3]))->format("H:i:s"))) && ( ($local_date->format("H:i:s")) <= ($dateNewDateTime->setTime(intval($Hf2[0] . $Hf2[1]), intval($Hf2[2] . $Hf2[3]))->format("H:i:s")) ) ))
    //             {


    //                 if (($Lundi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Monday") ||
    //                         ($Mardi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Tuesday") ||
    //                         ($Mercredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Wednesday") ||
    //                         ($Jeudi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Thursday") ||
    //                         ($Vendredi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Friday") ||
    //                         ($Samedi == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Saturday") ||
    //                         ($Dimanche == "1" && jddayofweek(gregoriantojd($local_date->format("m"), $local_date->format("d"), $local_date->format("Y")), 1) == "Sunday")
    //                 )
    //                 {
    //                     $rapport = $queryFetchArray($formatLangDateTime, $local_date, $row, $cbalise, $lengths, $rapport);
    //                 }
    //             }
    //         }
    //     }
    // }
    // else
    // {
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
                ini_set('display_errors', 'off');

                $rapport = $queryFetchArray($formatLangDateTime, $local_date, $row, $cbalise, $lengths, $rapport);
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


$additionDureeTrajet = array_sum($rapport->diffTotalTrajet);
//ChromePhp::LOG("additionDureeTrajet",date('Y-m-d H:i:s', $additionDureeTrajet));
$totalDateTrajet = new DateTime();
$totalDateTrajet->setTimestamp($additionDureeTrajet);
//	if( $additionDureeTrajet / (60*60) >= 24 ) {
//		$totalDateTrajet->modify('-1 day');
//		$totalDureeTrajet = $totalDateTrajet->format('d\j H:i:s');
//	}else {
//		$totalDureeTrajet = $totalDateTrajet->format('H:i:s');
//	}


$additionDureeArret = array_sum($rapport->diffTotalArret);
//ChromePhp::LOG("additionDureeArret",date('Y-m-d H:i:s', $additionDureeArret));
$totalDateArret = new DateTime();
$totalDateArret->setTimestamp($additionDureeArret);
//	if( $additionDureeArret / (60*60) >= 24 ) {
//		$totalDateArret->modify('-1 day');
//		$totalDureeArret = $totalDateArret->format('d\j H:i:s');
//	}else {
//		$totalDureeArret = $totalDateArret->format('H:i:s');
//	}
$totalKm = array_sum($rapport->km);
//ChromePhp::LOG("rapport->km",$rapport->km);
//ChromePhp::LOG($titrePeriode);
//ChromePhp::LOG($nomBaliseRapport);
//ChromePhp::LOG($idBaliseRapport);
//ChromePhp::LOG($rapport->nbrePosition);
//ChromePhp::LOG($rapport->nbreEtape);
//ChromePhp::LOG($totalKm);
//ChromePhp::LOG($totalDateTrajet);
//ChromePhp::LOG($totalDateArret);
//ChromePhp::LOG($additionDureeTrajet);
//ChromePhp::LOG($additionDureeArret);

if ((isset($_POST['etapeCheckbox'])))
{
    pageEtape($nomBaliseRapport, $cbalise, $rapport->km, $rapport->vitesseMoyenne, $rapport->vitesseMax, $rapport->dureeTrajet, $sql, $arrayTpositions,$db_user_2,$db_pass_2);
}
else if ((isset($_POST['stopCheckbox'])))
{
    pageStop($nomBaliseRapport, $cbalise, $sql, $arrayTpositions,$db_user_2,$db_pass_2);
}
else if ((isset($_POST['checkbox_address'])))
{
   //pageAdresse($nomBaliseRapport, $cbalise,$rapport->latDebut,$rapport->lngDebut,$rapport->dateDebut, $rapport->vitesse, $sql, $arrayTpositions);
   	pageAdresse($nomBaliseRapport, $cbalise, $sql, $arrayTpositions,$db_user_2,$db_pass_2);
}
else
    pagePrincipale($titrePeriode, $nomBaliseRapport, $idBaliseRapport, $rapport->nbrePosition, $rapport->nbreEtape, $totalKm, $totalDateTrajet, $totalDateArret, $additionDureeTrajet, $additionDureeArret);
?>