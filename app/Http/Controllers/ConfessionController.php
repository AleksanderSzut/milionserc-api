<?php

namespace App\Http\Controllers;

use App\Mail\ConfessionCreated;
use App\Mail\CreateConfession;
use App\Models\Confession;
use App\Models\Image;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
                    'status' => 'CONFESSION_VALIDATION_ERROR',
                    'statusCode' => 0,
                    'statusMessage' => $validator->errors()
                ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);

            } else {
                if ($confession->status == Confession::STATUS_NO_CREATED) {

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
                            $video->confession()->associate($confession);
                            $path = $videoData->store('videos');
                            $video->path = $path;
                            $video->save();
                        }
                    $confession->status = Confession::STATUS_CREATED;

                    $confession->save();

                    $mail = new ConfessionCreated(env("APP_URL") . '/wyznanie/' . $confession->uuid . '/' . $confession->access_code, $confession->order->billing->full_name);
                    Mail::to($confession->order->billing->email)->send($mail);

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

    protected function prepareConfessionsToArray($confessions): array
    {
        $data = [];
        foreach ($confessions as $index => $confession) {
            $data[$index]['id'] = $confession->id;
            $data[$index]['packageId'] = $confession->package_id;
            $data[$index]['title'] = $confession->title;
            $data[$index]['content'] = $confession->content;


            foreach ($confession->image as $indexImg => $image) {
                $data[$index]['images'][$indexImg] = $image->getLink();
            }
            foreach ($confession->video as $indexVideo => $video) {
                $data[$index]['videos'][$indexVideo] = $video->getLink();
            }
        }

        return $data;

    }

    public function read(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = [];
        $offset = (int)($request['offset'] ?? 0);
        $per_page = (int)($request['per_page'] ?? 4);
        if (isset($request['q']))
            $confessions = Confession::where('public', Confession::PUBLIC_YES)->where(function ($query) {
                global $request;
                $query->where('title', 'like', '%' . $request['q'] . '%')->orWhere('content', 'like', '%' . $request['q'] . '%');
            })->offset($offset)->limit($per_page)->get();
        else
            $confessions = Confession::where('public', Confession::PUBLIC_YES)->offset($offset)->limit($per_page)->get();
        $data['count'] = $confessions->count();
        $data['confessions'] = $this->prepareConfessionsToArray($confessions);


        return response()->json([
            'status' => 'OK',
            'statusCode' => 2,
            'data' => $data,
        ], Response::HTTP_OK);
    }


    protected function prepareSecuredConfessionToArray($confession): array
    {
        $data['id'] = $confession->id;
        $data['uuid'] = $confession->uuid;
        $data['packageId'] = $confession->package_id;
        $data['title'] = $confession->title;
        $data['content'] = $confession->content;
        $data['status'] = $confession->status;
        $data['public'] = $confession->public;
        $data['accessCode'] = $confession->access_code;
        $data['createdAt'] = $confession->created_at;
        $data['updatedAt'] = $confession->updated_at;

        foreach ($confession->image as $indexImg => $image) {
            $data['images'][$indexImg] = $image->getLink();
        }
        foreach ($confession->video as $indexVideo => $video) {
            $data['videos'][$indexVideo] = $video->getLink();
        }

        return $data;

    }

    public function setPublic(Request $request, $uuid, $access_code)
    {


        $validator = Validator::make($request->all(), [
            'public' => ['required', 'integer' ],
        ]);
        if ($validator->fails() || !($request['public'] == 0 || $request['public'] == 1)) {
            return response()->json([
                'status' => 'CONFESSION_VALIDATION_ERROR',
                'statusCode' => 0,
                'statusMessage' => $validator->errors()
            ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $confession = Confession::where('uuid', $uuid)->first();

            if (!isset($confession) || !$confession->verifyConfession($access_code))
                return response()->json([
                    'status' => 'CONFESSION_NOT_EXIST',
                    'statusCode' => 0,
                    'statusMessage' => "Confession with specified access code and uuid does not exist."
                ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
            else {

                $confession->public = $request['public'];
                $confession->save();

                return response()->json([
                    'status' => 'CONFESSION_SET_PUBLIC_SUCCESSFUL',
                    'statusCode' => 1,
                    'statusMessage' => "Set confession public flag successful."
                ])->setStatusCode(Response::HTTP_OK);
            }
        }


    }

    public function getConfession(Request $request, $uuid, $access_code)
    {

        $confession = Confession::where('uuid', $uuid)->first();

        if (!isset($confession) || !$confession->verifyConfession($access_code))
            return response()->json([
                'status' => 'CONFESSION_NOT_EXIST',
                'statusCode' => 0,
                'statusMessage' => "Confession with specified access code and uuid does not exist."
            ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
        else {
            $preparedConfession = $this->prepareSecuredConfessionToArray($confession);
            return response()->json([$preparedConfession])->setStatusCode(Response::HTTP_OK);
        }
    }
}
