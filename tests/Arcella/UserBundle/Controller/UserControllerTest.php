<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\UserBundle\Controller;

use Arcella\Test\ArcellaWebTestCase;
use Nelmio\Alice\Fixtures;
use Faker\Provider\Internet as FakerProvider;

class UserControllerTest extends ArcellaWebTestCase
{
    use UserBackendTrait, UserRegistrationTrait;

    public function testRegisterAction()
    {
        $username = 'foo' . rand();
        $email    = 'foo@bar' . rand() . '.com';
        $password = 'arcella';

        $this->doRegister($username, $email, $password);
        $response = $this->client->getResponse()->getContent();

        // Assertions
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains('Logged in as', $response);
    }

    public function testSettingsAction()
    {
        $this->accessBackend();
        $response = $this->client->getResponse()->getContent();

        // Assertions
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains('Settings', $response);
    }
}