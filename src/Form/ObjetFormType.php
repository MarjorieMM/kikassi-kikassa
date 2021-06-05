<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Objet;
use App\Entity\Adherent;
use App\Entity\Catalogue;
use App\Entity\Categorie;
use App\Entity\SousCategorie;
use App\Form\CategorieFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ObjetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('denomination', TextType::class, [
                'label' => '',
                'attr' => ['placeholder' => 'Dénomination de l\'objet'],
            ])
            ->add('marque', TextType::class, [
                'label' => '',
                'attr' => ['placeholder' => 'Marque de l\'objet'],
            ])
            ->add('description', TextareaType::class, [
                'label' => '',
                'attr' => ['placeholder' => 'Description de l\'objet'],
            ])
            ->add('photos', FileType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Photos de l\'objet'],
                'multiple' => true,
            ])

            ->add('valeur_achat', IntegerType::class, [
                'label' => '',
                'attr' => ['placeholder' => 'Valeur du neuf en €'],
            ])
            ->add('coef_usure', ChoiceType::class, [
                'placeholder' => 'Coefficient d\'usure',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
            ])
            ->add('pourcent_calcul', ChoiceType::class, [
                'placeholder' => '% de calcul',
                'choices' => [
                    '1 %' => 1.0,
                    '1,5 %' => 1.5,
                    '2 %' => 2.0,
                ],
            ])
            ->add('vitrine', ChoiceType::class, [
                'placeholder' => 'Objet mis en vitrine',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
            ])
            ->add('lieu', EntityType::class, [
                'placeholder' => 'Choisir un lieu de stockage',
                'class' => Lieu::class,
                'choice_label' => 'nom',
            ])

            ->add('catalogue', EntityType::class, [
                'placeholder' => 'Choisir un catalogue',
                'class' => Catalogue::class,
                'choice_label' => 'nom_catalogue',
            ])
            ->add('observation', TextType::class, [
                'label' => '',
                'attr' => ['placeholder' => 'Observations'],
            ])
            ->add('save', SubmitType::class, [
                'label' =>
                '<div class="btn-text p-1 px-2">Ajouter <br> le nouvel objet</div>',
                'label_html' => true,
                'attr' => ['class' => 'envoi-btn font-raleway'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Objet::class,
        ]);
    }
}