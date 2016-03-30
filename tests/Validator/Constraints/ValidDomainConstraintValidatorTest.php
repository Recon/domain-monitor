<?php

namespace Tests\Validator\Constraints;

use Validator\Constraints\ValidDomainConstraint;
use Validator\Constraints\ValidDomainConstraintValidator;

class ValidDomainConstraintValidatorTest extends AbstractValidatorTest
{

    public function testValidatorWithValidValues()
    {
        $contextMock = $this->getContextMock();
        $contextMock->expects($this->never())
            ->method('buildViolation');

        $validator = new ValidDomainConstraintValidator();
        $validator->initialize($contextMock);

        $validator->validate('google.com', new ValidDomainConstraint());
    }

    /**
     * @ski
     */
    public function testValidatorWithInvalidValues()
    {
        $this->markTestSkipped("Will cause a slight delay");
        $contextMock = $this->getContextMock();
        $contextMock->expects($this->once())
            ->method('buildViolation');

        $validator = new ValidDomainConstraintValidator();
        $validator->initialize($contextMock);

        $validator->validate('x', new ValidDomainConstraint());
    }


}
