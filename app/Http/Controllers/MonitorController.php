<?php

namespace App\Http\Controllers;

use App\Models\Hospital;

class MonitorController extends Controller
{
    public function index()
    {
        return view('monitor.index');
    }

    public function data()
    {
        $hospitals = Hospital::with(['departments', 'wards'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $payload = $hospitals->map(function ($h) {
            $depts = $h->departments->map(function ($d) {
                return [
                    'id' => $d->id,
                    'name' => $d->name,
                    'beds' => (int) $d->pivot->beds,
                    'vacant' => (int) $d->pivot->vacant_beds,
                ];
            });
            $wards = $h->wards->map(function ($w) {
                return [
                    'id' => $w->id,
                    'name' => $w->name,
                    'beds' => (int) $w->pivot->beds,
                    'vacant' => (int) $w->pivot->vacant_beds,
                ];
            });
            return [
                'id' => $h->id,
                'name' => $h->name,
                'logo' => $h->logo ? asset('storage/' . $h->logo) : null,
                'location' => $h->location,
                'total_beds' => $depts->sum('beds') + $wards->sum('beds'),
                'total_vacant' => $depts->sum('vacant') + $wards->sum('vacant'),
                'departments' => $depts->values(),
                'wards' => $wards->values(),
            ];
        });

        $totals = [
            'hospitals' => $payload->count(),
            'beds' => $payload->sum('total_beds'),
            'vacant' => $payload->sum('total_vacant'),
        ];

        return response()->json([
            'totals' => $totals,
            'hospitals' => $payload,
            'updated_at' => now()->format('H:i:s'),
        ]);
    }
}
