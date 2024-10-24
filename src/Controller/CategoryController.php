<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Services\Category\CategoryFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

#[AllowDynamicProperties] class CategoryController extends AbstractController
{
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public const ROUTE_FETCH = 'category_fetch';
    public const ROUTE_FIND_ALL = 'category_find_all';

    #[Route('/api/category/{id}', name: self::ROUTE_FETCH, methods: request::METHOD_GET)]
    #[Route('/api/categories', name: self::ROUTE_FIND_ALL, methods: request::METHOD_GET)]
    public function fetch(
        ?Category $category,
        Request $request,
        CategoryFinder $finder
    ): JsonResponse
    {
        return $finder->processQuery(
            $category,
            $request->attributes->get('_route') === self::ROUTE_FIND_ALL
        );
    }

}
