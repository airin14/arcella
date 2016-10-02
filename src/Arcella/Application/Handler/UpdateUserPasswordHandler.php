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
 * This class is responsible for handling a UpdateUserPassword command, which
 * is used to change the password of a given user.
 */
class UpdateUserPasswordHandler
{
    /**
     * @var $userRepository EntityRepository
     */
    private $userRepository;

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
     * @param EventDispatcherInterface $eventDispatcher
     * @param UserPasswordEncoder      $passwordEncoder
     */
    public function __construct(UserRepositoryInterface $userRepository, EventDispatcherInterface $eventDispatcher, UserPasswordEncoder $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Handles the UpdateUserPassword command and changes the password of a User
     * entity.
     *
     * @param UpdateUserPassword $command
     *
     * @throws EntityNotFoundException
     * @throws ValidatorException
     */
    public function handle(UpdateUserPassword $command)
    {
        // Fetch User entity
        $user = $this->userRepository->findOneBy(['username' => $command->username()]);

        if (!$user) {
            throw new EntityNotFoundException(
                'No entity found for username '.$command->username()
            );
        }

        // Checks if the credentials are valid
        if (!$this->passwordEncoder->isPasswordValid($user, $command->oldPassword())) {
            throw new ValidatorException(
                'Cannot update password for user, because of invalid credentials'
            );
        }

        // Set the new password
        $user->setPlainPassword($command->newPassword());

        // Add the User Entity to the UserRepository
        $this->userRepository->save($user);

        // Dispatch UserUpdatedPasswordEvent
        $event = new UserUpdatedPasswordEvent($user);
        $this->eventDispatcher->dispatch(UserUpdatedPasswordEvent::NAME, $event);
    }
}
