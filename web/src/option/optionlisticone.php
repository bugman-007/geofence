<?php

/*
* Afficher la liste des icones
*/

    /**
     * Created by PhpStorm.
     * User: NGUYEN Christophe
     * Date: 29/05/2015
     * Time: 10:10
     */

    $dir_nom = '../../assets/img/icon_list'; // dossier listé (pour lister le répertoir courant : $dir_nom = '.'  --> ('point')
    $dir = opendir($dir_nom) or die('Erreur de listage : le répertoire n\'existe pas'); // on ouvre le contenu du dossier courant
    $fichier= array(); // on déclare le tableau contenant le nom des fichiers
    $dossier= array(); // on déclare le tableau contenant le nom des dossiers

    while($element = readdir($dir)) {
        if($element != '.' && $element != '..') {
            if (!is_dir($dir_nom.'/'.$element)) {$fichier[] = $element;}
            else {$dossier[] = $element;}
        }
    }

    closedir($dir);

    $nbreIcone = 0;
    if(!empty($fichier)){
        foreach($fichier as $lien) {
            $extension = explode(".", $lien);
            if($extension[1] == 'png'  || $extension[1] == 'ico') {
                $nbreIcone++;
            }
        }
    }
    echo "t".$nbreIcone."g";
    if(!empty($fichier)){
        sort($fichier);
        foreach($fichier as $lien) {
            $extension = explode(".", $lien);
            if($extension[1] == 'png'  || $extension[1] == 'ico') {
                $source = $dir_nom."/".$lien;
                echo " <a href=\"#\"><img class=\"icone\" onclick=\"selectIcone(this,'$source')\" src=\"$source\"  alt=\"\" /></a>";
                echo "&";
                echo " < img";
            }
        }
    }
?>