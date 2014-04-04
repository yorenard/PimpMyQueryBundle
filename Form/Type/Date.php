<?php

namespace YoRenard\PimpMyQueryBundle\Form\Type;

use YoRenard\PimpMyQueryBundle\Entity\PMQParam;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class Date extends AbstractType
{

    protected $param;

    public function __construct(PMQParam $param)
    {
        $this->param = $param;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add($this->param->getCode(), 'date', array(
            'label' => $this->param->getName(),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => \IntlDateFormatter::SHORT
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
        return 'generated_form_date_'.uniqid();
    }

}