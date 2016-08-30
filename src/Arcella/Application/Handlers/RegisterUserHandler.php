<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Application\Handlers;

use Arcella\UserBundle\Entity\User;
use Arcella\Domain\Command\RegisterUser;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * Class RegisterUserHandler
 * @package Arcella\Application\Handlers
 */
class RegisterUserHandler
{
    /**
     * @var $userRepository EntityRepository
     */
    private $userRepository;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * RegisterUserHandler constructor.
     *
     * @param ValidatorInterface $validator
     * @param EntityRepository   $userRepository
     */
    public function __construct(EntityRepository $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    /**
     * @param RegisterUser $command
     */
    public function handle(RegisterUser $command)
    {
        $user = $command->user();

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;

            throw new ValidatorException($errorsString);
        }

        $user->setRoles(array("ROLE_USER"));

        $user->setSalt($this->generateSalt(16));

        $this->userRepository->add($user);
    }

    /**
     * @param $length
     * @param string $keyspace
     * @return string
     */
    private function generateSalt($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;

        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }

        return $str;
    }
}
