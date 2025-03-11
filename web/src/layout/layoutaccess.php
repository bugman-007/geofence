<?php
    /**
     * Created by PhpStorm.
     * User: NGUYEN Christophe
     * Date: 07/05/2015
     * Time: 09:54
     */

    include '../dbgpw.php';

    session_start();

    /************* gpwuser_gpw : Recuperer l'Id_Base, Id_Client  de l'utilisateur *******************/
    $connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
    if (!$connectGpwUser) die('Impossible de se connecter: '.mysqli_connect_error());
    $queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Base, Id_Client FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."') ORDER BY NomGPW"); //AND Id_GPW != 0
    $assocGpwUser = mysqli_fetch_assoc($queryGpwUser);
    $idBaseGpwUser = $assocGpwUser['Id_Base'];
    $idClientGpwUser = $assocGpwUser['Id_Client'];
    mysqli_free_result($queryGpwUser);
    mysqli_close($connectGpwUser);

    /************* gpwutilisateur : Recuperer le Type d'access, la durée d'access, la date fin, id client et id base de l'utilisateur *******************/
    $connectGpwUtilisateur = mysqli_connect($server, $db_user, $db_pass,$database);
    if (!$connectGpwUtilisateur) die('Impossible de se connecter: '.mysqli_connect_error());
    $queryGpwUtilisateur = mysqli_query($connectGpwUtilisateur,"SELECT * FROM gpwutilisateur WHERE (Login = '".$_SESSION['username']."') ");
    $assocGpwUtilisateur = mysqli_fetch_assoc($queryGpwUtilisateur);
    $type = $assocGpwUtilisateur['Type'];
    $duree = $assocGpwUtilisateur['Duree'];
    $dateFin = $assocGpwUtilisateur['Datefin'];
    $idBase =  $assocGpwUtilisateur['Id_Base'];
    $idClient =  $assocGpwUtilisateur['Id_Client'];
    mysqli_free_result($queryGpwUtilisateur);
    mysqli_close($connectGpwUtilisateur);

    if( ($idClient != $idClientGpwUser) || ($idBase != $idBaseGpwUser) ) {
        /************* gpwuser_gpw : Update l'Id_Base, Id_Client  de l'utilisateur *******************/
        $connect = mysqli_connect($server, $db_user, $db_pass, $database);
        if (!$connect) die('Impossible de se connecter: ' . mysqli_connect_error());
        $result = mysqli_query($connect, "UPDATE gpwutilisateur SET Id_Base = '" . $idBaseGpwUser . "', Id_Client = '" . $idClientGpwUser . "' WHERE (Login = '".$_SESSION['username']."' ) ");
        mysqli_close($connect);
    }

    date_default_timezone_set('Europe/Berlin');
    $dateNow = new DateTime();
    $authorization = "no";
    if($type == "0" || $type == "") {
        $authorization = "yes";
    }else{
        switch ($type) {
            case "1":
                $dateNow->add(new DateInterval('PT' . $duree . 'H'));
                break;
            case "2":
                $dateNow->add(new DateInterval('PT' . 24 * $duree . 'H'));
                break;
            case "3":
                $dateNow->add(new DateInterval('PT' . 24 * 7 * $duree . 'H'));
                break;
            case "4":
                $dateNow->add(new DateInterval('PT' . 24 * 7 * 4 * $duree . 'H'));
                break;
        }
        $dateFinTypeDuree = $dateNow->format('Y-m-d H:i:s');
        if ( ($dateFin == "") || ($dateFin == "0000-00-00 00:00:00") ) {
            if ($dateFin < $dateFinTypeDuree) {
                $authorization = "yes";
                if($dateFin != $dateFinTypeDuree) {
                    /************* gpwutilisateur : Remplir le champs Datefin  *******************/
                    $connect = mysqli_connect($server, $db_user, $db_pass, $database);
                    if (!$connect) die('Impossible de se connecter: ' . mysqli_connect_error());
                    $result = mysqli_query($connect, "UPDATE gpwutilisateur SET Datefin = '" . $dateFinTypeDuree . "' WHERE (Login = '".$_SESSION['username']."' ) ");
                    mysqli_close($connect);
                }
            }
        }else{
            if ($dateFin < $dateFinTypeDuree) $authorization = "no";
        }
    }
    echo $authorization."";

?>



