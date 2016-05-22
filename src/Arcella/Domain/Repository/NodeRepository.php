<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Repository;

use Arcella\Domain\Entity\Node;

/**
 * The general interface for NodeRepositories
 *
 * @package Arcella\Domain\Repository
 */
interface NodeRepositoryInterface
{
    /**
     * Returns a Node object by its $id
     *
     * @param integer $id Id of the node.
     *
     * @return mixed Arcella\Domain\Entity\Node
     */
    public function get($id);

    /**
     * Add a Node object to the repository
     *
     * @param Node $node The Node object that should be added to the repository
     *
     * @return void
     */
    public function add(Node $node);
}
