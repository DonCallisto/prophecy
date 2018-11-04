<?php

namespace spec\Prophecy\Exception\Doubler;

use PhpSpec\ObjectBehavior;
use Prophecy\Exception\Doubler\DoubleException;
use Prophecy\Exception\Exception;

class ClassNotFoundExceptionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('msg', 'CustomClass');
    }

    function it_is_a_prophecy_exception()
    {
        $this->shouldBeAnInstanceOf(Exception::class);
        $this->shouldBeAnInstanceOf(DoubleException::class);
    }

    function its_getClassname_returns_classname()
    {
        $this->getClassname()->shouldReturn('CustomClass');
    }
}
