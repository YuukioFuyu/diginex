<?php

use App\Models\Language;
use App\Models\Page;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Fluent;

if (! function_exists('notifyEvs')) {
    function notifyEvs($type, $message)
    {
        session()->flash('notifyevs', [
            'type' => $type,
            'message' => $message,
        ]);
    }
}

if (! function_exists('isActive')) {
    function isActive(array|string $route, mixed $parameter = null, string $class = 'active'): string
    {
        // If the parameter is provided, check the base URL match
        if ($parameter !== null) {
            return request()->url() === route($route, $parameter) ? $class : '';
        }

        // Handle routes that don't require parameters
        if (is_array($route)) {
            return Route::is(...$route) ? $class : '';
        }

        // For a single route, check if the current route matches
        return Route::is($route) ? $class : '';
    }

}

if (! function_exists('setting')) {

    function setting($key, $default = null)
    {
        if (is_null($key)) {
            return new \App\Models\Setting;
        }

        if (is_array($key)) {
            return \App\Models\Setting::set($key[0], $key[1]);
        }

        $value = \App\Models\Setting::get($key);

        return is_null($value) ? value($default) : $value;
    }
}

/**
 * Get select options based on the type.
 *
 * @param  string  $type  The type of options to retrieve.
 * @return array The array of options based on the given type.
 */
if (! function_exists('setting_select_options')) {
    function setting_select_options(string $type): array
    {

        $currencies = collect(getJsonData('currencies')[setting('site_currency_type')])
            ->mapWithKeys(fn ($currency) => [
                "{$currency['code']} : {$currency['name']} " => $currency['code'],
            ])
            ->all();

        $formattedTimeZones = collect(getJsonData('timezone'))->mapWithKeys(function ($item) {
            return ["{$item['utc']} - {$item['name']}" => $item['zone']];
        })->toArray();

        // Define the options based on the type
        return match ($type) {
            'mail_secure' => ['ssl', 'tls'],
            'site_environment' => ['local', 'production'],
            'site_currency_type' => ['fiat', 'crypto'],
            'site_currency' => $currencies ?? ['pty'],
            'home_redirect', 'cookie_page' => Page::getSlugs(),
            'header_style' => ['style_1', 'style_2'],
            'site_preloader' => ['basic', 'parallax', 'none'],
            'site_appearance' => ['dark_mode', 'light_mode'],
            'site_timezone' => $formattedTimeZones ?? [],
            default => [],
        };
    }
}

if (! function_exists('pluginCredentials')) {

    function pluginCredentials($pluginCode)
    {
        return \App\Models\Plugin::credentials($pluginCode);
    }
}

if (! function_exists('getJsonData')) {

    /**
     * Reads and decodes JSON data from a file.
     *
     * @param  string  $fileName  The name of the JSON file.
     * @return array The decoded JSON data as an associative array.
     *
     * @throws Exception If the file is not found or cannot be read.
     */
    function getJsonData(string $fileName): array
    {
        // Construct the file path
        $filePath = resource_path("json/{$fileName}.json");

        // Check if the file exists
        if (! file_exists($filePath)) {
            throw new \Exception("File not found: {$filePath}");
        }

        // Read the JSON content from the file
        $jsonContent = file_get_contents($filePath);

        // Check if the content was successfully read
        if ($jsonContent === false) {
            throw new \Exception("Could not read file: {$filePath}");
        }

        // Decode the JSON content and return as an associative array
        return json_decode($jsonContent, true);
    }
}

if (! function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function asset($path, $secure = null)
    {
        // Define valid image file extensions
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

        // Retrieve file extension from given path
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        // Check if the file has an image extension
        if (in_array(strtolower($extension), $imageExtensions)) {
            // Determine the appropriate format of the new URL
            $cdnPath = 'cdn-cgi/image/format=auto,quality=75/assets/' . $path;

            // Using app(`url`)->asset to build the right URL
            return app('url')->to($cdnPath, $secure);
        }

        // If not an image, return the URL as normal
        return app('url')->asset('assets/' . $path, $secure);
    }
}

if (! function_exists('demoImage')) {
    function demoImage($name = null): string
    {
        if (is_null($name)) {
            return 'general/static/placeholder.png';
        }

        return 'general/static/'.$name.'.png';
    }

}

if (! function_exists('paginate')) {
    function paginate($items, $perPage = 10)
    {
        $page = request()->input('page', 1);
        $currentPath = request()->path();

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage), // Get the items for the current page
            $items->count(), // Total number of items
            $perPage, // Number of items per page
            $page,
            ['path' => '/'.$currentPath]// Current page
        );
    }
}

