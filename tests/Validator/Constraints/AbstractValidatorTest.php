<?php

namespace Tests\Validator\Constraints;

use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;
use Tests\TestCase;

abstract class AbstractValidatorTest extends TestCase
{

    public function getContextMock()
    {
        $violationBuilderMock = $this->getConstraintViolationBuilderMock();
        $contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();

        $contextMock->method('buildViolation')
            ->willReturn($violationBuilderMock);

        return $contextMock;
    }

    public function getConstraintViolationBuilderMock()
    {
        $violationBuilderMock = $this->getMockBuilder(ConstraintViolationBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $violationBuilderMock->method('setParameter')
            ->willReturn($violationBuilderMock);
        $violationBuilderMock->method('addViolation')
            ->willReturn($violationBuilderMock);

        return $violationBuilderMock;
    }

}
