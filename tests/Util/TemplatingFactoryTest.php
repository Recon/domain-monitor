<?php
namespace Tests\Util;


use Symfony\Component\Templating\EngineInterface;
use Tests\TestCase;
use Util\TemplatingFactory;

class TemplatingFactoryTest extends TestCase
{
    public function testFactory()
    {
        $factory = new TemplatingFactory();
        $obj = $factory->create();

        $this->assertInstanceOf(EngineInterface::class, $obj);
    }
}
