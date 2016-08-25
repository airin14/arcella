<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Arcella\UserBundle\Form\LoginForm;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SecurityController
 *
 * Used for security relevant routes, such as login and logout of users.
 *
 * @package Arcella\UserBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * Manages the login of users.
     *
     * @Route("/login", name="security_login")
     * @return Response The response to be rendered
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername,
        ]);

        return $this->render(
            'security/login.html.twig',
            array(
                'form' => $form->createView(),
                'error' => $error,
            )
        );
    }

    /**
     * Manages the logout of users. (In truth this is just a dummy function, because the logout process of users is
     * implemented by Symfony itself.)
     *
     * @Route("/logout", name="security_logout")
     * @throws \Exception When reached, because this is implemented by Symfony itself.
     */
    public function logoutAction()
    {
        throw new \Exception('this should not be reached!');
    }
}
