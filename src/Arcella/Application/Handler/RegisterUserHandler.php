<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Application\Handler;

use Arcella\Domain\Command\RegisterUser;
use Arcella\Domain\Event\UserRegisteredEvent;
use Arcella\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * This class is a Handler and for handling a RegisterUser command, which is used to register a new User to the system.
 *
 * @package Arcella\Application\Handler
 */
class RegisterUserHandler
{
    /**
     * @var $userRepository EntityRepository
     */
    private $userRepository;

    /**
     * @var $validator ValidatorInterface
     */
    private $validator;

    /**
     * @var $eventDispatcher EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var $saltLength int
     */
    private $saltLength;

    /**
     * @var $saltKeyspace string
     */
    private $saltKeyspace;

    /**
     * RegisterUserHandler constructor.
     *
     * @param UserRepositoryInterface  $userRepository
     * @param ValidatorInterface       $validator
     * @param EventDispatcherInterface $eventDispatcher
     * @param int                      $saltLength
     * @param string                   $saltKeyspace
     */
    public function __construct(UserRepositoryInterface $userRepository, ValidatorInterface $validator, EventDispatcherInterface $eventDispatcher, $saltLength, $saltKeyspace)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
        $this->saltLength = $saltLength;
        $this->saltKeyspace = $saltKeyspace;
    }

    /**
     * Handles the RegisterUser command and creates a new user.
     *
     * @param RegisterUser $command
     */
    public function handle(RegisterUser $command)
    {
        $user = $command->user();

        // Validate the user entity
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;

            throw new ValidatorException($errorsString);
        }

        // Set default role for the new user
        $user->setRoles(array("ROLE_USER"));

        // Generate salt via internal function
        $user->setSalt($this->generateSalt());

        // Add the user to the repository
        $this->userRepository->add($user);

        // Dispatch UserRegisteredEvent
        $event = new UserRegisteredEvent($user);
        $this->eventDispatcher->dispatch(UserRegisteredEvent::NAME, $event);
    }

    /**
     * Generates a custom salt for the new user entity.
     *
     * Borrowed from http://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
     * @return string
     */
    private function generateSalt()
    {
        $str = '';
        $max = mb_strlen($this->saltKeyspace, '8bit') - 1;

        for ($i = 0; $i < $this->saltLength; ++$i) {
            $str .= $this->saltKeyspace[random_int(0, $max)];
        }

        return $str;
    }
}
