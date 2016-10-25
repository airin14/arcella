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
use Arcella\UtilityBundle\Repository\TokenRepository;
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
     * @var TokenRepository
     */
    private $tokenRepository;

    /**
     * @var $tokenValidator TokenValidator
     */
    private $tokenValidator;

    /**
     * RegisterUserHandler constructor.
     *
     * @param UserRepositoryInterface  $userRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @param TokenRepository          $tokenRepository
     * @param TokenValidator           $tokenValidator
     */
    public function __construct(UserRepositoryInterface $userRepository, EventDispatcherInterface $eventDispatcher, TokenRepository $tokenRepository, TokenValidator $tokenValidator)
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenRepository = $tokenRepository;
        $this->tokenValidator = $tokenValidator;
    }

    /**
     * Handles the ResetPassword command.
     *
     * @param ResetPassword $command
     *
     * @throws EntityNotFoundException
     * @throws ValidatorException
     */
    public function handle(ResetPassword $command)
    {
        if (!$this->tokenValidator->validateToken($command->token())) {
            throw new ValidatorException('Could not validate token: '.$command->token());
        }

        $token = $this->tokenRepository->findOneBy(['key' => $command->token()]);
        $params = $token->getParams();

        $user = $this->userRepository->findOneBy(['username' => $params['username']]);

        if (!$user) {
            throw new EntityNotFoundException(
                'No entity found for username '.$params['username']
            );
        }

        $user->setPlainPassword($command->newPassword());
        $this->userRepository->save($user);

        $this->tokenValidator->removeToken($command->token());

        $event = new ResetPasswordEvent($user);
        $this->eventDispatcher->dispatch(ResetPasswordEvent::NAME, $event);
    }
}
