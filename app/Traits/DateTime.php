<?php

namespace App\Traits;

use Carbon\Carbon;

trait DateTime
{
    public function now()
    {
        return Carbon::now();
    }
}
