<?php

namespace App\Controller;

use App\Repository\ExcursionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExcursionController extends AbstractController
{
    #[Route('/', name: 'main_excursionList', methods: ['GET'])]
    public function list(ExcursionRepository $excursionRepository): Response
    {
        $excursions = $excursionRepository->findAll();
        dump($excursions);
        return $this->render('main/home.html.twig', [
            'excursions' => $excursions
        ]);
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

    #[Route('/{id}/delete', name: 'excursion_delete')]
    public function delete(): Response
    {
        return $this->render('excursion/delete.html.twig', [
            'controller_name' => 'ExcursionController',
        ]);
    }
}
