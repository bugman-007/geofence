<?php
    /**
     * Created by PhpStorm.
     * User: NGUYEN Christophe
     * Date: 04/05/2015
     * Time: 12:28
     */

    /*
    * 	Affiche le tableau pour l'historique de periode selon la pagination courante
    * 	Carto:js
    */

set_time_limit(0);

    include '../function.php';

    $q=$_GET["Id_Tracker"];
    $d=$_GET["debut"];
    $f=$_GET["fin"];
    $nomBalise=$_GET["nomBalise"];
    $filtrage=$_GET["filtrage"];
    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $timezone=$_GET["timezone"];

    $dUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($d)),$timezone);
    $fUTC = LocalTimeToGmtTime(date("Y-m-d H:i:s", strtotime($f)),$timezone);
    //PARAMETRE BDD GLOBALE
    $database = $nomDatabaseGpw;  // the name of the database.
    $server = $ipDatabaseGpw;  // server to connect to.
    include '../dbconnect2.php';

    //CONNEXION BDD GLOBALE
    $connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
    if (!$connection) {
        die('Not connected : ' . mysqli_connect_error());
    }
	mysqli_set_charset($connection, "utf8");
    include('../dbtpositions.php');

    $sql = "";
    $arrayTpositions = getAllPeriodTpositions($d,$f);
    $i = 0;
$nombreDePages = 0;
    if (sizeof($arrayTpositions) > 1 ) {
        for ($i = 0; $i < sizeof($arrayTpositions); $i++) {
            $sql .= "SELECT COUNT(*) AS total  FROM $arrayTpositions[$i] WHERE (Pos_DateTime_position BETWEEN '".$dUTC."' AND '".$fUTC."' ) AND (Pos_Id_tracker = '".$q."' )
             ORDER BY Pos_DateTime_position; ";
        }
        if (mysqli_multi_query($connection, $sql)) {
            do {

                if ($retour_total = mysqli_store_result($connection)) {
                    $donnees_total=mysqli_fetch_assoc($retour_total); //On range retour sous la forme d'un tableau.
                    $total=$donnees_total['total']; //On récupère le total pour le placer dans la variable $total.
                    $messagesParPage=1000;
                    //Nous allons maintenant compter le nombre de pages.
//                    $nombreDePages=ceil($total/$messagesParPage);
                    $nombreDePages += ceil($total/$messagesParPage);
                    if(!mysqli_more_results($connection)) echo "NombreDePages:".$nombreDePages;
                    mysqli_free_result($retour_total);
                }
            } while (mysqli_more_results($connection) && mysqli_next_result($connection));
        }else {
            echo "First query failed..." . mysqli_error($mysqli);
        }
    }else{
        $sql = "SELECT COUNT(*) AS total  FROM $arrayTpositions[0] WHERE (Pos_DateTime_position BETWEEN '".$dUTC."' AND '".$fUTC."' ) AND (Pos_Id_tracker = '".$q."' )
             ORDER BY Pos_DateTime_position;";

        $retour_total=mysqli_query($connection,$sql);
        if( $retour_total !== false ) {

            $donnees_total=mysqli_fetch_assoc($retour_total); //On range retour sous la forme d'un tableau.
            $total=$donnees_total['total']; //On récupère le total pour le placer dans la variable $total.
            $messagesParPage=1000;
            //Nous allons maintenant compter le nombre de pages.
            $nombreDePages=ceil($total/$messagesParPage);

            echo "NombreDePages:".$nombreDePages;

            mysqli_free_result($retour_total);
        }
    }
    mysqli_close($connection);

