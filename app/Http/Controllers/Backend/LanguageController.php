<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Language\StoreRequest;
use App\Http\Requests\Language\UpdateRequest;
use App\Models\Language;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use JoeDixon\Translation\Drivers\Translation;
use Str;
use Throwable;

class LanguageController extends Controller
{


    private Translation $translation;

    private string $languageFilesPath;

    public function __construct(Translation $translation)
    {
        $this->translation = $translation;
        $this->languageFilesPath = resource_path('lang');

        // Define permissions for each action
        $permissions = [
            'index' => 'language-list',
            'store' => 'language-manage',
            'edit' => 'language-manage',
            'update' => 'language-manage',
            'translate' => 'language-manage',
            'translatedUpdate' => 'language-manage',
            'syncMissingKeys' => 'language-manage',
            'destroy' => 'language-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    /**
     * Display a paginated listing of the Language resource.
     */
    public function index()
    {

        $languages = Language::paginate(10);

        return view('backend.languages.index', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {

        $input = $request->validated();

        if (isset($input['is_default']) && $input['is_default']) {
            Language::where('is_default', true)->update(['is_default' => false]);
        }

        $data = [
            'name' => $input['language_name'],
            'code' => Str::lower($input['language_code']),
            'is_default' => $input['is_default'] ?? 0,
            'is_rtl' => $input['is_rtl'] ?? 0,
            'status' => $input['status'] ?? 0,
        ];
        $this->translation->addLanguage($input['language_code'], $input['language_name']);
        Language::create($data);

        notifyEvs('success', __('Language Added Successfully'));

        return redirect()->route('admin.language.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws Throwable
     */
    public function edit($id)
    {
        $language = Language::find($id);

        return view('backend.languages.edit', compact('language'))->render();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {

        $input = $request->validated();
        $language = Language::find($id);

        if (! isset($input['is_default']) && $language->is_default) {
            notifyEvs('error', 'Must have one default language');

            return redirect()->route('admin.language.index');
        }

        if (isset($input['is_default']) && $input['is_default'] != $language->is_default) {
            Language::where('is_default', true)->update(['is_default' => false]);
        }

        if (! isset($input['status']) && $language->is_default) {
            notifyEvs('error', 'Default language must be active');

            return redirect()->route('admin.language.index');
        }
        $languageCode = $language->code == 'en' ? $language->code : Str::lower($input['language_code']);
        $data = [
            'name' => $input['language_name'],
            'code' => $languageCode,
            'is_default' => $input['is_default'] ?? 0,
            'is_rtl' => $input['is_rtl'] ?? 0,
            'status' => $input['status'] ?? 0,
        ];

        // Extract filename and directory name using pathinfo()
        $filePathInfo = pathinfo("{$this->languageFilesPath}/{$language->code}.json");
        $folderPathInfo = pathinfo("{$this->languageFilesPath}/{$language->code}");

        // Build new file and directory paths
        $newFileName = "{$this->languageFilesPath}/{$languageCode}.json";
        $newFolderName = "{$this->languageFilesPath}/{$languageCode}";

        // Rename both file and directory
        rename($filePathInfo['dirname'].'/'.$filePathInfo['basename'], $newFileName);
        rename($folderPathInfo['dirname'].'/'.$folderPathInfo['basename'], $newFolderName);

        $language->update($data);

        notifyEvs('success', 'Language Updated Successfully');

        return redirect()->route('admin.language.index');

    }

    public function translate(Request $request, $languageCode)
    {


        if ($request->has('language') && $request->get('language') !== $languageCode) {
            return redirect()
                ->route('admin.language.translate', ['code' => $request->get('language'), 'group' => $request->get('group'), 'filter' => $request->get('filter')]);
        }

        $languages = $this->translation->allLanguages();
        $groups = $this->translation->getGroupsFor(config('app.fallback_locale'))->merge('single');
        $translations = $this->translation->filterTranslationsFor($languageCode, $request->get('filter'));

        if ($request->filled('group')) {
            $group = $request->get('group');
            if ($group === 'single') {
                $translations = new Collection(['single' => $translations->get('single')]);
            } else {
                $translations = new Collection(['group' => $translations->get('group')->only($group)]);
            }
        }

        return view('backend.languages.translate', compact('languageCode', 'languages', 'groups', 'translations'));

    }

    public function translatedUpdate(Request $request)
    {
        $group = $request->group;
        $language = $request->language;
        $isGroupTranslation = ! Str::contains($group, 'single');

        $this->translation->add($request, $language, $isGroupTranslation);

        notifyEvs('success', __('Keyword Updated Successfully'));

        return redirect()->back();
    }

    public function syncMissingKeys()
    {
        \Artisan::call('translation:sync-missing-translation-keys');
        notifyEvs('success', __('Translation Keys Synced Successfully'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $language = Language::find($id);

        try {
            $this->authorize('delete', $language);

            $languageCode = $language->code;
            $languageFilePath = "{$this->languageFilesPath}/$languageCode.json";
            $languageDirectoryPath = "{$this->languageFilesPath}/$languageCode";

            // Delete the language file and directory
            File::deleteDirectory($languageDirectoryPath);
            File::delete($languageFilePath);

            // Delete the language record from the database
            $language->delete();

            $keys = [
                'home_page_components',
                'header_navigations',
                'footer_navigations',
                'services',
                'blogs',
                'languages',
                'languagesPluck',
            ];

            foreach ($keys as $key) {
                Cache::forget($key);
            }

            return response()->json(['status' => 'success', 'message' => __('Language Deleted Successfully')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }

    }
}
