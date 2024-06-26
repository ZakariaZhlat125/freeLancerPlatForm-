<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Comments;
use App\Models\Posts;
use App\Models\Profile;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\ApiErrorException;
use Pusher\Pusher;
use Ramsey\Uuid\Uuid;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\Mailer\Exception\TransportException;

class CommentsController extends Controller
{
    //


    public function save(Request $request)
    {
        try {
            $request->validate([
                'cost' => ['required', 'numeric', 'max:1000000'],
                'duration' => ['required', 'numeric', 'max:1000000'],
            ], [
                'cost.required' => __('request.cost.required'),
                'duration.required' => __('request.duration.required'),
                'duration.numeric' => __('request.duration.numeric'),
            ]);

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


            $comment = new Comments();
            $comment->user_id = Auth::id();
            $comment->post_id = $request->post_id;
            $comment->cost = $request->cost;
            $comment->duration = $request->duration;
            $comment->description = $request->message;
            $comment->is_active = 1;
            $comment->cost_after_taxs = $request->cost / 0.5;

            $comments  = Comments::where('post_id', $request->post_id)->count();

            // update the offer numbers
            Posts::where('id', $request->post_id)->update([
                'offers' => $comments + 1
            ]);

            if ($comment->save()) {
                $postOwner = User::select(
                    'posts.id',
                    'posts.title',
                    'posts.user_id as userid',
                    'profiles.name as user_name'
                )->join('posts', 'posts.user_id', '=', 'users.id')
                    ->join('profiles', 'profiles.user_id', '=', 'users.id')
                    ->where('posts.id', $request->post_id)
                    ->first();

                $user = User::join('profiles', 'profiles.user_id', 'users.id')->where('id', $postOwner->userid)->first();
                $provider = Profile::select('name')->where('user_id', Auth::id())->first();
                // return response()->json($user);
                $data = [
                    'name' =>  $postOwner->user_name,
                    'post_title' => $postOwner->title,
                    'message' => __('messages.comment_added', ['provider' => $provider->name, 'title' => $postOwner->title]),
                    'url' => url('posts/details/' . $postOwner->id),
                    'userId' => $postOwner->userid
                ];

                $user->notify(new CommentNotification($data));

                return redirect()->back()
                    ->with(['message' => __('messages.offer_added_success'), 'type' => 'alert-success']);
            } else {
                return back()->with(['message' => __('messages.add_failed_message'), 'type' => 'alert-danger']);
            }

        } catch (FatalError $e) {
            return redirect()->back()->with(['message' => __('messages.add_failed_message'), 'type' => 'alert-success']);
        } catch (ApiErrorException $e) {
            return redirect()->back()->with(['message' => __('messages.check_internet_connection'), 'type' => 'alert-success']);
        } catch (TransportException $e) {
            return redirect()->back()->with(['message' =>  __('messages.check_internet_connection'), 'type' => 'alert-success']);
        } catch (\Throwable $th) {
            throw $th;
            return back()->with(['message' => __('messages.add_failed_message'), 'type' => 'alert-danger']);
        }
    }
    // update comment

    public function update(Request $request, $comment_id)
    {

        try {
            $request->validate([

                'cost' => ['required', 'numeric'],
                'duration' => ['required', 'numeric'],
                'message' => ['required'],
            ], [
                'cost.required' => __('request.cost.required'),
                'duration.required' => __('request.duration.required'),
                'duration.numeric' => __('request.duration.numeric'),
                'message.required' => __('request.details.required'),
                // 'message.min' => 'حقل الوصف يجب ان يحتوي على 255 حرف على الاقل',
            ]);

            $comment = Comments::find($comment_id);
            // $comment->user_id = Auth::id();

            //   $comment->user_id = Auth::id();
            // $comment->post_id = $request->post_id;
            $comment->cost = $request->cost;
            $comment->duration = $request->duration;
            $comment->description = $request->message;
            $comment->is_active = 1;
            $comment->cost_after_taxs = $request->cost / 0.5;

            if ($comment->save()) {
                return redirect()->back()
                    ->with(['message' => __('messages.offer_updated_success'), 'type' => 'alert-success']);
            } else
                return back()->with(['message' => __('messages.update_failed_message'), 'type' => 'alert-danger']);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return redirect()->back()->with(['message' => __('messages.time_limit_exceeded'), 'type' => 'alert-success']);
        } catch (\Throwable $th) {
            return back()->with(['message' => __('messages.update_failed_message'), 'type' => 'alert-danger']);
        }
    }
}
