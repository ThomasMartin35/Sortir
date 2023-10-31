<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExcursionController extends AbstractController
{
    #[Route('/', name: 'main_home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render("main/home.html.twig");
    }

    #[Route('/create', name: 'excursion_create')]
    public function create(): Response
    {
        return $this->render('excursion/create.html.twig', [
            'controller_name' => 'ExcursionController',
        ]);
    }

    #[Route('/{id}/update', name: 'excursion_update')]
    public function update(): Response
    {
        return $this->render('excursion/update.html.twig', [
            'controller_name' => 'ExcursionController',
        ]);
    }
}
