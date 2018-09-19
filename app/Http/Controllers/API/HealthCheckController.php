<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Validator;

class HealthCheckController extends Controller
{

    public function healthCheck(Request $request)
    {
        return response()->json(['status' => 'OK'], 200);
    }
}
