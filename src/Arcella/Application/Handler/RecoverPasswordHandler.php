<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Application\Handler;

use Arcella\Domain\Command\RecoverPassword;
use Arcella\Domain\Event\RecoverPasswordEvent;
use Arcella\Domain\Repository\UserRepositoryInterface;
use Arcella\UtilityBundle\TokenValidator\TokenValidator;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This class is responsible for handling the RecoverPassword command.
 */
class RecoverPasswordHandler
{
    /**
     * @var $userRepository EntityRepository
     */
    private $userRepository;

    /**
     * @var $mailer \Swift_Mailer
     */
    private $mailer;

    /**
     * @var $twig \Twig_Environment
     */
    private $twig;

    /**
     * @var $eventDispatcher EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * RecoverPasswordHandler constructor.
     *
     * @param UserRepositoryInterface  $userRepository
     * @param \Swift_Mailer            $mailer
     * @param \Twig_Environment        $twig
     * @param TokenValidator           $tokenValidator
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(UserRepositoryInterface $userRepository, \Swift_Mailer $mailer, \Twig_Environment $twig, TokenValidator $tokenValidator, EventDispatcherInterface $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->tokenValidator = $tokenValidator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Handles the RecoverPassword command and is used to reset the password
     * of a user entity.
     *
     * @param RecoverPassword $command
     *
     * @throws EntityNotFoundException
     */
    public function handle(RecoverPassword $command)
    {
        $user = $this->userRepository->findOneBy(['email' => $command->email()]);

        if (!$user) {
            throw new EntityNotFoundException(
                'No entity found for email address '.$command->email()
            );
        }

        $params = array(
            'username' => $user->getUsername(),
        );

        $token = $this->tokenValidator->generateToken($params);

        $twigParams = array(
            'name' => $user->getUsername(),
            'token' => $token,
        );

        $message = \Swift_Message::newInstance()
            ->setSubject('Password recovery')
            ->setFrom('noreply@arcella.dev')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'emails/password_reset.html.twig',
                    $twigParams
                ),
                'text/html'
            );

        $this->mailer->send($message);

        $event = new RecoverPasswordEvent($user);
        $this->eventDispatcher->dispatch(RecoverPasswordEvent::NAME, $event);
    }
}
