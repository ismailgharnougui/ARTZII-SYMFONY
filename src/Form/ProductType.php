<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_produit',TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a title']),
                ],
            ])
            ->add('description',TextareaType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a title']),
                ],
            ])
            ->add('categorie',TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a title']),
                ],
            ])
            ->add('price',NumberType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a title']),
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Product Image',
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
