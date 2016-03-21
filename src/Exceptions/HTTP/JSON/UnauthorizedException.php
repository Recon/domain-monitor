<?php

namespace Exceptions\HTTP\JSON;

class UnauthorizedException extends \RuntimeException
{

    public function __construct($message = null, $code = 400)
    {
        $response = new \Symfony\Component\HttpFoundation\JsonResponse([
            'error' => true,
            'message' => $message ? : "You are not authorized to perform this action"
            ], $code);

        $response->send();

        exit;
    }

}
