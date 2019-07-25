<?php

namespace App\Traits;

trait CreatedAtModifiedAtTrait
{
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="modified_at", type="datetime")
     */
    protected $modifiedAt;

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function updateCreateddAt(): void
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     * @return $this
     */
    public function setModifiedAt($modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime
     */
    public function getModifiedAt(): \DateTime
    {
        return $this->modifiedAt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateModifiedAt(): void
    {
        $this->setModifiedAt(new \DateTime());
    }
}