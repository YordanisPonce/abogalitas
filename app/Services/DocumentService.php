<?php

namespace App\Services;

use App\Enums\AllowedDocumentExtensionEnum;
use App\Helpers\ResponseHelper;
use App\Interfaces\EloquentDocumentRepositoryInterface;
use App\Interfaces\RepositoryInterface;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DocumentService
{
    use Upload;

    public function __construct(
        private readonly EloquentDocumentRepositoryInterface $repository,
        private readonly OcrService $ocrService,
        private readonly OpenAIService $openAIService
    ) {
    }

    public function findAll()
    {
        return ResponseHelper::ok("Todos los documentos", $this->repository->findAll(
            [
                'user_id' => auth()->id(),
                'paginate' => true
            ]
        ));
    }

    public function findById($id)
    {
        $model = $this->repository->findById($id);
        return ResponseHelper::ok("Documento por id", $model);
    }

    public function save(array $attributes)
    {

        $ext = pathinfo($attributes['source'], PATHINFO_EXTENSION);
        $imageUrl = null;
        if ($ext == AllowedDocumentExtensionEnum::PDF->value) {
            $pdfPath = $this->getFilePath($attributes['source']);
            $imageUrl = $this->ocrService->savePdfAsImage($pdfPath);
            $imageUrl = $this->getBase64Image($imageUrl, true);
        } else {
            $imageUrl = $this->getBase64Image($attributes['source']);
        }

        $extractedData = $this->openAIService->extractData($imageUrl);
        if (!$extractedData) {
            $extractedData = $this->openAIService->extractData($imageUrl);
        }
        throw_if(!$extractedData, "Fallo extrayendo los datos por favor vuelva a intentarlo");

        $model = $this->repository->save([
            'iva' => $this->convertToNumber($extractedData['iva']),
            'total' => $this->convertToNumber($extractedData['total']),
            'subtotal' => $this->convertToNumber($extractedData['subtotal']),
            'enterprise' => $extractedData['company'],
            'customer' => $extractedData['customer'],
            'orderNumber' => intval($extractedData['idOrder']),
        ] + $attributes);

        $transformItems = [];
        foreach ($extractedData['items'] as $key => $value) {
            $transformItems[] = [...$value, 'price' => $this->convertToNumber($value['price']), 'amount' => intval($value['amount'] ?? 0)];
        }

        $model->items()->createMany(
            $transformItems
        );
        return ResponseHelper::ok("Documento guardado satisfactoriamente", $this->repository->findById($model->id));
    }

    public function update(array $attributes, $id)
    {
        $model = $this->repository->findById($id);
        throw_if(!$model, "No se encuentra documento con el identificador proporcioanado");

        $model->fill($attributes)->save();
        if ($attributes['items']) {
            $model->items()->delete();
            $model->items()->createMany($attributes['items']);
        }

        return ResponseHelper::ok("Documento actualizado satisfactoriamente", $this->repository->findById($id));

    }

    public function delete($id)
    {
        return ResponseHelper::ok("Documento eliminado satisfactoriamente", $this->repository->delete($id));
    }

    private function convertToNumber($number)
    {
        try {
            return floatval(str_replace(',', '.', trim($number)));
        } catch (\Throwable $th) {
            return null;
        }

    }
}