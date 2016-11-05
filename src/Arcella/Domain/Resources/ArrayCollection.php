<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Resources;

use Doctrine\Common\Collections\ArrayCollection as DoctrineArrayCollection;

/**
 * This ArrayCollection is used as a decorator for the Doctrine ArrayCollection
 * to make a cleaner separation between the Domain and the other layers of
 * Arcella.
 */
class ArrayCollection extends DoctrineArrayCollection
{
}
