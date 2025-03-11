<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 23/02/2015
 * Time: 15:23
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

    $date = date("Y-m-d");
    $heure = date("H")+2;
    $heures = date($heure.":i:s");
    $dateTime = $date." 00:00:00";

    $localDateTime = new DateTime(null, new DateTimezone('Europe/Berlin'));
    // echo $localDateTime->format('Y-m-d H:i:s');
//echo $_SESSION['language'];
?>
<div class="panel-body" style="min-height: 880px">
<form id="formRapportTemps" name="formRapportTemps" action="rapportexceltemps.php" method="post" onsubmit="rapportTempsOnSubmit()" target="_blank" >

        <table class="table table-borderless">
            <tr><td colspan="4" style="text-align: center"> <h2><?php echo _('rapport_rapportinstant'); ?></h2></td></tr>
            <tr>
                <td colspan="4">
                    <div class="panel panel-default">
                        <div id="panelbody_rapportinstant" class="panel-body " style="padding: 10px 0 10px 15px; min-height:150px">

                            <table class="table table-borderless " bgcolor="#00FF00" >
                                <tr id="tr_debut_rapport" style="display:none">
                                    <td  colspan="4" style="padding-left: 25px">
<!--                                            <div class="form-group">-->
<!--                                                <div class="col-xs-3" style="text-align: right; margin-top: 2px;">-->
                                                <label for="debutRapport" style="width:100px"><?php echo _('debut'); ?> &nbsp;</label>
                                        <div id="du_div_jour" style="display: none;" ><br/>(<?php echo _('jour'); ?> +1)</div>&nbsp;</label>
<!--                                                </div>-->
<!--                                                <div class="col-xs-7">-->
                                                    <input name="debutRapport" id="debutRapport" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time"
                                                       onchange="enleverBoutonOuvrir1();resetSelectPeriode()" <?php echo "value='".$dateTime."' "; ?> />
<!--                                                </div>-->
<!--                                            </div>-->

                                    </td>
                                    <td colspan="4"  style="padding-left: 50px">

<!--                                            <div class="form-group">-->
<!--                                                <div class="col-xs-3" style="text-align: right; margin-top: 2px;">-->
                                                <label for="finRapport" style="width:100px" ><?php echo _('fin'); ?></label>
                                        <div id="au_div_jour" style="display: none;" ><br/>(<?php echo _('jour'); ?> +1)</div>&nbsp;</label>
<!--                                                </div>-->
<!--                                                <div class="col-xs-7">-->
                                                <input name="finRapport" id="finRapport" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time"
                                                       onchange="enleverBoutonOuvrir1();resetSelectPeriode()" <?php echo "value='".$localDateTime->format('Y-m-d H:i:s')."' "; ?> />
<!--                                                </div>-->
<!--                                            </div>-->

                                    </td>
                                </tr>
                                <tr  id="tr_fin_rapport" style="display:none;">
                                    <td  colspan="8" style="text-align: center; padding-top: 0px">
<!--                                        <div class=" col-xs-2 col-xs-offset-5" style=" padding: 0px; width:100px">-->
                                        <select id="selectPeriode" class="geo3x_input_datetime"  onChange="selectRapport(this.value)">
                                            <option value="aucun" disabled><?php echo _('aucune'); ?></option>
                                            <option value="aujourdhui"><?php echo _('aujourdhui'); ?></option>
                                            <option value="hier"><?php echo _('hier'); ?></option>
                                            <option value="semaine"><?php echo _('semaineflottante'); ?></option>
                                            <option value="mois"><?php echo _('moisflottant'); ?></option>
                                            <!-- <option value="annee">--><?php //echo _('anneeflottante'); ?><!--</option>-->
                                        </select>
<!--                                        </div>-->
                                    </td>
                                </tr>
                                </table>
                            <table class="table table-borderless" style="display:none;">
                                <tr id="tr_calculkm" style="display :none;">
                                    <td colspan="8" style="text-align: center" ><i>
<!--                                            <div class="col-xs-8">-->
                                            <?php echo _('rapport_saisirlitreintervalle'); ?> &nbsp;&nbsp;
