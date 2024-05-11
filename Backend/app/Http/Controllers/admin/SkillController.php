<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkillController extends Controller
{
    //
    public function list_skills()
    {
        $skills = Skill::get();
        return view('admin.skills.index')->with('skills', $skills);
    }


    public function add_skill()
    {

        return view('admin.skills._form');
    }


    public function store(Request $request)
    {

        Validator::validate($request->all(), [
            'name' => ['required', 'max:25'],
            'is_active' => ['required'],


        ], [
            'name.required' => __('request.skill.name.required'),
            'name.max' => __('request.skill.name.max'),
        ]);

        $skill = new Skill();
        $skill->name = $request->name;
        $skill->is_active = $request->is_active;


        if ($skill->save())
            return redirect()->route('list_skills')
                ->with(['message' => __('messages.skill_added_success'), 'type' => 'alert-success']);
        return back()->with(['message' => __("messages.add_failed_message"), 'type' => 'alert-danger']);
    }


    public function edit($skill_id)
    {
        $skill = Skill::find($skill_id);
        return view('admin.skills._form')->with(['data' => $skill]);
    }


    public function update(Request $request, $skill_id)
    {

        $skill = Skill::find($skill_id);
        $skill->name = $request->name;
        $skill->is_active = $request->is_active;

        if ($skill->save())
            return redirect()->route('list_skills')->with(['message' => __("messages.update_skill_success"), 'type' => 'alert-success']);
        return redirect()->back()->with(['message' => __("messages.update_failed_message"), 'type' => 'alert-danger']);
    }


    public function toggle($skill_id)
    {
        $skill = Skill::find($skill_id);
        $skill->is_active *= -1;
        if ($skill->save())
            if ($skill->is_active == -1)
                return back()->with(['message' => __('messages.skill_disabled_success'), 'type' => 'alert-success']);
            else
                return back()->with(['message' => __('messages.skill_enabled_success'), 'type' => 'alert-success']);
        return back()->with(['message' =>  __("messages.delete_failed_message"), 'type' => 'alert-danger']);
    }
}
