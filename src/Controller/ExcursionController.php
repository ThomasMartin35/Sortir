<?php

namespace App\Controller;

use App\Entity\Excursion;
use App\Entity\State;
use App\Form\ExcursionType;
use App\Repository\ExcursionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/excursion/{id}/details', name: 'excursion_details')]
    public function details(int $id, ExcursionRepository $excursionRepository): Response
    {
        $excursion = $excursionRepository->find($id);
        if (!$excursion){
            throw $this->createNotFoundException('Excursion non trouvé');
        }
        return $this->render('excursion/details.html.twig', [
            'excursion' => $excursion
        ]);
    }

    #[Route('/excursion/create', name: 'excursion_create', methods: ['GET', 'POST'])]
    // TODO Ajouter ISGranted ?

    public function create(
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $excursion = new Excursion();
        $excursionForm = $this->createForm(ExcursionType::class, $excursion);
        $excursionForm -> handleRequest($request);

        if ($excursionForm->isSubmitted() && $excursionForm->isValid()){
            $excursion->setOrganizer($this->getUser());

            //TODO Gérer l'Etat avec conditions
            $repo = $em->getRepository(State::class);


            $state = $repo->find('19');

            $excursion->setState($state);


            $em->persist($excursion);
            $em->flush();
            return $this->redirectToRoute('/');

        }

        return $this->render('\excursion\create.html.twig', [
            'excursionForm' => $excursionForm
        ]);
    }

    #[Route('/excursion/{id}/update', name: 'excursion_update')]
    public function update(): Response
    {
        return $this->render('excursion/update.html.twig', [
            'controller_name' => 'ExcursionController',
        ]);
    }

    #[Route('/excursion/{id}/delete', name: 'excursion_delete')]
    public function delete(): Response
    {
        return $this->render('excursion/delete.html.twig', [
            'controller_name' => 'ExcursionController',
        ]);
    }
}