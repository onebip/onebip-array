<?php

namespace Onebip;

class ArrayMerge extends \PHPUnit_Framework_TestCase
{
    public function testMerge()
    {
        $this->assertSame(
            ['a' => [1, 2, 3, 4]],
            array_merge(['a' => [1, 2]], ['a' => [3, 4]])
        );
    }

    public function testMergeEmpty()
    {
        $this->assertSame([], array_merge([], []));
    }

    public function testMergeNumericWillConcatInOrder()
    {
        $this->assertSame(
            [1, 2, 3, 4],
            array_merge([1, 2], [3, 4])
        );
    }

    public function testMergeAssociativeWillOverride()
    {
        $this->assertSame(
            ['a' => 2],
            array_merge(['a' => 1], ['a' => 2])
        );
    }

    public function testMergeDeplyRecursive()
    {
        $this->assertSame(
            ['a' => ['b' => null, 'c' => [1, 2, 3, 4]], 'b' => []],
            array_merge(['a' => ['b' => 2, 'c' => [1, 2]]], ['a' => ['b' => null, 'c' => [3, 4]], 'b' => []])
        );
    }

    public function testMergeMultipleArrays()
    {
        $this->assertSame(
            [1, 2, 3, 4],
            array_merge([1], [2], [3], [4])
        );
    }

    public function testMergeNotArrays()
    {
        $this->assertSame(
            [1, 2],
            array_merge(1, 2)
        );
    }
}
