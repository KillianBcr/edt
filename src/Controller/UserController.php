<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/users', name: 'app_user')]
    public function index(UserRepository $repository): Response
    {
        $users = $repository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/{id}', name: 'app_user_wish_show',
        requirements: [
            'id' => "\d+",
        ]
    )]
    public function showWishes(User $user): Response
    {
        $wishes = $user->getWish();

        return $this->render('user/showWish.html.twig',
            ['wishes' => $wishes, 'user' => $user]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/me', name: 'app_user_show')]
    public function showCurrentUser(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page');
        }
        $userId = $user->getId();

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/me/edit/{id}', name: 'app_user_edit', requirements: ['id' => '\d+'])]
    public function edit(ManagerRegistry $doctrine, Request $request, User $user): Response
    {
        $form = $this->createForm(userType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFirstname($form->getData()->getFirstname());
            $user->setLastname($form->getData()->getLastname());
            $user->setEmail($form->getData()->getEmail());
            $user->setAddress($form->getData()->getAddress());

            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_user_show', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/_form.html.twig', [
            'advertisement' => $user,
            'form' => $form->createView(),
        ]);
    }
}
