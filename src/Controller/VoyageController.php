<?php
namespace App\Controller;

use App\Entity\Hebergement;
use App\Entity\Prestation;
use App\Entity\Transport;
use App\Entity\Vol;
use App\Entity\Voyage;
use App\Models\VoyageModel;
use App\Repository\HebergementRepository;
use App\Repository\PrestationRepository;
use App\Repository\TransportRepository;
use App\Repository\VolRepository;
use App\Repository\VoyageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function Symfony\Component\Clock\now;


class VoyageController extends AbstractController
{
    private $voyageModel;

    public function __construct(VoyageRepository $voyageModel)
    {
        $this->voyageModel = $voyageModel;
    }

    #[Route('/voyages', name: 'voyages_index')]
    public function index(Request $request): Response
    {
        $id =  6;
        $voyage = $this->voyageModel->findById($id);
        // dd($voyage);
        $page = $request->query->getInt('page', 1);
        $search = $request->query->get('search', null);
        $items = 5;

        // Récupérer les voyages paginés
        $voyages = $this->voyageModel->getPaginatedVoyages($page, $items, $search);

        
        // Total pour la pagination
        $total = $search ? $this->voyageModel->countLike($search) : $this->voyageModel->countAll();

        $pages = ceil($total / $items);
        
        return $this->render('voyage/index.html.twig', [
            'total_pages' => $pages,
            'voyages' => $voyages,
            'total' => $total,
            'page' => $page,
            'search' => $search,
        ]);
    }

    #[Route('/voyage/{id}', name: 'voyage_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, EntityManagerInterface $entityManager, VoyageRepository $vr): Response
    {
        // Rechercher le voyage par son ID
        $voyage =  $this->voyageModel->findById($id);
        $prestations = $voyage->getPrestations();
        $vols = $voyage->getVols();
        $transports = $voyage->getTransports();
        $hebergements = $voyage->getHebergements();
        $cout = [
                'hebergement' => 0,
                'prestation' => 0,
                'transport' => 0,
                'vol' => 0,
                'total' => 0,
        ];
        $cout_total = 0;
        // dd($hebergements);


        // Vérifier si le voyage existe
        if (!$voyage) {
            $this->addFlash('error', 'Voyage introuvable.');
            return $this->redirectToRoute('voyage_index'); // Rediriger vers la liste si le voyage n'existe pas
        }

        $timeline = [];

        foreach ($hebergements as $h) {
            $cout["hebergement"] += $h->getCoutHebergement();
            $cout_total += $h->getCoutHebergement();

            $dateSort = $h->getDateDebut() ?? $h->getDateFin();

            if (!$dateSort) continue;

            $timeline[] = [
                'type' => 'hebergement',
                'date_sort' => $dateSort,
                'date_debut' => $h->getDateDebut(),
                'date_fin' => $h->getDateFin(),
                'titre' => $h->getNomHebergement(),
                'description' => $h->getLieuHebergement(),
                'cout' => $h->getCoutHebergement(),
                'objet' => $h,
            ];
        }

        foreach ($vols as $v) {
            $cout["vol"] += $v->getCoutVol();
            $cout_total += $v->getCoutVol();

            $dateSort = $v->getDatePrimus() ?? $v->getDateTerminus();

            if (!$dateSort) continue;

            $timeline[] = [
                'type' => 'vol',
                'date_sort' => $dateSort,
                'date_debut' => $v->getDatePrimus(),
                'date_fin' => $v->getDateTerminus(),
                'titre' => $v->getReference(),
                'description' => $v->getPrimus().' → '.$v->getTerminus(),
                'cout' => $v->getCoutVol(),
                'objet' => $v,
            ];
        }
        // dd($this->getUser());
        foreach ($transports as $t) {
            $cout["transport"] += $t->getCoutTransport();
            $cout_total += $t->getCoutTransport();

            $dateSort = $t->getDatePrimus() ?? $t->getDateTerminus();

            if (!$dateSort) continue;

            $timeline[] = [
                'type' => 'transport',
                'date_sort' => $dateSort,
                'date_debut' => $t->getDatePrimus(),
                'date_fin' => $t->getDateTerminus(),
                'titre' => $t->getPrimus(),
                'description' => $t->getPrimus().' → '.$t->getTerminus(),
                'cout' => $t->getCoutTransport(),
                'objet' => $t,
            ];
        }

        foreach ($prestations as $p) {
            $cout["prestation"] += $p->getCoutPrestation();
            $cout_total += $p->getCoutPrestation();

            if (!$p->getDatePrestation()) continue;

            $timeline[] = [
                'type' => 'prestation',
                'date_sort' => $p->getDatePrestation(),
                'date_debut' => $p->getDatePrestation(),
                'date_fin' => null,
                'titre' => $p->getPrestation(),
                'description' => $p->getLieuPrestation(),
                'cout' => $p->getCoutPrestation(),
                'objet' => $p,
            ];
        }

        $cout["total"] = $cout_total;
        usort($timeline, function ($a, $b) {
            if ($a['date_sort'] == $b['date_sort']) {
                return strcmp($a['type'], $b['type']);
            }
            return $a['date_sort'] <=> $b['date_sort'];
        });

        $voyages = $vr -> findAll();

        return $this->render('voyage/detail.html.twig', [
            'voyages'=> $voyages,
            'voyage' => $voyage,
            'hebergements' => $hebergements,
            'transports' => $transports,
            'vols' => $vols,
            'prestations' => $prestations,
            'timeline' => $timeline,
            'cout' => $cout
        ]);
    }

