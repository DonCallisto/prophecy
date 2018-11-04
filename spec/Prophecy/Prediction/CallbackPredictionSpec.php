<?php

namespace spec\Prophecy\Prediction;

use PhpSpec\ObjectBehavior;
use Prophecy\Call\Call;
use Prophecy\Prediction\PredictionInterface;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;
use RuntimeException;

class CallbackPredictionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('get_class');
    }

    function it_is_prediction()
    {
        $this->shouldHaveType(PredictionInterface::class);
    }

    function it_proxies_call_to_callback(ObjectProphecy $object, MethodProphecy $method, Call $call)
    {
        $returnFirstCallCallback = function ($calls, $object, $method) {
            throw new RuntimeException;
        };

        $this->beConstructedWith($returnFirstCallCallback);

        $this->shouldThrow(RuntimeException::class)->duringCheck(array($call), $object, $method);
    }
}
