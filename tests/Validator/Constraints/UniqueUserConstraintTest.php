<?php

namespace Tests\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Tests\TestCase;
use Validator\Constraints\UniqueUserConstraint;

class UniqueUserConstraintTest extends TestCase
{
    public function testConstraint()
    {
        $constraint = new UniqueUserConstraint();
        $class = $constraint->validatedBy();
        $this->assertNotEmpty($class);

        $validator = new $class;
        $this->assertInstanceOf(ConstraintValidator::class, $validator);
    }
}
