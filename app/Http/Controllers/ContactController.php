<?php

namespace App\Http\Controllers;

use App\Jobs\Mail\OrderJob;
use App\Mail\Contact;
use App\Mail\CreateConfession;
use App\Rules\CartItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'name' => ['required', 'max:255'],
            'content' => ['required', 'max:122200'],
        ];
    }

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), $this->rules());

        if (!$validator->fails()) {

            $mail = new Contact($request['email'], $request['name'], $request['content']);
            Mail::to($request['email'])->send($mail);
            return response()->json([
                'status' => 'MAIL_SUCCESSFUL',
                'statusCode' => 1,
                'statusMessage' => "Mail send successful."
            ])->setStatusCode(Response::HTTP_OK);

        } else
            return response()->json(['status' => 'MAIL_ERROR',
                'statusCode' => 0,
                'statusMessage' => $validator->errors()])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

}
