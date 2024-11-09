<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Helpers\ApiMessages;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AllowDynamicProperties] class ApiLoginController extends AbstractController
{
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger, JWTTokenManagerInterface $tokenManager, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->logger = $logger;
        $this->tokenManager = $tokenManager;
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }
    #[Route('/api/login_check', name: 'app_api_login_check', methods: ['POST'])]
    public function index(
        Request $request,
    ): Response
    {
        $data = json_decode($request->getContent(), true);
        $username = $data["username"];
        $password = $data["password"];
        $user = $this->userRepository->findOneByIdentifier($username);

        if (null !== $user && $this->userPasswordHasher->isPasswordValid($user, $password)) {
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
                    ->withSecure(true) // set to true on production
                    ->withSameSite('none') //set to lax on production
                    ->withPartitioned(true)
                    //->withDomain('localhost') 
                    ->withHttpOnly(true);

                $response->headers->setCookie($cookie);
                $this->logger->info('set cookie with token, value : '. $token);

                return $response;

            } catch (\Exception $exception) {
                $this->logger->error('Error creating token:', ['error' => $exception->getMessage()]);
                return new JsonResponse(['message' => 'Error creating token'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            if (!$user) {
                return new JsonResponse(
                    ['message' => ApiMessages::REGISTER_FAILURE_UNKNOWN_USER],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            elseif ($password !== $this->userPasswordHasher->hashPassword($user, $user->getPassword())) {
                return new JsonResponse(
                    ['message' => ApiMessages::REGISTER_FAILURE_PASSWORD_ERROR],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            return new JsonResponse(
                ['message' => "Identifiants manquants ou invalides"],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }
}
