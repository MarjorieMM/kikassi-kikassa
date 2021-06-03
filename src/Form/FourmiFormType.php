<?php

namespace App\Form;

use App\Entity\AdhesionBibliotheque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FourmiFormType extends AbstractType
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
            'validation_groups' => ['fourmi'],
        ]);
    }
}