<?php

namespace Exceptions\HTTP;

use Symfony\Component\HttpFoundation\Response;

class Error404 extends \RuntimeException
{

    public function __construct($message = null, $code = 404)
    {
        $response = new Response($message ?: 'Page not found', $code);
        $response->send();

        exit;
    }

}
