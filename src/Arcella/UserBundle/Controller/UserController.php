<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Controller;

use Arcella\Domain\Command\RegisterUser;
use Arcella\UserBundle\Entity\User;
use Arcella\UserBundle\Form\UserRegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 *
 * Implements all the user related things, like registering a new user.
 *
 * @package Arcella\UserBundle\Controller
 */
class UserController extends Controller
{
    /**
     * Manages the registration of users.
     *
     * @Route("/register", name="user_register")
     * @param Request $request
     *
     * @return Response The response to be rendered
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(UserRegistrationForm::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $user = $form->getData();

                $command = new RegisterUser($user);
                $this->get('command_bus')->handle($command);

                $this->addFlash('success', 'Welcome '.$user->getEmail());

                return $this->get('security.authentication.guard_handler')
                    ->authenticateUserAndHandleSuccess(
                        $user,
                        $request,
                        $this->get('arcella.security.login_form_authenticator'),
                        'main'
                    );
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
            }
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
