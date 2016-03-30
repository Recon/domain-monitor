<?php

namespace Tests\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Tests\TestCase;
use Validator\Constraints\DomainTestConstraint;
use Validator\Constraints\ValidDomainConstraint;

class ValidDomainConstraintTest extends TestCase
{
    public function testConstraint()
    {
        $constraint = new ValidDomainConstraint();
        $class = $constraint->validatedBy();
        $this->assertNotEmpty($class);

        $validator = new $class;
        $this->assertInstanceOf(ConstraintValidator::class, $validator);
    }
}
