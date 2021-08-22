<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use App\Repository\AdRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AdRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields = {"title"},
 *  message = "Une autre annonce possède dèja ce titre, merci de le modifier"
 * )
 */
class Ad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min= 10, max= 255, minMessage= "Le titre doit faire plus que 10 caractères!",
     * maxMessage= "le titre ne peut pas faire plus que 255 caractères" )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min= 20, minMessage= "Votre introduction doit faire plus que 20 caractères!")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min= 100, minMessage= "Votre description doit faire plus que 100 caractères!")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url()
     */
    private $coverImage;

    /**
     * @ORM\Column(type="integer")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="ad", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="ad")
     */
    private $bookings;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return void
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }

    }

    /**
     *
     * @return array
     */
    public function getNotAvailableDays(): array 
    {
        $notAvailableDays = [];

        foreach($this->bookings as $booking) {
            // Calculer les jours qui se trouvent entre la date d'arrivée et de départ
            $resultat = range(
                $booking->getStartDate()->getTimestamp(), 
                $booking->getEndDate()->getTimestamp(), 
                24 * 60 * 60
            );
            
            $days = array_map(function($dayTimestamp){
                return new \DateTime(date('Y-m-d', $dayTimestamp));
            }, $resultat);

            $notAvailableDays = array_merge($notAvailableDays, $days);
        }

        return $notAvailableDays;
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
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * 
     * @return Ad
     */
    public function setTitle(string $title): Ad
    {
        $this->title = $title;

        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * 
     * @return Ad
     */
    public function setSlug(string $slug): Ad
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     *
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * 
     * @return Ad
     */
    public function setPrice(float $price): Ad
    {
        $this->price = $price;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    /**
     * @param string $introduction
     * 
     * @return Ad
     */
    public function setIntroduction(string $introduction): Ad
    {
        $this->introduction = $introduction;

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
     * @return Ad
     */
    public function setDescription(string $description): Ad
    {
        $this->description = $description;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    /**
     * @param string $coverImage
     * 
     * @return Ad
     */
    public function setCoverImage(string $coverImage): Ad
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    /**
     * 
     * @return integer|null
     */
    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    /**
     * @param integer $rooms
     * 
     * @return Ad
     */
    public function setRooms(int $rooms): Ad
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param Image $image
     * 
     * @return Ad
     */
    public function addImage(Image $image): Ad
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAd($this);
        }

        return $this;
    }

    /**
     * @param Image $image
     * 
     * @return Ad
     */
    public function removeImage(Image $image): Ad
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAd() === $this) {
                $image->setAd(null);
            }
        }

        return $this;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     * 
     * @return Ad
     */
    public function setAuthor(?User $author): Ad
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    /**
     * @param Booking $booking
     * 
     * @return Ad
     */
    public function addBooking(Booking $booking): Ad
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setAd($this);
        }

        return $this;
    }

    /**
     * @param Booking $booking
     * 
     * @return Ad
     */
    public function removeBooking(Booking $booking): Ad
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getAd() === $this) {
                $booking->setAd(null);
            }
        }

        return $this;
    }
}
