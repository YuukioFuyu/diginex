<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class SeoController extends Controller
{
    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'index' => 'seo-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    public function index()
    {
        $section = 'Seo Manage';
        $settings = config('settings')['seo'];

        return view('backend.page.setting', compact('settings', 'section'));
    }
}
