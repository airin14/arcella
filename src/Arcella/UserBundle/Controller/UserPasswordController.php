<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Controller;

use Arcella\Domain\Command\UpdateUserPassword;
use Arcella\UserBundle\Form\Type\UserUpdatePasswordForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * UserPasswordController
 */
class UserPasswordController extends Controller
{
    /**
     * Manages the change of a users password.
     *
     * @Route("/_settings/password", name="security_update_password_form")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return Response The http-response
     */
    public function updatePasswordFormAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(UserUpdatePasswordForm::class);

        return $this->render('user/update_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Manages the change of a users password.
     *
     * @Route("/_settings/password", name="security_update_password")
     * @Method({"POST"})
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
            } catch (ValidatorException $e) {
                $this->addFlash('warning', $e->getMessage());
            }
        }

        return $this->redirectToRoute('security_update_password_form');
    }
}
