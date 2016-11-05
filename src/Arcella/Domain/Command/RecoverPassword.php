<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Command;

/**
 * This class is a Command that is used to reset the password if a user has
 * forgotten it.
 */
class RecoverPassword
{
    /**
     * @var string $email
     */
    private $email;

    /**
     * RecoverPassword constructor
     *
     * @param string $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * @return string $email
     */
    public function email()
    {
        return $this->email;
    }
}
