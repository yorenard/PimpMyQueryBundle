<?php

namespace YoRenard\PimpMyQueryBundle\Form\Type;

use YoRenard\PimpMyQueryBundle\Entity\PMQParam;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class String extends AbstractType
{

    protected $param;

    public function __construct(PMQParam $param)
    {
        $this->param = $param;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add($this->param->getCode(), 'text', array(
            'label' => $this->param->getName()
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
        return 'generated_form_string_'.uniqid();
    }

}