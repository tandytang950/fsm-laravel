<?php

namespace App\Dtos\FiniteStateMachine;

use App\Utils\Comparable;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * @property State $fromState
 * @property Input $input
 * @property State $toState
 */
class Transition extends DataTransferObject implements Comparable
{
    public State $fromState;
    public Input $input;
    public State $toState;

    /**
     * @param Transition $another
     * @return bool
     */
    public function equals(DataTransferObject $another): bool {
        if (!$another instanceof Transition) {
            return false;
        }

        return $this->fromState->equals($another->fromState)
            && $this->input->equals($another->input)
            && $this->toState->equals($another->toState);
    }
}
