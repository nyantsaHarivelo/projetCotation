<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/import')]
class ImportController extends AbstractController
{
    #[Route('/excel', name: 'import_excel')]
    public function excel(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $file = $request->files->get('excel_file');

            if ($file) {
                // Pour l’instant on simule l’import
                $this->addFlash('success', 'Fichier importé avec succès (simulation).');
            } else {
                $this->addFlash('danger', 'Aucun fichier sélectionné.');
            }
        }

        return $this->render('import/excel.html.twig');
    }
}
