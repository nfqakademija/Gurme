<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NFQAkademija\BaseBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use FOS\UserBundle\Form\Type\ChangePasswordFormType as BaseType;

class ChangePasswordFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraint = new UserPassword();

        $builder->add('current_password', 'password', array(
            'label' => 'Current password:',
            'translation_domain' => 'FOSUserBundle',
            'mapped' => false,
            'constraints' => $constraint,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Current password'
            )
        ));
        $builder
            ->remove('plainPassword')
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array(
                    'label' => 'Enter new password:',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'New password'
                    )
                ),
                'second_options' => array('label' => 'Repeat new password:',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Repeat password'
                    )
                ),
                'invalid_message' => 'Password mismatch. Try again.',
            ))
        ;
    }

    public function getName()
    {
        return 'gurme_user_change_password';
    }
}
