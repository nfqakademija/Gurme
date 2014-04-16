<?php

namespace Gurme\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecipeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'attr' => array(
                    'class' => 'form-control'
                ),
                'data' => 'Sample Recipe'
            ))
            ->add('ingredients', 'textarea', array(
                'attr' => array(
                    'label' => 'Ingredients',
                    'class' => 'form-control',
                    'rows' => '8'
                )
            ))
            ->add('directions', 'textarea', array(
                'attr' => array(
                    'class' => 'form-control',
                    'rows' => '8'
                )
            ))
            ->add('about', 'textarea', array(
                'attr' => array(
                    'class' => 'form-control',
                    'rows' => '4'
                )
            ))
            ->add('servings', 'integer', array(
                'attr' => array(
                    'class' => 'form-control'
                ),
                'data' => '4'
            ))
            ->add('prepTime', 'time', array(
                'label' => 'Preparation Time [hh:mm]',
                'input'  => 'datetime',
                'widget' => 'text',
                'data' => new \DateTime("0000-00-00 00:00:00")
            ))
            ->add('cookTime', 'time', array(
                'label' => 'Cooking Time [hh:mm]',
                'input'  => 'datetime',
                'widget' => 'text',
                'data' => new \DateTime("0000-00-00 00:00:00")
            ))
            ->add('readyTime', 'time', array(
                'label' => 'Ready Time [hh:mm] (optional)',
                'input'  => 'datetime',
                'widget' => 'text',
                'data' => new \DateTime("0000-00-00 00:00:00")
            ))
            ->add('calories', 'integer', array(
                'attr' => array(
                    'class' => 'form-control'
                ),
                'data' => '500'
            ))
            ->add('carbs', 'number', array(
                'attr' => array(
                    'class' => 'form-control'
                ),
                'data' => '52'
            ))
            ->add('fat', 'number', array(
                'attr' => array(
                    'class' => 'form-control'
                ),
                'data' => '23'
            ))
            ->add('protein', 'number', array(
                'attr' => array(
                    'class' => 'form-control'
                ),
                'data' => '81'
            ))
            ->add('approved')
            ->add('rating')
            ->add('private')
            ->add('coverPhoto')
            ->add('user')
            ->add('createdAt')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gurme\MainBundle\Entity\Recipe'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gurme_mainbundle_recipe';
    }
}
