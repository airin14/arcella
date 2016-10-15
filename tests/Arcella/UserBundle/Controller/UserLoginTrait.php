<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\UserBundle\Controller;

trait UserLoginTrait
{
    public function doLogin($username, $password)
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Login")')->count());

        // Fill in and submit the form
        $form = $crawler->filter('form[name=login_form]')->form();
        $crawler = $this->client->submit($form, array(
            'login_form[username]' => $username,
            'login_form[password]' => $password,
        ));
    }
}