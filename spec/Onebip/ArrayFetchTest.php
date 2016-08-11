<?php

namespace Onebip;

class Arrayarray_fetchTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->array = [0, 1, 2, null, 'a' => 1, 'b' => null];
    }

    public function testarray_fetch()
    {
        $this->assertSame(0, array_fetch($this->array, 0));
        $this->assertSame(1, array_fetch($this->array, 'a'));
        $this->assertSame(null, array_fetch($this->array, 3));
        $this->assertSame(null, array_fetch($this->array, 'b'));
    }

    public function testarray_fetchFallback()
    {
        $this->assertSame('fallback', array_fetch($this->array, 4, 'fallback'));
        $this->assertSame('fallback', array_fetch($this->array, 'c', 'fallback'));
        $this->assertSame(null, array_fetch($this->array, 'c', null));
    }

    public function testarray_fetchClosure()
    {
        $this->assertSame(
            4,
            array_fetch($this->array, 4, function ($i) { return $i; })
        );
        $this->assertSame(
            'c',
            array_fetch($this->array, 'c', function ($i) { return $i; })
        );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage key not found 4
     */
    public function testError()
    {
        $this->assertSame('fallback', array_fetch($this->array, '4'));
    }
}
