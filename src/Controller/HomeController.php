<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): JsonResponse
    {
        $user = new User();

        if ($this->getUser()) {
            $currentUser = $this->getUser();
            return new JsonResponse([
                'message' => 'Vous êtes connecté(e)',
                'user' => $currentUser->getUserIdentifier(),
                'path' => 'src/Controller/HomeController.ph',

           ]);
        }


        else {
            return  new JsonResponse([
                'message' => 'Vous n\'êtes pas connecté(e)',
                'path' => 'src/Controller/HomeController.php',
            ]);
        }
    }
}
