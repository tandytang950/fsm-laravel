<?php

namespace Tests\Unit\Dtos;

use App\Dtos\FiniteStateMachine;
use App\Dtos\FiniteStateMachine\State;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Tests\TestCase;

/**
 * @group fsm
 */
class FiniteStateMachineTest extends TestCase
{
    /**
     * @throws UnknownProperties
     */
    public function testConstructorSuccessfully(): void
    {
        $state0 = new State([
            'name' => 'S0'
        ]);

        $fsm = new FiniteStateMachine([
            'states' => collect([$state0]),
            'inputs' => collect([]),
            'initialState' => $state0,
            'finalStates' => collect([]),
            'transitions' => collect([]),
        ]);

        $this->assertEquals(collect([$state0]), $fsm->states);
        $this->assertEquals(collect([]), $fsm->inputs);
        $this->assertEquals($state0, $fsm->initialState);
        $this->assertEquals(collect([]), $fsm->finalStates);
        $this->assertEquals(collect([]), $fsm->transitions);
    }

    /**
     * @throws UnknownProperties
     */
    public function testConstructorWithTypeError()
    {
        $this->expectException(\TypeError::class);
        new FiniteStateMachine([
            'abc' => collect([]),
        ]);
    }
}
