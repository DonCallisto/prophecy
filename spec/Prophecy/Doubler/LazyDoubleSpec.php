<?php

namespace spec\Prophecy\Doubler;

use PhpSpec\ObjectBehavior;
use Prophecy\Doubler\Doubler;
use Prophecy\Exception\Doubler\ClassNotFoundException;
use Prophecy\Exception\Doubler\DoubleException;
use Prophecy\Exception\Doubler\InterfaceNotFoundException;
use Prophecy\Prophecy\ProphecySubjectInterface;

class LazyDoubleSpec extends ObjectBehavior
{
    function let(Doubler $doubler)
    {
        $this->beConstructedWith($doubler);
    }

    function it_returns_anonymous_double_instance_by_default($doubler, ProphecySubjectInterface $double)
    {
        $doubler->double(null, array())->willReturn($double);

        $this->getInstance()->shouldReturn($double);
    }

    function it_returns_class_double_instance_if_set($doubler, ProphecySubjectInterface $double, \ReflectionClass $class)
    {
        $doubler->double($class, array())->willReturn($double);

        $this->setParentClass($class);

        $this->getInstance()->shouldReturn($double);
    }

    function it_returns_same_double_instance_if_called_2_times(
        $doubler,
        ProphecySubjectInterface $double1,
        ProphecySubjectInterface $double2
    ) {
        $doubler->double(null, array())->willReturn($double1);
        $doubler->double(null, array())->willReturn($double2);

        $this->getInstance()->shouldReturn($double2);
        $this->getInstance()->shouldReturn($double2);
    }

    function its_setParentClass_throws_ClassNotFoundException_if_class_not_found()
    {
        $this->shouldThrow(ClassNotFoundException::class)
            ->duringSetParentClass('SomeUnexistingClass');
    }

    function its_setParentClass_throws_exception_if_prophecy_is_already_created(
        $doubler,
        ProphecySubjectInterface $double
    ) {
        $doubler->double(null, array())->willReturn($double);

        $this->getInstance();

        $this->shouldThrow(DoubleException::class)
            ->duringSetParentClass(\stdClass::class);
    }

    function its_addInterface_throws_InterfaceNotFoundException_if_no_interface_found()
    {
        $this->shouldThrow(InterfaceNotFoundException::class)
            ->duringAddInterface('SomeUnexistingInterface');
    }

    function its_addInterface_throws_exception_if_prophecy_is_already_created(
        $doubler,
        ProphecySubjectInterface $double
    ) {
        $doubler->double(null, array())->willReturn($double);

        $this->getInstance();

        $this->shouldThrow(DoubleException::class)
            ->duringAddInterface(\ArrayAccess::class);
    }
}
