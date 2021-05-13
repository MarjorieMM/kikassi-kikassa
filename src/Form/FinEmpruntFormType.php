<?php

namespace App\Form;

use App\Entity\Emprunt;
use App\Form\ObjetFormType;
use App\Form\EmpruntFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FinEmpruntFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dispo', ChoiceType::class, [
                'label' =>
                    'L\' état de l\'objet permet-il sa remise en ligne ? :',
                'placeholder' => 'Choisir...',
                'choices' => [
                    'Oui' => 'disponible',
                    'Non' => 'en maintenance',
                ],
                'attr' => [
                    'class' => 'form-control select-height mb-4',
                    'id' => 'statut-objet',
                    'name' => 'dispo',
                ],
                'required' => true,
            ])
            ->add('observations', TextareaType::class, [
                'label' =>
                    'Veuillez entrer une observation sur l\'état de l\'objet (celle-ci sera reprise sur le bon de retour)',
                'attr' => [
                    'class' => 'form-control mb-4',
                    'name' => 'obs',
                    'id' => 'obs-objet',
                ],
                'required' => true,
            ])
            ->add('paiement', ChoiceType::class, [
                'attr' => [
                    'class' =>
                        'custom-control custom-radio custom-control-inline',
                ],
                'label' =>
                    "Le paiement de l\'emprunt a-t'il été effectué ce jour ?",
                'label_attr' => [
                    'class' => 'mr-5 py-3',
                    // 'for' => 'penalites-radio2',
                ],
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'choice_attr' => [
                    'Oui' => [
                        'class' => 'custom-control-input mb-4',
                        'id' => 'paiement-radio1',
                        'name' => 'paiement',
                    ],
                    'Non' => [
                        'class' => 'custom-control-input mb-4',
                        'id' => 'paiement-radio2',
                        'name' => 'paiement',
                    ],
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('penalites', ChoiceType::class, [
                'attr' => [
                    'class' =>
                        'custom-control custom-radio custom-control-inline',
                ],
                'label' =>
                    "Le paiement des pénalités a-t'il été effectué ce jour ?",
                'label_attr' => [
                    'class' => 'mr-5',
                    // 'for' => 'penalites-radio2',
                ],
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'choice_attr' => [
                    'Oui' => [
                        'class' => 'custom-control-input mb-4',
                        'id' => 'penalites-radio1',
                        'name' => 'penalites',
                    ],
                    'Non' => [
                        'class' => 'custom-control-input mb-4',
                        'id' => 'penalites-radio2',
                        'name' => 'penalites',
                    ],
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' =>
                    '<div class="font-raleway-small btn-text">Valider</div>',
                'label_html' => true,
                'attr' => ['class' => 'btn btn-success btn-md btn-block'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}