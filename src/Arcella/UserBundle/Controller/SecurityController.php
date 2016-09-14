<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Controller;

use Arcella\Domain\Command\UpdateUserPassword;
use Arcella\UserBundle\Form\Type\LoginForm;
use Arcella\UserBundle\Form\Type\UserUpdatePasswordForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
     * @Method({"POST","GET"})
     *
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
     * @Method("GET")
     *
     * @throws \Exception When reached, because this is implemented by Symfony itself.
     */
    public function logoutAction()
    {
        throw new \Exception('this should not be reached!');
    }

    /**
     * Manages the change of a users password.
     *
     * @Route("/_settings/updatepassword", name="security_update_password")
     * @Method({"POST","GET"})
     *
     * @param Request $request
     *
     * @return Response The response to be rendered
     */
    public function updatePasswordAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(UserUpdatePasswordForm::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $user = $this->getUser();
                $data = $form->getData();

                $command = new UpdateUserPassword($user->getUsername(), $data['oldPassword'], $data['newPassword']);
                $this->get('command_bus')->handle($command);

                $this->addFlash('success', $this->get('translator')->trans('user.password.update.success'));
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
            }
        }

        return $this->render('user/update_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
