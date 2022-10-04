<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 04/10/2022
 * Time: 12:10 PM
 */

function removeSpecialCharacters($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

    return preg_replace('/-+/', '_', $string); // Replaces multiple hyphens with single one.
}