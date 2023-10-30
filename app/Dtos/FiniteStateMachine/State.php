<?php

namespace App\Dtos\FiniteStateMachine;

use App\Utils\Comparable;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * @property string $name
 */
class State extends DataTransferObject implements Comparable
{
    public string $name;

    /**
     * @param DataTransferObject $another
     * @return bool
     */
    public function equals(DataTransferObject $another): bool
    {
        if (get_class($another) != get_class($this)) {
            return false;
        }

        /**
         * @var State $another
         */
        return $this->name === $another->name;
    }
}
