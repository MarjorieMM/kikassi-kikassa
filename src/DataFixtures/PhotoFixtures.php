<?php

namespace App\DataFixtures;

use App\Entity\Photo;
use App\DataFixtures\ObjetFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class PhotoFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        $link = "../photos/";
        $liens = ["marteau.jpg", "perceuse.jpg", "aspirateur.jpg", "casseroles.jpg", "robot.jpg", "livre.jpg", "jouet.jpg", "tondeuse.jpg"];

        for ($i = 0; $i < 8; $i++) {
            $objet = $this->getReference('objet_' . $i);
            $photo = new Photo();

            $photo->setLien($link . $liens[$i])
                ->setObjet($objet);

            $manager->persist($photo);
        }


        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            ObjetFixtures::class,
        ];
    }
}
