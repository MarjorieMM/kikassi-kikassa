<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdherentsMenuController extends AbstractController
{
    #[Route('/admin/adherents', name: 'menu-adherent')]
    public function index(): Response
    {
        return $this->render('admin/admin_menus/adherents_menu.html.twig', [
            'controller_name' => 'AdminAdherentsMenuController',
            'return_path' => 'admin_main_menu',
            'section' => 'section-adherents',
            'color' => 'adherents-color',
            'items' => [
                [
                    'text' => 'INSCRIRE UN NOUVEL ADHÉRENT',
                    'icon' => 'plus',
                    'link' => 'admin_adherents_new',
                ],
                [
                    'text' => 'CHANGER STATUT FOURMI',
                    'icon' => 'ant',
                    'link' => 'admin_adherents_modif',
                    'param' => 'adherent-changement-fourmi',
                ],
                [
                    'text' => 'MODIFIER / RÉINSCRIRE UN ADHÉRENT',
                    'icon' => 'pen',
                    'link' => 'admin_adherents_modif',
                    'param' => 'adherent-reinscription',
                ],
                [
                    'text' => 'PASSER UN ADHÉRENT EN ADMIN',
                    'icon' => 'admin',
                    'link' => 'admin_adherents_modif',
                    'param' => 'adherent-passage-admin',
                ],
                [
                    'text' => 'AFFICHER LES ADHÉRENTS',
                    'icon' => 'show',
                    'link' => 'admin_adherents_list',
                ],
            ],
        ]);
    }
}
