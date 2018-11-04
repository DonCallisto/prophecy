<?php

namespace spec\Prophecy;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use Prophecy\Argument\Token\AnyValueToken;
use Prophecy\Argument\Token\ApproximateValueToken;
use Prophecy\Argument\Token\ArrayCountToken;
use Prophecy\Argument\Token\ArrayEntryToken;
use Prophecy\Argument\Token\ArrayEveryEntryToken;
use Prophecy\Argument\Token\CallbackToken;
use Prophecy\Argument\Token\ExactValueToken;
use Prophecy\Argument\Token\IdenticalValueToken;
use Prophecy\Argument\Token\LogicalAndToken;
use Prophecy\Argument\Token\LogicalNotToken;
use Prophecy\Argument\Token\ObjectStateToken;
use Prophecy\Argument\Token\StringContainsToken;
use Prophecy\Argument\Token\TypeToken;

class ArgumentSpec extends ObjectBehavior
{
    function it_has_a_shortcut_for_exact_argument_token()
    {
        $token = $this->exact(42);
        $token->shouldBeAnInstanceOf(ExactValueToken::class);
        $token->getValue()->shouldReturn(42);
    }

    function it_has_a_shortcut_for_any_argument_token()
    {
        $token = $this->any();
        $token->shouldBeAnInstanceOf(AnyValueToken::class);
    }

    function it_has_a_shortcut_for_multiple_arguments_token()
    {
        $token = $this->cetera();
        $token->shouldBeAnInstanceOf(AnyValuesToken::class);
    }

    function it_has_a_shortcut_for_type_token()
    {
        $token = $this->type('integer');
        $token->shouldBeAnInstanceOf(TypeToken::class);
    }

    function it_has_a_shortcut_for_callback_token()
    {
        $token = $this->that('get_class');
        $token->shouldBeAnInstanceOf(CallbackToken::class);
    }

    function it_has_a_shortcut_for_object_state_token()
    {
        $token = $this->which('getName', 'everzet');
        $token->shouldBeAnInstanceOf(ObjectStateToken::class);
    }

    function it_has_a_shortcut_for_logical_and_token()
    {
        $token = $this->allOf('integer', 5);
        $token->shouldBeAnInstanceOf(LogicalAndToken::class);
    }

    function it_has_a_shortcut_for_array_count_token()
    {
        $token = $this->size(5);
        $token->shouldBeAnInstanceOf(ArrayCountToken::class);
    }

    function it_has_a_shortcut_for_array_entry_token()
    {
        $token = $this->withEntry('key', 'value');
        $token->shouldBeAnInstanceOf(ArrayEntryToken::class);
    }

    function it_has_a_shortcut_for_array_every_entry_token()
    {
        $token = $this->withEveryEntry('value');
        $token->shouldBeAnInstanceOf(ArrayEveryEntryToken::class);
    }

    function it_has_a_shortcut_for_identical_value_token()
    {
        $token = $this->is('value');
        $token->shouldBeAnInstanceOf(IdenticalValueToken::class);
    }

    function it_has_a_shortcut_for_array_entry_token_matching_any_key()
    {
        $token = $this->containing('value');
        $token->shouldBeAnInstanceOf(ArrayEntryToken::class);
        $token->getKey()->shouldHaveType(AnyValuesToken::class);
    }

    function it_has_a_shortcut_for_array_entry_token_matching_any_value()
    {
        $token = $this->withKey('key');
        $token->shouldBeAnInstanceOf(ArrayEntryToken::class);
        $token->getValue()->shouldHaveType(AnyValueToken::class);
    }

    function it_has_a_shortcut_for_logical_not_token()
    {
        $token = $this->not('kagux');
        $token->shouldBeAnInstanceOf(LogicalNotToken::class);
    }

    function it_has_a_shortcut_for_string_contains_token()
    {
        $token = $this->containingString('string');
        $token->shouldBeAnInstanceOf(StringContainsToken::class);
    }

    function it_has_a_shortcut_for_approximate_token()
    {
        $token = $this->approximate(10);
        $token->shouldBeAnInstanceOf(ApproximateValueToken::class);
    }
}
