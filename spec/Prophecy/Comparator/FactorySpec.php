<?php

namespace spec\Prophecy\Comparator;

use PhpSpec\ObjectBehavior;
use Prophecy\Comparator\ClosureComparator;
use SebastianBergmann\Comparator\Factory;

class FactorySpec extends ObjectBehavior
{
    function it_extends_Sebastian_Comparator_Factory()
    {
        $this->shouldHaveType(Factory::class);
    }

    function it_should_have_ClosureComparator_registered()
    {
        $comparator = $this->getInstance()->getComparatorFor(function(){}, function(){});
        $comparator->shouldHaveType(ClosureComparator::class);
    }
}
