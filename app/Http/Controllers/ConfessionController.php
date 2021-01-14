<?php

namespace App\Http\Controllers;

use App\Models\Confession;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ConfessionController extends Controller
{
    public function getConfession(Request $request, $uuid, $access_code)
    {
        $confession = Confession::where('uuid', $uuid)->first();
        if ($confession === null || $confession->access_code !== $access_code)
            return response()->json([
                'status' => 'CONFESSION_NOT_EXIST',
                'statusCode' => 0,
                'statusMessage' => "Confession with specified access code and uuid does not exist."
            ])->setStatusCode(422);
        else
            return response()->json([$confession]);
    }
}
