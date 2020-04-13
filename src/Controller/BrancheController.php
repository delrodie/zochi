<?php

namespace App\Controller;

use App\Repository\ActiviteRepository;
use App\Repository\BrancheRepository;
use App\Repository\ProjetRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/branche")
 */
class BrancheController extends AbstractController
{
    private $brancheRepository;
    private $projetRepository;
    private $paginator;
    private $activiteRepository;

    public function __construct(BrancheRepository $brancheRepository, ProjetRepository $projetRepository, PaginatorInterface $paginator, ActiviteRepository $activiteRepository)
    {
        $this->brancheRepository = $brancheRepository;
        $this->projetRepository = $projetRepository;
        $this->paginator = $paginator;
        $this->activiteRepository = $activiteRepository;
    }

    /**
     * @Route("/{slug}", name="branche_index")
     */
    public function index(Request $request, $slug)
    {
        $branche = $this->brancheRepository->findOneBy(['slug'=>$slug]); //dd($branche->getId());
        $data = $this->activiteRepository->findByBranche($branche->getId());
        $projets = $this->projetRepository->findEncours($branche->getId());

        $activites = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),4
        );

        return $this->render('branche/index.html.twig', [
            'activites' => $activites,
            'branche' => $branche,
            'projets' => $projets
        ]);
    }

    /**
     * @Route("/{projet}/liste", name="branche_projet_activites")
     */
    public function activite(Request $request, $projet)
    {
        $data = $this->activiteRepository->findByProjet($projet);

        $activites = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),4
        );
        $projets = $this->projetRepository->findEncours($projet);


        return $this->render('branche/branche.html.twig', [
            'activites' => $activites,
            'projets' => $projets
        ]);
    }

    /**
     * @Route("/menu/1/branche", name="branche_menu")
     */
    public function menu(BrancheRepository $brancheRepository)
    {
        return $this->render('branche/menu.html.twig',[
            'branches' => $brancheRepository->findAll(),
        ]);
    }
}
