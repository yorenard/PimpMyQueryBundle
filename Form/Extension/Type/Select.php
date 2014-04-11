<?php

namespace YoRenard\PimpMyQueryBundle\Form\Type;

use YoRenard\PimpMyQueryBundle\Entity\PMQParam;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class Select extends AbstractType
{

    protected $param;

    public function __construct(PMQParam $param)
    {
        $this->param = $param;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add($this->param->getCode(), 'choice', array (
            'label' => $this->param->getName(),
            'choices' => $this->getChoicesList($this->param->getFieldValues()),
            'empty_value' => 'select.form.type.default',
        ));
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
        return 'generated_form_select_'.uniqid();
    }

    function trim_value(&$value)
    {
        $value = trim($value);
    }

    public function getChoicesList($fieldValues)
    {
        $choiceList = array();
        foreach(explode(';', $fieldValues) as $value) {
            $key = $value;
            if (strpos($value, ':') !== false) {
                list($key, $value) = explode(':', $value);
            }

            $choiceList[$key] = $value;
        }

        return /*array_walk($choiceList, 'trim_value')*/$choiceList;
    }

}