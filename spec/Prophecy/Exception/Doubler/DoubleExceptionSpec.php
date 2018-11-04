<?php

namespace spec\Prophecy\Exception\Doubler;

use PhpSpec\ObjectBehavior;
use Prophecy\Exception\Doubler\DoublerException;

class DoubleExceptionSpec extends ObjectBehavior
{
    function it_is_a_double_exception()
    {
        $this->shouldBeAnInstanceOf(\RuntimeException::class);
        $this->shouldBeAnInstanceOf(DoublerException::class);
    }
}
