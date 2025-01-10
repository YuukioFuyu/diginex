<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use Illuminate\Http\Request;

class PluginController extends Controller
{
    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'index' => 'plugin-list',
            'pluginType' => 'plugin-list',
            'edit' => 'plugin-manage',
            'update' => 'plugin-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->getPluginsView('general');
    }

    public function pluginType($plugin_type)
    {
        return $this->getPluginsView($plugin_type);
    }

    private function getPluginsView($pluginType)
    {
        $plugins = Plugin::where('type', $pluginType)->get();

        return view('backend.settings.plugin.index', compact('plugins'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $plugin = Plugin::find($id);

        return view('backend.settings.plugin.partials.__manage_append', compact('plugin'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'credentials' => 'required|array',
            'fields' => 'nullable|array',
        ]);

        // Handle optional fields and encode them to JSON if present
        if (isset($validatedData['fields'])) {
            $validatedData['credentials']['fields'] = $validatedData['fields'];
        }

        // Find and update the plugin
        $plugin = Plugin::findOrFail($id);
        $plugin->update([
            'credentials' => $validatedData['credentials'],
            'status' => $request->input('status', 0), // Set status to 0 if not provided
        ]);

        if ($plugin->type == 'mail_provider' && $plugin->status == 1) {
            Plugin::where('type', 'mail_provider')->where('id', '!=', $id)->update(['status' => 0]);
        }

        // Notify success and redirect back
        notifyEvs('success', __('Info Updated Successfully'));

        return redirect()->back();
    }
}
