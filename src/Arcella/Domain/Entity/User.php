<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Entity;

use Arcella\Domain\Exception\DomainException;

/**
 * This class is an Entity and represents a User inside the Domain.
 */
class User
{
    /**
     * @var string $username The name of the user.
     */
    protected $username;

    /**
     * @var string $email The email of the user.
     */
    protected $email;

    /**
     * @var array $roles The roles of the user.
     */
    protected $roles = array();

    /**
     * @var string $password The password of the user.
     */
    protected $password;

    /**
     * @var string $salt The custom salt of the user.
     */
    protected $salt;

    /**
     * Returns the $username of the entity.
     *
     * @return string $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the $username of the entity.
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Returns the $email of the entity.
     *
     * @return string $email
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
     * Returns the $roles of the entity.
     *
     * @return array $roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set the $roles of the entity. Please note that when using this function
     * all previous roles of this entity will be overridden.
     *
     * @param array $roles
     *
     * @throws DomainException If $roles is not an array
     */
    public function setRoles($roles)
    {
        if (!is_array($roles)) {
            throw new DomainException("Roles must be an array");
        }

        $this->roles = $roles;
    }

    /**
     * Returns the $password of the entity.
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the $password of the entity.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the $salt of the entity.
     *
     * @return string $salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set the $salt of the entity.
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }
}
