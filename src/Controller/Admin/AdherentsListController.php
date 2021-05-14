<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use App\Form\BiblioFormType;
use App\Form\AdhesionFormType;
use App\Entity\AdhesionBibliotheque;
use App\Repository\AdherentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdherentsListController extends AbstractController
{
    #[Route('/admin/adherents/list', name: 'admin_adherents_list')]

    public function index(AdherentRepository $repo): Response
    {
        $adherents = $repo->findAll();
        $route = 'down';

        return $this->render('admin/lists/adherents_list.html.twig', [
            'controller_name' => 'AdherentsListController',
            'adherents' => $adherents,
            'route' => $route,
            'section' => 'section-adherents',
            'return_path' => 'menu-adherent',
            'color' => 'adherents-color',
        ]);
    }

    #[Route('/admin/adherents/list/{adh}/{order}', name: 'admin_adherents_list_sort')]
    public function sortDataD($adh, $order, AdherentRepository $repo): Response
    {
        $biblio = ['fin_rc', 'depot_permanent', 'categorie_fourmi'];
        if (in_array($adh, $biblio)) {
            if ($order == 'up') {
                $adherents = $repo->orderByBiblioField($adh, 'DESC');
                $route = 'down';
            } else {
                $adherents = $repo->orderByBiblioField($adh, 'ASC');
                $route = 'up';
            }
        } else {
            if ($order == 'up') {
                $adherents = $repo->findBy([], [$adh => 'DESC']);
                $route = 'down';
            } else {
                $adherents = $repo->findBy([], [$adh => 'ASC']);
                $route = 'up';
            }
        }

        return $this->render('admin/lists/adherents_list.html.twig', [
            'controller_name' => 'AdherentsListController',
            'adherents' => $adherents,
            'route' => $route,
            'section' => 'section-adherents',
            'return_path' => 'menu-adherent',
            'color' => 'adherents-color',
        ]);
    }

    #[Route('/admin/adherents/new', name: 'admin_adherents_new')]

    public function newAdherent(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $adherent = new Adherent();

        $form = $this->createForm(AdhesionFormType::class, $adherent);

        $form->handleRequest($request);

        $submitted = $form->isSubmitted() ? 'was-validated' : '';

        if ($form->isSubmitted() && $form->isValid()) {
            $adherent->setCompteActif(true);
            $manager->persist($adherent);
            $manager->flush();

            $biblio = $request->request->get('biblio');

            if ($biblio == 'oui') {
                return $this->redirectToRoute('adherents_new_biblio', [
                    'id' => $adherent->getId(),
                ]);
            } elseif ($biblio == 'non') {
                $this->addFlash(
                    'success',
                    "Le nouvel adhérent {$adherent->getNomprenom()} a bien été créé"
                );

                return $this->redirectToRoute('admin_adherents_details', [
                    'slug' => $adherent->getSlug(),
                ]);
            }
        }
        return $this->render('admin/forms/adherents_new.html.twig', [
            'controller_name' => 'AdherentsListController',
            'adherent' => $adherent,
            'arrow' => true,
            'section' => 'section-adherents',
            'return_path' => 'menu-adherent',
            'color' => 'adherents-color',
            'form' => $form->createView(),
            'submitted' => $submitted,
        ]);
    }

    #[Route('/admin/adherents/new/biblio/{id}', name: 'adherents_new_biblio')]

    public function newBiblio(
        $id,
        AdherentRepository $adherentRepository,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder
    ): Response {
        $adherent = $adherentRepository->findOneById($id);

        $biblio = new AdhesionBibliotheque();
        $admin = $request->request->get('admin');
        $admin == 'oui'
            ? $biblio->setRoles(['ROLE_ADMIN'])
            : $biblio->setRoles(['ROLE_USER']);
        $form = $this->createForm(BiblioFormType::class, $biblio);

        $form->handleRequest($request);

        $submitted = $form->isSubmitted() ? 'was-validated' : '';

        if ($form->isSubmitted() && $form->isValid()) {
            $biblio->setAdherent($adherent);
            $biblio->setEmail($adherent->getEmail());
            $biblio->setSatutInscription('valide');
            $hash = $encoder->encodePassword(
                $biblio,
                $adherent->getNom() .
                    date_format($adherent->getDateNaissance(), 'Y')
            );

            $biblio->setMotDePasse($hash);
            $manager->persist($biblio);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'adhésion et l'inscription à la bibliothèque des objets de {$biblio->getAdherent()->getPrenom()} {$biblio->getAdherent()->getNom()} sont bien prises en compte"
            );
            return $this->redirectToRoute('admin_adherents_details', [
                'slug' => $adherent->getSlug(),
            ]);
        }

        return $this->render('admin/forms/adherents_biblio.html.twig', [
            'controller_name' => 'AdherentsListController',
            'biblio' => $biblio,
            'arrow' => true,
            'adherent' => $adherent,
            'section' => 'section-adherents',
            'return_path' => 'menu-adherent',
            'color' => 'adherents-color',
            'submitted' => $submitted,
            'form' => $form->createView(),
        ]);
    }
}