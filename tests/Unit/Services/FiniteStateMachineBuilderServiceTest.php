<?php

namespace Tests\Unit\Services;

use App\Contracts\FiniteStateMachineBuilderServiceInterface;
use App\Dtos\FiniteStateMachine\FinalState;
use App\Dtos\FiniteStateMachine\Input;
use App\Dtos\FiniteStateMachine\Output;
use App\Dtos\FiniteStateMachine\State;
use App\Dtos\FiniteStateMachine\Transition;
use App\Exceptions\IllegalFsmBuilderFinalStateException;
use App\Exceptions\IllegalFsmBuilderInitialStateException;
use App\Exceptions\IllegalFsmBuilderInputException;
use App\Exceptions\IllegalFsmBuilderStateException;
use App\Exceptions\IllegalFsmBuilderTransitionException;
use Tests\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

/**
 * @group fsm
 */
class FiniteStateMachineBuilderServiceTest extends TestCase
{
    /**
     * @throws IllegalFsmBuilderFinalStateException
     * @throws IllegalFsmBuilderInitialStateException
     * @throws IllegalFsmBuilderInputException
     * @throws IllegalFsmBuilderStateException
     * @throws IllegalFsmBuilderTransitionException
     * @throws UnknownProperties
     */
    public function testCreateSuccessfully(): void
    {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $input0 = new Input(['value' => '0']);
        $transition0 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]);

        /**
         * @var FiniteStateMachineBuilderServiceInterface $builderService
         */
        $builderService = app(FiniteStateMachineBuilderServiceInterface::class);
        $builderService->create(
            collect([$state0]), collect([$input0]), $state0, collect([$state0]), collect([$transition0])
        );
        $this->assertTrue(true);
    }

    /**
     * @throws IllegalFsmBuilderFinalStateException
     * @throws IllegalFsmBuilderInitialStateException
     * @throws IllegalFsmBuilderInputException
     * @throws IllegalFsmBuilderStateException
     * @throws IllegalFsmBuilderTransitionException
     * @throws UnknownProperties
     */
    public function testCreateWithInvalidStates(): void {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $state1 = new \stdClass();

        $input0 = new Input(['value' => '0']);
        $transition0 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]);

        /**
         * @var FiniteStateMachineBuilderServiceInterface $builderService
         */
        $builderService = app(FiniteStateMachineBuilderServiceInterface::class);

        $this->expectException(IllegalFsmBuilderStateException::class);
        $builderService->create(
            collect([$state0, $state1]), collect([$input0]), $state0, collect([$state0]), collect([$transition0])
        );
    }

    /**
     * @throws IllegalFsmBuilderFinalStateException
     * @throws IllegalFsmBuilderInitialStateException
     * @throws IllegalFsmBuilderInputException
     * @throws IllegalFsmBuilderStateException
     * @throws IllegalFsmBuilderTransitionException
     * @throws UnknownProperties
     */
    public function testCreateWithInvalidInputs(): void {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $input0 = new Input(['value' => '0']);
        $input1 = new \stdClass();
        $transition0 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]);

        /**
         * @var FiniteStateMachineBuilderServiceInterface $builderService
         */
        $builderService = app(FiniteStateMachineBuilderServiceInterface::class);

        $this->expectException(IllegalFsmBuilderInputException::class);
        $builderService->create(
            collect([$state0]), collect([$input0, $input1]), $state0, collect([$state0]), collect([$transition0])
        );
    }


    /**
     * @throws IllegalFsmBuilderFinalStateException
     * @throws IllegalFsmBuilderInitialStateException
     * @throws IllegalFsmBuilderInputException
     * @throws IllegalFsmBuilderStateException
     * @throws IllegalFsmBuilderTransitionException
     * @throws UnknownProperties
     */
    public function testCreateWithInvalidInitialState(): void {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $state1 = new State(['name' => 'S1']);
        $input0 = new Input(['value' => '0']);
        $transition0 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]);

        /**
         * @var FiniteStateMachineBuilderServiceInterface $builderService
         */
        $builderService = app(FiniteStateMachineBuilderServiceInterface::class);

        $this->expectException(IllegalFsmBuilderInitialStateException::class);
        $builderService->create(
            collect([$state0]), collect([$input0]), $state1, collect([$state0]), collect([$transition0])
        );
    }


    /**
     * @throws IllegalFsmBuilderInitialStateException
     * @throws IllegalFsmBuilderInputException
     * @throws IllegalFsmBuilderStateException
     * @throws IllegalFsmBuilderTransitionException
     * @throws UnknownProperties
     */
    public function testCreateWithInvalidFinalState(): void {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $state1 = new State(['name' => 'S1']);
        $state2 = new State(['name' => 'S2']);
        $input0 = new Input(['value' => '0']);
        $transition0 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]);

        /**
         * @var FiniteStateMachineBuilderServiceInterface $builderService
         */
        $builderService = app(FiniteStateMachineBuilderServiceInterface::class);

        $this->expectException(IllegalFsmBuilderFinalStateException::class);
        $builderService->create(
            collect([$state0, $state1]), collect([$input0]), $state1, collect([$state1]), collect([$transition0])
        );

        $this->expectException(IllegalFsmBuilderFinalStateException::class);
        $builderService->create(
            collect([$state0, $state1]), collect([$input0]), $state1, collect([$state2]), collect([$transition0])
        );
    }

    /**
     * @throws IllegalFsmBuilderFinalStateException
     * @throws IllegalFsmBuilderInitialStateException
     * @throws IllegalFsmBuilderInputException
     * @throws IllegalFsmBuilderStateException
     * @throws IllegalFsmBuilderTransitionException
     * @throws UnknownProperties
     */
    public function testCreateWithInvalidTransition(): void {
        $state0 = new FinalState(['name' => 'S0', 'output' => new Output(['value' => '0'])]);
        $state1 = new FinalState(['name' => 'S1', 'output' => new Output(['value' => '1'])]);
        $input0 = new Input(['value' => '0']);
        $input1 = new Input(['value' => '1']);
        $transition0 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state0,]);
        $transition1 = new \stdClass();
        $transition2 = new Transition(['fromState' => $state1, 'input' => $input0, 'toState' => $state0,]);
        $transition3 = new Transition(['fromState' => $state0, 'input' => $input0, 'toState' => $state1,]);
        $transition4 = new Transition(['fromState' => $state0, 'input' => $input1, 'toState' => $state0]);

        /**
         * @var FiniteStateMachineBuilderServiceInterface $builderService
         */
        $builderService = app(FiniteStateMachineBuilderServiceInterface::class);

        //invalid transition
        $this->expectException(IllegalFsmBuilderTransitionException::class);
        $builderService->create(
            collect([$state0]), collect([$input0]), $state0, collect([$state0]), collect([$transition0, $transition1])
        );

        //invalid transition of fromState
        $this->expectException(IllegalFsmBuilderTransitionException::class);
        $builderService->create(
            collect([$state0]), collect([$input0]), $state0, collect([$state0]), collect([$transition0, $transition2])
        );

        //invalid transition of toState
        $this->expectException(IllegalFsmBuilderTransitionException::class);
        $builderService->create(
            collect([$state0]), collect([$input0]), $state0, collect([$state0]), collect([$transition0, $transition3])
        );

        //invalid transition of input
        $this->expectException(IllegalFsmBuilderTransitionException::class);
        $builderService->create(
            collect([$state0]), collect([$input0]), $state0, collect([$state0]), collect([$transition0, $transition4])
        );
    }
}
