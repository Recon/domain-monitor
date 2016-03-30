<?php
namespace Tests\Util;


use Symfony\Component\Templating\PhpEngine;
use Tests\TestCase;
use Util\Config\ConfigLoader;
use Util\Mailer;
use Util\MailerFactory;

class MailerFactoryTest extends TestCase
{
    public function testFactory()
    {
        $settings = [
            'smtp_host'       => '0.0.0.0',
            'smtp_user'       => '',
            'smtp_pass'       => '',
            'smtp_encryption' => '',
            'mail_transport'  => 'mail',
        ];

        $callback = function ($key) use ($settings) {
            return $settings[$key];
        };

        $config = $this->getMockBuilder(ConfigLoader::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $config->expects($this->any())
            ->method('get')
            ->will($this->returnCallback($callback));

        $engine = $this->getMockBuilder(PhpEngine::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory = new MailerFactory($engine, $config);
        $mailer = $factory->factory();

        $this->assertInstanceOf(Mailer::class, $mailer);
        $this->assertInstanceOf(\Swift_Transport::class, $factory->getTransport());
    }
}
