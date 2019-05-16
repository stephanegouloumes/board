<?php

namespace App\Entity;

use App\Entity\CardList;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BoardRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Board
{
    use Timestamps;

    public function __construct()
    {
        $this->created_at = new \Datetime();
        $this->updated_at = new \Datetime();
        $this->cardLists = new ArrayCollection();
        $this->activities = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"board"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"board"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CardList", mappedBy="board", orphanRemoval=true, fetch="EAGER")
     */
    private $cardLists;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Activity", mappedBy="board", orphanRemoval=true)
     */
    private $activities;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="boards")
     * @ORM\JoinColumn(nullable=false, name="owner_id", referencedColumnName="id")
     */
    private $owner;

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

    /**
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setBoard($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
            // set the owning side to null (unless already changed)
            if ($activity->getBoard() === $this) {
                $activity->setBoard(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
