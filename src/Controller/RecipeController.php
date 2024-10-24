<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Entity\Recipe;
use App\Repository\AlimRepository;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use App\Repository\SubCategoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[AllowDynamicProperties] class RecipeController extends AbstractController
{
    public const ROUTE_FETCH = 'recipe_fetch';
    public const ROUTE_RECIPE_ALIM = 'recipe_alim_fetch';
    public const ROUTE_FIND_ALL = 'recipe_find_all';
    public const ROUTE_CREATE = 'recipe_create';
    public const ROUTE_EDIT = 'recipe_';

    public function __construct(
        RecipeRepository $recipeRepository,
        AlimRepository $alimRepository,
    )
    {
        $this->recipeRepository = $recipeRepository;
        $this->alimRepository = $alimRepository;
    }

    #[Route('/api/recipe/{id}', name: self::ROUTE_FETCH, methods: request::METHOD_GET)]
    #[Route('/api/recipes', name: self::ROUTE_FIND_ALL, methods: request::METHOD_GET)]
    public function index(
        Request $request,
        ?Recipe $recipe,
    ): JsonResponse
    {
        $all = $request->attributes->get('_route') === self::ROUTE_FIND_ALL;

        $data = $all
            ? $this->recipeRepository->findAll()
            : [
                "id" => $recipe->getId(),
                "title" => $recipe->getTitle(),
                "instruction" => $recipe->getInstructions(),
                "category" => $recipe->getCategory()->getName(),
                "subcategory" => $recipe->getSubCategory()->getName(),
                "quantities" => $recipe->getQuantities(),
                "aliments" => $this->recipeRepository->findAllAlimentsInRecipe($recipe),
            ];

        return new JsonResponse($data);
    }

    #[Route('/api/recipe/{id}/aliments', name: self::ROUTE_RECIPE_ALIM, methods: request::METHOD_GET)]
    public function findRecipeAliments(
        Recipe $recipe
    ): JsonResponse
    {
        $data = [];

        foreach ($recipe->getAliments() as $aliment) {
            $data[] = [
                'id' => $aliment->getId(),
                'food_label' => $aliment->getFoodLabel(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/api/recipe', name: self::ROUTE_CREATE, methods: request::METHOD_POST)]
    public function createRecipe(
        Request $request,
        EntityManagerInterface $em,
        AlimRepository $alimRepo,
        CategoryRepository $categoryRepo,
        SubCategoryRepository $subCategoryRepo,
        UserRepository $userRepo,
    ): Response
    {
        $data = json_decode($request->getContent(), true);
        $title = $data["title"];
        $instructions = $data["instructions"];
        $aliments = $data["aliments"];
        $quantities = $data["quantities"] ?? [];
        $user = $userRepo->findOneById($data['user']);
        $category = $categoryRepo->findOneByName($data['category']);
        $subcategory = $subCategoryRepo->findOneByName($data['subcategory']);
        if (!$title || !$instructions || !$aliments || !$category) {
            return  new JsonResponse([ "message" => "Il manque des éléments pour créer la recette", $request->attributes->get('errors'), Response::HTTP_BAD_REQUEST]);
        }

        $recipe = new Recipe();

        foreach ($aliments as $aliment) {
            $aliment = $alimRepo->findOneById($aliment);
            $recipe->addAliment($aliment);
        }

        $recipe
            ->setCategory($category)
            ->setTitle($title)
            ->setInstructions($instructions)
            ->setUser($user)
            ->setSubCategory($subcategory)
            ->setQuantities($quantities);

        $em->persist($recipe);
        $em->flush();

        return new JsonResponse(['message' => 'Recette créée'], Response::HTTP_CREATED);
    }
}