<!--                                            </div>-->
<!--                                            <div class="col-xs-3">-->

                                            <input type="number"   name="carburant" id="carburant" value="0" min="0"  style=" text-align:center" class="geo3x_input_text "/>
                                            <input id="calcul100km" class="btn btn-default btn-xs " type="button" onClick="calculpar100Km()" value="<?php echo _('rapport_convertir'); ?>">
                                            </div>
                                    </td>
                                </tr>

                                <tr id="tr_affichecarburant">
                                    <td colspan="8" style="text-align: center" >
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                            <label for="carburant100km" class="col-xs-5 control-label">
                                                <?php echo _('rapport_litredecarburants100km'); ?>
                                            </label>
                                            <div id="div_carburant100km" class="col-xs-3">
                                                <input readonly step="any" min="0" type="number"
                                                       name="carburant100km" id="carburant100km"  class="form-control input-xs" onchange="fn_do(this)" value="0"  style=" text-align:center"/>
                                            </div>
                                            <div id="div_selectCarburant" class="col-xs-4">
                                                <select id="selectCarburant" class="form-control input-xs" disabled >
                                                    <option value="1">Essence</option>
                                                    <option value="2"><?php echo _('rapport_gazole'); ?></option>
                                                    <option value="3">GPL</option>
                                                    <option value="4"><?php echo _('rapport_gaznaturel'); ?></option>
                                                    <!--                                            <option value="annee">Ann&eacute;e</option>-->
                                                </select>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <br/>
                                            <div class="col-xs-6">
                                                <input style="display:none" id="input_save_carburant" class="btn btn-default btn-xs dropdown-toggle" type="button" onClick="saveCarburant()" value="<?php echo _('rapport_enregistrer_carburant'); ?>">&nbsp;
                                            </div>
                                            <div class="col-xs-6">

                                                <input style="display:none" id="input_retour_carburant" class="btn btn-default btn-xs dropdown-toggle" type="button" onClick="retourCarburant()" value="<?php echo _('rapport_retour'); ?>">&nbsp;
                                            </div>
                                        </div>
                                    </td>
                                </tr>



                                <tr  id="tr_boutoncarburant"  >
                                    <td colspan="8" style="text-align: center" >
                                        <div class="col-xs-6" >
                                        <input type="button"   name="" id="" value="<?php echo _('rapport_modifierinfoscarburants'); ?>"
                                               style=" text-align:center; " onClick="buttonModifierCarburant()" class="btn btn-default btn-xs dropdown-toggle"/>
                                        </div>
                                            <div class="col-xs-6">
<!--                                    </td>-->
<!--                                    <td colspan="4" style="padding: 10px; text-align: center" >-->
                                        <input type="button"   name="" id="" value="<?php echo _('rapport_supprimerinfoscarburants'); ?>"
                                               style=" text-align:center; " onClick="deleteCarburant()" class="btn btn-default btn-xs dropdown-toggle"/>
                                            </div>
                                    </td>
                                </tr>
                            </table>

                            <div class="settings">
                                    <div data-role="fieldcontain" style="display:none">
                                        <label for="language">Language</label>
                                        <select name="language" id="language">
                                            <option value="">English</option>
                                            <option value="de">Deutsch</option>
                                            <option value="es">Espa�ol</option>
                                            <option value="fr" selected>Fran�ais</option>
                                            <option value="hu">Magyar</option>
                                            <option value="it">Italiano</option>
                                        </select>
                                    </div>
                                    <div data-role="fieldcontain" style="display:none">
                                        <label for="demo">Demo</label>
                                        <select name="demo" id="demo">
                                            <option value="date">Date</option>
                                            <option value="datetime"selected>Datetime</option>
                                            <option value="time" >Time</option>
                                        </select>
                                    </div>
                                </div>

                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <b>&nbsp;1) <?php echo _('rapport_choisirformat'); ?>:
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center">
                    <input id="rapport_pdf" class="btn btn-default btn-xs dropdown-toggle" type="button" onClick="changeFormatRapport('pdf')" value="PDF">&nbsp;

                </td>
                <td colspan="2" style="text-align: center">
                    <input id="rapport_excel" class="btn btn-default btn-xs dropdown-toggle" type="button" onClick="changeFormatRapport('excel')" value="Excel">&nbsp;
                </td>
