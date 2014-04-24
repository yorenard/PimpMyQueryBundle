<?php

namespace YoRenard\PimpMyQueryBundle\Form;

use YoRenard\PimpMyQueryBundle\Entity\PMQQuery;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;

class QueryType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array (
                'label' => 'name.form.label'
            ))
            ->add('connection', 'choice', array (
                'label' => 'connection.form.label',
                'choices' => array(
                        PMQQuery::CONNECTION_STAT => 'connection.stat.form.label',
                        PMQQuery::CONNECTION_FENIX => 'connection.fenix.form.label'
                        ),
                'required' => true
            ))
            ->add('desc', 'text', array (
                'label' => 'desc.form.label'
            ))
            ->add('query', 'textarea', array (
                'label' => 'query.form.label',
                'trim' => true,
                'required' => false,
                'constraints' => new NotNull()
            ))
            ->add('public', 'checkbox', array(
                'label' => 'status.form.label',
                'required' => false
            ))
//            ->add('params', 'collection', array(
//                'type' => new ParamType(),
//                'required' => false,
//                'allow_add' => true,
//                'allow_delete' => true,
//            ))
//            ->add('rights', 'collection', array(
//                'type' => new RightType(),
//                'required' => false,
//                'allow_add' => true,
//                'allow_delete' => true,
//            ))
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array (
            'data_class' => 'YoRenard\PimpMyQueryBundle\Entity\PMQQuery',
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