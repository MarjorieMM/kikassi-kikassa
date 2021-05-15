<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEmpruntMenuController extends AbstractController
{
    #[Route('/admin/emprunts', name: 'menu-emprunt')]
    public function index(): Response
    {
        return $this->render('admin/admin_menus/emprunts_menu.html.twig', [
            'controller_name' => 'AdminEmpruntMenuController',
            'color' => 'emprunts-color',
            'section' => 'section-emprunts',
            'return_path' => 'admin_main_menu',
            'items' => [
                [
                    'text' => "VALIDER LES RÉSERVATIONS D'OBJETS",
                    'icon' => 'check',
                    'link' => 'admin_emprunts_resa',
                ],
                [
                    'text' => "ENREGISTRER UN DÉPART D'OBJET",
                    'icon' => 'leave',
                    'link' => 'admin_emprunts_depart',
                ],
                [
                    'text' => "ENREGISTRER UN RETOUR D'OBJET",
                    'icon' => 'return',
                    'link' => 'admin_emprunts_retour',
                ],
                [
                    'text' => 'CRÉER UN NOUVEL EMPRUNT',
                    'icon' => 'basket',
                    'link' => 'admin_emprunts_new',
                ],
                [
                    'text' => 'MODIFIER / SUPPRIMER UN EMPRUNT',
                    'icon' => 'pen',
                    'link' => 'test',
                ],
                [
                    'text' => 'AFFICHER LES EMPRUNTS',
                    'icon' => 'show',
                    'link' => 'admin_emprunts_list',
                ],
            ],
        ]);
    }
}