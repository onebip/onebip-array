<?php

namespace Onebip;

class ArrayCartesianProductTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayCartesianProduct()
    {
        $this->assertSame(
            [[]],
            array_cartesian_product([])
        );
        $this->assertSame(
            [[1, 2]],
            array_cartesian_product([[1], [2]])
        );
        $this->assertSame(
            [[1]],
            array_cartesian_product([[1]])
        );
        $this->assertSame(
            [[1, 2]],
            array_cartesian_product([[1], [2]])
        );
        $this->assertSame(
            [[1, 2], [1, 3]],
            array_cartesian_product([[1], [2, 3]])
        );
        $this->assertSame(
            [[1, 3], [1, 4], [2, 3], [2, 4]],
            array_cartesian_product([[1, 2], [3, 4]])
        );
        $this->assertSame(
            [[1, 3, 5], [1, 3, 6], [1, 3, 7], [1, 3, 8],
             [1, 4, 5], [1, 4, 6], [1, 4, 7], [1, 4, 8]],
            array_cartesian_product([[1], [3, 4], [5, 6, 7, 8]])
        );
    }
}
