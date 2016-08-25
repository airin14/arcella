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
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
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
      * The e-mail adress of the user.
      *
      * @Assert\NotBlank()
      * @Assert\Email()
      * @ORM\Column(type="string", unique=true)
      */
    protected $email;

    /**
     * The encoded password of the user.
     *
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * A non-persisted field that's used to create the users encoded password.
     *
     * @Assert\NotBlank(groups={"Registration"})
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
     * @return mixed
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Returns the $email of the entity.
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the $email of the entity.
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns the $roles of the entity, while making sure it at least contains ROLE_User.
     *
     * @return array The roles of the user entity.
     */
    public function getRoles()
    {
        $roles = array('');

        // give everyone ROLE_USER!
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        return $roles;
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
