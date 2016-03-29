<?php

namespace Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class UniqueDomainOnAccountConstraint extends Constraint
{

    public $message = 'The "%domain%" is already being monitored in this account';

    public function validatedBy()
    {
        return UniqueDomainOnAccountConstraintValidator::class;
    }

}
