<?php

namespace Tests\Validator\Constraints;

use Models\User;
use Validator\Constraints\UniqueUserConstraint;
use Validator\Constraints\UniqueUserConstraintValidator;

class UniqueUserConstraintValidatorTest extends AbstractValidatorTest
{

    public function testValidatorWithValidValues()
    {

        $user = (new User())->setId(1);

        $contextMock = $this->getContextMock();
        $contextMock->expects($this->once())
            ->method('getObject')
            ->willReturn($user);
        $contextMock->expects($this->never())
            ->method('buildViolation');

        $validator = $this->getMockBuilder(UniqueUserConstraintValidator::class)
            ->setMethods(['getUserByEmail'])
            ->getMock();
        $validator->expects($this->once())
            ->method('getUserByEmail')
            ->willReturn($user);

        $validator->initialize($contextMock);

        $validator->validate(null, new UniqueUserConstraint());
    }

    public function testValidatorWithInvalidValues()
    {
        $contextMock = $this->getContextMock();
        $contextMock->expects($this->once())
            ->method('getObject')
            ->willReturn((new User())->setId(1));
        $contextMock->expects($this->once())
            ->method('buildViolation');

        $validator = $this->getMockBuilder(UniqueUserConstraintValidator::class)
            ->setMethods(['getUserByEmail'])
            ->getMock();
        $validator->expects($this->once())
            ->method('getUserByEmail')
            ->willReturn((new User())->setId(2));

        $validator->initialize($contextMock);

        $validator->validate(null, new UniqueUserConstraint());
    }

}
