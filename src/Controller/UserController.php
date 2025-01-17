<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[AllowDynamicProperties] class UserController extends AbstractController
{
    public const ROUTE_PREFERENCE_EDIT = 'preference_edit';
    public const ROUTE_PREFERENCE_FETCH = 'preference_fetch';
    public const ROUTE_USER_FETCH = 'user_fetch';
    public const ROUTE_USER_DELETE = 'user_delete';
    public const ROUTE_FIND_ALL_USER_EVENTS = 'find_all_user_events';

    public function __construct(
        UserRepository $repo,
        EventRepository       $eventRepository,
        RecipeRepository     $recipeRepository,
    )
    {
        $this->repo = $repo;
        $this->eventRepository = $eventRepository;
        $this->recipeRepository = $recipeRepository;
    }

    #[Route('/api/profile/{id}', name: self::ROUTE_PREFERENCE_FETCH, methods: Request::METHOD_GET)]
    public function index(
        User $user,
    ): JsonResponse
    {
        $preference = $user->getNutrientsDisplayPreference();

        return new JsonResponse($preference);
    }

    #[Route('/api/profile/{id}', name: self::ROUTE_PREFERENCE_EDIT, methods: Request::METHOD_POST)]
    public function updateNutrientsDisplayPreference(
        User $user,
        Request $request,
        EntityManagerInterface $em,
    )
    : JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user->setNutrientsDisplayPreference($data);

        $em->persist($user);
        $em->flush();

        return new JsonResponse($user->getNutrientsDisplayPreference());
    }

    /**
     * @throws \ReflectionException
     */
    #[Route('/api/profile/{id}/events', name: self::ROUTE_FIND_ALL_USER_EVENTS, methods: Request::METHOD_GET)]
    public function fetchUserEvents(
        Request $request,
        EntityManagerInterface $entityManager,
        User $user,
    ): JsonResponse
    {
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        $events = $this->eventRepository->findAllEventsByUser($user, $startDate, $endDate);
        $eventsArray = array_map(function($event) {
            return [
                'id' => $event->getId(),
                'date' => $event->getDate(),
                'content' => $event->getContent(),
                'title' => $event->getTitle(),
            ];
        }, $events);

        return new JsonResponse($eventsArray);
    }

    #[Route('/api/user/{email}', name: self::ROUTE_USER_FETCH, methods: Request::METHOD_GET)]
    public function fetch(
        ?string $email
    ): JsonResponse
    {
        $user = $this->repo->findOneByIdentifier($email);

        return new JsonResponse(
            [
                "userId" => $user->getId(),
                "username" => $user->getFullName(),
                "customization" => $user->getNutrientsDisplayPreference()
            ]);
    }

    #[Route('/api/profile/verify/{username}', methods: Request::METHOD_GET)]
    public function verifyUsernameIsAvailable(
        string $username,
    ): JsonResponse
    {
        $usernameAlreadyExists = $this->repo->findOneByUsername($username);

        if (!$usernameAlreadyExists) {
            return new JsonResponse(true);
        }

        return new JsonResponse(false);
    }

    #[Route('/api/profile/{id}/delete', name: self::ROUTE_USER_DELETE, methods: Request::METHOD_DELETE)]
    public function delete(
        Request $request,
        User $user,
        EntityManagerInterface $em,
    ): JsonResponse
    {
        if ($user) {
            $em->remove($user);
            $em->flush();

            return new JsonResponse("Votre compte a été supprimé", 200);
        } else {
            return new JsonResponse("Votre compte n'existe pas", 400);
        }
    }
}