<!--                <td  style="text-align: center">-->
<!--                    <input id="rapport_en_ligne" class="btn btn-default btn-xs dropdown-toggle" type="button" onClick="changeFormatRapport('html')" value="En Ligne">&nbsp;-->
<!--                </td>-->
            </tr>
            <tr style="display:none">
                <td><input type="text" name="nomBaliseRapport" id="nomBaliseRapport" value="test"  style="display:none"/></td>
                <td><input type="text" name="idBaliseRapport" id="idBaliseRapport" value="" style="display:none"/></td>
                <td><input type="text" name="timezone" id="timezone" value="" style="display:none"/></td>
                <td><input type="text" name="nomDatabaseGpwRapport" id="nomDatabaseGpwRapport" value="" style="display:none"/></td>
                <td><input type="text" name="ipDatabaseGpwRapport" id="ipDatabaseGpwRapport" value="" style="display:none"/></td>
                <td><input type="text" name="carburantRapport" id="carburantRapport" value="" style="display:none"/></td>
                <td><input type="text" name="carburant100KmRapport" id="carburant100KmRapport" value="" style="display:none"/></td>
                <td><input type="text" name="typeCarburantRapport" id="typeCarburantRapport" value="" style="display:none"/></td>
            </tr>
            <tr id="rapport_2_title" style="display:none">
                <td colspan="3">
                    <b> <br/>&nbsp;2) <?php echo _('rapport_contenurapport'); ?>:
                </td>
            </tr>
            <tr id="rapport_2_content" style="display:none">
                <td style="text-align: center"> <div class="checkbox"><label><input type="CHECKBOX" name="etapeCheckbox" id="etapeCheckbox" onClick="enleverBoutonOuvrir1('etape')" > <?php echo _('rapport_etape'); ?></label></div></td>
                <td style="text-align: center"> <div class="checkbox"><label><input type="CHECKBOX" name="stopCheckbox"	 id="stopCheckbox" onClick="enleverBoutonOuvrir1('stop')" > Stop </label></div></td>
                <td style="text-align: center"> <div class="checkbox"><label id = "checkboxgraf"><input type="CHECKBOX" name="graphCheckbox" id="graphCheckbox" onClick="enleverBoutonOuvrir1('vitesse')" > &nbsp;<?php echo _('rapport_graphevitesse'); ?></label></div></td>
                <td style="text-align: center"><div class="checkbox"><label><input type="CHECKBOX" name="checkbox_address" id="checkbox_address" onClick="enleverBoutonOuvrir1('address')"> <?php echo _('adresse'); ?> <a href="#" data-toggle="modal" data-target="#info_rapport_address"><i class="fa fa-info-circle info"></i></a> </label></div></td>
            </tr>
            
            <script type="text/javascript">

</script>
            <tr id="rapport_3_title" style="display:none">
                <td colspan="3">
                    <b> <br/>&nbsp;3) <?php echo _('rapport_generersurintervalle'); ?>:  <a href="#" data-toggle="modal" data-target="#infoTemps"><i class="fa fa-info-circle info"></i></a>
                </td>
            <tr id="rapport_3_content" style="display:none">
                <td colspan="2" style="text-align: center"><input type="button" class="btn btn-default btn-xs dropdown-toggle" onClick="javascript:genererRapport();" value="<?php echo _('rapport_genererrapport'); ?>" /></td>
                <td colspan="2" style="text-align: center"><div id="genererRapport"></div></td>
            </tr>
        </table>
</form>
        <div id="bodyRapportEtape"></div>
<!--        <center></center>-->

        <div class="col-lg-6">
            <table >


            </table>

<!--            <table >-->
<!---->
<!--                <tr><td> <input type="CHECKBOX" name="etapeCheckbox" id="etapeCheckbox" onClick="enleverBoutonOuvrir1()" checked></td><td> Etapes</td> </tr>-->
<!--                <tr><td> <input type="CHECKBOX" name="stopCheckbox"	 id="stopCheckbox" onClick="enleverBoutonOuvrir1()" checked> </td><td> Stop</td></tr> <br>-->
<!--                <tr><td> <input type="CHECKBOX" name="graphCheckbox" id="graphCheckbox" onClick="enleverBoutonOuvrir1()" checked></td> <td> &nbsp;Graphe Vitesse</td></tr>-->
<!--            </table>-->
            <br>


        </div>

