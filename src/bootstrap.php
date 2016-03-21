<?php

use \Authentication\AuthenticationManagerFactory;
use \Authentication\AuthorizationCheckerFactory;
use \Authentication\TokenStorageFactory;
use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\DependencyInjection\Definition;
use \Symfony\Component\DependencyInjection\Reference;
use \Symfony\Component\HttpFoundation\Session\Session;
use \Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use \Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use \Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use \Symfony\Component\Security\Core\Encoder\EncoderFactory;
use \Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;
use \Symfony\Component\Templating\EngineInterface;
use \Symfony\Component\Validator\Validation;
use \Util\TemplatingFactory;
use \Util\ValidatorFactory;

//require 'authentication.php';
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/functions.php";
require __DIR__ . "/Config/Propel/config.php";

$container = new ContainerBuilder();


/**
 * Session
 */
$session = new Session();
$session->start();

$definition = new Definition();
$definition->setSynthetic(true);
$container->setDefinition('session', $definition);
$container->set('session', $session);


/**
 * Build authentication mechanism
 */
$definition = new Definition(TokenStorage::class);
$definition->setFactory([TokenStorageFactory::class, 'create']);
$container->setDefinition('auth.token_storage', $definition);

$definition = new Definition(EncoderFactory::class);
$definition->setSynthetic(true);
$container->setDefinition('auth.encoder', $definition);
$container->set('auth.encoder', new EncoderFactory(array(
    'Models\User' => new PlaintextPasswordEncoder(),
)));

$definition = new Definition(AuthenticationProviderManager::class, [
    new Reference('auth.encoder')
    ]);
$definition->setFactory([AuthenticationManagerFactory::class, 'create']);
$container->setDefinition('auth.manager', $definition);

$definition = new Definition(AuthorizationChecker::class, [
    new Reference('auth.manager'),
    new Reference('auth.token_storage'),
    ]);
$definition->setFactory([AuthorizationCheckerFactory::class, 'create']);
$container->setDefinition('auth.checker', $definition);

$definition = new Definition(\Util\UserPermissionChecker::class);
$definition->setSynthetic(true);
$container->setDefinition('auth.permission_checker', $definition);
$container->set('auth.permission_checker', new \Util\UserPermissionChecker($container->get('auth.checker'), $container->get('session')));



// Anonymous authentication
if (!$token = $session->get('auth_token')) {
    $token = new AnonymousToken('something', 'anonymous');
    $session->set('auth_token', $token);
}
$container->get('auth.token_storage')->setToken($token);


/**
 * Validator
 */
$definition = new Definition(Validation::class);
$definition->setFactory([ValidatorFactory::class, 'create']);
$container->setDefinition('validator', $definition);



/**
 * Templating
 */
$definition = new Definition(EngineInterface::class);
$definition->setFactory([TemplatingFactory::class, 'create']);
$container->setDefinition('template_engine', $definition);