if (! function_exists('is_default_lang')) {
    function is_default_lang($lang, $class, $default = '')
    {
        if ($lang == config('app.static_default_language')) {
            return $class;
        }

        return $default;
    }
}
if (! function_exists('_tr')) {
    function _tr($data, $associative = true)
    {
        $defaultLanguage = config('app.static_default_language');
        $locale = app()->getLocale();

        // If the data is an array
        if (is_array($data)) {
            return $data[$locale] ?? $data[$defaultLanguage] ?? null;
        }

        // Decode JSON data
        $data = json_decode($data, $associative);

        // Return the translated string based on locale or default language
        return $associative
            ? ($data[$locale] ?? $data[$defaultLanguage] ?? null)
            : ($data->$locale ?? $data->$defaultLanguage ?? null);
    }

}

if (! function_exists('filterRichText')) {
    function filterRichText($html): string
    {
        // Remove all HTML tags, keeping only the text
        $text = strip_tags($html);

        // Optionally, you can also decode HTML entities (e.g., converting &amp; back to &)
        $text = html_entity_decode($text);

        // Trim whitespace
        return trim($text);
    }
}

if (! function_exists('modify_trans_data')) {
    function modify_trans_data($data, $associative = true)
    {
        $data = json_decode($data, $associative);

        $validLanguages = Language::languageGet()->keys();

        return $associative
            ? collect($data)->only($validLanguages)->all()
            : (object) collect($data)->only($validLanguages)->all();

    }

}

if (! function_exists('title_case')) {

    function title_case($string): string
    {
        return Str::title(str_replace('_', ' ', $string));
    }
}

if (! function_exists('title')) {
    function title($title)
    {
        return Str::of(__($title))
            ->lower()                  // Convert to lowercase
            ->replace(['_', '-'], ' ') // Replace underscores and hyphens with spaces
            ->title()
            ->transliterate();
    }
}

if (! function_exists('getCountries')) {

    function getCountries()
    {
        // Cache the country data for a day (or any duration you prefer)
        return Cache::remember('country_codes', 86400, function () {
            // Path to the country codes JSON file
            $path = resource_path('json/country_codes.json');

            // Check if the file exists before trying to read it
            if (file_exists($path)) {
                // Decode the JSON file into an associative array
                return json_decode(file_get_contents($path), true);
            }

            // Log an error or return a fallback value if the file does not exist
            \Log::error('CountryCodes.json file not found', ['path' => $path]);

            // Return an empty array if the file is missing or unreadable
            return [];
        });
    }
}

if (! function_exists('getLocation')) {
    function getLocation()
    {
        // Get client IP, fallback to a default IP if running locally
        $clientIp = Request::ip();
        $ip = $clientIp === '127.0.0.1' ? '103.77.188.202' : $clientIp;

        try {
            // Make HTTP request to get the location data
            $response = Http::timeout(5)->get("http://ip-api.com/json/{$ip}");

            // Check if the response is successful
            if ($response->successful()) {
                $locationData = $response->json();

                // Find the matching country in your getCountries() data
                $currentCountry = collect(getCountries())->firstWhere('code', $locationData['countryCode'] ?? null);

                // Structure the location data
                $location = [
                    'country_code' => $currentCountry['code'] ?? null,
                    'name' => $currentCountry['name'] ?? null,
                    'dial_code' => $currentCountry['dial_code'] ?? null,
                    'ip' => $locationData['query'] ?? $ip,
                ];

                return new Fluent($location);
            } else {
                // Log non-successful responses
                Log::warning('IP API failed', ['ip' => $ip, 'status' => $response->status()]);
            }
        } catch (\Exception $e) {
            // Log the exception if the API call fails
            Log::error('IP API error', ['ip' => $ip, 'error' => $e->getMessage()]);
        }

        // Return default or fallback data in case of an error
        return new Fluent([
            'country_code' => null,
            'name' => 'Unknown',
            'dial_code' => null,
            'ip' => $ip,
        ]);
    }
}

if (! function_exists('maskEmail')) {
    function maskEmail(string $email): string
    {
        // Return the original email if the app is not in demo mode
        if (! config('app.demo')) {
            return $email;
        }

        // Split the email into the local part and domain
        [$localPart, $domain] = explode('@', $email);

        $localPartLength = strlen($localPart);

        // If the local part has only one or two characters, return the original email without masking
        if ($localPartLength <= 2) {
            return $email;
        }

        // Get the first and last characters of the local part
        $firstChar = substr($localPart, 0, 1);
        $lastChar = substr($localPart, -1);

        // Calculate the number of characters to mask and generate that many dots
        $dots = str_repeat('.', $localPartLength - 2);

        // Mask the email
        $maskedLocalPart = $firstChar.$dots.$lastChar;

        return "{$maskedLocalPart}@{$domain}";
    }

}

if (! function_exists('footer_copyright')) {
    function footer_copyright()
    {
        // Retrieve copyright text from settings
        $footer_copyright_text = setting('copyright');

        // Replace the {copy} placeholder with the HTML &copy symbol;
        $footer_copyright_text = str_replace('{copy}', '&copy;', $footer_copyright_text);

        // Replace placeholder {year} with the current year
        $footer_copyright_text = str_replace('{year}', date('Y'), $footer_copyright_text);

        return $footer_copyright_text;
    }
}

