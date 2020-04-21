<?php

namespace App\Form;

use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"activite.placeholderTitle", 'autocomplete'=>'off']])
            ->add('description', TextareaType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"activite.placeholderDescription", 'rows'=>5]])
            //->add('createdAt')
            ->add('media', FileType::class,[
                'mapped'=>false,
                'required' =>false,
                'constraints'=>[
                    new File([
                        'maxSize' => '100000k',
                        'mimeTypes' =>[
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                            'image/webp',
                            'video/mp4',
                            'video/ogg',
                            'video/webm',
                            'video/mpeg',
                            'video/3gpp',
                            'video/MP2T',
                            'video/x-mpegURL',
                            'video/x-msvideo',
                            'video/x-ms-wmv'
                        ],
                        'mimeTypesMessage' => "Votre fichier doit être de type image ou video"
                    ])
                ]
            ])
            //->add('mediaType')
            //->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
}
