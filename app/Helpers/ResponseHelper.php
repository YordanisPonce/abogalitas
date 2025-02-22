<?php

namespace App\Helpers;

class ResponseHelper
{

    public static function response(array $returnResponse)
    {
        return response()->json($returnResponse, $returnResponse['status_code']);
    }

    public static function ok(string $message, mixed $data = null, int $statusCode = 200)
    {
        return [
            'status' => true,
            'message' => __($message),
            'status_code' => $statusCode,
            'data' => $data
        ];
    }

    public static function fail(string $message, int $statusCode = 400, $data = null)
    {

        return [
            'status' => false,
            'message' => __($message),
            'status_code' => $statusCode,
            'data' => $data
        ];
    }

    public static function error(string $message)
    {
        if (str_contains(strtolower($message), 'domain not found')) {
            return response()->json([
                'status' => false,
                'message' => __("Cuenta de correo inválida, dominio no encontrado"),
            ], 500);
        }
        if (str_contains(strtolower($message), 'connection could not be established')) {
            return response()->json([
                'status' => false,
                'message' => __("Hemos encontrado un error en el servidor de correo de nuestra plataforma"),
            ], 500);
        }
        return response()->json([
            'status' => false,
            'message' => __($message),
        ], 500);
    }

    public static function mail_error(string $message)
    {
        if (strpos(strtolower($message), 'domain not found')) {
            return self::error("Cuenta de correo inválida, dominio no encontrado");
        }
        return self::error($message);
    }
}