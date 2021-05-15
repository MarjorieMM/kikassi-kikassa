<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LieuFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $lieu = new Lieu();

        $lieu
            ->setNom('Association Kikassi Kikassa')
            ->setAdresse('7 rue de la Roquette')
            ->setCp('13200')
            ->setVille('ARLES')
            ->setTelephone('0749000787')
            ->setFermeture(0);

        $this->addReference('lieu', $lieu);
        $manager->persist($lieu);

        $manager->flush();
    }
}