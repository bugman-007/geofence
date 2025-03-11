<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 05/03/2015
 * Time: 15:45
 */
session_start();

if(isset($_SESSION['CREATED']))
    echo $_SESSION['CREATED'];
else echo "DECONNECTER";