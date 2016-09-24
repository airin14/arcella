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
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Exception\ValidatorException;

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

    /**
     * Manages the change of a users password.
     *
     * @Route("/_settings/password", name="security_update_password")
     * @Method({"POST","GET"})
     *
     * @param Request $request
     *
     * @return Response The http-response
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
                $input = $form->getData();

                $command = new UpdateUserPassword($user->getUsername(), $input['oldPassword'], $input['newPassword']);
                $this->get('command_bus')->handle($command);

                $this->addFlash('success', $this->get('translator')->trans('user.password.update.success'));
            } catch (ValidatorException) {
                $this->addFlash('warning', $e->getMessage());
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
            }
        }

        return $this->render('user/update_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
