<?php

namespace App\Form;

use App\Entity\Echange;
use App\Entity\Product;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EchangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('produit_echange', EntityType::class, [
                'class' => Product::class,
                'choices' => $options['products'],
                'choice_label' => 'nomProduit',
                'label' => 'Product to Exchange',
            ])
            ->add('produit_offert', EntityType::class, [
                'class' => Product::class,
                'choices' => $options['my_products'],
                'choice_label' => 'nomProduit',
                'label' => 'My Products',

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Echange::class,
            'my_products' => [],
            'products' => [],
        ]);
    }
}
