<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    //

    public  function  switch(Request $request)
    {
        $lang = $request->input('lang');

        session(['lang'=>$lang]);

        return redirect()->back();
    }
}
