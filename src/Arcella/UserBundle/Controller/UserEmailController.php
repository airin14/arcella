<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Controller;

use Arcella\Domain\Command\UpdateUserEmail;
use Arcella\Domain\Command\ValidateUserEmail;
use Arcella\UserBundle\Form\Type\UserUpdateEmailForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * The UserEmailController implements everything related to the email address
 * of a user.
 */
class UserEmailController extends Controller
{
    /**
     * Manages the change of a users email address.
     *
     * @Route("/_settings/email", name="user_update_email_form")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return Response The response to be rendered
     */
    public function updateEmailFormAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();
        $form = $this->createForm(UserUpdateEmailForm::class, [
            'email' => $user->getEmail(),
        ]);

        return $this->render('user/update_email.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Manages the change of a users email address.
     *
     * @Route("/_settings/email", name="user_update_email")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return Response The response to be rendered
     */
    public function setEmailAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        $form = $this->createForm(UserUpdateEmailForm::class, [
            'email' => $user->getEmail(),
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $data = $form->getData();

                $command = new UpdateUserEmail($user->getUsername(), $data['email']);
                $this->get('command_bus')->handle($command);

                $this->addFlash('success', $this->get('translator')->trans('user.email.update.success'));
            } catch (ValidatorException $e) {
                $this->addFlash('warning', $e->getMessage());
            }
        }

        return $this->redirectToRoute('user_update_email_form');
    }

    /**
     * Manages the validation users email address.
     *
     * @Route("/validate/email/{token}", name="user_validate_email")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param string  $token
     *
     * @return Response The response to be rendered
     */
    public function validateEmailAction(Request $request, $token)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        try {
            $command = new ValidateUserEmail($user->getUsername(), $token);
            $this->get('command_bus')->handle($command);

            $this->addFlash('success', $this->get('translator')->trans('user.email.validate.success'));
        } catch (\Exception $e) {
            $this->addFlash('warning', $this->get('translator')->trans('user.email.validate.failure'));
        }

        return $this->redirectToRoute('user_settings');
    }
}
