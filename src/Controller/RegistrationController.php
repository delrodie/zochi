<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/compte")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="compte_inscription")
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
        // Création du formulaire
        $user = new User();

        // Instancie le formulaire avec les contraintes par défaut, + la contrainte registration pour que
        // la saisie du mot de passe soit obligatoire
        $form = $this->createForm(UserType::class, $user, [
           'validation_groups' => ['user', 'registration']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // Verification de l'existence de compte
            $username = $user->getUsername();
            $verif = $userRepository->findOneBy(['username'=>$username]);
            if ($verif){
                $this->addFlash('danger', strtoupper($username)." existe déjà! Veuillez choisir un autre nom utilisateur ou si c'est le votre veuillez cliquer sur 'se connecter' au bas du formulaire");
                return $this->redirectToRoute('compte_inscription');
            }
            // Encodement du mot de passe
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            //Creation d'une adresse email par defaut
            $email = $username.'@scoutascci.org';
            $user->setEmail($email);

            $user->setRoles(['ROLE_USER']);

            // Enregistrement du me,bre en base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Votre compte a été crée avec succès. Veuillez vous connecter");

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/inscription.html.twig',[
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}