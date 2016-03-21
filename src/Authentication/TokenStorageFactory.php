<?php

namespace Authentication;

use \Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class TokenStorageFactory
{

    /**
     * @return AuthenticationProviderManager
     */
    public function create()
    {
        $tokenStorage = new TokenStorage();

        return $tokenStorage;
    }

}
