<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Controller;

use Arcella\Domain\Command\RecoverPassword;
use Arcella\Domain\Command\ResetPassword;
use Arcella\Domain\Command\UpdateUserEmail;
use Arcella\Domain\Command\ValidateUserEmail;
use Arcella\Domain\Event\RecoverPasswordEvent;
use Arcella\UserBundle\Form\Type\RecoverPasswordForm;
use Arcella\UserBundle\Form\Type\ResetPasswordForm;
use Arcella\UserBundle\Form\Type\UserUpdateEmailForm;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * Class ResetPasswordController
 */
class ResetPasswordController extends Controller
{
    /**
     * Manages the reset of a users password
     *
     * @Route("/password_reset/{token}", name="user_reset_password_form")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param string  $token
     *
     * @return Response The response to be rendered
     */
    public function resetPasswordFormAction(Request $request, $token)
    {
        $form = $this->createForm(ResetPasswordForm::class);

        return $this->render('user/password_reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Reset password
     *
     * @Route("/password_reset/{token}", name="user_reset_password")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param string  $token
     *
     * @return Response The response to be rendered
     */
    public function recoverPasswordAction(Request $request, $token)
    {
        $form = $this->createForm(ResetPasswordForm::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $data = $form->getData();

                $command = new ResetPassword($data['newPassword'], $token);
                $this->get('command_bus')->handle($command);

                $this->addFlash('success', $this->get('translator')->trans('user.password.reset.success'));
            } catch (EntityNotFoundException $e) {
                $this->addFlash('warning', $this->get('translator')->trans('user.password.reset.failure'));
            }
        } else {
            return $this->render('user/password_reset.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute('security_login');
    }
}
