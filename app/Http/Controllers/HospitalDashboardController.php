<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HospitalDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $hospital = $user->hospital()->with(['departments', 'wards'])->firstOrFail();

        return view('hospital-dashboard.index', compact('hospital'));
    }

    public function updateVacant(Request $request)
    {
        $request->validate([
            'type' => 'required|in:department,ward',
            'id' => 'required|integer',
            'vacant_beds' => 'required|integer|min:0',
        ]);

        $user = auth()->user();
        $hospital = $user->hospital;

        if ($request->type === 'department') {
            $pivot = $hospital->departments()->where('department_id', $request->id)->first();
            if ($pivot) {
                $hospital->departments()->updateExistingPivot($request->id, [
                    'vacant_beds' => min($request->vacant_beds, $pivot->pivot->beds),
                ]);
            }
        } else {
            $pivot = $hospital->wards()->where('ward_id', $request->id)->first();
            if ($pivot) {
                $hospital->wards()->updateExistingPivot($request->id, [
                    'vacant_beds' => min($request->vacant_beds, $pivot->pivot->beds),
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
