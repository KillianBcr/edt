<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/react', name: 'app_react')]
    #[Route('/react/semesters', name: 'app_react_semesters')]
    #[Route('/react/semesters/{id}', name: 'app_react_semester', requirements: ['id' => '\d+'])]
    public function react(): Response
    {
        return $this->render('home/react.html.twig');
    }
}
