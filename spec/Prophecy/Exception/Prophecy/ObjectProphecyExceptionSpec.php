<?php

namespace spec\Prophecy\Exception\Prophecy;

use PhpSpec\ObjectBehavior;
use Prophecy\Exception\Prophecy\ProphecyException;
use Prophecy\Prophecy\ObjectProphecy;

class ObjectProphecyExceptionSpec extends ObjectBehavior
{
    function let(ObjectProphecy $objectProphecy)
    {
        $this->beConstructedWith('message', $objectProphecy);
    }

    function it_should_be_a_prophecy_exception()
    {
        $this->shouldBeAnInstanceOf(ProphecyException::class);
    }

    function it_holds_double_reference($objectProphecy)
    {
        $this->getObjectProphecy()->shouldReturn($objectProphecy);
    }
}
