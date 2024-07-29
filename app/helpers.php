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