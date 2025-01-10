<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\ComponentService;
use Cache;
use Redirect;

class HomeController extends Controller
{
    public function index()
    {

        // Check if home_redirect is set and redirect if needed
        if (setting('home_redirect') !== '/') {
            return Redirect::to(setting('home_redirect'));
        }

        // Fetch data from cache or database
        $data = Cache::rememberForever('home_page_components', function () {
            $page = Page::where('slug', '/')->first();
            $components = [];
            $title = '';

            if ($page) {
                $components = ComponentService::getComponent(json_decode($page->component_id));
                $title = $page->title;
            }

            return compact('components', 'title');
        });
        $title = $data['title'];
        $components = $data['components'];

        return view('frontend.index', compact('components', 'title'));
    }
}
