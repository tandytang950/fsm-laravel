<?php

namespace App\Contracts;

use App\Dtos\FiniteStateMachine\Input;
use App\Dtos\FiniteStateMachine\State;
use App\Dtos\FiniteStateMachine\Transition;
use App\Exceptions\IllegalFsmProcessorTransitionException;
use Illuminate\Support\Collection;

interface FiniteStateMachineTransitionServiceInterface
{
    /**
     * @param Collection<Transition> $transitions
     * @param State $state
     * @param Input $input
     * @return Transition
     * @throws IllegalFsmProcessorTransitionException
     */
    public function findByStateAndInput(Collection $transitions, State $state, Input $input): Transition;
}
