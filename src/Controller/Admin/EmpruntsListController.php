<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Objet;
use App\Entity\Emprunt;
use App\Classes\Calculs;
use App\Form\SearchFormType;
use App\Form\EmpruntFormType;
use Doctrine\ORM\EntityManager;
use App\Form\FinEmpruntFormType;
use App\Repository\ObjetRepository;
use App\Repository\EmpruntRepository;
use App\Repository\AdherentRepository;
use App\Repository\SuperAdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdhesionBibliothequeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmpruntsListController extends AbstractController
{
    #[Route('/admin/emprunts/list', name: 'admin_emprunts_list')]
    public function index(EmpruntRepository $repo): Response
    {
        $emprunts = $repo->findAll();

        return $this->render('admin/lists/emprunts_list.html.twig', [
            'controller_name' => 'EmpruntsListController',
            'emprunts' => $emprunts,
            'section' => 'section-emprunts',
            'return_path' => 'menu-emprunt',
            'color' => 'emprunts-color',
        ]);
    }
    #[Route('/admin/emprunts/new', name: 'admin_emprunts_new')]

    public function newEmprunt(
        Request $request,
        EntityManagerInterface $manager,
        AdherentRepository $adherentRepository,
        ObjetRepository $objetRepository,
        SuperAdminRepository $superAdminRepository
    ): Response {
        $emprunt = new Emprunt();
        $objet = new Objet();
        $now = new \DateTime();


        $form = $this->createForm(EmpruntFormType::class, $emprunt);

        $form->handleRequest($request);


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

        //Partie recherche de l'objet

        $searchTerm = $request->query->get('obj');
        $objets = $objetRepository->findByText(
            $searchTerm
        );

        if ($request->query->get('previewobj')) {
            return $this->render('admin/forms/_searchObjet.html.twig', [
                'objets' => $objets
            ]);
        }

        /////////////////////

        // Je r??cup??re l'adh??rent est je v??rifie si c'est un adh??rent ou super-admin

        $adherent = $adherentRepository->findOneById(
            $request->request->get('adherent')
        );
        $admin = $superAdminRepository->findOneById(
            $request->request->get('adherent')
        );


        $adherent
            ? $emprunt->setAdherent($adherent)
            : $emprunt->setSuperAdmin($admin);


        // Je r??cup??re l'objet

        $objet = $objetRepository->findOneById($request->request->get('objet'));

        dump($request->request->get('objet'));

        $submitted = $form->isSubmitted() ? 'was-validated' : '';

        if ($form->isSubmitted() && $form->isValid()) {

            $dateFin = $form
                ->get('date_fin')
                ->getData()
                ->getTimestamp();
            $dateDebut = $form
                ->get('date_debut')
                ->getData()
                ->getTimestamp();

            //Je v??rifie si l'emprunteur est bien un adh??rent inscrit ?? la
            // biblioth??que ou un super-admin
            if (($adherent && $adherent->getAdhesionBibliotheque()) || $admin) {
                //  Je v??rifie si l'objet peut ??tre emprunt??
                if ($objet && $objet->getStatut() == 'Disponible') {
                    $empObjet = $objet->getEmprunts();
                    $empruntOk = [];
                    // Je compare avec les autres emprunts attach??s ?? l'objet
                    foreach ($empObjet as $obj) {
                        $empruntDebut = $obj->getDateDebut()->getTimestamp();
                        $empruntFin = $obj->getDateFin()->getTimestamp();
                        $objRetour = $obj->getDateRetourObjet();

                        if ($empruntDebut) {
                            if (
                                $empruntFin < $now->getTimestamp() &&
                                !$objRetour
                            ) {
                                $empruntOk[] = false;
                                dump('pas rendu');
                            } elseif (
                                $dateFin > $empruntDebut &&
                                $dateDebut <= $empruntFin
                            ) {
                                $empruntOk[] = false;
                                dump('chevauche');
                            } else {
                                dump('chevauche pas');
                                $empruntOk[] = true;
                            }
                        } else {
                            dump('pas d\'emprunt');
                            $empruntOk[] = true;
                        }
                    }
                    if (in_array(false, $empruntOk)) {
                        $this->addFlash(
                            'danger',
                            "L'objet {$objet->getDenomination()} n'est pas disponible pour un emprunt aux dates choisies"
                        );
                    } else {
                        if ($emprunt->getDateFin() < $emprunt->getDateDebut()) {
                            $this->addFlash(
                                'danger',
                                "La date de fin d'emprunt ne peut pas ??tre avant la date de d??but"
                            );
                        } else {
                            $emprunt->setObjet($objet);
                            //je set le statut de l'emprunt et je le met ?? "en cours" si l'emprunt d??bute aujourd'hui ou ?? "accept?? par l'admin" sinon

                            $emprunt->setStatut(
                                $emprunt->getDateDebut() == $now
                                    ? 'Emprunt en cours'
                                    : 'Accept?? par l\'Admin'
                            );
                            $biblio = $adherent->getAdhesionBibliotheque();
                            $finrc = $biblio->getFinRc();
                            $depot_perm = $biblio->getDepotPermanent();

                            $calc = new Calculs(
                                $finrc,
                                $objet,
                                $depot_perm,
                                $emprunt
                            );

                            $emprunt->setPrixEmprunt($calc->calculPrix());
                            $emprunt->setDepotRajoute(
                                $calc->calculDepot($adherent)
                            );

                            $manager->persist($emprunt);
                            $manager->flush();
                        }
                    }
                } else {
                    $this->addFlash(
                        'danger',
                        "Veuillez choisir un objet ou l'objet n'est pas disponible pour un emprunt"
                    );
                }
            } else {
                $this->addFlash(
                    'danger',
                    'Veuillez choisir un emprunteur ou l\'emprunteur n\'est pas inscrit ?? la biblioth??que'
                );
            }
        }

        return $this->render('admin/forms/emprunts_new.html.twig', [
            'controller_name' => 'EmpruntsListController',
            'arrow' => true,
            'section' => 'section-emprunts',
            'return_path' => 'menu-emprunt',
            'color' => 'emprunts-color',
            'form' => $form->createView(),
            'submitted' => $submitted,
            'searchTerm' => $searchTerm,
            'searchAdh' => $searchAdh

        ]);
    }

    #[Route('/admin/emprunts/reservations', name: 'admin_emprunts_resa')]
    public function ValidResa(EmpruntRepository $repo): Response
    {
        $reservations = [];
        $searchedStatut = 'en attente de validation';
        $resaEmprunts = $repo->findBy([
            'statut' => strtolower($searchedStatut),
        ]);
        foreach ($resaEmprunts as $resa) {
            $reservations[] = $resa;
        }

        return $this->render('admin/lists/emprunts_resa.html.twig', [
            'controller_name' => 'EmpruntsListController',
            'emprunts' => $reservations,
            'section' => 'section-emprunts',
            'return_path' => 'menu-emprunt',
            'color' => 'emprunts-color',
        ]);
    }

    #[Route('/admin/emprunts/accepter_reservations/{slug}', name: 'accepter_reservation')]
    public function ValResa(
        Emprunt $emprunt,
        EntityManagerInterface $manager
    ): Response {
        $emprunt->setStatut('Accept?? par l\'Admin');
        $manager->persist($emprunt);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'emprunt n?? {$emprunt->getId()} de {$emprunt->getAdherent()->getNomprenom()} a bien ??t?? accept??."
        );

        return $this->redirectToRoute('admin_emprunts_resa');
    }

    #[Route('/admin/emprunts/refuser_reservations/{slug}', name: 'refuser_reservation')]
    public function refusResa(
        Emprunt $emprunt,
        EntityManagerInterface $manager
    ): Response {
        $emprunt->setStatut('Refus?? par l\'Admin');
        $manager->persist($emprunt);
        $manager->flush();

        $this->addFlash(
            'danger',
            "L'emprunt n?? {$emprunt->getId()} de {$emprunt->getAdherent()->getNomprenom()} a ??t?? refus??."
        );

        return $this->redirectToRoute('admin_emprunts_resa');
    }

    #[Route('/admin/emprunts/departs', name: 'admin_emprunts_depart')]
    public function ValidDepart(
        Request $request,
        EmpruntRepository $repo,
        EntityManagerInterface $manager,
        AdherentRepository $adherentRepository
    ): Response {
        $formSearch = $this->createForm(SearchFormType::class);
        $formSearch->handleRequest($request);

        $adherent = $adherentRepository->findOneById(
            $request->request->get('adherent')
        );

        $now = new DateTime('now');

        $reservations = [];
        $searchedStatut = 'Accept?? par l\'Admin';
        $resaEmprunts = $repo->findBy([
            'statut' => strtolower($searchedStatut),
        ]);
        foreach ($resaEmprunts as $emprunt) {
            if ($emprunt->getDateDebut() <= $now) {
                $reservations[] = $emprunt;
            }
            // if ($emprunt->getDateDebut() < $now) {
            //     $emprunt->setDateDebut($now);
            //     $manager->persist($emprunt);
            //     $manager->flush();
            // }
        }

        return $this->render('admin/lists/emprunts_depart.html.twig', [
            'controller_name' => 'EmpruntsListController',
            'emprunts' => $reservations,
            'section' => 'section-emprunts',
            'return_path' => 'menu-emprunt',
            'color' => 'emprunts-color',
            'formSearch' => $formSearch->createView(),
        ]);
    }

    #[Route('/admin/emprunts/departs/non-paye/{slug}', name: 'enregistrer_depart')]
    public function EnregistrerDepart(
        Emprunt $emprunt,
        EntityManagerInterface $manager
    ): Response {
        $emprunt->setStatut('Emprunt en cours');
        $manager->persist($emprunt);
        $manager->flush();

        $this->addFlash(
            'warning',
            "Le d??part de l'objet {$emprunt->getObjet()->getDenomination()} est bien enregistr??, l'adh??rent n'a pas ??t?? effectu?? de paiement ce jour."
        );

        return $this->redirectToRoute('admin_emprunts_depart');
    }

    #[Route('/admin/emprunts/departs/paye/{slug}', name: 'enregistrer_depart_paye')]
    public function EnregistrerDepartPaye(
        Emprunt $emprunt,
        EntityManagerInterface $manager
    ): Response {
        $emprunt->setStatut('Emprunt en cours');
        $emprunt->setEmpruntRegle(true);
        $manager->persist($emprunt);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le d??part de l'objet {$emprunt->getObjet()->getDenomination()} est bien enregistr??, le r??glement de l'adh??rent a bien ??t?? pris en compte."
        );

        return $this->redirectToRoute('admin_emprunts_depart');
    }

    #[Route('/admin/emprunts/retours', name: 'admin_emprunts_retour')]
    public function ValidRetour(
        EmpruntRepository $repo,
        EntityManagerInterface $manager
    ): Response {
        $now = new DateTime('now');

        $retours = [];
        $searchedStatut = 'Emprunt en cours';
        $retourEmprunts = $repo->findBy([
            'statut' => strtolower($searchedStatut),
        ]);

        foreach ($retourEmprunts as $emprunt) {
            $retours[] = $emprunt;
            // On calcule les p??nalit??s de retard ??ventuelles au d??but :
            if ($emprunt->getDateFin() < $now) {
                $dureeEmp = intval(
                    $emprunt->getDateDebut()->diff($emprunt->getDateFin())->days
                );
                $dureeEmprunt = $dureeEmp === 0 ? 1 : $dureeEmp;
                $joursSup = $emprunt->getDateFin()->diff($now)->days;
                $prixParJour = $emprunt->getPrixEmprunt() / $dureeEmprunt;
                $penalites = $prixParJour * 1.75 * $joursSup;
                $emprunt->setPenalites($penalites);
                $manager->persist($emprunt);
                $manager->flush();
            }
        }

        return $this->render('admin/lists/emprunts_retour.html.twig', [
            'controller_name' => 'EmpruntsListController',
            'emprunts' => $retours,
            'section' => 'section-emprunts',
            'return_path' => 'menu-emprunt',
            'color' => 'emprunts-color',
        ]);
    }

    #[Route('/admin/emprunts/retour-valide/', name: 'validation-retour')]
    public function ValidationRetour(
        EmpruntRepository $repo,
        EntityManagerInterface $manager,
        Request $request
    ): Response {
        $now = new DateTime('now');
        $emprunt = $repo->findOneById($request->request->get('emprunt_id'));

        $dispo = $request->request->get('dispo');
        $observation = $request->request->get('obs');
        $paiement = $request->request->get('paiement');
        $penalites = $request->request->get('penalites');

        // automatiquement sett??s :

        $emprunt->setDateRetourObjet($now);
        $emprunt->setStatut('Termin??');

        // r??cup??r??s dans le formulaire :

        $objetRetourne = $emprunt->getObjet();
        $objetRetourne->setStatut($dispo);
        $objetRetourne->setObservation($observation);

        // paiement de l'emprunt et des penalites de retard si ils existent :

        if ($paiement) {
            $emprunt->setEmpruntRegle($paiement);
        }
        if ($penalites) {
            $emprunt->setPenalitesPayees($penalites);
        }

        $manager->persist($emprunt);
        $manager->persist($objetRetourne);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le retour de l'objet : {$objetRetourne->getDenomination()} est bien enregistr??"
        );
        return $this->redirectToRoute('admin_emprunts_retour');
    }
}
