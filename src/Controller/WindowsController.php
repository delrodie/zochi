<?php


namespace App\Controller;

use App\Repository\ActiviteRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/windows")
 */
class WindowsController extends AbstractController
{
    /**
     * @Route("/", name="windows_bloc_gauche")
     */
    public function gauche()
    {
        return $this->render('default/bloc_gauche.html.twig');
    }

    /**
     * @Route("/droit", name="windows_bloc_droit")
     */
    public function droit(ActiviteRepository $activiteRepository)
    {
        return $this->render('default/bloc_droit.html.twig',[
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/haut/entete", name="windows_bloc_entete")
     */
    public function entete(UtilisateurRepository $utilisateurRepository)
    {
        $user = $this->getUser();
        $utilisateur = $utilisateurRepository->findOneByUser($user);
        return $this->render('default/bloc_haut.html.twig',[
            'utilisateur' => $utilisateur,
            'user' => $user
        ]);
    }
}