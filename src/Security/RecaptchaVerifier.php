<?php

namespace App\Security;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Helpers\ApiMessages;


readonly class RecaptchaVerifier
{
    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger
    )
    {
    }

    /**
     * sends a request to Google API to verify the reCAPTCHA token
     *
     * @param string $recaptchaToken sent from client
     * @return JsonResponse specify the success or failure of the recaptcha verification
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function verifyRecaptcha(
        string $recaptchaToken,
    ): JsonResponse
    {

        try {
            $response = $this->client->request(
                'POST',
                'https://www.google.com/recaptcha/api/siteverify',
                ['body' =>
                    ['secret' => $_ENV['GOOGLE_RECAPTCHA_V3_SECRET_KEY'],
                        'response' => $recaptchaToken]
                ]
            );
            $validation = json_decode($response->getContent(), true);

            if(json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Erreur lors du traitement de la réponse JSON du service Google reCAPTCHA.");
            }

            return new JsonResponse([
                "success" => $validation['success'] ?? false,
                "hostname" => $validation['hostname'] ?? null,
                "error-codes" => $validation['error-codes'] ?? [],
            ]);

        } catch (Exception | ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());

            return new JsonResponse([
                "success" => false,
                "message" => "Echec de la vérification Google reCAPTCHA",
                "error" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

