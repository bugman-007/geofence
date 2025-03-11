<style type="text/css">
    .sortable td:hover {
        cursor: pointer;
    }
</style>
<?php

/*
* Afficher la liste groupe
*/

/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 01/06/2015
 * Time: 14:33
 */

    include '../dbgpw.php';

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

    $idClient=$_GET["idClient"];
    $nomBase = $_GET["nomBase"];
    $connectGpwBD = mysqli_connect($server, $db_user, $db_pass,$database);
    $queryGpwBD = mysqli_query($connectGpwBD,"SELECT Id_Base FROM gpwbd WHERE NomBase = '$nomBase'");
    $assocGpwBD = mysqli_fetch_assoc($queryGpwBD);
    $idBase = $assocGpwBD['Id_Base'];
    mysqli_free_result($queryGpwBD);
    mysqli_close($connectGpwBD);
    $connection=mysqli_connect($server, $db_user, $db_pass, $database);
    $sql="SELECT * FROM gpw WHERE Id_Client = '$idClient' AND Id_Base = '$idBase'  ORDER BY NomGPW  ";
    $result = mysqli_query($connection,$sql);


    echo '<table id="table_list_groupe" class="sortable table table-bordered table-hover TableManage"><tr><th width="50px">'; echo _('option_idgroupe'); echo '</th><th width="100px">'; echo _('option_nomgroupe'); echo '</th></tr>';
;
    while($row = mysqli_fetch_array($result)){
        echo "<tr onClick='clickTableGroupe(this)' ><td>".$row['Id_GPW']."</td><td>".$row['NomGPW']."</td></tr>";

    }
    mysqli_free_result($result);
    mysqli_close($connection);
?>
</table>