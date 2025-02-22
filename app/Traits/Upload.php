<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

trait Upload
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
                return $string;
            }, $path);

        } else {
            $result = $path;
        }
        return Storage::delete($result);
    }

    public function generateUrl(string $url, $temporary = false)
    {
        if ($temporary) {
            return Storage::temporaryUrl($url, now()->addDay());
        }
        return Storage::url($url);
    }

    private function uploadBase64Files($base64_string, $folderName = 'uploads')
    {
        // Verificar que la cadena Base64 no esté vacía
        $base64Image = $base64_string;
        if (empty($base64Image)) {
            return null;
        }

        // Separar el tipo de contenido y los datos de la imagen
        $imageData = explode(',', $base64Image);
        $imageType = isset($imageData[0]) ? explode(';', explode(':', $imageData[0])[1])[0] : null;

        // Verificar que el tipo de contenido sea una imagen
        if (strpos($imageType, 'image/') !== 0) {
            return null;
        }

        // Detectar la extensión de la imagen
        $extension = str_replace('image/', '', $imageType);

        // Generar un nombre de archivo único
        $filename = Str::random(15) . '.' . $extension;
        // Obtener los datos de la imagen
        $imageContent = base64_decode($imageData[1]);

        // Generar la ruta de almacenamiento
        $path = "{$folderName}/{$filename}";

        // Almacenar la imagen en el disco público
        Storage::put($path, $imageContent);

        // Devolver la URL relativa
        return Storage::url($path);
    }

    public function copyToStorage($sourcePath, $destinationFolder = 'uploads')
    {
        $source = public_path($sourcePath);
        $destination = $destinationFolder . '/' . basename($sourcePath);

        if (file_exists($source)) {
            $content = file_get_contents($source);
            Storage::put($destination, $content);
            return $destination;
        }

        return null;
    }

    public function fileExist($path)
    {

        return File::exists(public_path() . "/" . $path);

    }

    public function getFirstFilePath($folder)
    {
        try {
            $files = Storage::files("drive-downloads/$folder");
            if (!empty($files)) {
                return $files[0];
            }

        } catch (\Throwable $th) {
            //throw $th;
        }

        return null;
    }


    public function copyFolder($folderName)
    {
        $sourceFolder = public_path($folderName);
        $destinationFolder = Storage::disk('public')->path($folderName);
        File::copyDirectory($sourceFolder, $destinationFolder);

        $images = Storage::disk("public")->allFiles($folderName);
        foreach ($images as $image) {
            $names = collect(explode("/", $image));
            if ($names->count() >= 2) {
                $filename = $names->pop();
                $folder = $names->pop();
                $fileImage = File::get(Storage::disk("public")->path($image));
                Storage::put("$folderName/$folder/$filename", $fileImage);
            }
        }
    }

    public function getExcelFilePath($filePath)
    {
        return Storage::path($filePath);
    }

    public function getBase64Image($path, $relative = false)
    {
        $binaryContent = !$relative ? file_get_contents(Storage::path($path)) : file_get_contents($path);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $dataImage = 'data:image/' . $extension . ';base64,' . base64_encode($binaryContent);
        return $dataImage;
    }

    public function getFilePath(string $path): string
    {
        return Storage::path($path);
    }
}
