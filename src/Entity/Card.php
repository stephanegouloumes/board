<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Card
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
     * @Groups({"column", "card"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"column", "card"})
     * 
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"column", "card"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CardList", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $card_list;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"column", "card"})
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCardList(): ?CardList
    {
        return $this->card_list;
    }

    public function setCardList(?CardList $card_list): self
    {
        $this->card_list = $card_list;

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
