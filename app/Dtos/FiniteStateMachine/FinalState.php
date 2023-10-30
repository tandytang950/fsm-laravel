<?php

namespace App\Dtos\FiniteStateMachine;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * @property Output $output
 */
class FinalState extends State
{
    public Output $output;

    /**
     * @param DataTransferObject $another
     * @return bool
     */
    public function equals(DataTransferObject $another): bool
    {
        if (!$another instanceof FinalState) {
            return false;
        }

        return parent::equals($another)
            && $this->output->equals($another->output);
    }
}
