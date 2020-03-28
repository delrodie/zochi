<?php


namespace App\Utils;


class GestionActivite
{
    /**
     * Determination du type de fichier par l'extension
     *
     * @param $extension
     * @return string
     */
    public function mediaType($extension): string
    {
        switch ($extension){
            case 'png':
                $type = 'image';
                break;
            case 'jpeg':
                $type = 'image';
                break;
            case 'jpg':
                $type = 'image';
                break;
            case 'gif':
                $type = 'image';
                break;
            case 'webp':
                $type = 'image';
                break;
            default:
                $type = 'video';
                break;
        }

        return $type;
    }
}