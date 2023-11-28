<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddTagToSubjectController extends AbstractController
{
    public function __invoke(): \Symfony\Component\Security\Core\User\UserInterface
    {
        $addTag = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur inexistant');
        }

        return $user;
    }
}
