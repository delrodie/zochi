<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Utils\GestionActivite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/publication")
 */
class PublicationController extends AbstractController
{
    /**
     * @Route("/publication", name="publication")
     */
    public function index()
    {
        return $this->render('publication/index.html.twig', [
            'controller_name' => 'PublicationController',
        ]);
    }

    /**
     * @Route("/{id}", name="publication_show")
     */
    public function show(Activite $activite, GestionActivite $gestionActivite)
    {
        $compteur = $gestionActivite->compteurVue($activite->getId());
        if (!$compteur) return $this->redirectToRoute('homepage');
        return $this->render('publication/show.html.twig',[
            'activite' => $activite
        ]);
    }
}
