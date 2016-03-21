<?php

use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\UserChecker;

require "vendor/autoload.php";
/**
 *  Part 1: Authentication part
 */
// Define users which we can authenticate against, together with "roles".
$userProvider = new \Symfony\Component\Security\Core\User\InMemoryUserProvider(
    array(
    'john' => array(
        'password' => 'password',
        'roles' => array('ROLE_USER'),
    ),
    'admin' => array(
        'password' => 'secret',
        'roles' => array('ROLE_ADMIN'),
    ),
    )
);
// Create an encoder factory that will "encode" passwords
$encoderFactory = new \Symfony\Component\Security\Core\Encoder\EncoderFactory(array(
    // We simply use plaintext passwords for users from this specific class
    'Symfony\Component\Security\Core\User\User' => new PlaintextPasswordEncoder(),
    ));
// The user checker is a simple class that allows to check against different elements (user disabled, account expired etc)
$userChecker = new UserChecker();
// The (authentication) providers are a way to make sure to match credentials against users based on their "providerkey".
$providers = array(
    new DaoAuthenticationProvider($userProvider, $userChecker, 'main', $encoderFactory, true),
);
$authenticationManager = new AuthenticationProviderManager($providers, true);



/**
 *  Part 2: Tokens
 */
// We store our (authenticated) token inside the token storage
$tokenStorage = new TokenStorage();
/**
 *  Part 3: Authorization
 */
// We only create a single voter that checks on given token roles.
$voters = array(
    new \Symfony\Component\Security\Core\Authorization\Voter\RoleVoter('ROLE_'),
);
// Tie all voters into the access decision manager (
$accessDecisionManager = new AccessDecisionManager(
    $voters, AccessDecisionManager::STRATEGY_AFFIRMATIVE, false, true
);
/**
 *  Part 4: Tie authorization & authentication & token storage together for easy use
 */
$authorizationChecker = new AuthorizationChecker(
    $tokenStorage, $authenticationManager, $accessDecisionManager, false
);
/**
 *  Part 5: Authenticate a user based on supplied credentials
 */
try {
    $username = $argv[1];
    $password = $argv[2];
    // Create "unauthenticated" token and authenticate it
    $token = new UsernamePasswordToken($username, $password, 'main', array());
    $token = $authenticationManager->authenticate($token);
    // Store "authenticated" token in the token storage
    $tokenStorage->setToken($token);
} catch (AuthenticationException $e) {
    print $e->getMessage();
    exit(1);
}
/**
 *  Part 6: Check if the given user (token) has ROLE_ADMIN permissions
 */
if ($authorizationChecker->isGranted('ROLE_ADMIN')) {
    print "This user has admin rights.\n";
} else {
    print "Access denied\n";
}