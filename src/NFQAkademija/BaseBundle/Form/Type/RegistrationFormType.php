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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('email')
            ->add('email', 'email', array(
                'label' => 'E-mail',
                'translation_domain' => 'UpsBaseBundle',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->remove('username')
            ->add('username', null, array(
                'label' => 'Username',
                'translation_domain' => 'UpsUserBundle',
                'attr' => array(
                    'class' => 'form-control'
                )))
            ->remove('plainPassword')
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array(
                    'label' => 'Enter password',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ),
                'second_options' => array('label' => 'Repeat password',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ),
                'invalid_message' => 'Password mismatch. Try again.',
            ))
            ->add('name', 'text', array(
                'label' => 'Name',
                'translation_domain' => 'UpsBaseBundle',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
        ;
    }

    public function getName()
    {
        return 'gurme_user_registration';
    }
}
