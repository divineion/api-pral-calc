<?php

namespace App\Controller\Security;

use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class LogoutController extends AbstractController
{
    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(
        Request $request,
        RefreshTokenManagerInterface $refreshTokenManager,
    ): JsonResponse {
        $tokenValue = $request->cookies->get('REFRESH_TOKEN');

        if ($tokenValue) {
            $refreshToken = $refreshTokenManager->get($tokenValue);
            if ($refreshToken) {
                $refreshTokenManager->delete($refreshToken);
            }
        }

        $response = new JsonResponse(null, 204);
        $response->headers->clearCookie('REFRESH_TOKEN', '/api', null, false, true, 'strict');

        return $response;
    }
}
