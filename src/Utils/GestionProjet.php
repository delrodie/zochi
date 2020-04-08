<?php


namespace App\Utils;


class GestionProjet
{
    /**
     * Formattage de la date en YYYY-mm-dd
     *
     * @param $date
     * @return string
     */
    public function dateFormatte($date): string
    {
        $valeur = explode('/', $date); //dd(!array_key_exists(1,$valeur));
        // Si la date ne contient pas de slash alors renvoyer false
        if (!array_key_exists(1,$valeur)) {
            $valeur = explode('-', $date);
            if (!array_key_exists(1, $valeur)) return false;
        };
        $formatte = $valeur[0].'-'.$valeur[1].'-'.$valeur[2];

        return $formatte;
    }
}