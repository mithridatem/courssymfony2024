<?php
namespace App\Service;

class UtilsService{
    public static function cleanInput(string $value): string{
        return htmlspecialchars(strip_tags(trim($value)),ENT_NOQUOTES);
    }
}