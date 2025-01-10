<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ComponentContent;
use App\Models\Page;
use App\Services\ComponentService;

class PageController extends Controller
{
    public function page($slug)
    {
        $page = Page::where('slug', $slug)->first();

        if (! $page) {
            abort(404);
        }

        $componentIds = json_decode($page->component_id);
        $components = ComponentService::getComponent($componentIds);
        $title = $page->title;
        $isPageBreadcrumb = $page->is_breadcrumb;
        return view('frontend.page.show', compact('components', 'title', 'isPageBreadcrumb'));
    }

    public function details($id)
    {
        $isPageBreadcrumb = true;
        $componentContent = ComponentContent::with('component')->find($id);

        $componentId = $componentContent->component_id;
        $title = $componentContent->component->name.' Details';
        $section = $componentContent->component->section;
        $enContent = collect(json_decode($componentContent->content)->en);
        $translatedContent = collect(_tr($componentContent->content, false));
        $content = (object) $enContent->merge($translatedContent)->toArray();

        return view('frontend.page.details.index', compact('title', 'section', 'content', 'componentId', 'isPageBreadcrumb'));
    }
}
