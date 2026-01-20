<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/itineraire', name : 'itineraire_')]
class ItineraireController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(): Response
    {
        $itineraire = [
            [
                'id' => 1,
                'itineraire' => 'Antsirabe',
                'nbKm' => '2025-10-04',
                'divParCent' => '2025-10-10',
                'consommation' => '2025-10-10',
                'nbL' => '2025-10-10',
                'pxL' => '2025-10-10',
                'coutCarburant' => '2025-10-10',
            ],
            [
                'id' => 2,
                'itineraire' => 'Circuit Est Madagascar',
                'nbKm' => '2025-10-04',
                'divParCent' => '2025-10-10',
                'consommation' => '2025-10-10',
                'nbL' => '2025-10-10',
                'pxL' => '2025-10-10',
                'coutCarburant' => '2025-10-10',
            ],
        ];
        return $this->render('itineraire/list.html.twig', [
            'itineraire' =>$itineraire,
        ]);
    }
}
