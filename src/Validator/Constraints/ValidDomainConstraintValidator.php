<?php

namespace Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidDomainConstraintValidator extends ConstraintValidator
{

    public function validate($domain, Constraint $constraint)
    {
        if (!filter_var(gethostbyname($domain), FILTER_VALIDATE_IP)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%domain%', $domain)
                ->addViolation();
        }
    }

}
