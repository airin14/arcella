<?php

namespace Arcella\UtilityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Token is an entity that is used by the TokenValidator to validate a specific
 * request from a User.
 *
 * @ORM\Table(name="token")
 * @ORM\Entity(repositoryClass="Arcella\UtilityBundle\Repository\TokenRepository")
 */
class Token
{
    /**
     * @var int $id Used for internal identification of a Entity.
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $key The actual key by which a Entity can be identified.
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $key;

    /**
     * @var \DateTime The time when the Entity expires.
     *
     * @ORM\Column(name="expiration", type="datetime")
     */
    private $expiration;

    /**
     * @var array Additional parameters that have been added to the Entity
     *
     * @ORM\Column(name="params", type="json_array")
     */
    private $params = array();


    /**
     * Get the $id of an Entity
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the $key of an Entity
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get the $key of an Entity
     *
     * @return string $key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the $expiration of an Entity
     *
     * @param \DateTime $expiration
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;
    }

    /**
     * Get $expiration of an Entity
     *
     * @return \DateTime $expiration
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * Set additional $params of an Entity
     *
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * Get the additional $params of en Entity
     *
     * @return array $params
     */
    public function getParams()
    {
        return $this->params;
    }
}
