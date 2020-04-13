<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\DomaineRepository;
use App\Repository\ProjetRepository;
use App\Utils\GestionLog;
use App\Utils\GestionProjet;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projet")
 */
class ProjetController extends AbstractController
{
    private $projetRepository;

    public function __construct(ProjetRepository $projetRepository)
    {
        $this->projetRepository = $projetRepository;
    }

    /**
     * @Route("/", name="projet_index", methods={"GET"})
     */
    public function index(Request $request, ProjetRepository $projetRepository, DomaineRepository $domaineRepository, LoggerInterface $logger, GestionLog $gestionLog): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ACN',null, "Un utilisateur a tenté d'acceder à une page reservé aux ACN de branche");

        $user = $this->getUser();

        // Enregistrement du log Info
        $ip = $request->getClientIp(); //dd($ip);
        if (!$user){
            if (!$user) $logger->info("Anonyme a accedé à la liste des projets",['username'=>'Anonyme', 'module'=>"Projet :: Liste", 'ip'=>$ip]);
        }else{
            $message = $user->getUsername()." a affiché la liste des projets";
            $module = "Projet :: Liste";
            $gestionLog->addLogInfo($user, $module, $message, $ip);
        }

        return $this->render('projet/index.html.twig', [
            'projets' => $projetRepository->findBy([], ['createdAt'=>'DESC']),
            'domaines' => $domaineRepository->findBy([],['libelle'=>'ASC']),
        ]);
    }

    /**
     * @Route("/new", name="projet_new", methods={"GET","POST"})
     */
    public function new(Request $request, LoggerInterface $logger, GestionLog $gestionLog, GestionProjet $gestionProjet): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ACN',null, "Un utilisateur a tenté d'acceder à une page reservé aux ACN de branche");

        $user = $this->getUser();

        // Enregistrement du log Info
        $ip = $request->getClientIp(); //dd($ip);
        if (!$user){
            if (!$user) $logger->info("Anonyme a tenté d'enregistrer un projet",['username'=>'Anonyme', 'module'=>"Projet :: New", 'ip'=>$ip]);
        }else{
            $message = $user->getUsername()." a tenté d'enregistrer un projet";
            $module = "Projet :: New";
            $gestionLog->addLogInfo($user, $module, $message, $ip);
        }

        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $projet->setUser($user);

            // Formattage de la date
            $debut = $gestionProjet->dateFormatte($projet->getDateDebut());
            $fin = $gestionProjet->dateFormatte($projet->getDateFin());

            if (!$debut || !$fin) {
                $this->addFlash('danger', "veuillez choisir le bon format de date ex: 2020/12/31");
                return $this->redirectToRoute('projet_new');
            }

            // Sauvegarde des dates formattées
            $projet->setDateDebut($debut);
            $projet->setDateFin($fin);

            $entityManager->persist($projet);
            $entityManager->flush();

            if (!$user){
                if (!$user) $logger->info("Anonyme a enregistré un projet",['username'=>'Anonyme', 'module'=>"Projet :: New", 'ip'=>$ip]);
            }else{
                $message = $user->getUsername()." a enregistré le projet ".$projet->getId();
                $module = "Projet :: New";
                $gestionLog->addLogInfo($user, $module, $message, $ip);
            }

            return $this->redirectToRoute('projet_index');
        }

        return $this->render('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="projet_show", methods={"GET"})
     */
    public function show(Projet $projet, Request $request, LoggerInterface $logger, GestionLog $gestionLog, GestionProjet $gestionProjet): Response
    {
        $user = $this->getUser();

        // Enregistrement du log Info
        $ip = $request->getClientIp(); //dd($ip);
        if (!$user){
            if (!$user) $logger->info("Anonyme a affiché un projet ",['username'=>'Anonyme', 'module'=>"Projet :: Show", 'ip'=>$ip]);
        }else{
            $message = $user->getUsername()." a affiché le projet ID : ".$projet->getId();
            $module = "Projet :: Show";
            $gestionLog->addLogInfo($user, $module, $message, $ip);
        }

        // Si le nomutilisateur n'existe pas alors affecter Anonyme comme nom
        if (!$user) $username = "Anonyme";
        else $username = $user->getUsername(); //dd($projet);

        // Afficher les autres projets encours
        $projets = $this->projetRepository->findEncours($projet->getBranche()->getId()); //dd($projets);

        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
            'projets' => $projets,
            'username' => $username,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="projet_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Projet $projet, LoggerInterface $logger, GestionLog $gestionLog, GestionProjet $gestionProjet): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ACN',null, "Un utilisateur a tenté d'acceder à une page reservé aux ACN de branche");

        $user = $this->getUser();

        // Enregistrement du log Info
        $ip = $request->getClientIp(); //dd($ip);
        if (!$user){
            if (!$user) $logger->info("Anonyme a tenté de modifier un projet ",['username'=>'Anonyme', 'module'=>"Projet :: Edit", 'ip'=>$ip]);
        }else{
            $message = $user->getUsername()." a tenté de modifier le projet ID : ".$projet->getId();
            $module = "Projet :: Edit";
            $gestionLog->addLogInfo($user, $module, $message, $ip);
        }

        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Formattage de la date
            $debut = $gestionProjet->dateFormatte($projet->getDateDebut());
            $fin = $gestionProjet->dateFormatte($projet->getDateFin());

            if (!$debut || !$fin) {
                $this->addFlash('danger', "veuillez choisir le bon format de date ex: 2020/12/31");
                return $this->redirectToRoute('projet_edit',['id'=>$projet->getId()]);
            }

            // Sauvegarde des dates formattées
            $projet->setDateDebut($debut);
            $projet->setDateFin($fin);
            $this->getDoctrine()->getManager()->flush();

            if (!$user){
                if (!$user) $logger->info("Anonyme a modifié un projet ",['username'=>'Anonyme', 'module'=>"Projet :: Edit", 'ip'=>$ip]);
            }else{
                $message = $user->getUsername()." a modifié le projet ID : ".$projet->getId();
                $module = "Projet :: Edit";
                $gestionLog->addLogInfo($user, $module, $message, $ip);
            }

            return $this->redirectToRoute('projet_index');
        }

        return $this->render('projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="projet_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Projet $projet, LoggerInterface $logger, GestionLog $gestionLog, GestionProjet $gestionProjet): Response
    {
        $user = $this->getUser();

        $theme = $projet->getTheme();

        // Enregistrement du log Info
        $ip = $request->getClientIp(); //dd($ip);
        if (!$user){
            if (!$user) $logger->info("Anonyme a tenté de supprimer un projet",['username'=>'Anonyme', 'module'=>"Projet :: Delete", 'ip'=>$ip]);
        }else{
            $message = $user->getUsername()." a tenté de supprimer le projet qui a pour thème : ".$theme;
            $module = "Projet :: Delete";
            $gestionLog->addLogInfo($user, $module, $message, $ip);
        }

        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projet);
            $entityManager->flush();

            if (!$user){
                if (!$user) $logger->info("Anonyme a supprimé un projet",['username'=>'Anonyme', 'module'=>"Projet :: Delete", 'ip'=>$ip]);
            }else{
                $message = $user->getUsername()." a supprimé un projet qui avait pour thème : ".$theme;
                $module = "Projet :: Delete";
                $gestionLog->addLogInfo($user, $module, $message, $ip);
            }
        }

        return $this->redirectToRoute('projet_index');
    }
}
