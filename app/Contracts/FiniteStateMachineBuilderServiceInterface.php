<?php

namespace App\Contracts;

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

interface FiniteStateMachineBuilderServiceInterface
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
                           Collection $transitions): FiniteStateMachine;
}
