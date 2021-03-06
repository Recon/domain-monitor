<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;

global $container;

/**
 * Configure routing and pass control to controller
 */
$locator = new FileLocator([__DIR__ . '/../src']);
$loader = new YamlFileLoader($locator);

$context = new RequestContext();
$context->fromRequest(Request::createFromGlobals());

$router = new Router(new YamlFileLoader($locator), 'routes.yml', ['cache_dir' => null], $context);
$matcher = new UrlMatcher($router->getRouteCollection(), $context);

$path = parse_url($_SERVER['REQUEST_URI'])['path'];
$path = str_replace('/index.php', '', $path);

try {
    $parameters = $router->match($path);
} catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $ex) {
    throw new \Exceptions\HTTP\Error404();
}

$request = Request::createFromGlobals();
$resolver = new ControllerResolver();

$request->attributes->add($matcher->match($request->getPathInfo()));
$controller = $resolver->getController($request);
$controller[0]->setContainer($container);
$controller[0]->setTemplateEngine($container->get('template_engine'));
$controller[0]->setRequest($request);
$controller[0]->setAuthorizationChecker($container->get('auth.checker'));
$controller[0]->setPermissionChecker($container->get('auth.permission_checker'));
$controller[0]->setSessionManager($container->get('session'));
$controller[0]->init();
$arguments = $resolver->getArguments($request, $controller);
$response = call_user_func_array($controller, $arguments);

$response->send();
