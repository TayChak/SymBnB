<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate
{
    /**
     * @var string
     * 
     */
    private $oldPassword;

    /**
     * @var string
     * 
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit avoir 8 caractères !")
     * @Assert\NotEqualTo(propertyPath="oldPassword", message="Nouveau mot de passe ne doit pas être identique à l'ancien !!" )
     */
    private $newPassword;

    /**
     * @var string
     * 
     * @Assert\EqualTo(propertyPath="newPassword", message="mot de passe n'est pas identiques !!")
     */
    private $confirmPassword;

    /**
     *
     * @return string|null
     */
    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    /**
     * @param string $oldPassword
     * 
     * @return self
     */
    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    /**
     * @param string $newPassword
     * 
     * @return self
     */
    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    /**
     * @param string $confirmPassword
     * 
     * @return self
     */
    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
