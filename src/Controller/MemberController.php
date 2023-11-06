<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use App\Services\FileUploader;
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
    public function update(Member                      $member,
                           Request                     $request,
                           EntityManagerInterface      $em,
                           UserPasswordHasherInterface $HashedPassword,
                           FileUploader                $fileUploader): Response
    {
        //Permet de récupérer l'utilisateur actuel
        $user = $this->getUser();

        //Condition pour ne pas autoriser la personne à modifier le profil.
        if ($user === null || ($user->getId() !== $member->getId() && !$this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException('Vous n’êtes pas autorisé à modifier ce profil.');
        }

        $memberUpdateForm = $this->createForm(MemberType::class, $member, ['isEdit' => true]);
        $memberUpdateForm->handleRequest($request);

        if ($memberUpdateForm->isSubmitted() && $memberUpdateForm->isValid()) {
            $password = $memberUpdateForm->get('password')->getData();
            if (!empty($password)) {
                $newhashedPassword = $HashedPassword->hashPassword($member, $password);
                $member->setPassword($newhashedPassword);
                $this->addFlash('success', 'Le mot de passe a bien été modifié');
            }

            //Traitement de l'image
            $imageFile = $memberUpdateForm->get('image')->getData();
            if (($memberUpdateForm->has('deleteImage') && $memberUpdateForm['deleteImage']->getData())
                || $imageFile) {

                //Suppression de l'ancienne image
                $fileUploader->delete(
                    $member->getFilename(),
                    $this->getParameter('app.image_member_directory'));


                //Ajout d'une nouvelle image
                if ($imageFile) {
                    $member->setFilename($fileUploader->upload($imageFile));
                    $this->addFlash('success', 'La photo a bien été changée');
                } else {
                    $member->setFilename(null);
                }
            }

            $em->persist($member);
            $em->flush();
            $this->addFlash('success', 'Le profil a bien été modifié');

            $em->refresh($member);
            return $this->redirectToRoute('member_details', ['id' => $member->getId()]);
        }
        return $this->render('member/update.html.twig', [
            'member' => $member,
            'memberUpdateForm' => $memberUpdateForm
        ]);
    }
}


