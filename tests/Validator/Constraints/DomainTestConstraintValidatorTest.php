<?php

namespace Tests\Validator\Constraints;

use Validator\Constraints\DomainTestConstraint;
use Validator\Constraints\DomainTestConstraintValidator;

class DomainTestConstraintValidatorTest extends AbstractValidatorTest
{
    /**
     * @dataProvider getValidValues()
     */
    public function testValidatorWithValidValues($type)
    {
        $contextMock = $this->getContextMock();

        $contextMock->expects($this->never())
            ->method('buildViolation');


        $validator = new DomainTestConstraintValidator();
        $validator->initialize($contextMock);

        $validator->validate($type, new DomainTestConstraint());
    }

    public function getValidValues()
    {
        return [
            ['HTTP'],
            ['HTTPS'],
        ];
    }

    public function testValidatorWithInvalidValues()
    {
        $contextMock = $this->getContextMock();

        $contextMock->expects($this->once())
            ->method('buildViolation')
            ->willReturn($this->getConstraintViolationBuilderMock());

        $validator = new DomainTestConstraintValidator();
        $validator->initialize($contextMock);

        $validator->validate("123456", new DomainTestConstraint());
    }

}
