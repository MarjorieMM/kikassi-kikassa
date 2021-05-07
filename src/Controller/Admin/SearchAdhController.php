<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use App\Entity\SousCategorie;
use App\Entity\SuperAdmin;
use App\Repository\AdherentRepository;
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
        Request $request,
        AdherentRepository $adherentRepository,
        SuperAdminRepository $superAdminRepository
    ): Response {
        $Sadh = new Adherent();
        $Sadmin = new SuperAdmin();
        $data = $request->request->get('data');
        $Sadh = $adherentRepository->findById($data);
        $Sadmin = $superAdminRepository->findById($data);
        $Sperson = $Sadh ? ['adherent' => $Sadh] : ['admin' => $Sadmin];

        return $this->json($Sperson, 200, [], ['groups' => 'person']);
    }
}