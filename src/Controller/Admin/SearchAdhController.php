<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use App\Entity\AdhesionBibliotheque;
use App\Entity\SousCategorie;
use App\Entity\SuperAdmin;
use App\Repository\AdherentRepository;
use App\Repository\AdhesionBibliothequeRepository;
use App\Repository\EmpruntRepository;
use App\Repository\SousCategorieRepository;
use App\Repository\SuperAdminRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchAdhController extends AbstractController
{
    // #[Route('/admin/search', name: 'admin_search')]
    // public function index(): Response
    // {
    //     return $this->render('admin/search/index.html.twig', [
    //         'controller_name' => 'SearchController',
    //     ]);
    // }
    #[Route('/admin/{param}/new/adh', name: 'search_adherent')]

    public function retrieveAdh(
        Request $request,
        AdherentRepository $adherentRepository,
        SuperAdminRepository $superAdminRepository
    ): Response {
        $adhs = new Adherent();
        $admin = new SuperAdmin();
        $data = $request->request->get('data');
        $adhs = $adherentRepository->findByNomPrenom($data);
        $admin = $superAdminRepository->findByNomPrenom($data);
        $tab = ['adherent' => $adhs, 'admin' => $admin];

        return $this->json($tab, 200, [], ['groups' => 'person']);
    }

    #[Route('/admin/{param}/new/sel', name: 'select_adherent')]

    public function selectAdh(
        $param,
        Request $request,
        AdherentRepository $adherentRepository,
        SuperAdminRepository $superAdminRepository,
        EmpruntRepository $empruntRepository
    ): Response {
        $selectedAdh = new Adherent();
        $selectedAdmin = new SuperAdmin();
        $data = $request->request->get('data');
        $selectedAdh = $adherentRepository->findOneById($data);
        $selectedAdmin = $superAdminRepository->findOneById($data);
        $selectedPerson = $selectedAdh
            ? [
                'adherent' => $selectedAdh,
                'param' => $param,
                'biblio' => $selectedAdh->getAdhesionBibliotheque(),
                'roles' => $selectedAdh->getAdhesionBibliotheque()->getRoles(),
            ]
            : ['admin' => $selectedAdmin, 'param' => $param];

        return $this->json(
            $selectedPerson,
            200,
            [],
            ['groups' => ['person', 'objet']]
        );
    }
}