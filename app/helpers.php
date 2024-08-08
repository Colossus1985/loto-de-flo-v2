<?php

/**
 * Pour affichage valeur si non nulle
 * Renvoi valeur ou vide
 * @param double $val
 * @param boolean $format Si formatage nombre ( 2 décimales, séparateur décimal, séparateur milliers)
 * @param string $suffixe à ajouter en fin de chaîne si non null et chaine non vide (false par defaut)
 * @param string $virgule séparateur décimales  (point par défaut) 
 * @param string $millier séparateur milliers  (espace par defaut)
 * @param string $nb_decim nombre de décimales   (2 par défaut)
 * @param boolean $zero affichage de '0' si valeur nulle malgré tout
 * @return type
 */
function ifNotZero($val, $format=false, $suffixe=null, $virgule='.', $millier=' ', $nb_decim=2, $zero=false)
{
    if ($val != 0 || $zero) {
        if ($format) {
            $val = number_format($val, $nb_decim, $virgule, $millier);
        }
        if ($suffixe) {
            $val = $val . $suffixe;
        }
    } else {
        $val = '';
    }
    return $val;
}

/**
 * Transforme une date mysql (aaaa-mm-jj HH:ii:ss) en format courant (jj/mm/aa)
 *
 * @param   string  date format to return
 * @param   string  short année sur 2 caractères
 * @param   string  time rajout de l'heure (HH:mm)
 * @param   string  longtime rajout des secondes (HH:mm:ss) (Ajout 31/10/19)
 */
function sql2display($date, $short=false, $time=false, $longtime=false)
{
    if (empty($date))
    {
        return ''; //false;
    }
    // Format origine : aaaa-mm-jj HH:ii:ss
    $date = str_replace('/', '-', $date); // Au cas ou yyyy/mm/dd
    if (strlen($date)<10 || $date == '0000-00-00'){
        return ''; //false;
    }

    $c_date = strlen($date) > 10 ? DateTime::createFromFormat('Y-m-d H:i:s', $date) : DateTime::createFromFormat('Y-m-d', $date);

    $d = $short ? $c_date->format('d/m/y') : $c_date->format('d/m/Y');

    $t = '';
    if ($time) {
        if ($longtime) {
            $t = ' ('.$c_date->format('H:i:s').')';
        } else {
            $t = ' ('.$c_date->format('H:i').')';
        }
    }

    return $d.$t;
}

/**
 * tester un input 
 * @param string valeur d'un input
 */
function controlesInputs($request)
{
    $arrayControles = [];
    $regexInputName = "/^(\s)*[A-Za-z]+((\s)?((\'|\-|\.)?([A-Za-zéèîôàêç@])*))*(\s)*$/";
    $regexLiberty = "/^(\s)*[A-Za-z0-9éèîôàêç@]+((\s)?((\'|\-|\.)?([A-Za-z0-9éèîôàêç@])*))*(\s)*$/";
    $regexPhone = "/^([0-9]*)$/";

    $pwd_one = $request->inputPassword;
    $pwd_two = $request->inputPassword_confirmation;
    $phone = $request->inputTel;
    $firstName = $request->inputFirstName;
    $lastName = $request->inputLastName;
    $pseudo = $request->inputPseudo;
    $log_identifiant = $request->inputRegister;
    $nameGroup = $request->inputNameGroup;

    if ($pwd_one != null || $pwd_one != '') {
        if (!preg_match($regexLiberty, $pwd_one)) {
            array_push($arrayControles, ['erreur' => true, 'message' => "Attention aux charactères trop spéciaux dans le mot de passe !"]);
            return $arrayControles;
        } 
    }

    if ($pwd_two != null || $pwd_two != '') {
        if (!preg_match($regexLiberty, $pwd_two)) {
                array_push($arrayControles, ['erreur' => true, 'message' => "Attention aux charactères trop spéciaux dans le mot de passe !"]);
                return $arrayControles;
            } 
        }
    
    if ($pwd_one != null && $pwd_two != null) {
        if ($pwd_one != $pwd_two) {
            array_push($arrayControles, ['erreur' => true, 'message' => "Les deux mot de passes ne correspondent pas!"]);
            return $arrayControles;
        }
    }
    
    if ($phone != null || $phone != '') {
        if (!preg_match($regexPhone, $phone)) {
            array_push($arrayControles, ['erreur' => true, 'message' => "Veuillez rentrer seulement des numéros sans espaces pour le numéro de téléphone, s'il vous plait!"]);
            return $arrayControles;
        } 
    }
    
    if ($firstName != null || $firstName != '') {
        if (!preg_match($regexInputName, $firstName)) {
            array_push($arrayControles, ['erreur' => true, 'message' => "Attention aux charactères spéciaux et chiffres dans le prenom!"]);
            return $arrayControles;
        }
    }
    
    if ($lastName != null || $lastName != '') {
        if (!preg_match($regexInputName, $lastName)) {
            array_push($arrayControles, ['erreur' => true, 'message' => "Attention aux charactères spéciaux et chiffres dans le nom!"]);
            return $arrayControles;
        } 
    }
    
    if ($pseudo != null || $pseudo != '') {
        if (!preg_match($regexLiberty, $pseudo)) {
            array_push($arrayControles, ['erreur' => true, 'message' => "Attention aux charactères spéciaux dans le pseudo!"]);
            return $arrayControles;
        }
    }
    
    if ($log_identifiant != null || $log_identifiant != '') {
        if (!preg_match($regexLiberty, $log_identifiant)) {
            array_push($arrayControles, ['erreur' => true, 'message' => "Attention aux charactères spéciaux dans l'identifiant !"]);
            return $arrayControles;
        }
    }

    array_push($arrayControles, ['erreur' => false, 'message' => ""]);
        return $arrayControles;
}