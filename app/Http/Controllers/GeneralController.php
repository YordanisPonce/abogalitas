<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class GeneralController extends Controller
{
    use Upload;
    public function uploadFile(Request $request)
    {
        $request->validate(
            [
                'file' => 'required|file'
            ]
        );
        if (request()->hasFile('file')) {
            $file = $request->file('file');
            $path = $this->upload($file->getClientOriginalName() ?: Str::random(10), $file);
        }


        return ResponseHelper::ok('Url del fichero', [
            'source' => $path
        ]);
    }
}
