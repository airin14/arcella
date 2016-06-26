<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Entity;

use Symfony\Component\PropertyAccess\Exception\NoSuchIndexException;

/**
 * Nodes are the main structure that holds contents within Arcella, they consist of an numeric Id, a title and an array
 * containing the contents, where "text" is the default content. Also there are timestamps from the creation and the
 * last update included.
 *
 * @package Arcella\Domain\Entity
 */
class Node
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var array
     */
    protected $fields;

    /**
     * @var mixed
     */
    protected $fieldset;

    /**
     * @var timestamp
     */
    protected $createdAt;

    /**
     * @var timestamp
     */
    protected $updatedAt;

    /**
     * Node constructor.
     *
     * @param $id
     * @param $title
     * @param $slug
     * @param $content
     * @param $createdAt
     * @param $updatedAt
     */
    private function __construct($id, $title, $slug, $fields, $fielset, $createdAt, $updatedAt)
    {

    }

    /**
     * Returns the Id of the node.
     *
     * @return integer Id of the node.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the Title of the node.
     *
     * @return string Title of the node.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the Slug of the node.
     *
     * @return string Slug of the node.
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Returns the Content of the node.
     *
     * @param string $key
     *
     * @return array All the contents inside a associated array, with "text" as default.
     */
    public function getFields($key = null)
    {
        if (isset($key)) {
            if (!array_key_exists($key, $this->content)) {
                throw new NoSuchIndexException('No such $key in $this->content');
            }

            return $this->content[$key];
        }

        return $this->content;
    }

    /**
     * Returns the timestamp of the creation of the node.
     *
     * @return integer Timestamp of the nodes creation.
     */
    public function getCreated()
    {
        return $this->createdAt;
    }

    /**
     * Returns the timestamp of the last update of the node.
     *
     * @return integer Timestamp of the nodes last update.
     */
    public function getUpdated()
    {
        return $this->updatedAt;
    }
}
