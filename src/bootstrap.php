<?php

use Authentication\AuthenticationManagerFactory;
use Authentication\AuthorizationCheckerFactory;
use Authentication\TokenStorageFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Validator\Validation;
use Util\Mailer;
use Util\TemplatingFactory;
use Util\UserPermissionChecker;
use Util\ValidatorFactory;


define('APP_VERSION', '0.1.0');
define('APP_DIR', realpath(__DIR__ . '/..'));

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/functions.php";

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


/**
 * Event system
 */
$definition = new Definition(EventDispatcher::class);
$dispatcher = new EventDispatcher();
$definition->setSynthetic(true);
$container->setDefinition('dispatcher', $definition);
$container->set('event_dispatcher', $dispatcher);


/**
 * Config
 */
$container->register('config_writer', \Util\Config\Install\ConfigFileWriter::class)
    ->addArgument(realpath(__DIR__ . '/../config'));
$container->register('config_loader', \Util\Config\ConfigLoader::class)
    ->addArgument(realpath(__DIR__ . '/../config'));
$container->register('propel_connection_config_factory', \Util\PropelConnectionConfigFactory::class)
    ->addArgument($container->get('config_loader'));

$dispatcher->addSubscriber(new \Subscribers\ConfigLoad\PropelConfiguratorSubscriber(
    $container->get('propel_connection_config_factory')
));
$dispatcher->addSubscriber(new \Subscribers\Versioning\VersionChangeSubscriber(
    $container->get('propel_connection_config_factory')
));


/**
 * Load configuration
 */
try {
    $container->get('config_loader')->load();
    $container->get('event_dispatcher')->dispatch(
        \Events\ConfigLoadEvent::NAME,
        new \Events\ConfigLoadEvent($container->get('config_loader'))
    );
} catch (\Exceptions\MissingConfigurationFileException $ex) {

}

/**
 * Mailer
 */
$container->register('mailer_factory', \Util\MailerFactory::class)
    ->addArgument($container->get('template_engine'))
    ->addArgument($container->get('config_loader'));

$definition = new Definition(Mailer::class, [new Reference('template_engine')]);
$definition->setFactory([\Util\MailerFactory::class, 'factory']);
$container->setDefinition('mailer', $definition);

$container->register('mailer.test_change_notificaion_renderer', \Util\UserTestNotifications\MailRenderer::class)
    ->addArgument($container->get('template_engine'));


/**
 * Test session time provider
 */
$definition = new Definition(Util\TestSessionTimeProvider::class);
$definition->setFactory([Util\TestSessionTimeProvider::class, 'instance']);
$container->setDefinition('test_session_time_provider', $definition);


/**
 * Configure Event System Subscribers
 */
$dispatcher->addSubscriber(new Subscribers\DomainTestExecutionSubscriber($dispatcher));
$dispatcher->addSubscriber(new Subscribers\TestStatusChangeSubscriber($container->get('test_session_time_provider')));
$dispatcher->addSubscriber(new Subscribers\StatusChangeNotificationEmailsSubscriber(
        $container->get('test_session_time_provider'),
        $container->get('mailer_factory'),
        $container->get('mailer.test_change_notificaion_renderer'))
);


/**
 * Build authentication mechanism
 */
$definition = new Definition(TokenStorage::class);
$definition->setFactory([TokenStorageFactory::class, 'create']);
$container->setDefinition('auth.token_storage', $definition);

$definition = new Definition(EncoderFactory::class);
$definition->setSynthetic(true);
$container->setDefinition('auth.encoder', $definition);
$container->set('auth.encoder', new EncoderFactory([
    'Models\User' => new PlaintextPasswordEncoder(),
]));

$definition = new Definition(AuthenticationProviderManager::class, [
    new Reference('auth.encoder'),
]);
$definition->setFactory([AuthenticationManagerFactory::class, 'create']);
$container->setDefinition('auth.manager', $definition);

$definition = new Definition(AuthorizationChecker::class, [
    new Reference('auth.manager'),
    new Reference('auth.token_storage'),
]);
$definition->setFactory([AuthorizationCheckerFactory::class, 'create']);
$container->setDefinition('auth.checker', $definition);

$definition = new Definition(UserPermissionChecker::class);
$definition->setSynthetic(true);
$container->setDefinition('auth.permission_checker', $definition);
$container->set('auth.permission_checker',
    new UserPermissionChecker($container->get('auth.checker'), $container->get('session')));

// Anonymous authentication
if (!$token = $session->get('auth_token')) {
    $token = new AnonymousToken('something', 'anonymous');
    $session->set('auth_token', $token);
}
$container->get('auth.token_storage')->setToken($token);


/**
 * Tester/probes
 */
$definition = new Definition(Util\TestEvaluators\HttpEvaluator::class, [
    new Reference('event_dispatcher'),
]);
$definition->setFactory([Util\TestEvaluators\HttpEvaluator::class, 'factory']);
$container->setDefinition('evaluator.http', $definition);

$definition = new Definition(Util\TestEvaluators\HttpsEvaluator::class, [
    new Reference('event_dispatcher'),
]);
$definition->setFactory([Util\TestEvaluators\HttpsEvaluator::class, 'factory']);
$container->setDefinition('evaluator.https', $definition);


/**
 *
 */
$container->register('environment_check', \Util\EnvironmentCheck::class)
    ->addArgument($container->get('config_writer'));
