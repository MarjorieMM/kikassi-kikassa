<?php

namespace App\DataFixtures;

use App\Entity\Catalogue;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class CatalogueFixtures extends Fixture
{
  
    public function load(ObjectManager $manager)
    {

    $vert = new Catalogue();
    $vert->setNomCatalogue("Catalogue Vert")
         ->setCategorieFourmi("Verte");

         
    $bleu = new Catalogue();
    $bleu->setNomCatalogue("Catalogue Bleu")
         ->setCategorieFourmi("Bleue");
      
        $this->addReference('vert', $vert);
    $this->addReference('bleu', $bleu);

    $manager->persist($vert, $bleu);
  

    $manager->flush();
                        }
                    }