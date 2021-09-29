<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class APIBaseController extends Controller
{
    public function successJsonResponse(mixed $data, ?int $code = 200): JsonResponse
    {
        return response()->json(
            [
                'success' => true,
                'data' => $data
            ],
            $code
        );
    }

    public function errorJsonResponse(mixed $data, ?int $code = 500): JsonResponse
    {
        return response()->json(
            [
                'success' => false,
                'error' => $data
            ],
            $code
        );
    }
}
