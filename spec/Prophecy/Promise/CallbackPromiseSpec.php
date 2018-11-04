<?php

namespace spec\Prophecy\Promise;

use PhpSpec\ObjectBehavior;
use Prophecy\Promise\PromiseInterface;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

class CallbackPromiseSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('get_class');
    }

    function it_is_promise()
    {
        $this->shouldBeAnInstanceOf(PromiseInterface::class);
    }

    function it_should_execute_closure_callback(ObjectProphecy $object, MethodProphecy $method)
    {
        $firstArgumentCallback = function ($args) {
            return $args[0];
        };

        $this->beConstructedWith($firstArgumentCallback);

        $this->execute(array('one', 'two'), $object, $method)->shouldReturn('one');
    }

    function it_should_execute_static_array_callback(ObjectProphecy $object, MethodProphecy $method)
    {
        $firstArgumentCallback = array(ClassCallback::class, 'staticCallbackMethod');

        $this->beConstructedWith($firstArgumentCallback);

        $this->execute(array('one', 'two'), $object, $method)->shouldReturn('one');
    }

    function it_should_execute_instance_array_callback(ObjectProphecy $object, MethodProphecy $method)
    {
        $class = new ClassCallback();
        $firstArgumentCallback = array($class, 'callbackMethod');

        $this->beConstructedWith($firstArgumentCallback);

        $this->execute(array('one', 'two'), $object, $method)->shouldReturn('one');
    }

    function it_should_execute_string_function_callback(ObjectProphecy $object, MethodProphecy $method)
    {
        $firstArgumentCallback = 'spec\Prophecy\Promise\functionCallbackFirstArgument';

        $this->beConstructedWith($firstArgumentCallback);

        $this->execute(array('one', 'two'), $object, $method)->shouldReturn('one');
    }

}

/**
 * Class used to test callbackpromise
 *
 * @param array
 * @return string
 */
class ClassCallback
{
    /**
     * @param array $args
     */
    function callbackMethod($args)
    {
        return $args[0];
    }

    /**
     * @param array $args
     */
    static function staticCallbackMethod($args)
    {
        return $args[0];
    }
}

/**
 * Callback function used to test callbackpromise
 *
 * @param array
 * @return string
 */
function functionCallbackFirstArgument($args)
{
    return $args[0];
}
