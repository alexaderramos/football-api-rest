<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\JsonResponse;

trait ResponseHelper
{
    public function data($data, $code = 200)
    {

        return new JsonResponse([
            'status' => true,
            'data' => $data
        ], $code);
    }

    public function ok($message = 'Your information has been saved', $data = null, $code = 200)
    {

        return new JsonResponse(
            [
                'status' => true,
                'message' => $message,
                'data' => $data
            ],
            $code
        );
    }

    public function warning($message, $data = null, $code = 422)
    {

        return new JsonResponse([
            'status' => false,
            'message' => $message,
            'data' => $data

        ], $code);
    }

    public function error(\Exception $ex, $message = 'Could not connect to the server')
    {
        if (!config('app.debug')) {
            return new JsonResponse([
                'status' => false,
                'exception' => $message,
                'message' => $ex->getMessage(),
                'code' => $ex->getCode()
            ], $ex->getCode());
        }

        // Error messages to debug
        return new JsonResponse([
            'status' => false,
            'exception' => $message,
            'message' => $ex->getMessage(),
            'line' => $ex->getLine(),
            'file' => $ex->getFile(),
            'trace' => $ex->getTraceAsString(),
            'code' => $ex->getCode()
        ], $ex->getCode());

    }
}
