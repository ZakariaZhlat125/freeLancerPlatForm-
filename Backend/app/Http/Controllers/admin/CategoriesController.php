<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class CategoriesController extends Controller
{
    //

    public function list_category()
    {
        /**
         * !should this show only the item that are active?
         */
        $categories = category::get();
        return view('admin.categories.index')
            ->with('categories', $categories);
    }

    public function add_category()
    {
        return view('admin.categories._form');
    }


    public function edit($cat_id)
    {
        $category = category::find($cat_id);
        return view('admin.categories._form')
            ->with('data', $category);
    }



    public function toggle($cat_id)
    {

        $cat = category::find($cat_id);

        /**
        ! please is this mean delete?
         */
        $cat->is_active *= -1;


        if ($cat->save())
            if ($cat->is_active == -1)
                return back()->with(['message' => __('messages.department_disabled_message'), 'type' => 'alert-success']);
            else
                return back()->with(['message' => __('messages.department_enabled_message'), 'type' => 'alert-success']);
        return back()->with(['message' => __('messages.delete_failed_message'), 'type' => 'alert-danger']);
    }

    public function store(Request $request)
    {
        FacadesValidator::validate($request->all(), [
            'title' => ['required'],

        ], [
            'title.required' => 'this field is required',
        ]);

        $new_cat = new category();
        $new_cat->title = $request->title;

        $new_cat->is_active = $request->is_active;

        if ($new_cat->save())
            return redirect()->route('list_categories')->with(['message' => __('messages.department_added_success'), 'type' => 'alert-success']);
        return redirect()->back()->with(['message' => __('messages.add_failed_message'), 'type' => 'alert-danger']);
    }


    public function update(Request $request, $cat_id)
    {
        $cat = category::find($cat_id);
        $cat->title = $request->title;

        $cat->is_active = $request->is_active;

        if ($cat->save())
            return redirect()->route('list_categories')->with(['message' => __('messages.update_success'), 'type' => 'alert-success']);
        return redirect()->back()->with(['message' =>  __('messages.update_failed_message'), 'type' => 'alert-danger']);
    }
}
