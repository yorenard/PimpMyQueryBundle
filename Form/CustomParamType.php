<?php

namespace YoRenard\PimpMyQueryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use YoRenard\PimpMyQueryBundle\Entity\PMQRunQueue;

class CustomParamType extends AbstractType
{
    const CUSTOM_PARAM_TYPE_FORM_NAME = 'pmq_form';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('runExec', 'choice', array(
                    'required' => true,
                    'label' => 'customParam.form.runExec.label',
                    'multiple' => false,
                    'expanded' => false,
                    'choices' => array (
                        PMQRunQueue::EXECUTE_MODE_PLAN_ONCE => 'customParam.form.once',
                        PMQRunQueue::EXECUTE_MODE_PLAN_DAILY => 'customParam.form.daily',
                        PMQRunQueue::EXECUTE_MODE_PLAN_WEEKLY => 'customParam.form.weekly',
                        PMQRunQueue::EXECUTE_MODE_PLAN_MONTHLY => 'customParam.form.monthly'
                    )
                ))
                ->add('firstRunDt', 'date', array(
                    'label' => 'firstRunDt.form.label',
                    'required' => false,
                    'attr' => array(),
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => \IntlDateFormatter::SHORT
                ))
                ->add('lastRunDt', 'date', array(
                    'label' => 'lastRunDt.form.label',
                    'required' => false,
                    'attr' => array(),
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => \IntlDateFormatter::SHORT
                ))
                ->add('email', 'text', array(
                    'label' => 'email.form.label',
                    'required' => false,
                    'attr' => array()
                ))
                ->add('mode', 'hidden', array());
    }

    public function getDefaultOptions(array $options)
    {
        return array (
            'csrf_protection' => false,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'task_item',
        );
    }

    public function getName()
    {
        return self::CUSTOM_PARAM_TYPE_FORM_NAME;
    }

}