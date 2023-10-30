<?php

namespace App\Contracts;

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

interface FiniteStateMachineProcessorServiceInterface
{
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
    public function processList(FiniteStateMachine $finiteStateMachine, State $currentState,
                                Collection $inputs): FinalState;

    /**
     * @param FiniteStateMachine $finiteStateMachine
     * @param State $currentState
     * @param Input $input
     * @return State
     * @throws IllegalFsmProcessorInputException
     * @throws IllegalFsmProcessorCurrentStateException
     * @throws IllegalFsmProcessorTransitionException
     */
    public function process(FiniteStateMachine $finiteStateMachine, State $currentState, Input $input): State;
}
