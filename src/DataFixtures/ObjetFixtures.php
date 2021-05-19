<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Objet;
use App\DataFixtures\LieuFixtures;
use App\DataFixtures\CatalogueFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\SousCategoriesFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ObjetFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $objets = [
            'marteau',
            'perceuse',
            'aspirateur',
            'casseroles',
            'robot',
            'livre',
            'jouet',
            'tondeuse',
        ];
        $pourcentCalcul = [1, 1.5, 2];
        $statut = ['En maintenance', 'Disponible'];

        for ($i = 0; $i < count($objets); $i++) {
            $objet = new Objet();
            $lieu = $this->getReference('lieu');
            $ssCategorie = $this->getReference('ssCategorie_' . $i);
            $catalogues = [
                $this->getReference('vert'),
                $this->getReference('bleu'),
            ];

            $objet
                ->setDenomination($objets[$i])
                ->setMarque($faker->word)
                ->setDescription('Un super objet')
                ->setValeurAchat(rand(20, 120))
                ->setCoefUsure(rand(1, 5))
                ->setPourcentCalcul($faker->randomElement($pourcentCalcul))
                ->setVitrine($faker->boolean(50))
                ->setSousCategorie($ssCategorie)
                ->setLieu($lieu)
                ->setCatalogue($faker->randomElement($catalogues))
                ->setStatut($faker->randomElement($statut))
                ->setObservation('Etat moyen');
            $this->addReference('objet_' . $i, $objet);
            $manager->persist($objet);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            LieuFixtures::class,
            SousCategoriesFixtures::class,
            CatalogueFixtures::class,
            SousCategoriesFixtures::class,
        ];
    }
}