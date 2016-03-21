<?php

namespace Validator\Constraints;

use \Symfony\Component\Validator\Constraint;

class DomainTestConstraint extends Constraint
{

    public $message = 'The "%string%" test is not a valid test';

    public function validatedBy()
    {
        return DomainTestConstraintValidator::class;
    }

}
