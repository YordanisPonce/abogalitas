<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as MatwebsiteExcel;

trait ExportTrait
{
    public function export($class, $data, $file, $format = MatwebsiteExcel::XLSX, $options = [])
    {
        Excel::store(new $class($data), $file, 'public', $format, [
            ...$options,
            'visibility' => 'public',
            'encoding' => 'UTF-8'
        ]);
        if (isset($options['method'])) {
            $method = $options['method'];
            return Storage::disk('public')->$method($file);
        }
        return Storage::disk('public')->url($file);

    }
}
