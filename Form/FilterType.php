<?php

namespace YoRenard\PimpMyQueryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array (
                    'required' => false,
                    'attr' => array('placeholder' => 'filter.form.placeholder')
                ));
    }

    public function getDefaultOptions(array $options)
    {
        return array (
            'data_class' => 'LaFourchette\BiBundle\Entity\Filter',
            'csrf_protection' => false,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'task_item',
        );
    }

    public function getName()
    {
        return 'query_form';
    }
}
