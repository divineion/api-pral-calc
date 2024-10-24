<?php

namespace App\Services\Alim;

use App\Entity\Alim;
use App\Repository\AlimRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class AlimFinder
{
    public function __construct(
        public AlimRepository $alimRepository
    )
    {
    }

    public function processQuery(
        ?Alim $alim,
        $all
    ): JsonResponse
    {
        $result = $all
            ? $this->alimRepository->findAllFoodLabels()

            : [
            'Aliment' => $alim->getFoodLabel(),
            'Indice PRAL' => floatval($alim->getPralIndex()),
            'Energie' => floatval($alim->getNrjKcal()) ,
            'Lipides' => floatval($alim->getLipidesG()) ,
            'Glucides' => floatval($alim->getGlucidesG()) ,
            'Fibres' => floatval($alim->getFibresG()) ,
            'Calcium' => floatval($alim->getCalciumMg()) ,
            'Alcool' => floatval($alim->getAlcoolG()) ,
            'Cholestérol' => floatval($alim->getCholesterolMg()) ,
            'Amidon' => floatval($alim->getAmidonG()) ,
            'Iode' => floatval($alim->getIodeMcg()),
            'Fer' => floatval($alim->getFerMg()),
            'Lactose' => floatval($alim->getLactoseG()) ,
            'Magnésium' => floatval($alim->getMagnesiumMg()),
            'Maltose' => floatval($alim->getMaltoseG()),
            'Manganèse' => floatval($alim->getManganeseMg()),
            'Phosphore' => floatval($alim->getPhosphoreMg()),
            'Polyols' => floatval($alim->getPolyolsG()),
            'Potassium' => floatval($alim->getPotassiumMg()),
            'Protéines' => floatval($alim->getProteinesG()),
            'Galactose' => floatval($alim->getGalactoseG()),
            'Fructose' => floatval($alim->getFructoseG()),
            'Selenium' => floatval($alim->getSeleniumMcg()),
            'Cuivre' => floatval($alim->getCuivreMg()),
            'Vitamine B1' => floatval($alim->getVitamineB1Mg()),
            'Vitamine B2' => floatval($alim->getVitamineB2Mg()),
            'Vitamine B3' => floatval($alim->getVitamineB3Mg()),
            'Vitamine B5' => floatval($alim->getVitamineB5Mg()),
            'Vitamine B6' => floatval($alim->getVitamineB6Mg()),
            'Vitamine B9' => floatval($alim->getVitamineB9Mcg()),
            'Vitamine B12' => floatval($alim->getVitamineB12Mcg()),
            'Vitamine C' => floatval($alim->getVitamineCMg()),
            'Vitamine D' => floatval($alim->getVitamineDMcg()),
            'Vitamine E' => floatval($alim->getVitamineEMg()),
            'Vitamine K1' => floatval($alim->getVitamineK1Mcg()),
            'Vitamine K' => floatval($alim->getVitamineK2Mcg())
        ];

        return new JsonResponse($result);
    }
}