<div class="col-lg-6">
    <br>




</div>
    <div class="modal fade" id="info_rapport_address" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <br><br><br><br>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p><b><?php echo _('rapport_modaltitre_rapportadresse'); ?>: </b>
                        <br>
                        <?php echo _('rapport_modalcontenu1_rapportadresse'); ?>
                        <br><br>
                        <?php echo _('remarques'); ?>: <br>
                        <?php echo _('rapport_modalremarque1_rapportadresse'); ?> <br/>

                        <?php echo _('rapport_modalremarque2_rapportadresse'); ?>
                </div>
            </div>
        </div>
    </div>
<!-- Modal -->
<div class="modal fade" id="infoTemps" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <br><br><br><br>
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <p><b><?php echo _('rapport_modaltitre_genererrapport'); ?> : </b>
                    <br>
                    <?php echo _('rapport_modalcontenu1_genererrapport'); ?>
                    <br><br>
                    <?php echo _('remarques'); ?>: <br>
                    <?php echo _('rapport_modalremarque1_genererrapport'); ?>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="infoEtape" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <br><br><br><br>
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <b><?php echo _('rapport_modaltitre_genererrapportetape'); ?>:</b> <br>
                <?php echo _('rapport_modalcontenu1_genererrapportetape'); ?>
            </div>

        </div>
    </div>
</div>

    <?php ?>
</div> 			<!-- FIN Panel Rapport BODY -->


<script>
    document.getElementById("language").value = "<?php if( (substr($_SESSION['language'],-2) == "US")) echo ""; else echo "fr";?>";
    var myTimeFormat = "HH:ii:ss";

