<?php


namespace App\Utils;


use App\Repository\ActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

class GestionActivite
{
    private $em;
    private $activiteRepository;

    public function __construct(EntityManagerInterface $em, ActiviteRepository $activiteRepository, KernelInterface $kernel)
    {
        $this->em = $em;
        $this->activiteRepository = $activiteRepository;
        $this->kernel = $kernel;
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

    /**
     * Ouverture du fichier log en fonction de la date et de l'environnement
     *
     * @param $date
     * @return array|bool|false
     */
    public function ouverture($date)
    {
        // Recuperer la date puis affecter l'extension .log a la date
        // recuperer l'environnement encours puis chercher le chemin du repertoire
        $extension = $date.'.log';
        $env = $this->kernel->getEnvironment(); //dd($env);
        $racine = $this->kernel->getProjectDir().'/var/log/'.$env.'.monitoring-'.$extension;

        // Si le fichier n'existe pas alors retourner false
        // Sinon renvoyer le fichier ouvert
        if (!file_exists($racine))return false;
        else{
            $fichier = file($racine);

            return $fichier;
        }

    }
}