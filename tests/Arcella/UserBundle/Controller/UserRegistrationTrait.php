<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\UserBundle\Controller;

trait UserRegistrationTrait
{
    public function doRegister($username, $email, $password)
    {
        // Check if the registration form can be accessed
        $crawler = $this->client->request('GET', '/register');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Register")')->count());

        // Fill in and submit the form
        $form = $crawler->filter('form[name=user_registration_form]')->form();
        $crawler = $this->client->submit($form, array(
            'user_registration_form[username]' => $username,
            'user_registration_form[email]' => $email,
            'user_registration_form[plainPassword][first]' => $password,
            'user_registration_form[plainPassword][second]' => $password,
        ));
    }
}