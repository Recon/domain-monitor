<?php


namespace Util\UserTestNotifications;


use Models\Test;
use Models\User;

class UserInventory
{
    /**
     * @var Test[]
     */
    protected $failed = [];

    /**
     * @var Test[]
     */
    protected $succeeded = [];

    /**
     * @var User
     */
    protected $user;

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

    /**
     * @return \Models\Test[]
     */
    public function getSucceeded()
    {
        return $this->succeeded;
    }

    /**
     * @param \Models\Test[] $test
     * @return UserInventory
     */
    public function addSucceeded($test)
    {
        $this->succeeded[] = $test;
    }

    /**
     * @return \Models\Test[]
     */
    public function getFailed()
    {
        return $this->failed;
    }

    /**
     * @param \Models\Test[] $test
     */
    public function addFailed($test)
    {
        $this->failed[] = $test;
    }


}
