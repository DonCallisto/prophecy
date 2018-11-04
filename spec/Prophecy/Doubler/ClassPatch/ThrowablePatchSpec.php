<?php

namespace spec\Prophecy\Doubler\ClassPatch;

use Fixtures\Prophecy\ThrowableInterface;
use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use Prophecy\Doubler\ClassPatch\ClassPatchInterface;
use Prophecy\Doubler\Generator\Node\ClassNode;
use Prophecy\Exception\Doubler\ClassCreatorException;

class ThrowablePatchSpec extends ObjectBehavior
{
    function it_is_a_patch()
    {
        $this->shouldBeAnInstanceOf(ClassPatchInterface::class);
    }

    function it_does_not_support_class_that_does_not_implement_throwable(ClassNode $node)
    {
        if (\PHP_VERSION_ID < 70000) {
            throw new SkippingException('Throwable is not defined in PHP 5');
        }

        $node->getInterfaces()->willReturn(array());
        $node->getParentClass()->willReturn(\stdClass::class);

        $this->supports($node)->shouldReturn(false);
    }

    function it_supports_class_that_extends_not_throwable_class(ClassNode $node)
    {
        if (\PHP_VERSION_ID < 70000) {
            throw new SkippingException('Throwable is not defined in PHP 5');
        }

        $node->getInterfaces()->willReturn(array(\Throwable::class));
        $node->getParentClass()->willReturn(\stdClass::class);

        $this->supports($node)->shouldReturn(true);
    }

    function it_does_not_support_class_that_already_extends_a_throwable_class(ClassNode $node)
    {
        if (\PHP_VERSION_ID < 70000) {
            throw new SkippingException('Throwable is not defined in PHP 5');
        }

        $node->getInterfaces()->willReturn(array(\Throwable::class));
        $node->getParentClass()->willReturn(\InvalidArgumentException::class);

        $this->supports($node)->shouldReturn(false);
    }

    function it_supports_class_implementing_interface_that_extends_throwable(ClassNode $node)
    {
        if (\PHP_VERSION_ID < 70000) {
            throw new SkippingException('Throwable is not defined in PHP 5');
        }

        $node->getInterfaces()->willReturn(array(ThrowableInterface::class));
        $node->getParentClass()->willReturn(\stdClass::class);

        $this->supports($node)->shouldReturn(true);
    }

    function it_sets_the_parent_class_to_exception(ClassNode $node)
    {
        if (\PHP_VERSION_ID < 70000) {
            throw new SkippingException('Throwable is not defined in PHP 5');
        }

        $node->getParentClass()->willReturn(\stdClass::class);

        $node->setParentClass('Exception')->shouldBeCalled();

        $node->removeMethod('getMessage')->shouldBeCalled();
        $node->removeMethod('getCode')->shouldBeCalled();
        $node->removeMethod('getFile')->shouldBeCalled();
        $node->removeMethod('getLine')->shouldBeCalled();
        $node->removeMethod('getTrace')->shouldBeCalled();
        $node->removeMethod('getPrevious')->shouldBeCalled();
        $node->removeMethod('getNext')->shouldBeCalled();
        $node->removeMethod('getTraceAsString')->shouldBeCalled();

        $this->apply($node);
    }

    function it_throws_error_when_trying_to_double_concrete_class_and_throwable_interface(ClassNode $node)
    {
        if (\PHP_VERSION_ID < 70000) {
            throw new SkippingException('Throwable is not defined in PHP 5');
        }

        $node->getParentClass()->willReturn(\ArrayObject::class);

        $this->shouldThrow(ClassCreatorException::class)->duringApply($node);
    }
}
