<?php

namespace Tests\Prophecy\Doubler\Generator;

use Fixtures\Prophecy\EmptyClass;
use Fixtures\Prophecy\EmptyInterface;
use Fixtures\Prophecy\FinalClass;
use Fixtures\Prophecy\MethodWithAdditionalParam;
use Fixtures\Prophecy\ModifierInterface;
use Fixtures\Prophecy\Named;
use Fixtures\Prophecy\NullableArrayParameter;
use Fixtures\Prophecy\OptionalDepsClass;
use Fixtures\Prophecy\SpecialMethods;
use Fixtures\Prophecy\WithArguments;
use Fixtures\Prophecy\WithCallableArgument;
use Fixtures\Prophecy\WithFinalMethod;
use Fixtures\Prophecy\WithFinalVirtuallyPrivateMethod;
use Fixtures\Prophecy\WithProtectedAbstractMethod;
use Fixtures\Prophecy\WithReferences;
use Fixtures\Prophecy\WithReturnTypehints;
use Fixtures\Prophecy\WithStaticMethod;
use Fixtures\Prophecy\WithTypehintedVariadicArgument;
use Fixtures\Prophecy\WithVariadicArgument;
use Fixtures\Prophecy\WithVirtuallyPrivateMethod;
use PHPUnit\Framework\TestCase;
use Prophecy\Doubler\Generator\ClassMirror;
use Prophecy\Doubler\Generator\ReflectionInterface;

class ClassMirrorTest extends TestCase
{
    /**
     * @test
     */
    public function it_reflects_allowed_magic_methods()
    {
        $class = new \ReflectionClass(SpecialMethods::class);

        $mirror = new ClassMirror();

        $node = $mirror->reflect($class, array());

        $this->assertCount(7, $node->getMethods());
    }

    /**
     * @test
     */
    public function it_reflects_protected_abstract_methods()
    {
        $class = new \ReflectionClass(WithProtectedAbstractMethod::class);

        $mirror = new ClassMirror();

        $classNode = $mirror->reflect($class, array());

        $this->assertEquals(WithProtectedAbstractMethod::class, $classNode->getParentClass());

        $methodNodes = $classNode->getMethods();
        $this->assertCount(1, $methodNodes);

        $this->assertEquals('protected', $methodNodes['innerDetail']->getVisibility());
    }

    /**
     * @test
     */
    public function it_reflects_public_static_methods()
    {
        $class = new \ReflectionClass(WithStaticMethod::class);

        $mirror = new ClassMirror();

        $classNode = $mirror->reflect($class, array());

        $this->assertEquals(WithStaticMethod::class, $classNode->getParentClass());

        $methodNodes = $classNode->getMethods();
        $this->assertCount(1, $methodNodes);

        $this->assertTrue($methodNodes['innerDetail']->isStatic());
    }

    /**
     * @test
     */
    public function it_marks_required_args_without_types_as_not_optional()
    {
        $class = new \ReflectionClass(WithArguments::class);

        $mirror = new ClassMirror();

        $classNode = $mirror->reflect($class, array());
        $methodNode = $classNode->getMethod('methodWithoutTypeHints');
        $argNodes = $methodNode->getArguments();

        $this->assertCount(1, $argNodes);

        $this->assertEquals('arg', $argNodes[0]->getName());
        $this->assertNull($argNodes[0]->getTypeHint());
        $this->assertFalse($argNodes[0]->isOptional());
        $this->assertNull($argNodes[0]->getDefault());
        $this->assertFalse($argNodes[0]->isPassedByReference());
        $this->assertFalse($argNodes[0]->isVariadic());
    }

