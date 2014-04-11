<?php

namespace YoRenard\PimpMyQueryBundle\Tests\Form\Extension\Validator\Constraints;

use YoRenard\PimpMyQueryBundle\Entity\PMQQuery;
use YoRenard\PimpMyQueryBundle\Entity\PMQRunQueue;
use YoRenard\PimpMyQueryBundle\Form\Extension\Validator\Constraints\FormValidator;
use YoRenard\PimpMyQueryBundle\Form\Extension\Validator\Constraints\Form;

class FormValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $formValidator;
    protected $contextMock;

    public function setUp()
    {
        parent::setUp();

        $this->formValidator = new FormValidator();
        $this->contextMock = $this->getMockBuilder('Symfony\Component\Validator\ExecutionContextInterface')->disableOriginalConstructor()->getMock();
    }

    public function validateDataProvider()
    {
        $constraint = new Form();

        return array(
            // invalid constraint
            array(
                array(
                    'generatedParam' => array(),
                    'mode' => PMQQuery::EXECUTE_MODE_EXECUTE,
                    'runExec' => PMQRunQueue::EXECUTE_MODE_PLAN_ONCE,
                    'firstRunDt' => '',
                    'lastRunDt' => '',
                    'email' => '',
                ),
                new \Symfony\Component\Security\Core\Validator\Constraints\UserPassword(),
                true
            ),
            // Valid
            array(
                array(
                    'generatedParam' => array(),
                    'mode' => PMQQuery::EXECUTE_MODE_EXECUTE,
                    'runExec' => PMQRunQueue::EXECUTE_MODE_PLAN_ONCE,
                    'firstRunDt' => '',
                    'lastRunDt' => '',
                    'email' => '',
                ),
                $constraint,
                true
            ),
            // Valid with generatedParam
            array(
                array(
                    'generatedParam' => array(
                        'date' => '05/03/2013'
                    ),
                    'mode' => PMQQuery::EXECUTE_MODE_EXECUTE,
                    'runExec' => PMQRunQueue::EXECUTE_MODE_PLAN_ONCE,
                    'firstRunDt' => '',
                    'lastRunDt' => '',
                    'email' => '',
                ),
                $constraint,
                true
            ),
            // empty generatedParam
            array(
                array(
                    'generatedParam' => array(
                        'date' => ''
                    ),
                    'mode' => PMQQuery::EXECUTE_MODE_EXECUTE,
                    'runExec' => PMQRunQueue::EXECUTE_MODE_PLAN_ONCE,
                    'firstRunDt' => '',
                    'lastRunDt' => '',
                    'email' => '',
                ),
                $constraint,
                false
            ),
            // invalid executeMode
            array(
                array(
                    'generatedParam' => array(),
                    'mode' => '',
                    'runExec' => PMQRunQueue::EXECUTE_MODE_PLAN_ONCE,
                    'firstRunDt' => '',
                    'lastRunDt' => '',
                    'email' => '',
                ),
                $constraint,
                false
            ),
            // invalid executePlan
            array(
                array(
                    'generatedParam' => array(),
                    'mode' => PMQQuery::EXECUTE_MODE_EXECUTE,
                    'runExec' => '',
                    'firstRunDt' => '',
                    'lastRunDt' => '',
                    'email' => '',
                ),
                $constraint,
                false
            ),
            // empty firstRunDt on Plan execution mode
            array(
                array(
                    'generatedParam' => array(),
                    'mode' => PMQQuery::EXECUTE_MODE_PLAN,
                    'runExec' => PMQRunQueue::EXECUTE_MODE_PLAN_ONCE,
                    'firstRunDt' => '',
                    'lastRunDt' => '',
                    'email' => 'mail@mail.com',
                ),
                $constraint,
                false
            ),
            // empty mail on Plan execution mode
            array(
                array(
                    'generatedParam' => array(),
                    'mode' => PMQQuery::EXECUTE_MODE_PLAN,
                    'runExec' => PMQRunQueue::EXECUTE_MODE_PLAN_ONCE,
                    'firstRunDt' => '05/03/2013',
                    'lastRunDt' => '',
                    'email' => '',
                ),
                $constraint,
                false
            ),
            // empty lastRunDt on Plan execution mode
            array(
                array(
                    'generatedParam' => array(),
                    'mode' => PMQQuery::EXECUTE_MODE_PLAN,
                    'runExec' => PMQRunQueue::EXECUTE_MODE_PLAN_DAILY,
                    'firstRunDt' => '05/03/2013',
                    'lastRunDt' => '',
                    'email' => 'mail@mail.com',
                ),
                $constraint,
                false
            ),
        );
    }

    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate($formData, $constraint, $isValid)
    {
        $this->formValidator->initialize($this->contextMock);

        if (!$constraint instanceof Form) {
            $this->setExpectedException('\Symfony\Component\Form\Exception\UnexpectedTypeException');
        }

        if (true !== $isValid) {
            $this->contextMock->expects($this->any())->method('addViolation')->with($constraint->emptyRequiredFieldMessage);
        }

        $this->formValidator->validate($formData, $constraint);
    }
}
