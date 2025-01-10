<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'index' => 'role-list',
            'store' => 'role-create',
            'create' => 'role-create',
            'edit' => 'role-edit',
            'update' => 'role-edit',
            'destroy' => 'role-delete',
        ];
        $this->permissionAuthorization($permissions);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request  The HTTP request object.
     * @return View The view containing the list of roles.
     */
    public function index(Request $request): View
    {
        // Retrieve the roles from the database, ordered by ID in descending order.
        // The results are paginated with 5 roles per page.
        $roles = Role::orderBy('id', 'DESC')->paginate(8);

        // Calculate the starting index of the current page.
        $startIndex = ($request->input('page', 1) - 1) * 8;

        // Pass the roles and the starting index to the view.
        return view('backend.role.index', compact('roles'))
            ->with('i', $startIndex);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Retrieve all permissions and group them by category
        $permissions = Permission::get()->groupBy('category');

        // Return the view for creating a new role with the permissions
        return view('backend.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $validate = $request->validate([
            'role_name' => 'required|unique:roles,name',
            'description' => 'required',
            'permission' => 'required|array',
            'permission.*' => 'integer',
        ]);

        // Extract and convert permission values to integers
        $permissionsID = array_map(
            function ($value) {
                return (int) $value;
            },
            $validate['permission']
        );

        // Create a new role with the provided name
        $role = Role::create(['name' => $request->input('role_name'), 'description' => $validate['description']]);
        // Sync the permissions for the created role
        $role->syncPermissions($permissionsID);

        // Notify the user about successful role creation
        notifyEvs('success', 'Role created successfully');

        // Redirect to the role index page after successful creation
        return redirect()->route('admin.role.index');
    }

    /**
     * Show the form for editing a specific role.
     *
     * @param  int  $id  The ID of the role to edit.
     * @return View The view for editing the role.
     */
    public function edit(int $id): View
    {
        // Find the role by ID
        $role = Role::find($id);

        // Get all permissions grouped by category
        $permissions = Permission::get()->groupBy('category');

        // Get the IDs of the permissions associated with the role
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        // Return the view for editing the role, passing the role, permissions, and role permissions as data
        return view('backend.role.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update a role in storage.
     *
     * @param  Request  $request  The HTTP request.
     * @param  int  $id  The ID of the role to update.
     * @return RedirectResponse The redirect response.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'role_name' => 'required', // Ensure the role name is provided
            'description' => 'required', // Ensure the description is provided
            'permission' => 'required', // Ensure the permission list is provided
        ]);

        // Find the role by ID and update its name
        $role = Role::find($id);
        $role->name = $request->input('role_name');
        $role->description = $request->input('description');
        $role->save(); // Save the updated role

        // Convert the permission values to integers and sync them with the role
        $permissionsID = array_map(
            fn ($value) => (int) $value, // Convert permission values to integers
            $request->input('permission') // Get the permission list from the request
        );
        $role->syncPermissions($permissionsID); // Sync the permissions with the role

        // Notify the user about successful role update
        notifyEvs('success', 'Role updated successfully'); // Send a success notification

        // Redirect to the role index page with a success message
        return redirect()->route('admin.role.index'); // Redirect to the role index page
    }

    /**
     * Delete a role from the database.
     *
     * @param  int  $id  The ID of the role to delete.
     * @return RedirectResponse A JSON response indicating the success or failure of the deletion.
     */
    public function destroy(int $id)
    {
        // Retrieve the role from the database
        $role = Role::find($id);

        // Check if the role is the super-admin role
        if ($role->name !== 'super-admin' && ! $role->users()->exists()) {
            // Delete the role from the database
            $role->delete();

            // Notify the user about successful role deletion
            notifyEvs('success', 'Role deleted successfully');

            // Return a success response
            return redirect()->route('admin.role.index');
        }

        // If the role is assigned to at least one user, show a message to update the role
        notifyEvs('error', 'Please remove this role from all staff before deleting it.');

        // Return an error response if the role is assigned to at least one user
        return redirect()->route('admin.role.index');
    }
}
