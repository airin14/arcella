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
 * Class User
 * @package Arcella\Domain\Entity
 */
class User
{
    /**
     * @var
     */
    protected $username;

    /**
     * @var
     */
    protected $email;

    /**
     * @var
     */
    protected $roles = array();

    /**
     * @var
     */
    protected $password;

    /**
     * @var
     */
    protected $salt;

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
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
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     * @throws DomainException If $roles is not an array
     */
    public function setRoles($roles)
    {
        if (!is_array($roles))
        {
            throw new DomainException("Roles must be an array");
        }

        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param mixed $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }
}
