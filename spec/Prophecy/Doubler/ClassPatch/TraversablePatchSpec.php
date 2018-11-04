<?php

namespace spec\Prophecy\Doubler\ClassPatch;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Doubler\ClassPatch\ClassPatchInterface;
use Prophecy\Doubler\Generator\Node\ClassNode;
use Prophecy\Doubler\Generator\Node\MethodNode;

class TraversablePatchSpec extends ObjectBehavior
{
    function it_is_a_patch()
    {
        $this->shouldBeAnInstanceOf(ClassPatchInterface::class);
    }

    function it_supports_class_that_implements_only_Traversable(ClassNode $node)
    {
        $node->getInterfaces()->willReturn(array(\Traversable::class));

        $this->supports($node)->shouldReturn(true);
    }

    function it_does_not_support_class_that_implements_Iterator(ClassNode $node)
    {
        $node->getInterfaces()->willReturn(array(\Traversable::class, \Iterator::class));

        $this->supports($node)->shouldReturn(false);
    }

    function it_does_not_support_class_that_implements_IteratorAggregate(ClassNode $node)
    {
        $node->getInterfaces()->willReturn(array(\Traversable::class, \IteratorAggregate::class));

        $this->supports($node)->shouldReturn(false);
    }

    function it_has_100_priority()
    {
        $this->getPriority()->shouldReturn(100);
    }

    function it_forces_node_to_implement_IteratorAggregate(ClassNode $node)
    {
        $node->addInterface(\Iterator::class)->shouldBeCalled();

        $node->addMethod(Argument::type(MethodNode::class))->willReturn(null);

        $this->apply($node);
    }
}
