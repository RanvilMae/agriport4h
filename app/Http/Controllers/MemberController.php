<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Region;
use App\Models\LsaLevel;
use App\Models\Suffix;
use App\Models\Province;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');

        // 1. Fetch the regions based on the user's role/jurisdiction
        // If the user has a 'region_id', they are restricted to that region.
        // If not (Admin), they get all regions.
        $regions = \App\Models\Region::when($user->region_id, function ($query) use ($user) {
            return $query->where('id', $user->region_id);
        })->get();

        // 2. Fetch members, applying the same regional restriction and search filter
        $members = \App\Models\Member::with(['region', 'organization'])
            ->when($user->region_id, function ($query) use ($user) {
                return $query->where('region_id', $user->region_id);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(15);

        // 3. Pass both $members AND $regions to the view
        return view('members.index', compact('members', 'regions'));
    }

    public function create()
    {
        $user = auth()->user();
        $query = Region::with(['provinces', 'organizations'])->orderBy('id');

        // ROLE SCOPING: Only show their region in the dropdown
        if (in_array($user->role, ['President', 'Coordinator'])) {
            $query->where('id', $user->region_id);
        }

        $regions = $query->get();
        $lsaLevels = LsaLevel::all();
        $suffixes = Suffix::all();

        return view('members.create', [
            'regions' => $regions,
            'userRegionId' => auth()->user()->region_id ?? '', // Adjust field name to your DB
        ]);
    }

    public function show($id)
    {
        $member = Member::with(['region', 'province'])->findOrFail($id);

        // Security check: Don't let users see members outside their region
        if (auth()->user()->role !== 'Admin' && $member->region_id !== auth()->user()->region_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('members.id', compact('member'));
    }

    public function store(Request $request)
    {
        // Ensure 'member_type' is synced from the select if 'Others' wasn't picked
        // This handles cases where Alpine might not have updated the hidden field yet.
        if ($request->member_type_select !== 'Others') {
            $request->merge(['member_type' => $request->member_type_select]);
        }

        $validated = $this->validateMember($request);

        // Sync arrays and dynamic fields
        $validated['crops'] = $request->input('crops', []);
        $validated['services'] = $request->input('services', []);

        // Handle the Training Course specifically
        if ($request->training_type_select === 'Others') {
            $validated['training_course'] = $request->other_training;
        } else {
            $validated['training_course'] = $request->training_type_select;
        }

        Member::create($validated);

        return redirect()->route('members.index')->with('success', 'Member registered successfully! Unique ID has been generated.');
    }

    public function edit(Member $member)
    {
        // Security check
        if (auth()->user()->role !== 'Admin' && $member->region_id !== auth()->user()->region_id) {
            abort(403);
        }

        $user = auth()->user();
        $regionQuery = Region::with('provinces');

        if (in_array($user->role, ['President', 'Coordinator'])) {
            $regionQuery->where('id', $user->region_id);
        }

        $regions = $regionQuery->get();
        $provinces = Province::where('region_id', $member->region_id)->get();
        $suffixes = Suffix::all();
        $lsaLevels = LsaLevel::all();

        return view('members.edit', compact('member', 'regions', 'provinces', 'suffixes', 'lsaLevels'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $this->validateMember($request, $member->id);

        $validated['crops'] = $request->input('crops', []);
        $validated['services'] = $request->input('services', []);

        if ($request->training_type_select === 'Others') {
            $validated['training_course'] = $request->other_training;
        }

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Member record updated successfully!');
    }

    public function updateSelf(Request $request)
    {
        $member = auth()->user()->member; // Using the relationship

        $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_no' => 'required',
        ]);

        $member->update($request->only(['full_name', 'contact_no', 'address']));

        return back()->with('success', 'Your profile has been updated!');
    }

    public function destroy(Member $member)
    {
        if (auth()->user()->role !== 'Admin' && $member->region_id !== auth()->user()->region_id) {
            abort(403);
        }

        $member->delete();
        return redirect()->route('members.index')->with('success', 'Member deleted!');
    }

    public function downloadIdCard($id)
    {
        $member = Member::with(['region', 'province'])->findOrFail($id);

        if (auth()->user()->role !== 'Admin' && $member->region_id !== auth()->user()->region_id) {
            abort(403);
        }

        $pdf = Pdf::loadView('members.pdf-id', compact('member'))
            ->setPaper([0, 0, 250, 400], 'portrait');

        return $pdf->download($member->last_name . '-LSA-ID.pdf');
    }

    protected function validateMember(Request $request, $id = null)
    {
        $user = auth()->user();

        // FORCED REGION: If not admin, overwrite the region_id to the user's region
        // This prevents users from trying to submit data for other regions via DevTools
        if (in_array($user->role, ['President', 'Coordinator'])) {
            $request->merge(['region_id' => $user->region_id]);
        }

        $maxAgeDate = now()->subYears(30)->format('Y-m-d');
        // "Not less than 10" means they must be born before this date
        $minAgeDate = now()->subYears(10)->format('Y-m-d');

        return $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'required|string',
            'dob' => [
                'required',
                'date',
                "after_or_equal:$maxAgeDate",
                "before_or_equal:$minAgeDate",
            ],
            'contact_no' => 'required|string|max:20',
            'email' => 'required|email|unique:members,email,' . $id,
            'region_id' => 'required|exists:regions,id',
            'province_id' => 'required|exists:provinces,id',
            'city_municipality' => 'required|string',
            'district' => 'nullable|string',
            'barangay' => 'required|string',
            'zip_code' => 'nullable|string|max:4',
            'member_type' => 'required|string',
            'occupation' => 'nullable|string|max:255',
            'organization_id' => 'nullable|exists:organizations,id',
            'specialization' => 'required|string',
            'hvcdp_category' => 'nullable|required_if:specialization,HVCDP,Combination|string',
            'crops' => 'nullable|array',
            'services' => 'required|array',
            'internship' => 'nullable|string',
            'scholarship' => 'nullable|string',
            'lsa_level' => 'nullable|string',
            'lsa_type' => 'nullable|string',
            'training_course' => 'nullable|string',
        ], [
            'dob.after_or_equal' => 'The member must be 30 years old or younger.',
            'dob.before_or_equal' => 'The member must be at least 10 years old.',
        ]);
    }
}