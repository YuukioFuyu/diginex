<?php

namespace App\View\Components;

use Closure;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class Svg extends Component
{
    /**
     * Create a new component instance.
     */
    public $name;

    public $width;

    public $height;

    public $class;

    public function __construct($i, $width = null, $height = null, $class = '')
    {
        $this->name = $i;
        $this->width = $width;
        $this->height = $height;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.icon');
    }

    /**
     * Get the SVG content from the cache or from disk.
     *
     * First, this method checks the cache for the SVG content. If the cache is empty,
     * it will then attempt to read the SVG file from the disk.
     */
    public function svgContent(): ?string
    {
        $cacheKey = "svg.{$this->name}";
        return Cache::rememberForever($cacheKey, function () {
            try {
                // Define the SVG file path relative to the custom disk
                $svgPath = "{$this->name}.svg";
                return Storage::disk('svg_assets')->get($svgPath);
            } catch (Exception $e) {
                // Return null if there's an error reading the file
                return null;
            }
        });
    }
}
