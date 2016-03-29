<?php

namespace Authentication;

use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\User\UserChecker;

class AuthenticationManagerFactory
{

    /**
     * @param EncoderFactory $encoderFactory
     * @return AuthenticationProviderManager
     */
    public function create(EncoderFactory $encoderFactory)
    {

        $userProvider = new PropelUserProvider();
        $userChecker = new UserChecker();

        $providers = [
            new DaoAuthenticationProvider($userProvider, $userChecker, 'main', $encoderFactory, true),
        ];

        $authenticationManager = new AuthenticationProviderManager($providers, true);

        return $authenticationManager;
    }

}
