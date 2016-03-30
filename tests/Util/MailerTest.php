<?php
namespace Tests\Util;


use Symfony\Component\Templating\PhpEngine;
use Tests\TestCase;
use Util\Mailer;

class MailerTest extends TestCase
{
    public function testFactory()
    {
        $engine = $this->getMockBuilder(PhpEngine::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->once())
            ->method('render');

        $message = $this->getMockBuilder(\Swift_Message::class)
            ->disableOriginalConstructor()
            ->getMock();


        $mailer = new Mailer($message, $engine);

        $this->assertInstanceOf(Mailer::class, $mailer);

        $mailer->renderMessageBody('template');
    }
}
