<?php

namespace App\Form;

use App\Entity\Commande;
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
            
            ->add('commande', EntityType::class, [

                'class' => Commande::class,

                'choice_label' => function ($commande) {
                if ($commande->isEtat() === false) {
                    return $commande->getId();
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
