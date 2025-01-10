<?php

namespace App\Http\Controllers\Frontend;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function details($slug)
    {
        $isPageBreadcrumb = true;
        $blog = Blog::where('slug', $slug)->first();

        if (! $blog) {
            abort(404);
        }

        return view('frontend.blog.details', compact('blog', 'isPageBreadcrumb'));
    }

    public function filter(Request $request)
    {
        $isPageBreadcrumb = true;
        $blogsQuery = Blog::query()
            ->where('is_active', Status::TRUE)
            ->when($request->category, function ($query, $category) {
                return $query->where('category_id', $category);
            })
            ->when($request->search, function ($query, $searchTerm) {
                $searchTerm = '%'.$searchTerm.'%';

                return $query->where(function ($query) use ($searchTerm) {
                    $query->where('title', 'like', $searchTerm)
                        ->orWhere('summary', 'like', $searchTerm)
                        ->orWhere('content', 'like', $searchTerm);
                });
            })
            ->when($request->tag, function ($query, $tag) {
                return $query->whereJsonContains('tag', ['value' => $tag]);
            });

        $results = $blogsQuery->orderByDesc('created_at')->paginate(10);
        return view('frontend.blog.filter', compact('results', 'isPageBreadcrumb'));

    }
}
