<?php

namespace App\Controller;

use App\Repository\ActiviteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(ActiviteRepository $activiteRepository)
    {
        return $this->render('default/index.html.twig', [
            'activites' => $activiteRepository->findListByDesc(),
        ]);
    }
}
