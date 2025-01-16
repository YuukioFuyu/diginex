<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Support\Facades\Cache;

class LocaleController extends Controller
{
    public function setLocale($locale)
    {
        // Retrieve language by code
        $language = Language::where('code', $locale)->first();

        if (! $language) {
            notifyEvs('error', __('Invalid Language'));

            return redirect()->back();
        }

        // Forget multiple cache keys at once
        $keys = [
            'home_page_components',
            'header_navigations',
            'footer_navigations',
            'services',
            'blogs',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Store locale and direction in session
        session(['locale' => $locale, 'dir' => $language->is_rtl ? 'rtl' : 'ltr']);

        // notifyEvs('success', __('Language Changed'));

        return redirect()->back();

    }
}
