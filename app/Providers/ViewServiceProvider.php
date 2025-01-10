<?php

namespace App\Providers;

use App\Constants\Navigation;
use App\Constants\Status;
use App\Models\Blog;
use App\Models\Language;
use App\Models\Navigation as SiteNavigation;
use App\Models\Plugin;
use App\Models\Social;
use App\Services\ComponentService;
use Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        // Caching plugin data
        $this->composePluginData();

        // Composing top bar, header, and footer data
        $this->composeTopBarData();
        $this->composeHeaderData();
        $this->composeFooterData();

        // Composing blog data
        $this->composeBlogData();

        // Composing language data
        $this->composeLanguageData();

        // Set theme
        $this->setTheme();

    }

    /**
     * Compose plugin data.
     */
    private function composePluginData(): void
    {
        View::composer('frontend.partials.plugin._index', function ($view) {
            $plugins = Cache::rememberForever('plugins_data', function () {
                return Plugin::whereIn('code', ['tawk', 'fb', 'google-analytics'])
                    ->get()
                    ->keyBy('code')
                    ->map(function ($plugin) {
                        return [
                            'data' => $plugin->credentials,
                            'status' => $plugin->status,
                        ];
                    });
            });

            $view->with('plugins', $plugins);
        });
    }

    /**
     * Compose top bar data.
     */
    private function composeTopBarData(): void
    {
        View::composer('frontend.layouts.partials._top_bar', function ($view) {
            $socials = Social::where('status', Status::TRUE)->get();
            $contactInfo = ComponentService::getComponent([17])->first();
            $view->with(compact('socials', 'contactInfo'));
        });
    }

    /**
     * Compose header data.
     */
    private function composeHeaderData(): void
    {
        View::composer('frontend.layouts.partials._header_'.setting('header_style'), function ($view) {
            $navigations = $this->getNavigations(Navigation::HEADER);
            $contactInfo = ComponentService::getComponent([17])->first();
            $view->with(compact('navigations', 'contactInfo'));
        });
    }

    /**
     * Compose footer data.
     */
    private function composeFooterData(): void
    {
        View::composer(['frontend.layouts.partials._footer', 'frontend.layouts.partials._sidebar'], function ($view) {
            $navigations = $this->getNavigations(Navigation::FOOTER);
            $socials = Social::where('status', Status::TRUE)->get();
            $components = ComponentService::getComponent([17]);
            $contactInfo = $components->where('section', 'contact')->first();
            $view->with(compact('navigations', 'socials', 'contactInfo'));
        });
    }

    /**
     * Compose blog data.
     */
    private function composeBlogData(): void
    {
        View::composer(['frontend.page.component._blog', 'frontend.page.component._blog_list', 'frontend.blog.partial._side_bar', 'frontend.page.component._blog_grid'], function ($view) {
            $blogs = Cache::rememberForever('blogs', function () {
                return Blog::where('is_active', Status::TRUE)
                    ->orderBy('created_at', 'desc')
                    ->get();
            });
            $view->with('blogs', $blogs);
        });
    }

    /**
     * Compose language data.
     */
    private function composeLanguageData(): void
    {
        View::composer(['backend.layouts.partials._navbar', 'frontend.layouts.partials._sidebar', 'frontend.layouts.user.partials._navbar_end_options', 'frontend.layouts.partials._header_'.setting('header_style')], function ($view) {
            $languages = Cache::rememberForever('languages', function () {
                return Language::where('status', Status::TRUE)->get();
            });
            $view->with('languages', $languages);
        });
    }

    /**
     * Get navigations.
     */
    private function getNavigations(string $type): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::rememberForever($type.'_navigations', function () use ($type) {
            return SiteNavigation::where('is_active', Status::TRUE)
                ->whereIn('display', [$type, Navigation::BOTH])
                ->orderBy($type.'_order')
                ->get();
        });
    }

    private function setTheme()
    {
        View::composer(['frontend.layouts.app', 'frontend.page.component*'], function ($view) {
            $theme = request()->url() === request()->root().'/dark-home' ? 'dark_mode' : setting('site_appearance');
            $view->with('theme', $theme);
        });
    }
}
