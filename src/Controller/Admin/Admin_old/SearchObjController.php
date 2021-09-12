<?php

namespace App\Controller\Admin\Admin_old;;

use App\Entity\Objet;
use App\Repository\ObjetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchObjController extends AbstractController
{
    #[Route('/admin/search_obj', name: 'admin_search_obj')]
    public function index(): Response
    {
        return $this->render('admin/search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }
    #[Route('/admin/{param}/new/obj', name: 'search_objet')]

    public function retrieveObj(
        Request $request,
        ObjetRepository $objetRepository
    ): Response {
        $objets = new Objet();
        $data = $request->request->get('data');
        $objets = $objetRepository->findByText($data);
        return $this->json($objets, 200, [], ['groups' => 'objet']);
    }

    #[Route('/admin/{param}/new/selobj', name: 'select_objet')]

    public function selectObj(
        Request $request,
        ObjetRepository $objetRepository
    ): Response {
        $objet = new Objet();
        $data = $request->request->get('data');
        $objet = $objetRepository->findOneById($data);
        dump($objet->getEmprunts());
        // modif bug findoneby (groups à ajouter ?)
        return $this->json($objet, 200, [], ['groups' => 'objet']);
    }
}