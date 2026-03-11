<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Region;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Organization::query()->with('region');

        // Filter by Search (Name or Acronym)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('acronym', 'like', '%' . $request->search . '%');
            });
        }

        if ($user->role !== 'admin') {
            $query->where('region_id', $user->region_id);
        }

        // Filter by Region
        if ($request->filled('region')) {
            $query->where('region_id', $request->region);
        }

        // Get the results
        $organizations = $query->latest()->paginate(10)->withQueryString();
        $regions = Region::all();

        return view('organizations.index', compact('organizations', 'regions'));
    }

    public function create()
    {
        // 1. Fetch regions for the dropdown
        $regions = Region::orderBy('id', 'asc')->get();

        // 2. Fetch existing organizations to fix the "Undefined variable" error
        // We use with('region') to eager load the relationship and avoid N+1 issues
        $organizations = Organization::with('region')->orderBy('created_at', 'desc')->get();

        return view('organizations.create', compact('regions', 'organizations'));
    }

    /**
     * Store a newly created organization in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // 1. Basic Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'acronym' => 'nullable|string|max:20',
            'category' => 'required|in:LGU,PO,NGO,Academe',
            'region_id' => 'required|exists:regions,id',
        ]);

        // 2. Security Check: Enforce Regional Restriction
        if (!$user->hasRole('admin')) {
            if ($request->region_id != $user->region_id) {
                return back()->withErrors(['region_id' => 'You are only authorized to add organizations for your own region.']);
            }
        }

        // 3. Create the record
        Organization::create($validated);

        return redirect()->route('organizations.index')->with('success', 'Organization registered successfully.');
    }

    public function update(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'name' => 'required|string|max:255|unique:organizations,name,' . $organization->id,
            'acronym' => 'nullable|string|max:50',
        ]);

        $organization->update($validated);

        return back()->with('success', 'Organization updated successfully.');
    }
}
