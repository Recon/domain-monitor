<?php

namespace Tests\Util\TestEvaluators;

use Util\TestEvaluators\HttpsEvaluator;

class HttpsEvaluatorTest extends AbstractHttpEvaluatorTest
{
    public function setUp()
    {
        parent::setUp();
        $this->class = HttpsEvaluator::class;
    }
}
