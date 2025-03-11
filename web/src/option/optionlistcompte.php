<style type="text/css">
    .sortable td:hover {
        cursor: pointer;
    }
</style>
<?php

/*
* Afficher la liste compte
*/

/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 01/06/2015
 * Time: 14:33
 */

    include '../dbgpw.php';
    $idClient=$_GET["idClient"];
	$idBase = $_GET["idBase"];
    $connection=mysqli_connect($server, $db_user, $db_pass, $database);
    $sql="SELECT * FROM gpwutilisateur WHERE Id_Client = '$idClient' AND Id_Base = '$idBase' ORDER BY Login";
    $result = mysqli_query($connection,$sql);


    session_start();
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

    echo '<table id="table_list_utilisateur" class="sortable table table-bordered table-hover TableManage"><tr><th width="150px">Login</th><th width="100px">'; echo _('option_duree');
echo '</th><th width="130px">'; echo _('option_finvalidite'); echo '</th><th min-width="80px">'; echo _('option_nom'); echo '</th><th min-width="80px">'; echo _('option_prenom'); echo '</th></tr>';

    $i=0;

    while($row = mysqli_fetch_array($result)){
        $duree = "";
        $finValidite = "";
        switch ($row['Type']) {
            case "0":
                $duree = "illimité";
                break;
            case "1":
                $duree = $row['Duree']." ". _('heure'). "(s)";
                break;
            case "2":
                $duree = $row['Duree']." ". _('jour')."(s)";
                break;
            case "3":
                $duree = $row['Duree']." ". _('semaine')."(s)";
                break;
            case "4":
                $duree = $row['Duree']." ". _('mois')."(s)";
                break;
        }
        if($row['Datefin'] == "0000-00-00 00:00:00") $finValidite = "";
        else $finValidite =  $row['Datefin'] ;
        echo "<tr onClick='clickTableCompte(this)' ><td>".$row['Login']."</td><td>".$duree."</td><td>".$finValidite."</td><td>".$row['Nom']."</td><td>".$row['Prenom']."</td><td style='display:none'>".$row['Duree']."</td><td style='display:none'>".$row['Type']."</td></tr>";

    }
    mysqli_free_result($result);
    mysqli_close($connection);
?>
</table>