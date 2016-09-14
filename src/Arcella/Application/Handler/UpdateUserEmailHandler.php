<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Application\Handler;

use Arcella\Domain\Command\UpdateUserEmail;
use Arcella\Domain\Event\UserUpdatedEmailEvent;
use Arcella\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * This class is a Handler and for handling a UpdateUserEmail command, which is used to change the email address of a
 * given user.
 *
 * @package Arcella\Application\Handler
 */
class UpdateUserEmailHandler
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
     * RegisterUserHandler constructor.
     *
     * @param UserRepositoryInterface  $userRepository
     * @param ValidatorInterface       $validator
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(UserRepositoryInterface $userRepository, ValidatorInterface $validator, EventDispatcherInterface $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Handles the UpdateUserPassword command and changes the password of a user.
     *
     * @param UpdateUserEmail $command
     *
     * @throws EntityNotFoundException
     */
    public function handle(UpdateUserEmail $command)
    {
        $user = $this->userRepository->findOneBy(['username' => $command->username()]);

        if (!$user) {
            throw new EntityNotFoundException(
                'No user found for username '.$command->username()
            );
        }

        $user->setEmail($command->email());

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

        // Dispatch UserUpdatedEmailEvent
        $event = new UserUpdatedEmailEvent($user);
        $this->eventDispatcher->dispatch(UserUpdatedEmailEvent::NAME, $event);
    }
}
