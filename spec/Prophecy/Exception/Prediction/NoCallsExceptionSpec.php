<?php

namespace spec\Prophecy\Exception\Prediction;

use PhpSpec\ObjectBehavior;
use Prophecy\Exception\Prediction\PredictionException;
use Prophecy\Exception\Prophecy\MethodProphecyException;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

class NoCallsExceptionSpec extends ObjectBehavior
{
    function let(ObjectProphecy $objectProphecy, MethodProphecy $methodProphecy)
    {
        $methodProphecy->getObjectProphecy()->willReturn($objectProphecy);

        $this->beConstructedWith('message', $methodProphecy);
    }

    function it_is_PredictionException()
    {
        $this->shouldHaveType(PredictionException::class);
    }

    function it_extends_MethodProphecyException()
    {
        $this->shouldHaveType(MethodProphecyException::class);
    }
}
