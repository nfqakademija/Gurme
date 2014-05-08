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
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $constraint = new UserPassword();

        $builder
            ->add('name', null, array(
                'label' => 'Name or Company',
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('email', 'email', array(
                'label' => 'E-mail:',
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
        ;

        $builder
            ->remove('current_password')
            ->remove('username')
            ->add('current_password', 'password', array(
            'label' => 'Current password:',
            'translation_domain' => 'FOSUserBundle',
            'mapped' => false,
            'constraints' => $constraint,
            'attr' => array(
                'class' => 'form-control'
            )
        ));
    }

    public function getName()
    {
        return 'gurme_user_profile';
    }
}
