<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'description', 'price', 'plan_id'];


    public function features()
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function plan()
    {

        return $this->belongsTo(Plan::class);
    }
}
