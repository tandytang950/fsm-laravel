<?php

namespace App\Utils;

use Spatie\DataTransferObject\DataTransferObject;

interface Comparable
{
    /**
     * @param DataTransferObject $another
     * @return bool
     */
    public function equals(DataTransferObject $another): bool;
}
