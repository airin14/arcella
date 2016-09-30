<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Entity;

use Arcella\Domain\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Arcella\UserBundle\Repository\DoctrineORMUserRepository")
 * @UniqueEntity(fields={"email"}, message="It looks like your already have an account!")
 */
class User extends BaseUser implements UserInterface
{
    /**
     * Auto-generated ID of the user.
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * The name of the user.
     *
     * @Assert\NotBlank(message = "user.name.not_blank")
     * @ORM\Column(type="string", unique=true)
     */
    protected $username;

     /**
      * The email address of the user.
      *
      * @Assert\NotBlank(message = "user.email.not_blank")
      * @Assert\Email(message = "user.email.invalid")
      * @ORM\Column(type="string", unique=true)
      */
    protected $email;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $emailIsVerified;

    /**
     * The roles of the user.
     *
     * @ORM\Column(type="array")
     */
    protected $roles;

    /**
     * The users custom salt for the password.
     *
     * @ORM\Column(type="string")
     */
    protected $salt;

    /**
     * The encoded password of the user.
     *
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * A non-persisted field that's used to create the users encoded password.
     *
     * @Assert\NotBlank(message = "user.password.not_blank", groups={"Registration"})
     * @var string
     */
    protected $plainPassword;

    /**
     * Erase all credentials from the entity for security purposes.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * Set the $emailIsVerified of the entity.
     *
     * @param string $emailIsVerified
     */
    public function setEmailIsVerified($emailIsVerified)
    {
        $this->emailIsVerified = $emailIsVerified;
    }

    /**
     * Returns the $emailIsVerified of the entity.
     *
     * @return string $emailIsVerified
     */
    public function getEmailIsVerified()
    {
        return $this->emailIsVerified;
    }

    /**
     * Returns the $plainPassword of the entity.
     *
     * @return string plainPassword
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set the $plainPassword of the entity.
     *
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        // forces the object to look "dirty" to Doctrine. Avoids
        // Doctrine *not* saving this entity, if only plainPassword changes
        $this->password = null;
    }
}
