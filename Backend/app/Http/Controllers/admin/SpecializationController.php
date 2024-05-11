<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpecializationController extends Controller
{
    //

    public function list_specialization()
    {
        $categories = category::where('is_active', 1)->get();
        $specializations = specialization::get();
        return view('admin.specializations.index')->with(['specializations' => $specializations, 'categories' =>  $categories]);
    }

    public function add_specialization()
    {
        $categories = category::where('is_active', 1)->get();
        return view('admin.specializations._form')->with('categories', $categories);
    }

    public function edit($special_id)
    {
        $specialization = specialization::find($special_id);
        $categories = category::where('is_active', 1)->get();

        return view('admin.specializations._form')
            ->with(['data' => $specialization, 'categories' =>  $categories]);
    }

    public function toggle($special_id)
    {

        $special = specialization::find($special_id);
        $special->is_active *= -1;


        if ($special->save())
            if ($special->is_active == -1)
                return back()->with(['message' => __('messages.specialization_disabled_success'), 'type' => 'alert-success']);
            else
                return back()->with(['message' => __('messages.specialization_enabled_success'), 'type' => 'alert-success']);
        return back()->with(['message' => __('messages.delete_failed_message'), 'type' => 'alert-danger']);
    }


    public function store(Request $request)
    {
        Validator::validate($request->all(), [
            'title' => ['required'],
            'category_id' => ['required'],
        ], [
            'title.required' => __('request.field.required'),
            'category_id.required' => __('request.field.required'),
        ]);

        $new_special = new specialization();
        $new_special->title = $request->title;
        $new_special->category_id = $request->category_id;
        $new_special->is_active = $request->is_active;

        if ($new_special->save())
            return redirect()->route('list_specialization')->with(['message' => __('messages.new_department_added_success'), 'type' => 'alert-success']);
        return redirect()->back()->with(['message' =>  __('messages.add_failed_message'), 'type' => 'alert-danger']);
    }

    public function update(Request $request, $special_id)
    {
        $special = specialization::find($special_id);
        $special->title = $request->title;
        $special->category_id = $request->category_id;
        $special->is_active = $request->is_active;

        if ($special->save())
            return redirect()->route('list_specialization')->with(['message' => __('messages.department_updated_success'), 'type' => 'alert-success']);
        return redirect()->back()->with(['message' => __('messages.update_failed_message'), 'type' => 'alert-danger']);
    }
}
