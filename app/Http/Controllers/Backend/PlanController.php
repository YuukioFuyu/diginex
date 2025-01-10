<?php

namespace App\Http\Controllers\Backend;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\StoreUpdateRequest;
use App\Models\Language;
use App\Models\Plan;
use App\Traits\FileManageTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class PlanController extends Controller
{
    use FileManageTrait;

    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'index' => 'plan-list',
            'store' => 'plan-manage',
            'edit' => 'plan-manage',
            'update' => 'plan-manage',
            'destroy' => 'plan-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve only required columns to optimize the query
        $plans = Plan::select('id', 'icon', 'monthly_price', 'yearly_price', 'name', 'status')->get();

        return view('backend.plan.index', compact('plans'));
    }

    /**
     * Store a newly created plan in storage.
     *
     * @param  StoreUpdateRequest  $request  The request containing plan data
     * @return RedirectResponse
     *
     * @throws Throwable
     */
    public function store(StoreUpdateRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validated = $this->prepareData($request);

            // Handle file uploads
            $validated = $this->handleFileUploads($request, $validated);

            // Handle multilingual fields
            $validated = $this->handleMultilingualFields($request, $validated);

            // Create plan
            Plan::create($validated);

            notifyEvs('success', __('Plan Created Successfully'));
        });

        return redirect()->route('admin.plan.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        $languages = Language::where('status', Status::ACTIVE)->pluck('name', 'code');

        return view('backend.plan.edit', compact('plan', 'languages'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws Throwable
     */
    public function update(StoreUpdateRequest $request, Plan $plan)
    {
        DB::transaction(function () use ($request, $plan) {
            $langCode = request('lang');
            $validated = $this->prepareData($request, $plan, $langCode);

            // Handle file uploads
            $validated = $this->handleFileUploads($request, $validated, $plan);

            // Handle multilingual fields
            $validated = $this->handleMultilingualFields($request, $validated, $plan, $langCode);

            // Update plan
            $plan->update($validated);

            notifyEvs('success', __('Plan Updated Successfully'));
        });

        return redirect()->route('admin.plan.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws Throwable
     */
    public function destroy(Plan $plan)
    {
        DB::transaction(function () use ($plan) {
            // Delete associated files
            collect([$plan->icon, $plan->background_shape])
                ->filter()
                ->each(fn ($image) => $this->deleteImage($image));

            // Delete plan
            $plan->delete();

            notifyEvs('success', __('Plan Deleted Successfully'));
        });

        return redirect()->route('admin.plan.index');
    }

    /**
     * Prepare validated data and handle status.
     */
    private function prepareData(StoreUpdateRequest $request, $plan = null, $langCode = null)
    {
        $isStaticDefaultLang = $langCode === config('app.static_default_language');

        return $request->validated() + [
            'status' => $plan && ! $isStaticDefaultLang ? $plan->status : (int) $request->has('status'),
        ];
    }

    /**
     * Handle file uploads for the plan.
     */
    private function handleFileUploads($request, array $validated, $plan = null)
    {
        $files = ['icon', 'background_shape'];
        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $validated[$file] = $this->uploadImage(
                    $request->file($file),
                    $plan ? $plan->$file : null
                );
            }
        }

        return $validated;
    }

    /**
     * Handle multilingual fields like name and features.
     */
    private function handleMultilingualFields($request, array $validated, $plan = null, $langCode = null)
    {
        $fields = ['name', 'features'];
        $fallbackLocale = config('app.fallback_locale');
        $langCode = $langCode ?? $fallbackLocale;

        foreach ($fields as $field) {
            // Merge existing plan data for multilingual fields
            $existingData = $plan ? $plan->$field : [];

            // Merge and set the validated data
            $validated[$field] = array_merge($existingData, [$langCode => $validated[$field]]);
        }

        return $validated;
    }
}
