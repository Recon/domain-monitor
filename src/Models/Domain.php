<?php

namespace Models;

use \Models\Base\Domain as BaseDomain;
use  \Symfony\Component\Validator\Constraints As Assert;
use \Symfony\Component\Validator\Mapping\ClassMetadata;
use \Validator\Constraints\UniqueDomainOnAccountConstraint;
use \Validator\Constraints\ValidDomainConstraint;

/**
 * Skeleton subclass for representing a row from the 'domain' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Domain extends BaseDomain
{

    const STATUS_UNKNOWN = 0; // Used on freshly created records
    const STATUS_FAIL_ALL = 1;  // Indicates that all the tests for a domain failed
    const STATUS_FAIL_PARTIAL = 2;  // Indicates that only some of the tests for a domain failed
    const STATUS_OK = 3;  // Indicates that all the tests succeded

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addGetterConstraint('uri', new Assert\Regex([
            'pattern' => '#^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$#',
            'message' => 'The URI provided doesn\'t seem to be a valid domain name'
        ]));

        $metadata->addGetterConstraint('uri', new Assert\NotBlank([
            'message' => 'The domain cannot be blank'
        ]));

        $metadata->addGetterConstraint('uri', new UniqueDomainOnAccountConstraint());
        $metadata->addGetterConstraint('uri', new ValidDomainConstraint());
    }

}
