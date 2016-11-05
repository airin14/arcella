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
     * Handles the RecoverPassword command and is used in the first step of
     * resetting the password of a given User entity.
     *
     * @param RecoverPassword $command
     *
     * @throws EntityNotFoundException
     */
    public function handle(RecoverPassword $command)
    {
        // Get user from userRepository
        $user = $this->userRepository->findOneBy(['email' => $command->email()]);

        if (!$user) {
            throw new EntityNotFoundException(
                'No entity found for email address '.$command->email()
            );
        }

        // Set some parameters for the Token
        $params = array(
            'username' => $user->getUsername(),
        );

        $token = $this->tokenValidator->generateToken($params);

        // Set some parameters for the email
        $twigParams = array(
            'name' => $user->getUsername(),
            'token' => $token,
        );

        // Create \Swift_Message object for email
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

        // Send the email
        $this->mailer->send($message);

        // Trigger RecoverPasswordEvent
        $event = new RecoverPasswordEvent($user);
        $this->eventDispatcher->dispatch(RecoverPasswordEvent::NAME, $event);
    }
}
