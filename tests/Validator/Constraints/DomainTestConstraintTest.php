<?php

namespace Tests\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Tests\TestCase;
use Validator\Constraints\DomainTestConstraint;

class DomainTestConstraintTest extends TestCase
{
    public function testConstraint()
    {
        $constraint = new DomainTestConstraint();
        $class = $constraint->validatedBy();
        $this->assertNotEmpty($class);

        $validator = new $class;
        $this->assertInstanceOf(ConstraintValidator::class, $validator);
    }
}
