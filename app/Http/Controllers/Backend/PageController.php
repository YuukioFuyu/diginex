<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageComponent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use View;

class PageController extends Controller
{
    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'index' => 'page-list',
            'create' => 'page-manage',
            'store' => 'page-manage',
            'edit' => 'page-manage',
            'update' => 'page-manage',
            'settingType' => 'page-manage',
            'error404' => 'page-manage',
            'destroy' => 'page-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    /**
     * Retrieves all pages and renders the page index view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pages = Page::all();

        return view('backend.page.index', compact('pages'));
    }

    /**
     * Renders the page create view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $components = PageComponent::where('status', 1)->get()->sortBy('sort');
        $componentCategory = $components->pluck('category')->unique();

        return view('backend.page.create', compact('components', 'componentCategory'));
    }

    /**
     * Creates a new page.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), ([
            'page_title' => 'required',
            'component' => 'required',
        ]));

        if ($validator->fails()) {
            notifyEvs('error', $validator->errors()->first());

            return redirect()->back()->withInput();
        }

        $validateData = $validator->getData();
        $page = new Page;
        if ($page->where('slug', $validateData['page_slug'])->exists()) {
            notifyEvs('error', __('Same Slug Already Exists'));

            return redirect()->back()->withInput();
        }
        $page->is_breadcrumb = $validateData['is_breadcrumb'] ?? 0;
        $page->title = $validateData['page_title'];
        $page->slug = Str::slug($validateData['page_slug']);
        $page->component_id = json_encode($validateData['component']);
        $page->save();
        notifyEvs('success', __('Page Created Successfully'));

        return redirect()->route('admin.page.site.index');
    }

    /**
     * Renders the page edit view.
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $page = Page::find($id);
        $pageComponentIds = json_decode($page->component_id, true);

        $components = PageComponent::where('status', 1)->get();

        $componentCategory = $components->pluck('category')->unique();

        $componentsAvailable = $components->reject(function ($component) use ($pageComponentIds) {
            return in_array($component->id, $pageComponentIds);
        })->sortBy('sort');

        $pageComponents = $components->whereIn('id', $pageComponentIds)
            ->sortBy(function ($component) use ($pageComponentIds) {
                return array_search($component->id, $pageComponentIds);
            });

        return view('backend.page.edit', compact('page', 'componentsAvailable', 'pageComponents', 'componentCategory'));
    }

    /**
     * Updates the page.
     *
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), ([
            'page_title' => 'required',
            'component' => 'required',
        ]));

        if ($validator->fails()) {
            notifyEvs('error', $validator->errors()->first());

            return redirect()->back()->withInput();
        }

        $validateData = $validator->getData();

        $page = Page::find($id);
        $page->is_breadcrumb = $validateData['is_breadcrumb'] ?? 0;
        $page->title = $validateData['page_title'];
        $page->meta_title = $validateData['meta_title'] ?? null;
        $page->meta_description = $validateData['meta_description'] ?? null;
        $page->meta_keywords = $validateData['tags'] ?? null;
        $page->component_id = json_encode($validateData['component']);
        $page->is_active = $request->is_active ?? 0;
        $page->slug = $page->slug != '/' ? Str::slug($validateData['page_slug']) : $page->slug;
        $page->save();

        notifyEvs('success', __('Page Updated Successfully'));

        return redirect()->back();
    }

    public function destroy($id)
    {
        $page = Page::find($id);
        if ($page->slug == '/') {
            notifyEvs('error', __('Home Page Cannot be Deleted'));

            return redirect()->back();
        }
        $page->delete();

        notifyEvs('success', __('Page Deleted Successfully'));

        return redirect()->back();
    }

    public function settingType($setting_type)
    {
        $section = $setting_type;
        $settings = config('settings')[$section];

        return view('backend.page.setting', compact('settings', 'section'));
    }

    public function error404()
    {
        return view('backend.page.404');
    }
}
