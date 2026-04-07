<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('hospital')->latest()->paginate(20);
        $hospitals = Hospital::where('is_active', true)->orderBy('name')->get();
        return view('users.index', compact('users', 'hospitals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,hospital_manager,observer',
            'hospital_id' => 'nullable|required_if:role,hospital_manager|exists:hospitals,id',
        ], [
            'name.required' => 'الاسم مطلوب.',
            'username.required' => 'اسم المستخدم مطلوب.',
            'username.unique' => 'اسم المستخدم موجود مسبقاً.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل.',
            'role.required' => 'نوع المستخدم مطلوب.',
            'hospital_id.required_if' => 'يجب تحديد المستشفى لمسؤول المستشفى.',
        ]);

        $data = $request->only('name', 'username', 'password', 'role');
        $data['hospital_id'] = $request->role === 'hospital_manager' ? $request->hospital_id : null;
        $data['is_active'] = $request->boolean('is_active', true);

        $user = User::create($data);
        $user->load('hospital');

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المستخدم بنجاح.',
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,hospital_manager,observer',
            'hospital_id' => 'nullable|required_if:role,hospital_manager|exists:hospitals,id',
        ], [
            'name.required' => 'الاسم مطلوب.',
            'username.required' => 'اسم المستخدم مطلوب.',
            'username.unique' => 'اسم المستخدم موجود مسبقاً.',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل.',
            'role.required' => 'نوع المستخدم مطلوب.',
            'hospital_id.required_if' => 'يجب تحديد المستشفى لمسؤول المستشفى.',
        ]);

        $data = $request->only('name', 'username', 'role');
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }
        $data['hospital_id'] = $request->role === 'hospital_manager' ? $request->hospital_id : null;
        $data['is_active'] = $request->boolean('is_active', true);

        $user->update($data);
        $user->load('hospital');

        return response()->json([
            'success' => true,
            'message' => 'تم تعديل المستخدم بنجاح.',
            'user' => $user,
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'لا يمكنك حذف حسابك.'], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المستخدم بنجاح.',
        ]);
    }
}
