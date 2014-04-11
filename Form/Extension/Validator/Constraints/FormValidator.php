<?php

namespace YoRenard\PimpMyQueryBundle\Form\Extension\Validator\Constraints;

use YoRenard\PimpMyQueryBundle\Entity\PMQQuery;
use YoRenard\PimpMyQueryBundle\Entity\PMQRunQueue;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class FormValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($form, Constraint $constraint)
    {
        if (!$constraint instanceof Form) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Form');
        }

        // Empty parameter
        foreach($form['generatedParam'] as $generatedParam) {
            if($generatedParam == '') {
                $this->context->addViolation($constraint->emptyRequiredFieldMessage);
            }
        }

        if(!in_array($form['mode'], PMQQuery::getExecuteModeList()) || !in_array($form['runExec'], PMQRunQueue::getExecutePlanList())) {
            $this->context->addViolation($constraint->emptyRequiredFieldMessage);
        }

        if(!in_array($form['mode'], array(PMQQuery::EXECUTE_MODE_EXECUTE, PMQQuery::EXECUTE_MODE_EXPORT)) && (empty($form['email']) || empty($form['firstRunDt']))) {
            $this->context->addViolation($constraint->emptyRequiredFieldMessage);
        }

        if($form['runExec'] != PMQRunQueue::EXECUTE_MODE_PLAN_ONCE && empty($form['lastRunDt'])) {
            $this->context->addViolation($constraint->emptyRequiredFieldMessage);
        }
    }
}
