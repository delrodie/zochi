<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\UtilisateurRepository;
use App\Utils\GestionActivite;
use App\Utils\GestionLog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function show(Request $request, Activite $activite, GestionActivite $gestionActivite, UtilisateurRepository $utilisateurRepository, GestionLog $gestionLog)
    {
        $compteur = $gestionActivite->compteurVue($activite->getId());
        if (!$compteur) return $this->redirectToRoute('homepage');


        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        //Utilisateur
        $user = $this->getUser();
        if (!$user) {
            $user = null;
            $utilisateur = null;
        }
        else{
            $utilisateur = $utilisateurRepository->findByUser($user->getUsername());
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $commentaire->setUser($user);
                $commentaire->setActivite($activite); //dd($commentaire);
                $entityManager->persist($commentaire);
                $entityManager->flush();

                // Enregistrement du log Info
                $ip = $request->getClientIp();
                $message = $user->getUsername()." a commenté l'activité ID :".$activite->getId();
                $module = "Commentaire :: New";
                $gestionLog->addLogInfo($user, $module, $message, $ip);

                return $this->redirectToRoute('publication_show',['id'=>$activite->getId()]);
            }
        }

        return $this->render('publication/show.html.twig',[
            'activite' => $activite,
            'user' => $user,
            'utilisateur' => $utilisateur,
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }
}
