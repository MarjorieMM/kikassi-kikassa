<?php

namespace App\Controller;

use App\Entity\Objet;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/{slug}/detail", name="home")
     */
    public function detailsObjet(Objet $objet, SousCategorieRepository $sousCategorie): Response
    {
        return $this->render('home/detailsObjet.html.twig', [
            'controller_name' => 'HomeController',
            'objet' => $objet
        ]);
    }
}
