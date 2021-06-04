<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use Symfony\Component\Form\AbstractType;
use App\Repository\SousCategorieRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = $options['categories'];
        $sousCategories = $options['sousCategories'];

        $builder->add('categories', EntityType::class, [
            'placeholder' => 'Choisir une catégorie',
            'class' => Categorie::class,
            // 'choices' => $categories->getNomCategorie(),
            'choice_label' => 'nom_categorie',
        ]);
        $builder->add('souscategories', EntityType::class, [
            'placeholder' => 'Choisir une sous-catégorie',
            'class' => SousCategorie::class,
            'choice_label' => 'nom_ss_categorie',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
        $resolver->setRequired(['categories', 'sousCategories']);
    }
}