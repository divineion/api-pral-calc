<?php

namespace App\Controller\Security;

use App\Repository\RefreshTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class LogoutController extends AbstractController
{
    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(
        Request $request,
        RefreshTokenRepository $refreshTokenRepository,
        EntityManagerInterface $em,
    ): JsonResponse {
        $tokenValue = $request->cookies->get('REFRESH_TOKEN');

        if ($tokenValue) {
            $refreshToken = $refreshTokenRepository->findOneBy(['refreshToken' => $tokenValue]);
            if ($refreshToken) {
                $em->remove($refreshToken);
                $em->flush();
            }
        }

        $response = new JsonResponse(null, 204);
        $response->headers->clearCookie('REFRESH_TOKEN', '/api', null, false, true, 'strict');

        return $response;
    }
}
