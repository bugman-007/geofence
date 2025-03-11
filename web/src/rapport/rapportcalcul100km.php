<?php

session_start();
$_SESSION['CREATED'] = time();

include '../function.php';
include '../dbconnect2.php';

ini_set('display_errors', 'off');


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
    $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
    $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $result =  round(($earth_radius * $d)/1000,3);
    return $result;
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
    if (sizeof($arrayTpositions) > 1)
    {
        $i = 0;
        if (mysqli_multi_query($connectStatutEncode, $sql))
        {
            do
            {
                if ($resultStatutEncode = mysqli_store_result($connectStatutEncode))
                {
                    while ($rowStatutEncode = mysqli_fetch_array($resultStatutEncode))
                    {
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
                            $puissance --;
                        }
                        if ($statutEncode[29] == "1")
                        {
                            if ($rowStatutEncode['Pos_Vitesse'] == 0)
                            {
                                $cbalise[$i] = "rouge";
                                $statutSTOP[$i] = "";
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

                        $i++;
                    }
                    mysqli_free_result($resultStatutEncode);
                }
            } while (mysqli_more_results($connectStatutEncode) && mysqli_next_result($connectStatutEncode));
        }
        else
        {
            
        }
    }
    else
    {
        $resultStatutEncode = mysqli_query($connectStatutEncode, $sql);

        while ($rowStatutEncode = mysqli_fetch_array($resultStatutEncode))
        {
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
                $puissance --;
            }
            if ($statutEncode[29] == "1")
            {
                if ($rowStatutEncode['Pos_Vitesse'] == 0)
                {
                    $cbalise[$i] = "rouge";
                    $statutSTOP[$i] = "";
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

            $i++;
        }
    }
    mysqli_close($connectStatutEncode);

    return $cbalise;
}

/* * **********************************************************************************************
 * ************************************************************************************************	MAIN
 * ********************************************************************************************** */
$idBaliseRapport = $_GET["idTracker"];
$timezone = $_GET["timezone"];

$debutRapport = $_GET['debutRapport'];
$finRapport = $_GET['finRapport'];

$carburantRapport = $_GET['carburant'];

$dUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($debutRapport)), $timezone);
$fUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($finRapport)), $timezone);

$nomDatabaseGpw = $_GET["nomDatabaseGpw"];
$ipDatabaseGpw = $_GET["ipDatabaseGpw"];

include('../dbtpositions.php');

$connectionMain = mysqli_connect($ipDatabaseGpw, $db_user_2, $db_pass_2, $nomDatabaseGpw);
mysqli_set_charset($connectionMain, "utf8");


$i = 0;  //incrementation lecture sql
$y = 0;  //incrementation ligne tableau etape
$km = array();
$conditionOk = "";
$condition = "";

$sql = "";
$arrayTpositions = getAllPeriodTpositions($debutRapport, $finRapport);
$i = 0;

$queryFetchArray = function($result, $lengths, $timezone, $conditionOk, $cbalise, $i, $boubou, $y, $carburantRapport) {

    while ($row = mysqli_fetch_array($result))
    {
        $utc_date = DateTime::createFromFormat(
                        'Y-m-d H:i:s', $row['Pos_DateTime_position'], new DateTimeZone('UTC')
        );
        $local_date = $utc_date;
        $local_date->setTimeZone(new DateTimeZone($timezone));
        if ($conditionOk == "")
        {
            if ((($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop")) || (($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] != "stop")) || (($cbalise[$i - 1] == "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop")) || (($cbalise[$i - 1] != "stop") && ($cbalise[$i] != "stop") && ($cbalise[$i + 1] == "stop")))
            {
                $latDebut = $row["Pos_Latitude"];
                $lngDebut = $row["Pos_Longitude"];
                if ($boubou != 0)
                {
                    $boubou = 0;
                }
                $condition = "ok";
                $conditionOk = "ok";
            }
        }
        if ($condition == "ok")
        {
            if (( ($cbalise[$i] == "stop") && ($cbalise[$i + 1] == "stop") ) || ( ($cbalise[$i] == "stop") && ($cbalise[$i + 1] != "stop") ))
            {
                $km[$y] = get_distance_m($latDebut, $lngDebut, $row["Pos_Latitude"], $row["Pos_Longitude"]);
                $condition = "";
                $conditionOk = "";
                $y++;
            }
        }
        if ($i == $lengths - 1)
        {
            if ($cbalise[$i] == "stop")
            {
                
            }
            else
            {
                $km[$y] = get_distance_m($latDebut, $lngDebut, $row["Pos_Latitude"], $row["Pos_Longitude"]);
            }
        }
        $i++;
    }
    $totalKm = array_sum($km);

    $litrePerKm = $carburantRapport / $totalKm;
    $litrePer100Km = $litrePerKm * 100;
    echo $litrePer100Km;
};

$lengths = 0;
if (sizeof($arrayTpositions) > 1)
{
    for ($i = 0; $i < sizeof($arrayTpositions); $i++)
    {
        $sql .= "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
 				FROM $arrayTpositions[$i] WHERE (Pos_DateTime_position BETWEEN '" . $dUTC . "' AND '" . $fUTC . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapport . "' ) ORDER BY Pos_DateTime_position;";
    }
    $i = 0;
    $cbalise = statutEncodeRapport($sql, $arrayTpositions,$db_user_2,$db_pass_2);
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
    else
    {
        echo 0;
    }
    if (mysqli_multi_query($connectionMain, $sql))
    {
        do
        {
            if ($result = mysqli_store_result($connectionMain))
            {
                $queryFetchArray($result, $lengths, $timezone, $conditionOk, $cbalise, $i, $boubou, $y, $carburantRapport);

                mysqli_free_result($result);
            }
        } while (mysqli_more_results($connectionMain) && mysqli_next_result($connectionMain));
    }
    else
    {
        echo 0;
    }
}
else
{
    $sql = "SELECT Pos_DateTime_position,Pos_Latitude,Pos_Longitude,Pos_Statut,Pos_Vitesse,Pos_Direction,Pos_Odometre,Pos_Adresse
						FROM $arrayTpositions[0] WHERE (Pos_DateTime_position BETWEEN '" . $dUTC . "' AND '" . $fUTC . "' ) AND (Pos_Id_tracker = '" . $idBaliseRapport . "' )
						ORDER BY Pos_DateTime_position";
    $cbalise = statutEncodeRapport($sql, $arrayTpositions,$db_user_2,$db_pass_2);
    $result = mysqli_query($connectionMain, $sql);
    if ($result !== false)
    {
        $lengths = mysqli_num_rows($result);
        $queryFetchArray($result, $lengths, $timezone, $conditionOk, $cbalise, $i, $boubou, $y, $carburantRapport);
    }
    mysqli_free_result($result);
}
mysqli_close($connectionMain);


//	$totalKm = array_sum($km);
//
//
//	$litrePerKm = $carburantRapport / $totalKm;
//
//	$litrePer100Km = $litrePerKm * 100;
//	echo $litrePer100Km;
?>