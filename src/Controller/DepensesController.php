<?php

// DepensesController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepensesController extends AbstractController
{
    #[Route('/depenses-carburant', name: 'app_depenses_carburant')]
    public function index(): Response
    {
        $depenses = [
            [
                'date' => '28/12/2025',
                'vehicule' => 'Volkswagen Golf 3',
                'immatriculation' => '9134 TAS',
                'station' => [
                    'nom' => 'Shell Ambanidia',
                    'localisation' => 'Ambanidia'
                ],
                'litres' => 8.58,
                'prix_litre' => '4 660,00 Ar',
                'total' => '39 982,80 Ar',
                'paiement' => 'Espèces'
            ],
            [
                'date' => '22/12/2025',
                'vehicule' => 'Volkswagen Golf 3',
                'immatriculation' => '9134 TAS',
                'station' => [
                    'nom' => 'Shell Mandroseza',
                    'localisation' => 'Mandroseza'
                ],
                'litres' => 8.58,
                'prix_litre' => '4 660,00 Ar',
                'total' => '39 982,80 Ar',
                'paiement' => 'Espèces'
            ],
            [
                'date' => '14/12/2025',
                'vehicule' => 'Volkswagen Golf 3',
                'immatriculation' => '9134 TAS',
                'station' => [
                    'nom' => 'Jovena Ambohijatovo',
                    'localisation' => 'Ambohijatovo'
                ],
                'litres' => 8.60,
                'prix_litre' => '4 660,00 Ar',
                'total' => '40 076,00 Ar',
                'paiement' => 'Espèces'
            ],
            [
                'date' => '16/11/2025',
                'vehicule' => 'Volkswagen Golf 3',
                'immatriculation' => '9134 TAS',
                'station' => [
                    'nom' => 'Shell Bypass',
                    'localisation' => 'Bypass'
                ],
                'litres' => 8.80,
                'prix_litre' => '4 550,00 Ar',
                'total' => '40 040,00 Ar',
                'paiement' => 'Espèces'
            ]
        ];

        return $this->render('depenses/depenses_carburant.html.twig', [
            'depenses' => $depenses,
        ]);
    }
}