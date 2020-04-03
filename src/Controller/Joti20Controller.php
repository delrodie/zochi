<?php

namespace App\Controller;

use App\Entity\Joti;
use App\Repository\JotiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Joti20Controller extends AbstractController
{
    /**
     * @Route("/joti20", name="joti20")
     */
    public function index(JotiRepository $jotiRepository)
    {
        // Connexion au login
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash("danger", "Veuillez vous connecter à votre compte ou <a href=\"/compte/inscription\">créez en un</a> <br> Puis accedez au site de JOTI");
            return $this->redirectToRoute('app_login');
        }

        $joti = new Joti();
        $em = $this->getDoctrine()->getManager();

        $date = date('Y-m-d H:i:s', time());
        $joti->setDate($date);
        $joti->setNombre(1);
        $joti->setUser($user->getUsername());

        $em->persist($joti);
        $em->flush();

        return $this->redirect('https://www.jotajoti.info/fr');
    }

    /**
     * @Route("/joti20/liste", name="joti20_liste")
     */
    public function liste(JotiRepository $jotiRepository)
    {
        $participants = $jotiRepository->findBy([],['id'=>'DESC']);

        return $this->render('joti20/index.html.twig', compact('participants'));
    }
}
