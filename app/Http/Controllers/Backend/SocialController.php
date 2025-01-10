<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Social;
use Illuminate\Http\Request;
use Validator;

class SocialController extends Controller
{
    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'index' => 'social-list',
            'store' => 'social-manage',
            'edit' => 'social-manage',
            'update' => 'social-manage',
            'destroy' => 'social-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $socials = Social::all();

        return view('backend.social.index', compact('socials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'icon_class' => 'required',
            'target' => 'required',
            'url' => 'required',
            'status' => 'integer|in:0,1',
        ]);

        if ($validator->fails()) {
            notifyEvs('error', $validator->messages()->first());

            return redirect()->back()->withInput();
        }

        $validatedData = $validator->validate();
        $social = new Social;
        $social->fill($validatedData);
        $social->status = $validatedData['status'] ?? 0;
        $social->save();
        notifyEvs('success', __('Social Link Added Successfully'));

        return redirect()->route('admin.social.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $social = Social::find($id);

        return view('backend.social.edit', compact('social'))->render();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'icon_class' => 'required',
            'target' => 'required',
            'url' => 'required',
            'status' => 'integer|in:0,1',
        ]);

        if ($validator->fails()) {
            notifyEvs('error', $validator->messages()->first());

            return redirect()->back()->withInput();
        }

        $validatedData = $validator->validate();
        $social = Social::find($id);
        $social->fill($validatedData);
        $social->status = $validatedData['status'] ?? 0;
        $social->save();
        notifyEvs('success', __('Social Link Updated Successfully'));

        return redirect()->route('admin.social.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $social = Social::find($id);
        $social->delete();

        return response()->json(['status' => 'success', 'message' => __('Social Link deleted successfully')]);
    }
}
