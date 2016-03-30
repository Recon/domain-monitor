<?php

namespace Tests\Util\TestEvaluators;

use Util\TestEvaluators\HttpEvaluator;

class HttpEvaluatorTest extends AbstractHttpEvaluatorTest
{
    public function setUp()
    {
        parent::setUp();
        $this->class = HttpEvaluator::class;
    }
}
