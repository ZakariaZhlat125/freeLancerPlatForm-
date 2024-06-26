<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Models\Profile;
use App\Models\Project;
use App\Models\User;
use App\Notifications\AcceptOfferNotification;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Expectation;

class ProjectController extends Controller
{
    //

    // this function to seeker to create the project aggregation /acceptance
    public function acceptOffer(Request $request)
    {
        $request->validate([
            'amount' => ['required']
        ], [
            'amount.required' => 'المبلغ المتفق عليه مطلوب *',
        ]);


        try {

            $project = new Project();
            $project->seeker_id = Auth::id();
            $project->provider_id = $request->provider_id;
            $project->offer_id = $request->offer_id;
            $project->status = 'pending';
            $project->amount = $request->amount;

            // Apply platform discount
            $project->totalAmount = $project->amount * 0.95;
            $project->duration = $request->duration;
            $project->post_id = $request->post_id;

            if ($project->save()) {

                return $this->showProviderConfirmation($request->provider_id, $request->offer_id, $project->id, $request->post_id);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return redirect()->back()->with(['message' => __('messages.time_limit_exceeded'), 'type' => 'alert-success']);
        } catch (\Exception $th) {
            return redirect()->back()->with(['message' => __('messages.accept_request_failed'), 'type' => 'alert-danger']);
        }
    }

    public function showProviderConfirmation($provider_id, $comment_id, $project_id, $post_id)
    {
        try {
            $project = Project::select(
                'comments.id',
                'comments.duration',
                'comments.cost',
                'comments.description as comment_description',
                'posts.title',
                'seeker_id',
                'profiles.name',
                'posts.description as post_description',
                'projects.offer_id',
                'projects.provider_id',
                'projects.id as project_id'
            )
                ->join('comments', 'comments.id', '=', 'projects.offer_id')
                ->join('posts', 'posts.id', '=', 'projects.post_id')
                ->join('profiles', 'profiles.user_id', '=', 'projects.seeker_id')
                ->where('comments.is_active', 1)
                ->where('comments.user_id', $provider_id)
                ->where('comments.id', $comment_id)
                ->where('posts.id', $post_id)
                ->where('projects.id', $project_id)
                ->first();

            $user = User::where('id', $provider_id);
            $profile = Profile::select('name')->where("user_id", $provider_id)->first();
            $data = [
                'name' =>  $profile->name,
                "project_id" => $project_id,
                "project_title" => $project->title,
                'message' => __('messages.offer_accept', ["Project" => $project->title]),
                'url' => url('/confirm-project/' . $project_id . '/' . $project->seeker_id),
                'userId' => $provider_id
            ];
            if ($project) {
                $user = User::where('id', $provider_id)->first();
                $user->notify(new AcceptOfferNotification($data));
                return redirect()->back()->with(['message' => __('messages.offer_acceptance_message_sent'), 'type' => 'alert-success']);
            } else {
                return redirect()->back()->with(['message' => __('messages.add_failed_message'), 'type' => 'alert-danger']);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return redirect()->back()->with(['message' => __('messages.time_limit_exceeded'), 'type' => 'alert-success']);
        } catch (\Exception $th) {
            return redirect()->back()->with(['message' => __('messages.add_failed_message'), 'type' => 'alert-danger']);
        }
    }



    // here the project show details to the provider
    function confirmProject($project_id, $seeker_id)
    {
        try {
            $projects = Project::select(
                'comments.id',
                'comments.duration',
                'comments.cost',
                'comments.description as comment_description',
                'posts.title',
                'posts.description as post_description',
                'projects.offer_id',
                'projects.amount',
                'projects.duration',
                'projects.seeker_id',
                'projects.status',
                'projects.id as project_id',
            )
                ->join('comments', 'comments.id', '=', 'projects.offer_id')
                ->join('posts', 'posts.id', '=', 'projects.post_id')
                ->where('posts.user_id', $seeker_id)
                ->where('projects.id', $project_id)
                ->where('projects.provider_id', Auth::id())
                ->first();

            // print_r($projects);
            // if ($projects->status == 'pending')
            return view('client.post.providerConfirmation')->with(['project' => $projects, 'amount' => $projects->amount]);
            // else {
            //     return redirect()->route('profile')->with(['message' => 'انت لمن تعد مصرح له بالدخول لهذه الصفحه ', 'type' => 'alert-danger']);
            // }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return redirect()->back()->with(['message' => __('messages.add_failed_message'), 'type' => 'alert-success']);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->route('profile')->with(['message' => __('messages.access.unauthorized'), 'type' => 'alert-danger']);
        }
    }



    // if ther use accept the project
    function acceptProject($project_id, $seeker_id)
    {
        // try {
        // notify the provider about the acceptence of the offer


        $project = Project::select(
            'posts.title',
            'projects.amount',
            'projects.totalAmount',
            'projects.seeker_id',
            'projects.provider_id',
            'projects.status',
            'projects.payment_status',
        )->join('posts', 'posts.id', 'projects.post_id')
            ->where('projects.seeker_id', $seeker_id)
            ->where('projects.id', $project_id)
            ->where('projects.payment_status', 'unpaid')
            ->where('projects.status', 'pending')
            ->first();




        $notify = new NotificationController();
        $notify->acceptTheProjectNotifiction($project);

        $provider = Profile::where('user_id', $project->provider_id)->first();
        $limitValue = $provider->limit;
        if ($limitValue <= 4 && $limitValue > 0) {
            $provider->limit =  $limitValue - 1;
        } else {
            $provider->limit = 0;
        }

        $provider->save();
        Project::where('id', $project_id)->update([
            'status' => 'at_work',
        ]);

        // Profile::where('user_id', Auth::id())
        //     ->where('limit', '<=', 4)
        //     ->where('limit', '>=', 0)
        //     ->decrement('limit');



        // return response()->json($seekerNotify);
        return redirect()->route('profile')->with(['message' => __('messages.acceptance_message_sent'), 'type' => 'alert-success']);
        // } catch (\Illuminate\Http\Client\ConnectionException $e) {
        //     return redirect()->back()->with(['message' => __('messages.time_limit_exceeded'), 'type' => 'alert-success']);
        // } catch (\Throwable $th) {
        //     return redirect()->route('profile')->with(['message' => __("messages.access.unauthorized"), 'type' => 'alert-danger']);
        // }
    }




    // if the provider reject the project
    function rejectProject($project_id, $seeker_id)
    {
        try {
            // notify the provider about the acceptence of the offer


            $project = Project::select(
                'posts.title',
                'projects.seeker_id',
                'projects.id as project_id'
            )->join('posts', 'posts.id', 'projects.post_id')
                ->where('projects.seeker_id', $seeker_id)
                ->where('projects.id', $project_id)
                ->first();

            $saveProj = Project::find($project_id);
            $saveProj->status = 'rejected';
            $saveProj->save();


            $notify = new NotificationController();
            $notify->rejectProjectNotifiction($project);

            return redirect()->route('profile')->with(['message' => __("messages.rejection_message_sent"), 'type' => 'alert-success']);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return redirect()->back()->with(['message' => __('messages.time_limit_exceeded'), 'type' => 'alert-success']);
        } catch (\Throwable $th) {
            return redirect()->route('profile')->with(['message' => __("messages.access.unauthorized"), 'type' => 'alert-danger']);
        }
    }




    function providerResponse(Request $request)
    {
        if ($request->input('confirm')) {
            $project = Project::where('offer_id', $request->offer_id)->update(['status' => 'at work']);
        } elseif ($request->input('reject')) {
            $project = Project::where('offer_id', $request->offer_id)->update(['status' => 'rejected']);
        }

        return redirect()->back();
    }
}
