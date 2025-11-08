<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Mail\EmailMailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function send()
    {
        Mail::to(Auth::user())->send(new EmailMailable());
        return ApiResponse::SendResponse(200, 'email sent sucssesfully', []);
    }
}
