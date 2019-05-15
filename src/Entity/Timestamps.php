<?php

namespace App\Entity;

trait Timestamps
{
    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;
    
    /**
     * @ORM\PrePersist()
     */
    public function createdAt()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function updatedAt()
    {
        $this->updated_at = new \DateTime();
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
}
