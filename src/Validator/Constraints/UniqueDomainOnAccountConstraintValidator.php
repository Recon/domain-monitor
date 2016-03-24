<?php

namespace Validator\Constraints;

use \Models\Domain;
use \Models\DomainQuery;
use \Propel\Runtime\ActiveQuery\Criteria;
use \Symfony\Component\Validator\Constraint;
use \Symfony\Component\Validator\ConstraintValidator;

class UniqueDomainOnAccountConstraintValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        /* @var $domain Domain */
        $domain = $this->context->getObject();

        if (!$domain)
            return;

        $hasDuplicate = (bool) DomainQuery::create()
                ->filterByUri($domain->getUri())
                ->filterByAccount($domain->getAccount())
                ->filterById($domain->getId(), Criteria::NOT_EQUAL)
                ->count();

        if ($hasDuplicate) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%domain%', $domain->getUri())
                ->addViolation();
        }
    }

}
