<?php

namespace App\Http\Controllers;

use App\Models\Confession;
use App\Models\Image;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ConfessionController extends Controller
{
    public function rules()
    {

        return [
            'title' => ['required'],
            'content' => ['required'],
            'images.*' => ['required', 'image', 'max:8182'],
            'images.0' => ['required'],
            'videos.*' => ['required', 'mimes:mp4,mov,ogg', 'max:25600'],
        ];
    }

    public function create(Request $request, $uuid, $access_code)
    {
        $confession = Confession::where('uuid', $uuid)->first();

        if ($confession->verifyConfession($access_code)) {
            $validator = Validator::make($request->all(), $this->rules());

            if ($validator->fails()) {

                return response()->json([
                    'status' => 'ORDER_VALIDATION_ERROR',
                    'statusCode' => 0,
                    'statusMessage' => $validator->errors()
                ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);

            } else {
                if ($confession->status == Confession::STATUS_NO_CREATED || true) {

                    $confession->title = $request['title'];
                    $confession->content = $request['content'];

                    foreach ($request['images'] as $image) {
                        $img = new Image();
                        $img->confession()->associate($confession);
                        $path = $image->store('images');
                        $img->path = $path;
                        $img->save();
                    }
                    if (isset($request['videos']))
                        foreach ($request['videos'] as $videoData) {
                            $video = new Video();
                            $video->confession()->associate( $confession);
                            $path = $videoData->store('videos');
                            $video->path = $path;
                            $video->save();
                        }

                    $confession->save();

                    return response()->json([
                        'status' => 'CONFESSIONS_CREATED',
                        'statusCode' => 2,
                        'statusMessage' => "Confession created."
                    ])->setStatusCode(Response::HTTP_OK);

                } else {
                    return response()->json([
                        'status' => 'CONFESSIONS_WAS_CREATED_EARLIER',
                        'statusCode' => 1,
                        'statusMessage' => "Confession was created earlier."
                    ])->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
                }

            }

        } else
            return response()->json([
                'status' => 'CONFESSION_NOT_EXIST',
                'statusCode' => 0,
                'statusMessage' => "Confession with specified access code and uuid does not exist."
            ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
    }


    public function getConfession(Request $request, $uuid, $access_code)
    {
        $confession = Confession::where('uuid', $uuid)->first();

        if ($confession->verifyConfession($access_code))
            return response()->json([$confession])->setStatusCode(Response::HTTP_OK);
        else
            return response()->json([
                'status' => 'CONFESSION_NOT_EXIST',
                'statusCode' => 0,
                'statusMessage' => "Confession with specified access code and uuid does not exist."
            ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
    }
}
