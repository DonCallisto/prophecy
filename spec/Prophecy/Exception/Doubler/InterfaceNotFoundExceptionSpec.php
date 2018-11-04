<?php

namespace spec\Prophecy\Exception\Doubler;

use PhpSpec\ObjectBehavior;
use Prophecy\Exception\Doubler\ClassNotFoundException;

class InterfaceNotFoundExceptionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('msg', 'CustomInterface');
    }

    function it_extends_ClassNotFoundException()
    {
        $this->shouldBeAnInstanceOf(ClassNotFoundException::class);
    }

    function its_getClassname_returns_classname()
    {
        $this->getClassname()->shouldReturn('CustomInterface');
    }
}
