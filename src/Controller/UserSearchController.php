<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
use App\Utils\GestionLog;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/search")
 */
class UserSearchController extends AbstractController
{
    private $userRepository;
    private $utilisateurRepository;
    private $logger;
    private $gestionLog;
    private  $paginator;

    public function __construct(UserRepository $userRepository, UtilisateurRepository $utilisateurRepository, LoggerInterface $logger, GestionLog $gestionLog, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->logger = $logger;
        $this->gestionLog = $gestionLog;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="user_search", methods={"POST","GET"})
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        $username = $request->get('_username');

        $this->denyAccessUnlessGranted('ROLE_ADMIN', 'Page non autorisée', "Un utilisateur a tenté d'acceder de rechercher l'user ".$username);

        // Si le formulalire est vide alors renvoyer a la liste des user
        if (!$username) return $this->redirectToRoute('user_index');

        // Enregistrement du log Info
        $ip = $request->getClientIp();
        $message = $user->getUsername()." a recherché l'user :".$username;
        $module = "Recherche :: User";
        $this->gestionLog->addLogInfo($user, $module, $message, $ip);

        // Recherche de l'utilisateur
        $data = $this->userRepository->searchUser($username);
        $users = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),12
        );


        return $this->render('user/index.html.twig', [
            'users' => $users,
            'utilisateurs' =>$this->utilisateurRepository->findAll(),
            'nombre' => $this->userRepository->findListWithoutUtilisateur(),
        ]);
    }
}
