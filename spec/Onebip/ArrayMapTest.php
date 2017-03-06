<?php

namespace Onebip;

class ArrayMapTest extends \PHPUnit_Framework_TestCase
{
    public function testMap()
    {
        $this->assertSame(
            [2, 4, 6],
            array_map([1, 2, 3], function($value) { return $value * 2; })
        );
    }

    public function testIterator()
    {
        $this->assertSame(
            [2, 4, 6],
            array_map(new Range(1, 4), function($value) { return $value * 2; })
        );
    }

    public function testIdentity()
    {
        $this->assertSame([], array_map([]));
        $this->assertSame([1, 2, 3], array_map([1, 2, 3]));
    }

    public function testIdentityPreservingKeys()
    {
        $array = ['1' => 1, '2' => 2, '3' => 3];
        $this->assertSame($array, array_map($array, null, true));
        $this->assertNotSame($array, array_map($array, null, false));
    }

    public function testIndexesAreLost()
    {
        $this->assertSame([1, 2, 3], array_map(['1' => 1, '2' => 2, '3' => 3]));
    }

    public function testIndexesArePassedAsParameters()
    {
        $returnKeys = function($value, $key) { return $key; };
        $this->assertSame(
            ['one', 'two', 'three'],
            array_map(['one' => 1, 'two' => 2, 'three' => 3], $returnKeys)
        );
    }
}
