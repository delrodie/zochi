<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Si le formualire concerne une inscription de l'internaute
        // Sinon le formulaire par defaut (enregistrement de l'administrateur)
        if (in_array('registration', $options['validation_groups'])){
            $builder
                ->add('username', TextType::class,['attr'=>['class'=>'title-discussion-input','placeholder'=>"security.Username",'autocomplete'=>"off", 'onkeypress'=>"return verif(event);", 'pattern'=>"^[a-zA-Z0-9_]{3,16}$"]])
                ->add('plainPassword', RepeatedType::class,[
                    'type'=> PasswordType::class,
                    'first_options' => ['attr'=>['class'=>'title-discussion-input','placeholder'=>'security.Password']],
                    'second_options' => ['attr' =>['class'=>'title-discussion-input','placeholder'=>'security.ConfirmPassword'] ]
                ])
                ;
        }else{
            $builder
                ->add('email',EmailType::class,['attr'=>['class'=>'form-control','placeholder'=>"security.Email",'autocomplete'=>"off"]])
                ->add('username', TextType::class,['attr'=>['class'=>"form-control",'placeholder'=>"security.Username",'autocomplete'=>"off"]])
                ->add('password', PasswordType::class,['attr'=>['class'=>'form-control','placeholder'=>"security.Password"]])
                ->add('roles', ChoiceType::class,[
                    'choices'=>['Utilisateur'=>'ROLE_USER','Administrateur'=>'ROLE_ADMIN'],
                    'multiple'=>true,
                    'expanded'=>true
                ])
            ;
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
