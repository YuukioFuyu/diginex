<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SiteCustom;
use Illuminate\Http\Request;

class SiteCustomController extends Controller
{
    public function type($type)
    {
        $data = SiteCustom::value($type);
        $notify = [
            'css' => __("On this page, you can modify the UI's appearance using CSS. Editing content here demands programming expertise"),
            'script' => __("On this page, you can modify the UI's appearance using JavaScript. Editing content here demands programming expertise"),
        ];
        $data->notify = $notify[$type];
        return view('backend.custom.index', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'value' => 'required',
        ]);

        $type = $request->type;
        $value = $request->value;
        $is_active = $request->input('is_active', 0);

        // Find the existing site custom record by type (assuming record always exists)
        $siteCustom = SiteCustom::where('type', $type)->firstOrFail();

        // Update the existing record
        $siteCustom->update([
            'value' => $value,
            'is_active' => $is_active,
        ]);

        // Notify success
        notifyEvs('success', __('Updated successfully'));

        return back();
    }
}
