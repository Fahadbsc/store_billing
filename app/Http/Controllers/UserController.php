<?php
// backend/app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::orderBy('name')->get(['id','name','username','email','role','is_active','created_at']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|unique:users|max:50',
            'email'    => 'nullable|email|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,cashier',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'is_active' => true,
        ]);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name'      => 'sometimes|string|max:255',
            'username'  => "sometimes|string|unique:users,username,{$id}|max:50",
            'email'     => "nullable|email|unique:users,email,{$id}",
            'password'  => 'nullable|string|min:6',
            'role'      => 'sometimes|in:admin,cashier',
            'is_active' => 'sometimes|boolean',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Cannot delete your own account'], 400);
        }
        $user->update(['is_active' => false]);
        return response()->json(['message' => 'User deactivated']);
    }
}
