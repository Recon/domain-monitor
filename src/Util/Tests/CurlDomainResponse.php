<?php

namespace Util\Tests;

use \Models\Domain;

class CurlDomainResponse
{

    /**
     * The HTML body of the response
     *
     * @var string
     */
    protected $content;

    /**
     * Info array returned by curl_getinfo()
     *
     * @var array
     */
    protected $info = [];

    /**
     * @var \Models\Test
     */
    protected $test;

    function getContent()
    {
        return $this->content;
    }

    function getInfo()
    {
        return $this->info;
    }

    function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    function setInfo($info)
    {
        $this->info = $info;
        return $this;
    }

    function getTest()
    {
        return $this->test;
    }

    function setTest(\Models\Test $test)
    {
        $this->test = $test;
        return $this;
    }

}
