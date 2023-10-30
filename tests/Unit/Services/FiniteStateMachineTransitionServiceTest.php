<?php

namespace Tests\Unit\Services;

use App\Contracts\FiniteStateMachineTransitionServiceInterface;
use App\Dtos\FiniteStateMachine\FinalState;
use App\Dtos\FiniteStateMachine\Input;
use App\Dtos\FiniteStateMachine\Output;
use App\Dtos\FiniteStateMachine\Transition;
use App\Exceptions\IllegalFsmProcessorTransitionException;
use Tests\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

/**
 * @group fsm
 */
class FiniteStateMachineTransitionServiceTest extends TestCase
{
    /**
     * @throws UnknownProperties
     * @throws IllegalFsmProcessorTransitionException
     */
    public function testFindByStateAndInputSuccessfully(): void
    {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $state1 = new FinalState(['name' => 'S1', 'output' => new Output(['value' => '1'])]);
        $state2 = new FinalState(['name' => 'S2', 'output' => new Output(['value' => '2'])]);

        $input0 = new Input(['value' => '0']);
        $input1 = new Input(['value' => '1']);

        $transition0 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]);
        $transition1 = new Transition(['fromState' => $state0, 'input' => $input1, 'toState' => $state1,]);
        $transition2 = new Transition(['fromState' => $state1, 'input' => $input0, 'toState' => $state2,]);
        $transition3 = new Transition(['fromState' => $state1, 'input' => $input1, 'toState' => $state0,]);
        $transition4 = new Transition(['fromState' => $state2, 'input' => $input0, 'toState' => $state1,]);
        $transition5 = new Transition(['fromState' => $state2, 'input' => $input1, 'toState' => $state2,]);

        $transitions = collect([
            $transition0, $transition1, $transition2, $transition3, $transition4, $transition5,
        ]);

        /**
         * @var FiniteStateMachineTransitionServiceInterface $transitionService
         */
        $transitionService = app(FiniteStateMachineTransitionServiceInterface::class);

        $foundTransition = $transitionService->findByStateAndInput($transitions, $state0, $input0);
        $this->assertEquals($transition0, $foundTransition);

        $foundTransition = $transitionService->findByStateAndInput($transitions, $state0, $input1);
        $this->assertEquals($transition1, $foundTransition);

        $foundTransition = $transitionService->findByStateAndInput($transitions, $state1, $input0);
        $this->assertEquals($transition2, $foundTransition);

        $foundTransition = $transitionService->findByStateAndInput($transitions, $state1, $input1);
        $this->assertEquals($transition3, $foundTransition);

        $foundTransition = $transitionService->findByStateAndInput($transitions, $state2, $input0);
        $this->assertEquals($transition4, $foundTransition);

        $foundTransition = $transitionService->findByStateAndInput($transitions, $state2, $input1);
        $this->assertEquals($transition5, $foundTransition);
    }

    /**
     * @throws UnknownProperties
     */
    public function testFindByStateAndInputWithDuplication(): void {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $state1 = new FinalState(['name' => 'S1', 'output' => new Output(['value' => '1'])]);

        $input0 = new Input(['value' => '0']);

        $transition0 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]);
        $transition1 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state1,]);

        $transitions = collect([
            $transition0, $transition1
        ]);

        try {
            /**
             * @var FiniteStateMachineTransitionServiceInterface $transitionService
             */
            $transitionService = app(FiniteStateMachineTransitionServiceInterface::class);
            $transitionService->findByStateAndInput($transitions, $state0, $input0);
            $this->fail();
        } catch (IllegalFsmProcessorTransitionException $e) {
            $this->assertTrue(true);
        }
    }


    /**
     * @throws UnknownProperties
     */
    public function testFindByStateAndInputWithNotFound(): void {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $input0 = new Input(['value' => '0']);
        $transitions = collect([]);

        try {
            /**
             * @var FiniteStateMachineTransitionServiceInterface $transitionService
             */
            $transitionService = app(FiniteStateMachineTransitionServiceInterface::class);
            $transitionService->findByStateAndInput($transitions, $state0, $input0);
            $this->fail();
        } catch (IllegalFsmProcessorTransitionException $e) {
            $this->assertTrue(true);
        }
    }

}
