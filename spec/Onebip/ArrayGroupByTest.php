<?php

namespace Onebip;

use PHPUnit\Framework\TestCase;

class ArrayGroupByTest extends TestCase
{
    public function testArrayGroupBy()
    {
        $this->assertSame([], array_group_by([]));
        $this->assertSame(
            [
                1 => [1],
                2 => [2, 2],
                3 => [3, 3],
                4 => [4],
            ],
            array_group_by([1, 2, 2, 3, 3, 4])
        );
        $this->assertSame(
            [
                1 => [1, 3, 5],
                0 => [2, 4, 6],
            ],
            array_group_by(
                [1, 2, 3, 4, 5, 6],
                function ($n) {
                    return $n % 2;
                }
            )
        );
    }

    public function testIterator()
    {
        $this->assertSame(
            [
                1 => [1],
                2 => [2, 2],
                3 => [3, 3],
                4 => [4],
            ],
            array_group_by(new \ArrayIterator([1, 2, 2, 3, 3, 4]))
        );
    }
}
