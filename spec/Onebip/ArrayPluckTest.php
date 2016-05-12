<?php

namespace Onebip;

class ArrayPluckTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayPluckColumn()
    {
        $this->assertSame(
            ['bar', 'bar'],
            array_pluck([['foo' => 'bar', 'bis' => 'ter'],
                         ['foo' => 'bar', 'bis' => 'ter']],
                        'foo')
        );
    }

    public function testIterator()
    {
        $this->assertSame(
            ['bar', 'bar'],
            array_pluck(
                new \ArrayIterator([
                    ['foo' => 'bar', 'bis' => 'ter'],
                    ['foo' => 'bar', 'bis' => 'ter']
                ]),
                'foo'
            )
        );
    }

    public function testArrayPluckWithHoles()
    {
        $this->assertSame(
            ['bar', null, 'bar'],
            array_pluck([['foo' => 'bar', 'bis' => 'ter'],
                         ['foz' => 'bar', 'bis' => 'ter'],
                         ['foo' => 'bar', 'bis' => 'ter']],
                        'foo')
        );
    }

    public function testArrayPluckWithScalarValues()
    {
        $this->assertSame(
            ['bar', null, 'bar'],
            array_pluck([['foo' => 'bar', 'bis' => 'ter'],
                         "a scalar value",
                         ['foo' => 'bar', 'bis' => 'ter']],
                        'foo')
        );
    }
}
