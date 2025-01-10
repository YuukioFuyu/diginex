<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\FileManageTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    use FileManageTrait;

    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'index' => 'staff-list',
            'store' => 'staff-create',
            'edit' => 'role-edit',
            'update' => 'role-edit',
        ];
        $this->permissionAuthorization($permissions);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request The HTTP request object.
     * @return View The view for the staff index.
     */
    public function index(Request $request)
    {

        // Retrieve the latest admin records and paginate them.
        $staffs = Admin::with('roles')->latest()->paginate(10);


        if ($request->input('search')) {
            $staffs = Admin::where('name', 'like', '%' . $request->input('search') . '%')
                ->orWhere('email', 'like', '%' . $request->input('search') . '%')
                ->with('roles')
                ->latest()
                ->paginate(5);
        }

        $roles = Role::where('name', '!=', 'super-admin')->pluck('name', 'name')->all();

        // Return the view for the staff index with the paginated data.
        return view('backend.staff.index', compact('staffs', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $input = $request->validate([
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|same:confirm_password',
            'role' => 'required',
            'status' => 'boolean',
        ]);

        if ($request->hasFile('avatar')) {
            $input['avatar'] = isset($input['avatar']) ? self::uploadImage($input['avatar']) : null;
        }

        $input['password'] = Hash::make($input['password']);

        $input['status'] = $request->input('status') ? 1 : 0;
        $user = Admin::create($input);
        $user->assignRole($request->input('role'));

        notifyEvs('success', __('Staff created successfully'));

        return redirect()->route('admin.staff.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $staff = Admin::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $staffRole = $staff->roles->pluck('name', 'name')->first();

        return view('backend.staff.partials._update_data', compact('staff', 'roles', 'staffRole'))->render();
    }

    /**
     * Update the specified staff member in storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the input data
        $input = $request->validate([
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => 'same:confirm_password',
            'role' => 'required',
            'status' => 'boolean',
        ]);

        // Hash the password if provided
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            // Remove the password field from the input if not provided
            $input = Arr::except($input, ['password']);
        }

        // Find the staff member by ID
        $staff = Admin::find($id);


        if ($staff->id == 1 && ($input['role'] !== 'super-admin' || !isset($input['status']) || $input['status'] !== 1)) {
            notifyEvs('error', __('You can not change super-admin role or status.'));
            return redirect()->route('admin.staff.index');
        }

        // Upload avatar and cover images if provided
        if ($request->hasFile('avatar')) {
            $oldAvatar = $staff->avatar == 'general/static/default/avatar.png' ? null : $staff->avatar;
            $input['avatar'] = isset($input['avatar']) ? self::uploadImage($input['avatar'], $oldAvatar) : $staff->avatar;
        }

        // Set the status field based on the input value
        $input['status'] = $request->input('status') ? 1 : 0;

        // Update the staff member with the new data
        $staff->update($input);

        // Delete existing roles for the staff member
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        // Assign the new role to the staff member
        $staff->assignRole($request->input('role'));

        // Display a success message
        notifyEvs('success', __('Staff updated successfully'));

        // Redirect to the staff index page
        return redirect()->route('admin.staff.index');
    }
}
