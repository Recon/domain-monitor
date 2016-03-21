<?php

namespace Controllers;

use \Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use \Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticationController extends AbstractController
{

    public function login()
    {
        $username = $this->request->get('username');
        $password = $this->request->get('password');

        try {
            // Create "unauthenticated" token and authenticate it
            $token = new UsernamePasswordToken($username, $password, 'main', []);
            $token = $this->container->get('auth.manager')->authenticate($token);

            // Store "authenticated" token in the token storage
            $this->container->get('auth.token_storage')->setToken($token);

            $this->session->set('auth_token', $token);

            return new JsonResponse([
                'success' => true
            ]);
        } catch (AuthenticationException $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
                ], 400);
        }
    }

}
