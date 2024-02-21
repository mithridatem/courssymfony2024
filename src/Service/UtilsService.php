<?php

namespace App\Service;

use Symfony\Component\String\UnicodeString;

class UtilsService
{
    /**
     * Fonction qui va nettoyer la chaine en entrée
     * 
     * @param string $value chaine à nettoyer
     * @return string retourne la chaine néttoyé
     */
    public static function cleanInput(string $value): string
    {
        return htmlspecialchars(strip_tags(trim($value)), ENT_NOQUOTES);
    }

    /**
     * fonction qui test si une chaine match un regex
     * 
     * @param string $string chaine à tester
     * @param string $regex regex
     * @return bool retourne true si la chaine matche le regex, sinon false
     */
    public static function testRegex(string $string, string $regex): bool
    {
        //chaine à tester
        $string = new UnicodeString($string);
        //si le password possède 12 caractères minumum, lettre minuscule, majuscule et nombre
        if ($string->match($regex)) {
            return true;
        } 
        return false;
    }
}
