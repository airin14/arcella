<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\EventListener;

use Arcella\Domain\Event\UserRegisteredEvent;
use Arcella\Domain\Event\UserUpdatedEmailEvent;
use Arcella\UserBundle\Utils\TokenValidator;

/**
 * Class UserEmailValidationListener
 * @package Arcella\UserBundle\EventListener
 */
class UserEmailValidationListener
{
    protected $twig;
    protected $mailer;

    /**
     * UserEmailValidationListener constructor.
     * @param \Twig_Environment $twig
     * @param \Swift_Mailer     $mailer
     * @param TokenValidator    $tokenValidator
     */
    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer, TokenValidator $tokenValidator)
    {
        $this->twig           = $twig;
        $this->mailer         = $mailer;
        $this->tokenValidator = $tokenValidator;
    }

    /**
     * @param UserUpdatedEmailEvent $event
     */
    public function onUserUpdatedEmail(UserUpdatedEmailEvent $event)
    {
        $user = $event->getUser();

        $token = $this->tokenValidator->generateToken();

        $twigParams = array(
            'name' => $user->getUsername(),
            'token' => $token,
        );

        $message = \Swift_Message::newInstance()
            ->setSubject('Please validate your email address')
            ->setFrom('noreply@arcella.dev')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'emails/email_validation.html.twig',
                    $twigParams
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }

    /**
     * @param UserRegisteredEvent $event
     */
    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $user = $event->getUser();

        $token = $this->tokenValidator->generateToken();

        $twigParams = array(
            'name' => $user->getUsername(),
            'token' => $token,
        );

        $message = \Swift_Message::newInstance()
            ->setSubject('Welcome to arcella.dev!')
            ->setFrom('noreply@arcella.dev')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'emails/email_welcome.html.twig',
                    $twigParams
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
