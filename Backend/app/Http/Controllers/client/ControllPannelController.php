<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ControllPannelController extends Controller
{
    //

    function index()
    {


        // give all the categories
        $categories = category::where('is_active', 1)->get();

        $profile = Profile::where('user_id', Auth::id())->get();

        // give the roles of the user
        $user = User::find(Auth::id());
        $userRole = 'seeker';
        if ($user->hasRole('provider') && $user->hasRole('seeker')) {
            $userRole = 'both';
        } else if ($user->hasRole('provider')) {
            $userRole = 'provider';
        }
        // dd($profile, $userRole, $categories);
        // return response()->json($transactions);
        return view('client.userProfile.controllPannal')->with([
            'data' => $profile,
            'categories' =>  $categories,
            'role' => $userRole,

        ]);
        // return view('client.userProfile.controllPannal')->with();
    }


    // here the function for the saving the user information
    public function profile_save(Request $request)
    {
        // try {
        $current_user_id = Auth::user()->id;

        $userRole = false;
        // Auth::user()->roles()->detach();

        $user = User::findOrFail($current_user_id);
        $user_name = $user->name;
        // if ($request->provider && $request->seeker) {
        //     Auth::user()->roles()->sync([3, 4]);
        //     $userRole = true;
        // } else if ($request->provider) {
        //     Auth::user()->roles()->sync([4]);
        //     $userRole = true;
        // } else if ($request->seeker) {
        //     Auth::user()->roles()->sync([3]);
        //     $userRole = true;
        // } else {
        //     $userRole = false;
        // }


        // if ($userRole) {
        Profile::updateOrCreate(
            ['user_id' => $current_user_id],
            [
                // !what this for?
                'job_title' => $request->input('job_title'),
                'name' => $user_name,
                'specialization'  =>  $request->input('category_id'),
                'bio'  =>  $request->input('bio'),
                'video'  =>  $request->input('video'),
                'category_id' => $request->input('category_id'),
                'hire_me' => $request->hire_me ? 1 : 0
            ]

        );
        return redirect()->route('profile')
            ->with(['message' => __('messages.personal_info_updated_success'), 'type' => 'alert-success']);
        // } else {
        //     return redirect()->back()
        //         ->with(['message' => 'يرجى تحديد نوع الحساب رجاء', 'type' => 'alert-danger']);
        // }
        // } catch (\Throwable $th) {
        //     //throw $th;
        // }
    }


    function admin()
    {
        // Show account Users && Posts && Rports
        $post = DB::table('posts')->count();
        $reports = DB::table('reports')->count();
        $cates = DB::table('categories')->count();
        $user = DB::table('users')->count();

        $users = User::select(DB::raw("COUNT(*) as count"), DB::raw("MONTHNAME(created_at) as month_name"))

            ->whereYear('created_at', date('Y'))

            ->groupBy(DB::raw("MONTHNAME(created_at)"))

            ->pluck('count', 'month_name');

        $labels = $users->keys();
        $data = $users->values();

        return view('admin.index', compact('labels', 'data', 'user', 'reports', 'cates', 'post'));
    }



    public function edit_pro()
    {
        $current_user_id = Auth::user()->id;
        $profile = Profile::where('user_id', $current_user_id)->get();
        //  print_r($profile);
        return view('client.userProfile.editUserProfile')
            ->with('data', $profile);
    }


    public function uploadFile($file)
    {
        $dest = public_path() . "/images/";

        //$file = $request->file('image');

        $filename = time() . "_" . $file->getClientOriginalName();
        $file->move($dest, $filename);

        return $filename;
    }

    public function account_save(Request $request)
    {

        $current_user_id = Auth::user()->id;
        // User::where('id', $current_user_id)->update(['name' => $request->input('name')]);

        // $filename = $this->uploadFile($request->file('avatar'));

        // $ser->image=$this->uploadFile($request->file('image'));
        // Profile::where('user_id', $current_user_id)->update(
        //     [
        //         'name' => $request->input('name'),
        //         'gender'    =>  $request->input('gender'),
        //         'country'  =>  $request->input('country'),
        //         'mobile'  =>  $request->input('mobile'),
        //         'avatar' => $filename,
        //     ]

        // );
        Validator::validate($request->all(), [
            'name' => 'required|min:8',
            'gender' => 'required',
            'country' => 'required',
            'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'avatar' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        ], [
            'name.required' => __("request.name.required"),
            'name.min' => __("request.name.min"),
            'gender.required' => __("request.gender.required"),
            'country.required' => __("request.country.required"),
            'avatar.required' => __("request.avatar.required"),
            'avatar.image' => __("request.avatar.image"),
            'avatar.mimes' => __("request.avatar.mimes"),
            'mobile.required' => __("request.mobile.required"),
            'mobile.regex' => __("request.mobile.regex"),
            'mobile.min' => __("request.mobile.min"),


        ]);
        $pro = Profile::where('user_id', $current_user_id)->first();
        $pro->name = $request->name;
        $pro->gender = $request->gender;
        $pro->country = $request->country;
        $pro->mobile = $request->mobile;


        if ($request->hasFile('avatar'))
            $pro->avatar = $this->uploadFile($request->file('avatar'));
        else {
        }
        if ($pro->save())

            return redirect()->route('account')->with(['message' => 'تم تعديل بياناتك الشخصيه بنجاح', 'type' => 'alert-success']);
    }
}
