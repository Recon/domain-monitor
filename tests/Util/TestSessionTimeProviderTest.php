<?php
namespace Tests\Util;


use Tests\TestCase;
use Util\TestSessionTimeProvider;

class TestSessionTimeProviderTest extends TestCase
{
    public function testFactory()
    {
        $provider = TestSessionTimeProvider::instance();

        $this->assertInstanceOf(TestSessionTimeProvider::class, $provider);
        $this->assertInstanceOf(\DateTime::class, $provider->getTime());
    }
}
