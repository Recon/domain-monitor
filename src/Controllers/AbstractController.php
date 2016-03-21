<?php

namespace Controllers;

use \Exceptions\HTTP\JSON\UnauthorizedException;
use \Models\User;
use \Symfony\Component\DependencyInjection\Container;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Session\Session;
use \Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use \Symfony\Component\Templating\EngineInterface;
use \Util\UserPermissionChecker;

class AbstractController
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var EngineInterface
     */
    protected $templateEngine;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var AuthorizationChecker
     * @deprecated Use $permissionChecker instead
     */
    protected $authorizationChecker;

    /**
     * @var UserPermissionChecker
     */
    protected $permissionChecker;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param Container $container
     */
    final public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param EngineInterface $engine
     */
    final public function setTemplateEngine(EngineInterface $engine)
    {
        $this->templateEngine = $engine;
    }

    /**
     * @param Request $request
     */
    final public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param AuthorizationChecker $checker
     * @deprecated Use $permissionChecker instead
     */
    final public function setAuthorizationChecker(AuthorizationChecker $checker)
    {
        $this->authorizationChecker = $checker;
    }

    /**
     * @param UserPermissionChecker $checker
     */
    final public function setPermissionChecker(UserPermissionChecker $checker)
    {
        $this->permissionChecker = $checker;
    }

    /**
     * @param Session $session
     */
    final public function setSessionManager(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @return User
     * @throws UnauthorizedException
     */
    protected function getLoggedInUser()
    {
        if (!$this->authorizationChecker->isGranted(['ROLE_USER'])) {
            throw new UnauthorizedException();
        }

        return $this->session->get('auth_token')->getUser();
    }

    /**
     * Performs a validation againts a model
     *
     * @param mixed $model One of the \Model\* object implementing loadValidatorMetadata static method
     * @return array Array of error messages
     */
    protected function getErrorMessages($model)
    {
        /* @var $validator ValidatorInterface */
        $validator = $this->container->get('validator');

        $violations = $validator->validate($model);
        $messages = [];
        if (count($violations)) {
            foreach ($violations as $violation) {
                $messages[] = $violation->getMessage();
            }
        }

        return $messages;
    }

}
