<?php

namespace App\Service;

use Symfony\Component\String\UnicodeString;

class UtilsService
{
    /**
     * @param string $value chaine à nettoyer
     * @return string chaine néttoyé (suppression du code)
     */
    //fonction qui nettoie les chaines de caractères
    public static function cleanInput(string $value): string
    {
        return htmlspecialchars(strip_tags(trim($value)), ENT_NOQUOTES);
    }

    /**
     * @param string $string chaine à tester
     * @param string $regex regex
     * @return bool retourne true si la chaine matche le regex, sinon false
     */
    //fonction qui test si une chaine match un regex
    public static function testRegex(string $string, string $regex): bool
    {
        //chaine à tester
        $string = new UnicodeString($string);
        //si le password possède 12 caractères minumum, lettre minuscule, majuscule et nombre
        if ($string->match($regex)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $string chaine à encoder en UTF-8
     * @return string retourne la chaine encodée en UTF-8
     */
    //fonction qui encode en UTF-8 une chaine de caractères
    public static function convertUtf8(string $string): string
    {
        return mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
    }

    /**
     * @param array $data tableau de string
     * @return bool retourne true si un des champs est vide sinon false
     */
    //fonction qui teste si les imputs d'un formulaire sont vides
    public static function testEmpty(...$data): bool
    {
        foreach ($data as $value) {
            if (empty($value)) {
                return true;
            }
        }
        return false;
    }
}
