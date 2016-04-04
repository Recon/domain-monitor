<?php

namespace Subscribers;

use Events\DomainEvent;
use Events\UserEvent;
use Models\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserDomainConsistencySubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvent::NAME_ADDED     => [
                ['checkByUserEvent', 10],
            ],
            UserEvent::NAME_UPDATED   => [
                ['checkByUserEvent', 10],
            ],
            DomainEvent::NAME_ADDED   => [
                ['checkByDomainEvent', 10],
            ],
            DomainEvent::NAME_UPDATED => [
                ['checkByDomainEvent', 10],
            ],

        ];
    }

    public function checkByUserEvent(UserEvent $event)
    {
        $user = $event->getUser();
        $this->ensureAdministratorConsistency($user);
    }

    public function checkByDomainEvent(DomainEvent $event)
    {
        $account = $event->getDomain()->getAccount();

        foreach ($account->getUsers() As $user) {
            $this->ensureAdministratorConsistency($user);
        }
    }

    /**
     * Ensures that all the account domains are associated with the administrator user in the database
     *
     * @param User $user
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function ensureAdministratorConsistency(User $user)
    {
        if (!$user->hasRole(User::ROLE_ADMIN)) {
            return;
        }

        foreach ($user->getAccount()->getDomains() As $domain) {
            $user->addDomain($domain);
        }

        $user->save();
    }
}
