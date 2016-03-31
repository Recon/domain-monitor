<?php

namespace Authentication;

use Models\User;
use Models\UserQuery;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class PropelUserProvider implements UserProviderInterface
{

    public function loadUserByUsername($username)
    {
        $user = UserQuery::create()
            ->findByEmail($username);

        if ($user->count()) {
            return $user->getFirst();
        }

        $ex = new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        $ex->setUsername($username);

        throw $ex;
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === User::class;
    }

}
