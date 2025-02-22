<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\PdfToImage\Pdf;

trait FileManagementTrait
{
    public function upload($filename, $content, $folderName = 'uploads')
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = Str::random(40) . '.' . $ext;
        Storage::putFileAs($folderName, $content, $name);
        return $folderName . '/' . $name;
    }

    public function remove($path)
    {
        if (is_array($path)) {
            $result = array_map(function ($string) {
                return 'public/' . $string;
            }, $path);

        } else {
            $result = 'public/' . $path;
        }
        return Storage::delete($result);
    }

    public function generateUrl(string $url)
    {
        return Storage::url($url);
    }
    public function pdfToPng($pathToPdf, $outputPath): bool
    {
        try {
            $pdf = new Pdf($pathToPdf);
            $pdf->saveAllPagesAsImages($outputPath);
            return true;
        } catch (\Throwable $th) {
            return false;
        }

    }


}
