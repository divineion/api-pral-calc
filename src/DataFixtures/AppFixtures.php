<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Repository\CategoryRepository;
use App\Repository\SubCategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private CategoryRepository $categoryRepository;
    private SubCategoryRepository $subCategoryRepository;

    //define nutrients properties to use in customization
    public const NUTRIENTS_DISPLAY_DEFAULT_PREFERENCE = [
        "Energie" => true,
        "Lipides" => true,
        "Glucides" => true,
        "Protéines" => true,
        "Fibres" => true,
        "Calcium" => true,
        "Alcool" => false,
        "Cholestérol" => false,
        "Amidon" => false,
        "Iode" => false,
        "Fer" => false,
        "Lactose" => false,
        "Magnésium" => false,
        "Maltose" => false,
        "Manganèse" => false,
        "Phosphore" => false,
        "Polyols" => false,
        "Potassium" => false,
        "Galactose" => false,
        "Fructose" => false,
        "Selenium" => false,
        "Cuivre" => false,
        "Vitamine B1" => false,
        "Vitamine B2" => false,
        "Vitamine B3" => false,
        "Vitamine B5" => false,
        "Vitamine B6" => false,
        "Vitamine B9" => false,
        "Vitamine B12" => false,
        "Vitamine C" => false,
        "Vitamine D" => false,
        "Vitamine E" => false,
        "Vitamine K1" => false,
        "Vitamine K" => false
    ];
    public function __construct(
        CategoryRepository $categoryRepository,
        SubCategoryRepository $subCategoryRepository,
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->subCategoryRepository = $subCategoryRepository;
    }

    public function load(ObjectManager $manager): void
    {
        //define categories
        $categories = [
            "Petit déjeûner",
            "Collation",
            "Pâtisseries",
            "Soupes et potages",
            "Sauces",
            "Pains, plats à base de pain",
            "Entremets, desserts glacés",
            "Boissons",
            "Salades",
            "Tartes salées et pizzas"
        ];

        //set categories if they do not exist
        foreach ($categories as $cat) {
            $category = $this->categoryRepository->findOneByName($cat) ?? new Category();
            $category->setName($cat);
            $manager->persist($category);
        }

        $manager->flush();

        //define subcategories as values in each category as key
        $subCategories = [
            "Petit déjeûner" => [
                "Petits déjeûners salés",
                "Petits déjeûners sucrés"
            ],
            "Collation" => [
                "Collations salées",
                "Collations sucrées"
            ],
            "Pâtisseries" => [
                "Biscuits",
                "Tartes",
                "Gâteaux",
                "Petits-fours",
                "Viennoiseries"
            ],
            "Soupes et potages" => [
                "Bouillons",
                "Moulinés",
                "Crèmes et veloutés"
            ],
            "Sauces" => [
                "Sauces brunes",
                "Sauces blanches",
                "Sauces au beurre",
                "Sauces à l'huile"
            ],
            "Pains, plats à base de pain" => [
                "Sandwiches",
                "Tartines"
            ],
            "Entremets, desserts glacés" => [
                "Crèmes",
                "Beignets",
                "Compotes",
                "Charlottes",
                "Crêpes",
                "Flan",
                "Gelée",
                "Mousse",
                "Soufflé sucré"
            ],
            "Boissons" => [
                "Boisson chaude sans alcool",
                "Boisson fraîche sans alcool",
                "Boisson chaude alcoolisée",
                "Boisson fraîche alcoolisée",
            ],
            "Salades" => [
                "Salade chaude",
                "Salade froide",
                "Salade de fruits"
            ],
            "Tartes salées et pizzas" => [
                "Tarte salée",
                "Quiche",
                "Pizza",
                "Tourte"
            ]
        ];

        //verify categories, create associated subcategories if they do not exist
        foreach ($subCategories as $categoryName => $subCategoryNames) {
            $category = $this->categoryRepository->findOneByName($categoryName);

            if (!$category) {
                throw new \Exception("La catégorie de référence $categoryName n'existe pas \n Vérifiez les clés du tableau subCategories");
            }

            foreach ($subCategoryNames as $subCat) {
                $subCategory = $this->subCategoryRepository->findOneByName($subCat) ?? new SubCategory();
                $subCategory->setName($subCat);
                $subCategory->setCategory($category);
                $manager->persist($subCategory);
            }
        };

        $manager->flush();
    }
}
