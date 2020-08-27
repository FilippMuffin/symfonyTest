<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $externalID;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="childCategories")
     */
    private $rootCategory;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="rootCategory")
     */
    private $childCategories;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="categories")
     */
    private $products;

    public function __construct()
    {
        $this->childCategories = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExternalID(): ?string
    {
        return $this->externalID;
    }

    public function setExternalID(?string $externalID): self
    {
        $this->externalID = $externalID;

        return $this;
    }

    public function getRootCategory(): ?self
    {
        return $this->rootCategory;
    }

    public function setRootCategory(?self $rootCategory): self
    {
        $this->rootCategory = $rootCategory;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildCategories(): Collection
    {
        return $this->childCategories;
    }

    public function addChildCategory(self $childCategory): self
    {
        if (!$this->childCategories->contains($childCategory)) {
            $this->childCategories[] = $childCategory;
            $childCategory->setRootCategory($this);
        }

        return $this;
    }

    public function removeChildCategory(self $childCategory): self
    {
        if ($this->childCategories->contains($childCategory)) {
            $this->childCategories->removeElement($childCategory);
            // set the owning side to null (unless already changed)
            if ($childCategory->getRootCategory() === $this) {
                $childCategory->setRootCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->removeCategory($this);
        }

        return $this;
    }
}
