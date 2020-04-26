<?php

namespace App\Controller;

use App\Repository\ActiviteRepository;
use App\Utils\GestionLog;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class V1ActiviteController extends AbstractController
{
    private $activiteRepository;
    private $paginator;
    private $logger;
    private $gestionLog;

    public function __construct(ActiviteRepository $activiteRepository, PaginatorInterface $paginator, LoggerInterface $logger, GestionLog $gestionLog)
    {
        $this->activiteRepository = $activiteRepository;
        $this->paginator = $paginator;
        $this->logger = $logger;
        $this->gestionLog = $gestionLog;
    }
    /**
     * @Route("/v1/activite", name="v1_activite_index")
     */
    public function index(Request $request)
    {
        $activiteListe = $this->activiteRepository->findListByDesc();

        $user = $this->getUser();

        // Enregistrement du log Info
        $ip = $request->getClientIp(); //dd($ip);
        if (!$user){
            if (!$user) $this->logger->info("Anonyme a acceder a la page d'accueil",['username'=>'Anonyme', 'module'=>"Accueil", 'ip'=>$ip]);
        }else{
            $message = $user->getUsername()." a affiché la page d'accueil";
            $module = "Accueil";
            $this->gestionLog->addLogInfo($user, $module, $message, $ip);
        }



        $activites = $this->paginator->paginate(
            $activiteListe,
            $request->query->getInt('page', 1),6
        );
        return $this->render('v1_activite/index.html.twig', [
            'activites' => $activites,
        ]);
    }
}
