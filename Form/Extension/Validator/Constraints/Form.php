<?php

namespace YoRenard\PimpMyQueryBundle\Form\Extension\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Form extends Constraint
{
    public $emptyRequiredFieldMessage = 'The field is required.';
    public $message = 'The form is invalid.';
//    public $message = 'The form is invalid.';
    public $service = 'yorenard_pimp_my_query.form.constraints.validator.form';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return $this->service;
    }
}
