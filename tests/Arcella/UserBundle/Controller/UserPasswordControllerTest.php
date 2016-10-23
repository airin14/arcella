<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\UserBundle\Controller;

use Arcella\Test\ArcellaWebTestCase;

class UserPasswordControllerTest extends ArcellaWebTestCase
{
    use UserLoginTrait;

    public function testUpdatePasswordAction()
    {
        $username    = "monty93";
        $password    = "arcella";
        $oldPassword = $password;
        $newPassword = "arcella";

        $this->doLogin($username, $password);
        $this->doPasswordUpdate($oldPassword, $newPassword);

        // Fetch the response
        $response = $this->client->getResponse()->getContent();

        // Assertions
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains('Your password has been updated successfully.', $response);
    }

    public function testUpdatePasswordActionWithInvalidCredentials()
    {
        $username    = "monty93";
        $password    = "arcella";
        $oldPassword = "invalid";
        $newPassword = "arcella";

        $this->doLogin($username, $password);
        $this->doPasswordUpdate($oldPassword, $newPassword);

        // Fetch the response
        $response = $this->client->getResponse()->getContent();

        // Assertions
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains("Cannot update password for user, because of invalid credentials", $response);
    }

    public function testAccessToUpdatePasswordAction()
    {
        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/_settings/password');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Login")')->count());
    }

    private function doPasswordUpdate($oldPassword, $newPassword)
    {
        // Check if the update form can be accessed
        $crawler = $this->client->request('GET', '/_settings/password');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Change your password")')->count());

        // Fill in and submit the form
        $form = $crawler->filter('form[name=user_update_password_form]')->form();
        $crawler = $this->client->submit($form, array(
            'user_update_password_form[oldPassword]' => $oldPassword,
            'user_update_password_form[newPassword][first]' => $newPassword,
            'user_update_password_form[newPassword][second]' => $newPassword,
        ));
    }
}
