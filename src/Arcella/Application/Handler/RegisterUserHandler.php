<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Application\Handler;

use Arcella\Domain\Command\RegisterUser;
use Arcella\Domain\Event\UserRegisteredEvent;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @var $validator Validator
     */
    private $validator;

    /**
     * @var $eventDispatcher EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var $saltLength int
     */
    private $saltLength;

    /**
     * @var $saltLeyspace string
     */
    private $saltKeyspace;

    /**
     * RegisterUserHandler constructor.
     *
     * @param EntityRepository   $userRepository
     * @param ValidatorInterface $validator
     * @param EventDispatcher    $eventDispatcher
     * @param int                $saltLength
     * @param string             $saltKeyspace
     */
    public function __construct(EntityRepository $userRepository, ValidatorInterface $validator, EventDispatcherInterface $eventDispatcher, $saltLength, $saltKeyspace)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
        $this->saltLength = $saltLength;
        $this->saltKeyspace = $saltKeyspace;
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

        $user->setSalt($this->generateSalt());

        $this->userRepository->add($user);

        $event = new UserRegisteredEvent($user);
        $this->eventDispatcher->dispatch(UserRegisteredEvent::NAME, $event);
    }

    /**
     * @return string
     */
    private function generateSalt()
    {
        $str = '';
        $max = mb_strlen($this->saltKeyspace, '8bit') - 1;

        for ($i = 0; $i < $this->saltLength; ++$i) {
            $str .= $this->saltKeyspace[random_int(0, $max)];
        }

        return $str;
    }
}
