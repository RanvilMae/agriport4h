<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Member;
use App\Models\Organization;
use App\Models\Announcement;
use App\Models\AuditLog;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Use query() instead of all() so we can chain where() clauses
        $memberQuery = Member::query();

        // STRICT REGIONAL FILTERING
        if ($user->role !== 'admin') {
            // This ensures they ONLY ever see members sharing their region_id
            $memberQuery->where('region_id', $user->region_id);
        }

        // Now, when you fetch for the table, it's already filtered
        $recent_members = $memberQuery->latest()->take(10)->get();
        $orgQuery = Organization::query();

        // Role-Based Filtering
        if ($user->role === 'member') {
            // Members only see their own region's data
            $memberQuery->where('region_id', $user->region_id);
            $orgQuery->where('region_id', $user->region_id);

            // Personalize announcements: Global ones + their specific region
            $announcements = Announcement::where('target_region', $user->region_id)
                ->orWhere('target_region', 'Global')
                ->latest()
                ->take(5)
                ->get();
        } else {
            // Admins see everything
            $announcements = Announcement::latest()->take(5)->get();
        }

        // Top Stats
        $total_members = $memberQuery->count();
        $queue_count = DB::table('jobs')->count();
        $audit_logs = AuditLog::with('user')->latest()->take(5)->get();

        // Chart Data (Filtered by role)
        $chartQuery = Member::join('regions', 'members.region_id', '=', 'regions.id')
            ->select('regions.name', DB::raw('count(members.id) as total'));

        if ($user->role === 'member') {
            $chartQuery->where('members.region_id', $user->region_id);
        }

        $chartData = $chartQuery->groupBy('regions.name')->get();

        $data = [
            'total_members' => $total_members,
            'announcements' => $announcements,
            'queue_count' => $queue_count,
            'audit_logs' => $audit_logs,
            'recent_members' => $memberQuery->latest()->take(10)->get(),
            'chartLabels' => $chartData->pluck('name'),
            'chartCounts' => $chartData->pluck('total'),
            'active_regions_count' => Organization::where('is_active', true)->count(),

            // Regional Progress (Shows local reach if member)
            'regional_stats' => Region::withCount([
                'members' => function ($q) use ($user) {
                    if ($user->role === 'member')
                        $q->where('region_id', $user->region_id);
                }
            ])->get()->map(function ($reg) use ($total_members) {
                return (object) [
                    'name' => $reg->name,
                    'percentage' => $total_members > 0 ? round(($reg->members_count / $total_members) * 100) : 0
                ];
            }),

            'activities' => collect([
                (object) ['title' => 'ICT Benchmarking - Field Office VIII', 'location' => 'Palo, Leyte', 'date_range' => 'Mar 10 - 15', 'is_ongoing' => true],
                (object) ['title' => 'Siglat 4-H Regional Summit', 'location' => 'MLU Campus', 'date_range' => 'April 02', 'is_ongoing' => false],
            ]),
        ];

        return view('dashboard', $data);
    }
}