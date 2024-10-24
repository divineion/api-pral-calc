<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[AllowDynamicProperties] class ApiLoginController extends AbstractController
{
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger, JWTTokenManagerInterface $tokenManager, UserRepository $userRepository)
    {
        $this->logger = $logger;
        $this->tokenManager = $tokenManager;
        $this->userRepository = $userRepository;
    }
    #[Route('/api/login_check', name: 'app_api_login_check', methods: ['POST'])]
    public function index(
        Request $request,
    ): Response
    {
        $data = json_decode($request->getContent(), true);
        $username = $data["username"];
        $user = $this->userRepository->findOneByIdentifier($username);

        if (null !== $user) {
            try {
                $token = $this->tokenManager->create($user);

                if (empty($token)) {
                    $this->logger->error("The token generated for the user identified by {$user->getUserIdentifier()} is empty after creation");

                    $payload = [
                        'username' => $user->getUserIdentifier(),
                        'roles' => $user->getRoles(),
                    ];

                    $token = $this->tokenManager->createFromPayload($user, $payload);

                    return new JsonResponse([
                        'user' => $user->getUserIdentifier(),
                        'token' => $token,
                        'id' => $user->getId(),
                    ]);
                }

                else {
                    $this->logger->info("A token has been successfully generated for the user identified by {$user->getUserIdentifier()}.");
                }

                $response = new JsonResponse([
                    'user' => $user->getUserIdentifier(),
                    'token' => $token,
                    'id' => $user->getId(),
                ]);

                $cookie = Cookie::create('auth_token')
                    ->withValue($token)
                    ->withExpires(time()+604800) // 7 days
                    ->withSecure(true)
                    ->withSameSite('lax')
                    ->withPartitioned(true)
                    ->withDomain('localhost')
                    ->withHttpOnly(true);

                $response->headers->setCookie($cookie);

                return $response;

            } catch (\Exception $exception) {
                $this->logger->error('Error creating token:', ['error' => $exception->getMessage()]);
                return new JsonResponse(['message' => 'Error creating token'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            $this->logger->warning('No user found for given credentials.');
            return new JsonResponse([
                'message' => 'missing credentials',
                Response::HTTP_UNAUTHORIZED
            ]);
        }
    }
}
