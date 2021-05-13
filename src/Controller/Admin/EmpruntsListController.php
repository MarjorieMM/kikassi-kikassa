<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Objet;
use App\Entity\Emprunt;
use App\Form\SearchFormType;
use App\Form\EmpruntFormType;
use Doctrine\ORM\EntityManager;
// use App\Classes\CalculDepotRajoute;
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

        $formObjSearch = $this->createForm(SearchFormType::class);
        $formSearch = $this->createForm(SearchFormType::class);
        $form = $this->createForm(EmpruntFormType::class, $emprunt);

        $form->handleRequest($request);

        // Je récupère l'adhérent est je vérifie si c'est un adhérent ou super-admin

        $adherent = $adherentRepository->findOneById(
            $request->request->get('adherent')
        );
        $admin = $superAdminRepository->findOneById(
            $request->request->get('adherent')
        );

        $adherent
            ? $emprunt->setAdherent($adherent)
            : $emprunt->setSuperAdmin($admin);

        //Je récupère l'objet

        $objet = $objetRepository->findOneById($request->request->get('objet'));

        $submitted = $form->isSubmitted() ? 'was-validated' : '';

        if ($form->isSubmitted() && $form->isValid()) {
            //  Je vérifie si l'objet peut être emprunté
            if ($objet) {
                if ($objet->getStatut() == 'Disponible') {
                    $emprunt->setObjet($objet);
                    //Je vérifie si l'emprunteur est bien un adhérent inscrit à la
                    // bibliothèque ou un super-admin
                    if (
                        ($adherent && $adherent->getAdhesionBibliotheque()) ||
                        $admin
                    ) {
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

                        //je set le statut de l'emprunt et je le met à "en cours" si l'emprunt débute aujourd'hui ou à "accepté par l'admin" si non
                        $emprunt->setStatut(
                            $emprunt->getDateDebut() == $now
                                ? 'Emprunt en cours'
                                : 'Accepté par l\'Admin'
                        );
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
                        //Je set le  statut de l'objet à réservé
                        $objet->setStatut('Réservé');
                        $manager->persist($emprunt);
                        $manager->flush();
                    } else {
                        $this->addFlash(
                            'danger',
                            'Emprunteur non choisi ou adhérent non inscrit à la bibliothèque'
                        );
                    }
                } else {
                    $this->addFlash(
                        'danger',
                        "L'objet {$objet->getDenomination()} n'est pas disponible pour un emprunt"
                    );
                }
            } else {
                $this->addFlash('danger', 'Veuillez choisir un objet');
            }
        }

        return $this->render('admin/forms/emprunts_new.html.twig', [
            'controller_name' => 'EmpruntsListController',
            'arrow' => true,
            'section' => 'section-emprunts',
            'return_path' => 'menu-emprunt',
            'color' => 'emprunts-color',
            'form' => $form->createView(),
            'formObjSearch' => $formObjSearch->createView(),
            'formSearch' => $formSearch->createView(),
            'submitted' => $submitted,
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
        $emprunt->setStatut('Accepté par l\'Admin');
        $manager->persist($emprunt);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'emprunt n° {$emprunt->getId()} de {$emprunt->getAdherent()->getNomprenom()} a bien été accepté."
        );

        return $this->redirectToRoute('admin_emprunts_resa');
    }

    #[Route('/admin/emprunts/refuser_reservations/{slug}', name: 'refuser_reservation')]
    public function refusResa(
        Emprunt $emprunt,
        EntityManagerInterface $manager
    ): Response {
        $emprunt->setStatut('Refusé par l\'Admin');
        $manager->persist($emprunt);
        $manager->flush();

        $this->addFlash(
            'danger',
            "L'emprunt n° {$emprunt->getId()} de {$emprunt->getAdherent()->getNomprenom()} a été refusé."
        );

        return $this->redirectToRoute('admin_emprunts_resa');
    }

    #[Route('/admin/emprunts/departs', name: 'admin_emprunts_depart')]
    public function ValidDepart(
        EmpruntRepository $repo,
        EntityManagerInterface $manager
    ): Response {
        $now = new DateTime('now');

        $reservations = [];
        $searchedStatut = 'Accepté par l\'Admin';
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
            "Le départ de l'objet {$emprunt->getObjet()->getDenomination()} est bien enregistré, l'adhérent n'a pas été effectué de paiement ce jour."
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
            "Le départ de l'objet {$emprunt->getObjet()->getDenomination()} est bien enregistré, le réglement de l'adhérent a bien été pris en compte."
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
            // On calcule les pénalités de retard éventuelles au début :
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

        // automatiquement settés :

        $emprunt->setDateRetourObjet($now);
        $emprunt->setStatut('Terminé');

        // récupérés dans le formulaire :

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
            "Le retour de l'objet : {$objetRetourne->getDenomination()} est bien enregistré"
        );
        return $this->redirectToRoute('admin_emprunts_retour');
    }
}