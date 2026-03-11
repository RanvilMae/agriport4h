<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Region; // Import the Region model
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Fetch all regions to populate the dropdown in the register view
        $regions = Region::orderBy('id', 'asc')->get();

        return view('auth.register', compact('regions'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate all incoming fields
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:Admin,Coordinator,Member,President'],
            'region_id' => ['required_if:role,Coordinator,President,Member', 'nullable', 'exists:regions,id'],
            'position' => ['nullable', 'string', 'max:255'],
        ]);

        // 2. Prevent duplicate role/position assignments in the same region
        if ($request->filled('region_id')) {
            $exists = User::where('region_id', $request->region_id)
                ->where('role', $request->role)
                ->where('position', $request->position)
                ->exists();

            if ($exists) {
                return back()->withInput()->withErrors([
                    'role' => "This region already has an assigned {$request->role} for this position."
                ]);
            }
        }

        // 3. Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'region_id' => $request->region_id,
            'position' => $request->position,
        ]);

        event(new Registered($user));

        // NOTE: I removed Auth::login($user) so the current user 
        // (the one doing the registering) stays logged in.

        // Redirect back to the registration page with the success message
        return redirect()->route('register')
            ->with('success', 'Account has been registered successfully! Contact your president for activation of your account.');
    }
}