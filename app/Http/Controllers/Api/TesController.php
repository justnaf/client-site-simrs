<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TesController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'success'
        ]);
    }
}
