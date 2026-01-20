<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class LocaleController extends AbstractController
{
    #[Route('/change-lang/{locale}', name: 'change_language')]
    public function changeLanguage(string $locale, Request $request): RedirectResponse
    {
        $request->getSession()->set('_locale', $locale);

        return $this->redirect($request->headers->get('referer') ?? '/');
    }
}
