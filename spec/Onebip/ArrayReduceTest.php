<?php

namespace Onebip;

use function Onebip\array_reduce;

class ArrayReduceTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayReduce()
    {
        $this->assertSame(
            45,
            array_reduce(
                [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                function ($acc, $n) { return $acc + $n; },
                0
            )
        );
    }

    public function testIterator()
    {
        $this->assertSame(
            45,
            array_reduce(
                new Range(0, 10),
                function ($acc, $n) { return $acc + $n; },
                0
            )
        );
    }
}
