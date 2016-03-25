<?php

namespace Events;

use \Symfony\Component\EventDispatcher\Event;

class TestExecutionEvent extends Event
{

    const NAME_TEST_SUCCEDED = 'domain.test.succeded';
    const NAME_TEST_FAILED = 'domain.test.failed';

    /**
     * @var \Models\Test
     */
    private $test;

    public function __construct(\Models\Test $test)
    {
        $this->test = $test;
    }

    function getTest()
    {
        return $this->test;
    }

}
