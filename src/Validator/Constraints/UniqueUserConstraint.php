<?php

namespace Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class UniqueUserConstraint extends Constraint
{

    public $message = 'The "%email%" email address is already being used';

    public function validatedBy()
    {
        return UniqueUserConstraintValidator::class;
    }

}
