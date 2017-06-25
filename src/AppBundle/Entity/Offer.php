<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="offer")
 * @ORM\HasLifecycleCallbacks()
 */
class Offer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max=250
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     */
    private $description;

    /**
     * @ORM\ManyToOne(
     *      targetEntity = "AppBundle\Entity\User",
     *      inversedBy = "offer"
     * )
     * @ORM\JoinColumn(
     *      name = "create_author_id",
     *      referencedColumnName = "id",
     *      onDelete = null
     * )
     */
    private $createAuthor;

    /**
     * @ORM\Column(
     *      name="create_date",
     *      type="datetime"
     * )
     */
    private $dateCreated;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Offer
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Offer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Offer
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set createAuthor
     *
     * @param \AppBundle\Entity\User $createAuthor
     *
     * @return Offer
     */
    public function setCreateAuthor(\AppBundle\Entity\User $createAuthor = null)
    {
        $this->createAuthor = $createAuthor;

        return $this;
    }

    /**
     * Get createAuthor
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreateAuthor()
    {
        return $this->createAuthor;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        if ($this->dateCreated === null) {
            $this->setDateCreated(new \DateTime());
        }
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        if ($this->dateCreated === null) {
            $this->setDateCreated(new \DateTime());
        }
    }
}
