<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The UserRegistrationForm is used for updating a users password.
 */
class UserUpdatePasswordForm extends AbstractType
{
    /**
     * Build the actual Form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, array('label' => 'label.password_old'))
            ->add('newPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'   => array('label' => 'label.password_new'),
                'second_options'  => array('label' => 'label.password_repeat'),
                'invalid_message' => 'user.password.mismatch',
            ));
    }

    /**
     * Configuration of the Form.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms',
        ]);
    }
}
