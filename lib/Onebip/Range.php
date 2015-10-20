<?php

namespace Onebip;

use Iterator;

class Range implements Iterator
{
    private $from;
    private $to;
    private $curr;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;

        $this->rewind();
    }

    function rewind() { $this->curr = $this->from; }
    function current() { return $this->curr; }
    function key() { return $this->curr; }
    function next() { $this->curr++; }
    function valid() { return $this->curr < $this->to; }
}
