<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    //

    public function getEmail()
    {
        return view('client.user.email');
    }


    public function postEmail(Request $request)
    {
        Validator::validate($request->all(), [
            'email' => ['required', 'email'],
        ], [
            'email.required' =>  __('request.email.required'),
            'email.email' =>  __('request.email.email'),
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert(
            ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
        );

        try {
            Mail::send('client.user.verify', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('اشعار استعادة  رمز الدخول');
                $message->from('kalefnyinfo@gmail.com', 'متاح');
            });
            $message =  ['message' =>__('messages.email_verification_sent')  , 'type' => 'alert-success'];

            return redirect()->route('login')->with($message);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('message', __('messages.error_occurred'));
        }
    }
}
