<?php

namespace App\Controller;

use App\Entity\Excursion;
use App\Form\ExcursionType;
use App\Form\FilterFormType;
use App\Form\Model\FilterModel;
use App\Repository\ExcursionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExcursionController extends AbstractController
{
    #[Route('/', name: 'main_excursionList')]
    public function list(ExcursionRepository $excursionRepository, Request $request): Response
    {
        $filterModel = new FilterModel();
        $filterForm = $this->createForm(FilterFormType::class, $filterModel);
        $filterForm->handleRequest($request);

//        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
//
//        }

//        $excursions = $excursionRepository->findExcursionByFilters($filterModel);

        $excursions = $excursionRepository->findAll();
        dump($excursions);
        return $this->render('main/home.html.twig', [
            'excursions' => $excursions,
            'filterForm' => $filterForm->createView()
        ]);

    }

    #[Route('/excursion/{id}/details', name: 'excursion_details')]
    public function details(int $id, ExcursionRepository $excursionRepository): Response
    {
        $excursion = $excursionRepository->find($id);
        if (!$excursion) {
            throw $this->createNotFoundException('Excursion non trouvé');
        }
        return $this->render('excursion/details.html.twig', [
            'excursion' => $excursion
        ]);
    }

    #[Route('/excursion/create', name: 'excursion_create', methods: ['GET', 'POST'])]
    // TODO Ajouter ISGranted ?

    public function create(
        Request                $request,
        EntityManagerInterface $em
    ): Response
    {
        $excursion = new Excursion();
        $excursionForm = $this->createForm(ExcursionType::class, $excursion);
        $excursionForm->handleRequest($request);

        if ($excursionForm->isSubmitted() && $excursionForm->isValid()) {
            $excursion->setOrganizer($this->getUser());

            //TODO Gérer l'Etat
            $excursion->setState(19);
            $em->persist($excursion);
            $em->flush();
            return $this->redirectToRoute('/excursion/{id}/details', ['id' => $excursion->getId()]);

        }

        return $this->render('\excursion\create.html.twig', [
            'excursionForm' => $excursionForm
        ]);
    }

    #[Route('/excursion/{id}/update', name: 'excursion_update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function update(
        Excursion              $excursion,
        Request                $request,
        EntityManagerInterface $em
    ): Response
    {
        $excursionForm = $this->createForm(ExcursionType::class, $excursion);
        $excursionForm->handleRequest($request);

        if ($excursionForm->isSubmitted() && $excursionForm->isValid()) {
            $em->persist($excursion);
            $em->flush();
            $this->addFlash('success', 'La sortie a été modifé');
            return $this->redirectToRoute('excursion_details', ['id' => $excursion->getId()]);
        }

        return $this->render('excursion/update.html.twig', [
            'excursionForm' => $excursionForm
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