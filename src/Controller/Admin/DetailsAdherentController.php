<?php

namespace App\Controller\Admin;

use DateInterval;
use Knp\Snappy\Pdf;
use App\Entity\Adherent;
use App\Form\AllFormType;
use App\Form\BiblioFormType;
use App\Form\FourmiFormType;
use App\Form\SearchFormType;
use App\Form\AdhesionFormType;
use App\Entity\AdhesionBibliotheque;
use App\Repository\AdherentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdhesionBibliothequeRepository;
use App\Classes\CalculDepotRajoute;
use DateTime;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DetailsAdherentController extends AbstractController
{
    #[Route('/admin/details/adherent/{slug}', name: 'admin_adherents_details')]
    public function index(
        $slug,
        AdherentRepository $adherentRepository,
        Adherent $adherent
    ): Response {
        // $adherent = $adherentRepository->findOneBySlug($slug);

        return $this->render('admin/pages_details/details_adherent.html.twig', [
            'controller_name' => 'DetailsAdherentController',
            'adherent' => $adherent,
            'return_path' => 'menu-adherent',
            'section' => 'section-adherents',
            'color' => 'adherents-color',
        ]);
    }

    #[Route('/admin/adherents/edit/{slug}', name: 'admin_adherents_edit')]

    public function editAdherent(
        Adherent $adherent,
        Request $request,
        AdhesionBibliothequeRepository $repoBiblio,
        EntityManagerInterface $manager
    ): Response {
        // Si l'adhésion de l'adhérent date de + d'un an :
        $perime = false;
        $now = new \DateTime();
        $date = $adherent->getDateAdhesion();
        $nextYear = $date->add(new DateInterval('P1Y'));
        if ($adherent->getDateAdhesion()) {
            if ($nextYear < $now) {
                $perime = true;
                $adherent->setCompteActif(false);
                // pour l'affichage :
                $adherent->setMontantCotisation(0);
                $adherent->setEtatCotisation('');
                $adherent->setMoyenPaiement('');
                $nextYear = $date->sub(new DateInterval('P1Y'));
            }
        }

        $form = $this->createForm(AdhesionFormType::class, $adherent);

        $form->handleRequest($request);

        $submitted = $form->isSubmitted() ? 'was-validated' : '';

        if ($form->isSubmitted() && $form->isValid()) {
            // Si concerné, je procède à la ré-adhésion :
            if ($nextYear < $now) {
                $adherent->setCompteActif(true);
                $adherent->setDateAdhesion($now);
            }
            $biblio = $adherent->getAdhesionBibliotheque();
            $biblio->setEmail($adherent->getEmail());

            $manager->persist($adherent);
            $manager->persist($biblio);
            $manager->flush();

            /** @var ClickableInterface $button  */
            $button = $form->get('saveAndContinue');
            if ($adherent->getAdhesionBibliotheque()) {
                // Si l'adhérent est déjà inscrit à la bibliothèque :
                // Si click sur le bouton 'modifier l'adhésion à la Bibliothèque' :
                if ($button->isClicked()) {
                    return $this->redirectToRoute(
                        'admin_adherents_edit_bilio',
                        [
                            'id' => $adherent->getId(),
                        ]
                    );
                } else {
                    // Si click sur le bouton 'valider les changements' retour à la page profil de l'adherent :
                    return $this->redirectToRoute('admin_adherents_details', [
                        'slug' => $adherent->getSlug(),
                    ]);
                    $this->addFlash(
                        'success',
                        "L'adhérent : {$adherent->getNomprenom()} a bien été mis à jour"
                    );
                }
                // Si l'adhérent n'est pas encore inscrit à la bibliothèque :
            } else {
                // Si click sur le bouton 'procéder à l'inscription à la Bibliothèque' :
                if ($button->isClicked()) {
                    return $this->redirectToRoute('adherents_new_biblio', [
                        'id' => $adherent->getId(),
                    ]);
                } else {
                    // Si click sur le bouton 'valider les changements' :
                    $this->addFlash(
                        'success',
                        "L'adhérent : {$adherent->getNomprenom()} a bien été mis à jour"
                    );
                    return $this->redirectToRoute('admin_adherents_details', [
                        'slug' => $adherent->getSlug(),
                    ]);
                }
            }
        }
        return $this->render('admin/forms/adherents_edit.html.twig', [
            'controller_name' => 'AdherentsListController',
            'adherent' => $adherent,
            'arrow' => true,
            'section' => 'section-adherents',
            'return_path' => 'menu-adherent',
            'color' => 'adherents-color',
            'form' => $form->createView(),
            'submitted' => $submitted,
            'perime' => $perime,
        ]);
    }

    #[Route('/admin/adherents/edit/biblio/{id}', name:'admin_adherents_edit_bilio')]

    public function editBiblioAdherent(
        $id,
        AdherentRepository $adherentRepository,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder
    ): Response {
        $adherent = $adherentRepository->findOneById($id);
        $biblio = $adherent->getAdhesionBibliotheque();

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
                "Les changements dans l'Inscription à la Bibliothèque des Objets de {$biblio->getAdherent()->getPrenom()} {$biblio->getAdherent()->getNom()} sont bien prises en compte"
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

    #[Route('/admin/{param}/modif', name: 'admin_adherents_modif')]
    public function selModif(
        $param,
        AdherentRepository $adherentRepository,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $formSearch = $this->createForm(SearchFormType::class);

        $formSearch->handleRequest($request);

        $adherent = $adherentRepository->findOneById(
            $request->request->get('adherent')
        );

        $form = $this->createForm(FourmiFormType::class);

        $submitted = $form->isSubmitted() ? 'was-validated' : '';

        //Pour la réinscription / modification d'adhérent :

        if ($adherent && $param == 'adherent-reinscription') {
            return $this->redirectToRoute('admin_adherents_edit', [
                'slug' => $adherent->getSlug(),
            ]);
        }

        //Pour la modification du statut fourmi :

        if ($adherent && $param == 'adherent-changement-fourmi') {
            $biblio = new AdhesionBibliotheque();
            $biblio = $adherent->getAdhesionBibliotheque();
            $form->handleRequest($request);
            $data = $form['categorie_fourmi']->getData();

            if ($form->isSubmitted() && $form->isValid()) {
                $biblio->setCategorieFourmi($data);
                $manager->persist($biblio);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Le statut fourmi de l'adhérent : {$adherent->getNomprenom()} a bien été mis à jour"
                );
                return $this->redirectToRoute('admin_adherents_details', [
                    'slug' => $adherent->getSlug(),
                ]);
            }
        }
        // Pour le passage d'un adhérent en Admin :

        if ($adherent && $param == 'adherent-passage-admin') {
            $biblio = new AdhesionBibliotheque();
            $biblio = $adherent->getAdhesionBibliotheque();
            $admin = $request->request->get('admin');
            $admin == 'ROLE_ADMIN'
                ? $biblio->setRoles(['ROLE_ADMIN'])
                : $biblio->setRoles(['ROLE_USER']);
            $manager->persist($biblio);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'adhérent : {$adherent->getNomprenom()} a bien ses droits Admin modifiés"
            );
            return $this->redirectToRoute('admin_adherents_details', [
                'slug' => $adherent->getSlug(),
            ]);
        }

        return $this->render('admin/forms/adherents_modif.html.twig', [
            'controller_name' => 'DetailsAdherentController',
            'return_path' => 'menu-adherent',
            'section' => 'section-adherents',
            'color' => 'adherents-color',
            'formSearch' => $formSearch->createView(),
            'form' => $form->createView(),
            'param' => $param,
            'submitted' => $submitted,
        ]);
    }
    #[Route('/admin/details/adherent/bon_adhesion/{slug}', name: 'admin_adherents_adh_pdf')]
    public function adhPdf(Adherent $adherent, Pdf $knpSnappyPdf): Response
    {
        $nomAdherent = $adherent->getNom();
        $date = $adherent->getDateAdhesion()->format('d-m-Y');

        $html = $this->renderView('admin/admin_bons/bon_adhesion.html.twig', [
            'adherent' => $adherent,
        ]);

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            'adhesion-' . $nomAdherent . '-' . $date . '.pdf'
        );
    }

    #[Route('/admin/details/adherent/bon_biblio/{slug}', name: 'admin_adherents_biblio_pdf')]
    public function biblioPdf(Adherent $adherent, Pdf $knpSnappyPdf): Response
    {
        $biblio = $adherent->getAdhesionBibliotheque();
        $nomAdherent = $adherent->getNom();
        $date = $adherent
            ->getAdhesionBibliotheque()
            ->getDateInscription()
            ->format('d-m-Y');

        $html = $this->renderView('admin/admin_bons/bon_biblio.html.twig', [
            'biblio' => $biblio,
            'adherent' => $adherent,
        ]);

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            'inscription-bibliotheque-' . $nomAdherent . '-' . $date . '.pdf'
        );
    }
}