<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Entity;

/**
 * The Content class provides the concrete contents of the nodes
 *
 * @package Arcella\Domain\Entity
 */
class Content
{
    private $id;

    /**
     * Constructor
     */
    private function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
