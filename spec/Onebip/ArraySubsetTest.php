<?php

namespace Onebip;

use PHPUnit\Framework\TestCase;

class ArraySubsetTest extends TestCase
{
    public function testArraySubset()
    {
        $this->assertTrue(array_subset([], []));
        $this->assertTrue(array_subset([], [1]));
        $this->assertTrue(array_subset([1, 2, 3], [1, 2, 3, 4, 5]));

        $this->assertFalse(array_subset([1], []));
        $this->assertFalse(array_subset([1, 2, 3, 6], [1, 2, 3, 4, 5]));
    }
}
