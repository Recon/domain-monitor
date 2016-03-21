<?php

namespace Exceptions\HTTP\JSON;

class ResourceNotFoundException extends \RuntimeException
{

    public function __construct($message = null, $code = 404)
    {
        $response = new \Symfony\Component\HttpFoundation\JsonResponse([
            'error' => true,
            'message' => $message ? : "This resource was not found"
            ], $code);

        $response->send();

        exit;
    }

}
