<?php

namespace Arcella\UtilityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Token is an entity that is used by the TokenValidator to validate a request
 * from a User and keeps all necessary data for this purpose.
 *
 * @ORM\Table(name="token")
 * @ORM\Entity(repositoryClass="Arcella\UtilityBundle\Repository\TokenRepository")
 */
class Token
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $key;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration", type="datetime")
     */
    private $expiration;

    /**
     * @var array
     *
     * @ORM\Column(name="params", type="json_array")
     */
    private $params = array();


    /**
     * Get the $id of an Entity
     *
     * @return int $id Only used for internal identification of the entity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the $key of an Entity
     *
     * @param string $key The key by which the Entity will be identified.
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get the $key of an Entity
     *
     * @return string $key The key by which this Entity can be identified.
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the $expiration of an Entity
     *
     * @param \DateTime $expiration The time when the Entity expires.
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;
    }

    /**
     * Get the $expiration of an Entity
     *
     * @return \DateTime $expiration The time when the Entity expires.
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * Set additional $params of an Entity
     *
     * @param array $params Additional parameters that can been added to the Entity.
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * Get the additional $params of en Entity
     *
     * @return array $params Additional parameters that have been added to the Entity.
     */
    public function getParams()
    {
        return $this->params;
    }
}
