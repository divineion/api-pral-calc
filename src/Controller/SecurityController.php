<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login', methods: Request::METHOD_POST)]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): JsonResponse
    {
         if ($this->getUser()) {
             return new JsonResponse(['message' => 'Vous êtes déjà connecté.'], Response::HTTP_OK);
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return new JsonResponse([
            'message' => 'JsonResponse : authentification réussie'
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return $this->redirectToRoute('app_home');

        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
