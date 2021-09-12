<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminObjetMenuController extends AbstractController
{
    #[Route('/admin/objets', name: 'menu-objet')]
    public function index(): Response
    {
        return $this->render('admin/admin_menus/objets_menu.html.twig', [
            'controller_name' => 'AdminObjetMenuController',
            'section' => 'section-objets',
            'color' => 'objets-color',
            'return_path' => 'admin_main_menu',
            'items' => [
                [
                    'text' => 'AJOUTER UN NOUVEL OBJET',
                    'icon' => 'new',
                    'link' => 'admin_objets_new',
                ],
                [
                    'text' => 'MODIFIER UN OBJET',
                    'icon' => 'pen',
                    'link' => 'admin_objets_select',
                ],
                [
                    'text' => 'AJOUTER / MODIFIER UN CATALOGUE',
                    'icon' => 'catalog',
                    'link' => 'test',
                ],
                [
                    'text' => 'LES CATÉGORIES / SOUS-CATÉGORIES',
                    'icon' => 'categories',
                    'link' => 'test',
                ],
                [
                    'text' => 'AFFICHER LES OBJETS',
                    'icon' => 'show',
                    'link' => 'admin_objets_list',
                ],
            ],
        ]);
    }
}
