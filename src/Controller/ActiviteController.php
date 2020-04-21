<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Commentaire;
use App\Entity\Projet;
use App\Form\ActiviteType;
use App\Form\CommentaireType;
use App\Repository\ActiviteRepository;
use App\Repository\ProjetRepository;
use App\Repository\UtilisateurRepository;
use App\Utils\GestionActivite;
use App\Utils\GestionLog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/activite")
 */
class ActiviteController extends AbstractController
{
    private  $utilisateurRepository;
    private $projetRepository;

    public function __construct(UtilisateurRepository $utilisateurRepository, ProjetRepository $projetRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->projetRepository = $projetRepository;
    }

    /**
     * @Route("/", name="activite_index", methods={"GET"})
     */
    public function index(Request $request, ActiviteRepository $activiteRepository, GestionLog $gestionLog): Response
    {
        $user = $this->getUser();

        // Si le profile de l'utilisateur n'a pas été renseigné alors rediriger le vers enregistrement de profile
        $utilisateur = $this->utilisateurRepository->findByUser($user->getUsername());
        if (!$utilisateur){
            $username = strtoupper($user->getUsername());
            $this->addFlash('warning', "Bonjour ".$username. ", Veuillez renseignez votre profile pour participer au Scoutisme à la maison");
            return $this->redirectToRoute('utilisateur_new');
        }

        // Enregistrement du log
        $ip = $request->getClientIp();
        $message = $user->getUsername()." a affiché la liste de ses activités";
        $module = "Activite :: Liste";
        $gestionLog->addLogInfo($user, $module, $message, $ip);

        // Si branche enrgistrer alors rechercher le projet encours pour mettre en avant
        // sinon afficher la liste des activités de la branche
        $branche = $utilisateur->getBranche();
        if ($branche){
            $projets = $this->projetRepository->findEncours($branche->getId());
            $activites = $activiteRepository->findByBranche($branche->getId());
        }else{
            $projets = new Projet();
            $activites = $activiteRepository->findByUser($user,['id'=>'DESC']);
        }


        return $this->render('activite/index.html.twig', [
            'activites' => $activites,
            'user' => $user,
            'projets' => $projets
        ]);
    }

    /**
     * @Route("/new/{projet}", name="activite_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger, GestionActivite $gestionActivite, GestionLog $gestionLog): Response
    {
        // Recuperation de l'utilisateur
        $user = $this->getUser();
        $projetId = $request->get('projet');

        // Enregistrement du log Info
        $ip = $request->getClientIp();
        $message = $user->getUsername()." a tenté d'enregistrer une activité ";
        $module = "Activite :: New";
        $gestionLog->addLogInfo($user, $module, $message, $ip);

        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Recupération du media
            $mediaFile = $form->get('media')->getData();
            //$taille = round($mediaFile->getSize() / 1000000,1);

            // Traitement du fichier s'il a été telechargé
            if ($mediaFile){
                $originalFileName = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFileName);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$mediaFile->guessExtension();

                $extension = $mediaFile->guessExtension();
                $type = $gestionActivite->mediaType($extension);

                // Deplacement du fichier dans le repertoire dedié
                try {
                    $mediaFile->move(
                        $this->getParameter('media_directory'),
                        $newFilename
                    );
                }catch (FileException $e){

                }

                $activite->setMediaType($type);
                $activite->setMedia($newFilename);
                $activite->setUser($user);
            }

            // Recherche du projet concrné puis enregsitrement
            $projet = $this->projetRepository->findOneById($projetId);
            $activite->setProjet($projet);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activite);
            $entityManager->flush();

            // Enregistrement du log Info
            $message = $user->getUsername()." a enregistré l'activité ID:".$activite->getId();
            $module = "Activite :: New";
            $gestionLog->addLogInfo($user, $module, $message, $ip);

            return $this->redirectToRoute('activite_show',['id'=>$activite->getId()]);
        }

        return $this->render('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
            'media' => 'Télécharger le media',
            'projetID' => $projetId,
            'projet' => $this->projetRepository->findOneById($projetId)
        ]);
    }

    /**
     * @Route("/{id}", name="activite_show", methods={"POST","GET"})
     */
    public function show(Request $request, Activite $activite, ActiviteRepository $activiteRepository, UtilisateurRepository $utilisateurRepository, GestionLog $gestionLog): Response
    {
        $user = $this->getUser();
        $utilisateur = $utilisateurRepository->findByUser($user->getUsername());


        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $commentaire->setUser($user);
            $commentaire->setActivite($activite); //dd($commentaire);
            $entityManager->persist($commentaire);
            $entityManager->flush();


            // Enregistrement du log Info
            $ip = $request->getClientIp();
            $message = $user->getUsername()." a enregistré le commentaire ID:".$activite->getId();
            $module = "Commentaire :: New";
            $gestionLog->addLogInfo($user, $module, $message, $ip);

            return $this->redirectToRoute('publication_show',['id'=>$activite->getId()]);
        }

        // Enregistrement du log Info
        $ip = $request->getClientIp();
        $message = $user->getUsername()." a affiché l'activité ID:".$activite->getId();
        $module = "Activite :: Show";
        $gestionLog->addLogInfo($user, $module, $message, $ip);

        return $this->render('activite/show.html.twig', [
            'activite' => $activite,
            'activites' => $activiteRepository->findOneByUser($user),
            'commentaire' => $commentaire,
            'form' => $form->createView(),
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/{id}/edit/{projet}", name="activite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Activite $activite, GestionLog $gestionLog): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        // recuperation du l'id du projet
        $projetID = $request->get('projet');

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            // Enregistrement du log Info
            $ip = $request->getClientIp();
            $message = $user->getUsername()." a modifié  l'activité ID ". $activite->getId();
            $module = "Activite :: Edit";
            $gestionLog->addLogInfo($user, $module, $message, $ip);

            return $this->redirectToRoute('activite_index');
        }

        return $this->render('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
            'media' => 'Modifier le media',
            'menu_vertical' => 'publication',
            'projetID' => $projetID,
        ]);
    }

    /**
     * @Route("/{id}", name="activite_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Activite $activite, GestionLog $gestionLog): Response
    {
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activite);
            $entityManager->flush();

            // Enregistrement du log Info
            $ip = $request->getClientIp();
            $message = $user->getUsername()." a supprimé une activité";
            $module = "Activite :: Delete";
            $gestionLog->addLogInfo($user, $module, $message, $ip);
        }

        return $this->redirectToRoute('activite_index');
    }
}
