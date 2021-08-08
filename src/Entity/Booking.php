<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookingRepository;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 *  @ORM\HasLifecycleCallbacks
 */
class Booking
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
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="bookings")
     */
    private $ad;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return void
     */
    public function prePersist()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }

        if (empty($this->amount)) {
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }
    
    
    public function getDuration()
    {
        $diff = $this->endDate->diff($this->startDate);
        return $diff->days;
    }

    /**
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     *
     * @return \DateTimeInterface|null
     */
    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     *
     * @param \DateTimeInterface $startDate
     * @return Booking
     */
    public function setStartDate(\DateTimeInterface $startDate): Booking
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * 
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     *
     * @param \DateTimeInterface $endDate
     * @return Booking
     */
    public function setEndDate(\DateTimeInterface $endDate): Booking
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     *
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     *
     * @param \DateTimeInterface $createdAt
     * @return Booking
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): Booking
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     *
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     *
     * @param float $amount
     * @return Booking
     */
    public function setAmount(float $amount): Booking
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     *
     * @return User|null
     */
    public function getBooker(): ?User
    {
        return $this->booker;
    }

    /**
     *
     * @param User|null $booker
     * @return Booking
     */
    public function setBooker(?User $booker): Booking
    {
        $this->booker = $booker;

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
     * @return Booking
     */
    public function setAd(?Ad $ad): Booking
    {
        $this->ad = $ad;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     * @return Booking
     */
    public function setComment(?string $comment): Booking
    {
        $this->comment = $comment;

        return $this;
    }
}
