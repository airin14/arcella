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
use Arcella\UserBundle\Utils\TokenValidator\TokenValidator;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * This class is responsible for handling the UpdateUserEmail command, which is
 * used to change the email address of a given User entity.
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
     * UpdateUserEmailHandler constructor.
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
     * Handles the UpdateUserEmail command and changes the email address of a
     * User entity.
     *
     * @param UpdateUserEmail $command
     *
     * @throws EntityNotFoundException
     */
    public function handle(UpdateUserEmail $command)
    {
        // Fetch User entity
        $user = $this->userRepository->findOneBy(['username' => $command->username()]);

        if (!$user) {
            throw new EntityNotFoundException(
                'No entity found for username '.$command->username()
            );
        }

        // Set the new email address
        $user->setEmail($command->email());
        $user->setEmailIsVerified(false);

        // Validate the User entity
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new ValidatorException($errorsString);
        }

        // Add the User entity to the UserRepository
        $this->userRepository->save($user);

        // Dispatch UserUpdatedEmailEvent
        $event = new UserUpdatedEmailEvent($user);
        $this->eventDispatcher->dispatch(UserUpdatedEmailEvent::NAME, $event);
    }
}
