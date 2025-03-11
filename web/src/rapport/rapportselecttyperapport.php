<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 27/02/2015
 * Time: 10:09
 */
session_start();
$_SESSION['CREATED'] = time();
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
    include '../dbgpw.php';
    include '../dbconnect2.php';
    $idTracker=$_GET["idTracker"];
    $nomDatabaseGpw=$_GET["nomDatabaseGpw"];
    $ipDatabaseGpw=$_GET["ipDatabaseGpw"];
    $database = $nomDatabaseGpw;
    $server = $ipDatabaseGpw;

    $connection=mysqli_connect($server, $db_user_2, $db_pass_2,$database);
    $sql="SELECT Freq_envoi FROM trapport  WHERE Id_tracker = '".$idTracker."'AND Freq_envoi != '-1' ORDER BY Freq_envoi ASC";
    $result = mysqli_query($connection,$sql);
    $typeExist = mysqli_num_rows($result);
    if($typeExist) {
        echo '<select id="select_type_rapport" class="geo3x_input_datetime" onChange="selectTypeRapport(this.value);">';
        echo '<option value="nothing" >---</option>';
        while ($row = mysqli_fetch_array($result)) {
            if ($row['Freq_envoi'] == "0") $type = _('rapport_unefois');
            if ($row['Freq_envoi'] == "1") $type = _('rapport_journalier');
            if ($row['Freq_envoi'] == "4") $type = _('rapport_journalierplus');
            if ($row['Freq_envoi'] == "2") $type = _('rapport_hebdomadaire');
            if ($row['Freq_envoi'] == "5") $type = _('rapport_hebdomadaireplus');
            if ($row['Freq_envoi'] == "3") $type = _('rapport_mensuel');
            if ($row['Freq_envoi'] == "6") $type = _('rapport_mensuelplus');
            echo '<option value="' . $row['Freq_envoi'] . '">' . $type . '</option>';
        }
        echo '</select>';
    }else{
        echo "aucun";
    }


    mysqli_close($connection);
?>