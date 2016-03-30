<?php
namespace Tests\Util;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\TestCase;
use Util\ValidatorFactory;

class ValidatorFactoryTest extends TestCase
{
    public function testFactory()
    {
        $factory = new ValidatorFactory();

        $this->assertInstanceOf(ValidatorFactory::class, $factory);
        $this->assertInstanceOf(ValidatorInterface::class, $factory->create());
    }
}
