<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait HasSlug
{

    public function getSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        // Check for existing slugs and append a number if necessary
        $count = $this->newQuery()->where('slug', $slug)->where('id', '<>', $id)->count();
        if ($count > 0) {
            $slug .= '-' . $count;
        }

        return $slug;
    }
}
