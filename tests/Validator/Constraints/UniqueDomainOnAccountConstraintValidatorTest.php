<?php

namespace Tests\Validator\Constraints;

use Models\Domain;
use Validator\Constraints\UniqueDomainOnAccountConstraint;
use Validator\Constraints\UniqueDomainOnAccountConstraintValidator;

class UniqueDomainOnAccountConstraintValidatorTest extends AbstractValidatorTest
{

    public function testValidatorWithValidValues()
    {
        $contextMock = $this->getContextMock();
        $contextMock->expects($this->once())
            ->method('getObject')
            ->willReturn(new Domain());
        $contextMock->expects($this->never())
            ->method('buildViolation');

        $validator = $this->getMockBuilder(UniqueDomainOnAccountConstraintValidator::class)
            ->setMethods(['exists'])
            ->getMock();

        $validator->expects($this->once())
            ->method('exists')
            ->willReturn(false);

        $validator->initialize($contextMock);

        $validator->validate(null, new UniqueDomainOnAccountConstraint());
    }

    public function testValidatorWithInvalidValues()
    {
        $contextMock = $this->getContextMock();
        $contextMock->expects($this->once())
            ->method('getObject')
            ->willReturn(new Domain());
        $contextMock->expects($this->once())
            ->method('buildViolation');

        $validator = $this->getMockBuilder(UniqueDomainOnAccountConstraintValidator::class)
            ->setMethods(['exists'])
            ->getMock();

        $validator->expects($this->once())
            ->method('exists')
            ->willReturn(true);

        $validator->initialize($contextMock);

        $validator->validate(null, new UniqueDomainOnAccountConstraint());
    }

}
