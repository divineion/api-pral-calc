<?php

namespace App\Controller\Security;

use App\DTO\Request\RegisterRequest;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $em,
        private readonly MailerInterface $mailer,
    ) {}

    /**
     * @throws RandomException
     * @throws TransportExceptionInterface
     */
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload] RegisterRequest $request,
    ): JsonResponse {
        // Check for duplicate email
        if ($this->userRepository->findOneByIdentifier($request->email)) {
            return new JsonResponse(['error' => 'Email already registered'], 409);
        }

        // Check for duplicate username
        if ($this->userRepository->findOneByUsername($request->username)) {
            return new JsonResponse(['error' => 'Username already taken'], 409);
        }

        $user = new User();
        $user->setEmail($request->email);
        $user->setUsername($request->username);
        $user->setPassword($this->passwordHasher->hashPassword($user, $request->password));
        $user->setMemberSince((new \DateTimeImmutable())->format('Y-m-d'));
        $user->setRoles([]);

        // Generate verification token (64-char hex, cryptographically secure)
        $token = bin2hex(random_bytes(32));
        $user->setVerificationToken($token);
        $user->setTokenExpiresAt(new \DateTimeImmutable('+24 hours'));

        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch (UniqueConstraintViolationException) {
            // Race condition safety net — explicit checks above handle the common case
            return new JsonResponse(['error' => 'Email or username already registered'], 409);
        }

        // Send verification email (plain HTML, no Twig — TemplatedEmail is excluded)
        $frontendUrl = $_ENV['FRONTEND_URL'] ?? 'http://localhost:3000';
        $verificationEmail = (new Email())
            ->from('noreply@pral-calc.fr')
            ->to($user->getEmail())
            ->subject('Vérifiez votre adresse email - PRAL Calc')
            ->html(sprintf(
                '<p>Bonjour %s,</p>'
                . '<p>Merci de vous être inscrit sur PRAL Calc.</p>'
                . '<p>Cliquez sur le lien ci-dessous pour vérifier votre adresse email :</p>'
                . '<p><a href="%s/verify-email/%s">Vérifier mon adresse email</a></p>'
                . '<p>Ce lien expire dans 24 heures.</p>'
                . '<p>Si vous n\'avez pas créé de compte, ignorez cet email.</p>',
                $user->getFullName(),
                $frontendUrl,
                $token
            ));
        $this->mailer->send($verificationEmail);

        return new JsonResponse(['message' => 'Account created. Check your email to verify your account.'], 201);
    }

    #[Route('/api/verify-email/{token}', name: 'api_verify_email', methods: ['GET'])]
    public function verifyEmail(string $token): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['verificationToken' => $token]);

        if (!$user) {
            return new JsonResponse(['error' => 'Invalid verification token'], 400);
        }

        if ($user->getTokenExpiresAt() < new \DateTimeImmutable()) {
            return new JsonResponse(['error' => 'Verification token has expired'], 400);
        }

        $user->setIsVerified(true);
        $user->setVerificationToken(null);
        $user->setTokenExpiresAt(null);
        $this->em->flush();

        return new JsonResponse(['message' => 'Email verified successfully'], 200);
    }
}
