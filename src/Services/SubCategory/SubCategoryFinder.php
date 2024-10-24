<?php

namespace App\Services\SubCategory;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Repository\SubCategoryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class SubCategoryFinder
{
    public function __construct(
        public SubCategoryRepository $subCategoryRepository
    )
    {
    }
    public function processQuery(
        ?SubCategory $subCategory,
        ?bool $all
    ) : JsonResponse
    {
        $result = $all
            ? $this->subCategoryRepository->findAll()
            : [
                'id' => $subCategory->getId(),
                'nom' => $subCategory->getName(),
                'catégorie' => $subCategory->getCategory()->getName()
            ];

            return new JsonResponse($result);
    }

    public function SubCategoriesByCategory(
        ?Category $category
    ): JsonResponse
    {
        $result = $this->subCategoryRepository->findByCategory($category);

        return new JsonResponse($result);
    }
}