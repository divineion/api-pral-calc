<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[AllowDynamicProperties] class EventController extends AbstractController
{
    public const ROUTE_FETCH = 'event_fetch';
    public const ROUTE_CREATE = 'event_create';
    public const ROUTE_EDIT = 'event_edit';
    public const ROUTE_DELETE = 'event_delete';

    public function __construct(
        UserRepository         $userRepository,
        EventRepository        $eventRepository,
        EntityManagerInterface $em,
        JWTTokenManagerInterface          $jwtManager,
    )
    {
        $this->userRepository = $userRepository;
        $this->eventRepository = $eventRepository;
        $this->em = $em;
        $this->jwtManager = $jwtManager;
    }

    #[Route('/api/event/{id}', name: self::ROUTE_FETCH, methods: Request::METHOD_GET)]
    public function fetch(
        int $id
    ): JsonResponse
    {
        $event = $this->eventRepository->find($id);

        $data = [
            'id' => $event->getId(),
            'title' => $event->getTitle(),
            'date' => $event->getDate(),
            'content' => $event->getContent(),
            'user' => $event->getUser()->getId(),
            'totalPralIndex' => $event->getTotalPralIndex(),
        ];

        return new JsonResponse($data);
    }

    #[Route('/api/event', name: self::ROUTE_CREATE, methods: Request::METHOD_POST)]
    public function createEvent(
        Request $request,
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $title = $data["title"];
        $content = $data["content"];
        $user = $this->userRepository->findOneById($data["user"]);
        $date = $data["date"];
        $totalPralIndex = $data["totalPralIndex"];

        $event = new Event();
        $event->setUser($user);
        $event->setContent($content);
        $event->setDate($date);
        $event->setTitle($title);
        $event->setTotalPralIndex($totalPralIndex);

        $this->em->persist($event);
        $this->em->flush();

        return new JsonResponse($event->getContent(), Response::HTTP_CREATED);
    }

    #[Route('/api/event/{id}', name: self::ROUTE_EDIT, methods: Request::METHOD_POST)]
    public function updateEvent(
        Request $request,
        int     $id,
    ): JsonResponse
    {
        $event = $this->eventRepository->find($id);
        $data = json_decode($request->getContent(), true);

        $title = $data["title"];
        $content = $data["content"];
        $date = $data["date"];
        $totalPralIndex = $data["totalPralIndex"];

        $event->setTitle($title);
        $event->setContent($content);
        $event->setDate($date);
        $event->setTotalPralIndex($totalPralIndex);

        $this->em->persist($event);
        $this->em->flush();

        return new JsonResponse('Event updated', Response::HTTP_OK);
    }


    #[Route('/api/event/{id}/delete', name: self::ROUTE_DELETE, methods: Request::METHOD_DELETE)]
    public function delete(
        Request $request,
        Event $event,
        EntityManagerInterface $em,
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $useremail = $data["useremail"];
        $authHeaders = $request->headers->get("Authorization");
        $token = substr($authHeaders, 7);
        
        try {
            $userData = $this->jwtManager->parse($token);
        } catch (JWTDecodeFailureException $e) {
            return new JsonResponse("Invalid Token", Response::HTTP_UNAUTHORIZED);
        }

        $tokenUserId = (int) $userData["id"];
        $TokenUsername = $userData["username"];

        if ($tokenUserId == $event->getUser()->getId() && $TokenUsername == $useremail ) {
            $em->remove($event);
            $em->flush();

            return new JsonResponse("Resource deleted", Response::HTTP_OK);
        } else {
            return new JsonResponse("Forbidden error", Response::HTTP_FORBIDDEN);
        }
    }
}
