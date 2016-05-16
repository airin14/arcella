<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain;

/**
 * Interface Node
 *
 * Nodes are the main structure that holds contents within Arcella, they consist of an numeric Id, a title and an array
 * containing the contents, where "text" is the default content. Also there are timestamps from the creation and the
 * last update included.
 *
 * @package Arcella\Domain
 */
interface Node
{
    /**
     * Returns the Id of the node.
     *
     * @return integer Id of the node.
     */
    public function getId();

    /**
     * Returns the Title of the node.
     *
     * @return string Title of the node.
     */
    public function getTitle();

    /**
     * Updates the Title of the node and returns the changed node with the new title.
     *
     * @param string $title The new title for the node.
     * @return mixed The node with the new title.
     */
    public function setTitle(string $title);

    /**
     * Returns the Slug of the node.
     *
     * @return string Slug of the node.
     */
    public function getSlug();

    /**
     * Updates the Slug of the node and returns the changed node with the new slug.
     *
     * @param string $slug The new slug for the node.
     * @return mixed The node with the new slug.
     */
    public function setSlug(string $slug);

    /**
     * Returns the Content of the node.
     *
     * @return array All the contents inside a associated array, with "text" as default.
     */
    public function getContent();

    /**
     * Sets Content of the node.
     *
     * @param array $content The new content for the node.
     * @return mixed The node with the new content.
     */
    public function setConcent(array $content);

    /**
     * Returns the timestamp of the creation of the node.
     *
     * @return integer Timestamp of the nodes creation.
     */
    public function getCreated();

    /**
     * Returns the timestamp of the last update of the node.
     *
     * @return integer Timestamp of the nodes last update.
     */
    public function getUpdated();
}