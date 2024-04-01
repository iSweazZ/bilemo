<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "app_product_details",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getProducts")
 * )
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"getProducts"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"getProducts"})
     */
    private string $brand;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"getProducts"})
     */
    private string $model;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"getProducts"})
     */
    private ?string $color;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"getProducts"})
     */
    private ?int $memory;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"getProducts"})
     */
    private ?string $description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"getProducts"})
     */
    private float $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getMemory(): ?int
    {
        return $this->memory;
    }

    public function setMemory(?int $memory): self
    {
        $this->memory = $memory;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
