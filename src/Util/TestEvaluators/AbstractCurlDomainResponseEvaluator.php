<?php

namespace Util\TestEvaluators;

use \Events\TestExecutionEvent;
use \Models\Test;
use \Symfony\Component\EventDispatcher\EventDispatcher;
use \Util\Tests\CurlDomainResponse;

/**
 * This class evaluates CURL responses and triggers the corresponding events
 */
class AbstractCurlDomainResponseEvaluator
{

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {

        $this->dispatcher = $dispatcher;
    }

    public static function factory(EventDispatcher $dispatcher)
    {
        return new HttpEvaluator($dispatcher);
    }

    public function evaluate(CurlDomainResponse $response)
    {
        $info = (array) $response->getInfo();

        if (empty($info['http_code'])) {
            return $this->onTestFailed($response->getTest());
        }

        if ($info['http_code'] >= 400) {
            return $this->onTestFailed($response->getTest());
        }

        return $this->onTestSucceded($response->getTest());
    }

    protected function onTestSucceded(Test $test)
    {
        $this->dispatcher->dispatch(TestExecutionEvent::NAME_TEST_SUCCEDED, new TestExecutionEvent($test));

        return true;
    }

    protected function onTestFailed(Test $test)
    {
        $this->dispatcher->dispatch(TestExecutionEvent::NAME_TEST_FAILED, new TestExecutionEvent($test));

        return false;
    }

}
