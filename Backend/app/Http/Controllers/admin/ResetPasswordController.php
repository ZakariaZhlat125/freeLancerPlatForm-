<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    //
    public function getPassword($token)
    {
        return view('client.user.reset', ['token' => $token]);
    }


    public function updatePassword(Request $request)
    {

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'max:20', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
            'password_confirmation' => ['same:password'],

        ], [
            'email.required' =>  __('request.email.required'),
            'email.email' =>  __('request.email.email'),
            'password.required' => __('request.password.required'),
            'password.min' => __('request.password.min'),
            'password.regex' =>  __('request.password.regex'),
            'password_confirmation.same' => __('confirm_pass.same'),


        ]);


        $updatePassword = DB::table('password_resets')
            ->where(['email' => $request->email, 'token' => $request->token])
            ->first();

        if (!$updatePassword)
            return back()->withInput()->with('error', 'Invalid token!');

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect('login')->with(['message' =>  __('messages.password_changed_success'), 'type' => 'alert-success']);
    }
}
