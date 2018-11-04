<?php

namespace spec\Prophecy\Promise;

use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use Prophecy\Exception\InvalidArgumentException;
use Prophecy\Promise\PromiseInterface;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

class ThrowPromiseSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(\RuntimeException::class);
    }

    function it_is_promise()
    {
        $this->shouldBeAnInstanceOf(PromiseInterface::class);
    }

    function it_instantiates_and_throws_exception_from_provided_classname(ObjectProphecy $object, MethodProphecy $method)
    {
        $this->beConstructedWith(\InvalidArgumentException::class);

        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringExecute(array(), $object, $method);
    }

    function it_instantiates_exceptions_with_required_arguments(ObjectProphecy $object, MethodProphecy $method)
    {
        $this->beConstructedWith(RequiredArgumentException::class);

        $this->shouldThrow(RequiredArgumentException::class)
            ->duringExecute(array(), $object, $method);
    }

    function it_throws_provided_exception(ObjectProphecy $object, MethodProphecy $method)
    {
        $this->beConstructedWith($exc = new \RuntimeException('Some exception'));

        $this->shouldThrow($exc)->duringExecute(array(), $object, $method);
    }

    function it_throws_error_instances(ObjectProphecy $object, MethodProphecy $method)
    {
        if (!class_exists('\Error')) {
            throw new SkippingException('The class Error, introduced in PHP 7, does not exist');
        }

        $this->beConstructedWith($exc = new \Error('Error exception'));

        $this->shouldThrow($exc)->duringExecute(array(), $object, $method);
    }

    function it_throws_errors_by_class_name()
    {
        if (!class_exists('\Error')) {
            throw new SkippingException('The class Error, introduced in PHP 7, does not exist');
        }

        $this->beConstructedWith(\Error::class);

        $this->shouldNotThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    function it_does_not_throw_something_that_is_not_throwable_by_class_name()
    {
        $this->beConstructedWith(\stdClass::class);

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    function it_does_not_throw_something_that_is_not_throwable_by_instance()
    {
        $this->beConstructedWith(new \stdClass());

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    function it_throws_an_exception_by_class_name()
    {
        $this->beConstructedWith(\Exception::class);

        $this->shouldNotThrow(InvalidArgumentException::class)->duringInstantiation();
    }
}

class RequiredArgumentException extends \Exception
{
    final public function __construct($message, $code) {}
}
