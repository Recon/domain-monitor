<?php

namespace Tests\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Validator\Constraints\UniqueDomainOnAccountConstraint;
use Tests\TestCase;

class UniqueDomainOnAccountConstraintTest extends TestCase
{
    public function testConstraint()
    {
        $constraint = new UniqueDomainOnAccountConstraint();
        $class = $constraint->validatedBy();
        $this->assertNotEmpty($class);

        $validator = new $class;
        $this->assertInstanceOf(ConstraintValidator::class, $validator);
    }
}
