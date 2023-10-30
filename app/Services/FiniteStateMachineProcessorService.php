<?php

namespace App\Services;

use App\Contracts\FiniteStateMachineProcessorServiceInterface;
use App\Contracts\FiniteStateMachineTransitionServiceInterface;
use App\Dtos\FiniteStateMachine;
use App\Dtos\FiniteStateMachine\FinalState;
use App\Dtos\FiniteStateMachine\Input;
use App\Dtos\FiniteStateMachine\State;
use App\Exceptions\IllegalFsmProcessorCurrentStateException;
use App\Exceptions\IllegalFsmProcessorFinalStateException;
use App\Exceptions\IllegalFsmProcessorInitialStateException;
use App\Exceptions\IllegalFsmProcessorInputException;
use App\Exceptions\IllegalFsmProcessorTransitionException;
use Illuminate\Support\Collection;

class FiniteStateMachineProcessorService implements FiniteStateMachineProcessorServiceInterface
{
    private FiniteStateMachineTransitionServiceInterface $transitionService;

    public function __construct(FiniteStateMachineTransitionServiceInterface $transitionService) {
        $this->transitionService = $transitionService;
    }

    /**
     * @param FiniteStateMachine $finiteStateMachine
     * @param State $currentState
     * @param Collection $inputs
     * @return FinalState
     * @throws IllegalFsmProcessorCurrentStateException
     * @throws IllegalFsmProcessorFinalStateException
     * @throws IllegalFsmProcessorInitialStateException
     * @throws IllegalFsmProcessorInputException
     * @throws IllegalFsmProcessorTransitionException
     */
    public function processList(FiniteStateMachine $finiteStateMachine, State $currentState, Collection $inputs): FinalState {

        if (!$currentState->equals($finiteStateMachine->initialState)) {
            throw new IllegalFsmProcessorInitialStateException("initial state is invalid");
        }

        $state = $currentState;

        /**
         * @var Input $input
         */
        foreach ($inputs->all() as $input) {
            $state = $this->process($finiteStateMachine, $state, $input);
        }

        if (!$finiteStateMachine->finalStates->contains($state)) {
            throw new IllegalFsmProcessorFinalStateException("Inputs lead to an invalid final state");
        }

        if (!$state instanceof FinalState) {
            throw new \TypeError("Expected state to be instance of FinalState, got " . get_class($state));
        }

        return $state;
    }

    /**
     * @param FiniteStateMachine $finiteStateMachine
     * @param State $currentState
     * @param Input $input
     * @return State
     * @throws IllegalFsmProcessorInputException
     * @throws IllegalFsmProcessorCurrentStateException
     * @throws IllegalFsmProcessorTransitionException
     */
    public function process(FiniteStateMachine $finiteStateMachine, State $currentState, Input $input): State {
        if (!$finiteStateMachine->inputs->contains($input)) {
            throw new IllegalFsmProcessorInputException('Invalid input');
        }

        if (!$finiteStateMachine->states->contains($currentState)) {
            throw new IllegalFsmProcessorCurrentStateException('Invalid current state');
        }

        $transition = $this->transitionService->findByStateAndInput($finiteStateMachine->transitions, $currentState, $input);

        return $transition->toState;
    }

}
