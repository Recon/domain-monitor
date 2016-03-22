<?php

namespace Exceptions\HTTP\JSON;

class InvalidResetToken extends \RuntimeException
{

    public function __construct($message = null, $code = 400)
    {
        $response = new \Symfony\Component\HttpFoundation\JsonResponse([
            'error' => true,
            'message' => $message ? : "The reset token being used is invalid"
            ], $code);

        $response->send();

        exit;
    }

}