    /**
     * @test
     */
    public function it_properly_reads_methods_arguments_with_types()
    {
        $class = new \ReflectionClass(WithArguments::class);

        $mirror = new ClassMirror();

        $classNode = $mirror->reflect($class, array());
        $methodNode = $classNode->getMethod('methodWithArgs');
        $argNodes = $methodNode->getArguments();

        $this->assertCount(3, $argNodes);

        $this->assertEquals('arg_1', $argNodes[0]->getName());
        $this->assertEquals('array', $argNodes[0]->getTypeHint());
        $this->assertTrue($argNodes[0]->isOptional());
        $this->assertEquals(array(), $argNodes[0]->getDefault());
        $this->assertFalse($argNodes[0]->isPassedByReference());
        $this->assertFalse($argNodes[0]->isVariadic());

        $this->assertEquals('arg_2', $argNodes[1]->getName());
        $this->assertEquals(\ArrayAccess::class, $argNodes[1]->getTypeHint());
        $this->assertFalse($argNodes[1]->isOptional());

        $this->assertEquals('arg_3', $argNodes[2]->getName());
        $this->assertEquals(\ArrayAccess::class, $argNodes[2]->getTypeHint());
        $this->assertTrue($argNodes[2]->isOptional());
        $this->assertNull($argNodes[2]->getDefault());
        $this->assertFalse($argNodes[2]->isPassedByReference());
        $this->assertFalse($argNodes[2]->isVariadic());
    }

    /**
     * @test
     * @requires PHP 5.4
     */
    public function it_properly_reads_methods_arguments_with_callable_types()
    {
        $class = new \ReflectionClass(WithCallableArgument::class);

        $mirror = new ClassMirror();

        $classNode = $mirror->reflect($class, array());
        $methodNode = $classNode->getMethod('methodWithArgs');
        $argNodes = $methodNode->getArguments();

        $this->assertCount(2, $argNodes);

        $this->assertEquals('arg_1', $argNodes[0]->getName());
        $this->assertEquals('callable', $argNodes[0]->getTypeHint());
        $this->assertFalse($argNodes[0]->isOptional());
        $this->assertFalse($argNodes[0]->isPassedByReference());
        $this->assertFalse($argNodes[0]->isVariadic());

        $this->assertEquals('arg_2', $argNodes[1]->getName());
        $this->assertEquals('callable', $argNodes[1]->getTypeHint());
        $this->assertTrue($argNodes[1]->isOptional());
        $this->assertNull($argNodes[1]->getDefault());
        $this->assertFalse($argNodes[1]->isPassedByReference());
        $this->assertFalse($argNodes[1]->isVariadic());
    }

    /**
     * @test
     * @requires PHP 5.6
     */
    public function it_properly_reads_methods_variadic_arguments()
    {
        $class = new \ReflectionClass(WithVariadicArgument::class);

        $mirror = new ClassMirror();

        $classNode = $mirror->reflect($class, array());
        $methodNode = $classNode->getMethod('methodWithArgs');
        $argNodes = $methodNode->getArguments();

        $this->assertCount(1, $argNodes);

        $this->assertEquals('args', $argNodes[0]->getName());
        $this->assertNull($argNodes[0]->getTypeHint());
        $this->assertFalse($argNodes[0]->isOptional());
        $this->assertFalse($argNodes[0]->isPassedByReference());
        $this->assertTrue($argNodes[0]->isVariadic());
    }

    /**
     * @test
     * @requires PHP 5.6
     */
    public function it_properly_reads_methods_typehinted_variadic_arguments()
    {
        if (defined('HHVM_VERSION_ID')) {
            $this->markTestSkipped('HHVM does not support typehints on variadic arguments.');
        }

        $class = new \ReflectionClass(WithTypehintedVariadicArgument::class);

        $mirror = new ClassMirror();

        $classNode = $mirror->reflect($class, array());
        $methodNode = $classNode->getMethod('methodWithTypeHintedArgs');
        $argNodes = $methodNode->getArguments();

        $this->assertCount(1, $argNodes);

        $this->assertEquals('args', $argNodes[0]->getName());
        $this->assertEquals('array', $argNodes[0]->getTypeHint());
        $this->assertFalse($argNodes[0]->isOptional());
        $this->assertFalse($argNodes[0]->isPassedByReference());
        $this->assertTrue($argNodes[0]->isVariadic());
    }

    /**
     * @test
     */
    public function it_marks_passed_by_reference_args_as_passed_by_reference()
    {
        $class = new \ReflectionClass(WithReferences::class);

        $mirror = new ClassMirror();

        $classNode = $mirror->reflect($class, array());

        $this->assertTrue($classNode->hasMethod('methodWithReferenceArgument'));

        $argNodes = $classNode->getMethod('methodWithReferenceArgument')->getArguments();

        $this->assertCount(2, $argNodes);

        $this->assertTrue($argNodes[0]->isPassedByReference());
        $this->assertTrue($argNodes[1]->isPassedByReference());
    }

