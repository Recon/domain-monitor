<?php

namespace Tests\Util\TestEvaluators;

use Models\Test;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Tests\TestCase;
use Util\Tests\CurlDomainResponse;

abstract class AbstractHttpEvaluatorTest extends TestCase
{

    protected $class;

    /**
     * @dataProvider getData
     * @param int|null $code
     * @param string   $method
     */
    public function testRenderer($code, $method)
    {
        $dispatcher = new EventDispatcher();
        $response = new CurlDomainResponse();
        $response->setInfo([
            'http_code' => $code,
        ]);
        $response->setTest(new Test());

        $evaluator = $this->getMockBuilder($this->class)
            ->setConstructorArgs([$dispatcher])
            ->setMethods([$method])
            ->getMock();
        $evaluator->expects($this->once())
            ->method($method);

        $evaluator->evaluate($response);
    }

    public function getData()
    {
        return [
            ['200', 'onTestSucceded'],
            ['500', 'onTestFailed'],
            [null, 'onTestFailed'],
        ];
    }
}
