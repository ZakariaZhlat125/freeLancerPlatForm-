<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\Comments;
use App\Models\Posts;
use App\Models\PostSkills;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Expectation;

class postController extends Controller
{
    //

    // this page show the list of the posts
    public function showAll()
    {
        $projects =  Posts::select(
            'posts.id',
            'posts.user_id',
            'posts.title',
            'posts.offers',
            'posts.description',
            'profiles.name',
            'profiles.user_id as provider_id'
        )->join('profiles', 'profiles.user_id', '=', 'posts.user_id')->where('is_active', 1)->where('status', 'open')->orderBy('id', 'DESC')->get();


        $cates = category::where('is_active', 1)->get();

        // return response()->json($cates);
        return view('client.user.projectlancer')->with(['posts' => $projects, 'categories' => $cates]);
    }




    // this route show one page
    public function showOne($post_id)
    {
        try {
            $post = Posts::select(
                'posts.*',
                'profiles.avatar',
                'profiles.name as post_user_name',
                'profiles.user_id as post_user_id',
                'profiles.job_title',
            )->join('profiles', 'profiles.user_id', 'posts.user_id')->where('id', (int)$post_id)->where('is_active', 1)->first();


            $skills = PostSkills::select('skills.name')
                ->join('skills', 'skills.id', '=', 'post_skills.skill_id')
                ->where('post_id', (int)$post_id)
                ->where('is_active', 1)
                ->get();

            $comments =  Comments::select(
                'profiles.name',
                'profiles.specialization',
                'profiles.rating',
                'profiles.user_id',
                'profiles.avatar',
                'profiles.limit',
                'comments.duration',
                'comments.cost',
                'comments.description',
                'comments.id as offer_id',
                'comments.user_id as provider_id',
            )
                ->join('profiles', 'profiles.user_id', '=', 'comments.user_id')
                ->where('post_id', (int)$post_id)
                ->get();
            $checkProject = Project::select(
                'status'
            )
                ->where('post_id', (int)$post_id)
                ->where('status', '!=', 'rejected')
                ->first();

            // print_r($comments);
            $hasComment = Comments::where('post_id', (int)$post_id)->where('user_id', Auth::id())->count();

            // return response()->json($post);
            return view('client.post.postDetails')->with([
                'post' => $post,
                'comments' => $comments,
                'post_id' => $post_id,
                'skills' => $skills,
                'hasComment' => $hasComment > 0 ? true : false,
                'checkHasProject' => $checkProject ? true : false
            ]);
        } catch (\Throwable $th) {
            return back()->with(['message' => ' هنالك مشكله ما رجاء قم باعاده المحاوله', 'type' => 'alert-danger']);
        }
    }



    // page for show the form of create new post
    public function index()
    {
        $user = auth()->user();
        $userProfile = $user->profile;
        if (!$userProfile) {
            return redirect(route('profile'))->with(['message' => __("profile.CompleteYourProfile"), 'type' => 'alert-danger']);
        }

        $skill = Skill::where('is_active', 1)->get();
        $categories = category::where('is_active', 1)->get();

        return view('client.post.post')->with(['skills' => $skill, 'categories' => $categories]);
    }



    public function save(Request $request)
    {
        try {
            $request->validate([
                'title' => ['required', 'min:15'],
                'category' => ['required'],
                'cost' => ['required'],
                'message' => ['required', 'min:100'],
                'duration' => ['required', 'numeric', 'gt:0'],
            ], [
                'title.required' => __('request.project.title.required'),
                'title.min' => __("request.title.min"),
                'title.max' => __('request.title.max'),
                'category.required' => __('request.category.required'),
                'cost.required' => __('request.cost.required'),
                'message.required' => __('request.project.details'),
                'message.min' => __('request.message.min'),
                'duration.required' => __('request.duration.required'),
                'duration.numeric' => __("request.duration.numeric"),
                'duration.gt' => __('request.duration.gt'),

            ]);



            $post = new Posts();
            $post->user_id = Auth::id();
            $post->title = $request->title;
            $post->description = $request->message;
            $post->cost = $request->cost;
            $post->duration = $request->duration;
            $post->category_id = $request->category;
            $post->status = 'open';

            if ($request->hasFile('files'))
                $post->file = $this->uploadFile($request->file('files'));

            if ($post->save()) {


                // ! send notification to all the users in the same category in the database except the owner
                // !still not working
                // $users = Profile::select('profiles.user_id')->join('categories', 'profiles.category_id', '=', 'categories.id')->where('categories.id', $request->category)->get();
                $users = User::select(
                    'users.id',
                    'users.name',
                    'categories.title'
                )
                    ->join('profiles', 'profiles.user_id', '=', 'users.id')
                    ->join('categories', 'profiles.category_id', '=', 'categories.id')
                    ->where('categories.id', $request->category)
                    ->get();
                // $users = User::with( 'inTheSameCategoriy')->where('category_id', $request->category)->get();
                $data = [
                    'category' => '',
                    'post_title' => $request->title,
                    'url' => url('posts/derails/' .  $post->id)
                ];
                // FacadesNotification::send($users, new PostNotification($data));
                // print_r($users);




                $skills = $request->skills;
                $needToInsert = false;
                // insert if the skills are new
                if (!blank($skills))
                    foreach ($skills  as $value) {
                        $findSkill = PostSkills::where('post_id', $post->id)->where('skill_id', $value)->get();

                        if ($findSkill->isEmpty()) {
                            PostSkills::insert(['skill_id' => $value, 'post_id' =>  $post->id]);
                        }
                    }

                return redirect('/posts/details/' . $post->id)
                    ->with(['message' => __('messages.project.add_success'), 'type' => 'alert-success']);
            } else
                return back()->with(['message' => __('messages.add_failed_message'), 'type' => 'alert-danger']);
        } catch (Expectation   $th) {
            // throw $th;
            return back()->with(['message' => __('messages.add_failed_message'), 'type' => 'alert-danger']);
        }
    }



