<?php

namespace Onebip;

use ArrayIterator;

class ArrayMaxTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayMax()
    {
        $this->assertNull(array_max([]));
        $this->assertSame(0, array_max([0]));
        $this->assertSame(1, array_max([0, 1]));

        $this->assertNull(array_max(new ArrayIterator([])));
        $this->assertSame(0, array_max(new ArrayIterator([0])));
        $this->assertSame(1, array_max(new ArrayIterator([0, 1])));
    }
}
