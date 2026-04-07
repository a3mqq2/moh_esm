<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Hospital;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::with(['departments', 'wards'])->get();

        $totalHospitals = $hospitals->count();
        $activeHospitals = $hospitals->where('is_active', true)->count();
        $inactiveHospitals = $totalHospitals - $activeHospitals;

        $totalDepartments = Department::count();
        $totalWards = Ward::count();
        $totalUsers = User::count();

        $usersByRole = [
            'admin' => User::where('role', 'admin')->count(),
            'hospital_manager' => User::where('role', 'hospital_manager')->count(),
            'observer' => User::where('role', 'observer')->count(),
        ];

        // Bed totals across all hospitals
        $totalBeds = 0;
        $totalVacant = 0;
        foreach ($hospitals as $h) {
            $totalBeds += $h->departments->sum('pivot.beds') + $h->wards->sum('pivot.beds');
            $totalVacant += $h->departments->sum('pivot.vacant_beds') + $h->wards->sum('pivot.vacant_beds');
        }
        $totalOccupied = $totalBeds - $totalVacant;
        $occupancyRate = $totalBeds > 0 ? round(($totalOccupied / $totalBeds) * 100) : 0;

        // Top 5 hospitals by capacity
        $topHospitals = $hospitals->map(function ($h) {
            $beds = $h->departments->sum('pivot.beds') + $h->wards->sum('pivot.beds');
            $vacant = $h->departments->sum('pivot.vacant_beds') + $h->wards->sum('pivot.vacant_beds');
            return [
                'name' => $h->name,
                'logo' => $h->logo ? asset('storage/' . $h->logo) : null,
                'beds' => $beds,
                'vacant' => $vacant,
                'occupied' => $beds - $vacant,
                'rate' => $beds > 0 ? round((($beds - $vacant) / $beds) * 100) : 0,
            ];
        })->sortByDesc('beds')->take(5)->values();

        // Critical hospitals (vacant <= 2)
        $criticalHospitals = $hospitals->map(function ($h) {
            $beds = $h->departments->sum('pivot.beds') + $h->wards->sum('pivot.beds');
            $vacant = $h->departments->sum('pivot.vacant_beds') + $h->wards->sum('pivot.vacant_beds');
            return [
                'name' => $h->name,
                'beds' => $beds,
                'vacant' => $vacant,
            ];
        })->filter(function ($h) {
            return $h['beds'] > 0 && $h['vacant'] <= 2;
        })->sortBy('vacant')->take(8)->values();

        return view('dashboard', compact(
            'totalHospitals', 'activeHospitals', 'inactiveHospitals',
            'totalDepartments', 'totalWards', 'totalUsers', 'usersByRole',
            'totalBeds', 'totalVacant', 'totalOccupied', 'occupancyRate',
            'topHospitals', 'criticalHospitals'
        ));
    }
}
