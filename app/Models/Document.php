<?php

namespace App\Models;

use App\Traits\Upload;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use Upload, SoftDeletes;
    protected $fillable = [
        'iva',
        'total',
        'subtotal',
        'enterprise',
        'customer',
        'date',
        'orderNumber',
        'user_id',
        'source'
    ];
    public function items()
    {
        return $this->hasMany(DocumentItem::class);
    }

    protected function source(): Attribute
    {
        return Attribute::make(
            get: fn($item) => $item ? $this->generateUrl($item) : null
        );
    }

    public function setSourceAttribute($value)
    {
        $source = collect(explode("/", $value));
        if ($source->count() > 2) {
            $fileName = $source->pop();
            $fileFolder = $source->pop();
            $source = "$fileFolder/$fileName";
        } else {
            $source = $value;
        }

        $this->attributes['source'] = $source;
    }

    public function setIvaAttribute($value)
    {
        $iva = $value;
        if (request()->method() == 'POST' && isset($this->attributes['total'])) {
            $total = $this->attributes['total'];
            $iva = $total - ($total / 1.21);
        }
        $this->attributes['iva'] = $iva;
    }
}
