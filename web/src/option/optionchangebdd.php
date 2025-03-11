<?php
/**
 * Created by PhpStorm.
 * User: Christophe NGUYEN
 * Date: 08/09/2015
 * Time: 15:23
 */

/*
* Changer de BDD
*/
    session_start();
    $selectBdd = $_GET["selectBdd"];
$_SESSION['superviseurIdBd'] = $selectBdd;