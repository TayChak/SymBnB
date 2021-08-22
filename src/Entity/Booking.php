<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookingRepository;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Date(message="Attention, la date d'arrivée doit être au bon format !")
     * @Assert\GreaterThan("today", message="La date d'arrivée doit être ultérieure à la date d'aujourd'hui !", groups={"front"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention, la date de départ doit être au bon format !")
     * @Assert\GreaterThan(propertyPath="startDate", message="La date de départ doit être plus éloignée que la date d'arrivée !")
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
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
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
    
    /**
     * @return boolean 
     */
    public function isBookableDates(): bool
    {
        // 1) Il faut connaitre les dates qui sont impossibles pour l'annonce
        $notAvailableDays = $this->ad->getNotAvailableDays();
        // 2) Il faut comparer les dates choisies avec les dates impossibles
        $bookingDays      = $this->getDays();
        
        $formatDay = function($day){
            return $day->format('Y-m-d');
        };

        $days           = array_map($formatDay, $bookingDays);
        $notAvailable   = array_map($formatDay, $notAvailableDays);

        foreach($days as $day) {
            if(array_search($day, $notAvailable) !== false) return false;
        }

        return true;
    }

    /**
     *
     * @return array²
     */
    public function getDays(): array 
    {
        $resultat = range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            24 * 60 * 60
        );

        $days =  array_map(function($dayTimestamp) {
            return new \DateTime(date('Y-m-d', $dayTimestamp));
        }, $resultat);

        return $days;
    }
    
    /**
     *
     * @return void
     */
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
