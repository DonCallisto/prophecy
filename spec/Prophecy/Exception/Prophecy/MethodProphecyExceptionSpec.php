<?php

namespace spec\Prophecy\Exception\Prophecy;

use PhpSpec\ObjectBehavior;
use Prophecy\Exception\Prophecy\ObjectProphecyException;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

class MethodProphecyExceptionSpec extends ObjectBehavior
{
    function let(ObjectProphecy $objectProphecy, MethodProphecy $methodProphecy)
    {
        $methodProphecy->getObjectProphecy()->willReturn($objectProphecy);

        $this->beConstructedWith('message', $methodProphecy);
    }

    function it_extends_DoubleException()
    {
        $this->shouldBeAnInstanceOf(ObjectProphecyException::class);
    }

    function it_holds_a_stub_reference($methodProphecy)
    {
        $this->getMethodProphecy()->shouldReturn($methodProphecy);
    }
}
