<?php

namespace spec\Prophecy\Exception\Doubler;

use PhpSpec\ObjectBehavior;
use Prophecy\Exception\Doubler\DoublerException;
use Prophecy\Exception\Exception;

class ClassMirrorExceptionSpec extends ObjectBehavior
{
    function let(\ReflectionClass $class)
    {
        $this->beConstructedWith('', $class);
    }

    function it_is_a_prophecy_exception()
    {
        $this->shouldBeAnInstanceOf(Exception::class);
        $this->shouldBeAnInstanceOf(DoublerException::class);
    }

    function it_contains_a_reflected_class_link($class)
    {
        $this->getReflectedClass()->shouldReturn($class);
    }
}
