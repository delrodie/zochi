<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Le titre de l'article", 'autocomplete'=>'off', 'tabindex'=>0]])
            ->add('imageFile', VichImageType::class,['required'=>false,'allow_delete'=>true,'download_label'=>'...'])
            ->add('content', TextareaType::class,['attr'=>['class'=>'form-control', 'rows'=>5 ,'placeholder'=>"Description"]])
            //->add('tags', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>'les mots clés','autocomplete'=>'off']])
            //->add('createdAt')
            //->add('updatedAt')
            //->add('statut', CheckboxType::class,['attr'=>['class'=>'form-input-custom'],'required'=>false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
