<?php

namespace spec\Prophecy\Exception\Doubler;

use PhpSpec\ObjectBehavior;
use Prophecy\Exception\Doubler\DoubleException;

class MethodNotExtendableExceptionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('', 'User', 'getName');
    }

    function it_is_DoubleException()
    {
        $this->shouldHaveType(DoubleException::class);
    }

    function it_has_MethodName()
    {
        $this->getMethodName()->shouldReturn('getName');
    }

    function it_has_classname()
    {
        $this->getClassName()->shouldReturn('User');
    }
}
