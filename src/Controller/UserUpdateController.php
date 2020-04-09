<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/update")
 */
class UserUpdateController extends AbstractController
{
    /**
     * @Route("/{id}", name="user_update", methods={"POST","GET"})
     */
    public function update(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user, [
            'validation_groups' => ['user', 'change-role']
        ]);    //dd($form);
        $form->handleRequest($request); //dd($form);

        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }
        return $this->render('user_update/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/change-password", name="user_update_password", methods={"POST","GET"})
     */
    public function changepassword(Request $request, User $user)
    {
        $userRequest = $this->getUser();

        // Si le utilisateurs sont differents alors rediregiger vers le profile avec message d'erreur
        if ($user->getUsername() != $userRequest->getUsername()) {
            $this->addFlash(
                'danger',
                "Attention vous avez tenté de changer un mot de passe qui n'est pas le votre.
                Si vous penssez que c'est une erreur, contactez les administrateurs de la plateforme"
            );

            return $this->redirectToRoute('activite_index');
        }

        $form = $this->createForm(UserType::class, $user, [
            'validation_groups' => ['user', 'change-password']
        ]);    //dd($form);
        $form->handleRequest($request); //dd($form);

        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_show',['id'=>$user->getId()]);
        }
        return $this->render('user_update/password.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
