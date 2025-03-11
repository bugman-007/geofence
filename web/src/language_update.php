<?php
/**
 * Created by PhpStorm.
 * User: Emachines1
 * Date: 22/09/2015
 * Time: 16:35
 */

session_start();
$_SESSION['language'] = $_GET['geo3x_lang'];
echo $_SESSION['language'];