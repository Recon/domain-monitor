<?php

namespace Util\Tests;

use \Models\Domain;
use \Models\Test;

class HttpBatch extends AbstractCurlBatch
{

    protected function configureCurlHandle($ch, Test $test)
    {
        curl_setopt($ch, CURLOPT_URL, $test->getDomain()->getUri());
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    }

}
