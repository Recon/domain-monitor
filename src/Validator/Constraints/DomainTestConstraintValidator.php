<?php

namespace Validator\Constraints;

use Models\Test;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DomainTestConstraintValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {

        if (!array_key_exists('TYPE_' . $value, Test::getTestTypesMap())) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }
    }

}
