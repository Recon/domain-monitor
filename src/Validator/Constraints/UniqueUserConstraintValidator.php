<?php

namespace Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserConstraintValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        $user = \Models\UserQuery::create()->findOneByEmail($value);

        if (!$user) {
            return;
        }

        if ($user->getId() != $this->context->getObject()->getId()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%email%', $value)
                ->addViolation();
        }
    }

}
