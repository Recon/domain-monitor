<?php


namespace Util\UserTestNotifications;

use Models\User;

class UserInventoryPoolManager implements \IteratorAggregate
{

    /**
     * @var UserInventory[]
     */
    protected $pool = [];

    /**
     * @var int Keeps track of current element in Interator
     */
    protected $current;

    /**
     * @param User $user
     * @return UserInventory
     */
    public function getUserInventory(User $user)
    {
        $id = $user->getId();

        if (empty($this->pool[$id])) {
            $this->pool[$id] = new UserInventory($user);
        }

        return $this->pool[$id];
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->pool);
    }

}
