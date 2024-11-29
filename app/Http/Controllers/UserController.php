<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();  // Eager load roles for display
// dd($users);
        return Inertia::render('Users/Index', [
            'users' => $users
        ]);
    }

    // Show the form for creating a new user
    public function create()
    {
        $roles = Role::all();  // Get all roles

        return Inertia::render('Users/Edit', [
            'roles' => $roles
        ]);
    }

    // Store a newly created user in storage
    public function store(Request $request)
    {
        $validated = $this->validateUser($request); // Validate user input

        if ($validated->fails()) {
            return redirect()->route('users.create')
                ->withErrors($validated)
                ->withInput();
        }

        // Create user and hash the password
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign roles
        $user->syncRoles($request->roles);

        return redirect()->route('users.index');
    }

    // Show the form for editing the specified user
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all(); // Get all roles

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    // Update the specified user in storage
    public function update(Request $request, $id)
    {
        $validated = $this->validateUser($request, $id); // Validate user input

        if ($validated->fails()) {
            return redirect()->route('users.edit', $id)
                ->withErrors($validated)
                ->withInput();
        }

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        // Sync roles
        $user->syncRoles($request->roles);

        return redirect()->route('users.index');
    }

    // Delete the specified user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index');
    }

    // Assign roles to a user
    public function assignRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $user->syncRoles($request->roles);

        return response()->json(['message' => 'Roles assigned successfully']);
    }

    // Validate user input
    private function validateUser(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => "required|email|max:255|unique:users,email,$id",
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name', // Ensure roles exist
        ];

        return Validator::make($request->all(), $rules);
    }
}
