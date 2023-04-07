<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;  
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;  
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;


class AjoutArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('artlib', TextType::class, [
            'label' => 'Nom de la pièce:',
            'attr' => ['class' => 'form-control']
        ])
        ->add('artdesc', TextareaType::class, [
            'label' => 'Description:',
            'attr' => ['class' => 'form-control']
        ]) 
        ->add('artdispo', NumberType::class, [
            'label' => 'Disponibilité:',
            'attr' => ['class' => 'form-control']
        ])
        ->add('artimg', FileType::class, [
            'label' => 'Image:',
            'required' => false,
            'attr' => ['class' => 'form-control']
        ])
        ->add('artprix', NumberType::class, [
            'label' => 'Prix:',
            'attr' => ['class' => 'form-control']
        ])
        ->add('catlib', TextType::class, [
            'label' => 'Catégorie de la pièce:',
            'attr' => ['class' => 'form-control']
        ])
        ->add('Ajouter', SubmitType::class, [
            'label' => 'Ajouter l\'article',
            'attr' => ['class' => 'btn btn-primary']
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
