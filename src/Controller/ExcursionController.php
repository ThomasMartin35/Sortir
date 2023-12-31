<?php

namespace App\Controller;

use App\Entity\Excursion;
use App\Entity\State;
use App\Form\ExcursionType;
use App\Form\FilterFormType;
use App\Form\Model\FilterModel;
use App\Form\DeleteExcursionType;
use App\Repository\ExcursionRepository;
use App\Repository\StateRepository;
use App\Security\Voter\UsersVoter;
use App\Services\StateManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ExcursionController extends AbstractController
{
    #[Route('/', name: 'main_excursionList')]
    public function list(ExcursionRepository $excursionRepository, Request $request, StateRepository $stateRepository,
                         StateManager        $stateManager, EntityManagerInterface $em): Response
    {


        $currentUser = $this->getUser();
        $filterModel = new FilterModel();
        $filterForm = $this->createForm(FilterFormType::class, $filterModel);
        $filterForm->handleRequest($request);

        $excursions = $excursionRepository->findAllExcursionsWithoutArchived();
        $stateManager->checkExcursionState();

        if ($filterForm->isSubmitted()) {
            $excursions = $excursionRepository->findExcursionByFilters($filterModel, $currentUser, $stateRepository);
        }

        return $this->render('main/home.html.twig', [
            'excursions' => $excursions,
            'filterForm' => $filterForm->createView()
        ]);
    }

    #[Route('/excursion/{id}/details', name: 'excursion_details')]
    public function details(int $id, ExcursionRepository $excursionRepository): Response
    {
        $excursion = $excursionRepository->findOneExcursionWithParticipants($id);
        if (!$excursion) {
            throw $this->createNotFoundException('Sortie non trouvée');
        }
        return $this->render('excursion/details.html.twig', [
            'excursion' => $excursion
        ]);

    }

    #[Route('/excursion/create', name: 'excursion_create', methods: ['GET', 'POST'])]

    public function create(
        Request                $request,
        EntityManagerInterface $em
    ): Response
    {
        $excursion = new Excursion();
        $userCampus = $this->getUser()->getCampus();
        $excursion->setCampus($userCampus);

        $excursionForm = $this->createForm(ExcursionType::class, $excursion);
        $excursionForm->handleRequest($request);

        if ($excursionForm->isSubmitted() && $excursionForm->isValid()) {
            $excursion->setOrganizer($this->getUser());

            $repo = $em->getRepository(State::class);

                $publishClicked = $excursionForm->get('publish')->isClicked();

                if ($publishClicked) {
                    $state = $repo->findOneBy(
                        ['caption' => 'Opened']);
                    $excursion->setState($state);
                } else {
                    $state = $repo->findOneBy(
                        ['caption' => 'Created']);
                    $excursion->setState($state);
                }

            $excursion->setState($state);

            $organizer = $this->getUser();
            $excursion->setOrganizer($organizer);
            $excursion->addParticipant($organizer);

            $em->persist($excursion);
            $em->flush();
            $this->addFlash('success', 'La sortie a été créée avec succès');
            return $this->redirectToRoute('main_excursionList');

        }

        return $this->render('\excursion\create.html.twig', [
            'excursionForm' => $excursionForm
        ]);
    }

    #[Route('/excursion/{id}/update', name: 'excursion_update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function update(
        int                    $id,
        Excursion              $excursion,
        Request                $request,
        EntityManagerInterface $em
    ): Response
    {

        $this->denyAccessUnlessGranted(UsersVoter::EXCURSION_EDITPUBLISH, $excursion);
        $excursion = $em->getRepository(Excursion::class)->find($id);

        if (!($excursion->getOrganizer() === $this->getUser() || $this->isGranted('ROLE_ADMIN'))) {
            return $this->redirectToRoute('excursion_details', ['id' => $excursion->getId()]);
        }

        $excursionForm = $this->createForm(ExcursionType::class, $excursion);
        $excursionForm->handleRequest($request);
        $repoUpdated = $em->getRepository(State::class);

        if ($excursionForm->isSubmitted() && $excursionForm->isValid()) {
            $publishClicked = $excursionForm->get('publish')->isClicked();

            if ($publishClicked) {
                $state = $repoUpdated->findOneBy(
                    ['caption' => 'Opened']);
                $excursion->setState($state);
            } else {
                $state = $repoUpdated->findOneBy(
                    ['caption' => 'Created']);
                $excursion->setState($state);
            }


        $em->persist($excursion);
        $em->flush();
        $this->addFlash('success', 'La sortie a été modifiée');
        return $this->redirectToRoute('excursion_details', ['id' => $excursion->getId()]);
    }
        return $this->render('excursion/update.html.twig', [
            'excursion' => $excursion,
            'excursionForm' => $excursionForm
        ]);
    }


    #[Route('/excursion/{id}/delete', name: 'excursion_delete', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function delete(

        Excursion              $excursion,
        Request                $request,
        EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(UsersVoter::EXCURSION_VIEWCANCEL, $excursion);

        $deleteExcursionForm = $this->createForm(DeleteExcursionType::class, $excursion);
        $deleteExcursionForm->handleRequest($request);

        if ($deleteExcursionForm->isSubmitted() && $deleteExcursionForm->isValid() and
            $excursion->getOrganizer() === $this->getUser() or $this->isGranted('ROLE_ADMIN')
        ) {
            $reason = $deleteExcursionForm->get('reason')->getData();
            $excursion->setDescription("SORTIE ANNULEE");
            $excursion->setReason($reason);
            $repo = $em->getRepository(State::class);
            $state = $repo->findOneBy(
                ['caption' => 'Canceled']);
            $excursion->setState($state);

            $em->persist($excursion);
            $em->flush();
//            throw $this->createAccessDeniedException();

            if (!empty($reason)) {
                $this->addFlash('success', 'La sortie a été supprimée');
                return $this->redirectToRoute('excursion_details', ['id' => $excursion->getId()]);
            }


        }

        return $this->render('excursion/delete.html.twig', [
            'excursion' => $excursion,
            'deleteExcursionForm' => $deleteExcursionForm
        ]);
    }

    #[Route('/excursion/{id}/subscribe', name: 'excursion_subscribe', requirements: ['id' => '\d+'])]
    public function addParticipant(
        Excursion              $excursion,
        EntityManagerInterface $em
    ): Response
    {
        $this->denyAccessUnlessGranted(UsersVoter::EXCURSION_SUBSCRIBE, $excursion);

        $user = $this->getUser();

        $excursion->addParticipant($user);

        $em->persist($excursion);
        $em->flush();
        $this->addFlash('success', 'Vous êtes inscrit !');

        return $this->redirectToRoute('excursion_details', ['id' => $excursion->getId()]);

    }

    #[Route('/excursion/{id}/unsubscribe', name: 'excursion_unsubscribe', requirements: ['id' => '\d+'])]
    public function removeParticipant(
        Excursion              $excursion,
        EntityManagerInterface $em
    ): Response
    {
        $this->denyAccessUnlessGranted(UsersVoter::EXCURSION_UNSUBSCRIBE, $excursion);

        $user = $this->getUser();

        $excursion->removeParticipant($user);

        $em->persist($excursion);
        $em->flush();
        $this->addFlash('info', 'Vous êtes désinscrit !');

        return $this->redirectToRoute('excursion_details', ['id' => $excursion->getId()]);

    }

    #[Route('/excursion/{id}/deleteDraft', name: 'excursion_deletedraft', requirements: ['id' => '\d+'])]
    public function removeDraft(
        Excursion              $excursion,
        EntityManagerInterface $em
    ): Response
    {
        $this->denyAccessUnlessGranted(UsersVoter::EXCURSION_EDITPUBLISH, $excursion);

        $em->remove($excursion);
        $em->flush();
        $this->addFlash('success', 'La sortie a été supprimée');

        return $this->redirectToRoute('main_excursionList');

    }



}