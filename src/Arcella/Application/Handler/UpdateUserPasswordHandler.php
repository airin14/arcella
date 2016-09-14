<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Application\Handler;

use Arcella\Domain\Command\UpdateUserPassword;
use Arcella\Domain\Event\UserUpdatedPasswordEvent;
use Arcella\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * This class is a Handler and for handling a UpdateUserPassword command, which is used to change the password of a
 * given user.
 *
 * @package Arcella\Application\Handler
 */
class UpdateUserPasswordHandler
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
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    /**
     * RegisterUserHandler constructor.
     *
     * @param UserRepositoryInterface  $userRepository
     * @param ValidatorInterface       $validator
     * @param EventDispatcherInterface $eventDispatcher
     * @param UserPasswordEncoder      $passwordEncoder
     */
    public function __construct(UserRepositoryInterface $userRepository, ValidatorInterface $validator, EventDispatcherInterface $eventDispatcher, UserPasswordEncoder $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Handles the UpdateUserPassword command and changes the password of a user.
     *
     * @param UpdateUserPassword $command
     *
     * @throws EntityNotFoundException
     */
    public function handle(UpdateUserPassword $command)
    {
        $user = $this->userRepository->findOneBy(['username' => $command->username()]);

        if (!$user) {
            throw new EntityNotFoundException(
                'No user found for username '.$command->username()
            );
        }

        if (!$this->passwordEncoder->isPasswordValid($user, $command->oldPassword())) {
            throw new ValidatorException(
                'Cannot update password for user, because of invalid credentials'
            );
        }

        $user->setPlainPassword($command->newPassword());

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

        // Add the user to the repository
        $this->userRepository->save($user);

        // Dispatch UserUpdatedPasswordEvent
        $event = new UserUpdatedPasswordEvent($user);
        $this->eventDispatcher->dispatch(UserUpdatedPasswordEvent::NAME, $event);
    }
}
