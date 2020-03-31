<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/utilisateur")
 */
class UtilisateurController extends AbstractController
{
    /**
     * @Route("/", name="utilisateur_index", methods={"GET"})
     */
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="utilisateur_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger, UtilisateurRepository $utilisateurRepository): Response
    {
        $user = $this->getUser();

        // Verification de l'existence du profile, si oui redirection modification
        $verif = $utilisateurRepository->findOneByUser($user);
        if ($verif) return $this->redirectToRoute('utilisateur_edit',['id'=>$verif->getId(),'user'=>$user->getUsername()]);


        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();

            // recuperation du fichier
            $avatarFile = $form->get('avatar')->getData();

            // Traitement du fichier s'il est a été téléchargé
            if ($avatarFile){
                $originalFileName = pathinfo($avatarFile->getClientoriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFileName);
                $newFilename = $safeFilename.'-'.uniqid().'-'.$avatarFile->guessExtension();

                try {
                    $avatarFile->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e){

                }

                $utilisateur->setAvatar($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $profile = $utilisateur->getNom().' '.$utilisateur->getPrenoms();
            $slug = $slugify->slugify($profile);
            $utilisateur->setSlug($slug);
            $utilisateur->setUser($user);
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('utilisateur_show',['user'=>$user->getUsername()]);
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
            'avatarLibelle' => "Telechargez votre avatar",
            'menu_vertical' => 'parametre'
        ]);
    }

    /**
     * @Route("/{user}", name="utilisateur_show", methods={"GET"})
     */
    public function show($user, UtilisateurRepository $utilisateurRepository, UserRepository $userRepository): Response
    {
        $userEntity = $userRepository->findOneByUsername($user);
        $utilisateur = $utilisateurRepository->findOneByUser($userEntity);
        if (!$utilisateur) return $this->redirectToRoute('utilisateur_new');
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
            'user' => $userEntity,
            'menu_vertical' => 'profile',
        ]);
    }

    /**
     * @Route("/{id}/edit-{user}", name="utilisateur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Utilisateur $utilisateur, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // recuperation du fichier
            $avatarFile = $form->get('avatar')->getData();

            // Traitement du fichier s'il est a été téléchargé
            if ($avatarFile){
                $originalFileName = pathinfo($avatarFile->getClientoriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFileName);
                $newFilename = $safeFilename.'-'.uniqid().'-'.$avatarFile->guessExtension();

                try {
                    $avatarFile->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e){

                }

                $utilisateur->setAvatar($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('utilisateur_show',['id'=>$utilisateur->getId(),  'user'=>$user->getUsername()]);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
            'avatarLibelle' => "Telechargez votre avatar",
            'menu_vertical' => 'parametre',
            'user' => $user
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateur_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Utilisateur $utilisateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('utilisateur_index');
    }
}
