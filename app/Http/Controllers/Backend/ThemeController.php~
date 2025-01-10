<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index()
    {
        $themes = collect(config('theme.list'));

        $homeComponentIds = json_decode(Page::where('slug', '/')->first('component_id')->component_id);

        return view('backend.theme.index', compact('themes', 'homeComponentIds'));
    }

    public function update(Request $request)
    {

        $name = $request->name;
        $themes = collect(config('theme.list'));
        $theme = $themes->get($name);

        $componentIds = $theme['component_ids'];

        $home = Page::where('slug', '/')->first();
        $home->component_id = json_encode($componentIds);
        $home->save();

        Setting::add('header_style', $theme['header']);
        Setting::add('site_appearance', $theme['color']);



        notifyEvs('success', __('Theme Updated Successfully'));

        return redirect()->route('admin.theme.index');

    }
}
