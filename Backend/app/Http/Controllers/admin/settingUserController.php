<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class settingUserController extends Controller
{
    //

    public function show()
    {
        try {
            $users = User::orderBy('id', 'desc')->get();
            // return response()->json($users);
            return view('admin.users.index')->with('users', $users);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function edit($user_id)
    {
        $users = User::find($user_id);
        // $users->isban*=-1;
        // $users->save();
        return view('admin.users._formUserBlock')->with(['data' => $users]);
    }

    public function ban($user_id)
    {
        $user = User::find($user_id);
        if ($user->is_active == 1)
            $user->is_active = 0;
        else
            $user->is_active = 1;

        $user->save();
        return back();
        // if($user->isban == 0)

        // return back()->with(['message' => 'تم حظر المستخدم بنجاح', 'type' => 'alert-success']);
        // return back();

        // return back()->with(['message' => 'تم فك حظر المستخدم بنجاح', 'type' => 'alert-success']);
        // return back()->with(['message' => 'فشلت عمليه الحظر الرجاء اعاده المحاوله   ', 'type' => 'alert-danger']);
    }
}
