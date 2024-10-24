<?php

namespace App\Services\Category;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryFinder
{
    public function __construct(
        public CategoryRepository $categoryRepository
    )
    {
    }
    public function processQuery(
        ?Category $category,
        ?bool $all
    ) : JsonResponse
    {
        $result = $all
            ? $this->categoryRepository->findAll()
            :
            [
                'id' => $category->getId(),
                'nom' => $category->getName(),
            ];

        return new JsonResponse($result);
    }
}