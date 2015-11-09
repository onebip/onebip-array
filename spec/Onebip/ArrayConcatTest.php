<?php

namespace Onebip;

class ArrayConcatTest extends \PHPUnit_Framework_TestCase
{
    public function testConcat()
    {
        $this->assertSame(
            [1, 2, 3, 4],
            array_concat(1, [2, 3], [4])
        );
    }

    public function testConcatPresevesNestedArrays()
    {
        $this->assertSame(
            [1, 2, [3], 4],
            array_concat(1, [2, [3]], [4])
        );
    }

    public function testConcatEmpty()
    {
        $this->assertSame([], array_concat([], [], []));
    }

    public function testConcatCastToArray()
    {
        $this->assertSame([1], array_concat([1]));
        $this->assertSame([1], array_concat(1));
    }
}
