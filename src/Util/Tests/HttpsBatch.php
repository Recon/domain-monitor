<?php

namespace Util\Tests;

use \Models\Test;

class HttpsBatch extends AbstractCurlBatch
{

    const CAINFO_PATH = '../../Storage/cacert.pem';

    protected function configureCurlHandle($ch, Test $test)
    {
        curl_setopt($ch, CURLOPT_URL, 'https://' . $test->getDomain()->getUri());
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, realpath(__DIR__ . '/' . static::CAINFO_PATH));
    }

}