    #[Route('/voyage/edit', name: 'voyage_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
    
        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Créer une nouvelle instance de Voyage
            $data = $request->request->all(); // Récupérer tous les champs du formulaire

            $voyage = $this->voyageModel->findById($data['id']);

            // Remplir l'objet $voyage avec les données du formulaire
            $voyage->setTourist($data['tourist']);
            $voyage->setDateDebut(new \DateTime($data['date_debut']));
            $voyage->setAcheve($data['acheve'] === '1'); // Convertir la string en bool
            $voyage->setPaye($data['paye'] === '1'); // Convertir la string en bool

            // Enregistrer le voyage dans la base de données
            $entityManager->flush();

            // Flash message pour notification
            $this->addFlash('success', 'Le voyage a été modifié avec succès.');

            // Rediriger vers la liste des voyages
        }
        
        return $this->redirectToRoute('voyage_detail',  ['id' => $data['id']]); // Remplacez par votre route de liste si différente
    }

    #[Route('/voyage/add', name: 'voyage_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer une nouvelle instance de Voyage
        $voyage = new Voyage();

        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            $data = $request->request->all(); // Récupérer tous les champs du formulaire

            // Remplir l'objet $voyage avec les données du formulaire
            $voyage->setTourist($data['tourist']);
            $voyage->setDateDebut(new \DateTime($data['date_debut']));
            $voyage->setAcheve($data['acheve'] === '1'); // Convertir la string en bool
            $voyage->setPaye($data['paye'] === '1'); // Convertir la string en bool

            // Enregistrer le voyage dans la base de données
            $entityManager->persist($voyage);
            $entityManager->flush();

            // Flash message pour notification
            $this->addFlash('success', 'Le voyage a été ajouté avec succès.');

            // Rediriger vers la liste des voyages
            return $this->redirectToRoute('voyages_index'); // Remplacez par votre route de liste si différente
        }

        // Si le formulaire n'est pas soumis, afficher un message d'erreur
        return $this->render('voyage/add.html.twig', [
            'form' => $voyage,
        ]);
    }


    #[Route('/voyage/{id}/payer', name: 'voyage_payer')]
    public function payer(int $id, EntityManagerInterface $entityManager): Response
    {
        // Rechercher le voyage par son ID
        $voyage = $this->voyageModel->findById($id);

        // Vérifier si le voyage existe
        if (!$voyage) {
            $this->addFlash('error', 'Voyage introuvable.');
            return $this->redirectToRoute('voyage_index'); // Rediriger vers la liste si le voyage n'existe pas
        }

        // Mettre à jour le statut de paiement
        $voyage->setPaye(true); // Marquer comme payé

        // Enregistrer les modifications en base de données
        $entityManager->flush();

        // Flash message de succès
        $this->addFlash('success', 'Le paiement a été marqué comme effectué.');

        // Redirection vers la liste des voyages
        return $this->redirectToRoute('voyages_index'); // Remplacez par votre route de liste si différente
    }
    
    #[Route('/voyage/{id}/terminer', name: 'voyage_terminer')]
    public function markAsCompleted(int $id, EntityManagerInterface $entityManager): Response
    {
        // Rechercher le voyage par son ID
        $voyage = $this->voyageModel->findById($id);

        // Vérifier si le voyage existe
        if (!$voyage) {
            $this->addFlash('error', 'Voyage introuvable.');
            return $this->redirectToRoute('voyages_index'); // Rediriger vers la liste si le voyage n'existe pas
        }

        // Mettre à jour le statut de paiement
        $voyage->setAcheve(true); // Marquer comme payé

        // Enregistrer les modifications en base de données
        $entityManager->flush();

        // Flash message de succès
        $this->addFlash('success', 'Le voyage a été marqué comme terminé.');

        // Redirection vers la liste des voyages
        return $this->redirectToRoute('voyages_index'); // Remplacez par votre route de liste si différente
    }

    #[Route('/voyage/{id}/delete', name: 'voyage_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Voyage $voyage,
        EntityManagerInterface $em
        ): Response {
        // dd($voyage);
        if ($this->isCsrfTokenValid('delete'.$voyage->getId(), $request->request->get('_token'))) {
            $message = " #".$voyage->getId()." - ".$voyage->getTourist()." [". $voyage->getDateDebut()->format("d-m-Y")."] ";
            $em->remove($voyage);
            $em->flush();

            $this->addFlash('success', 'Voyage <strong>'.$message.'</strong> supprimé avec succès.');
        } else {
            $this->addFlash('danger', 'Action non autorisée.');
        }

    return $this->redirectToRoute('voyages_index');
}

    #[Route('/voyages/statistiques', name: 'voyages_stats')]
    public function statistics(): Response
    {
        $stats = $this->voyageModel->getStatistics();
        $monthlyStats = $this->voyageModel->getMonthlyStats();
        $uniqueTourists = $this->voyageModel->getUniqueTourists();
        
        return $this->render('voyage/statistics.html.twig', [
            'stats' => $stats,
            'monthlyStats' => $monthlyStats,
            'tourists' => $uniqueTourists,
        ]);
    }

    #[Route('/hebergement/edit', name: 'hebergement_edit')]
    public function editHebergement(Request $request, EntityManagerInterface $entityManager, HebergementRepository $hr, VoyageRepository $vr): Response
    {
    
        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Créer une nouvelle instance de Voyage
            $data = $request->request->all(); // Récupérer tous les champs du formulaire

            $hebergement = $hr->find($data["id"]);

            // Remplir l'objet $voyage avec les données du formulaire
            $hebergement->setNomHebergement($data['nom_hebergement']);
            $hebergement->setLieuHebergement($data['lieu_hebergement']);
            $hebergement->setDateDebut(new \DateTime($data['date_debut']));
            $hebergement->setDateFin(new \DateTime($data['date_fin']));
            $hebergement->setCoutHebergement($data['cout_hebergement']);
            $hebergement->setVoyage($vr->find($data['voyage']));

            // Enregistrer le voyage dans la base de données
            $entityManager->flush();

            // Flash message pour notification
            $this->addFlash('success', 'L\'hébergement a été modifié avec succès.');

            // Rediriger vers la liste des voyages
                
            return $this->redirectToRoute('voyage_detail',  ['id' => $data['voyage'], 'type' => 'hebergement']); // Remplacez par votre route de liste si différente
        }

        return $this->redirectToRoute('voyages_index');
    }

    #[Route('/vol/edit', name: 'vol_edit')]
    public function editVol(Request $request, EntityManagerInterface $entityManager, VolRepository $vor, VoyageRepository $vr): Response
    {
    
        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Créer une nouvelle instance de Voyage
            $data = $request->request->all(); // Récupérer tous les champs du formulaire

            $vol = $vor->find($data["id"]);

            // Remplir l'objet $voyage avec les données du formulaire
            $vol->setReference($data['ref_vol']);
            $vol->setPrimus($data['primus_vol']);
            $vol->setTerminus($data['terminus_vol']);
            $vol->setDatePrimus(new \DateTime($data['date_primus_vol']));
            $vol->setDateTerminus(new \DateTime($data['date_terminus_vol']));
            $vol->setAgenceVol($data['agence_vol']);
            $vol->setCoutVol($data['cout_vol']);
            $vol->setVoyage($vr->find($data['voyage']));

            // Enregistrer le voyage dans la base de données
            $entityManager->flush();

            // Flash message pour notification
            $this->addFlash('success', 'Le vol a été modifié avec succès.');

            // Rediriger vers la liste des voyages
                
            return $this->redirectToRoute('voyage_detail',  ['id' => $data['voyage'], 'type' => 'vol']); // Remplacez par votre route de liste si différente
        }

        return $this->redirectToRoute('voyages_index');
    }

    #[Route('/transport/edit', name: 'transport_edit')]
    public function editTransport(Request $request, EntityManagerInterface $entityManager, TransportRepository $tr, VoyageRepository $vr): Response
    {
    
        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Créer une nouvelle instance de Voyage
            $data = $request->request->all(); // Récupérer tous les champs du formulaire

            $transport = $tr->find($data["id"]);

            $transport->setDatePrimus(new \DateTime($data['date_primus_transport']));
            $transport->setPrimus($data['primus_transport']);

            $transport->setDateTerminus(new \DateTime($data['date_terminus_transport']));
            $transport->setTerminus($data['terminus_transport']);

            $transport->setNombreKm($data['nombre_km']);
            $transport->setNombreLitre($data['nombre_litre']);
            $transport->setPrixLitre($data['prix_litre']);

            $transport->setCoutTransport($data['cout_transport']);
            $transport->setVoyage($vr->find($data['voyage']));

            // Enregistrer le voyage dans la base de données
            $entityManager->flush();

            // Flash message pour notification
            $this->addFlash('success', 'Le transport a été modifié avec succès.');

            // Rediriger vers la liste des voyages
                
            return $this->redirectToRoute('voyage_detail',  ['id' => $data['voyage'], 'type' => 'transport']); // Remplacez par votre route de liste si différente
        }

        return $this->redirectToRoute('voyages_index');
    }

    #[Route('/prestation/edit', name: 'prestation_edit')]
    public function editPrestation(Request $request, EntityManagerInterface $entityManager, PrestationRepository $pr, VoyageRepository $vr): Response
    {
    
        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Créer une nouvelle instance de Voyage
            $data = $request->request->all(); // Récupérer tous les champs du formulaire

            $prestation = $pr->find($data["id"]);

            // Remplir l'objet $voyage avec les données du formulaire
            $prestation->setPrestation($data['prestation']);
            $prestation->setDatePrestation(new \DateTime($data['date_prestation']));
            $prestation->setCoutPrestation($data['cout_prestation']);
            $prestation->setLieuPrestation($data['lieu_prestation']);
            $prestation->setVoyage($vr->find($data['voyage']));

            // Enregistrer le voyage dans la base de données
            $entityManager->flush();

            // Flash message pour notification
            $this->addFlash('success', 'La prestation a été modifié avec succès.');

            // Rediriger vers la liste des voyages
                
            return $this->redirectToRoute('voyage_detail',  ['id' => $data['voyage'], 'type' => 'prestation']); // Remplacez par votre route de liste si différente
        }

        return $this->redirectToRoute('voyages_index');
    }

    #[Route('/hebergement/new/{id}', name: 'hebergement_new', requirements: ['id' => '\d+'])]
    public function newHebergement(int $id, Request $request, EntityManagerInterface $entityManager, HebergementRepository $hr, VoyageRepository $vr): Response
    {
    
        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Créer une nouvelle instance de Voyage

            $data = $request->request->all(); // Récupérer tous les champs du formulaire

            $hebergement = new Hebergement();

            // Remplir l'objet $voyage avec les données du formulaire
            $hebergement->setNomHebergement($data['nom_hebergement']);
            $hebergement->setLieuHebergement($data['lieu_hebergement']);
            $hebergement->setDateDebut(new \DateTime($data['date_debut']));
            $hebergement->setDateFin(new \DateTime($data['date_fin']));
            $hebergement->setCoutHebergement($data['cout_hebergement']);
            $hebergement->setVoyage($vr->find($id));

            // Enregistrer le voyage dans la base de données
            $entityManager->persist($hebergement);
            $entityManager->flush();

            // Flash message pour notification
            $this->addFlash('success', 'L\'hébergement a été ajoutée avec succès.');

            // Rediriger vers la liste des voyages
                
            return $this->redirectToRoute('voyage_detail',  ['id' => $id, 'type' => 'hebergement']); // Remplacez par votre route de liste si différente
        }

        return $this->redirectToRoute('voyages_index');
    }

    #[Route('/vol/new/{id}', name: 'vol_new', requirements: ['id' => '\d+'])]
    public function newVol(int $id, Request $request, EntityManagerInterface $entityManager, VolRepository $vor, VoyageRepository $vr): Response
    {
    
        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Créer une nouvelle instance de Voyage
            $data = $request->request->all(); // Récupérer tous les champs du formulaire

            $vol = new Vol();

            // Remplir l'objet $voyage avec les données du formulaire
            $vol->setReference($data['ref_vol']);
            $vol->setPrimus($data['primus_vol']);
            $vol->setTerminus($data['terminus_vol']);
            $vol->setDatePrimus(new \DateTime($data['date_primus_vol']));
            $vol->setDateTerminus(new \DateTime($data['date_terminus_vol']));
            $vol->setAgenceVol($data['agence_vol']);
            $vol->setCoutVol($data['cout_vol']);
            $vol->setVoyage($vr->find($id));

            // Enregistrer le voyage dans la base de données
            $entityManager->persist($vol);
            $entityManager->flush();

            // Flash message pour notification
            $this->addFlash('success', 'Le vol a été ajoutée avec succès.');

            // Rediriger vers la liste des voyages
                
            return $this->redirectToRoute('voyage_detail',  ['id' => $id, 'type' => 'vol']); // Remplacez par votre route de liste si différente
        }

        return $this->redirectToRoute('voyages_index');
    }

    #[Route('/transport/new/{id}', name: 'transport_new', requirements: ['id' => '\d+'])]
    public function newTransport(int $id, Request $request, EntityManagerInterface $entityManager, TransportRepository $tr, VoyageRepository $vr): Response
    {
    
        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Créer une nouvelle instance de Voyage
            $data = $request->request->all(); // Récupérer tous les champs du formulaire

            $transport = new Transport();

            $transport->setDatePrimus(new \DateTime($data['date_primus_transport']));
            $transport->setPrimus($data['primus_transport']);

            $transport->setDateTerminus(new \DateTime($data['date_terminus_transport']));
            $transport->setTerminus($data['terminus_transport']);

            $transport->setNombreKm($data['nombre_km']);
            $transport->setNombreLitre($data['nombre_litre']);
            $transport->setPrixLitre($data['prix_litre']);

            $transport->setCoutTransport($data['cout_transport']);
            $transport->setVoyage($vr->find($id));

            // Enregistrer le voyage dans la base de données
            $entityManager->persist($transport);
            $entityManager->flush();

            // Flash message pour notification
            $this->addFlash('success', 'Le transport a été ajoutée avec succès.');

            // Rediriger vers la liste des voyages
                
            return $this->redirectToRoute('voyage_detail',  ['id' => $id, 'type' => 'transport']); // Remplacez par votre route de liste si différente
        }

        return $this->redirectToRoute('voyages_index');
    }

    #[Route('/prestation/new/{id}', name: 'prestation_new', requirements: ['id' => '\d+'])]
    public function newPrestation(int $id, Request $request, EntityManagerInterface $entityManager, PrestationRepository $pr, VoyageRepository $vr): Response
    {
    
        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Créer une nouvelle instance de Voyage
            $data = $request->request->all(); // Récupérer tous les champs du formulaire

            $prestation = new Prestation();

            // Remplir l'objet $voyage avec les données du formulaire
            $prestation->setPrestation($data['prestation']);
            $prestation->setDatePrestation(new \DateTime($data['date_prestation']));
            $prestation->setCoutPrestation($data['cout_prestation']);
            $prestation->setLieuPrestation($data['lieu_prestation']);
            $prestation->setVoyage($vr->find($id));

            // Enregistrer le voyage dans la base de données
            $entityManager->persist($prestation);
            $entityManager->flush();

            // Flash message pour notification
            $this->addFlash('success', 'La prestation a été ajoutée avec succès.');

            // Rediriger vers la liste des voyages
                
            return $this->redirectToRoute('voyage_detail',  ['id' => $id, 'type' => 'prestation']); // Remplacez par votre route de liste si différente
        }

        return $this->redirectToRoute('voyages_index');
    }

    #[Route('/hebergement/{id}/delete', name: 'hebergement_delete', methods: ['POST'])]
    public function deleteHebergement(
        Request $request,
        Hebergement $hebergement,
        EntityManagerInterface $em
        ): Response {
        // dd($hebergement);
        $id = $hebergement->getVoyage()->getId();
        if ($this->isCsrfTokenValid('delete'.$hebergement->getId(), $request->request->get('_token'))) {
            $voyage = $hebergement->getVoyage();
            $message = $hebergement->getNomHebergement()." de #".$voyage->getId()." - ".$voyage->getTourist()." [". $hebergement->getDateDebut()->format("d-m-Y")."] ";
            $em->remove($hebergement);
            $em->flush();

            $this->addFlash('warning', 'Hébergement <strong>'.$message.'</strong> supprimé avec succès.');
        } else {
            $this->addFlash('danger', 'Action non autorisée.');
        }

        return $this->redirectToRoute('voyage_detail', ['id' => $id, 'type' => 'hebergement']);
    }

    #[Route('/transport/{id}/delete', name: 'transport_delete', methods: ['POST'])]
    public function deleteTransport(
        Request $request,
        Transport $transport,
        EntityManagerInterface $em
        ): Response {
        // dd($transport);
        $id = $transport->getVoyage()->getId();
        if ($this->isCsrfTokenValid('delete'.$transport->getId(), $request->request->get('_token'))) {
            $voyage = $transport->getVoyage();
            $message = $transport->getPrimus()."->".$transport->getTerminus()." de  : #".$voyage->getId()." - ".$voyage->getTourist()." [". $transport->getDatePrimus()->format("d-m-Y")."] ";
            $em->remove($transport);
            $em->flush();

            $this->addFlash('success', 'Transport <strong>'.$message.'</strong> supprimé avec succès.');
        } else {
            $this->addFlash('danger', 'Action non autorisée.');
        }

      return $this->redirectToRoute('voyage_detail', ['id' => $id, 'type' => 'transport']);
    }

    #[Route('/vol/{id}/delete', name: 'vol_delete', methods: ['POST'])]
    public function deleteVol(
        Request $request,
        Vol $vol,
        EntityManagerInterface $em
        ): Response {
        // dd($vol);
        $id = $vol->getVoyage()->getId();
        if ($this->isCsrfTokenValid('delete'.$vol->getId(), $request->request->get('_token'))) {
            $voyage = $vol->getVoyage();
            $message = $vol->getPrimus()."->".$vol->getTerminus()." de  : #".$voyage->getId()." - ".$voyage->getTourist()." [". $vol->getDatePrimus()->format("d-m-Y")."] ";
            
            $em->remove($vol);
            $em->flush();

            $this->addFlash('warning', 'Vol <strong>'.$message.'</strong> supprimé avec succès.');
        } else {
            $this->addFlash('danger', 'Action non autorisée.');
        }

      return $this->redirectToRoute('voyage_detail', ['id' => $id, 'type' => 'vol']);
    }

    #[Route('/prestation/{id}/delete', name: 'prestation_delete', methods: ['POST'])]
    public function deletePrestation(
        Request $request,
        Prestation $prestation,
        EntityManagerInterface $em
        ): Response {
        // dd($prestation);
        $id = $prestation->getVoyage()->getId();
        if ($this->isCsrfTokenValid('delete'.$prestation->getId(), $request->request->get('_token'))) {
            $voyage = $prestation->getVoyage();
            $message = $prestation->getPrestation()." de  : #".$voyage->getId()." - ".$voyage->getTourist()." [". $prestation->getDatePrestation()->format("d-m-Y")."] ";
            $em->remove($prestation);
            $em->flush();

            $this->addFlash('warning', 'Prestation <strong>'.$message.'</strong> supprimée avec succès.');
        } else {
            $this->addFlash('danger', 'Action non autorisée.');
        }

      return $this->redirectToRoute('voyage_detail', ['id' => $id, 'type' => 'prestation']);
    }
}