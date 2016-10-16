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

/**
 * Class ResetPasswordHandler
 */
class ResetPasswordHandler
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
     * Handles the ResetPassword command.
     *
     * @param ResetPasswordHandler $command
     *
     * @throws EntityNotFoundException
     * @throws ValidatorException
     */
    public function handle(ResetPasswordHandler $command)
    {
        $user = $this->userRepository->findOneBy(['email' => $command->email()]);

        if (!$user) {
            throw new EntityNotFoundException(
                'No entity found for username '.$command->username()
            );
        }

        if (!$this->passwordEncoder->isPasswordValid($user, $command->oldPassword())) {
            throw new ValidatorException(
                'Cannot update password for user, because of invalid credentials'
            );
        }

        $user->setPlainPassword($command->newPassword());

        $this->userRepository->save($user);

        $event = new UserUpdatedPasswordEvent($user);
        $this->eventDispatcher->dispatch(UserUpdatedPasswordEvent::NAME, $event);
    }
}
