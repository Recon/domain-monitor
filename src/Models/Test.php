<?php

namespace Models;

use Models\Base\Test as BaseTest;
use ReflectionClass;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Validator\Constraints\DomainTestConstraint;

/**
 * Skeleton subclass for representing a row from the 'test' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Test extends BaseTest
{

    const TYPE_HTTP = 1;
    const TYPE_HTTPS = 2;

    private static $testTypesMap = null;

    public function getTestName()
    {
        $key = array_search($this->getTestType(), self::getTestTypesMap());

        return $key ? str_replace('TYPE_', '', $key) : false;
    }

    public function setTestName($testName)
    {
        if (array_key_exists('TYPE_' . $testName, self::getTestTypesMap())) {
            $this->setTestType(self::getTestTypesMap()['TYPE_' . $testName]);
        }

        return $this;
    }

    public static function getTestTypesMap()
    {
        if (is_null(self::$testTypesMap)) {
            $reflection = new ReflectionClass(static::class);
            self::$testTypesMap = $reflection->getConstants();
        }

        return self::$testTypesMap;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addGetterConstraint('testName', new DomainTestConstraint());
    }

}
