<?php

namespace App\Controller\Front;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShowProductsController extends AbstractController
{

    #[Route('/{reactRouting}', name: 'index', priority: '-1', requirements: ['reactRouting' => '^(?!admin)(?!api).+'], defaults: ['reactRouting' => null])]

    /**
     * @Route("/{reactRouting}", name="index", priority="-1", requirements={"reactRouting"="^(?!admin)(?!api).+"}, defaults={"reactRouting": null})
     */

    public function index(): Response
    {

        return $this->render('front/show_products/index.html.twig', [
            'controller_name' => 'ShowProductsController',
        ]);
    }
}