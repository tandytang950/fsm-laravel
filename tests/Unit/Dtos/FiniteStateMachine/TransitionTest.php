<?php

namespace Tests\Unit\Dtos\FiniteStateMachine;

use App\Dtos\FiniteStateMachine\FinalState;
use App\Dtos\FiniteStateMachine\Input;
use App\Dtos\FiniteStateMachine\Output;
use App\Dtos\FiniteStateMachine\Transition;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Tests\TestCase;

/**
 * @group fsm
 */
class TransitionTest extends TestCase
{
    /**
     * @throws UnknownProperties
     */
    public function testConstructorSuccessfully(): void
    {
        $state = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $input = new Input(['value' => '0']);

        $transition = new Transition(['fromState' => $state, 'input' => $input, 'toState' => $state,]);

        $this->assertTrue($transition->fromState->equals($state));
        $this->assertTrue($transition->toState->equals($state));
        $this->assertTrue($transition->input->equals($input));
    }

    /**
     * @throws UnknownProperties
     */
    public function testConstructorWithTypeError()
    {
        $this->expectException(\TypeError::class);
        new Transition([
            'unknown' => null,
        ]);
    }

    /**
     * @throws UnknownProperties
     */
    public function testEqualsWithAnother() {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $state1 = new FinalState(['name' => 'S1', 'output' => new Output(['value' => '1'])]);

        $input0 = new Input(['value' => '0']);
        $input1 = new Input(['value' => '1']);

        $transition0 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]);
        $transition1 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]);
        $this->assertTrue($transition0->equals($transition1));
        $this->assertTrue($transition1->equals($transition0));

        $transition0 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]);
        $transition1 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state1,]);
        $this->assertFalse($transition0->equals($transition1));
        $this->assertFalse($transition1->equals($transition0));

        $transition0 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]);
        $transition1 = new Transition(['fromState' => $state0, 'input' => $input1, 'toState' => $state0,]);
        $this->assertFalse($transition0->equals($transition1));
        $this->assertFalse($transition1->equals($transition0));

        $transition0 = new Transition(['fromState' => $state1, 'input' => $input0, 'toState' => $state0,]);
        $transition1 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]);
        $this->assertFalse($transition0->equals($transition1));
        $this->assertFalse($transition1->equals($transition0));
    }
}
