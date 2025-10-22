<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        
        // Base query for applications
        $applicationsQuery = Application::query();
        
        // If not super admin, only show user's applications
        if (!$user->isSuperAdmin()) {
            $applicationsQuery->where('user_id', $user->id);
        }

        $totalApplications = $applicationsQuery->count();
        
        $statusBreakdown = $applicationsQuery
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $monthlyApplications = $applicationsQuery
            ->select(DB::raw('DATE_FORMAT(applied_date, "%Y-%m") as month'), DB::raw('count(*) as count'))
            ->where('applied_date', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $topCompanies = $applicationsQuery
            ->join('companies', 'applications.company_id', '=', 'companies.id')
            ->select('companies.name', DB::raw('count(*) as count'))
            ->groupBy('companies.id', 'companies.name')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get()
            ->toArray();

        $recentApplications = $applicationsQuery
            ->with(['company', 'job'])
            ->orderBy('applied_date', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'total_applications' => $totalApplications,
            'status_breakdown' => $statusBreakdown,
            'monthly_applications' => $monthlyApplications,
            'top_companies' => $topCompanies,
            'recent_applications' => $recentApplications,
        ]);
    }

    /**
     * Get application success rate
     */
    public function successRate(Request $request)
    {
        $user = $request->user();
        
        $applicationsQuery = Application::query();
        
        if (!$user->isSuperAdmin()) {
            $applicationsQuery->where('user_id', $user->id);
        }

        $totalApplications = $applicationsQuery->count();
        
        if ($totalApplications === 0) {
            return response()->json([
                'success_rate' => 0,
                'total_applications' => 0,
                'successful_applications' => 0,
            ]);
        }

        $successfulApplications = $applicationsQuery
            ->whereIn('status', ['offer_received', 'offer_accepted'])
            ->count();

        $successRate = ($successfulApplications / $totalApplications) * 100;

        return response()->json([
            'success_rate' => round($successRate, 2),
            'total_applications' => $totalApplications,
            'successful_applications' => $successfulApplications,
        ]);
    }

    /**
     * Get application timeline
     */
    public function timeline(Request $request)
    {
        $user = $request->user();
        
        $applicationsQuery = Application::query();
        
        if (!$user->isSuperAdmin()) {
            $applicationsQuery->where('user_id', $user->id);
        }

        $timeline = $applicationsQuery
            ->with(['company', 'job'])
            ->orderBy('applied_date', 'desc')
            ->get()
            ->map(function ($application) {
                return [
                    'id' => $application->id,
                    'company' => $application->company->name,
                    'job_title' => $application->job?->title,
                    'status' => $application->status,
                    'applied_date' => $application->applied_date,
                    'created_at' => $application->created_at,
                ];
            });

        return response()->json($timeline);
    }
}
