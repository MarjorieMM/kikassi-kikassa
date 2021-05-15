<?php

namespace App\Form;

use App\Entity\Adherent;
use App\Entity\AdhesionBibliotheque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdherentUtilisateurMdpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motDePasse', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'attr' => ['placeholder' => 'Tapez votre mot de passe ici']
            ])
            ->add('passwordConfirm', PasswordType::class, [
                'label' => 'Mot de passe pour la confirmation',
                'attr' => ['placeholder' => 'Tapez votre mot de passe ici pour la confirmation']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AdhesionBibliotheque::class,
        ]);
    }
}
