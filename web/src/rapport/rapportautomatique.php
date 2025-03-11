<?php
/**
 * Created by PhpStorm.
 * User: NGUYEN Christophe
 * Date: 23/02/2015
 * Time: 16:10
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
?>
<div class="panel-body" style="height: 880px">
<?php
    $date = date("Y-m-d");
    $heure = date("H")+2;
    $heures = date($heure.":i:s");
    $dateTime = $date." 00:00:00";

    $localDateTime = new DateTime(null, new DateTimezone('Europe/Berlin'));
    // echo $localDateTime->format('Y-m-d H:i:s');

?>
<!--    <br>-->
    <center>
        <table class="table table-borderless">
            <tr><td colspan="4" style="text-align: center"> <h2><?php echo _('rapport_rapportauto'); ?></h2></td></tr>
            <tr>
                <td colspan="4">
                    <div class="panel panel-default">
                        <div class="panel-body" id="panelbody_rapportinstant" style="padding: 10px 0 10px 15px; min-height:150px">
                         <div id="div_une_fois" style="display:none">
                            <table class="table table-borderless">
                            <tr>
                                <td colspan="2"  style="padding-left: 25px">
<!--                                        <div class="form-group">-->
<!--                                            <div class="col-md-3" style="margin-top: 2px;">-->
                                                <label for="test" style="width:100px"><?php echo _('debut'); ?>
                                                <div id="du_div_jour" style="display: none;" >(<?php echo _('jour'); ?> +1)</div>&nbsp;</label>
<!--                                            </div>-->
<!--                                            <div class="col-md-7">-->
                                                <input name="debutRapport" id="debutRapport" class="geo3x_input_datetime  demo-test-date demo-test-datetime demo-test-time"
                                                       onchange="onChangeTextDebut(this.value);resetSelectPeriode()" <?php echo "value='".$dateTime."' "; ?> />
<!--                                            </div>-->
<!--                                        </div>-->
                                <td colspan="2"   style="padding-left: 50px">
<!--                                        <div class="form-group">-->
<!--                                            <div class="col-md-3" style=" margin-top: 2px;">-->
                                                <label for="test" style="width:100px"><?php echo _('fin'); ?>
                                                    <div id="au_div_jour" style="display: none;" ><?php echo _('jour'); ?> +1)</div>&nbsp;</label>
<!--                                            </div>-->
<!--                                            <div class="col-md-7">-->
                                                <input name="finRapport" id="finRapport" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time"
                                                       onchange="onChangeTextFin(this.value);resetSelectPeriode()" <?php echo "value='".$localDateTime->format('Y-m-d H:i:s')."' "; ?> />
<!--                                            </div>-->
<!--                                        </div>-->
                                </td>
                                <tr>
                                    <td colspan=4" style="text-align:center; vertical-align: middle; padding-left: 0px">
<!--                                        <div class=" col-xs-2 col-xs-offset-5" style=" padding: 0px; width:100px">-->
                                            <select id="selectPeriode" class="geo3x_input_datetime" onChange="selectRapport(this.value)">
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
                        </table>
                    </div>
                    <div id="div_hebdomadaire" style=" display:none">
                        <table class="table table-borderless">
                            <tr>
                                <td colspan="2" ><label for="test" style="width:100px"><?php echo _('debut'); ?>
                                        <div id="du_div_semaine" style="display: none;" >(<?php echo _('semaine'); ?>)</div>&nbsp;</label>
                                    <select class="geo3x_input_datetime" id="select_debut_jour_hebdomadaire">
                                        <option value="1" ><?php echo _('lundi'); ?></option>
                                        <option value="2" ><?php echo _('mardi'); ?></option>
                                        <option value="3" ><?php echo _('mercredi'); ?></option>
                                        <option value="4" ><?php echo _('jeudi'); ?></option>
                                        <option value="5" ><?php echo _('vendredi'); ?></option>
                                        <option value="6" ><?php echo _('samedi'); ?></option>
                                        <option value="7" ><?php echo _('dimanche'); ?></option>
                                    </select>
                                </td>

                                <td colspan="2"><label for="test" style="width:100px"><?php echo _('fin'); ?>
                                        <div id="au_div_semaine" style="display: none;" >(<?php echo _('semaine'); ?>+1)</div></label>
                                    <select class="geo3x_input_datetime" id="select_fin_jour_hebdomadaire">
                                        <option value="1" ><?php echo _('lundi'); ?></option>
                                        <option value="2" ><?php echo _('mardi'); ?></option>
                                        <option value="3" ><?php echo _('mercredi'); ?></option>
                                        <option value="4" ><?php echo _('jeudi'); ?></option>
                                        <option value="5" ><?php echo _('vendredi'); ?></option>
                                        <option value="6" ><?php echo _('samedi'); ?></option>
                                        <option value="7" ><?php echo _('dimanche'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr><td colspan="4" style="padding-left:125px"><br/><label for="test"><?php echo _('rapport_jourenvoi'); ?> &nbsp;</label>
                                <select class="geo3x_input_datetime" id="select_jour_envoi_hebdomadaire" onchange="changeJourEnvoiHebdo(this.value)">
                                    <option value="1" ><?php echo _('lundi'); ?></option>
                                    <option value="2" ><?php echo _('mardi'); ?></option>
                                    <option value="3" ><?php echo _('mercredi'); ?></option>
                                    <option value="4" ><?php echo _('jeudi'); ?></option>
                                    <option value="5" ><?php echo _('vendredi'); ?></option>
                                    <option value="6" ><?php echo _('samedi'); ?></option>
                                    <option value="7" ><?php echo _('dimanche'); ?></option>
                                </select>
                            </td>
                        </table>
                    </div>
                    <div id="div_mensuel" style="display:none">
                        <table class="table table-borderless">
                            <tr>
                                <td colspan="2" ><label for="test" style="width:100px"><?php echo _('debut'); ?>  <div id="du_div_mois" style="display: none;" >(<?php echo _('mois'); ?>)</div></label>
                                    <select class="geo3x_input_datetime" id="select_debut_jour_mensuel">
                                        <option value="1" >1</option><option value="2" >2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option>
                                        <option value="6" >6</option><option value="7" >7</option><option value="8" >8</option><option value="9" >9</option><option value="10" >10</option>
                                        <option value="11" >11</option><option value="12" >12</option><option value="13" >13</option><option value="14" >14</option><option value="15" >15</option>
                                        <option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option>
                                        <option value="21" >21</option><option value="22" >22</option><option value="23" >23</option><option value="24" >24</option><option value="25" >25</option>
                                        <option value="26" >26</option><option value="27" >27</option><option value="28" >28</option><option value="29" >29</option><option value="30" >30</option>
                                        <option value="31" >31</option>
                                    </select>
                                </td>

                                <td colspan="2"><label for="test" style="width:100px"><?php echo _('fin'); ?>  <div id="au_div_mois" style="display: none;" >(<?php echo _('mois'); ?>+1)</div></label>
                                    <select class="geo3x_input_datetime" id="select_fin_jour_mensuel">
                                        <option value="1" >1</option><option value="2" >2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option>
                                        <option value="6" >6</option><option value="7" >7</option><option value="8" >8</option><option value="9" >9</option><option value="10" >10</option>
                                        <option value="11" >11</option><option value="12" >12</option><option value="13" >13</option><option value="14" >14</option><option value="15" >15</option>
                                        <option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option>
                                        <option value="21" >21</option><option value="22" >22</option><option value="23" >23</option><option value="24" >24</option><option value="25" >25</option>
                                        <option value="26" >26</option><option value="27" >27</option><option value="28" >28</option><option value="29" >29</option><option value="30" >30</option>
                                        <option value="31" >31</option>
                                    </select>
                                </td>
                            </tr>
                            <tr><td colspan="4" style="padding-left:125px"><br/><label for="test"><?php echo _('rapport_jourenvoi'); ?> &nbsp;</label>
                                    <select class="geo3x_input_datetime" id="select_jour_envoi_mensuel"  onchange="changeJourEnvoiMensuel(this.value)">
                                        <option value="1" >1</option><option value="2" >2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option>
                                        <option value="6" >6</option><option value="7" >7</option><option value="8" >8</option><option value="9" >9</option><option value="10" >10</option>
                                        <option value="11" >11</option><option value="12" >12</option><option value="13" >13</option><option value="14" >14</option><option value="15" >15</option>
                                        <option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option>
                                        <option value="21" >21</option><option value="22" >22</option><option value="23" >23</option><option value="24" >24</option><option value="25" >25</option>
                                        <option value="26" >26</option><option value="27" >27</option><option value="28" >28</option><option value="29" >29</option><option value="30" >30</option>
                                        <option value="31" >31</option>
                                    </select>
                                </td></tr>
                        </table>
                    </div>
                            <table class="table table-borderless" style="display:none;">
                                <tr id="tr_calculkm" style="display :none">
                                    <td colspan="4" style=" text-align: center" ><i>
<!--                                            <div class="col-xs-8">-->
                                                <?php echo _('rapport_saisirlitreintervalle'); ?> &nbsp;&nbsp;
<!--                                            </div>-->
<!--                                            <div class="col-xs-3">-->

                                                <input type="number"   name="carburant" id="carburant" value="0" min="0"  style=" text-align:center" class="geo3x_input_text "/>
                                            <input id="calcul100km" class="btn btn-default btn-xs " type="button" onClick="calculpar100Km()" value="<?php echo _('rapport_convertir'); ?>">&nbsp;
<!--                                            </div>-->
                                    </td>

                                </tr>

                                <tr id="tr_affichecarburant" >

                                    <td colspan="4" style=" text-align: center" >
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
                                    <td colspan="4" style="text-align: center" >
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
                </td>
            </tr>

            <tr>
                <td colspan="2" style="text-align: center">
                    <input id="input_new_rapport" class="btn btn-default btn-xs dropdown-toggle" type="button" onClick="addNewTypeRapport()" value="<?php echo _('rapport_creernouveau'); ?>">&nbsp;

                </td>
                <td colspan="2" style="text-align: center">
                    <input id="input_update_rapport" class="btn btn-default btn-xs dropdown-toggle" type="button" onClick="listTypeRapport();showListMail()" value="<?php echo _('rapport_modifierrapport'); ?>">&nbsp;
                </td>
            </tr>
            <tr>

                    <td> <div class="checkbox"><label><input type="CHECKBOX" name="checkbox_address" id="checkbox_address">  <?php echo _('adresse'); ?>  <a href="#" data-toggle="modal" data-target="#info_rapport_address"><i class="fa fa-info-circle info"></i></a></label><div></td>
                    <td><div class="checkbox"><label><input type="CHECKBOX" name="etapeCheckbox" id="etapeCheckbox"> <?php echo _('rapport_etape'); ?></label><div></td>
                    <td><div class="checkbox"><label><input type="CHECKBOX" name="stopCheckbox"	 id="stopCheckbox"> Stop</label><div></td>
                    <td><div class="checkbox"><label><input type="CHECKBOX" name="graphCheckbox" id="graphCheckbox" ><?php echo _('rapport_graphevitesse'); ?></label><div></td>

            </tr>
            <tr id="tr_1">
                <td colspan="2">
                    <b> <br/>&nbsp;1) <?php echo _('rapport_choisirtype'); ?>:
                </td>
                <td colspan="2"> <br/>Type: &nbsp; <a href="#" data-toggle="modal" data-target="#info_rapport_automatique_type">?</a>
                    <div id="div_select_type_rapport" style="display:inline">
                        <select id="select_type_rapport" class="geo3x_input_datetime"onChange="selectTypeRapport(this.value);" >
                            <option value="nothing" >---</option>
<!--                            <option value="unefois" >Une fois</option>-->
<!--                            <option value="journalier" >Journalier</option>-->
<!--                            <option value="hebdomadaire" >Hebdomadaire</option>-->
<!--                            <option value="mensuel" >Mensuel</option>-->
<!--                            <option value="journalier+" >Journalier+</option>-->
<!--                            <option value="hebdomadaire+" >Hebdomadaire+</option>-->
<!--                            <option value="mensuel+" >Mensuel+</option>-->
                        </select>
                    </div>
                    <div class="modal fade" id="info_rapport_automatique_type" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <br><br><br><br>
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <p><b><?php echo _('rapport_modaltitre_rapportauto'); ?> : Type</b></p>

                                    <?php echo _('rapport_modalcontenu1_rapportauto'); ?>
                                    <br/>
                                    -  <?php echo _('rapport_modalcontenu2_rapportauto'); ?>
                                    <br/>
                                    -  <?php echo _('rapport_modalcontenu3_rapportauto'); ?>
                                    <br/>
                                    - <?php echo _('rapport_modalcontenu4_rapportauto'); ?>
                                    <br/>
                                    - <?php echo _('rapport_modalcontenu5_rapportauto'); ?>
                                    <br/> <br/>
                                    <?php echo _('remarques'); ?>:
                                    <br/> <br/>
                                    <?php echo _('rapport_modalremarque1_rapportauto'); ?>
                                    <br/> <br/>
                                    <?php echo _('rapport_modalremarque2_rapportauto'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td id="tr_2_1" colspan="4"><br/>
                    <b>&nbsp;2) <?php echo _('rapport_objetmessage'); ?> (Mail)
                </td>
            </tr>
            <tr id="tr_2_2">
                <td colspan="2" ><?php echo _('rapport_objet'); ?><br>
                    <input id="objet"  type="text" class="geo3x_input_text" style="width: 100%">
                </td>
                <td colspan="2" >
                    Message: <br> <input id="message" class="geo3x_input_text" style="width: 100%" type="text">
                </td>
            </tr>

            <tr id="tr_3_1">
                <td colspan="3"><br/>
                    <b>&nbsp;3) <?php echo _('rapport_choisirdateheure'); ?> &nbsp; </b><a href="#" data-toggle="modal" data-target="#info_rapport_automatique_prochain_envoi">?</a>
                        <div class="modal fade" id="info_rapport_automatique_prochain_envoi" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <br><br><br><br>
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p><b<?php echo _('rapport_modaltitre_rapportautoprochainenvoi'); ?></b></p>
                                        <?php echo _('rapport_modalcontenu1_rapportautoprochainenvoi'); ?>
                                        <br/>
                                        <?php echo _('rapport_modalcontenu2_rapportautoprochainenvoi'); ?>
                                        <!--                                        Pour le rapport journalier, hebdomadaire et mensuel, le "prochain envoi" est donné au titre indicatif (non saisissable). Il faut choisir plutôt le "jour d'envoi" à la place (hebdomadaire et mensuel)-->
<!--                                        <br/>-->
<!--                                        <br/>-->
<!--                                        Si rapport est déjà envoyé au moment de la période en cours, la date d'envoi de la période prochaine sera affichée ici.-->
<!--                                        <br/> <br/>-->
<!--                                        Remarques:-->
<!--                                        <br/>-->
<!--                                        La date d'envoi n'est saisissable qu'au cas de rapport type "une fois" de l'année-->

                                    </div>
                                </div>
                            </div>
                        </div>
                </td>
            </tr>
            <tr id="tr_3_2">
                <td colspan="4" style="text-align: center">
                    <label for="test"><?php echo _('rapport_prochainenvoi'); ?>: &nbsp;</label>
                    <input name="prochain_envoi_rapport" id="prochain_envoi_rapport" class="geo3x_input_datetime demo-test-date demo-test-datetime demo-test-time" onchange="onChangeTextEnvoi(this.value)"/>

                </td>
            </tr>
<!--            <div class="settings">-->
<!--                <div data-role="fieldcontain" style="display:none">-->
<!--                    <label for="language">Language</label>-->
<!--                    <select name="language" id="language">-->
<!--                        <option value="">English</option>-->
<!--                        <option value="de">Deutsch</option>-->
<!--                        <option value="es">Espa�ol</option>-->
<!--                        <option value="fr" selected>Fran�ais</option>-->
<!--                        <option value="hu">Magyar</option>-->
<!--                        <option value="it">Italiano</option>-->
<!--                    </select>-->
<!--                </div>-->
<!--                <div data-role="fieldcontain" style="display:none">-->
<!--                    <label for="demo">Demo</label>-->
<!--                    <select name="demo" id="demo">-->
<!--                        <option value="date">Date</option>-->
<!--                        <option value="datetime"selected>Datetime</option>-->
<!--                        <option value="time" >Time</option>-->
<!--                    </select>-->
<!--                </div>-->
<!--            </div>-->

            <tr id="tr_4_1">
                <td colspan="3">
                    <b>&nbsp;4) <?php echo _('rapport_choisirformatauto'); ?>
                </td>
            </tr>
            <tr id="tr_4_2">
                <td> <div class="checkbox"><label><input type= "radio" id="radio_pdf" name="format_rapport" value="pdf"> PDF</label></div></td>
                <td> <div class="checkbox"><label><input type= "radio" id="radio_excel" name="format_rapport" value="excel"> Excel</label></div></td>
                <td> <div class="checkbox"><label><input type= "radio" id="radio_htm" name="format_rapport" value="htm"> En ligne</label></div></td>
                <td> <div class="checkbox"><label><input type= "radio" id="radio_xml" name="format_rapport"  value="xml"> XML</label></div></td>
            </tr>

            <tr id="tr_5_1">
                <td colspan="3">
                    <b>&nbsp;5) <?php echo _('rapport_choisirdestinataire'); ?>
                </td>
            </tr>
            <tr id="tr_5_2">
                <td colspan="4" style="padding-left: 150px">
                    <table >
                        <tr >
                            <td >Mail 1: 	<input style="width:150px" class="geo3x_input_text" id="text_mail_1" type="text" onblur="valider_mail(this)"> </td>
                            <td>&nbsp;</td>
                            <td><center><input type="checkbox" id="checkbox_mail_1" name="" value="" onclick="onCheckMail(1)"></td></center>
                        </tr>

                        <tr>
                            <td>Mail 2: <input style="width:150px" class="geo3x_input_text" id="text_mail_2" type="text" onblur="valider_mail(this)"></td>
                            <td>&nbsp;</td>
                            <td><center><input type="checkbox" id="checkbox_mail_2" name="" value="" onclick="onCheckMail(2)"></td></center>
                        </tr>

                        <tr>
                            <td>Mail 3: <input style="width:150px" class="geo3x_input_text" id="text_mail_3" type="text" onblur="valider_mail(this)"></td>
                            <td>&nbsp;</td>
                            <td><center><input type="checkbox" id="checkbox_mail_3" name="" value="" onclick="onCheckMail(3)"></td></center>
                        </tr>

                        <tr>
                            <td>Mail 4: <input style="width:150px" class="geo3x_input_text" id="text_mail_4" type="text" onblur="valider_mail(this)"></td>
                            <td>&nbsp;</td>
                            <td><center><input type="checkbox" id="checkbox_mail_4" name="" value="" onclick="onCheckMail(4)"></td></center>
                        </tr>
                    </table>

                </td>
            </tr>

    <tr id="tr_6_1">
        <td colspan="2"style="padding-left: 75px">

            <div id="div_bouton_supprimer" style="display:none"><input class="btn btn-default btn-xs dropdown-toggle" type="button" onClick="deleteRapportAutomatique();" value="<?php echo _('rapport_supprimer'); ?>"></div>
        </td>
        <td colspan="2"><div id="div_bouton_enregister" style="display:none"><input class="btn btn-default btn-xs dropdown-toggle" type="button" onClick="saveTypeRapport()" value="<?php echo _('rapport_enregistrer'); ?>">&nbsp;</div></td>
    </tr>
        </table>

    </center>


</div> 			<!-- FIN Panel Rapport BODY -->

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
<script>
    document.getElementById("language").value = "<?php if( (substr($_SESSION['language'],-2) == "US")) echo ""; else echo "fr";?>";
    var myTimeFormat = "HH:ii:ss";


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
    function selectPeriode(value){
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
                var moisSemaineDebutPeriode
                var anneeSemaineDebutPeriode;

                //Si en semaine flottante on est le mois d'avant
                if((debutPeriode[8]+""+debutPeriode[9]-7) == ("0" || "00") ){
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
                    }else if(moisSemaineDebutPeriode == ( "01" || "03" || "05" || "07" || "08" || "10" || "12" )){
                        jourSemaineDebutPeriode = "31";
                        debutPeriode = replaceStr(debutPeriode,8,jourSemaineDebutPeriode[0]);
                        debutPeriode = replaceStr(debutPeriode,9,jourSemaineDebutPeriode[1]);
                        //Si le mois d'avant est paire
                    }else if(moisSemaineDebutPeriode == ( "04" || "06" || "09" || "11" )){
                        jourSemaineDebutPeriode = "31";
                        debutPeriode = replaceStr(debutPeriode,8,jourSemaineDebutPeriode[0]);
                        debutPeriode = replaceStr(debutPeriode,9,jourSemaineDebutPeriode[1]);
                        //Si le mois d'avant on est en F�vrier
                    }else if(moisSemaineDebutPeriode == "02"){
                        jourSemaineDebutPeriode ="29";
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
            var demo = "datetime";
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

</script>