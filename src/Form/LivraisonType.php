<?php

namespace App\Form;

use App\Entity\Commands;
use App\Entity\Livreur;
use App\Entity\Livraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateLiv')
            ->add('prixLiv')
            ->add('livreur', EntityType::class, [

                'class' => Livreur::class,

                'choice_label' => function ($livreur) {
                    if ($livreur->getEtat() === "Disponible") {
                        return $livreur->getNom();
                    }else{
                        return 'Non disponible ';
                    }
                }
            ])        
            
          ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
