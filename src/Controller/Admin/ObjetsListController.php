<?php

namespace App\Controller\Admin;

use App\Entity\Objet;
use App\Entity\Photo;
use App\Entity\Adherent;
use App\Form\ObjetFormType;
use App\Form\SearchFormType;
use App\Entity\SousCategorie;
use App\Form\CategorieFormType;
use App\Repository\ObjetRepository;
use App\Repository\AdherentRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SousCategorieRepository;
use App\Repository\SuperAdminRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ObjetsListController extends AbstractController
{
    #[Route('/admin/objets/list', name: 'admin_objets_list')]
    public function index(ObjetRepository $repo): Response
    {
        $objets = $repo->findAll();
        $route = 'down';

        return $this->render('admin/lists/objets_list.html.twig', [
            'controller_name' => 'ObjetsListController',
            'objets' => $objets,
            'route' => $route,
            'section' => 'section-objets',
            'return_path' => 'menu-objet',
            'color' => 'objets-color',
        ]);
    }
    #[Route('/admin/details/objet/{slug}', name: 'admin_details_objet')]
    public function showDetails(
        $slug,
        ObjetRepository $objetRepository,
        Objet $objet
    ): Response {
        // $objet = $objetRepository->findOneBySlug($slug);

        return $this->render('admin/pages_details/details_objet.html.twig', [
            'controller_name' => 'DetailsObjetController',
            'objet' => $objet,
            'return_path' => 'menu-objet',
            'section' => 'section-objets',
            'color' => 'objets-color',
        ]);
    }

    #[Route('/admin/objets/list/{obj}/{order}', name: 'admin_objets_list_sort')]
    public function sortDataD($obj, $order, ObjetRepository $repo): Response
    {
        if ($order == 'up') {
            $objets = $repo->findBy([], [$obj => 'DESC']);
            $route = 'down';
        } else {
            $objets = $repo->findBy([], [$obj => 'ASC']);
            $route = 'up';
        }

        return $this->render('admin/lists/objets_list.html.twig', [
            'controller_name' => 'ObjetsListController',
            'objets' => $objets,
            'route' => $route,
            'section' => 'section-objets',
            'return_path' => 'menu-objet',
            'color' => 'objets-color',
        ]);
    }

    #[Route('/admin/objets/new', name: 'admin_objets_new')]

    public function newObjet(
        Request $request,
        EntityManagerInterface $manager,
        AdherentRepository $adherentRepository,
        SousCategorieRepository $ssCatRepository,
        SuperAdminRepository $superAdminRepository
    ): Response {
        $objet = new Objet();

        $formCat = $this->createForm(CategorieFormType::class);
        $form = $this->createForm(ObjetFormType::class, $objet);

        ///////////////////
        //Partie recherche de l'emprunteur

        $searchAdh = $request->query->get('adh');
        $isAdh = $adherentRepository->findByNomPrenom(
            $searchAdh
        );
        $isAdmin = $superAdminRepository->findByNomPrenom(
            $searchAdh
        );
        $adherents = ['adh' => $isAdh, 'sadmin' => $isAdmin];

        if ($request->query->get('previewadh')) {
            return $this->render('admin/forms/_searchAdherent.html.twig', [
                'adherents' => $adherents
            ]);
        }

        ///////////////////

        if ($request->query->get('previewadh')) {
            return $this->render('admin/forms/_searchAdherent.html.twig', [
                'adherents' => $adherents
            ]);
        }

        $adherent = $adherentRepository->findOneById(
            $request->request->get('adherent')
        );
        $ssCat = $ssCatRepository->findOneById(
            $request->request->get('ss_cat')
        );

        $objet->setAdherent($adherent);
        $objet->setSousCategorie($ssCat);

        $form->handleRequest($request);

        $submitted = $form->isSubmitted() ? 'was-validated' : '';

        if ($form->isSubmitted() && $form->isValid()) {
            $directory = 'photos';
            $file = $form['photos']->getData();

            foreach ($file as $photo) {
                $extension = $photo->guessExtension();
                if (!$extension) {
                    $extension = 'bin';
                }

                $photoLien =
                    $objet->getDenomination() .
                    rand(1, 9999) .
                    '.' .
                    $extension;
                $photo->move($directory, $photoLien);

                $img = new Photo();
                $img->setLien($photoLien);
                $img->setObjet($objet);
                $manager->persist($img);
            }

            $objet->setStatut('Disponible');
            $manager->persist($objet);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le nouvel objet : {$objet->getDenomination()} {$objet->getMarque()} a bien été créé"
            );
            return $this->redirectToRoute('admin_details_objet', [
                'slug' => $objet->getSlug(),
            ]);
        }

        return $this->render('admin/forms/objets_new.html.twig', [
            'controller_name' => 'ObjetsListController',
            'arrow' => true,
            'section' => 'section-objets',
            'return_path' => 'menu-objet',
            'color' => 'objets-color',
            'form' => $form->createView(),
            // 'formSearch' => $formSearch->createView(),
            'submitted' => $submitted,
            'formCat' => $formCat->createView(),
        ]);
    }

    #[Route('/admin/objets/new/cat', name: 'admin_objets_cat')]

    public function getCats(
        Request $request,
        SousCategorieRepository $ssCatRepo
    ): Response {
        $ssCats = new SousCategorie();
        $data = $request->request->get('data');
        $ssCats = $ssCatRepo->findBy(['categorie' => $data]);
        return $this->json($ssCats, 200, [], ['groups' => 'categorie']);
    }
}
