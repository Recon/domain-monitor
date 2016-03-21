<?php

namespace Util;

use \Models\Account;
use \Symfony\Component\HttpFoundation\Session\Session;
use \Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class UserPermissionChecker
{

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var AuthorizationChecker
     */
    protected $authorizationChecker;

    public function __construct(AuthorizationChecker $authorizationChecker, Session $session)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->session = $session;
    }

    public function isLoggedIn()
    {
        return $this->authorizationChecker->isGranted(['ROLE_ADMIN', 'ROLE_USER']);
    }

    public function isAdmin()
    {
        return $this->authorizationChecker->isGranted(['ROLE_ADMIN']);
    }

    public function hasAccountRights(Account $account)
    {
        if (!$this->isLoggedIn()) {
            return false;
        }

        if ($account->getId() != $this->session->get('auth_token')->getAccount()->getId()) {
            return false;
        }

        return true;
    }

}
