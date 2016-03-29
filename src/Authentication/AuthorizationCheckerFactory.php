<?php

namespace Authentication;

use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter;

class AuthorizationCheckerFactory
{

    /**
     * @return AuthorizationChecker
     */
    public function create(AuthenticationProviderManager $authenticationManager, TokenStorage $tokenStorage)
    {
        $voters = [
            new RoleVoter('ROLE_'),
        ];

        $accessDecisionManager = new AccessDecisionManager(
            $voters, AccessDecisionManager::STRATEGY_AFFIRMATIVE, false, true
        );

        $authorizationChecker = new AuthorizationChecker(
            $tokenStorage, $authenticationManager, $accessDecisionManager, false
        );

        return $authorizationChecker;
    }

}
