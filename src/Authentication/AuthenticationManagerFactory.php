<?php

namespace Authentication;

use \Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use \Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use \Symfony\Component\Security\Core\Encoder\EncoderFactory;
use \Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;
use \Symfony\Component\Security\Core\User\InMemoryUserProvider;
use \Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Validator\Constraints\Length;

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

        $providers = array(
            new DaoAuthenticationProvider($userProvider, $userChecker, 'main', $encoderFactory, true),
        );

        $authenticationManager = new AuthenticationProviderManager($providers, true);

        return $authenticationManager;
    }

}
