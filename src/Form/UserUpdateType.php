<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,['attr'=>['class'=>'form-control','placeholder'=>"security.Email",'autocomplete'=>"off"]])
            ->add('username', TextType::class,['attr'=>['class'=>"form-control",'placeholder'=>"security.Username",'autocomplete'=>"off"]])
            //->add('password', PasswordType::class,['attr'=>['class'=>'form-control','placeholder'=>"security.Password"]])
            ->add('roles', ChoiceType::class,[
                'choices'=>['Utilisateur'=>'ROLE_USER', 'ACN Branche'=>'ROLE_ACN    ', 'Administrateur'=>'ROLE_ADMIN'],
                'multiple'=>true,
                'expanded'=>true
            ])
            //->add('isActive')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
