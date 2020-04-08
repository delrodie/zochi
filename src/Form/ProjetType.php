<?php

namespace App\Form;

use App\Entity\Projet;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('theme', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"projet.placeholderTheme", 'autocomplete'=>"off"]])
            ->add('deroule', TextareaType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"projet.placeholderDeroule", 'rows'=>5]])
            //->add('createdAt')
            ->add('dateDebut', TextType::class,['attr'=>['class'=>'form-control datepicker-here my-1', 'placeholder'=>"projet.placeholderDebut", 'autocomplete'=>"off", 'data-language'=>'fr']])
            ->add('dateFin', TextType::class,['attr'=>['class'=>'form-control datepicker-here my-1', 'placeholder'=>"projet.placeholderFin", 'autocomplete'=>"off", 'data-language'=>'fr']])
            ->add('domaine', EntityType::class,[
                'attr'=>['class'=>'form-control domaine-select'],
                'class'=> 'App\Entity\Domaine',
                'query_builder' => function(EntityRepository $er){
                    return $er->liste();
                },
                'choice_label' => 'libelle',
                'label' => 'projet.labelDomaine',
                'required'=>true,
                'multiple' => true
            ])
            ->add('branche', EntityType::class,[
                'attr'=>['class'=>'wide', 'style'=>'display:none'],
                'class'=> 'App\Entity\Branche',
                'query_builder' => function(EntityRepository $er){
                    return $er->liste();
                },
                'choice_label' => 'nom',
                'label' => 'projet.labelBranche',
                'required'=>true
            ])
            //->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
