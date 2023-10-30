<?php

namespace App\Dtos;

use App\Dtos\FiniteStateMachine\FinalState;
use App\Dtos\FiniteStateMachine\Input;
use App\Dtos\FiniteStateMachine\State;
use App\Dtos\FiniteStateMachine\Transition;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * @property Collection<State> $states
 * @property Collection<Input> $inputs
 * @property State $initialState
 * @property Collection<FinalState> $finalStates
 * @property Collection<Transition> $transitions
 */
class FiniteStateMachine extends DataTransferObject
{
    public Collection $states;
    public Collection $inputs;
    public State $initialState;
    public Collection $finalStates;
    public Collection $transitions;
}
