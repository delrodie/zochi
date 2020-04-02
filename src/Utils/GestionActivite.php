<?php


namespace App\Utils;


use App\Repository\ActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;

class GestionActivite
{
    private $em;
    private $activiteRepository;

    public function __construct(EntityManagerInterface $em, ActiviteRepository $activiteRepository)
    {
        $this->em = $em;
        $this->activiteRepository = $activiteRepository;
    }

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

    /**
     * Incrementation du nombre de vue de l'article
     *
     * @param $id
     * @return bool
     */
    public function compteurVue($id): bool
    {
        $activite = $this->activiteRepository->findOneById($id);
        if (!$activite) return false;
        else{
            $compteur = $activite->getVue();
            $activite->setVue($compteur+1);
            $this->em->flush();

            return true;
        }
    }
}