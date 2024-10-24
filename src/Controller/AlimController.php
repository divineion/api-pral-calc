<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Entity\Alim;
use App\Repository\AlimRepository;
use App\Services\Alim\AlimFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[AllowDynamicProperties] class AlimController extends AbstractController
{
    public const ROUTE_FETCH = 'alim_fetch';
    public const ROUTE_FIND_ALL = 'alim_find_all';

    public function __construct(
        AlimRepository $alimRepository
    )
    {
        $this->alimRepository = $alimRepository;
    }

    #[Route('api/aliment/{id}', name: self::ROUTE_FETCH, methods: Request::METHOD_GET)]
    #[Route('api/aliments', name: self::ROUTE_FIND_ALL, methods: Request::METHOD_GET)]
    public function fetch(
        ?Alim $alim,
        Request $request,
        AlimFinder $finder
    ): JsonResponse
    {
        return $finder->processQuery(
            $alim,
            $request->attributes->get('_route') === self::ROUTE_FIND_ALL
        );
    }
}
