<?php

namespace Onebip;

class ArrayFlattenTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayFlatten()
    {
        $this->assertSame([], array_flatten([]));
        $this->assertSame(
            [1, 2, 3, 4, 5],
            array_flatten([1, [2, [3, [4, [5]]]]])
        );
    }
}
