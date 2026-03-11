<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'user_id' => 'required',
            'external_link' => 'nullable|url',
            'pdf_file' => 'nullable|mimes:pdf|max:5120', // Limit to 5MB
        ]);

        if ($request->hasFile('pdf_file')) {
            $validated['pdf_path'] = $request->file('pdf_file')->store('memos', 'public');
        }

        Announcement::create($validated);
        return back()->with('success', 'Announcement posted successfully!');
    }

    public function update(Request $request, Announcement $announcement)
    {
        $announcement->update($request->all());
        return back()->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement deleted.');
    }
}