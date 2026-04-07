<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Hospital;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::latest()->paginate(20);
        $departments = Department::orderBy('name')->get();
        $wards = Ward::orderBy('name')->get();
        return view('hospitals.index', compact('hospitals', 'departments', 'wards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:hospitals,name',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'location' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'اسم المستشفى مطلوب.',
            'name.unique' => 'اسم المستشفى موجود مسبقاً.',
            'logo.image' => 'الشعار يجب أن يكون صورة.',
            'logo.max' => 'حجم الشعار يجب ألا يتجاوز 2 ميجابايت.',
            'email.email' => 'البريد الإلكتروني غير صالح.',
        ]);

        $data = $request->only('name', 'location', 'email', 'latitude', 'longitude', 'notes');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('hospitals', 'public');
        }

        $hospital = Hospital::create($data);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المستشفى بنجاح.',
            'hospital' => $hospital,
            'logo_url' => $hospital->logo ? asset('storage/' . $hospital->logo) : null,
        ]);
    }

    public function update(Request $request, Hospital $hospital)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:hospitals,name,' . $hospital->id,
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'location' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'اسم المستشفى مطلوب.',
            'name.unique' => 'اسم المستشفى موجود مسبقاً.',
            'logo.image' => 'الشعار يجب أن يكون صورة.',
            'logo.max' => 'حجم الشعار يجب ألا يتجاوز 2 ميجابايت.',
            'email.email' => 'البريد الإلكتروني غير صالح.',
        ]);

        $data = $request->only('name', 'location', 'email', 'latitude', 'longitude', 'notes');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            if ($hospital->logo) {
                Storage::disk('public')->delete($hospital->logo);
            }
            $data['logo'] = $request->file('logo')->store('hospitals', 'public');
        }

        $hospital->update($data);

        return response()->json([
            'success' => true,
            'message' => 'تم تعديل المستشفى بنجاح.',
            'hospital' => $hospital,
            'logo_url' => $hospital->logo ? asset('storage/' . $hospital->logo) : null,
        ]);
    }

    public function syncUnits(Request $request, Hospital $hospital)
    {
        $request->validate([
            'departments' => 'nullable|array',
            'departments.*.id' => 'required|exists:departments,id',
            'departments.*.beds' => 'required|integer|min:0',
            'wards' => 'nullable|array',
            'wards.*.id' => 'required|exists:wards,id',
            'wards.*.beds' => 'required|integer|min:0',
        ]);

        // Sync departments
        $deptSync = [];
        foreach ($request->input('departments', []) as $dept) {
            $deptSync[$dept['id']] = ['beds' => $dept['beds']];
        }
        $hospital->departments()->sync($deptSync);

        // Sync wards
        $wardSync = [];
        foreach ($request->input('wards', []) as $ward) {
            $wardSync[$ward['id']] = ['beds' => $ward['beds']];
        }
        $hospital->wards()->sync($wardSync);

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ الأقسام والعنايات بنجاح.',
            'hospital' => $hospital->load(['departments', 'wards']),
        ]);
    }

    public function getUnits(Hospital $hospital)
    {
        return response()->json([
            'departments' => $hospital->departments->map(function ($d) {
                return ['id' => $d->id, 'name' => $d->name, 'beds' => $d->pivot->beds, 'vacant_beds' => $d->pivot->vacant_beds];
            }),
            'wards' => $hospital->wards->map(function ($w) {
                return ['id' => $w->id, 'name' => $w->name, 'beds' => $w->pivot->beds, 'vacant_beds' => $w->pivot->vacant_beds];
            }),
        ]);
    }

    public function destroy(Hospital $hospital)
    {
        if ($hospital->logo) {
            Storage::disk('public')->delete($hospital->logo);
        }

        $hospital->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المستشفى بنجاح.',
        ]);
    }
}
