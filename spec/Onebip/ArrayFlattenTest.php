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

    public function testIterators()
    {
        $this->assertSame(
            [1, 2, 3, 4, 5],
            array_flatten(
                new \ArrayIterator([1,
                    new \ArrayIterator([2,
                        new \ArrayIterator([3,
                            new \ArrayIterator([4,
                                new \ArrayIterator([5])])])])]))
        );
    }
}
