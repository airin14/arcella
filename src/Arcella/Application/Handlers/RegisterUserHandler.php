<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Application\Handlers;

use Arcella\UserBundle\Entity\User;
use Arcella\Domain\Command\RegisterUser;
use Doctrine\ORM\EntityRepository;

/**
 * Class RegisterUserHandler
 * @package Arcella\Application\Handlers
 */
class RegisterUserHandler
{
    /**
     * @var $userRepository UserRepository
     */
    private $userRepository;

    /**
     * RegisterUserHandler constructor.
     *
     * @param EntityRepository $userRepository
     */
    public function __construct(EntityRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        //parent::__construct();
    }

    /**
     * @param RegisterUser $command
     */
    public function handle(RegisterUser $command)
    {
        $user = new User();
        $user->setUsername($command->username());
        $user->setEmail($command->email());
        $user->setRoles($command->roles());
        $user->setPlainPassword($command->password());
        $user->setSalt("salt");

        $this->userRepository->add($user);
    }
}
