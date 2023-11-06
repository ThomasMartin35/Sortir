<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    #[Route('/place', name: 'app_place')]
    public function index(): Response
    {
        return $this->render('place/index.html.twig', [
            'controller_name' => 'PlaceController',
        ]);
    }

    #[Route('/place/create', name: 'place_create', methods: ['GET', 'POST'])]

    public function create(
        Request                $request,
        EntityManagerInterface $em
    ): Response
    {
        $place = new Place();
        $placeForm = $this->createForm(PlaceType::class, $place);
        $placeForm->handleRequest($request);

        if ($placeForm->isSubmitted() && $placeForm->isValid()) {
            $em->persist($place);
            $em->flush();
            return $this->redirectToRoute('main_excursionList');
        }

        return $this->render('\place\create.html.twig', [
            'placeForm' => $placeForm
        ]);
    }
}
