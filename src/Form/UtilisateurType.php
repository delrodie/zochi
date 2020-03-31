<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'attr'=>['class'=>'payment-input', 'placeholder'=>"utilisateur.nom", 'autocomplete'=>"off"],
                'label'=>'utilisateur.labelNom'
            ])
            ->add('prenoms', TextType::class,[
                'attr'=>['class'=>'payment-input', 'placeholder'=>"utilisateur.prenoms", 'autocomplete'=>"off"],
                'label' => 'utilisateur.labelPrenoms'
            ])
            ->add('dateNaissance', TextType::class,[
                'attr'=>['class'=>'payment-input datepicker-here', 'data-language'=>'fr' , 'placeholder'=>"utilisateur.datenaissance", 'autocomplete'=>"off"],
                'label' => 'utilisateur.labelDateNaissance'
            ])
            ->add('genre', ChoiceType::class,[
                'attr'=>['class'=>'wide', 'style'=>'display:none'],
                'choices'=> ['Feminin'=>'femme', 'Masculin'=>'Homme'],
                'multiple'=>false,
                'expanded'=>false,
                'label'=>'utilisateur.labelGenre'
            ])
            ->add('telephone', TelType::class,[
                'attr'=>['class'=>'payment-input', 'placeholder'=>"utilisateur.tel", 'autocomplete'=>"off"],
                'label'=>'utilisateur.labelTelephone',
                'required'=>false
            ])
            ->add('email', EmailType::class,[
                'attr'=>['class'=>'payment-input', 'placeholder'=>'utilisateur.email', 'autocomplete'=>"off"],
                'label'=>'utilisateur.labelEmail',
                'required'=>false
            ])
            ->add('statut', ChoiceType::class,[
                'attr'=>['class'=>'wide', 'style'=>'display: none'],
                'choices' =>['Jeune'=>'Jeune', 'Adulte'=>'Adulte'],
                'multiple'=>false,
                'expanded'=>false,
                'label'=>'utilisateur.labelStatut'
            ])
            ->add('domicile', TextType::class,[
                'attr'=>['class'=>'payment-input', 'placeholder'=>"utilisateur.domicile", 'autocomplete'=>"off"],
                'label'=>'utilisateur.labelDomicile',
                'required'=>false
            ])
            ->add('biographie', TextareaType::class,[
                'attr'=>['class'=>'replt-comnt', 'placeholder'=>'utilisateur.biographie'],
                'label'=>'utilisateur.labelBiographie'
            ])
            ->add('avatar', FileType::class,[
                'mapped' => false,
                'required'=>false,
                'constraints'=>[
                    new File([
                        'maxSize'=>'500k',
                        'mimeTypes'=>[
                            'image/png',
                            'image/jpeg',
                            'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Votre photo doit être au format png/jpg',
                        'maxSizeMessage'=> "Votre photo est trop lourde"
                    ])
                ]
            ])
            //->add('slug')
            //->add('user')
            ->add('branche', EntityType::class,[
                'attr'=>['class'=>'wide', 'style'=>'display:none'],
                'class'=> 'App\Entity\Branche',
                'query_builder' => function(EntityRepository $er){
                        return $er->liste();
                },
                'choice_label' => 'nom',
                'label' => 'utilisateur.branche',
                'required'=>false
            ])
            ->add('region', EntityType::class,[
                'attr'=>['class'=>'wide', 'style'=>'display:none'],
                'class'=>'App\Entity\Region',
                'query_builder' => function(EntityRepository $er){
                        return $er->liste();
                },
                'choice_label' => 'nom',
                'label'=> 'utilisateur.region',
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
