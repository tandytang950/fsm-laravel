<?php

namespace Tests\Unit\Services;

use App\Contracts\FiniteStateMachineBuilderServiceInterface;
use App\Contracts\FiniteStateMachineProcessorServiceInterface;
use App\Dtos\FiniteStateMachine;
use App\Dtos\FiniteStateMachine\FinalState;
use App\Dtos\FiniteStateMachine\Input;
use App\Dtos\FiniteStateMachine\Output;
use App\Dtos\FiniteStateMachine\State;
use App\Dtos\FiniteStateMachine\Transition;
use App\Exceptions\IllegalArgumentException;
use App\Exceptions\IllegalFsmBuilderFinalStateException;
use App\Exceptions\IllegalFsmBuilderInitialStateException;
use App\Exceptions\IllegalFsmBuilderInputException;
use App\Exceptions\IllegalFsmBuilderStateException;
use App\Exceptions\IllegalFsmBuilderTransitionException;
use App\Exceptions\IllegalFsmProcessorCurrentStateException;
use App\Exceptions\IllegalFsmProcessorFinalStateException;
use App\Exceptions\IllegalFsmProcessorInitialStateException;
use App\Exceptions\IllegalFsmProcessorInputException;
use App\Exceptions\IllegalFsmProcessorTransitionException;
use App\Services\FiniteStateMachineProcessorService;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

/**
 * @group fsm
 */
class FiniteStateMachineProcessorServiceTest extends TestCase
{
    /**
     * @dataProvider addFiniteStateMachinesExamples
     */
    public function testValidProcessList(array $fsmArray, State $initialState, Collection $inputs, FinalState $expectFinalState): void
    {
        /**
         * @var FiniteStateMachineBuilderServiceInterface $builderService
         */
        $builderService = app(FiniteStateMachineBuilderServiceInterface::class);

        $finiteStateMachine = $builderService->create(
            $fsmArray['states'],
            $fsmArray['inputs'],
            $fsmArray['initialState'],
            $fsmArray['finalStates'],
            $fsmArray['transitions'],
        );

        /**
         * @var FiniteStateMachineProcessorServiceInterface $finiteStateMachineService
         */
        $finiteStateMachineService = app(FiniteStateMachineProcessorServiceInterface::class);
        $finalState = $finiteStateMachineService->processList($finiteStateMachine, $initialState, $inputs);
        $this->assertEquals($expectFinalState, $finalState);
    }

