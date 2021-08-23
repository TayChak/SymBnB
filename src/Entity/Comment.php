<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $rating;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\PrePersist
     * 
     * @return void
     */
    public function prePersist() {
        if(empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }
    }

    /**
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     *
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     * 
     * @return Comment
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): Comment
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     *
     * @return integer|null
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * @param integer $rating
     * 
     * @return Comment
     */
    public function setRating(int $rating): Comment
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * 
     * @return Comment
     */
    public function setDescription(string $description): Comment
    {
        $this->description = $description;

        return $this;
    }

    /**
     *
     * @return Ad|null
     */
    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    /**
     * @param Ad|null $ad
     * 
     * @return Comment
     */
    public function setAd(?Ad $ad): Comment
    {
        $this->ad = $ad;

        return $this;
    }

    /**
     *
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     * 
     * @return Comment
     */
    public function setAuthor(?User $author): Comment
    {
        $this->author = $author;

        return $this;
    }
}
