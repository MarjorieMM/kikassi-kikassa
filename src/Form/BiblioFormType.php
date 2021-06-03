<?php

namespace App\Form;

use App\Entity\AdhesionBibliotheque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BiblioFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie_fourmi', ChoiceType::class, [
                'label' => 'Catégorie fourmi',
                'placeholder' => 'Choisir une Catégorie Fourmi',
                'choices' => [
                    'Verte' => 'Verte',
                    'Bleue' => 'Bleue',
                    'Dorée' => 'Dorée',
                ],
            ])
            ->add('depot_permanent', ChoiceType::class, [
                'label' => 'Montant du dépôt de garantie versé',
                'placeholder' => 'Montant du dépôt de garantie versé',
                'choices' => [
                    '0' => 0,
                    '30' => 30,
                    '60' => 60,
                    '90' => 90,
                    '120' => 120,
                    '150' => 150,
                    '180' => 180,
                    '210' => 210,
                ],
            ])
            ->add('fin_rc', DateType::class, [
                'label' => 'Date de fin de Resposabilité Civile',
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' =>
                    'Date de fin de la responsabilité civile (si donnée), format : 10/05/1950',
                ],
                'input' => 'datetime',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'required' => false,
            ])
            ->add('justif_identite', ChoiceType::class, [
                'label' => 'Justificatif d\'identité remis',
                'placeholder' => 'Justificatif d\'identité remis ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
            ])
            ->add('justif_domicile', ChoiceType::class, [
                'label' => 'Justificatif de domicile remis',
                'placeholder' => 'Justificatif de domicile remis ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' =>
                '<div class="btn-text px-2">Valider l\'inscription <br> à la Bibliothèque</div>',
                'label_html' => true,
                'attr' => ['class' => 'envoi-btn font-raleway'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AdhesionBibliotheque::class,
        ]);
    }
}