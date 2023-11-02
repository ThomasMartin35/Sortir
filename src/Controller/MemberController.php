<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    #[Route('/member/{id}', name: 'member_details', requirements: ['id' => '\d+'])]
    public function details(int $id, MemberRepository $memberRepository): Response
    {
        $member = $memberRepository->find($id);
        if (!$member) {
            throw $this->createNotFoundException('Membre introuvable.');
        }
        return $this->render('member/details.html.twig', [
            'member' => $member
        ]);
    }

    #[Route('/member/{id}/update', name: 'member_update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function update(Member $member, Request $request, EntityManagerInterface $em): Response
    {
        //TODO Don't forget the createAccessDeniedException
        $memberUpdateForm = $this->createForm(MemberType::class, $member);
        $memberUpdateForm->handleRequest($request);

        if ($memberUpdateForm->isSubmitted() && $memberUpdateForm->isValid()) {
            $em->persist($member);
            $em->flush();
            return $this->redirectToRoute('member_details', ['id'=> $member->getId()]);
        }


        return $this->render('member/update.html.twig', [
            'member' => $member,
            'memberUpdateForm' => $memberUpdateForm
        ]);
    }
}
