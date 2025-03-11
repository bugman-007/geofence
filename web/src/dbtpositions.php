<?php
/**
 * Created by PhpStorm.
 * User: Christophe NGUYEN
 * Date: 10/02/2016
 * Time: 10:00
 */

function getAllPeriodTpositions($d,$f){
    $arrayTpositions =  array();

    $tableTPositions = "tpositions";
    $dateNow = strtotime(date('Y-m-d H:i:s'));

    /*$dateChosen = strtotime($d);
    $secs = $dateNow - $dateChosen;// == <seconds between the two times>
    $monthDifference = round($secs / (60*60*24*7*4)) ;
    $dayDifference = round($secs / (60*60*24)) ;
    $year = $d[0]."".$d[1]."".$d[2]."".$d[3];
    $month = $d[5]."".$d[6];

    $dateChosen2 = strtotime($f);
    $secs2 = $dateNow - $dateChosen2;
    $monthDifference2 = round($secs2 / (60*60*24*7*4)) ;
    $dayDifference2 = round($secs2 / (60*60*24)) ;
    $year2 = $f[0] . "" . $f[1] . "" . $f[2] . "" . $f[3];
    $month2 = $f[5] . "" . $f[6];

    $secs3 = $dateChosen2 - $dateChosen;
    $monthDifference3 = round($secs3 / (60*60*24*7*4)) ;
    $dayDifference3 = round($secs3 / (60*60*24)) ;
*/
    /*if($dayDifference >= 90){

        if(intval($year) <= 2014 && intval($year2) <= 2014){
            $tableTPositions = "tpositions201412";
            array_push($arrayTpositions, $tableTPositions);
        }else{

            if( (intval($month) <= 3))	$tableTPositions = "tpositions".($year)."03";
            if( (intval($month) > 3) && (intval($month) <= 6))	$tableTPositions = "tpositions".($year)."06";
            if( (intval($month) > 6) && (intval($month) <= 9))	$tableTPositions = "tpositions".($year)."09";
            if( (intval($month) > 9) && (intval($month) <= 12)) $tableTPositions = "tpositions".($year)."12";
            if((intval($year) <= 2014 )) $tableTPositions = "tpositions201412";

            array_push($arrayTpositions, $tableTPositions);


            if($dayDifference3 >= 90) {
                $monthTablePosition1 = intval($tableTPositions[14] . "" . $tableTPositions[15]);
                if ((intval($year) <= 2014)) {
                    $monthTablePosition1 = 12;
                    $secs3 = $dateChosen2 - strtotime("2015-01-01");
                    $monthDifference3 = round($secs3 / (60 * 60 * 24 * 7 * 4));
                }

                while (($monthDifference3 > 0)) {
                    $monthDifference3 -= 3;
                    $monthTablePosition1 += 3;
                    if ($monthTablePosition1 > 12) {
                        $year = intval($year) + 1;
                        $monthTablePosition1 = 3;
                    }
                    if (($monthDifference3 > 0 && (($year . "" . sprintf("%02d", $monthTablePosition1)) < date('Ym'))
                        && (($year . "" . sprintf("%02d", $monthTablePosition1)) >= "201412") && ($monthTablePosition1 <= 12))) {
                        array_push($arrayTpositions, "tpositions" . ($year) . "" . sprintf("%02d", $monthTablePosition1));
                    }
                }
            }


            if($dayDifference2 >= 90){
                if ((intval($month2) <= 3)) $tableTPositions2 = "tpositions" . ($year2) . "03";
                if ((intval($month2) > 3) && (intval($month2) <= 6)) $tableTPositions2 = "tpositions" . ($year2) . "06";
                if ((intval($month2) > 6) && (intval($month2) <= 9)) $tableTPositions2 = "tpositions" . ($year2) . "09";
                if ((intval($month2) > 9) && (intval($month2) <= 12)) $tableTPositions2 = "tpositions" . ($year2) . "12";
                if (!in_array($tableTPositions2, $arrayTpositions))  array_push($arrayTpositions, $tableTPositions2);
            }else{
                $tableTPositions2 = "tpositions";
                if (!in_array($tableTPositions2, $arrayTpositions))  array_push($arrayTpositions, $tableTPositions2);
            }

        }
        $tableTPositions = "tpositions";
        array_push($arrayTpositions, $tableTPositions);
    }else{*/
        $tableTPositions = "tpositions";
        array_push($arrayTpositions, $tableTPositions);
    //}

//    print_r($arrayTpositions);

    return $arrayTpositions;
}
function getAllPositionsTpositions($p,$ordre){
    $arrayTpositions =  array();

    $tableTPositions = "tpositions";
    $tableTPositions2 = "tpositions";

    $dateNow = strtotime(date('Y-m-d H:i:s'));
    $yearNow = intval(date('Y'));
    $monthNow = intval(date('m'));

    $dateChosen = strtotime($p);

    /*$secsDifference = $dateNow - $dateChosen;
    $monthDifference = round($secsDifference / (60*60*24*7*4)) ;
    $dayDifference = round($secsDifference / (60*60*24)) ;
*/
    /*if($dayDifference >= 90){
        $yearChosen = $p[0]."".$p[1]."".$p[2]."".$p[3];
        $monthChosen = $p[5]."".$p[6];
//		if(intval($yearChosen) < $yearNow){
//			$tableTPositions = "tpositions".$yearChosen."12";
//		}else{
        if(intval($yearChosen) <= 2014){
            $tableTPositions = "tpositions201412";
            $tableTPositions2 = "tpositions201503";
        }else{
            if( (intval($monthChosen) <= 3)){
                $tableTPositions = "tpositions".($yearChosen)."03";
                if($ordre == "ASC"){
                    $tableTPositions2 = "tpositions".($yearChosen)."06";
                    if($monthDifference == 3) $tableTPositions2 = "tpositions";
                }
                if($ordre == "DESC") $tableTPositions2 = "tpositions".(intval($yearChosen)-1)."12";
            }
            if( (intval($monthChosen) > 3) && (intval($monthChosen) <= 6)){
                $tableTPositions = "tpositions".($yearChosen)."06";
                if($ordre == "ASC"){
                    $tableTPositions2 = "tpositions".($yearChosen)."09";
                    if($monthDifference == 3) $tableTPositions2 = "tpositions";
                }
                if($ordre == "DESC") $tableTPositions2 = "tpositions".($yearChosen)."03";
            }
            if( (intval($monthChosen) > 6) && (intval($monthChosen) <= 9)){
                $tableTPositions = "tpositions".($yearChosen)."09";
                if($ordre == "ASC"){
                    $tableTPositions2 = "tpositions".($yearChosen)."12";
                    if($monthDifference == 3) $tableTPositions2 = "tpositions";
                }
                if($ordre == "DESC") $tableTPositions2 = "tpositions".($yearChosen)."06";
            }
            if( (intval($monthChosen) > 9) && (intval($monthChosen) <= 12)){
                $tableTPositions = "tpositions".($yearChosen)."12";
                if($ordre == "ASC"){
                    $tableTPositions2 = "tpositions".(intval($yearChosen)+1)."03";
                    if($monthDifference == 3) $tableTPositions2 = "tpositions";
                }
                if($ordre == "DESC") $tableTPositions2 = "tpositions".($yearChosen)."09";
            }
        }
        $tableTPositions = "tpositions";
        if($ordre == "ASC") $tableTPositions2 = "tpositions";
        if($ordre == "DESC"){
            $modulo = $monthNow % 3;
            $monthSub = ($monthNow) - $modulo;
            if($monthSub == $monthNow) $monthSub = $monthSub - 3;
            if ($monthSub == 00) {
                $yearNow = intval($yearNow) - 1;
                $monthSub = 12;
            }
            $monthNow = sprintf("%02d", $monthSub);

            $tableTPositions2 = "tpositions" . ($yearNow) . "" . $monthNow;
        }
    }else{*/
        $tableTPositions = "tpositions";
        if($ordre == "ASC") $tableTPositions2 = "tpositions";
        if($ordre == "DESC"){
            $modulo = $monthNow % 3;
            $monthSub = ($monthNow) - $modulo;
            if($monthSub == $monthNow) $monthSub = $monthSub - 3;
            if ($monthSub == 00) {
                $yearNow = intval($yearNow) - 1;
                $monthSub = 12;
            }
            $monthNow = sprintf("%02d", $monthSub);

            //$tableTPositions2 = "tpositions" . ($yearNow) . "" . $monthNow;
        }
   // }
    array_push($arrayTpositions, $tableTPositions);
//    if (!in_array($tableTPositions2, $arrayTpositions))   array_push($arrayTpositions, $tableTPositions2);

    return $arrayTpositions;
}
//print_r(getAllPeriodTpositions($_GET["d"],$_GET["f"]));
//print_r(getAllPositionsTpositions($_GET["p"],$_GET["ordre"]));