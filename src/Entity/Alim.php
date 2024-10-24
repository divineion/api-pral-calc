<?php

namespace App\Entity;

use App\Repository\AlimRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlimRepository::class)]
class Alim
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $food_label = null;

    #[ORM\Column(type:types::DECIMAL, precision: 5, scale: 2)]
    private ?string $pral_index = null;

    #[ORM\Column]
    private ?int $alim_code = null;

    #[ORM\Column(type:types::DECIMAL, precision: 6, scale: 2)]
    private ?string $nrj_kj = null;

    #[ORM\Column(type:types::DECIMAL, precision: 5, scale: 2)]
    private ?string $nrj_kcal = null;

    #[ORM\Column(type:types::DECIMAL, precision: 5, scale: 2)]
    private ?string $eau_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $sel_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $sodium_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 5, scale: 2)]
    private ?string $magnesium_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 6, scale: 2)]
    private ?string $phosphore_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 6, scale: 2)]
    private ?string $potassium_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 6, scale: 2)]
    private ?string $calcium_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $manganese_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 5, scale: 2)]
    private ?string $fer_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $cuivre_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $zinc_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 5, scale: 2)]
    private ?string $selenium_mcg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $iode_mcg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $proteines_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $glucides_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $sucres_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $fructose_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $galactose_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $lactose_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $glucose_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $maltose_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $saccharose_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $amidon_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $polyols_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $fibres_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 5, scale: 2)]
    private ?string $lipides_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $ags_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $agmi_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $agpi_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 3, scale: 2)]
    private ?string $ag_04_0_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 3, scale: 2)]
    private ?string $ag_06_0_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 3, scale: 2)]
    private ?string $ag_08_0_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 3, scale: 2)]
    private ?string $ag_10_0_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $ag_12_0_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $ag_14_0_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $ag_16_0_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $ag_18_0_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $ag_18_1_ole_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $ag_18_2_lino_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $ag_18_3_a_lino_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 3, scale: 2)]
    private ?string $ag_20_4_ara_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $ag_20_5_epa_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $ag_20_6_dha_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $retinol_mcg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $beta_carotene_mcg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $vitamine_d_mcg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $vitamine_e_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $vitamine_k1_mcg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $vitamine_k2_mcg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $vitamine_c_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $vitamine_b1_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $vitamine_b2_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $vitamine_b3_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $vitamine_b5_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $vitamine_b6_mg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $vitamine_b12_mcg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 7, scale: 2)]
    private ?string $vitamine_b9_mcg = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $alcool_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 4, scale: 2)]
    private ?string $acides_organiques_g = null;

    #[ORM\Column(type:types::DECIMAL, precision: 6, scale: 2)]
    private ?string $cholesterol_mg = null;

    #[ORM\Column]
    private ?int $alim_grp_code = null;

    #[ORM\Column]
    private ?int $alim_ssgrp_code = null;

    #[ORM\Column]
    private ?int $alim_ssssgrp_code = null;

    /**
     * @var Collection<int, Recipe>
     */
    #[ORM\ManyToMany(targetEntity: Recipe::class, mappedBy: 'aliments')]
    private Collection $relatedRecipes;

    public function __construct()
    {
        $this->relatedRecipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFoodLabel(): ?string
    {
        return $this->food_label;
    }

    /**
     * @return int|null
     */
    public function getAlimCode(): ?int
    {
        return $this->alim_code;
    }

    /**
     * @return string|null
     */
    public function getPralIndex(): ?string
    {
        return $this->pral_index;
    }

    /**
     * @param string|null $pral_index
     */
    public function setPralIndex(?string $pral_index): void
    {
        $this->pral_index = $pral_index;
    }

    /**
     * @return string|null
     */
    public function getNrjKj(): ?string
    {
        return $this->nrj_kj;
    }

    /**
     * @return string|null
     */
    public function getNrjKcal(): ?string
    {
        return $this->nrj_kcal;
    }

    /**
     * @return string|null
     */
    public function getEauG(): ?string
    {
        return $this->eau_g;
    }

    /**
     * @return string|null
     */
    public function getSelG(): ?string
    {
        return $this->sel_g;
    }

    /**
     * @return string|null
     */
    public function getSodiumMg(): ?string
    {
        return $this->sodium_mg;
    }

    /**
     * @return string|null
     */
    public function getMagnesiumMg(): ?string
    {
        return $this->magnesium_mg;
    }

    /**
     * @return string|null
     */
    public function getPhosphoreMg(): ?string
    {
        return $this->phosphore_mg;
    }

    /**
     * @return string|null
     */
    public function getPotassiumMg(): ?string
    {
        return $this->potassium_mg;
    }

    /**
     * @return string|null
     */
    public function getCalciumMg(): ?string
    {
        return $this->calcium_mg;
    }

    /**
     * @return string|null
     */
    public function getManganeseMg(): ?string
    {
        return $this->manganese_mg;
    }

    /**
     * @return string|null
     */
    public function getFerMg(): ?string
    {
        return $this->fer_mg;
    }

    /**
     * @return string|null
     */
    public function getCuivreMg(): ?string
    {
        return $this->cuivre_mg;
    }

    /**
     * @return string|null
     */
    public function getZincMg(): ?string
    {
        return $this->zinc_mg;
    }

    /**
     * @return string|null
     */
    public function getSeleniumMcg(): ?string
    {
        return $this->selenium_mcg;
    }

    /**
     * @return string|null
     */
    public function getIodeMcg(): ?string
    {
        return $this->iode_mcg;
    }

    /**
     * @return string|null
     */
    public function getProteinesG(): ?string
    {
        return $this->proteines_g;
    }

    /**
     * @return string|null
     */
    public function getGlucidesG(): ?string
    {
        return $this->glucides_g;
    }

    /**
     * @return string|null
     */
    public function getSucresG(): ?string
    {
        return $this->sucres_g;
    }

    /**
     * @return string|null
     */
    public function getFructoseG(): ?string
    {
        return $this->fructose_g;
    }

    /**
     * @return string|null
     */
    public function getGalactoseG(): ?string
    {
        return $this->galactose_g;
    }

    /**
     * @return string|null
     */
    public function getLactoseG(): ?string
    {
        return $this->lactose_g;
    }

    /**
     * @return string|null
     */
    public function getGlucoseG(): ?string
    {
        return $this->glucose_g;
    }

    /**
     * @return string|null
     */
    public function getMaltoseG(): ?string
    {
        return $this->maltose_g;
    }

    /**
     * @return string|null
     */
    public function getSaccharoseG(): ?string
    {
        return $this->saccharose_g;
    }

    /**
     * @return string|null
     */
    public function getAmidonG(): ?string
    {
        return $this->amidon_g;
    }

    /**
     * @return string|null
     */
    public function getPolyolsG(): ?string
    {
        return $this->polyols_g;
    }

    /**
     * @return string|null
     */
    public function getFibresG(): ?string
    {
        return $this->fibres_g;
    }

    /**
     * @return string|null
     */
    public function getLipidesG(): ?string
    {
        return $this->lipides_g;
    }

    /**
     * @return string|null
     */
    public function getAgsG(): ?string
    {
        return $this->ags_g;
    }

    /**
     * @return string|null
     */
    public function getAgmiG(): ?string
    {
        return $this->agmi_g;
    }

    /**
     * @return string|null
     */
    public function getAgpiG(): ?string
    {
        return $this->agpi_g;
    }

    /**
     * @return string|null
     */
    public function getAg040G(): ?string
    {
        return $this->ag_04_0_g;
    }

    /**
     * @return string|null
     */
    public function getAg060G(): ?string
    {
        return $this->ag_06_0_g;
    }

    /**
     * @return string|null
     */
    public function getAg080G(): ?string
    {
        return $this->ag_08_0_g;
    }

    /**
     * @return string|null
     */
    public function getAg100G(): ?string
    {
        return $this->ag_10_0_g;
    }

    /**
     * @return string|null
     */
    public function getAg120G(): ?string
    {
        return $this->ag_12_0_g;
    }

    /**
     * @return string|null
     */
    public function getAg140G(): ?string
    {
        return $this->ag_14_0_g;
    }

    /**
     * @return string|null
     */
    public function getAg160G(): ?string
    {
        return $this->ag_16_0_g;
    }

    /**
     * @return string|null
     */
    public function getAg180G(): ?string
    {
        return $this->ag_18_0_g;
    }

    /**
     * @return string|null
     */
    public function getAg181OleG(): ?string
    {
        return $this->ag_18_1_ole_g;
    }

    /**
     * @return string|null
     */
    public function getAg182LinoG(): ?string
    {
        return $this->ag_18_2_lino_g;
    }

    /**
     * @return string|null
     */
    public function getAg183ALinoG(): ?string
    {
        return $this->ag_18_3_a_lino_g;
    }

    /**
     * @return string|null
     */
    public function getAg204AraG(): ?string
    {
        return $this->ag_20_4_ara_g;
    }

    /**
     * @return string|null
     */
    public function getAg205EpaG(): ?string
    {
        return $this->ag_20_5_epa_g;
    }

    /**
     * @return string|null
     */
    public function getAg206DhaG(): ?string
    {
        return $this->ag_20_6_dha_g;
    }

    /**
     * @return string|null
     */
    public function getRetinolMcg(): ?string
    {
        return $this->retinol_mcg;
    }

    /**
     * @return string|null
     */
    public function getBetaCaroteneMcg(): ?string
    {
        return $this->beta_carotene_mcg;
    }

    /**
     * @return string|null
     */
    public function getVitamineDMcg(): ?string
    {
        return $this->vitamine_d_mcg;
    }

    /**
     * @return string|null
     */
    public function getVitamineEMg(): ?string
    {
        return $this->vitamine_e_mg;
    }

    /**
     * @return string|null
     */
    public function getVitamineK1Mcg(): ?string
    {
        return $this->vitamine_k1_mcg;
    }

    /**
     * @return string|null
     */
    public function getVitamineK2Mcg(): ?string
    {
        return $this->vitamine_k2_mcg;
    }

    /**
     * @return string|null
     */
    public function getVitamineCMg(): ?string
    {
        return $this->vitamine_c_mg;
    }

    /**
     * @return string|null
     */
    public function getVitamineB1Mg(): ?string
    {
        return $this->vitamine_b1_mg;
    }

    /**
     * @return string|null
     */
    public function getVitamineB2Mg(): ?string
    {
        return $this->vitamine_b2_mg;
    }

    /**
     * @return string|null
     */
    public function getVitamineB3Mg(): ?string
    {
        return $this->vitamine_b3_mg;
    }

    /**
     * @return string|null
     */
    public function getVitamineB5Mg(): ?string
    {
        return $this->vitamine_b5_mg;
    }

    /**
     * @return string|null
     */
    public function getVitamineB6Mg(): ?string
    {
        return $this->vitamine_b6_mg;
    }

    /**
     * @return string|null
     */
    public function getVitamineB12Mcg(): ?string
    {
        return $this->vitamine_b12_mcg;
    }

    /**
     * @return string|null
     */
    public function getVitamineB9Mcg(): ?string
    {
        return $this->vitamine_b9_mcg;
    }

    /**
     * @return string|null
     */
    public function getAlcoolG(): ?string
    {
        return $this->alcool_g;
    }

    /**
     * @return string|null
     */
    public function getAcidesOrganiquesG(): ?string
    {
        return $this->acides_organiques_g;
    }

    /**
     * @return string|null
     */
    public function getCholesterolMg(): ?string
    {
        return $this->cholesterol_mg;
    }

    /**
     * @return int|null
     */
    public function getAlimGrpCode(): ?int
    {
        return $this->alim_grp_code;
    }

    /**
     * @return int|null
     */
    public function getAlimSsgrpCode(): ?int
    {
        return $this->alim_ssgrp_code;
    }

    /**
     * @return int|null
     */
    public function getAlimSsssgrpCode(): ?int
    {
        return $this->alim_ssssgrp_code;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRelatedRecipes(): Collection
    {
        return $this->relatedRecipes;
    }

    public function addRelatedRecipe(Recipe $relatedRecipe): static
    {
        if (!$this->relatedRecipes->contains($relatedRecipe)) {
            $this->relatedRecipes->add($relatedRecipe);
            $relatedRecipe->addAliment($this);
        }

        return $this;
    }

    public function removeRelatedRecipe(Recipe $relatedRecipe): static
    {
        if ($this->relatedRecipes->removeElement($relatedRecipe)) {
            $relatedRecipe->removeAliment($this);
        }

        return $this;
    }
}
