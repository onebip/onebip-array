<?php

namespace Onebip;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ArrayFetchTest extends TestCase
{
    public function setUp(): void
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

    public function testError()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('key not found 4');

        $this->assertSame('fallback', array_fetch($this->array, '4'));
    }
}
