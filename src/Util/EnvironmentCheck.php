<?php


namespace Util;


use ArrayIterator;
use IteratorAggregate;

class EnvironmentCheck implements IteratorAggregate
{

    /**
     * @var array
     */
    protected $checks = [];

    public function __construct()
    {
        $this->checkPhpVersion();
        $this->checkCurl();
        $this->checkPdo();
    }


    protected function checkCurl()
    {
        $this->checks['curl'] = [
            'message'  => sprintf("CURL enabled", phpversion()),
            'passes'   => extension_loaded('curl') && function_exists('curl_init'),
            'required' => true,
        ];
    }

    protected function checkPdo()
    {
        $this->checks['pdo'] = [
            'message'  => sprintf("PDO enabled", phpversion()),
            'passes'   => extension_loaded('pdo') && class_exists('\PDO'),
            'required' => true,
        ];
    }

    protected function checkPhpVersion()
    {
        $this->checks['php_version'] = [
            'message'  => sprintf("At least PHP 5.5 required (%s present)", phpversion()),
            'passes'   => version_compare(phpversion(), '5.5.0', '>='),
            'required' => true,
        ];
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->checks);
    }

    public function hasFailures()
    {
        foreach ($this->checks as $check) {
            if (!$check['passes']) {
                return true;
            }
        }

        return false;
    }
}
