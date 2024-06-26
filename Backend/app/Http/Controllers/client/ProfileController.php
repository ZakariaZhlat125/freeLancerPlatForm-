<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Transfer;
use App\Models\UserSkills;
use App\Models\Wallet;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class ProfileController extends Controller
{
    //

    // function savecategories(Request $request)
    // {
    //     $categories = $request->categories;
    //     print_r($categories);
    //     if (blank($categories)) {
    //         return redirect()->back()->with(['message' => 'يرجى اضافة تخصص جديد', 'type' => 'alert-danger']);
    //     } else {
    //         $needToInsert = false;
    //         // insert if the categories are new
    //         foreach ($categories  as $value) {
    //             $findcategory = Usercategories::where('user_id', Auth::id())->where('category_id', $value)->get();

    //             if ($findcategory->isEmpty()) {
    //                 $message = ['message' => 'تمت اضافة التخصص بنجاح', 'type' => 'alert-success'];
    //                 Usercategories::insert(['category_id' => $value, 'user_id' => Auth::id()]);
    //             } else {
    //                 $message = ['message' => 'تمت اضافة هذا التخصص مسبقا', 'type' => 'alert-danger'];
    //             }

    //             print_r(['category_id' => $value, 'user_id' => Auth::id()]);
    //         }

    //         return redirect()->back()->with($message);
    //     }
    // }


    function showSkills()
    {
        $profile = Profile::where('user_id', Auth::id())->first();
        $skills = Skill::where('is_active', 1)->get();
        $myskills = UserSkills::join('skills', 'skills.id', '=', 'user_skills.skill_id')->where('user_id', Auth::id())->get(['skills.name', 'user_skills.skill_id']);
        // $myskills = User::with(['skills'])->where('id', Auth::id())->get();
        return view('client.userProfile.editSkills')->with(['item' => $profile, 'skills' => $skills, 'myskills' => $myskills]);
    }


    function saveSkills(Request $request)
    {
        $skills = $request->skills;
        print_r($skills);
        if (blank($skills)) {
            return redirect()->back()->with(['message' => __('message.add_new_skill'), 'type' => 'alert-danger']);
        } else {
            $needToInsert = false;
            // insert if the skills are new
            foreach ($skills  as $value) {
                $findSkill = UserSkills::where('user_id', Auth::id())->where('skill_id', $value)->get();

                if ($findSkill->isEmpty()) {
                    $message = ['message' => __('messages.skill_added_success'), 'type' => 'alert-success'];
                    UserSkills::insert(['skill_id' => $value, 'user_id' => Auth::id()]);
                } else {
                    $message = ['message' => __('messages.skill_already_exists'), 'type' => 'alert-danger'];
                }

                print_r(['skill_id' => $value, 'user_id' => Auth::id()]);
            }

            return redirect()->back()->with($message);
        }
    }

    function deleteSkill($skill_id)
    {
        $skill = UserSkills::where(['skill_id' => $skill_id, 'user_id' => Auth::id()])->delete();

        return redirect()->back()->with(['message' => __('messages.skill_deleted_success'), 'type' => 'alert-danger']);
    }



    //  mywallete view
    function showMyWallet()
    {
        try {
            $profile = Profile::where('user_id', Auth::id())->first();
            $wallet = Wallet::where('holder_id', Auth::id())->first();
            $user = Auth::user();
            $userRoles = $user->roles;
            $userRole= $userRoles->first()->name;

            if (!$wallet) {
                $wallet = Wallet::create([
                    'holder_type' =>  $userRole,
                    'holder_id' =>  Auth::id(),
                    'name' => $profile->name . ' Wallet',
                    'slug' => 'default',
                    'uuid' => Uuid::uuid4()->toString(),
                    'balance' => 10000,
                    'decimal_places' => 2,
                ]);
            }

            $iAmSeeker = false;
            if (Auth::check() && Auth::user()->hasRole('seeker'))
                $iAmSeeker = true;
            $transactions_to_owner = Transfer::select(
                'from.name',
                'deposit.amount as dep_amount',
                'deposit.meta->project_id',
                'withdraw.meta->project_id',
                'withdraw.amount as with_amount',
                'transfers.created_at',
                'dep_post.title',
                'with_post.title',
            )
                // ->join('wallets as  wa1', 'wa1.id', '=', 'to_id')
                ->join('wallets as wa2', 'wa2.id', '=', 'from_id')
                ->join('profiles as from', 'from.user_id', '=', 'wa2.holder_id')
                // ->join('profiles as to', 'to.user_id', '=', 'wa2.holder_id')
                ->join('transactions as deposit', 'deposit.id', '=', 'transfers.deposit_id')
                ->join('transactions as withdraw', 'withdraw.id', '=', 'transfers.withdraw_id')
                ->join('projects as dep', 'dep.id', '=', 'deposit.meta->project_id')
                ->join('projects as with', 'with.id', '=', 'withdraw.meta->project_id')
                ->join('posts as dep_post', 'dep_post.id', '=', 'dep.post_id')
                ->join('posts as with_post', 'with_post.id', '=', 'with.post_id')
                // ->where($iAmSeeker ? 'from_id' : 'to_id', $wallet->id)
                ->where('to_id', $wallet->id)
                // ->orWhere('from_id', $wallet->id)
                ->get();
            $transactions_from_owner = Transfer::select(
                'to.name',
                'withdraw.meta->project_id',
                'withdraw.amount as with_amount',
                'transfers.created_at',
                'with_post.title',
            )
                ->join('wallets as  wa1', 'wa1.id', '=', 'to_id')
                // ->join('wallets as wa2', 'wa2.id', '=', 'from_id')
                // ->join('profiles as from', 'from.user_id', '=', 'wa2.holder_id')
                ->join('profiles as to', 'to.user_id', '=', 'wa1.holder_id')
                ->join('transactions as deposit', 'deposit.id', '=', 'transfers.deposit_id')
                ->join('transactions as withdraw', 'withdraw.id', '=', 'transfers.withdraw_id')
                ->join('projects as dep', 'dep.id', '=', 'deposit.meta->project_id')
                ->join('projects as with', 'with.id', '=', 'withdraw.meta->project_id')
                ->join('posts as dep_post', 'dep_post.id', '=', 'dep.post_id')
                ->join('posts as with_post', 'with_post.id', '=', 'with.post_id')
                // ->where($iAmSeeker ? 'from_id' : 'to_id', $wallet->id)
                // ->where('to_id', $wallet->id)
                ->where('from_id', $wallet->id)
                ->get();
            // return response()->json($transactions);
            return view('client.userProfile.myWallet')->with([
                'item' => $profile,
                'wallet' => $wallet,
                'deposit' => $transactions_to_owner,
                'withdraw' => $transactions_from_owner

            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return redirect()->back()->with(['message' => __('messages.time_limit_exceeded'), 'type' => 'alert-success']);
        } catch (\Throwable $th) {
            throw $th;
            return back()->with(['message' => __('messages.add_failed_message'), 'type' => 'alert-danger']);
        }
    }
}
