<?php

namespace App\Http\Controllers\Backend;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Language;
use App\Traits\FileManageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class BlogController extends Controller
{
    use FileManageTrait;

    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'index' => 'blog-list',
            'create' => 'blog-manage',
            'store' => 'blog-manage',
            'edit' => 'blog-manage',
            'update' => 'blog-manage',
            'destroy' => 'blog-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::with('category')->paginate(10);

        return view('backend.blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BlogCategory::where('is_active', Status::TRUE)->get();

        return view('backend.blog.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = \Validator::make($request->all(), [

            'cover' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required',
            'title' => 'required',
            'slug' => 'required|unique:blogs,slug',
            'tag' => 'required',
            'summary' => 'required',
            'content' => 'required',
        ]);

        if ($validated->fails()) {
            notifyEvs('error', $validated->errors()->first());

            return redirect()->back();
        }

        $validatedData = $request->all();

        if ($request->hasFile('cover')) {
            $validatedData['cover'] = self::uploadImage($validatedData['cover']);
        }

        $validatedData['slug'] = Str::slug($validatedData['slug']);

        $lang = config('app.static_default_language');

        $title = [$lang => $validatedData['title']];
        $summary = [$lang => $validatedData['summary']];

        $content = [$lang => Purifier::clean(htmlspecialchars_decode($validatedData['content']))];

        $validatedData['title'] = json_encode($title);
        $validatedData['summary'] = json_encode($summary);
        $validatedData['content'] = json_encode($content);

        $validatedData['is_active'] = $request->filled('is_active') ? Status::TRUE : Status::FALSE;
        $validatedData['author_id'] = auth()->user()->id;
        Blog::create($validatedData);
        notifyEvs('success', 'Blog Created Successfully');

        return redirect()->route('admin.blog.site.index');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $blog = Blog::find($id);
        $categories = BlogCategory::where('is_active', Status::TRUE)->get();
        $languages = Language::where('status', Status::ACTIVE)->pluck('name', 'code');

        return view('backend.blog.edit', compact('blog', 'categories', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = \Validator::make($request->all(), [
            'cover' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required_if:lang,'.config('app.static_default_language'),
            'title' => 'required',
            'slug' => 'required_if:lang,'.config('app.static_default_language').'|unique:blogs,slug,'.$id,
            'tag' => 'required_if:lang,'.config('app.static_default_language'),
            'summary' => 'required',
            'content' => 'required',
        ]);

        if ($validated->fails()) {
            notifyEvs('error', $validated->errors()->first());

            return redirect()->back();
        }

        $validatedData = $request->all();

        $blog = Blog::find($id);
        if ($request->hasFile('cover')) {
            $validatedData['cover'] = self::uploadImage($validatedData['cover'], $blog->cover);
        }

        $lang = $validatedData['lang'];

        $title = modify_trans_data($blog->title);
        $title[$lang] = $validatedData['title'];

        $summary = modify_trans_data($blog->summary);
        $summary[$lang] = $validatedData['summary'];

        $content = modify_trans_data($blog->content);
        $content[$lang] = Purifier::clean(htmlspecialchars_decode($validatedData['content']));

        $validatedData['slug'] = $request->filled('slug') ? Str::slug($validatedData['slug']) : $blog->slug;
        $validatedData['title'] = json_encode($title);
        $validatedData['summary'] = json_encode($summary);
        $validatedData['content'] = json_encode($content);
        $validatedData['is_active'] = ($request->filled('is_active') or $lang !== config('app.static_default_language')) ? Status::TRUE : Status::FALSE;
        $validatedData['author_id'] = auth()->user()->id;

        $blog->update($validatedData);
        notifyEvs('success', 'Blog Updated Successfully');

        return redirect()->route('admin.blog.site.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);
        if ($blog->cover) {
            self::deleteImage($blog->cover);
        }
        $blog->delete();

        return response()->json(['status' => 'success', 'message' => __('Blog deleted successfully')]);
    }
}
