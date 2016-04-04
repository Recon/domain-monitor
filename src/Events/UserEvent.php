<?php

namespace Events;

use Models\User;
use Symfony\Component\EventDispatcher\Event;

class UserEvent extends Event
{

    const NAME_ADDED = 'user.added';
    const NAME_UPDATED = 'user.updated';

    /**
     * @var User
     */
    private $user;

    /**
     * UserEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

}
