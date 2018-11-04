<?php

namespace spec\Prophecy\Exception\Prediction;

use PhpSpec\ObjectBehavior;
use Prophecy\Call\Call;
use Prophecy\Exception\Prediction\PredictionException;
use Prophecy\Exception\Prophecy\MethodProphecyException;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

class UnexpectedCallsExceptionSpec extends ObjectBehavior
{
    function let(ObjectProphecy $objectProphecy, MethodProphecy $methodProphecy, Call $call1, Call $call2)
    {
        $methodProphecy->getObjectProphecy()->willReturn($objectProphecy);

        $this->beConstructedWith('message', $methodProphecy, array($call1, $call2));
    }

    function it_is_PredictionException()
    {
        $this->shouldHaveType(PredictionException::class);
    }

    function it_extends_MethodProphecyException()
    {
        $this->shouldHaveType(MethodProphecyException::class);
    }

    function it_should_expose_calls_list_through_getter($call1, $call2)
    {
        $this->getCalls()->shouldReturn(array($call1, $call2));
    }
}
