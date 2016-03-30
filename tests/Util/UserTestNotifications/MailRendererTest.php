<?php

namespace Tests\Util\UserTestNotifications;


use Models\User;
use Tests\TestCase;
use Util\TemplatingFactory;
use Util\UserTestNotifications\MailRenderer;
use Util\UserTestNotifications\UserInventory;

class MailerRendererTest extends TestCase
{
    public function testRenderer()
    {
        $inventory = new UserInventory(new User());
        $renderer = new MailRenderer((new TemplatingFactory())->create());

        $subject = $renderer->getSubject($inventory);
        $this->assertTrue(strlen($subject) > 4);

        $message = $renderer->render($inventory);
        $this->assertTrue(strlen($message) > 4);
    }
}
