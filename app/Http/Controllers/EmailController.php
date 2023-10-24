<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SFMailable;
use App\Mail\SFMailableEN;
use App\Mail\SFMailableReply;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        // Check the Authorization header
        $authHeader = $request->header('Authorization');
        $AuthHeaderCode = env('MAIL_HEADER_SENDER');
        if ($authHeader !== $AuthHeaderCode) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $content = $request->input('message');
        // Check for the "language" parameter
        $language = $request->has('language') && $request->input('language') === 'es' ? 'es' : 'en';

        $mailable = $language === 'es' ? new SFMailable($name) : new SFMailableEN($name);
        Mail::to($email)->send($mailable);

        $mailableReply = new SFMailableReply($name, $email, $content);
        $recipients = ['info@olimed.com.pe', 'tadeo.m@olimed.com.pe'];
        Mail::to($recipients)->send($mailableReply);

    }
}
