<?php

namespace YoRenard\PimpMyQueryBundle\Form\Type;

use YoRenard\PimpMyQueryBundle\Entity\PMQParam;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File as ConstraintsFile;
use Symfony\Component\Validator\Constraints\NotBlank;

class File extends AbstractType
{
    public static $authorizedMaxSize = '5M';
    public static $authorizedMimeTypes = array('text/csv', 'text/plain');

    protected $param;

    public function __construct(PMQParam $param)
    {
        $this->param = $param;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add($this->param->getCode(), 'file', array(
                'label' => $this->param->getName(),
                'required' => true,
                'constraints' => array(
                    new ConstraintsFile(array(
                        'maxSize' => self::$authorizedMaxSize,
                        'mimeTypes' => self::$authorizedMimeTypes
                    )),
                    new NotBlank()
                )
            ))
        ;
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
        return 'generated_form_file_'.uniqid();
    }
}