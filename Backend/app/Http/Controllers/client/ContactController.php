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
            'name.required' => __('resquest.name.required'),
            'email.required' => __('resquest.email.required'),
            'email.email' =>__('resquest.email.email'),
            'message.required' => __('resquest.message.required'),
        ]);

        Contact::create($request->all());

        return redirect()->back()

        ->with(['message' => __('messages.thank_you') ,'type' => 'alert-success']);

    }
}
