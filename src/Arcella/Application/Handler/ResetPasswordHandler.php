<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Application\Handler;

use Arcella\Domain\Command\ResetPassword;
use Arcella\Domain\Event\ResetPasswordEvent;
use Arcella\Domain\Repository\UserRepositoryInterface;
use Arcella\UtilityBundle\TokenValidator\TokenValidator;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @var $tokenValidator TokenValidator
     */
    private $tokenValidator;

    /**
     * ResetPasswordHandler constructor.
     *
     * @param UserRepositoryInterface  $userRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @param TokenValidator           $tokenValidator
     */
    public function __construct(UserRepositoryInterface $userRepository, EventDispatcherInterface $eventDispatcher, TokenValidator $tokenValidator)
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenValidator = $tokenValidator;
    }

    /**
     * Handles the ResetPassword command, which does the actual reset of a
     * given User entity.
     *
     * @param ResetPassword $command
     *
     * @throws EntityNotFoundException
     * @throws ValidatorException
     */
    public function handle(ResetPassword $command)
    {
        // Validate the Token
        if (!$this->tokenValidator->validateToken($command->token())) {
            throw new ValidatorException('Could not validate token: '.$command->token());
        }

        // Get User entity from the Tokens params
        $params = $this->tokenValidator->getTokenParams();
        $user = $this->userRepository->findOneBy(['username' => $params['username']]);

        if (!$user) {
            throw new EntityNotFoundException(
                'No entity found for username '.$params['username']
            );
        }

        // Set new PlainPassword
        $user->setPlainPassword($command->newPassword());
        $this->userRepository->save($user);

        // Remove Token from tokenRepository
        $this->tokenValidator->removeToken($command->token());

        // Trigger ResetPasswordEvent
        $event = new ResetPasswordEvent($user);
        $this->eventDispatcher->dispatch(ResetPasswordEvent::NAME, $event);
    }
}
