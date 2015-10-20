<?php

namespace Onebip;

class ArrayAllTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayAll()
    {
        $multipleOfTwo = function ($n) { return $n % 2 === 0; };

        $this->assertTrue(array_all([], $multipleOfTwo));
        $this->assertTrue(array_all([2, 4, 6], $multipleOfTwo));
        $this->assertFalse(array_all([2, 4, 5], $multipleOfTwo));

        $lessThanTen = function ($n) { return $n < 10; };
        $this->assertTrue(array_all(new Range(0, 10), $lessThanTen));
        $this->assertFalse(array_all(new Range(0, 11), $lessThanTen));
    }
}
