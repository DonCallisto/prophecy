<?php

namespace spec\Prophecy\Prediction;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\ArgumentsWildcard;
use Prophecy\Call\Call;
use Prophecy\Exception\Prediction\UnexpectedCallsException;
use Prophecy\Prediction\PredictionInterface;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

class NoCallsPredictionSpec extends ObjectBehavior
{
    function it_is_prediction()
    {
        $this->shouldHaveType(PredictionInterface::class);
    }

    function it_does_nothing_if_there_is_no_calls_made(ObjectProphecy $object, MethodProphecy $method)
    {
        $this->check(array(), $object, $method)->shouldReturn(null);
    }

    function it_throws_UnexpectedCallsException_if_calls_found(
        ObjectProphecy $object,
        MethodProphecy $method,
        Call $call,
        ArgumentsWildcard $arguments
    ) {
        $object->reveal()->willReturn(new \stdClass);
        $method->getObjectProphecy()->willReturn($object);
        $method->getMethodName()->willReturn('getName');
        $method->getArgumentsWildcard()->willReturn($arguments);
        $arguments->__toString()->willReturn('123');

        $call->getMethodName()->willReturn('getName');
        $call->getArguments()->willReturn(array(5, 4, 'three'));
        $call->getCallPlace()->willReturn('unknown');

        $this->shouldThrow(UnexpectedCallsException::class)
            ->duringCheck(array($call), $object, $method);
    }
}
