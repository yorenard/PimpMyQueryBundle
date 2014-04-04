<?php

namespace YoRenard\PimpMyQueryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use YoRenard\PimpMyQueryBundle\Entity\PMQRight;

class RightType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('level', 'choice', array (
                    'label' => 'diffusion.form.level.label',
                    'choices' => array(
                        PMQRight::USER_TYPE_USER => 'right.form.type.user',
                        PMQRight::USER_TYPE_MANAGER => 'right.form.type.manager',
                    ),
                    'required' => false,
                    'multiple' => false,
                    'expanded' => false,
                    'empty_value' => false,
                ))
                ->add('service', 'entity', array(
                    'class' => 'YoRenardLFUserBundle:Service',
                    'property' => 'label',
                    'required' => false,
                    'empty_value' => 'select.form.service.default'
                ))
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array (
            'data_class' => 'YoRenard\PimpMyQueryBundle\Entity\PMQRight',
            'csrf_protection' => false,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'task_item',
        );
    }

    public function getName()
    {
        return 'right_form';
    }

}