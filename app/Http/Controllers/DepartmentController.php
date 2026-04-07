<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->paginate(20);
        return view('departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ], [
            'name.required' => 'اسم القسم مطلوب.',
            'name.unique' => 'اسم القسم موجود مسبقاً.',
            'name.max' => 'اسم القسم يجب ألا يتجاوز 255 حرف.',
        ]);

        $department = Department::create($request->only('name'));

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة القسم بنجاح.',
            'department' => $department,
        ]);
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
        ], [
            'name.required' => 'اسم القسم مطلوب.',
            'name.unique' => 'اسم القسم موجود مسبقاً.',
            'name.max' => 'اسم القسم يجب ألا يتجاوز 255 حرف.',
        ]);

        $department->update($request->only('name'));

        return response()->json([
            'success' => true,
            'message' => 'تم تعديل القسم بنجاح.',
            'department' => $department,
        ]);
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف القسم بنجاح.',
        ]);
    }
}
