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
use Arcella\UtilityBundle\TokenValidator\TokenValidator;
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
     * @var $tokenValidator TokenValidator
     */
    private $tokenValidator;

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
     * @param TokenValidator           $tokenValidator
     * @param int                      $saltLength
     * @param string                   $saltKeyspace
     */
    public function __construct(UserRepositoryInterface $userRepository, ValidatorInterface $validator, EventDispatcherInterface $eventDispatcher, TokenValidator $tokenValidator, $saltLength, $saltKeyspace)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenValidator = $tokenValidator;
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
        $user = new User();
        $user->setUsername($command->username());
        $user->setEmail($command->email());
        $user->setEmailIsVerified(false);
        $user->setPlainPassword($command->password());
        $user->setRoles(array("ROLE_USER"));
        $user->setSalt($this->tokenValidator->createSalt($this->saltLength, $this->saltKeyspace));

        // Validate the User entity
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new ValidatorException($errorsString);
        }

        $this->userRepository->add($user);

        $event = new UserRegisteredEvent($user);
        $this->eventDispatcher->dispatch(UserRegisteredEvent::NAME, $event);
    }
}
