<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    #[Route('/member/{id}', name: 'member_details')]
    public function details(): Response
    {
        return $this->render('member/details.html.twig', [
            'controller_name' => 'MemberController',
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
