<?php

namespace App\Entity;

use App\Entity\CardList;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BoardRepository")
 */
class Board
{
    public function __construct()
    {
        $this->created_at = new \Datetime();
        $this->updated_at = new \Datetime();
        $this->cardLists = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CardList", mappedBy="board", orphanRemoval=true, fetch="EAGER")
     */
    public $cardLists;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|CardList[]
     */
    public function getCardLists(): Collection
    {
        return $this->cardLists;
    }

    public function addCardList(CardList $cardList): self
    {
        if (!$this->cardLists->contains($cardList)) {
            $this->cardLists[] = $cardList;
            $cardList->setBoard($this);
        }

        return $this;
    }

    public function removeCardList(CardList $cardList): self
    {
        if ($this->cardLists->contains($cardList)) {
            $this->cardLists->removeElement($cardList);
            // set the owning side to null (unless already changed)
            if ($cardList->getBoard() === $this) {
                $cardList->setBoard(null);
            }
        }

        return $this;
    }
}