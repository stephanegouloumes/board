<?php

namespace App\Entity;

use App\Entity\Board;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardListRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CardList
{
    use Timestamps;
    
    public function __construct()
    {
        $this->created_at = new \Datetime();
        $this->updated_at = new \Datetime();
        $this->cards = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"column"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"column"})
     * 
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Board", inversedBy="cardLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $board;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="card_list", cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     * @Groups({"column"})
     */
    private $cards;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"column"})
     * 
     * @Assert\NotBlank
     */
    private $position;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setCardList($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getCardList() === $this) {
                $card->setCardList(null);
            }
        }

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
