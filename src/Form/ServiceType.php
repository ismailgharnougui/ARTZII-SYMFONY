<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a title']),
                ],
            ])
            ->add('description',TextareaType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a description']),
                ],
            ])
            ->add('date_annonce', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Publish Date',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a date']),
                ],

            ])
            ->add('adresse',TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a adresse']),
                ],
            ])
            ->add('categorie',TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a categorie']),
                ],
            ])
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
