<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use Illuminate\Http\Request;

class WardController extends Controller
{
    public function index()
    {
        $wards = Ward::latest()->paginate(20);
        return view('wards.index', compact('wards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:wards,name',
        ], [
            'name.required' => 'اسم العناية مطلوب.',
            'name.unique' => 'اسم العناية موجود مسبقاً.',
            'name.max' => 'اسم العناية يجب ألا يتجاوز 255 حرف.',
        ]);

        $ward = Ward::create($request->only('name'));

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة العناية بنجاح.',
            'ward' => $ward,
        ]);
    }

    public function update(Request $request, Ward $ward)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:wards,name,' . $ward->id,
        ], [
            'name.required' => 'اسم العناية مطلوب.',
            'name.unique' => 'اسم العناية موجود مسبقاً.',
            'name.max' => 'اسم العناية يجب ألا يتجاوز 255 حرف.',
        ]);

        $ward->update($request->only('name'));

        return response()->json([
            'success' => true,
            'message' => 'تم تعديل العناية بنجاح.',
            'ward' => $ward,
        ]);
    }

    public function destroy(Ward $ward)
    {
        $ward->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف العناية بنجاح.',
        ]);
    }
}
