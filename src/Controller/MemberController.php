<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function update(Member $member, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $HashedPassword): Response
    {
        //Permet de récupérer l'utilisateur actuel
        $user = $this->getUser();

        //Condition pour ne pas autoriser la personne à modifier le profil.
        if ($user === null || $user->getId() !== $member->getId()) {
            throw $this->createAccessDeniedException('Vous n’êtes pas autorisé à modifier ce profil.');
        }

        $memberUpdateForm = $this->createForm(MemberType::class, $member, ['isEdit'=>true]);
        $memberUpdateForm->handleRequest($request);

        if ($memberUpdateForm->isSubmitted() && $memberUpdateForm->isValid()) {
            $password = $memberUpdateForm->get('password')->getData();
            if (!empty($password)) {
                $newhashedPassword = $HashedPassword->hashPassword($member, $password);
                $member->setPassword($newhashedPassword);
            }
            $em->persist($member);
            $em->flush();
            $em->refresh($member);
            return $this->redirectToRoute('member_details', ['id' => $member->getId()]);
        }
        return $this->render('member/update.html.twig', [
            'member' => $member,
            'memberUpdateForm' => $memberUpdateForm
        ]);
    }
}


