<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\UserBundle\Controller;

use Arcella\Test\ArcellaWebTestCase;

class PasswordRecoveryTest extends ArcellaWebTestCase
{
    use UserLoginTrait;

    private $message;

    public function testPasswordRecovery()
    {
        $this->markTestSkipped("Because of a Profiler error emails from previous requests can't be accessed.");

        $username    = 'ivy.mann';
        $email       = 'fschmeler@gmail.com';
        $newPassword = "arcella";

        $this->requestRecoveryMail($email);

        // Assertions
        $response = $this->client->getResponse()->getContent();
        $this->assertContains("Please check your emails, we sent you an email to reset your password", $response);

        $this->getEmailFromProfiler();

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $this->message);
        $this->assertEquals('Password recovery', $this->message->getSubject());
        $this->assertEquals('noreply@arcella.dev', key($this->message->getFrom()));
        $this->assertEquals($email, key($this->message->getTo()));

        $token = $this->getTokenFromEmailBody($this->message->getBody());
        $this->resetPassword($token, $newPassword);

        // Fetch the response
        $response = $this->client->getResponse()->getContent();

        // Assertions
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains("Your password has been updated successfully", $response);
    }

    private function requestRecoveryMail($email)
    {
        // Check if the update form can be accessed
        $crawler = $this->client->request('GET', '/password_recovery');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Recover your password")')->count());

        $this->client->enableProfiler();

        // Fill in and submit the form
        $form = $crawler->filter('form[name=recover_password_form]')->form();
        $this->client->submit($form, array(
            'recover_password_form[email]' => $email,
        ));
    }

    private function resetPassword($token, $newPassword)
    {
        // Check if the update form can be accessed
        $crawler = $this->client->request('GET', '/password_reset/'.$token);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Reset your password")')->count());

        // Fill in and submit the form
        $form = $crawler->filter('form[name=reset_password_form]')->form();
        $this->client->submit($form, array(
            'reset_password_form[newPassword][first]' => $newPassword,
            'reset_password_form[newPassword][second]' => $newPassword,
        ));
    }

    private function getTokenFromEmailBody($body)
    {
        $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";

        if(preg_match_all("/$regexp/siU", $body, $matches, PREG_SET_ORDER)) {
            $token = $matches[3];
            return $token;
        }

        return false;
    }

    private function getEmailFromProfiler()
    {
        $mailCollector = $this->client->getProfile()->getCollector('swiftmailer');
        $this->assertEquals(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $this->message = $collectedMessages[0];
    }
}
