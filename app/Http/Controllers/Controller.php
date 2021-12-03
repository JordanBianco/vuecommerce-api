<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($data = [], $status = 200)
    {
        return response()->json([
            'message' => 'success',
            'data' => $data
        ], $status);
    }

    public function error($status = 400)
    {
        return response()->json([
            'message' => 'error',
        ], $status);
    }
}
