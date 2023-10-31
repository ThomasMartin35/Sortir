<?php

namespace App\Controller;

use App\Repository\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/member/{id}/update', name: 'member_update')]
    public function update(): Response
    {
        return $this->render('member/update.html.twig', [
            'controller_name' => 'MemberController',
        ]);
    }
}
