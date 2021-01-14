<?php

namespace App\Http\Controllers;

use App\Models\Confession;
use App\Models\Image;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller {

    public function show( $image_id, $access_code = null)
    {
        $image = Image::where('id', $image_id)->first();
        $confession = $image->confession;
        if($confession->public == Confession::PUBLIC_YES || $confession->verifyConfession($access_code))
            return response()->file($image->getPath());
        return new Response('', 403);
    }
}
