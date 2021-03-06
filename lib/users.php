<?php

function get_users() {

    $users = array();
    if ($fh = fopen($_SERVER['DOCUMENT_ROOT'].'/config/users/.passwd', 'r')) {
        while ($line = fgets($fh)) {
            $row = explode('||', $line);
            $user = get_user($row[0]);
            $user['name'] = $row[0];
            $user['login'] = $row[0];
            $user['first_name'] = $row[1];
            $user['last_name'] = $row[2];
            $user['mail'] = $row[3];
            $users[] = $user;
        }
    } else {
        echo "<p>Unable to open users config file</p>";
    }
    return $users;
}


function get_user($u) {

    $user = array();

    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/config/users/'.$u)) {
        if ($fh = fopen($_SERVER['DOCUMENT_ROOT'].'/config/users/'.$u, 'r')) {
            $data = fgets($fh);

            fclose($fh);

            $user = unserialize($data);
        } else {
            //echo "<p>Unable to open file for $u</p>";
        }
    } else {

        if ($fh = fopen($_SERVER['DOCUMENT_ROOT'].'/config/users/.passwd', 'r')) {
            while ($line = fgets($fh)) {
                $row = explode('||', $line);
                if ($row[0] == $u) {
                    $user['name'] = $row[0];
                    $user['login'] = $row[0];
                    $user['first_name'] = $row[1];
                    $user['last_name'] = $row[2];
                    $user['mail'] = $row[3];
                }
            }
        }

    }
    return $user;

}


function save_user($user) {

    $fh = fopen($_SERVER['DOCUMENT_ROOT'].'/config/users/'.$user['login'], 'w');

    fwrite($fh, serialize($user));

    fclose($fh);

}
/*
$u = array();
$u['login'] = 'thierry';
$u['first_name'] = 'Thierry';
$u['last_name'] = 'Sallé';
$dirs = array();
$dirs[] = "2013-06-29 - Asnières";
$dirs[] = "2013-06 - Apéro";
$dirs[] = "2013-06 - Maternité";
$dirs[] = "2013-06 - Photos Papi et Mamie Sallé";
$dirs[] = "2013-06 - Retour à la maison";
$dirs[] = "2013-07 - Charlie et Papa";
$dirs[] = "2013-07 - Dans le bain";
$dirs[] = "2013-07 - Dans l'écharpe";
$dirs[] = "2013-07 et 08 - Happy Charlie";
$dirs[] = "2013-07 et 08 - les copains de Maman";
$dirs[] = "2013-07 - Promenade avec Tata";
$dirs[] = "2013-07 - Week end avec Bernard et Thérèse";
$dirs[] = "2013-08 - Chez Delphine à Lyon";
$dirs[] = "2013-08 - Chez Mamie à Livron";
$dirs[] = "2013-08 - Chez Marie et Mat";
$dirs[] = "2013-08 - Chez Papi à Françillon";
$dirs[] = "2013-08 - Grenoble";
$dirs[] = "2013-08 - Sieste...";
$dirs[] = "2013-09-07 et 08 - Week end en Normandie";
$dirs[] = "2013-09 - A la maison";
$dirs[] = "2013-10 - Divers";
$dirs[] = "2013-11 - Novembre";
$dirs[] = "2013-12 - a la maison";
$dirs[] = "2014-01 - Bonne Année";
$dirs[] = "2014-01 - Divers";
$dirs[] = "2014-03 - A la Crèche";
$dirs[] = "2014-03 - A la Maison";
$dirs[] = "2014-03 - Divers";
$dirs[] = "2014-03 - Effeil Tower";
$dirs[] = "2014-04 - A Emalleville";
$dirs[] = "2014-04 - A la maison";
$dirs[] = "2014-05";
$dirs[] = "2014-07 Au parc";
$dirs[] = "2014-07 le Trotteur";
$dirs[] = "2014-08 en vacances";
$dirs[] = "2014-09";
$dirs[] = "2014-10";
$dirs[] = "2014-12";
$dirs[] = "2015";

$u['dirs'] = $dirs;

save_user($u);
*/

?>
