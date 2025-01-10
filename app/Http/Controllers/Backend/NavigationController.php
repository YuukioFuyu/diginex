<?php

namespace App\Http\Controllers\Backend;

use App\Constants\Navigation;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\NavigationRequest;
use App\Models\Language;
use App\Models\Navigation as SiteNavigation;
use App\Models\Page;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Throwable;

class NavigationController extends Controller
{
    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'index' => 'navigation-list',
            'store' => 'navigation-manage',
            'edit' => 'navigation-manage',
            'update' => 'navigation-manage',
            'type' => 'navigation-list',
            'positionUpdate' => 'navigation-manage',
            'positionRemove' => 'navigation-manage',
            'destroy' => 'navigation-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::where('is_active', 1)->get();
        $navigations = SiteNavigation::all();

        return view('backend.navigation.index', compact('pages', 'navigations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NavigationRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            notifyEvs('error', $request->validator->errors()->first());

            return redirect()->back()->withInput();
        }

        $isCustomUrl = ! $request->has('is_custom_url');

        $validatedData = $request->validated();

        $slug = $validatedData['custom_url'];

        if ($isCustomUrl || $request->filled('page_id')) {
            $page = Page::find($validatedData['page_id']);
            $slug = $page->slug != '/' ? '/'.$page->slug : $page->slug;
        }

        $maxHeaderOrder = SiteNavigation::max('header_order');
        $maxFooterOrder = SiteNavigation::max('footer_order');

        // Determine the position based on the type
        if ($validatedData['navigation_display'] == Navigation::BOTH) {
            // For Both type, increment both header and footer positions
            $validatedData['header_order'] = $maxHeaderOrder + 1;
            $validatedData['footer_order'] = $maxFooterOrder + 1;
        } elseif ($validatedData['navigation_display'] == Navigation::HEADER) {
            // For Header type, only increment header position
            $validatedData['header_order'] = $maxHeaderOrder + 1;
        } else {
            // For Footer type, only increment footer position
            $validatedData['footer_order'] = $maxFooterOrder + 1;
        }

        if (SiteNavigation::exists('slug', $slug)) {
            notifyEvs('error', 'Navigation Slug already exists');

            return redirect()->back()->withInput();
        }

        $navigationName = json_encode([config('app.static_default_language') => $validatedData['navigation_name']]);
        SiteNavigation::create(['name' => $navigationName, 'slug' => $slug, 'page_id' => $isCustomUrl ? $validatedData['page_id'] : null, 'header_order' => $validatedData['header_order'] ?? 0, 'footer_order' => $validatedData['footer_order'] ?? 0, 'display' => $validatedData['navigation_display'], 'target' => $validatedData['target_link'], 'is_active' => $validatedData['status'] ?? 0]);

        notifyEvs('success', __('Navigation added successfully'));

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $languages = Language::where('status', Status::ACTIVE)->pluck('name', 'code');
        $pages = Page::where('is_active', 1)->get();
        $navigation = SiteNavigation::find($id);

        return view('backend.navigation.partial._edit_append', compact('navigation', 'pages', 'languages'))->render();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NavigationRequest $request, string $id)
    {

        // Check if validation fails
        if ($request->validator && $request->validator->fails()) {
            notifyEvs('error', $request->validator->errors()->first());

            return redirect()->back()->withInput();
        }

        // Process validated data
        $validatedData = $request->validated();

        // Get navigation by ID
        $navigation = SiteNavigation::findOrFail($id);

        // Update navigation name with the current language
        $lang = $request->input('lang');
        $oldName = modify_trans_data($navigation->name);
        $oldName[$lang] = $validatedData['navigation_name'];
        $navigation->name = $oldName;

        // Process navigation fields if default language matches the current language
        if (config('app.static_default_language') == $lang) {
            $isCustomUrl = $request->has('is_custom_url');
            $slug = $validatedData['custom_url'];

            // If not a custom URL, fetch page slug
            if (! $isCustomUrl && $request->filled('page_id')) {
                $slug = '/'.Page::find($validatedData['page_id'])->slug ?: '/';
            }

            // Update header and footer positions based on display type
            if ($validatedData['navigation_display'] != $navigation->type) {
                $maxHeaderPosition = SiteNavigation::max('header_order');
                $maxFooterPosition = SiteNavigation::max('footer_order');

                $headerOrder = ($validatedData['navigation_display'] == Navigation::BOTH || $validatedData['navigation_display'] == Navigation::HEADER) ? ($maxHeaderPosition + 1) : 0;
                $footerOrder = ($validatedData['navigation_display'] == Navigation::BOTH || $validatedData['navigation_display'] == Navigation::FOOTER) ? ($maxFooterPosition + 1) : 0;
            } else {
                $headerOrder = $navigation->header_order;
                $footerOrder = $navigation->footer_order;
            }

            if (SiteNavigation::exists('slug', $slug, $id)) {
                notifyEvs('error', 'Navigation Slug already exists');

                return redirect()->back()->withInput();
            }

            // Update navigation fields
            $navigation->slug = $slug;
            $navigation->page_id = ! $isCustomUrl ? $validatedData['page_id'] : null;
            $navigation->display = $validatedData['navigation_display'];
            $navigation->target = $validatedData['target_link'];
            $navigation->header_order = $headerOrder;
            $navigation->footer_order = $footerOrder;
            $navigation->is_active = $validatedData['status'] ?? 0;
        }

        // Save navigation changes
        $navigation->save();

        // Notify success and redirect back
        notifyEvs('success', __('Navigation updated successfully'));

        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $navigation = SiteNavigation::find($id);
        $navigation->delete();

        notifyEvs('success', __('Navigation deleted successfully'));

        return redirect()->back();
    }

