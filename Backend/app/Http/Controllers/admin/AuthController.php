<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //////////////////show users ////////////////
    public function listAll()
    {
        $users = User::where('is_active', 1)
            ->get();
        return view('admin.users')->with('users', $users);
    }


    // ////////////////// shoe regiser page  //////////////////////////
    public function create()
    {
        return view('createUser');
    }


    // this function show the the verfiy message
    public function show()
    {
        return view('client.user.verify-email');
    }



    public function request()
    {
        auth()->user()->sendEmailVerificationNotification();

        return back()
            ->with(['message' => __('message.confirmation_sent'), 'type' => 'alert-success']);
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('profile')->with(['message' => __('message.account_confirmed_message'), 'type' => 'alert-success']); // <-- change this to whatever you want
    }


    ///////////////// add user //////////////////
    public function register(Request $request)
    {

        try {

            // return response()->json($request->role);
            FacadesValidator::validate($request->all(), [
                'name' => ['required', 'min:8', 'max:50', /*'regex:/[a-z]/' , 'regex:/[A-Z]/' */],
                'email' => ['required', 'email', 'unique:users,email'],
                'user_pass' =>  ['required', 'min:8', 'max:20', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
                'confirm_pass' => ['same:user_pass']

            ], [
                'name.required' => __("request.name.required"),
                // 'name.regex' => 'يجب ان يحتوي على حروف كبيرة "A-Z"وصغيرة"a-z" ',
                'name.min' => __('request.name.min'),
                'email.unique' => __('request.email.unique'),
                'email.required' =>  __('request.email.required'),
                'email.email' =>  __('request.email.email'),
                'user_pass.required' => __('request.password.required'),
                'user_pass.min' => __('request.password.min'),
                'user_pass.max' => __('request.password.max'),
                'user_pass.regex' => __('request.password.regex'),
                'confirm_pass.same' => __('request.confirm_pass.same'),


            ]);
            $role = 'seeker';
            if ($request->role == 'provider')
                $role = 'provider';
            // check if the user still empty
            $checkUsers = User::first();
            if (is_null($checkUsers)) {
                $role = 'admin';
            }
            $name = $request->name;
            $u = new User();
            $u->name = $name;
            $u->password = Hash::make($request->user_pass);
            $u->email = $request->email;
            $token = Str::uuid();
            $u->remember_token = $token;


            if ($u->save()) {
                // try {

                $u->addRole($role);
                $to_name = $request->name;
                $to_email = $request->email;
                $data = array('name' => $request->name, 'activation_url' => URL::to('/') . "/verify_email/" . $token);

                // Mail::send('emails.welcome', $data, function ($message) use ($to_name, $to_email) {
                //     $message->to($to_email, $to_name)
                //         ->subject('تسجيل عضوية جديدة');
                //     $message->from('kalefnyinfo@gmail.com', 'كلفني');
                // });
                $u->sendEmailVerificationNotification();
                // $u->notify(new VerifyEmail);
                // if the user not admin
                if ($role !== 'admin') {
                    // setup the profile
                    $profile = new Profile();
                    $profile->name = $name;
                    $profile->user_id = $u->id;
                    $profile->save();


                    // if ($role == 'seeker')
                    //     $u->deposit(10000);
                }


                return redirect()->route('login')
                    ->with(['message' => __('messages.account_created_message'), 'type' => 'alert-success']);
                // } catch (\Throwable $th) {
                //     return back()->with(['message' => 'فشلت عمليه تسجيل دخولك رجاء اعاده المحاوله   ', 'type' => 'alert-danger']);
                // }
            }
            return back()->with(['message' => __('error.login.failed'), 'type' => 'alert-danger']);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $errorMessage = '';
            foreach ($errors as $error) {
                $errorMessage .= implode(" \n", $error) . " ";
            }
            return back()->with(['message' => $errorMessage, 'type' => 'alert-danger']);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return redirect()->back()->with(['message' =>  __('messages.time_limit_exceeded'), 'type' => 'alert-success']);
        } catch (\Throwable $th) {
            return redirect()->route('admin')->with(['message' => __('messages.access.unauthorized'), 'type' => 'alert-danger']);
        }
    }

    ///////////////// show hogin page after check role//////////////////

    public function showLogin()
    {
        if (Auth::check())
            return redirect()->route($this->checkRole());
        else
            return view('login');
    }
    /////////////////  check role//////////////////

    public function checkRole()
    {

        if (Auth::user()->hasRole('admin'))
            return 'admin';
        else
            return 'home';
    }

    ///////////////// check account in  hogin page //////////////////
    public function login(Request $request)
    {


        FacadesValidator::validate($request->all(), [
            'email' => ['email', 'required'],
            'user_pass' => ['required']


        ], [
            'email.required' =>  __('request.email.required'),
            'email.email' =>  __('request.email.email'),
            'user_pass.required' => __('request.password.required'),
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->user_pass, 'is_active' => 1])) {

            if (Auth::user()->hasRole('admin')) {
                return redirect()->route('admin');
            } else {
                return redirect()->route('profile');
                // return redirect()->route('home');
            }
        } else {
            return redirect()->route('login')->with(['message' =>    __('messages.email_password.check'),  'type' => 'alert-danger']);
        }
    }


    ///////////////// logout function //////////////////

    public function logout()
    {

        Auth::logout();
        return redirect()->route('login');
    }


    public function verifyEmail($token)
    {
        $user = User::where('remember_token', $token)->first();
        if ($user) {
            $user->email_verified_at = Carbon::now()->timestamp;
            $user->save();
            Auth::login($user);
            return redirect()->route('profile')->with(['message' => __('messages.account_activated_message'), 'type' => 'alert-success']);;
        } else
            echo "invalid token";
    }
    // start change password

    public function changePassword()
    {
        return view('admin.change-password');
    }

    public function updatePassword(Request $request)
    {
        # Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',


        ], [
            'old_password.required' => __('request.old_password.required'),
            'new_password.confirmed' => __('request.new_password.confirmed'),
            'new_password.required' => __("request.new_password.required"),
        ]);



        #Match The Old Password


        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return back()->with("error", __('messages.password.old_incorrect'));
        }


        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);


        return back()->with("status", __('messages.password_changed_message'));
    }
    // end change password

}
