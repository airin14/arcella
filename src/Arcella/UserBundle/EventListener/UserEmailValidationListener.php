<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\EventListener;

use Arcella\Domain\Event\UserUpdatedEmailEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

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
     */
    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    /**
     * @param UserUpdatedEmailEvent $event
     */
    public function onUserUpdatedEmail(UserUpdatedEmailEvent $event)
    {
        $user = $event->getUser();

        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
                $this->twig->render(
                    'Emails/email_validation.html.twig',
                    array('name' => $user->getUsername())
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