    // file upload
    public function uploadFile($file)
    {
        $dest = public_path() . "/postsFiles/";

        //$file = $request->file('image');

        $filename = time() . "_" . $file->getClientOriginalName();
        $file->move($dest, $filename);

        return $filename;
    }




    // edit post
    public function editPosts($post_id)
    {
        $post = Posts::find($post_id);
        $skill = Skill::where('is_active', 1)->get();
        $categories = category::where('is_active', 1)->get();

        return view('client.post.editPost')->with(['data' => $post, 'skills' => $skill, 'categories' => $categories]);
    }




    public function showProject()
    {

        try {
            $project = Project::select(
                'posts.title',
                'projects.amount',
                'projects.id as project_id',
                'projects.seeker_id as seeker_id',
                'projects.provider_id as provider_id',
                'projects.totalAmount',
                'projects.status',
                'projects.payment_status',
                'projects.invoice',
                'projects.created_at',
                'projects.duration',
                'projects.post_id',
            )->join('posts', 'posts.id', 'projects.post_id')->get();
            // return response()->json($projects);
            return view('client.post.myProject')->with('projects', $project);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return redirect()->back()->with(['message' => __('messages.time_limit_exceeded'), 'type' => 'alert-success']);
        } catch (Expectation   $th) {
            // throw $th;
            return back()->with(['message' => __('messages.error_occurred'), 'type' => 'alert-danger']);
        }
    }


    public function update(Request $request, $post_id)
    {
        try {
            $request->validate([
                'title' => ['required', 'min:15'],
                'category' => ['required'],
                'cost' => ['required'],
                'message' => ['required', 'min:100'],
                'duration' => ['required', 'numeric', 'gt:0'],
            ], [

                'title.required' => __('request.project.title.required'),
                'title.min' => __("request.title.min"),
                'title.max' => __('request.title.max'),
                'category.required' => __('request.category.required'),
                'cost.required' => __('request.cost.required'),
                'message.required' => __('request.project.details'),
                'message.min' => __('request.message.min'),
                'duration.required' => __('request.duration.required'),
                'duration.numeric' => __("request.duration.numeric"),
                'duration.gt' => __('request.duration.gt'),
            ]);



            $post = Posts::find($post_id);
            $post->user_id = Auth::id();
            $post->title = $request->title;
            $post->description = $request->message;
            $post->cost = $request->cost;
            $post->duration = $request->duration;
            $post->category_id = $request->category;

            if ($request->hasFile('files'))
                $post->file = $this->uploadFile($request->file('files'));
            if ($post->save()) {


                return redirect()->route('myProject')
                    ->with(['message' => __('messages.project.update_success'), 'type' => 'alert-success']);
            } else
                return back()->with(['message' => __('messages.update_failed_message'), 'type' => 'alert-danger']);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return redirect()->back()->with(['message' => __('messages.time_limit_exceeded'), 'type' => 'alert-success']);
        } catch (Expectation   $th) {
            // throw $th;
            return back()->with(['message' => __('messages.update_failed_message'), 'type' => 'alert-danger']);
        }
    }



    public function toggle($post_id)
    {

        $post = Posts::find($post_id);
        $post->is_active *= -1;
        if ($post->save())
            return redirect()->route('myProject')->with(['message' => __('messages.project.delete_success'), 'type' => 'alert-success']);
        return back()->with(['message' => __('messages.delete_failed_message'), 'type' => 'alert-danger']);
    }
}
