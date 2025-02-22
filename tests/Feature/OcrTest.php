<?php

use App\Services\OcrService;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Spatie\PdfToImage\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;

function saveImages($pathToPdf)
{
    $pdf = new Pdf($pathToPdf);
    $basename = pathinfo($pathToPdf, PATHINFO_FILENAME);
    $outputPath = 'images' . "/$basename";
    if (!File::isDirectory(public_path($outputPath))) {
        File::makeDirectory(public_path($outputPath));
    }
    if ($pdf->getNumberOfPages() <= 1) {
        $path = public_path("$outputPath/1.png");
        $pdf->saveImage($path);
        return $path;
    }
    for ($i = 0; $i < $pdf->getNumberOfPages(); $i++) {
        $path = $outputPath . "/" . $i + 1 . ".png";
        $pdf->saveImage($path);
        if (file_exists(public_path($path))) {
            $paths[] = $path;
        }

    }
    return $paths;
}
/* 
test('Save bill as image', function () {
    $pathToPdf = public_path('momoi.pdf');
    $outputPath = public_path('momoiimages');
    $pdf = new Pdf($pathToPdf);
    $success = false;
    for ($i = 0; $i < $pdf->getNumberOfPages(); $i++) {
        $success = $pdf->saveImage($outputPath . "/" . $i + 1 . ".png");
    }
    expect($success)->toBe(true);
});

test('Read text image', function () {
    $imageUrl = public_path('images/factura-momoi.png');

    $output = (new TesseractOCR($imageUrl))
        ->setOutputFile(public_path('momoiimages/1.png'))
        ->run();
    expect(true)->toBe(true);
});

test('Extract data as file', function () {
    $billPath = public_path('momoi.pdf');
    $imageUrl = saveImages($billPath);
    $output = (new TesseractOCR($imageUrl))
        ->setOutputFile(public_path('images/output.txt'))
        ->run();
    $service = app()->make(OcrService::class);
    // Leer el archivo de salida
    $text = file_get_contents(public_path('images/output.txt'));
    // Reemplazar saltos de línea por \n
    $text = str_replace(PHP_EOL, '\n', $text);
    $htmlText = nl2br(htmlspecialchars($text));
    $body = "<html><body>" .
        "<h1>Texto Extraído</h1>" .
        "<p>$htmlText</p>" .
        "</body></html>";
    $output = $service->extractData($text);
    expect($output)->toBeArray();
});
 */
/*
test('Extract data from albaran', closure: function () {
    $output = (new TesseractOCR(public_path('images/momoi/1.png')))
    ->setOutputFile(public_path('images/output.txt'))
    ->run();
    Log::debug($output);
    $billPath = public_path('facturas/ticket.pdf');
    $paths = saveImages($billPath);
    if (!is_string($paths)) {
        $outputs = [];
        foreach ($paths as $key => $value) {
            $output = (new TesseractOCR($value))
                ->setOutputFile(public_path('ticket/1.png'))
                ->run();
            $outputs[] = $output;
        }

        $service = app()->make(OcrService::class);
        $output = $service->extractData($outputs);

    } else {
        $output = (new TesseractOCR(public_path('images/momoi/1.png')))
            ->setOutputFile(public_path('images/output.txt'))
            ->run();
            Log::debug($output);
        $service = app()->make(OcrService::class);
        $output = $service->extractData($output);
    }
    expect($output)->toBeArray();
});
*/

test('Save pdf as images', closure: function () {

    $ocrService = new OcrService;
    $pdfs = ['facturas/albaran.pdf', 'facturas/document-1.pdf', 'facturas/document-2.pdf', 'facturas/document.pdf', 'facturas/factura-4.pdf', 'facturas/ticket.pdf'];

    $output = [];
    foreach ($pdfs as $value) {
        $output[] = $ocrService->savePdfAsImage(public_path($value));
    }

    expect($output)->toBeArray();
});

/*test('Extract text from images', closure: function () {

    $ocrService = new OpenAIService;
    $pdfs = [
        public_path('images/ticket/1.jpg'),
    ];
    foreach ($pdfs as $value) {
        $binaryContent = file_get_contents($value);
        $extension = pathinfo($value, PATHINFO_EXTENSION);
        $dataImage = 'data:image/' . $extension . ';base64,' . base64_encode($binaryContent);
        $data = $ocrService->extractData($dataImage);

    }
    expect([])->toBeArray();

});*/