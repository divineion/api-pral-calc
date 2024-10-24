<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $instructions = null;

    #[ORM\Column(type:types::DECIMAL, precision: 6, scale: 2, nullable: true)]
    private ?string $pralIndex = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private ?string $image;

    #[ORM\Column]
    private ?array $quantities;

    /**
     * @var ?Collection<int, Alim>
     */
    #[ORM\ManyToMany(targetEntity: Alim::class, inversedBy: 'relatedRecipes')]
    private ?Collection $aliments;

    #[ORM\ManyToOne(targetEntity: User::Class, inversedBy: 'recipes')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'recipes')]
    #[ORM\JoinColumn(name: 'category', referencedColumnName: 'id')]
    private ?Category $category;

    #[ORM\ManyToOne(targetEntity: SubCategory::class, inversedBy: 'recipes')]
    #[ORM\JoinColumn(name: 'subcategory', referencedColumnName: 'id')]
    private ?SubCategory $subCategory;

    public function __construct()
    {
        $this->aliments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(string $instructions): static
    {
        $this->instructions = $instructions;

        return $this;
    }

    /**
     * @return Collection<int, Alim>
     */
    public function getAliments(): Collection
    {
        return $this->aliments;
    }

    public function addAliment(Alim $aliment): static
    {
        if (!$this->aliments->contains($aliment)) {
            $this->aliments->add($aliment);
        }

        return $this;
    }

    public function removeAliment(Alim $aliment): static
    {
        $this->aliments->removeElement($aliment);

        return $this;
    }

    /**
     * @return array
     */
    public function getQuantities(): array
    {
        return $this->quantities;
    }

    /**
     * @param array $quantities
     */
    public function setQuantities(array $quantities): void
    {
        $this->quantities = $quantities;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
    public function getSubCategory(): ?SubCategory
    {
        return $this->subCategory;
    }

    public function setSubCategory(?SubCategory $subCategory): self
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPralIndex(): ?float
    {
        return $this->pralIndex;
    }

    /**
     * @param float|null $pralIndex
     */
    public function setPralIndex(?float $pralIndex): void
    {
        $this->pralIndex = $pralIndex;
    }
}
