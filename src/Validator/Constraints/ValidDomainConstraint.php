<?php

namespace Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ValidDomainConstraint extends Constraint
{

    public $message = 'The "%domain%" does not appear to be valid or accessible';

    public function validatedBy()
    {
        return ValidDomainConstraintValidator::class;
    }

}