    /**
     * @test
     * @expectedException Prophecy\Exception\Doubler\ClassMirrorException
     */
    public function it_throws_an_exception_if_class_is_final()
    {
        $class = new \ReflectionClass(FinalClass::class);

        $mirror = new ClassMirror();

        $mirror->reflect($class, array());
    }

    /**
     * @test
     */
    public function it_ignores_final_methods()
    {
        $class = new \ReflectionClass(WithFinalMethod::class);

        $mirror = new ClassMirror();

        $classNode = $mirror->reflect($class, array());

        $this->assertCount(0, $classNode->getMethods());
    }

    /**
     * @test
     */
    public function it_marks_final_methods_as_unextendable()
    {
        $class = new \ReflectionClass(WithFinalMethod::class);

        $mirror = new ClassMirror();

        $classNode = $mirror->reflect($class, array());

        $this->assertCount(1, $classNode->getUnextendableMethods());
        $this->assertFalse($classNode->isExtendable('finalImplementation'));
    }

    /**
     * @test
     * @expectedException Prophecy\Exception\InvalidArgumentException
     */
    public function it_throws_an_exception_if_interface_provided_instead_of_class()
    {
        $class = new \ReflectionClass(EmptyInterface::class);

        $mirror = new ClassMirror();

        $mirror->reflect($class, array());
    }

    /**
     * @test
     */
    public function it_reflects_all_interfaces_methods()
    {
        $mirror = new ClassMirror();

        $classNode = $mirror->reflect(null, array(
            new \ReflectionClass(Named::class),
            new \ReflectionClass(ModifierInterface::class),
        ));

        $this->assertEquals(\stdClass::class, $classNode->getParentClass());
        $this->assertEquals(array(
            ReflectionInterface::class,
            ModifierInterface::class,
            Named::class,
        ), $classNode->getInterfaces());

        $this->assertCount(3, $classNode->getMethods());
        $this->assertTrue($classNode->hasMethod('getName'));
        $this->assertTrue($classNode->hasMethod('isAbstract'));
        $this->assertTrue($classNode->hasMethod('getVisibility'));
    }

    /**
     * @test
     */
    public function it_ignores_virtually_private_methods()
    {
        $class = new \ReflectionClass(WithVirtuallyPrivateMethod::class);

        $mirror = new ClassMirror();

        $classNode = $mirror->reflect($class, array());

        $this->assertCount(2, $classNode->getMethods());
        $this->assertTrue($classNode->hasMethod('isAbstract'));
        $this->assertTrue($classNode->hasMethod('__toString'));
        $this->assertFalse($classNode->hasMethod('_getName'));
    }

    /**
     * @test
     */
    public function it_does_not_throw_exception_for_virtually_private_finals()
    {
        $class = new \ReflectionClass(WithFinalVirtuallyPrivateMethod::class);

        $mirror = new ClassMirror();

        $classNode = $mirror->reflect($class, array());

        $this->assertCount(0, $classNode->getMethods());
    }

