<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function createSuccessResponse($code, $message, $status, $data = null)
    {
        $status = $status ?: Str::slug($message, '_');

        return response()->json(['code' => $code, 'message' => $message, 'status' => $status, 'data' => $data], $code);
    }

    public function createErrorResponse($code, $message, $status, $data = null)
    {
        $status = $status ?: Str::slug($message, '_');

        return response()->json(['code' => $code, 'message' => $message, 'status' => $status, 'data' => $data], $code);
    }
}
