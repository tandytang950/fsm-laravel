<?php

namespace App\Dtos\FiniteStateMachine;

use App\Utils\Comparable;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * @property string $value
 */
class Output extends DataTransferObject implements Comparable
{
    public string $value;

    /**
     * @param DataTransferObject $another
     * @return bool
     */
    public function equals(DataTransferObject $another): bool {
        if (!$another instanceof Output) {
            return false;
        }

        return $this->value === $another->value;
    }
}
