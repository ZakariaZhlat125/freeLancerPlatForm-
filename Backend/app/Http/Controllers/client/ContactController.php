<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    //

    public function index()

    {
        return view('client.static.contactUs');
    }

    public function store(Request $request)

    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ], [
            'name.required' => 'رجاء قم بأدخال الاسم',
            'email.required' => 'رجاء قم بأدخال الايميل',
            'email.email' => 'ادخل الايميل بشكل صحيح',
            'message.required' => 'رجاء قم بأدخال رسالتك',
        ]);

        Contact::create($request->all());

        return redirect()->back()

        ->with(['message' => '   شكرا لتواصلك معنا', 'type' => 'alert-success']);

    }
}
