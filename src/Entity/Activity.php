<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Activity
{
    use Timestamps;

    public function __construct()
    {
        $this->created_at = new \Datetime();
        $this->updated_at = new \Datetime();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"activity"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"activity"})
     */
    private $entityType;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"activity"})
     */
    private $entity;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"activity"})
     */
    private $action;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="activities")
     * @Groups({"activity"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Board", inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $board;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntityType(): ?string
    {
        return $this->entityType;
    }

    public function setEntityType(string $entityType): self
    {
        $this->entityType = $entityType;

        return $this;
    }

    public function getEntity(): ?int
    {
        return $this->entity;
    }

    public function setEntity(int $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

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

    public function getBoard(): ?Board
    {
        return $this->board;
    }

    public function setBoard(?Board $board): self
    {
        $this->board = $board;

        return $this;
    }
}
