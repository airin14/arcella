<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Controller;

use Arcella\Domain\Command\RecoverPassword;
use Arcella\UserBundle\Form\Type\RecoverPasswordForm;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RecoverPasswordController
 */
class RecoverPasswordController extends Controller
{
    /**
     * Show recovery form
     *
     * @Route("/password_recovery", name="user_recover_password_form")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return Response The response to be rendered
     */
    public function recoverPasswordFormAction(Request $request)
    {
        $form = $this->createForm(RecoverPasswordForm::class);

        return $this->render('user/password_recover.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Send recovery mail
     *
     * @Route("/password_recovery", name="user_recover_password")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return Response The response to be rendered
     */
    public function recoverPasswordAction(Request $request)
    {
        $form = $this->createForm(RecoverPasswordForm::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $data = $form->getData();

                $command = new RecoverPassword($data['email']);
                $this->get('command_bus')->handle($command);

                $this->addFlash('success', $this->get('translator')->trans('user.password.recovery.sent'));
            } catch (EntityNotFoundException $e) {
                $this->addFlash('warning', $this->get('translator')->trans('user.password.recovery.failure'));
            }
        } else {
            return $this->render('user/password_recover.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute('security_login');
    }
}
