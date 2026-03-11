<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{

    public function index()
    {
        // Check for System Admin role immediately
        if (auth()->user()->role !== 'Admin') {
            abort(403, 'Unauthorized. Only Admins can manage users.');
        }

        $users = User::with('region')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // Only System Admins can access this
        if (auth()->user()->role !== 'Admin') {
            abort(403);
        }

        $regions = \App\Models\Region::all();
        return view('users.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:Admin,Coordinator,Member,President'],
            'region_id' => ['required_if:role,Coordinator,President,Member', 'nullable', 'exists:regions,id'],
            'position' => 'nullable|string|max:255',
        ]);

        // Prevent duplicate role/position assignments in the same region
        $exists = User::where('region_id', $request->region_id)
            ->where('role', $request->role)
            ->where('position', $request->position)
            ->whereNotNull('region_id')
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'role' => "This region already has an assigned {$request->role}."
            ]);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'region_id' => $request->region_id,
            'position' => $request->position,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        $regions = Region::all();
        return view('users.edit', compact('user', 'regions'));
    }

    public function accept(User $user)
    {
        // Authorization check
        if (auth()->user()->role !== 'Admin') {
            abort(403);
        }

        $user->update([
            'is_accepted' => true,
            'accepted_by' => auth()->id(),
            'accepted_at' => now(),
        ]);

        return back()->with('success', "Access granted for {$user->name}.");
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:Admin,President,Coordinator,Member', // Updated roles
            'region_id' => 'nullable|required_unless:role,Admin|exists:regions,id',
        ]);

        $user->update([
            'name' => $request->name,
            'role' => $request->role,
            // Admins stay global (null), others must have a region
            'region_id' => $request->role === 'Admin' ? null : $request->region_id,
        ]);

        return redirect()->route('users.index')
            ->with('success', "Updated {$user->name} to {$request->role}.");
    }
}