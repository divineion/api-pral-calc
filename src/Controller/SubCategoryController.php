<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Entity\Category;
use App\Entity\SubCategory;
use App\Repository\SubCategoryRepository;
use App\Services\SubCategory\SubCategoryFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

#[AllowDynamicProperties] class SubCategoryController extends AbstractController
{
    public const ROUTE_FETCH = 'subcategory_fetch';
    public const ROUTE_FIND_ALL = 'subcategory_find_all';
    const ROUTE_FETCH_CATEGORY_SUBCATEGORIES = 'fetch_categories_subcategories';

    public function __construct(SubCategoryRepository $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    #[Route('/api/subcategory/{id}', name: self::ROUTE_FETCH, methods: Request::METHOD_GET)]
    #[Route('/api/subcategories', name: self::ROUTE_FIND_ALL, methods: Request::METHOD_GET)]
    public function fetch(
        ?SubCategory $subCategory,
        Request $request,
        SubCategoryFinder $finder
    ): JsonResponse
    {
        return $finder->processQuery(
            $subCategory,
            $request->attributes->get('_route') === self::ROUTE_FIND_ALL
        );
    }

    #[Route('/api/category/{id}/subcategories', name: self::ROUTE_FETCH_CATEGORY_SUBCATEGORIES, methods: Request::METHOD_GET)]
    public function fetchCategorySubCategories(
        ?Category $category,
        Request $request,
        SubCategoryFinder $finder
    ) : JsonResponse
    {
        return $finder->SubCategoriesByCategory($category);
    }
}
