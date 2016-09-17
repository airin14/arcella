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
use Arcella\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * This class is responsible for handling the RegisterUser command, which is
 * used to add a new User entity to the system.
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
     * @var $saltLength int Length of the salt.
     */
    private $saltLength;

    /**
     * @var $saltKeyspace string Keyspace that is used while creating the salt.
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
     * Handles the RegisterUser command, adds a new User entity to the
     * UserRepository and dispatches the UserRegistered event.
     *
     * @param RegisterUser $command
     *
     * @throws ValidatorException
     */
    public function handle(RegisterUser $command)
    {
        // Create User entity and set some data
        $user = new User();

        $user->setUsername($command->username());
        $user->setEmail($command->email());
        $user->setPlainPassword($command->password());
        $user->setRoles(array("ROLE_USER"));
        $user->setSalt($this->generateSalt());

        // Validate the User entity
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new ValidatorException($errorsString);
        }

        // Add the User to the UserRepository
        $this->userRepository->add($user);

        // Dispatch UserRegisteredEvent
        $event = new UserRegisteredEvent($user);
        $this->eventDispatcher->dispatch(UserRegisteredEvent::NAME, $event);
    }

    /**
     * Generates a custom salt for the new user entity. This code was borrowed from
     * http://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
     *
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
