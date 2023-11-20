<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetMeController extends AbstractController
{
    public function __invoke(): \Symfony\Component\Security\Core\User\UserInterface
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur inexistant');
        }

        return $user;
    }
}
