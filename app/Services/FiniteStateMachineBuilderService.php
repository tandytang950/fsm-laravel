<?php

namespace App\Services;

use App\Contracts\FiniteStateMachineBuilderServiceInterface;
use App\Dtos\FiniteStateMachine;
use App\Dtos\FiniteStateMachine\FinalState;
use App\Dtos\FiniteStateMachine\Input;
use App\Dtos\FiniteStateMachine\State;
use App\Dtos\FiniteStateMachine\Transition;
use App\Exceptions\IllegalFsmBuilderFinalStateException;
use App\Exceptions\IllegalFsmBuilderInitialStateException;
use App\Exceptions\IllegalFsmBuilderInputException;
use App\Exceptions\IllegalFsmBuilderStateException;
use App\Exceptions\IllegalFsmBuilderTransitionException;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class FiniteStateMachineBuilderService implements FiniteStateMachineBuilderServiceInterface
{

    /**
     *
     * @param Collection<State> $states
     * @param Collection<Input> $inputs
     * @param State $initialState
     * @param Collection<FinalState> $finalStates
     * @param Collection<Transition> $transitions
     * @return FiniteStateMachine
     * @throws IllegalFsmBuilderFinalStateException
     * @throws IllegalFsmBuilderInitialStateException
     * @throws IllegalFsmBuilderInputException
     * @throws IllegalFsmBuilderStateException
     * @throws IllegalFsmBuilderTransitionException
     * @throws UnknownProperties
     */
    public function create(Collection $states, Collection $inputs,
                           State      $initialState, Collection $finalStates,
                           Collection $transitions): FiniteStateMachine
    {
        $this->validateStates($states);
        $this->validateInputs($inputs);
        $this->validateInitialState($states, $initialState);
        $this->validateFinalStates($states, $finalStates);
        $this->validateTransitions($states, $inputs, $transitions);

        return new FiniteStateMachine([
            'states' => $states,
            'inputs' => $inputs,
            'initialState' => $initialState,
            'finalStates' => $finalStates,
            'transitions' => $transitions,
        ]);
    }

    /**
     * @param Collection<State> $states
     * @throws IllegalFsmBuilderStateException
     */
    private function validateStates(Collection $states): void
    {
        foreach ($states->all() as $state) {
            if (!$state instanceof State) {
                throw new IllegalFsmBuilderStateException("Build finite state machine with invalid states");
            }
        }
    }

    /**
     * @param Collection<Input> $inputs
     * @throws IllegalFsmBuilderInputException
     */
    private function validateInputs(Collection $inputs): void
    {
        foreach ($inputs->all() as $input) {
            if (!$input instanceof Input) {
                throw new IllegalFsmBuilderInputException("Build finite state machine with invalid inputs");
            }
        }
    }

    /**
     * @param Collection<State> $states
     * @param State $initialState
     * @throws IllegalFsmBuilderInitialStateException
     */
    private function validateInitialState(Collection $states, State $initialState): void
    {
        if (!$states->contains($initialState)) {
            throw new IllegalFsmBuilderInitialStateException("Build finite state machine with invalid initial state");
        }
    }

    /**
     * @param Collection<State> $states
     * @param Collection<FinalState> $finalStates
     * @throws IllegalFsmBuilderFinalStateException
     */
    private function validateFinalStates(Collection $states, Collection $finalStates): void
    {
        foreach ($finalStates->all() as $finalState) {
            if (!$finalState instanceof FinalState || !$states->contains($finalState)) {
                throw new IllegalFsmBuilderFinalStateException("Build finite state machine with invalid final states");
            }
        }
    }

    /**
     * @param Collection<State> $states
     * @param Collection<Input> $inputs
     * @param Collection<Transition> $transitions
     * @throws IllegalFsmBuilderTransitionException
     */
    private function validateTransitions(Collection $states, Collection $inputs, Collection $transitions): void
    {
        foreach ($transitions->all() as $transition) {
            if (!$transition instanceof Transition) {
                throw new IllegalFsmBuilderTransitionException("Build finite state machine with invalid transitions");
            }

            if (!$states->contains($transition->fromState)) {
                throw new IllegalFsmBuilderTransitionException("Build finite state machine with invalid transitions of fromState");
            }

            if (!$states->contains($transition->toState)) {
                throw new IllegalFsmBuilderTransitionException("Build finite state machine with invalid transitions of toState");
            }

            if (!$inputs->contains($transition->input)) {
                throw new IllegalFsmBuilderTransitionException("Build finite state machine with invalid transitions of input");
            }
        }
    }
}