    // ============================ extra functions ===========================

    public function type($type)
    {
        $navigations = SiteNavigation::orderedBy($type)->get();

        return view('backend.navigation.type', compact('type', 'navigations'));
    }

    /**
     * Update the position of multiple navigation items.
     *
     * @return RedirectResponse
     *
     * @throws Throwable
     */
    public function positionUpdate(Request $request)
    {
        $inputs = $request->validate(['navigation_ids' => ['required', 'array'], 'navigation_ids.*' => ['integer'], 'type' => ['required', 'string', Rule::in(['header', 'footer'])]]);

        $i = 1;

        DB::transaction(function () use ($inputs, $i) {
            foreach ($inputs['navigation_ids'] as $id) {
                $navigation = SiteNavigation::findOrFail($id);
                $column = $inputs['type'] == Navigation::HEADER ? 'header_order' : 'footer_order';
                $navigation->update([$column => $i]);
                $i++;
            }
        });

        notifyEvs('success', __('Navigation position updated successfully'));

        return redirect()->back();

    }

    /**
     * Remove the position of a navigation item.
     *
     * @return RedirectResponse
     */
    public function positionRemove(Request $request)
    {
        $inputs = $request->validate(['id' => ['required', 'integer'], 'type' => ['required', 'string', Rule::in([Navigation::HEADER, Navigation::FOOTER])]]);

        $navigation = SiteNavigation::findOrFail($inputs['id']);
        if ($navigation->display == $inputs['type']) {
            notifyEvs('error', 'This Menu Only Available '.ucwords($inputs['type']).' Position. Can Not Delete');

            return redirect()->route('admin.navigation.type', $inputs['type']);
        }
        $navigation->update(['header_order' => $inputs['type'] == Navigation::HEADER ? 0 : $navigation->header_order, 'footer_order' => $inputs['type'] == Navigation::FOOTER ? 0 : $navigation->footer_order, 'display' => $inputs['type'] == Navigation::HEADER ? Navigation::FOOTER : Navigation::HEADER]);

        notifyEvs('success', __('Navigation position removed successfully'));

        return redirect()->route('admin.navigation.type', $inputs['type']);
    }
}