    /**
     * Mod-Three FA
     * Based on the notation from the definition, our modulo three FSM would be configured as
     *
     * follows:
     * Q = (S0, S1, S2)
     * Σ = (0, 1)
     * q0 = S0
     * F = (S0, S1, S2)
     * δ(S0,0) = S0; δ(S0,1) = S1; δ(S1,0) = S2; δ(S1,1) = S0; δ(S2,0) = S1; δ(S2,1) = S2
     *
     * Test Case 1: 110 and output S0 = 0
     * Test Case 2: 1010 and output S1 = 1
     *
     * @return array
     * @throws UnknownProperties
     */
    public static function addFiniteStateMachinesExamples(): array
    {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $state1 = new FinalState(['name' => 'S1', 'output' => new Output(['value' => '1'])]);
        $state2 = new FinalState(['name' => 'S2', 'output' => new Output(['value' => '2'])]);

        $input0 = new Input(['value' => '0']);
        $input1 = new Input(['value' => '1']);

        $transitions = collect([
            new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]),
            new Transition(['fromState' => $state0, 'input' => $input1, 'toState' => $state1,]),
            new Transition(['fromState' => $state1, 'input' => $input0, 'toState' => $state2,]),
            new Transition(['fromState' => $state1, 'input' => $input1, 'toState' => $state0,]),
            new Transition(['fromState' => $state2, 'input' => $input0, 'toState' => $state1,]),
            new Transition(['fromState' => $state2, 'input' => $input1, 'toState' => $state2,]),
        ]);


        return [
            'input 110 output S0 = 0' => [
                'finiteStateMachine' => [
                    'states' => collect([$state0, $state1, $state2]),
                    'inputs' => collect([$input0, $input1]),
                    'initialState' => $state0,
                    'finalStates' => collect([$state0, $state1, $state2]),
                    'transitions' => $transitions,
                ],
                'initialState' => $state0,
                'inputs' => collect([$input1, $input1, $input0]), //110
                'expectFinalState' => $state0,
            ],
            'input 1010 output S1 = 1' => [
                'finiteStateMachine' => [
                    'states' => collect([$state0, $state1, $state2]),
                    'inputs' => collect([$input0, $input1]),
                    'initialState' => $state0,
                    'finalStates' => collect([$state0, $state1, $state2]),
                    'transitions' => $transitions,
                ],
                'initialState' => $state0,
                'inputs' => collect([$input1, $input0, $input1, $input0]), //1010
                'expectFinalState' => $state1,
            ],
        ];
    }

    /**
     * @throws IllegalFsmBuilderFinalStateException
     * @throws IllegalFsmBuilderInitialStateException
     * @throws IllegalFsmBuilderInputException
     * @throws IllegalFsmBuilderStateException
     * @throws IllegalFsmBuilderTransitionException
     * @throws UnknownProperties
     */
    public function testProcessWithInvalidInput(): void {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $input0 = new Input(['value' => '0']);
        $input1 = new Input(['value' => '1']);

        /**
         * @var FiniteStateMachineBuilderServiceInterface $builderService
         */
        $builderService = app(FiniteStateMachineBuilderServiceInterface::class);

        $finiteStateMachine = $builderService->create(
            collect([$state0]),
            collect([$input0]),
            $state0,
            collect([$state0]),
            collect([]),
        );

        /**
         * @var FiniteStateMachineProcessorServiceInterface $finiteStateMachineService
         */
        $finiteStateMachineService = app(FiniteStateMachineProcessorServiceInterface::class);

        try {
            $finiteStateMachineService->process($finiteStateMachine, $state0, $input1);
            $this->fail();
        } catch (IllegalFsmProcessorInputException $e) {
            $this->assertTrue(true);
        } catch (IllegalArgumentException $e) {
            $this->fail();
        }
    }

    /**
     * @throws IllegalFsmBuilderFinalStateException
     * @throws IllegalFsmBuilderInitialStateException
     * @throws IllegalFsmBuilderInputException
     * @throws IllegalFsmBuilderStateException
     * @throws IllegalFsmBuilderTransitionException
     * @throws UnknownProperties
     */
    public function testProcessWithInvalidCurrentState(): void {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $state1 = new FinalState(['name' => 'S1', 'output' => new Output(['value' => '1'])]);
        $input0 = new Input(['value' => '0']);

        /**
         * @var FiniteStateMachineBuilderServiceInterface $builderService
         */
        $builderService = app(FiniteStateMachineBuilderServiceInterface::class);

        $finiteStateMachine = $builderService->create(
            collect([$state0]),
            collect([$input0]),
            $state0,
            collect([$state0]),
            collect([]),
        );

        /**
         * @var FiniteStateMachineProcessorService $finiteStateMachineService
         */
        $finiteStateMachineService = app(FiniteStateMachineProcessorService::class);
        try {
            $finiteStateMachineService->process($finiteStateMachine, $state1, $input0);
            $this->fail();
        } catch (IllegalFsmProcessorCurrentStateException $e) {
            $this->assertTrue(true);
        } catch (IllegalArgumentException $e) {
            $this->fail();
        }
    }

    /**
     * @throws IllegalFsmBuilderFinalStateException
     * @throws IllegalFsmBuilderInitialStateException
     * @throws IllegalFsmBuilderInputException
     * @throws IllegalFsmBuilderStateException
     * @throws IllegalFsmBuilderTransitionException
     * @throws UnknownProperties
     */
    public function testProcessWithInvalidTransition(): void {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $input0 = new Input(['value' => '0']);

        /**
         * @var FiniteStateMachineBuilderServiceInterface $builderService
         */
        $builderService = app(FiniteStateMachineBuilderServiceInterface::class);

        $finiteStateMachine = $builderService->create(
            collect([$state0]),
            collect([$input0]),
            $state0,
            collect([$state0]),
            collect([]),
        );

        /**
         * @var FiniteStateMachineProcessorServiceInterface $finiteStateMachineService
         */
        $finiteStateMachineService = app(FiniteStateMachineProcessorServiceInterface::class);
        try {
            $finiteStateMachineService->process($finiteStateMachine, $state0, $input0);
            $this->fail();
        } catch (IllegalFsmProcessorTransitionException $e) {
            $this->assertTrue(true);
        } catch (IllegalArgumentException $e) {
            $this->fail();
        }
    }

    /**
     * @throws IllegalFsmBuilderFinalStateException
     * @throws IllegalFsmBuilderInitialStateException
     * @throws IllegalFsmBuilderInputException
     * @throws IllegalFsmBuilderStateException
     * @throws IllegalFsmBuilderTransitionException
     * @throws UnknownProperties
     */
    public function testProcessListWithInvalidInitialState(): void {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $state1 = new FinalState(['name' => 'S1', 'output' => new Output(['value' => '1'])]);

        $input0 = new Input(['value' => '0']);

        /**
         * @var FiniteStateMachineBuilderServiceInterface $builderService
         */
        $builderService = app(FiniteStateMachineBuilderServiceInterface::class);

        $finiteStateMachine = $builderService->create(
            collect([$state0]),
            collect([$input0]),
            $state0,
            collect([$state0]),
            collect([]),
        );

        /**
         * @var FiniteStateMachineProcessorServiceInterface $finiteStateMachineService
         */
        $finiteStateMachineService = app(FiniteStateMachineProcessorServiceInterface::class);
        try {
            $finiteStateMachineService->processList($finiteStateMachine, $state1, collect([$input0]));
            $this->fail();
        } catch (IllegalFsmProcessorInitialStateException $e) {
            $this->assertTrue(true);
        } catch (IllegalArgumentException $e) {
            $this->fail();
        }
    }

    /**
     * @throws UnknownProperties
     */
    public function testProcessListWithInvalidFinalState(): void {
        $state0 = new State(['name' => 'S0']);
        $state1 = new FinalState(['name' => 'S1', 'output' => new Output(['value' => '1'])]);

        $input0 = new Input(['value' => '0']);

        $transitions = collect([
            new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]),
        ]);

        $finiteStateMachine = new FiniteStateMachine([
            'states' => collect([$state0]),
            'inputs' => collect([$input0]),
            'initialState' => $state0,
            'finalStates' => collect([$state1]),
            'transitions' => $transitions,
        ]);

        /**
         * @var FiniteStateMachineProcessorServiceInterface $finiteStateMachineService
         */
        $finiteStateMachineService = app(FiniteStateMachineProcessorServiceInterface::class);
        try {
            $finiteStateMachineService->processList($finiteStateMachine, $state0, collect([$input0]));
            $this->fail();
        } catch (IllegalFsmProcessorFinalStateException $e) {
            $this->assertTrue(true);
        } catch (IllegalArgumentException $e) {
            $this->fail();
        }
    }

}
