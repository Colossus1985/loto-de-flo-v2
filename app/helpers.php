<?php

/**
 * Transforme une date mysql (aaaa-mm-jj HH:ii:ss) en format courant (jj/mm/aa)
 *
 * @param	string	date format to return
 * @param	string	short année sur 2 caractères
 * @param	string	time rajout de l'heure (HH:mm)
 * @param	string	longtime rajout des secondes (HH:mm:ss) (Ajout 31/10/19)
 * @param	string	timezone
 */

 if (!function_exists('sql2display')) { 
    function sql2display($date, $short=false, $time=false, $longtime=false, $tz=false)
    {
        if (empty($date) || is_null($date) || !$date) {
            return '';
        }

        // Nettoyage de la date : Suppression des espaces redondants mais en conservant ceux nécessaires
        $clean_date = preg_replace('/\s+/', ' ', trim($date));

        // Support pour différents formats d'entrée
        // Exemple : Sept 30 2021 11:14AM ou 2021-09-30 11:14AM
        $formats = [
            'M d Y h:iA',  // Format avec mois abrégé, date et heure 12h
            'Y-m-d H:i:s', // Format MySQL standard
            'Y-m-d',       // Format sans heure
            'Y/m/d H:i:s', // Format avec slashs et heure
            'Y/m/d'        // Format avec slashs sans heure
        ];

        // Tentative de conversion de la date
        $c_date = false;
        foreach ($formats as $format) {
            $c_date = DateTime::createFromFormat($format, $clean_date);
            if ($c_date !== false) {
                break;
            }
        }

        // Vérification si la date contient des millisecondes au format 'Y-m-d H:i:s.u'
        if (!$c_date && preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d{1,6}$/', $clean_date)) {
            // Ajout de zéros si nécessaire pour gérer les millisecondes (format '.000', '.1', etc.)
            $parts = explode('.', $clean_date);
            $milliseconds = str_pad($parts[1], 6, '0'); // Compléter avec des zéros pour 6 chiffres
            $date = $parts[0] . '.' . $milliseconds;
            $c_date = DateTime::createFromFormat('Y-m-d H:i:s.u', $date);
        }
    
        // Si aucune conversion n'a fonctionné, retour vide
        if (!$c_date) {
            //  return '';
            return $date; //PhR 08/11/24
        }
    
        // Vérification si la date contient des millisecondes
        if (strlen($date) > 19) {
            // Extraire les millisecondes (si présentes)
            $milliseconds = substr($date, 20);
            $milliseconds = str_pad($milliseconds, 6, '0'); // Ajoute des zéros jusqu'à 6 chiffres
            $date = substr($date, 0, 19) . '.' . $milliseconds;
            $c_date = DateTime::createFromFormat('Y-m-d H:i:s.u', $date);
        }

        // Gestion des fuseaux horaires si spécifié
        if ($tz) {
            date_timezone_set($c_date, timezone_open($tz));
        }

        // Formatage de la date
        $d = $short ? $c_date->format('d/m/y') : $c_date->format('d/m/Y');

        // Ajout du temps si demandé
        $t = '';
        if ($time) {
            if ($longtime) {
                $t = ' (' . $c_date->format('H:i:s') . ')'; // Format long avec secondes
            } else {
                $t = ' (' . $c_date->format('H:i') . ')';   // Format court sans secondes
            }
        }

        return $d . $t;
    }
}

/**
 * Pour affichage valeur si non nulle
 * Renvoi valeur ou vide
 * @param double $val
 * @param boolean $format Si formatage nombre ( 2 décimales, séparateur décimal, séparateur milliers)
 * @param string $suffixe à ajouter en fin de chaîne si non null et chaine non vide (false par defaut)
 * @param string $virgule séparateur décimales  (point par défaut) 
 * @param string $millier séparateur milliers  (espace par defaut)
 * @param string $nb_decim nombre de décimales   (2 par défaut)
 * @param string $default valeur rendu si $val = 0   (2 par défaut)
 * @return type
 */
if (!function_exists('ifNotZero')) { 
    function ifNotZero($val, $format=false, $suffixe=null, $virgule='.', $millier=' ', $nb_decim=2, $default='0,00')
    {
        if ($val != 0) {
            if ($format) {
                $val = number_format($val, $nb_decim, $virgule, $millier);
            }
            if ($suffixe) {
                $val = $val . $suffixe;
            }
        } else {
            $val = $default . ' ' . $suffixe;
        }
        return $val;
    }
}

/**
 * tester un input 
 * @param string valeur d'un input
 */
if (!function_exists('controlesInputs')) { 
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
}