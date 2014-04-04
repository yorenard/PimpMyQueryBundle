<?php

namespace YoRenard\PimpMyQueryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use YoRenard\PimpMyQueryBundle\Entity\PMQParam;

class ParamType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array (
                    'label' => 'personalized_field.form.name.label'
                ))
                ->add('field_type', 'choice', array (
                    'label' => 'personalized_field.form.type.label',
                    'choices' => $this->getFieldTypeList(),
                    'required' => false,
                    'multiple' => false,
                    'expanded' => false,
                    'empty_value' => false,
                ))
                ->add('field_values', 'text', array (
                    'label' => 'personalized_field.form.value.label',
                    'required' => false
                ))
                ->add('code', 'hidden', array ());
    }

    public function getDefaultOptions(array $options)
    {
        return array (
            'data_class' => 'YoRenard\PimpMyQueryBundle\Entity\PMQParam',
            'csrf_protection' => false,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'task_item',
        );
    }

    public function getName()
    {
        return 'param_form';
    }

    public function getFieldTypeList()
    {
        return array(
            PMQParam::FIELD_TYPE_INT    => 'personalized_field.form.type.int',
            PMQParam::FIELD_TYPE_STRING => 'personalized_field.form.type.string',
            PMQParam::FIELD_TYPE_DATE   => 'personalized_field.form.type.date',
            PMQParam::FIELD_TYPE_SELECT => 'personalized_field.form.type.select',
            PMQParam::FIELD_TYPE_FILE   => 'personalized_field.form.type.file',
        );
    }

}