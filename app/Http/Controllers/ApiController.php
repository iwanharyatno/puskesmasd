<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function sendResponse($result, $message = "Request completed successfully!", $code = 200)
    {
        return response()->json([
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ], $code);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        return response()->json([
            'success' => false,
            'message' => $error,
            'data'    => $errorMessages,
        ], $code);
    }
}
