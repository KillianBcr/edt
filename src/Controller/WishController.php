<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class WishController extends AbstractController
{
    #[Route('/wish', name: 'app_wish')]
    public function index(WishRepository $repository): Response
    {
        $wishes = $repository->findAll();

        return $this->render('wish/index.html.twig', [
            'wishes' => $wishes,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/wish/new', name: 'app_wish_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(WishType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wish = $form->getData();
            $subjectId = $request->query->get('subjectId');

            if (null !== $subjectId) {
                // Utilisez l'EntityManager pour charger l'entité Subject
                $subject = $manager->getRepository(Subject::class)->find($subjectId);

                if (null !== $subject) {
                    $wish->setSubjectId($subject);
                }
            }

            $manager->persist($wish);
            $manager->flush();

            return $this->redirectToRoute('app_wish');
        }

        return $this->render('/wish/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/wish/edit/{id}', 'app_wish_edit', methods: ['GET', 'POST'])]
    public function edit(Wish $wish, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            return $this->redirectToRoute('app_user_wish_show', ['id' => $wish->getWishUser()->getId()]);
        }

        return $this->render('/_partials/_form.html.twig', [
            'form' => $form->createView(),
            'wish' => $wish,
        ]);
    }

    #[Route('wish/delete/{id}', 'app_wish_delete', methods: ['GET'])]
    public function delete(Wish $wish, EntityManagerInterface $manager): Response
    {
        $manager->remove($wish);
        $manager->flush();

        return $this->redirectToRoute('app_wish');
    }
}
