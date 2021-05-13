<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Objet;
use App\Entity\Emprunt;
use App\Entity\Adherent;
use App\Entity\Catalogue;
use App\Form\SearchFormType;
use App\Entity\SousCategorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EmpruntFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_debut', DateType::class, [
                'label' => '',
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'Date de début, format : 10/05/1950',
                ],
                'input' => 'datetime',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
            ])
            ->add('date_fin', DateType::class, [
                'label' => '',
                'widget' => 'single_text',
                'attr' => ['placeholder' => 'Date de fin, format : 10/05/1950'],
                'input' => 'datetime',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
            ])
            ->add('emprunt_regle', ChoiceType::class, [
                'placeholder' => 'L\'emprunt est-il réglé ce jour ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' =>
                    '<div class="btn-text p-1 px-2">Enregister <br> l\'emprunt</div>',
                'label_html' => true,
                'attr' => ['class' => 'envoi-btn font-raleway'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}