<?php

namespace Util\Tests;

use Models\Test;

abstract class AbstractCurlBatch
{

    /**
     * @var Resource
     */
    protected $multiHandle;

    /**
     * @var Resource[]
     */
    protected $elementHandles = [];

    /**
     * @var Test[]
     */
    protected $tests = [];

    public function __construct()
    {
        $this->multiHandle = curl_multi_init();
    }

    public function __destruct()
    {
        curl_multi_close($this->multiHandle);
    }

    public function addTest(Test $test)
    {
        $ch = curl_init();

        $this->configureCurlHandle($ch, $test);

        curl_multi_add_handle($this->multiHandle, $ch);

        $this->elementHandles[$test->getId()] = $ch;
        $this->tests[$test->getId()] = $test;
    }

    abstract protected function configureCurlHandle($ch, Test $test);

    /**
     * Performs CURL get requests on the defined tests and returns a result array
     *
     * @return CurlDomainResponse[]
     */
    public function execute()
    {
        $running = null;

        do {
            curl_multi_exec($this->multiHandle, $running);
        } while ($running > 0);

        $result = [];
        foreach ($this->elementHandles as $id => $ch) {
            $result[$id] = new CurlDomainResponse();
            $result[$id]->setContent(curl_multi_getcontent($ch));
            $result[$id]->setInfo(curl_getinfo($ch));
            $result[$id]->setTest($this->tests[$id]);

            curl_multi_remove_handle($this->multiHandle, $ch);
        }

        return $result;
    }

}
