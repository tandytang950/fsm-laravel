<?php

namespace App\Services;

use App\Contracts\FiniteStateMachineTransitionServiceInterface;
use App\Dtos\FiniteStateMachine\Input;
use App\Dtos\FiniteStateMachine\State;
use App\Dtos\FiniteStateMachine\Transition;
use App\Exceptions\IllegalFsmProcessorTransitionException;
use Illuminate\Support\Collection;

class FiniteStateMachineTransitionService implements FiniteStateMachineTransitionServiceInterface
{
    /**
     * @param Collection<Transition> $transitions
     * @param State $state
     * @param Input $input
     * @return Transition
     * @throws IllegalFsmProcessorTransitionException
     */
    public function findByStateAndInput(Collection $transitions, State $state, Input $input): Transition {

        $foundTransition = null;

        /**
         * @var Transition $transition
         */
        foreach ($transitions->all() as $transition) {
            if ($transition->fromState->equals($state) && $transition->input->equals($input)) {
                if (!is_null($foundTransition)) {
                    throw new IllegalFsmProcessorTransitionException("found duplicate transition with current state and input");
                }
                $foundTransition = $transition;
            }
        }

        if (is_null($foundTransition)) {
            throw new IllegalFsmProcessorTransitionException("Can't find transition with current state and input");
        }

        return $foundTransition;
    }
}
