<?php

namespace App\Controller;

use App\Repository\ActiviteRepository;
use App\Repository\JotiRepository;
use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request, ActiviteRepository $activiteRepository, JotiRepository $jotiRepository ,PaginatorInterface $paginator, UserRepository $userRepository)
    {
        $activiteListe = $activiteRepository->findListByDesc();

        $activites = $paginator->paginate(
            $activiteListe,
            $request->query->getInt('page', 1),9
        );
        return $this->render('default/index.html.twig', [
            'activites' => $activites,
            'jotis' => $jotiRepository->findAll(),
            'nombre_activite' => $activiteListe,
            'utilisateurs' => $userRepository->findAll(),
        ]);
    }
}
