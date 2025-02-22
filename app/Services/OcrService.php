<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Interfaces\RepositoryInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

class OcrService
{


    public function savePdfAsImage($pathToPdf)
    {
        $pdf = new Pdf($pathToPdf);
        $basename = pathinfo($pathToPdf, PATHINFO_FILENAME);
        $outputPath = 'images' . "/$basename";
        if (!File::isDirectory(Storage::path($outputPath))) {
            File::makeDirectory(path: Storage::path($outputPath), recursive: true);
        }
        if ($pdf->getNumberOfPages() <= 1) {
            $path = Storage::path("$outputPath/1.jpg");
            $pdf->saveImage($path);
            return $path;
        }
        for ($i = 0; $i < $pdf->getNumberOfPages(); $i++) {
            $path = $outputPath . "/" . $i + 1 . ".jpg";
            $pdf->saveImage($path);
            if (file_exists(Storage::path($path))) {
                $paths[] = $path;
            }
        }
        return $paths;
    }
    public function extractData($output)
    {

        $data = null;
        if (is_array($output)) {

        } else if (is_string($output)) {
            $data = $this->analizeStr($output);
        }
        Log::debug($data);

        return ResponseHelper::ok("Elementos extraidos", $data);
    }

    private function analizeStr($str)
    {
        $iva = $this->getIva($str);
        $total = $this->getTotal($str);
        return [
            'iva' => $iva,
            'total' => $total,
        ];
    }

    private function getIva($text)
    { // Expresión regular para capturar el IVA

        $patterns = [
            "/IVA\s*\(\s*[0-9]+\s*%\s*:\s*([0-9]+(?:\.[0-9]{1,2})?)\s*€/",
            "/Impuesto\s*:\s*([0-9]+(?:\.[0-9]{1,2})?)\s*€/",
            // Agrega más patrones según sea necesario
        ];

        $patron = "/IVA\s*\(\s*[0-9]+\s*%\s*:\s*([0-9]+(?:\.[0-9]{1,2})?)\s*€/";
        if (preg_match($patron, $text, $coincidencias)) {
            return (float) str_replace(',', '.', $coincidencias[1]);
        }
        return null;
    }

    private function getTotal($text)
    { // Expresión regular para capturar el IVA

        $patterns = [
            "/Total\s*\(\s*[0-9]+\s*%\s*:\s*([0-9]+(?:\.[0-9]{1,2})?)\s*€/",
            "/Total\s*([0-9]+(?:\,[0-9]{1,2})?)\s*€/",
            "/Total\s*:\(\s*[0-9]+\s*%\s*:\s*([0-9]+(?:\.[0-9]{1,2})?)\s*€/",
            "/TOTAL\s*:\s*([0-9]+(?:\.[0-9]{1,2})?)\s*€/",
            "/Total\s*\s*([0-9]+(?:\.[0-9]{1,2})?)\s*€/",
            "/Total\s*([0-9]+(?:\,[0-9]{1,2})?)\s*€/"
            // Agrega más patrones según sea necesario
        ];

        $patron = "/IVA\s*\(\s*[0-9]+\s*%\s*:\s*([0-9]+(?:\.[0-9]{1,2})?)\s*€/";
        if (preg_match($patron, $text, $coincidencias)) {
            return (float) str_replace(',', '.', $coincidencias[1]);
        }
        return null;
    }


    private function normalizeText($text)
    {
        // Reemplazar múltiples espacios por un solo espacio
        $text = preg_replace('/\s+/', ' ', $text);
        // Eliminar espacios antes de los dos puntos
        $text = preg_replace('/\s*:\s*/', ': ', $text);
        return $text;
    }
}