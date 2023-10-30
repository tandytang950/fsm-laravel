<?php

namespace App\Dtos\FiniteStateMachine;

use App\Utils\Comparable;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * @property string $value
 */
class Input extends DataTransferObject implements Comparable
{
    public string $value;

    /**
     * @param DataTransferObject $another
     * @return bool
     */
    public function equals(DataTransferObject $another): bool {
        if (!$another instanceof Input) {
            return false;
        }

        return $this->value === $another->value;
    }
}