    /**
     * @test
     * @requires PHP 7
     */
    public function it_reflects_return_typehints()
    {
        $class = new \ReflectionClass(WithReturnTypehints::class);

        $mirror = new ClassMirror();

        $classNode = $mirror->reflect($class, array());

        $this->assertCount(3, $classNode->getMethods());
        $this->assertTrue($classNode->hasMethod('getName'));
        $this->assertTrue($classNode->hasMethod('getSelf'));
        $this->assertTrue($classNode->hasMethod('getParent'));

        $this->assertEquals('string', $classNode->getMethod('getName')->getReturnType());
        $this->assertEquals(WithReturnTypehints::class, $classNode->getMethod('getSelf')->getReturnType());
        $this->assertEquals(EmptyClass::class, $classNode->getMethod('getParent')->getReturnType());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_throws_an_exception_if_class_provided_in_interfaces_list()
    {
        $class = new \ReflectionClass(EmptyClass::class);

        $mirror = new ClassMirror();

        $mirror->reflect(null, array($class));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_throws_an_exception_if_not_reflection_provided_as_interface()
    {
        $mirror = new ClassMirror();

        $mirror->reflect(null, array(null));
    }

    /**
     * @test
     */
    public function it_doesnt_use_scalar_typehints()
    {
        $mirror = new ClassMirror();

        $classNode = $mirror->reflect(new \ReflectionClass(\ReflectionMethod::class), array());
        $method = $classNode->getMethod('export');
        $arguments = $method->getArguments();

        $this->assertNull($arguments[0]->getTypeHint());
        $this->assertNull($arguments[1]->getTypeHint());
        $this->assertNull($arguments[2]->getTypeHint());
    }

    /**
     * @test
     */
    public function it_doesnt_fail_to_typehint_nonexistent_FQCN()
    {
        $mirror = new ClassMirror();

        $classNode = $mirror->reflect(new \ReflectionClass(OptionalDepsClass::class), array());
        $method = $classNode->getMethod('iHaveAStrangeTypeHintedArg');
        $arguments = $method->getArguments();
        $this->assertEquals('I\Simply\Am\Nonexistent', $arguments[0]->getTypeHint());
    }

    /**
     * @test
     * @requires PHP 7.1
     */
    public function it_doesnt_fail_on_array_nullable_parameter_with_not_null_default_value()
    {
        $mirror = new ClassMirror();

        $classNode = $mirror->reflect(new \ReflectionClass(NullableArrayParameter::class), array());
        $method = $classNode->getMethod('iHaveNullableArrayParameterWithNotNullDefaultValue');
        $arguments = $method->getArguments();
        $this->assertSame('array', $arguments[0]->getTypeHint());
        $this->assertTrue($arguments[0]->isNullable());
    }

    /**
     * @test
     */
    public function it_doesnt_fail_to_typehint_nonexistent_RQCN()
    {
        $mirror = new ClassMirror();

        $classNode = $mirror->reflect(new \ReflectionClass(OptionalDepsClass::class), array());
        $method = $classNode->getMethod('iHaveAnEvenStrangerTypeHintedArg');
        $arguments = $method->getArguments();
        $this->assertEquals('I\Simply\Am\Not', $arguments[0]->getTypeHint());
    }

    /**
     * @test
     * @requires PHP 7.2
     */
    function it_doesnt_fail_when_method_is_extended_with_more_params()
    {
        $mirror = new ClassMirror();

        $classNode = $mirror->reflect(
            new \ReflectionClass(MethodWithAdditionalParam::class),
            array(new \ReflectionClass(Named::class))
        );
        $method = $classNode->getMethod('getName');
        $this->assertCount(1, $method->getArguments());

        $method = $classNode->getMethod('methodWithoutTypeHints');
        $this->assertCount(2, $method->getArguments());
    }

    /**
     * @test
     */
    function it_changes_argument_names_if_they_are_varying()
    {
        // Use test doubles in this test, as arguments named ... in the Reflection API can only happen for internal classes
        $class = $this->prophesize(\ReflectionClass::class);
        $method = $this->prophesize(\ReflectionMethod::class);
        $parameter = $this->prophesize(\ReflectionParameter::class);

        $class->getName()->willReturn('Custom\ClassName');
        $class->isInterface()->willReturn(false);
        $class->isFinal()->willReturn(false);
        $class->getMethods(\ReflectionMethod::IS_PUBLIC)->willReturn(array($method));
        $class->getMethods(\ReflectionMethod::IS_ABSTRACT)->willReturn(array());

        $method->getParameters()->willReturn(array($parameter));
        $method->getName()->willReturn('methodName');
        $method->isFinal()->willReturn(false);
        $method->isProtected()->willReturn(false);
        $method->isStatic()->willReturn(false);
        $method->returnsReference()->willReturn(false);

        if (version_compare(PHP_VERSION, '7.0', '>=')) {
            $method->hasReturnType()->willReturn(false);
        }

        $parameter->getName()->willReturn('...');
        $parameter->isDefaultValueAvailable()->willReturn(true);
        $parameter->getDefaultValue()->willReturn(null);
        $parameter->isPassedByReference()->willReturn(false);
        $parameter->allowsNull()->willReturn(true);
        $parameter->getClass()->willReturn($class);
        if (version_compare(PHP_VERSION, '5.6', '>=')) {
            $parameter->isVariadic()->willReturn(false);
        }

        $mirror = new ClassMirror();

        $classNode = $mirror->reflect($class->reveal(), array());

        $methodNodes = $classNode->getMethods();

        $argumentNodes = $methodNodes['methodName']->getArguments();
        $argumentNode = $argumentNodes[0];

        $this->assertEquals('__dot_dot_dot__', $argumentNode->getName());
    }
}
