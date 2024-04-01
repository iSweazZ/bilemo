<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeInterface;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "app_user_details",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getUsers")
 * )
 *
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "app_delete_user",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getUsers")
 * )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"getUsers","getClients"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'email est obligatoire")
     * @Assert\Length(min="1", max="255", minMessage="L'email doit faire au moins {{ limit }} caractères", maxMessage="L'email doit faire moins de {{ limite }} caractères.")
     * @Groups({"getUsers","getClients"})
     */
    private string $email;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"getUsers","getClients"})
     */
    private ?DateTimeInterface $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="users")
     * @Groups({"getUsers","getClients"})
     */
    private ?Client $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreationDate(): ?DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
