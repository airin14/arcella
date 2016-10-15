<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Controller;

use Arcella\UserBundle\Form\Type\LoginForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * The SecurityController is Used for security relevant routes, such as login
 * and logout of users.
 */
class SecurityController extends Controller
{
    /**
     * Manages the login of users.
     *
     * @Route("/login", name="security_login")
     * @Method({"POST","GET"})
     *
     * @return Response The http-response
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $form = $this->createForm(LoginForm::class, [
            'username' => $authenticationUtils->getLastUsername(),
        ]);

        return $this->render(
            'security/login.html.twig',
            array(
                'form'  => $form->createView(),
                'error' => $authenticationUtils->getLastAuthenticationError(),
            )
        );
    }

    /**
     * Manages the logout of users. (In truth this is just a dummy because the
     * actual process is done by Symfony itself...)
     *
     * @Route("/logout", name="security_logout")
     * @Method("GET")
     *
     * @throws \Exception
     */
    public function logoutAction()
    {
        throw new \Exception('This should not be reached!');
    }
}
