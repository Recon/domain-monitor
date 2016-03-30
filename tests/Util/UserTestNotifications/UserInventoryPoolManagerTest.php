<?php

namespace Tests\Util\UserTestNotifications;


use Models\User;
use Tests\TestCase;
use Util\UserTestNotifications\UserInventory;
use Util\UserTestNotifications\UserInventoryPoolManager;

class UserInventoryPoolManagerTest extends TestCase
{
    public function testManager()
    {
        $pool = new UserInventoryPoolManager();
        $user1 = (new User())->setId(1);
        $user2 = (new User())->setId(2);

        $inventory = $pool->getUserInventory($user1);

        $this->assertInstanceOf(UserInventory::class, $inventory);
        $this->assertEquals($user1->getId(), $inventory->getUser()->getId());

        $pool->getUserInventory($user2);

        $this->assertEquals(2, count((array)$pool));
    }
}
