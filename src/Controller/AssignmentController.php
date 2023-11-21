<?php

namespace App\Controller;

use App\Entity\Assignment;
use App\Form\AssignmentType;
use App\Repository\AssignmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssignmentController extends AbstractController
{
    #[Route('/assignment', name: 'app_assignment')]
    public function index(AssignmentRepository $repository): Response
    {
        $assignments = $repository->findAll();

        return $this->render('assignment/index.html.twig', [
            'assignments' => $assignments,
        ]);
    }

    #[Route('/assignment/new', name: 'app_assignment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $assignment = new Assignment();
        $form = $this->createForm(AssignmentType::class, $assignment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $assignment = $form->getData();

            $manager->persist($assignment);
            $manager->flush();

            return $this->redirectToRoute('app_assignment');
        }

        return $this->render('/assignment/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('assignment/delete/{id}', 'app_assignment_delete', methods: ['GET'])]
    public function delete(Assignment $assignment, EntityManagerInterface $manager): Response
    {
        $manager->remove($assignment);
        $manager->flush();

        return $this->redirectToRoute('app_assignment');
    }
}
