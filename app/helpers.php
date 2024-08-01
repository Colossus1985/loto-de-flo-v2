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

    if ($nameGroup != null || $nameGroup != '') {
        if (!preg_match($regexLiberty, $nameGroup)) {
            array_push($arrayControles, ['erreur' => true, 'message' => "Attention aux charactères spéciaux dans le nom du groupe!"]);
            return $arrayControles;
        }
    }

    array_push($arrayControles, ['erreur' => false, 'message' => ""]);
        return $arrayControles;
}