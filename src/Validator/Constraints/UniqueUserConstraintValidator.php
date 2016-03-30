<?php

namespace Validator\Constraints;

use Models\UserQuery;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserConstraintValidator extends ConstraintValidator
{

    public function validate($email, Constraint $constraint)
    {
        if (!$user = $this->getUserByEmail($email)) {
            return;
        }

        if ($user->getId() != $this->context->getObject()->getId()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%email%', $email)
                ->addViolation();
        }
    }

    /**
     * @param  string $email
     * @return \Models\User
     */
    protected function getUserByEmail($email)
    {
        return UserQuery::create()->findOneByEmail($email);
    }

}
