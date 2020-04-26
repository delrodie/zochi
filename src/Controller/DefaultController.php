<?php

namespace App\Controller;

use App\Repository\ActiviteRepository;
use App\Repository\CommentaireRepository;
use App\Repository\JotiRepository;
use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
use App\Utils\GestionLog;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request, ActiviteRepository $activiteRepository,PaginatorInterface $paginator, UserRepository $userRepository, GestionLog $gestionLog, LoggerInterface $logger, CommentaireRepository $commentaireRepository, UtilisateurRepository $utilisateurRepository)
    {
        $activiteListe = $activiteRepository->findListByDesc();

        $user = $this->getUser();

        // Enregistrement du log Info
        $ip = $request->getClientIp(); //dd($ip);
        if (!$user){
            if (!$user) $logger->info("Anonyme a acceder a la page d'accueil",['username'=>'Anonyme', 'module'=>"Accueil", 'ip'=>$ip]);
        }else{
            $message = $user->getUsername()." a affiché la page d'accueil";
            $module = "Accueil";
            $gestionLog->addLogInfo($user, $module, $message, $ip);
        }



        $activites = $paginator->paginate(
            $activiteListe,
            $request->query->getInt('page', 1),3
        );

        return $this->render('v1/default/index.html.twig', [
            'activites' => $activites,
            'nombre_activite' => $activiteListe,
            'utilisateurs' => $userRepository->findAll(),
            'nombre_commentaire' => $commentaireRepository->findAll(),
            'louveteaux' => $utilisateurRepository->findByBrancheAndStatut('Louveteau', 'Jeune'),
            'eclaireurs' => $utilisateurRepository->findByBrancheAndStatut('Eclaireur', 'Jeune'),
            'cheminots' => $utilisateurRepository->findByBrancheAndStatut('Cheminot', 'Jeune'),
            'routiers' => $utilisateurRepository->findByBrancheAndStatut('Routier', 'Jeune'),
        ]);
    }

    /**
     * @Route("/template", name="app_template")
     */
    public function template(Request $request, ActiviteRepository $activiteRepository,PaginatorInterface $paginator, UserRepository $userRepository, GestionLog $gestionLog, LoggerInterface $logger, CommentaireRepository $commentaireRepository)
    {
        $activiteListe = $activiteRepository->findListByDesc();

        $user = $this->getUser();

        // Enregistrement du log Info
        $ip = $request->getClientIp(); //dd($ip);
        if (!$user){
            if (!$user) $logger->info("Anonyme a acceder a la page d'accueil",['username'=>'Anonyme', 'module'=>"Accueil", 'ip'=>$ip]);
        }else{
            $message = $user->getUsername()." a affiché la page d'accueil";
            $module = "Accueil";
            $gestionLog->addLogInfo($user, $module, $message, $ip);
        }



        $activites = $paginator->paginate(
            $activiteListe,
            $request->query->getInt('page', 1),6
        );
        return $this->render('default/template2.html.twig', [
            'activites' => $activites,
            'nombre_activite' => $activiteListe,
            'utilisateurs' => $userRepository->findAll(),
            'nombre_commentaire' => $commentaireRepository->findAll(),
        ]);
    }
}
