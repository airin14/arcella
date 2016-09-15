<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Security;

use Arcella\UserBundle\Form\Type\LoginForm;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Class LoginFormAuthenticator
 *
 * @package Arcella\UserBundle\Security
 */
class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    /**
     * LoginFormAuthenticator constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param EntityManager        $em
     * @param RouterInterface      $router
     * @param UserPasswordEncoder  $passwordEncoder
     */
    public function __construct(FormFactoryInterface $formFactory, EntityManager $em, RouterInterface $router, UserPasswordEncoder $passwordEncoder)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Get the credentials to be used in this login.
     *
     * @param Request $request
     * @return mixed|null
     */
    public function getCredentials(Request $request)
    {
        $isLoginSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');

        if (!$isLoginSubmit) {
            // skip authentication
            return null;
        }

        $form = $this->formFactory->create(LoginForm::class);
        $form->handleRequest($request);

        $data = $form->getData();
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['username']
        );

        return $data;
    }

    /**
     * Get the user entity.
     *
     * @param mixed                 $credentials
     * @param UserProviderInterface $userProvider
     * @return null|object
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['username'];

        return $this->em->getRepository('ArcellaUserBundle:User')
            ->findOneBy(['username' => $username]);
    }

    /**
     * Checking if all the credentials are correct.
     *
     * @param mixed         $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['password'];

        if ($this->passwordEncoder->isPasswordValid($user, $password)) {
            return true;
        }

        return false;
    }

    /**
     * What happens after successful authentication?
     *
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $providerKey
     *
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetPath = null;

        // if the user hit a secure page and start() was called, this was
        // the URL they were on, and probably where you want to redirect to
        if ($request->getSession() instanceof SessionInterface) {
            $targetPath = $this->getTargetPath($request->getSession(), $providerKey);
        }

        if (!$targetPath) {
            $targetPath = $this->router->generate('homepage');
        }

        return new RedirectResponse($targetPath);
    }

    /**
     * Get the URL to the UserController:loginAction
     *
     * @return string
     */
    protected function getLoginUrl()
    {
        return $this->router->generate('security_login');
    }
}
