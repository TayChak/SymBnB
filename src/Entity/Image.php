<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url()
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min= 10, minMessage= "Le titre de l'image doit faire au mois 10 caractères!" )
     */
    private $caption;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

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
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * 
     * @return Image
     */
    public function setUrl(string $url): Image
    {
        $this->url = $url;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getCaption(): ?string
    {
        return $this->caption;
    }

    /**
     * @param string $caption
     * 
     * @return Image
     */
    public function setCaption(string $caption): Image
    {
        $this->caption = $caption;

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
     * @return self
     */
    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }
}
