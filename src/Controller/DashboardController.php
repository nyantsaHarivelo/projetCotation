<?php

namespace App\Controller;

use App\Repository\VoyageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(VoyageRepository $vr): Response
    {
        // $data = $vr->countVoyagesParMois();
        // dd($data);
        return $this->render('dashboard/index.html.twig');
    }
}
