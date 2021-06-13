<?php

namespace App\Controller\Front;

use App\Repository\AdherentRepository;
use App\Repository\ObjetRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShowProductsController extends AbstractController
{
    #[Route('/', name: 'front_show_products')]
    public function index(ObjetRepository $repo, AdherentRepository $adhRepo): Response
    {
        $objets = $repo->findAll();
        $adherents = $adhRepo->findAll();

        return $this->render('front/show_products/index.html.twig', [
            'controller_name' => 'ShowProductsController',
            'objets' => $objets,
            'adherents' => $adherents
        ]);
    }
}