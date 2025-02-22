<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasSlug;
    protected $fillable = [
        'name',
        'slug',
        'photo',
        'description'
    ];

    protected function photo(): Attribute
    {

        return Attribute::make(
            get: fn($item) => $item ? Storage::url($item) : null,
        );

    }

    public function setNameAttribute($value)
    {
        $this->attributes['slug'] = $this->getSlug($value);
        $this->attributes['name'] = $value;
    }

    public function setPhotoAttribute($value)
    {
        $source = collect(explode("/", $value));
        if ($source->count() > 2) {
            $fileName = $source->pop();
            $fileFolder = $source->pop();
            $source = "$fileFolder/$fileName";
        } else {
            $source = $value;
        }

        $this->attributes['photo'] = $source;

    }
}
