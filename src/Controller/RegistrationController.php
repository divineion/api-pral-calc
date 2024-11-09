<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\AppLoginAuthenticator;
use App\Security\EmailVerifier;
use App\Security\RecaptchaVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Helpers\ApiMessages;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AllowDynamicProperties] class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly EmailVerifier $emailVerifier,
        private readonly RecaptchaVerifier $recaptchaVerifier,
        UserController $userController,
        LoggerInterface $logger,
        ParameterBagInterface $parameterBag,
    )
    {
        $this->logger = $logger;
        $this->userController = $userController;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    #[Route('/register', name: 'app_register', methods: Request::METHOD_POST)]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        Security $security,
    ): ? JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        $this->recaptchaVerifier->verifyRecaptcha($data["recaptchaToken"]);

        try {
            $user = new User();
            $email = $data['email'];
            $username = $data['username'];
            $password = $data['password'];
            $date = new \DateTime();

            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
            );
            $user->setMemberSince($date->format('Y-m-d'));

            $entityManager->persist($user);
            $entityManager->flush();
        }
        catch (Exception $e) {
             $this->logger->error("INTERNAL SERVER ERROR : " . $e->getMessage());
             return new JsonResponse([
                 "status" => "error",
                 "message" => ApiMessages::ACCOUNT_CREATION_FAILURE],
                 Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $address = $this->parameterBag->get('MAILER_DSN_USERNAME');

            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address($address, 'Equipe Pral Calc'))
                    ->to($user->getEmail())
                    ->subject('Bienvenue sur Pral-Calc !')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
        } catch (VerifyEmailExceptionInterface $e) {
            $this->logger->error("Verify email error : " . $e->getMessage());
        }


        $security->login($user, AppLoginAuthenticator::class);

        return new JsonResponse(Response::HTTP_CREATED);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(
        Request $request,
        UserRepository $userRepository,
        Security $security
    ): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return new JsonResponse(Response::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return new JsonResponse(Response::HTTP_BAD_REQUEST);
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return new JsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $security->login($user, AppLoginAuthenticator::class);

        return new JsonResponse(Response::HTTP_OK);
    }
}
