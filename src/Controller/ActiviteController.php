<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use App\Utils\GestionActivite;
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
    /**
     * @Route("/", name="activite_index", methods={"GET"})
     */
    public function index(ActiviteRepository $activiteRepository): Response
    {
        $user = $this->getUser();
        return $this->render('activite/index.html.twig', [
            'activites' => $activiteRepository->findByUser($user,['id'=>'DESC']),
            'user' => $user
        ]);
    }

    /**
     * @Route("/new", name="activite_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger, GestionActivite $gestionActivite): Response
    {
        // Recuperation de l'utilisateur
        $user = $this->getUser();
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Recupération du media
            $mediaFile = $form->get('media')->getData();

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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activite);
            $entityManager->flush();

            return $this->redirectToRoute('activite_show',['id'=>$activite->getId()]);
        }

        return $this->render('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
            'media' => 'Télécharger le media'
        ]);
    }

    /**
     * @Route("/{id}", name="activite_show", methods={"GET"})
     */
    public function show(Activite $activite, ActiviteRepository $activiteRepository): Response
    {
        $user = $this->getUser();
        //$activites = $activiteRepository->findOneByUser($user); dd($activites);
        return $this->render('activite/show.html.twig', [
            'activite' => $activite,
            'activites' => $activiteRepository->findOneByUser($user),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="activite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Activite $activite): Response
    {
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activite_index');
        }

        return $this->render('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
            'media' => 'Modifier le media'
        ]);
    }

    /**
     * @Route("/{id}", name="activite_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Activite $activite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('activite_index');
    }
}
