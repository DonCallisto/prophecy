<?php

namespace spec\Prophecy\Exception\Doubler;

use PhpSpec\ObjectBehavior;
use Prophecy\Doubler\Generator\Node\ClassNode;
use Prophecy\Exception\Doubler\DoublerException;
use Prophecy\Exception\Exception;

class ClassCreatorExceptionSpec extends ObjectBehavior
{
    function let(ClassNode $node)
    {
        $this->beConstructedWith('', $node);
    }

    function it_is_a_prophecy_exception()
    {
        $this->shouldBeAnInstanceOf(Exception::class);
        $this->shouldBeAnInstanceOf(DoublerException::class);
    }

    function it_contains_a_reflected_node($node)
    {
        $this->getClassNode()->shouldReturn($node);
    }
}
