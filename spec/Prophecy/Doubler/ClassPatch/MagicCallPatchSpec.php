<?php

namespace spec\Prophecy\Doubler\ClassPatch;

use PhpSpec\ObjectBehavior;
use Prophecy\Doubler\ClassPatch\ClassPatchInterface;
use Prophecy\Doubler\Generator\Node\ClassNode;
use Prophecy\Doubler\Generator\Node\MethodNode;

class MagicCallPatchSpec extends ObjectBehavior
{
    function it_is_a_patch()
    {
        $this->shouldBeAnInstanceOf(ClassPatchInterface::class);
    }

    function it_supports_anything(ClassNode $node)
    {
        $this->supports($node)->shouldReturn(true);
    }

    function it_discovers_api_using_phpdoc(ClassNode $node)
    {
        $node->getParentClass()->willReturn(MagicalApi::class);
        $node->getInterfaces()->willReturn(array());

        $node->addMethod(new MethodNode('undefinedMethod'))->shouldBeCalled();

        $this->apply($node);
    }

    function it_ignores_existing_methods(ClassNode $node)
    {
        $node->getParentClass()->willReturn(MagicalApiExtended::class);
        $node->getInterfaces()->willReturn(array());

        $node->addMethod(new MethodNode('undefinedMethod'))->shouldBeCalled();
        $node->addMethod(new MethodNode('definedMethod'))->shouldNotBeCalled();

        $this->apply($node);
    }

    function it_ignores_empty_methods_from_phpdoc(ClassNode $node)
    {
        $node->getParentClass()->willReturn(MagicalApiInvalidMethodDefinition::class);
        $node->getInterfaces()->willReturn(array());

        $node->addMethod(new MethodNode(''))->shouldNotBeCalled();

        $this->apply($node);
    }

    function it_discovers_api_using_phpdoc_from_implemented_interfaces(ClassNode $node)
    {
        $node->getParentClass()->willReturn(MagicalApiImplemented::class);
        $node->getInterfaces()->willReturn(array());

        $node->addMethod(new MethodNode('implementedMethod'))->shouldBeCalled();

        $this->apply($node);
    }

    function it_discovers_api_using_phpdoc_from_own_interfaces(ClassNode $node)
    {
        $node->getParentClass()->willReturn(\stdClass::class);
        $node->getInterfaces()->willReturn(array(MagicalApiImplemented::class));

        $node->addMethod(new MethodNode('implementedMethod'))->shouldBeCalled();

        $this->apply($node);
    }

    function it_discovers_api_using_phpdoc_from_extended_parent_interfaces(ClassNode $node)
    {
        $node->getParentClass()->willReturn(MagicalApiImplementedExtended::class);
        $node->getInterfaces()->willReturn(array());

        $node->addMethod(new MethodNode('implementedMethod'))->shouldBeCalled();

        $this->apply($node);
    }

    function it_has_50_priority()
    {
        $this->getPriority()->shouldReturn(50);
    }
}

/**
 * @method void undefinedMethod()
 */
class MagicalApi
{
    /**
     * @return void
     */
    public function definedMethod()
    {

    }
}

/**
 * @method void invalidMethodDefinition
 * @method void
 * @method
 */
class MagicalApiInvalidMethodDefinition
{
}

/**
 * @method void definedMethod()
 */
class MagicalApiExtended extends MagicalApi
{

}

/**
 */
class MagicalApiImplemented implements MagicalApiInterface
{

}

/**
 */
class MagicalApiImplementedExtended extends MagicalApiImplemented
{
}
'ArrayAccess'
/**
 * @method void implementedMethod()
 */
interface MagicalApiInterface
{

}