//
//    $('#carburant').on('input', function() {
//        calculpar100Km();
//    });

    //Aujourdhui
    var notreDateDebut = new Date();
    var notreMoisDebut = notreDateDebut.getMonth()+1;
    debutPeriode = 	notreDateDebut.getFullYear() + "-" + ((notreMoisDebut < 10)?"0":"") + notreMoisDebut+ "-" + ((notreDateDebut.getDate() < 10)?"0":"") + notreDateDebut.getDate() + " 00:00:00";

    var notreDateFin = new Date();
    var notreMoisFin = notreDateFin.getMonth()+1;
    finPeriode 	= 	notreDateFin.getFullYear() + "-" + ((notreMoisFin < 10)?"0":"") + notreMoisFin+ "-" + ((notreDateFin.getDate() < 10)?"0":"") + notreDateFin.getDate() + " "
    + 	((notreDateFin.getHours() < 10)?"0":"") + notreDateFin.getHours() + ":" + ((notreDateFin.getMinutes() < 10)?"0":"") +  notreDateFin.getMinutes() + ":" + ((notreDateFin.getSeconds() < 10)?"0":"") + notreDateFin.getSeconds();

    if(document.getElementById("language").value == ""){
        myTimeFormat = "hh:ii:ss A";
        debutPeriode = formatDateAMPM(debutPeriode);
        finPeriode = formatDateAMPM(finPeriode);
    }

    document.getElementById("debutRapport").value = debutPeriode;
    document.getElementById("finRapport").value = finPeriode;

    function replaceStr(str, pos, value){
        var arr = str.split('');
        arr[pos]=value;
        return arr.join('');
    }
    function selectRapport(value){

        var debutPeriode = document.getElementById("debutRapport").value;
        var finPeriode = document.getElementById("finRapport").value;
        //Aujourdhui
        var notreDateDebut = new Date();
        var notreMoisDebut = notreDateDebut.getMonth()+1;
        debutPeriode = 	notreDateDebut.getFullYear() + "-" + ((notreMoisDebut < 10)?"0":"") + notreMoisDebut+ "-" + ((notreDateDebut.getDate() < 10)?"0":"") + notreDateDebut.getDate() + " 00:00:00";

        var notreDateFin = new Date();
        var notreMoisFin = notreDateFin.getMonth()+1;
        finPeriode 	= 	notreDateFin.getFullYear() + "-" + ((notreMoisFin < 10)?"0":"") + notreMoisFin+ "-" + ((notreDateFin.getDate() < 10)?"0":"") + notreDateFin.getDate() + " "
        + 	((notreDateFin.getHours() < 10)?"0":"") + notreDateFin.getHours() + ":" + ((notreDateFin.getMinutes() < 10)?"0":"") +  notreDateFin.getMinutes() + ":" + ((notreDateFin.getSeconds() < 10)?"0":"") + notreDateFin.getSeconds();

        switch(value){
            /*****************************************************************************************/
            case "hier":
                var jourHierDebutPeriode;
                var moisHierDebutPeriode;
                var anneeHierDebutPeriode;
                // alert((debutPeriode[8]+""+debutPeriode[9]-1));
                //Si hier on estle mois d'avant
                if((debutPeriode[8]+""+debutPeriode[9]-1) == ("0" || "00") ){
                    moisHierDebutPeriode = (((debutPeriode[5]+""+debutPeriode[6]-1)<10)?"0":"") + (debutPeriode[5]+""+debutPeriode[6]-1);

                    debutPeriode = replaceStr(debutPeriode,5,moisHierDebutPeriode[0]);
                    debutPeriode = replaceStr(debutPeriode,6,moisHierDebutPeriode[1]);
                    //Si le mois d'avant on est  � l'ann�e pr�c�dente
                    if(moisHierDebutPeriode == ( "0" || "00" )){
                        anneeHierDebutPeriode = debutPeriode[0]+""+debutPeriode[1]+""+(debutPeriode[2]+""+debutPeriode[3] -1) ;
                        debutPeriode = replaceStr(debutPeriode,0,anneeHierDebutPeriode[0]);
                        debutPeriode = replaceStr(debutPeriode,1,anneeHierDebutPeriode[1]);
                        debutPeriode = replaceStr(debutPeriode,2,anneeHierDebutPeriode[2]);
                        debutPeriode = replaceStr(debutPeriode,3,anneeHierDebutPeriode[3]);
                        //Si le mois d'avant est impaire
                    }else if(moisHierDebutPeriode == ( "01" || "03" || "05" || "07" || "08" || "10" || "12" )){
                        jourHierDebutPeriode = "31";
                        debutPeriode = replaceStr(debutPeriode,8,jourHierDebutPeriode[0]);
                        debutPeriode = replaceStr(debutPeriode,9,jourHierDebutPeriode[1]);
                        //Si le mois d'avant est paire
                    }else if(moisHierDebutPeriode == ( "04" || "06" || "09" || "11" )){
                        jourHierDebutPeriode = "31";
                        debutPeriode = replaceStr(debutPeriode,8,jourHierDebutPeriode[0]);
                        debutPeriode = replaceStr(debutPeriode,9,jourHierDebutPeriode[1]);
                        //Si le mois d'avant on est en F�vrier
                    }else if(moisHierDebutPeriode == "02"){
                        jourHierDebutPeriode ="29";
                        debutPeriode = replaceStr(debutPeriode,8,jourHierDebutPeriode[0]);
                        debutPeriode = replaceStr(debutPeriode,9,jourHierDebutPeriode[1]);
                    }
                }else{
                    //Sinon (si on n'est pas au mois d'avant)
                    jourHierDebutPeriode = (((debutPeriode[8]+""+debutPeriode[9]-1)<10)?"0":"") + (debutPeriode[8]+""+debutPeriode[9]-1);
                    debutPeriode = replaceStr(debutPeriode,8,jourHierDebutPeriode[0]);
                    debutPeriode = replaceStr(debutPeriode,9,jourHierDebutPeriode[1]);
                }

                var jourHierFinPeriode = (((finPeriode[8]+""+finPeriode[9]-1)<10)?"0":"") + (finPeriode[8]+""+finPeriode[9]-1);
                finPeriode = replaceStr(finPeriode,8,jourHierFinPeriode[0]);
                finPeriode = replaceStr(finPeriode,9,jourHierFinPeriode[1]);

                var heureHierFinPeriode = "23";
                finPeriode = replaceStr(finPeriode,11,heureHierFinPeriode[0]);
                finPeriode = replaceStr(finPeriode,12,heureHierFinPeriode[1]);

                var minuteHierFinPeriode = "59";
                finPeriode = replaceStr(finPeriode,14,minuteHierFinPeriode[0]);
                finPeriode = replaceStr(finPeriode,15,minuteHierFinPeriode[1]);

                var secondeHierFinPeriode = "59";
                finPeriode = replaceStr(finPeriode,17,secondeHierFinPeriode[0]);
                finPeriode = replaceStr(finPeriode,18,secondeHierFinPeriode[1]);
                break;

            /*****************************************************************************************/
            case "semaine":
                var jourSemaineDebutPeriode;
                var moisSemaineDebutPeriode;
                var anneeSemaineDebutPeriode;

                //Si en semaine flottante on est le mois d'avant
                if((debutPeriode[8]+""+debutPeriode[9]-7) <= ("0" || "00") ){

                    moisSemaineDebutPeriode = (((debutPeriode[5]+""+debutPeriode[6]-1)<10)?"0":"") + (debutPeriode[5]+""+debutPeriode[6]-1);
                    debutPeriode = replaceStr(debutPeriode,5,moisSemaineDebutPeriode[0]);
                    debutPeriode = replaceStr(debutPeriode,6,moisSemaineDebutPeriode[1]);
                    //Si le mois d'avant on est  � l'ann�e pr�c�dente
                    if(moisSemaineDebutPeriode == ( "0" || "00" )){
                        anneeSemaineDebutPeriode = debutPeriode[0]+""+debutPeriode[1]+""+(debutPeriode[2]+""+debutPeriode[3] -1) ;
                        debutPeriode = replaceStr(debutPeriode,0,anneeSemaineDebutPeriode[0]);
                        debutPeriode = replaceStr(debutPeriode,1,anneeSemaineDebutPeriode[1]);
                        debutPeriode = replaceStr(debutPeriode,2,anneeSemaineDebutPeriode[2]);
                        debutPeriode = replaceStr(debutPeriode,3,anneeSemaineDebutPeriode[3]);

                        // moisSemaineDebutPeriode = "12";
                        debutPeriode = replaceStr(debutPeriode,5,moisSemaineDebutPeriode[0]);
                        debutPeriode = replaceStr(debutPeriode,6,moisSemaineDebutPeriode[1]);

                        //Si le mois d'avant est impaire
                    }else if( moisSemaineDebutPeriode ==  "01" || "03" || "05" || "07" || "08" || "10" || "12" ){
                        jourSemaineDebutPeriode = 31+ parseInt(debutPeriode[8]+""+debutPeriode[9]-7);
                        jourSemaineDebutPeriode = jourSemaineDebutPeriode+"";
                        debutPeriode = replaceStr(debutPeriode,8,jourSemaineDebutPeriode[0]);
                        debutPeriode = replaceStr(debutPeriode,9,jourSemaineDebutPeriode[1]);
                        //Si le mois d'avant est paire
                    }else if(moisSemaineDebutPeriode ==  "04" || "06" || "09" || "11" ){
                        jourSemaineDebutPeriode = 30 + parseInt(debutPeriode[8]+""+debutPeriode[9]-7);
                        jourSemaineDebutPeriode = jourSemaineDebutPeriode+"";
                        debutPeriode = replaceStr(debutPeriode,8,jourSemaineDebutPeriode[0]);
                        debutPeriode = replaceStr(debutPeriode,9,jourSemaineDebutPeriode[1]);
                        //Si le mois d'avant on est en F�vrier
                    }else if(moisSemaineDebutPeriode == "02"){
                        jourSemaineDebutPeriode = 28+ parseInt(debutPeriode[8]+""+debutPeriode[9]-7);
                        jourSemaineDebutPeriode = jourSemaineDebutPeriode+"";
                        debutPeriode = replaceStr(debutPeriode,8,jourSemaineDebutPeriode[0]);
                        debutPeriode = replaceStr(debutPeriode,9,jourSemaineDebutPeriode[1]);
                    }
                }else{
                    //Sinon (si on n'est pas au mois d'avant)
                    jourSemaineDebutPeriode = (((debutPeriode[8]+""+debutPeriode[9]-7)<10)?"0":"") + (debutPeriode[8]+""+debutPeriode[9]-7);
                    debutPeriode = replaceStr(debutPeriode,8,jourSemaineDebutPeriode[0]);
                    debutPeriode = replaceStr(debutPeriode,9,jourSemaineDebutPeriode[1]);
                }
                break;
            /*****************************************************************************************/
            case "mois":
                //Mois flottant *********************************
                var moisMoisDebutPeriode;
                var anneeMoisDebutPeriode;
                //Si le mois d'avant on est  � l'ann�e pr�c�dente
                if(moisMoisDebutPeriode == ( "0" || "00" )){
                    anneeMoisDebutPeriode = debutPeriode[0]+""+debutPeriode[1]+""+(debutPeriode[2]+""+debutPeriode[3] -1) ;
                    debutPeriode = replaceStr(debutPeriode,0,anneeMoisDebutPeriode[0]);
                    debutPeriode = replaceStr(debutPeriode,1,anneeMoisDebutPeriode[1]);
                    debutPeriode = replaceStr(debutPeriode,2,anneeMoisDebutPeriode[2]);
                    debutPeriode = replaceStr(debutPeriode,3,anneeMoisDebutPeriode[3]);
                    moisMoisDebutPeriode = "12";
                    debutPeriode = replaceStr(debutPeriode,5,moisMoisDebutPeriode[0]);
                    debutPeriode = replaceStr(debutPeriode,6,moisMoisDebutPeriode[1]);
                }else{
                    moisMoisDebutPeriode = (((debutPeriode[5]+""+debutPeriode[6]-1)<10)?"0":"") + (debutPeriode[5]+""+debutPeriode[6]-1);
                    debutPeriode = replaceStr(debutPeriode,5,moisMoisDebutPeriode[0]);
                    debutPeriode = replaceStr(debutPeriode,6,moisMoisDebutPeriode[1]);
                }

                break;
            /*****************************************************************************************/
            case "annee":
                //Mois flottant *********************************
                var anneeAnneeDebutPeriode;
                anneeAnneeDebutPeriode = debutPeriode[0]+""+debutPeriode[1]+""+(debutPeriode[2]+""+debutPeriode[3] -1) ;
                debutPeriode = replaceStr(debutPeriode,0,anneeAnneeDebutPeriode[0]);
                debutPeriode = replaceStr(debutPeriode,1,anneeAnneeDebutPeriode[1]);
                debutPeriode = replaceStr(debutPeriode,2,anneeAnneeDebutPeriode[2]);
                debutPeriode = replaceStr(debutPeriode,3,anneeAnneeDebutPeriode[3]);
                // alert(anneeAnneeDebutPeriode[0]);

                break;
        }
        if(document.getElementById("language").value == ""){
            debutPeriode = formatDateAMPM(debutPeriode);
            finPeriode = formatDateAMPM(finPeriode);
        }
        document.getElementById("debutRapport").value = debutPeriode;
        document.getElementById("finRapport").value = finPeriode;
        $('#demo').trigger('change');
        enleverBoutonOuvrir1();
    }
    $(function () {
        var curr = new Date().getFullYear();
        var opt = {
            'date': {
                preset: 'date',
                dateOrder: 'd Dmmyy',
                invalid: { daysOfWeek: [0, 6], daysOfMonth: ['5/1', '12/24', '12/25'] }
            },
            'datetime': {
                preset: 'datetime',
				minDate: new Date(2019, 1, 1, 0, 0),
				maxDate: new Date(2050, 2, 1, 0, 0),
                stepMinute: 1,
                dateFormat: 'yy-mm-dd',
                timeFormat: myTimeFormat
            },
            'time': {
                preset: 'time'
            }
        }

        $('.settings select').bind('change', function() {
            var demo = $('#demo').val();
            // if (!demo.match(/select/i)) {
            // $('.demo-test-' + demo).val('');
            // }
            $('.demo-test-' + demo).scroller('destroy').scroller($.extend(opt[demo], {
                theme: $('#theme').val(),
                mode: $('#mode').val(),
                lang: $('#language').val(),
                display: $('#display').val(),
                animate: $('#animation').val()
            }));
            $('.demo').hide();
            $('.demo-' + demo).show();

        });
        $('#demo').scroller('setValue', "test", true);
        $('#demo').trigger('change');


    });
//    ('.menu li.effect').on('click', function(){
//        $(this).addClass('active').siblings().removeClass('active');
//    });

</script>