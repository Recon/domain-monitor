<?php

namespace Events;

use Models\Test;
use Symfony\Component\EventDispatcher\Event;

class TestStatusChangeEvent extends Event
{

    const NAME_TEST_BECOMES_SUCCESFUL = 'domain.test.status_change.succeded';
    const NAME_TEST_BECOMES_FAILED = 'domain.test.status_change.failed';

    /**
     * @var Test
     */
    protected $test;

    /**
     * TestStatusChangeEvent constructor.
     * @param Test $test
     */
    public function __construct(Test $test)
    {
        $this->test = $test;
    }

    /**
     * @return Test
     */
    public function getTest()
    {
        return $this->test;
    }

}
