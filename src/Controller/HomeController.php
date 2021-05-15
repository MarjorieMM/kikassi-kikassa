<?php

namespace App\Controller;

use Attribute;
use App\Entity\Objet;
use App\Entity\Emprunt;
use App\Entity\Adherent;
use App\Form\EmpruntType;
use App\Repository\ObjetRepository;
use App\Repository\PhotoRepository;
use App\Entity\AdhesionBibliotheque;
use App\Form\AdherentUtilisateurType;
use App\Repository\EmpruntRepository;
use App\Repository\AdherentRepository;
use App\Repository\CategorieRepository;
use App\Form\AdherentUtilisateurMdpType;
use App\Repository\SuperAdminRepository;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]

    public function index(ObjetRepository $objetRepository, PhotoRepository $photoRepository, CategorieRepository $categorieRepository, SousCategorieRepository $sousCategorieRepository): Response
    {
        $objets = $objetRepository->findAll();
        $photos = $photoRepository->findAll();
        $categories = $categorieRepository->findAll();
        $sousCategories = $sousCategorieRepository->findAll();

        //  dd($sousCategories);

        return $this->render('home/catalogue.html.twig', [
            'controller_name' => 'HomeController',
            'objets' => $objets,
            'photos' => $photos,
            'categories' => $categories,
            'sousCategories' => $sousCategories,
        ]);
    }

    #[Route('/{slug}/detail', name: 'objetDetail', methods: ['GET', 'POST'])]
    public function detailsObjet(Objet $objet, EmpruntRepository $empruntRepository, Request $request, AdherentRepository $adherentRepository, SuperAdminRepository $superAdminRepository): Response

    {
        // foreach ($request->attributes->all() as $attribute) {
        // if ($attribute == $objet) {
        //     dump($objet->getId());
        // }
        // dump($attribute);
        // }
        // dump($request->attributes->all());
        // dump($session->all());


        $emprunt = new Emprunt();
        $form = $this->createForm(EmpruntType::class, $emprunt);
        $form->handleRequest($request);
        // // Je récupère l'adhérent et je vérifie si c'est un adhérent ou super-admin
        // dump($this->getUser()->getId());
        if ($this->getUser()) {
            $adherentBibliotheque = $this->getUser()->getId();
            $adherent = $adherentRepository->findOneById($adherentBibliotheque);
            $adminBibliotheque = $this->getUser()->getId();
            $admin = $superAdminRepository->findOneById($adminBibliotheque);

            $adherent
                ? $emprunt->setAdherent($adherent)
                : $emprunt->setSuperAdmin($admin);
        }


        if ($form->isSubmitted() && $form->isValid()) {


            if ($objet) {
                if ($objet->getStatut() == 'Disponible' || $objet->getStatut() == 'Réservé') {

                    // j'envoie en bdd l'objet
                    $emprunt->setObjet($objet);
                    // Je set la date de réservation uniquement si l'emprunt ne débute pas le jour même, et je la met à aujourd'hui
                    $now = new \DateTime();
                    if ($emprunt->getDateDebut() > $now) {
                        $emprunt->setDateReservation($now);
                    }
                    if ($emprunt->getDateFin() < $emprunt->getDateDebut()) {
                        $this->addFlash(
                            'danger',
                            "La date de fin d'emprunt ne peut pas être avant la date de début"
                        );
                    }

                    // calcul du prix de l'emprunt :
                    $obj = $emprunt->getObjet();
                    $days = $emprunt
                        ->getDateDebut()
                        ->diff($emprunt->getDateFin())->days;
                    $prix =
                        (((($obj->getValeurAchat() *
                            $obj->getPourcentCalcul()) /
                            100) *
                            $obj->getCoefUsure()) /
                            5) *
                        $days;
                    $emprunt->setPrixEmprunt($prix);

                    // calcul du montant de dépôt de garantie à rajouter au dépôt permanent :
                    $finrc = $adherent
                        ->getAdhesionBibliotheque()
                        ->getFinRc();
                    $depot_perm = $adherent
                        ->getAdhesionBibliotheque()
                        ->getDepotPermanent();

                    if ($adherent) {
                        if ($finrc > $now) {
                            $depot_rajoute =
                                ($obj->getValeurAchat() *
                                    $obj->getCoefUsure()) /
                                5 /
                                3 -
                                $depot_perm;
                            dump('rc valide');
                        } else {
                            $depot_rajoute =
                                ($obj->getValeurAchat() *
                                    $obj->getCoefUsure()) /
                                5 -
                                $depot_perm;
                            dump('pas rc ou rc perimee');
                        }
                    } else {
                        $depot_rajoute = 0;
                        dump('est admin');
                    }

                    $emprunt->setDepotRajoute(
                        $depot_rajoute < 0 ? 0 : $depot_rajoute
                    );
                    // valeur provisoire à 0 pour réglé des bugs
                    // $emprunt->setDepotRajoute(0);

                    // le statut de l'emprunt est mis "en attente de validation"
                    $emprunt->setStatut("demande avant panier");

                    dump($emprunt);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($emprunt);
                    $entityManager->flush();

                    return $this->redirectToRoute('home');
                }
            }
        }
        dump($emprunt);

        return $this->render('home/detailsObjet.html.twig', [
            'controller_name' => 'HomeController',
            'objet' => $objet,
            'emprunt' => $empruntRepository,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/panier', name: 'panier')]
    public function panierIndex(EmpruntRepository $empruntRepository, ObjetRepository $objetRepository, SousCategorieRepository $sousCategorieRepository, CategorieRepository $categorieRepository): Response
    {
        // if ($this->getUser()) {
        //     $adherentBibliotheque = $this->getUser()->getId();
        //     $adherent = $adherentRepository->findOneById($adherentBibliotheque);
        //     $adminBibliotheque = $this->getUser()->getId();
        //     $admin = $superAdminRepository->findOneById($adminBibliotheque);

        //     if ($adherent) {
        //         $adherentAdmin = $adherent;
        //     } else {
        //         $adherentAdmin = $admin;
        //     }
        // }

        return $this->render('home/panier.html.twig', [
            'controller_name' => 'HomeController',
            'emprunts' => $empruntRepository->findAll(),
            // 'adherent' => $adherentAdmin,
            'objets' => $objetRepository->findAll(),
            'sousCategories' => $sousCategorieRepository->findAll(),
            'categories' => $categorieRepository->findAll()
        ]);
    }

    #[Route('/historique_panier', name: 'historiquePanier')]
    public function historiquePanierIndex(EmpruntRepository $empruntRepository, ObjetRepository $objetRepository): Response
    {
        return $this->render('home/historiquePanier.html.twig', [
            'controller_name' => 'HomeController',
            'emprunts' => $empruntRepository->findAll(),
            'objets' => $objetRepository->findAll()
        ]);
    }

    // #[Route('/catalogue', name: 'catalogue')]
    // public function catalogue(): Response
    // {
    //     return $this->render('home/catalogue.html.twig', [
    //         'controller_name' => 'CatalogueController',
    //     ]);
    // }

    #[Route('/validation_panier', name: 'validation')]
    public function validationPanier(Request $request, EmpruntRepository $empruntRepository, ObjetRepository $objetRepository, SousCategorieRepository $sousCategorieRepository, CategorieRepository $categorieRepository, AdherentRepository $adherentRepository, SuperAdminRepository $superAdminRepository): Response
    {
        // dump($this->getUser()->getId());
        $emprunts = $empruntRepository->findAll();
        foreach ($emprunts as $emprunt) {
            // dump($emprunt->getAdherent());
            // selection (par adherent connecté et emprunt à statut avant panier) des emprunts visible dans le panier
            if ($this->getUser()) {
                // récupération de l'adherent dans emprunt
                $adherentBibliotheque = $this->getUser()->getId();
                $adherent = $adherentRepository->findOneById($adherentBibliotheque);
                $adminBibliotheque = $this->getUser()->getId();
                $admin = $superAdminRepository->findOneById($adminBibliotheque);
                $adherent
                    ? $adh = $emprunt->getAdherent($adherent)
                    : $adh = $emprunt->getSuperAdmin($admin);
                // dump($this->getUser());
                if ($adh) {
                    // dump($adh->getId());
                    //comparaison de l'adherent connecté à adherent dans emprunt
                    if ($adh->getId() == $this->getUser()->getId()) {
                        //selection des emprunts présent dans le panier
                        if ($emprunt->getStatut() == "demande avant panier") {
                            $emprunt->setStatut("en attente de validation");
                            $entityManager = $this->getDoctrine()->getManager();
                            $entityManager->persist($emprunt);
                            $entityManager->flush();
                            $this->addFlash('success', "Votre demande d'emprunt a bien été prise en compte.");
                            return $this->redirectToRoute('panier');
                        }
                    }
                }
            }
        }
    }
    #[Route('/adherent/{slug}/edit', name: 'compte', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adherent $adherent, AdhesionBibliotheque $adhesionBibliotheque): Response
    {
        $form = $this->createForm(AdherentUtilisateurType::class, $adherent);
        $formMdp = $this->createForm(AdherentUtilisateurMdpType::class, $adhesionBibliotheque);
        $form->handleRequest($request);
        $formMdp->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() || $formMdp->isSubmitted() && $formMdp->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Votre demande de changement a bien été prise en compte.");
            // return $this->redirectToRoute('home');
        }

        return $this->render('home/compte.html.twig', [
            'adherent' => $adherent,
            'adherentBibliotheque' => $adhesionBibliotheque,
            'form' => $form->createView(),
            'formMdp' => $formMdp->createView(),
        ]);
    }
}
