<?php

namespace App\Form;

use App\Entity\Livreurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
class LivreursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


        ->add('numTel', TextType::class,[
            'required' => true,
            'constraints' => [ new Regex([
                'pattern' => '/^\d{8}$/',
                'message' => 'Le numero doit contenir 12 chiffes '
            ]),
                new NotBlank([
                    'message' => 'Veuillez saisir le numero de telephone ',
                ])
            ],
        ])
            ->add('nom', TextType::class,[
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le nom',
                    ])
                ],
            ])            ->add('prenom', TextType::class,[
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le prénom',
                    ])
                ],
            ])            
            ->add('regionLivreur', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Bizerte' => 'Bizerte',
                    'Tunis' => 'Tunis',
                    'Zaghouan' => 'Zaghouan',
                    'Beja' => 'Beja',
                    'Jandouba' => 'Jandouba',
                    'Kef' => 'Kef',
                    'Gasserine' => 'Gasserine',
                    'Ben Arous' => 'Ben Arous',
                    'Nabeul' => 'Nabeul',
                    'Tozeur' => 'Tozeur',
                    'Sousse' => 'Sousse',
                    'Monastir' => 'Monastir',
                    'Tataouine' => 'Tataouine',
                    'Kebili' => 'Kebili',
                    'Seliana' => 'Seliana',
                    'Sidi Bouzid' => 'Sidi Bouzid',
                    'Sfax' => 'Sfax',
                    'Kairouan' => 'Kairouan',
                    'Mednine' => 'Mednine',
                    'Gafsa' => 'Gafsa',
                    'Ariana' => 'Ariana',
                    'Mannouba' => 'Mannouba',



                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livreurs::class,
        ]);
    }
}
