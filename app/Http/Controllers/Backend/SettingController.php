<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'index' => 'site-settings-view',
            'update' => 'site-settings-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    /**
     * Display a listing of the settings.
     *
     * @return Factory
     */
    public function index()
    {

        // Fetch settings from the configuration file
        $settings = collect(config('settings'))->reject(function ($setting) {
            return isset($setting['setting_page']) && $setting['setting_page'] === false;
        })->toArray();

        // Map over the settings array to extract the 'icon' key from each setting
        $settingMenus = array_map(function ($setting) {
            return $setting['icon'];
        }, $settings);

        // Return the view with the settings and setting menus
        return view('backend.settings.site.index', compact('settings', 'settingMenus'));
    }

    public function update($section, Request $request, SettingService $settingService)
    {

        try {
            // Update settings using the service
            $settingService->update($section, $request);

            // Build the success message
            $message = title($section).__(' Updated Successfully');

            // Notify the user of the success
            notifyEvs('success', $message);

            // Redirect back with the section
            return redirect()->back()->with('section', $section);

        } catch (Exception $e) {
            // Notify the user of the error
            notifyEvs('error', $e->getMessage());

            // Redirect back
            return redirect()->back()->with('section', $section);
        }
    }
}
