<?php
declare(strict_types = 1);

namespace Xamplifier\Reviews\Tests;

use PHPUnit\Framework\TestCase;
use Xamplifier\Reviews\EndPoint;

class EndPointTest extends TestCase
{
    public function testCreateProperties()
    {
        $obj = new EndPoint([
            'foo' => 'bar'
        ]);
        $expected = 'bar';

        $this->assertEquals($expected, $obj->foo);
    }
}
