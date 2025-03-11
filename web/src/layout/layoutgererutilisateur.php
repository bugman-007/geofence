<?php

/*
 * Pour l'affiche d'un contenu HTML pour afficher le contenu de "Gerer"
 * Afficher par un include dans la page layout.php
 */

	include '../dbgpw.php';
	/************* Recuperer l'Id_Base, Id_GPW, NomGPW de l'utilisateur *******************/
	$connectGpwUser = mysqli_connect($server, $db_user, $db_pass,$database);
	if (!$connectGpwUser) {
        die('Impossible de se connecter: '.mysqli_connect_error());
    }
    mysqli_set_charset($connectGpwUser, "utf8");
	$queryGpwUser = mysqli_query($connectGpwUser,"SELECT Id_Base,Id_GPW, NomGPW, Superviseur, Id_Client FROM gpwuser_gpw WHERE (Login = '".$_SESSION['username']."' ) ORDER BY NomGPW"); //AND Id_GPW != 0
	$assocGpwUser = mysqli_fetch_assoc($queryGpwUser);

	$superviseurGpwUser = $assocGpwUser['Superviseur'];
	$idClientGpwUser = $assocGpwUser['Id_Client'];

	mysqli_free_result($queryGpwUser);
	mysqli_close($connectGpwUser);
	?>
    <div class="modal fade" id="gerer" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <br><br><br><br>
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="gerer_modal" class="modal-body" style="min-height:710px;overflow:auto;">
                    <form class="form-horizontal" role="form" style="font-size: 14px;">
                        <div class="form-group">
                            <div class="col-md-10" >
                                &nbsp; &nbsp;<span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;
                                <label  class="control-label">
                                    <?php echo _('option_groupeutilisateur'); ?>:
                                </label>
                            </div>.
                        </div>
                        <div style="border: 0; height: 1px; background: #aaa; background-image: linear-gradient(to right, lightgray, lightgray, lightgray);"></div>
                    </form>
                    <br/>
                    <div id="TableGroupe"   style="height:210px;overflow:scroll;">
                    </div>

                    <table class="table table-borderless">
                        <?php

                        if( $superviseurGpwUser == "2" && $idClientGpwUser != "-1"){
                            echo '<tr><td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="showCreateGroup(\''.$idClientGpwUser.'\')" value="'; echo _('option_creergroupe'); echo'" ></td>';
                            echo '<td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="showModifyGroup(\''.$idClientGpwUser.'\')" value="'; echo _('option_modifiergroupe'); echo'"></td>';
                            echo '<td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="deleteGPW(\''.$idClientGpwUser.'\')" value="'; echo _('option_supprimergroupe'); echo'"></td></tr>';
                        }else  if( $superviseurGpwUser == "2" && $idClientGpwUser == "-1"){
                            echo '<tr><td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="showCreateGroup()" value="'; echo _('option_creergroupe'); echo'" ></td>';
                            echo '<td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="showModifyGroup()" value="'; echo _('option_modifiergroupe'); echo'"></td>';
                            echo '<td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="deleteGPW()" value="'; echo _('option_supprimergroupe'); echo'"></td></tr>';
                        }else  if( !empty($_SESSION['superviseurIdBd'])){
                            echo '<tr><td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="showCreateGroup()" value="'; echo _('option_creergroupe'); echo'" ></td>';
                            echo '<td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="showModifyGroup()" value="'; echo _('option_modifiergroupe'); echo'"></td>';
                            echo '<td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="deleteGPW()" value="'; echo _('option_supprimergroupe'); echo'"></td></tr>';
                        }
                        ?>

                    </table>
                    <br/>
                    <br/>
                    <form class="form-horizontal" role="form" style="font-size: 14px;">
                        <div class="form-group">
                            <div class="col-md-10" >
                                &nbsp; &nbsp;<span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;
                                <label  class="control-label">
                                    <?php echo _('option_compteutilisateur'); ?>:
                                </label>
                            </div>.
                        </div>
                        <div style="border: 0; height: 1px; background: #aaa; background-image: linear-gradient(to right, lightgray, lightgray, lightgray);"></div>
                        <br/>
                    </form>
                    
                    <div id="TableCompte" style="height:210px;overflow:scroll;">
                    </div>

                    <table class="table table-borderless">
                        <?php
                        if( $superviseurGpwUser == "2" && $idClientGpwUser != "-1"){
                            echo '<tr><td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="showCreateAccount(\''.$idClientGpwUser.'\')" value="'; echo _('option_creercompte'); echo'"></td>';
                            echo '<td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="showModifyAccount(\''.$idClientGpwUser.'\')" value="'; echo _('option_modifiercompte'); echo'"></td>';
                            echo '<td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="deleteAccount(\''.$idClientGpwUser.'\')" value="'; echo _('option_supprimercompte'); echo'"></td></tr>';
                        }else  if( $superviseurGpwUser == "2" && $idClientGpwUser == "-1"){
                            echo '<tr><td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="showCreateAccount()" value="'; echo _('option_creercompte'); echo'"></td>';
                            echo '<td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="showModifyAccount()" value="'; echo _('option_modifiercompte'); echo'"></td>';
                            echo '<td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="deleteAccount()" value="'; echo _('option_supprimercompte'); echo'"></td></tr>';
                        }else if( !empty($_SESSION['superviseurIdBd'])){
                            echo '<tr><td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="showCreateAccount()" value="'; echo _('option_creercompte'); echo'"></td>';
                            echo '<td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="showModifyAccount()" value="'; echo _('option_modifiercompte'); echo'"></td>';
                            echo '<td style="text-align:center;"><input type="button" class="btn btn-default btn-xs" onClick="deleteAccount()" value="'; echo _('option_supprimercompte'); echo'"></td></tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="fiche_groupe" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <br><br><br><br>
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="fiche_groupe_modal" class="modal-body" style="min-height:700px;overflow:auto;">
                    <form class="form-horizontal" role="form" style="font-size: 14px;">
                        <div class="form-group">
                            <div class="col-md-10" >
                                &nbsp; &nbsp;<span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;
                                <label class="control-label">
                                    <?php echo _('option_fichegroupe'); ?>:
                                </label>
                            </div>
                        </div>
                        <div style="border: 0; height: 1px; background: #aaa; background-image: linear-gradient(to right, lightgray, lightgray, lightgray);"></div>
                    </form>
                    <div id="create_groupe_content" ></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="fiche_compte" tabindex="-20" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <br><br><br><br>
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="fiche_compte_modal" class="modal-body" style="min-height:800px;overflow:auto;">

                </div>
            </div>
        </div>
    </div>
   