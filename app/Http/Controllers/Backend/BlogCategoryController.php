<?php

namespace App\Http\Controllers\Backend;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Language;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{

    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'index' => 'blog-category-list',
            'store' => 'blog-category-manage',
            'edit' => 'blog-category-manage',
            'update' => 'blog-category-manage',
            'destroy' => 'blog-category-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogCategories = BlogCategory::all();

        return view('backend.blog.category.index', compact('blogCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = \Validator::make($request->all(), [
            'category_name' => 'required|unique:blog_categories,name',
        ]);

        if ($validated->fails()) {
            notifyEvs('error', $validated->errors()->first());

            return redirect()->back();
        }


        $validatedData = $validated->getData();
        $blogCategory = new BlogCategory;
        $blogCategory->name = json_encode([config('app.static_default_language') => $validatedData['category_name']]);
        $blogCategory->is_active = (isset($validatedData['is_active']) && $validatedData['is_active'] == Status::TRUE) ? Status::TRUE : Status::FALSE;
        $blogCategory->save();
        notifyEvs('success', __('Blog Category Created Successfully'));

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $blogCategory = BlogCategory::find($id);

        $languages = Language::where('status', Status::ACTIVE)->pluck('name', 'code');

        return view('backend.blog.category.edit', compact('blogCategory', 'languages'))->render();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'lang' => 'required|string',
            'category_name' => 'required|unique:blog_categories,name,'.$id,
            'is_active' => 'sometimes|boolean',
        ]);

        $blogCategory = BlogCategory::findOrFail($id);

        $lang = $request->input('lang');
        $name = modify_trans_data($blogCategory->name);
        $name[$lang] = $request->input('category_name');

        $is_active = $request->filled('is_active') && $request->input('is_active') == Status::TRUE;

        // Update the blog category
        $blogCategory->name = $name;
        $blogCategory->is_active = $is_active;
        $blogCategory->save();

        notifyEvs('success', __('Blog Category Updated Successfully'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $blogCategory = BlogCategory::find($id);
        $blogCategory->delete();

        return response()->json(['status' => 'success', 'message' => __('Blog Category Deleted Successfully')]);
    }
}
