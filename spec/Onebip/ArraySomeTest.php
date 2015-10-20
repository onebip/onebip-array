<?php

namespace Onebip;

class ArraySomeTest extends \PHPUnit_Framework_TestCase
{
    public function testArraySome()
    {
        $this->assertTrue(
            array_some([1, 2, 3], function($value) { return $value % 2 === 0; })
        );
    }
}
